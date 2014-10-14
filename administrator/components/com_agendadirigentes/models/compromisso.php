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
                        $data = $this->getItem();
                        if(@isset($data->id))
                        {                            
                            $data->owner = $this->getOwner($data);
                            $data->dirigentes = $this->getParticipantes($data);
                        }
                        else
                        {
                            $data->owner = NULL;
                            $data->dirigentes = array();
                        }
                }
                return $data;
        }

        protected function getOwner( $data = NULL )
        {
                if (is_null($data)) {
                        $data = $this->getItem();
                }
                $query = $this->_db->getQuery(true);
                $query->select( $this->_db->quoteName('dirigente_id') );
                $query->from( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
                $query->where( $this->_db->quoteName('compromisso_id') . ' = ' . $data->id );
                $query->where( $this->_db->quoteName('owner') . ' = 1' );
                $this->_db->setQuery($query);
                return $this->_db->loadResult();
        }

        protected function getParticipantes( $data = NULL )
        {
                if (is_null($data)) {
                        $data = $this->getItem();
                }
                $query = $this->_db->getQuery(true);
                $query->select( $this->_db->quoteName('dirigente_id') );
                $query->from( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
                $query->where( $this->_db->quoteName('compromisso_id') . ' = ' . $data->id );
                $query->where( $this->_db->quoteName('owner') . ' = 0' );
                $this->_db->setQuery($query);
                
                $result = $this->_db->loadRowList();
                $array = array();
                foreach ($result as $k => $v) {
                        $array[] = $v[0];
                }
                return $array;
        }

        public function save($data)
        {
            $result = parent::save($data);
            if(!$result)
                return false;

            if($data['id']==0)
                $data['id'] = $this->_db->insertid();

            if(!$this->updateCompromissosDirigentes($data))
                return false;

            return true;
        }

        protected function updateCompromissosDirigentes($data)
        {
                if (@isset($data['id'])===false)
                    return false;

                if(!$this->clearCompromissosDirigentes($data))
                    return false;

                if(!$this->insertCompromissosDirigentes($data))
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

        protected function insertCompromissosDirigentes($data)
        {
            //variaveis iniciais de input
            $owner = @$data['owner'];
            $app = JFactory::getApplication();
            $db = $this->_db;

            //formatando owner e finalizando execucao quando valor for indevido
            if (is_array($owner)) {
                    $owner = (int) $owner[0];
            }
            $owner = (int) $owner;
            
            if($owner==0)
                return false;

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
                        $app->enqueueMessage('O cargo de '.$nomeDirigente.' n&atilde;o permite sobreposi&ccedil;&atilde;o.', 'Warning');
                    }
                }

                //formatando datas para as proximas acoes
                $data_inicial = explode('/', $data['data_inicial']);
                $data_inicial = $data_inicial[2]."-".$data_inicial[1]."-".$data_inicial[0];
                $data_final = explode('/', $data['data_final']);
                $data_final = $data_final[2]."-".$data_final[1]."-".$data_final[0];

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
                        $db->Quote( $data_inicial )
                        . ' AND ' .
                        $db->quoteName('comp.data_final') . ' <= ' .
                        $db->Quote( $data_final )
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

                            $app->enqueueMessage( $sobrepostos[$i]->dirigente_name
                                . ' j&aacute; possui compromisso(s) sobreposto(s) nesse(s) mesmo(s) dia(s) e hor&aacute;rio(s).'
                                . ' Entre em contato com o respons&aacute;vel pela agenda dessa autoridade.'
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

            //inserir os itens que puderem ser inseridos, de acordo com as regras
            for ($i=0, $limit = count($items); $i < $limit; $i++)
            {
                //nao insere compromissos de dirigentes que nao permitem sobreposicao ou ja tiveram compromissos sobrepostos para mesmo dia e horario
                if( array_key_exists($items[$i]['dirigente_id'],  $dirigentes_nao_permitem_sobrepor) )
                    continue;

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

            } //fim for() insert compromissos x dirigentes

            //sobrepoe somente se o compromisso estiver publicado (problema com troca de estados fora da edicao)
            if ( $data['state'] != 1 && count($ids_dirigentes) > 0)
            {
                $app->enqueueMessage('Sobreposi&ccedil;&otilde;es de agendas ocorrem somente no ato da publica&ccedil;&atilde;o do compromisso.', 'Warning');
                return true;
            }

            // sobrepoe de acordo com os resultados do array de itens sobrepostos
            $compromissos_sobrepostos = array();

            for ($i=0, $limit = count($sobrepostos); $i < $limit; $i++)
            { 
                $item = $sobrepostos[$i];

                if( $item->sobreposto==1 ) //item ja foi sobreposto por outro compromisso
                    continue;

                if(in_array($item->compromisso_id, $compromissos_sobrepostos)) //sobreposicao completa ja realizada...
                    continue;

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
                    $compromissos_sobrepostos[] = $item->compromisso_id;
                }
                else //se sobrepor compromisso sem owner, somente ele precisa ser sobreposto
                {
                    $conditions = array(
                            $db->quoteName('compromisso_id') . ' = ' . (int) $item->compromisso_id,
                            $db->quoteName('dirigente_id') . ' = ' . (int) $item->dirigente_id
                        );
                }
                
                //executar sobreposicao
                $query->update(
                        $db->quoteName('#__agendadirigentes_dirigentes_compromissos')
                    )->set( $fields )->where( $conditions );

                $db->setQuery((string)$query);
                $db->query();

                if($i == 0)
                {
                    $app->enqueueMessage('Ao menos um dos participantes teve um compromisso de data e hor&aacute;rio convergentes sobreposto.', 'Warning');
                }
            } // fim for() sobrepostos

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
                        // . ' AND ' .
                        // $this->_db->quoteName('dc.owner') . ' = 1'
                    );
            
            $db->setQuery((string)$query);
            return $db->loadObjectList('id');
        }


}