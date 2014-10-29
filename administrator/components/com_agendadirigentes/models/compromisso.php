<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Compromisso Model
 */
class AgendaDirigentesModelCompromisso extends JModelAdmin
{
    protected $dirigentes_agendas_alteradas = array();

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       2.5
     */
    public function getTable($type = 'Compromisso', $prefix = 'AgendaDirigentesTable', $config = array()) 
    {
            return JTable::getInstance($type, $prefix, $config);
    }
    /**
     * Method to get the record form.
     *
     * @param       array   $data           Data for the form.
     * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
     * @return      mixed   A JForm object on success, false on failure
     * @since       2.5
     */
    public function getForm($data = array(), $loadData = true) 
    {
            // Get the form.
            $form = $this->loadForm('com_agendadirigentes.compromisso', 'compromisso',
                                    array('control' => 'jform', 'load_data' => $loadData));
            if (empty($form)) 
            {
                    return false;
            }
            return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return      mixed   The data for the form.
     * @since       2.5
     */
    protected function loadFormData() 
    {
            // Check the session for previously entered form data.
            $data = JFactory::getApplication()->getUserState('com_agendadirigentes.edit.compromisso.data', array());

            if (empty($data)) 
            {
                $data = $this->getItemExtended();
            }

            return $data;
    }

    protected function getItemExtended($pk = NULL, $get_sobrepostos = false)
    {
        $data = $this->getItem($pk);
        if(@isset($data->id))
        {                            
            $data->owner = $this->getOwner($data);
            $data->dirigentes = $this->getParticipantes($data);

            if($get_sobrepostos)
            {
                $data->compromissos_sobrepostos = $this->getCompromissosSobrepostos($data);
            }
            else
            {
                $data->compromissos_sobrepostos = NULL;
            }

        }
        else
        {
            $data->owner = NULL;
            $data->dirigentes = array();
            $data->compromissos_sobrepostos = NULL;
        }
        return $data;
    }

    protected function getItems($pks = array(), $fields = '*', $pk_key = false)
    {
        $db = JFactory::getDBO();
        
        if(!is_array($pks))
            $pks = array( (int) $pks );

        for ($i=0, $limit = count($pks); $i < $limit; $i++) { 
            $pks[$i] = (int) $pks[$i];
        }

        if ($fields != '*')
        {
            if( $pk_key )
            {
                if(array_search('id', $fields) === false)
                    $fields[] = 'id';
            }

            for ($i=0, $limit = count($fields); $i < $limit; $i++)
            {
                $fields[$i] = $db->quoteName( $fields[$i] );
            } 
        }

        $query = $db->getQuery( true );
        
        $query->select(
                $fields
            )->from(
                $db->quoteName('#__agendadirigentes_compromissos')
            )->where(
                $db->quoteName('id') . ' IN (' .
                    implode(', ', $pks) . ')'
            );

        $db->setQuery((string) $query);
        
        if(! $pk_key )
            return $db->loadObjectList();

        return $db->loadObjectList('id');
    }

    protected function getItemsExtended($pks = array(), $fields = '*', $pk_key = false)
    {
        $multiple = true;
        $get_owners_participantes = 0;
        $pk_key_participantes = true;        
        $arr_compromisso_id = array();
        
        $items = $this->getItems($pks, $fields, $pk_key);

        foreach ($items as $item)
        {
            $arr_compromisso_id[] = $item->id;
        }

        $owners = $this->getOwner($arr_compromisso_id, $multiple);
        
        $participantes = $this->getParticipantes(
                            $arr_compromisso_id,
                            $multiple,
                            $get_owners_participantes,
                            $pk_key_participantes
                        );

        foreach ($items as $k => &$item) {
            $item->owner = $owners[ $item->id ];
            $item->dirigentes = $participantes[ $item->id ];
        }

        return $items;
    }

    protected function getOwner( $data = NULL, $multiple = false )
    {
        if (is_null($data))
        {
            $data = $this->getItem();
            $multiple = false;
        }
        elseif (is_array($data) && !$multiple)
        {
            $obj = new StdClass();
            @$obj->id = (int) $data['id'];
            $data = $obj;
        }
        elseif (is_array($data) && $multiple)
        {
            for ($i=0, $limit = count($data); $i < $limit; $i++) { 
                $data[$i] = (int) $data[$i];
            }
        }
        elseif (! is_object($data))
            return NULL;

        $query = $this->_db->getQuery(true);
        $query->select(
                    $this->_db->quoteName('dirigente_id')
                )->from(
                    $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos')
                )->where(
                    $this->_db->quoteName('owner') . ' = 1'
                );

        if(!$multiple)
        {
            $query->where(
                        $this->_db->quoteName('compromisso_id') . ' = ' . (int) $data->id
                    );
        }
        else
        {
            $query->select(
                $this->_db->quoteName('compromisso_id')
            );

            $query->where(
                $this->_db->quoteName('compromisso_id') . ' IN ('
                    . implode(', ', $data) . ')'
            );
        }

        $this->_db->setQuery((string) $query);

        if(!$multiple)
            return $this->_db->loadResult();

        //multiple == true
        $array = array();
        $result = $this->_db->loadObjectList();
        foreach ($result as $v)
        {
            $array[ $v->compromisso_id ] = $v->dirigente_id;
        }

        return $array;

    }

    protected function getParticipantes( $data = NULL, $multiple = false, $owner = 0, $pk_key = false )
    {
        if (is_null($data))
        {
            $data = $this->getItem();
            $multiple = false;
            $pk_key = false;
        }
        elseif (is_array($data) && !$multiple)
        {
            $obj = new StdClass();
            @$obj->id = (int) $data['id'];
            $data = $obj;
            $pk_key = false;
        }
        elseif (is_array($data) && $multiple)
        {
            for ($i=0, $limit = count($data); $i < $limit; $i++) { 
                $data[$i] = (int) $data[$i];
            }
        }
        elseif (! is_object($data))
            return NULL;

        $query = $this->_db->getQuery(true);
        $query->select(
                $this->_db->quoteName('dirigente_id')
            )
            ->from(
                $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos')
            );

        if(! $multiple)
        {
            $query->where(
                    $this->_db->quoteName('compromisso_id') . ' = ' . (int) $data->id
                );                
        }
        else
        {
            $query->select(
                $this->_db->quoteName('compromisso_id')
            );

            $query->where(
                $this->_db->quoteName('compromisso_id') . ' IN ('
                    . implode(', ', $data) . ')'
            );
        }
        
        if(! is_null($owner))
            $query->where( $this->_db->quoteName('owner') . ' = 0' );
        
        $this->_db->setQuery( (string) $query);
        
        $array = array();

        if(! $pk_key )
        {
            $result = $this->_db->loadRowList();
            foreach ($result as $v)
            {
                $array[] = $v[0];
            }
        }
        else
        {
            $result = $this->_db->loadObjectList();
            foreach ($result as $v)
            {
                if(! isset($array[ $v->compromisso_id ]) )
                    $array[ $v->compromisso_id ] = array();

                $array[ $v->compromisso_id ][] = $v->dirigente_id;
            }
        }

        return $array;
    }

    protected function getCompromissosSobrepostos( $data = NULL )
    {
        if (is_null($data))
        {
            $data = $this->getItem();
        }
        elseif (is_array($data))
        {
            $obj = new StdClass();
            @$obj->id = (int) $data['id'];
            $data = $obj;
        }
        $query = $this->_db->getQuery(true);
        $query->select( 'DISTINCT ' . $this->_db->quoteName('compromisso_id') );
        $query->from( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
        $query->where( $this->_db->quoteName('sobreposto_por') . ' = ' . $data->id );

        $this->_db->setQuery( (string) $query);
        
        $result = $this->_db->loadRowList();
        $array = array();
        foreach ($result as $k => $v) {
                $array[] = $v[0];
        }
        return $array;
    }

    public function save($data)
    {
        if(@$data['id'] > 0)
            $olddata = $this->getItemExtended( $data['id'], true );
        else
            $olddata = NULL;

        $result = parent::save($data);
        
        if(!$result)
            return false;

        if($data['id']==0)
            $data['id'] = $this->_db->insertid();

        if ($olddata)
        {
            $compromissoAlterado = $this->isCompromissoAlterado($data, $olddata);

            if($compromissoAlterado)
            {
                $this->updateAgendaAlteradaInfo( $data );
            }
        }
        else
        {
            $compromissoAlterado = false;
        }

        if(!$this->updateCompromissosDirigentes($data, $compromissoAlterado, $olddata))
            return false;

        return true;
    }

    protected function updateCompromissosDirigentes($data, $compromissoAlterado = false, $olddata = NULL)
    {
            if (@isset($data['id'])===false)
                return false;

            if(! $this->clearCompromissosDirigentes($data) )
                return false;

            if(! $this->insertCompromissosDirigentes($data, $compromissoAlterado, $olddata) )
                return false;

            return true;
    }

    protected function clearCompromissosDirigentes($data)
    {
        $db = $this->_db;

        //preparando input para array
        if( is_array($data) && isset($data['id']) )
        {
            $ids = array( (int) $data['id'] );
        }
        elseif( is_array($data) )
        {
            $ids = $data;
        }
        else
        {
            $ids = array( (int) $data );
        }

        $ids = implode(', ', $ids);

        //removendo sobreposicoes
        $query = $db->getQuery(true);
        $fields = array(
                $db->quoteName('sobreposto') . ' = 0',
                $db->quoteName('sobreposto_por') . ' = 0'
            );
        $conditions = array(
                $db->quoteName('sobreposto_por') . ' IN (' . $ids . ')'
            );

        $query->update(
                $db->quoteName('#__agendadirigentes_dirigentes_compromissos')
            )->set( $fields )->where( $conditions );

        $db->setQuery((string)$query);

        if( ! $db->query() )
            return false;

        // limpando registros de participantes para o compromisso em questao
        $query = $db->getQuery(true);
        $query->delete(
            $db->quoteName('#__agendadirigentes_dirigentes_compromissos')
        );
        $query->where(
            $db->quoteName('compromisso_id') .' IN (' . $ids . ')'
        );
        $db->setQuery($query);

        return $db->query();
    }

    protected function insertCompromissosDirigentes($data, $compromissoAlterado = false, $olddata = NULL)
    {
        //variaveis iniciais de input
        $owner = @$data['owner'];
        $app = JFactory::getApplication();
        $db = $this->_db;
        $session = JFactory::getSession();

        //formatando owner e finalizando execucao quando valor for indevido
        if (is_array($owner)) {
            $owner = (int) $owner[0];
        }
        $owner = (int) $owner;
        
        if($owner==0)
            return false;

        //alterando formato dos dados antes do salvamento (olddata)
        if (! is_null($olddata))
        {
            $olddata = (array) $olddata;
        }

        //montagem dos itens que serao inseridos na tabela #__agendadirigentes_dirigentes_compromissos
        $items = array();
        $items[] = array(
                        'dirigente_id' => $owner,
                        'compromisso_id' => intval($data['id']),
                        'owner' => 1,
                        'sobreposto' => 0,
                        'sobreposto_por' => 0
                );

        $dirigentes = @$data['dirigentes'];
        $ids_dirigentes = array();

        if (is_array($dirigentes)) {
            for ($i=0, $limit = count($dirigentes); $i < $limit; $i++) { 
                if (is_numeric($dirigentes[$i])) { //grava somente os itens que possuem ID, ou seja, dirigentes cadastrados. Itens nao numericos sao participantes externos
                    $items[] = array(
                            'dirigente_id' => $dirigentes[$i],
                            'compromisso_id' => $data['id'],
                            'owner' => 0,
                            'sobreposto' => 0,
                            'sobreposto_por' => 0
                    );
                    $ids_dirigentes[ $dirigentes[$i] ] = $dirigentes[$i];
                }
            }
        }

        if (count($ids_dirigentes))
        {
            //verificar se ha dirigentes que nao podem ter compromissos sobrepostos
            $query = $db->getQuery(true);
            $query->select(
                    $db->quoteName('dir.id') . ', ' .
                    $db->quoteName('dir.name')
                )
                ->from(
                    $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_cargos', 'car')
                    .' ON (' . $db->quoteName('dir.cargo_id') . ' = ' . $db->quoteName('car.id') . ')'
                )
                ->where(
                    $db->quoteName('permitir_sobreposicao') . ' = 0'
                    . ' AND ' .
                    $db->quoteName('dir.id') . ' IN (' . implode(', ', $ids_dirigentes) . ')'
                );
            $db->setQuery((string)$query);
            $dirigentes_nao_permitem_sobrepor = $db->loadObjectList('id');
            
            //avisar sobre dirigentes que nao podem ter compromissos sobrepostos e remover do array de ids de ids_dirigentes
            foreach ($dirigentes_nao_permitem_sobrepor as $id => $obj) {
                if( array_key_exists($id, $ids_dirigentes) )
                {
                    unset( $ids_dirigentes[ $id ] );
                    $nomeDirigente = $obj->name;
                    $app->enqueueMessage(JText::_('COM_AGENDADIRIGENTES_MODELS_COMPROMISSO_NO_OVERRIDE'), 'Warning');
                }
            }

            //formatando datas para as proximas acoes
            $data['data_inicial'] = $this->mysql_convert( $data['data_inicial'] );
            $data['data_final'] = $this->mysql_convert( $data['data_final'] );

            //verificar ids dos itens que serao sobrepostos, aproveitando array atualizado de ids_dirigentes
            //essa verificacao precisa ser feita antes para impedir a insercao de itens que ja foram sobrepostos por outros compromissos
            $query = $db->getQuery(true);
            $query->select(
                    $db->quoteName('dc.compromisso_id') . ', ' .
                    $db->quoteName('dc.dirigente_id') . ', ' .
                    $db->quoteName('dc.owner') . ', ' .
                    $db->quoteName('dc.sobreposto') . ', ' .
                    $db->quoteName('dc.sobreposto_por') . ', ' .
                    $db->quoteName('comp.title', 'compromisso_title') . ', ' .
                    $db->quoteName('dir.name', 'dirigente_name')
                )
                ->from(
                    $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                    .' ON (' . $db->quoteName('dir.id') . ' = ' . $db->quoteName('dc.dirigente_id') . ')'
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_compromissos', 'comp')
                    .' ON (' . $db->quoteName('comp.id') . ' = ' . $db->quoteName('dc.compromisso_id') . ')'
                )
                ->where(
                    $db->quoteName('comp.data_inicial') . ' >= ' .
                    $db->Quote( $data['data_inicial'] )
                    . ' AND ' .
                    $db->quoteName('comp.data_final') . ' <= ' .
                    $db->Quote( $data['data_final'] )
                    . ' AND ' .
                    $db->quoteName('comp.horario_inicio') . ' >= ' .
                    $db->Quote( $data['horario_inicio'] )
                    . ' AND ' .
                    $db->quoteName('comp.horario_fim') . ' <= ' .
                    $db->Quote( $data['horario_fim'] )
                    . ' AND ' .
                    $db->quoteName('dir.id') . ' IN (' . implode(', ', $ids_dirigentes) . ')'
                    . ' AND ' .
                    $db->quoteName( 'comp.id' ) . ' <> ' . (int) $data['id']
                );
            $db->setQuery((string) $query);
            $sobrepostos = $db->loadObjectList();

            //inclui na lista de dirigentes sem sobreposicao aqueles com compromissos ja sobrepostos para a data pretendida
            for ($i=0, $limit = count($sobrepostos); $i < $limit; $i++) { 
                if($sobrepostos[$i]->sobreposto == 1)
                {
                    if( ! array_key_exists($sobrepostos[$i]->dirigente_id,  $dirigentes_nao_permitem_sobrepor) )
                    {
                        $key = $sobrepostos[$i]->dirigente_id;
                        $dirigentes_nao_permitem_sobrepor[ $key ] = new StdClass();
                        $dirigentes_nao_permitem_sobrepor[ $key ]->id = $sobrepostos[$i]->dirigente_id;
                        $dirigentes_nao_permitem_sobrepor[ $key ]->name = $sobrepostos[$i]->dirigente_name;

                        $app->enqueueMessage( 
                            sprintf( JText::_('COM_AGENDADIRIGENTES_MODELS_COMPROMISSO_ALREADY_OVERRIDDEN'), $sobrepostos[$i]->dirigente_name)
                            , 'Warning');
                        
                    }                        
                }
            }
        }
        else
        {
            $dirigentes_nao_permitem_sobrepor = array();
            $sobrepostos = array();
            $compromissos_nao_permitem_sobrepor = array();
        }
        //fim if else count(dirigentes)

        //zerando o array principal de dirigentes
        $data['dirigentes'] = array();

        //inserir os itens que puderem ser inseridos, de acordo com as regras
        for ($i=0, $limit = count($items); $i < $limit; $i++)
        {
            //nao insere compromissos de dirigentes que nao permitem sobreposicao ou ja tiveram compromissos sobrepostos para mesmo dia e horario
            if( array_key_exists($items[$i]['dirigente_id'],  $dirigentes_nao_permitem_sobrepor) )
            {
                continue;
            }
            //nao insere os itens que foram retirados da tabela de ids_dirigentes e que nao correspondem ao owner
            else if( $items[$i]['owner'] != 1 && ! array_key_exists($items[$i]['dirigente_id'],  $ids_dirigentes) )
            {
                continue;
            }

            //insere os itens que podem ser inseridos
            $columns = array_keys($items[$i]);
            $values = array_values($items[$i]);
            $query = $db->getQuery(true);
            $query
                ->insert($db->quoteName('#__agendadirigentes_dirigentes_compromissos'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));

            $db->setQuery((string)$query);

            if (!$db->query()) {
                return false;
            }

            if( $items[$i]['owner'] != 1 )
                $data['dirigentes'][] = $items[$i]['dirigente_id'];

        } //fim for() insert compromissos x dirigentes

        //verifica alteracoes de participantes retirados para registro de alteracoes na agenda
        if($compromissoAlterado == true && !is_null($olddata))
        {
            //participantes foram adicionados, e depois removidos
            $diff_olddata_dirigentes = array();
            for ($j=0, $jlimit=count($olddata['dirigentes']); $j < $jlimit; $j++)
            { 
                if( array_search($olddata['dirigentes'][$j], $data['dirigentes']) === false )
                {
                    $diff_olddata_dirigentes[] = $olddata['dirigentes'][$j];
                }
            }
            if( count($diff_olddata_dirigentes) > 0 ) //itens retirados foram identificados
            {
                $this->updateAgendaAlteradaInfo( $data, 0, $diff_olddata_dirigentes );
            }

            //compromissos que haviam sido sobrepostos, e agora nao estao mais
            $diff_olddata_sobreposicoes = array();
            for ($k=0, $klimit = count($olddata['compromissos_sobrepostos']); $k < $klimit; $k++)
            {
                $olddata['compromissos_sobrepostos'][$k] = (int) $olddata['compromissos_sobrepostos'][$k];
                $found = false;
                for ($l=0, $llimit = count($sobrepostos); $l < $llimit; $l++) { 
                   
                   $sobrepostos[$l]->compromisso_id = (int)  $sobrepostos[$l]->compromisso_id;

                   if($olddata['compromissos_sobrepostos'][$k] == $sobrepostos[$l]->compromisso_id)
                   {
                        $found = true;
                        break;
                   }
                }
                if(! $found )
                {
                    $diff_olddata_sobreposicoes[] = $olddata['compromissos_sobrepostos'][$k];
                }
            }
            if( count($diff_olddata_sobreposicoes) > 0 ) //itens retirados foram identificados, e uma ultima atualizacao na agenda desses itens precisa ser informada
            {
                for ($j=0, $jlimit = count($diff_olddata_sobreposicoes); $j < $jlimit; $j++)
                { 
                    $pk = (int) $diff_olddata_sobreposicoes[$j];

                    if($pk == 0)
                        continue;

                    $tmp_data = (array) $this->getItemExtended($pk);
                    $tmp_data['data_inicial'] = $data['data_inicial'];
                    $tmp_data['data_final'] = $data['data_final'];

                    $this->updateAgendaAlteradaInfo( $tmp_data );
                }
            }

        }

        //sobrepoe somente se o compromisso estiver publicado (problema com troca de estados fora da edicao)
        if ( $data['state'] != 1 && count($sobrepostos) > 0 )
        {
            if( $session->get('msg_sobreposicoes_na_publicacao', 0) == 0 )
            {
                $app->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_MODELS_COMPROMISSO_ONLY_OVERRIDE_ON_PUBLISH'), 'Warning' );
                $session->set('msg_sobreposicoes_na_publicacao', 1);                
            }
            // return true;
        }

        // sobrepoe de acordo com os resultados do array de itens sobrepostos
        $compromissos_ja_sobrepostos = array();

        //registra os itens sobrepostos
        $data['compromissos_sobrepostos'] = array();

        //executa as sobreposicoes cabiveis
        for ($i=0, $limit = count($sobrepostos); $i < $limit; $i++)
        { 
            $item = $sobrepostos[$i];

            if( $item->sobreposto==1 ) //item ja foi sobreposto por outro compromisso entao pule
                continue;
            
            if(in_array($item->compromisso_id, $compromissos_ja_sobrepostos)) //sobreposicao completa ja realizada, entao pule
                continue;

            if ( $data['state'] == 1 )
            {
                $query = $db->getQuery(true);
                $fields = array(
                        $db->quoteName('sobreposto') . ' = 1',
                        $db->quoteName('sobreposto_por') . ' = ' . (int) $data['id']
                    );
                if( $item->owner == 1 ) //se sobrepor um compromisso do owner, todos os demais devem ser sobrepostos (sobreposicao completa)
                {
                    $conditions = array(
                            $db->quoteName('compromisso_id') . ' = ' . (int) $item->compromisso_id
                        );
                    $compromissos_ja_sobrepostos[] = $item->compromisso_id;
                }
                else //se sobrepor compromisso sem owner, somente ele precisa ser sobreposto
                {
                    $conditions = array(
                            $db->quoteName('compromisso_id') . ' = ' . (int) $item->compromisso_id,
                            $db->quoteName('dirigente_id') . ' = ' . (int) $item->dirigente_id
                        );
                }
                
                //executar sobreposicao no banco
                $query->update(
                        $db->quoteName('#__agendadirigentes_dirigentes_compromissos')
                    )->set( $fields )->where( $conditions );

                $db->setQuery((string)$query);
                $db->query();

                //aviso de sobreposicao
                if($i == 0  && $session->get('msg_ao_menos_um_sobreposto', 0) == 0)
                {
                    $session->set('msg_ao_menos_um_sobreposto', 1);
                    $app->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_MODELS_COMPROMISSO_ATLEAST_ONE_OVERRIDE'), 'Warning');
                }
            }

            //registrando alteracoes nos dias das agendas
            if( array_search(intval($item->compromisso_id), $data['compromissos_sobrepostos']) === false)
                $data['compromissos_sobrepostos'][] = (int) $item->compromisso_id;

        } // fim for() sobrepostos

        //registrando alteracoes em datas de agendas de compromissos sobrepostos, publicados ou nao
        if($compromissoAlterado == true)
        {
            for ($i=0, $limit = count($data['compromissos_sobrepostos']); $i < $limit; $i++)
            { 
                $pk = (int) $data['compromissos_sobrepostos'][$i];

                if($pk == 0)
                    continue;

                $tmp_data = (array) $this->getItemExtended($pk);
                $tmp_data['data_inicial'] = $data['data_inicial'];
                $tmp_data['data_final'] = $data['data_final'];

                $this->updateAgendaAlteradaInfo( $tmp_data );
            }
        }

        return true;
    }

    /**
     * Method to toggle the featured setting of articles.
     *
     * @param   array    The ids of the items to toggle.
     * @param   integer  The value to toggle to.
     *
     * @return  boolean  True on success.
     */
    public function featured($pks, $value = 0)
    {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);

        if (empty($pks))
        {
            $this->setError(JText::_('COM_AGENDADIRIGENTES_NENHUM_ITEM_SELECIONADO'));
            return false;
        }

        foreach($pks as &$pk)
        {
            $pk = intval($pk);
        }

        try
        {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                        ->update($db->quoteName('#__agendadirigentes_compromissos'))
                        ->set('featured = ' . (int) $value)
                        ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();

        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }

        $this->cleanCache();

        return true;
    }

    /**
     * Method to check if it's OK to delete a message. Overwrites JModelAdmin::canDelete
     */
    protected function canDelete($item)
    {            
        if( !empty( $item->catid ) )
        {
            return AgendaDirigentesHelper::getGranularPermissions( 'compromissos', $item->catid, 'delete' );
        }

        return false;
    }

    protected function canEditState($item)
    {
        list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('compromissos', $item, 'manage' );

        if($canChange)
            return true;
        
        return false;
    }

    public function delete(&$pks)
    {
        $pks = (array) $pks;

        if( parent::delete($pks) )
        {
            //apaga relacionamentos do(s) compromisso(s)
            return $this->clearCompromissosDirigentes( $pks );
        }

        return false;
    }

    public function getDataFromIds( $pks = array(), $fields = array() )
    {
        foreach ($pks as &$pk) {
            $pk = intval($pk);
        }
        $items = implode(', ', $pks);

        if(count($fields)==0)
            return NULL;

        $db = JFactory::getDBO();

        foreach($fields as &$field)
        {
            $field = $db->quoteName( $field );
        }

        $fields = implode(', ', $fields);
        
        $query = $db->getQuery(true);

        $query->select(
                    $fields
                )
                ->from(
                    $db->quoteName('#__agendadirigentes_compromissos', 'comp')
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                    . ' ON (' . 
                    $db->quoteName('comp.id')
                    . ' = ' . 
                    $db->quoteName('dc.compromisso_id')
                    . ' AND ' .
                    $db->quoteName('dc.owner') . ' = 1 )' 
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                    . ' ON ' . 
                    $db->quoteName('dc.dirigente_id')
                    . ' = ' . 
                    $db->quoteName('dir.id')
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_cargos', 'car')
                    . ' ON ' . 
                    $db->quoteName('dir.cargo_id')
                    . ' = ' . 
                    $db->quoteName('car.id')
                )
                ->where(
                    $db->quoteName('comp.id') . ' IN (' . $items . ')'
                );
        
        $db->setQuery((string)$query);
        return $db->loadObjectList('id');
    }

    public function publish(&$pks, $value = 1)
    {
        $session = JFactory::getSession();
        $fields = array('state', 'published_once');
        $pk_key = true;
        
        $olddata = $this->getItems($pks, $fields, $pk_key);

        $publish = parent::publish($pks, $value);
        if(! $publish )
            return false;

        $fields = '*';
        $newdata = $this->getItemsExtended($pks, $fields, $pk_key);
        
        //igualando os itens ja que a diferenca consiste somente em 'state' e 'published_once'
        foreach ($olddata as $k => $old_item)
        {
            $copy = $newdata[$k];
            $copy->state = $old_item->state;
            $copy->published_once = $old_item->published_once;
            $old_item = (array) $copy;
            $new_item = (array) $newdata[$k];
            $new_item['state'] = $value;

            $compromissoAlterado = $this->isCompromissoAlterado($new_item, $old_item);

            if($compromissoAlterado)
            {
                $this->updateAgendaAlteradaInfo( $new_item );
            }

            if(!$this->updateCompromissosDirigentes($new_item, $compromissoAlterado, $old_item))
            {
                $session->set('msg_ao_menos_um_sobreposto', 0);
                $session->set('msg_sobreposicoes_na_publicacao', 0);
                return false;
            }
        }

        $session->set('msg_ao_menos_um_sobreposto', 0);
        $session->set('msg_sobreposicoes_na_publicacao', 0);

        return true;

    }

    public function updateAgendaAlteradaInfo( $data, $owner = NULL, $dirigentes = NULL )
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $JDate = new JDate('now', $app->getCfg('offset'));

        @$owner = (!is_null($owner))? $owner : intval($data['owner']);
        @$dirigentes = (!is_null($dirigentes))? $dirigentes : $data['dirigentes'];
        @$data_inicial = $data['data_inicial'];
        @$data_final = $data['data_final'];
        if(empty($dirigentes) || is_null($data_inicial) || is_null($data_final) )
            return false;

        if($owner > 0)
            $dirigentes = array_merge($dirigentes, array($owner));

        $data_inicial = $this->mysql_convert( $data['data_inicial'] );
        $data_final = $this->mysql_convert( $data['data_final'] );
        
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);
        $daysecs = 86400;
        do {
            $date = date('Y-m-d', $time_inicial);

            for ($i=0, $limit=count($dirigentes); $i < $limit; $i++)
            {
                $dirigente = (int) $dirigentes[$i];

                if (empty( $dirigente ))
                    continue;

                if (array_key_exists($dirigente. '-' . $date, $this->dirigentes_agendas_alteradas))
                    continue;

                $query = $db->getQuery( true );
                $query->select(
                        $db->quoteName( 'qtd_alteracoes' )
                    )->from(
                        $db->quoteName( '#__agendadirigentes_agendaalterada' )
                    )->where(
                        $db->quoteName( 'id_dirigente' ) . ' = ' . $dirigente
                        . ' AND ' .
                        $db->quoteName( 'data' ) . ' = ' . $db->Quote( $date )
                    );
                $db->setQuery( (string) $query );
                $qtd_alteracoes = (int) $db->loadResult();

                $obj = new StdClass();
                $obj->id_dirigente = (int) $dirigentes[$i];
                $obj->data = $date;
                $obj->data_alteracao = $JDate->format('Y-m-d H:i:s', $app->getCfg('offset'));  
                $obj->qtd_alteracoes = $qtd_alteracoes + 1;
                $obj->user_id = JFactory::getUser()->id;

                if(empty($qtd_alteracoes))
                {
                    if(! $db->insertObject('#__agendadirigentes_agendaalterada', $obj) )
                        return false;
                }
                else
                {
                    if(! $db->updateObject('#__agendadirigentes_agendaalterada', $obj, array('id_dirigente','data')) )
                        return false;
                }
                $this->dirigentes_agendas_alteradas[ $dirigente . '-' . $date ] = $obj->qtd_alteracoes;
            }

            $time_inicial += $daysecs;
        }
        while ( $time_inicial <=  $time_final );
        
        return true;

    }

    public function isCompromissoAlterado( $newdata = array(), $olddata = array() )
    {
        if(! is_array($newdata))
            $newdata = (array) $newdata;
        
        @$id = (int) $newdata['id'];

        if(empty($id))
            return false;

        if(is_null($olddata) || count($olddata) == 0 )
        {
            $olddata = $this->getTable();
            $olddata->load( $id );
            $olddata->owner = $this->getOwner( $olddata );
            $olddata->dirigentes = (array) $this->getParticipantes( $olddata );
        }
        elseif (is_object($olddata))
        {
            $olddata = (array) $olddata;
        }
        elseif (!is_array($olddata))
        {
            return false;
        }

        if( empty($olddata['id']) )
            return false;

        if (!isset($olddata['owner']))
        {
           $olddata['owner'] = $this->getOwner( $olddata );
        }

        if (!isset($olddata['dirigentes']))
        {
           $olddata['dirigentes'] = $this->getParticipantes( $olddata );
        }

        $ignore_fields = array(
                            'id',
                            'created',
                            'created_by',
                            'params',
                            'created',
                            'created_by',
                            'modified',
                            'modified_by',
                            'checked_out',
                            'checked_out_time',
                            'published_once',
                            'featured',
                            'language',
                            'version'
                        );

        for ($i=0, $limit = count($ignore_fields); $i < $limit; $i++) { 
            if( array_key_exists($ignore_fields[$i], $newdata) )
            {
                unset( $newdata[ $ignore_fields[$i] ] );
            }
        }

        $alterado = false;

        foreach ($newdata as $key => $newvalue) {
            if($key=='state') //comparacao do campo state
            {
                $olddata[$key] = (int) $olddata[$key];
                $newvalue = (int) $newvalue;
                $olddata['published_once'] = (int) $olddata['published_once'];

                if( ($olddata[$key]==1 && $newvalue != 1) // so considera alteracao se item estava publicado e agora nao esta mais
                    || ( $olddata['published_once']==1 && $newvalue!=$olddata[$key]
                        && ($olddata[$key]==1 || $newvalue==1) ) ) //ou se alguma vez ja foi publicado e valor novo for diferente do anterior e acao foi publicar ou despublicar
                {
                    $alterado = true;
                    break;
                }
                continue;
            }
            elseif ($key == 'dirigentes') //ajuste para comparacao de dirigentes
            {
                //retirando itens em branco do campo de dirigentes
                $newresult = array();
                foreach ($newvalue as $vlr) {
                    if( ! empty($vlr) )
                        $newresult[] = $vlr;
                }
                $newvalue = $newresult;
                sort($newvalue);
                $newvalue = implode(',', $newvalue);

                //preparando participantes externos dos dados antigos (olddata)
                $participantes_externos = $olddata['participantes_externos'];
                $participantes_externos = str_replace('; ', ',', $participantes_externos);
                $participantes_externos = explode(',', $participantes_externos);

                //adicionar dados de participantes externos aos dados antigos (olddata)
                @$olddata[$key] = (array) $olddata[$key];
                $olddata[$key] = array_merge($olddata[$key], $participantes_externos);
                $oldresult = array();
                foreach ($olddata[$key] as $vlr) {
                    if( ! empty($vlr) )
                        $oldresult[] = $vlr;
                }
                $olddata[$key] = $oldresult;
                sort($olddata[$key]);
                $olddata[$key] = implode(',', $olddata[$key]);                
            }
            elseif ($key == 'data_inicial' || $key == 'data_final') //ajuste para comparacao de data inicial ou data final
            {
                $newvalue = $this->mysql_convert( $newvalue );
            }

            if( trim( $olddata[$key] ) != trim( $newvalue ) ) //comparacao dos resultados em si
            {
                $alterado = true;
                break;
            }

        }

        return $alterado;
    } 

    protected function mysql_convert( $date )
    {
        if( strpos($date, '/')!==false )
        {
            $date = explode('/', $date);
            $date = $date[2]."-".$date[1]."-".$date[0];
        }
        return $date;
    }
}