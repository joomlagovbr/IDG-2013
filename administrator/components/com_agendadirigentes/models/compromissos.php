<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * CompromissoList Model
 */
class AgendaDirigentesModelCompromissos extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'comp.id', 'id', 
                'comp.title', 'title', 
                'dir.name', 'owner', 
                'dc.owner', 'owner', 
                'comp.state', 'state', 
                'comp.data_inicial', 'data_inicial', 
                'comp.data_final', 'data_final', 
                'comp.dia_todo', 'dia_todo', 
                'comp.horario_inicio', 'horario_inicio', 
                'comp.horario_fim', 'horario_fim', 
                'comp.local', 'local',                     
                'car.name', 'name',
                'participante_id', 'dirigente_id',
                'dates', 'duracao', 'catid'
            );
        }
        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
            // Create a new query object.           
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            // Select some fields from the compromissos table
            $query
                ->select(' comp.id, comp.checked_out, comp.title, comp.data_inicial') //comp.catid, 
                ->select(' comp.data_final, comp.horario_inicio, comp.horario_fim, comp.description, comp.local')
                ->select(' comp.params, comp.featured, comp.created_by')
                ->select(' comp.state, comp.dia_todo')
                ->select(' dc.sobreposto, dc.owner, dc.sobreposto_por')
                ->select(' dir.name AS nameOwner, dir.id AS idOwner')
                ->select(' car.name AS cargoOwner, car.catid')
                ->from( $db->quoteName('#__agendadirigentes_compromissos', 'comp') );

            $participante_id = $this->getState('filter.participante_id');
            if (is_numeric($participante_id))
            {
                $query->where('dc.dirigente_id = ' . (int) $participante_id);
                $this->setState('list.status_dono_compromisso', 0);
            }

            $status_dono_compromisso = $this->getState('list.status_dono_compromisso', 1);
            if ($status_dono_compromisso == 0) //1 compromisso por participante
            {
                $query->join( 'LEFT',
                    $db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                    .' ON (' . $db->quoteName('comp.id') . ' = ' . $db->quoteName('dc.compromisso_id') . ')' );
            }
            else //filtrar por dono
            {
                $query->join( 'LEFT',
                    $db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                    .' ON (' . $db->quoteName('comp.id') . ' = ' . $db->quoteName('dc.compromisso_id') . ' AND dc.owner = 1)' );
            }

            $query
                ->join( 'LEFT',
                    $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                    .' ON (' . $db->quoteName('dc.dirigente_id') . ' = ' . $db->quoteName('dir.id') . ')' )
                ->join( 'LEFT',
                    $db->quoteName('#__agendadirigentes_cargos', 'car')
                    .' ON (' . $db->quoteName('dir.cargo_id') . ' = ' . $db->quoteName('car.id') . ')' );

            // Filter by state
            $state = $this->state->get('filter.state');
            if (is_numeric($state))
            {
                $query->where('comp.state = ' . (int) $state);
            }
            elseif ($state === '')
            {
                $query->where('(comp.state IN (0, 1))');
            }

            // Filter by category.
            $catid = $this->getState('filter.catid');
            if (is_numeric($catid))
            {
                $query->where('car.catid = ' . (int) $catid);
            }

            // Filter by cargo.
            $cargo_id = $this->getState('filter.cargo_id');
            if (is_numeric($cargo_id))
            {
                $query->where('dir.cargo_id = ' . (int) $cargo_id);
            }

            // Filter by dia todo.
            $dia_todo = $this->getState('filter.dia_todo');
            if (is_numeric($dia_todo))
            {
                $query->where('comp.dia_todo = ' . (int) $dia_todo);
            }

            //filter by 1 dia ou mais de compromisso
            $duracao = $this->getState('filter.duracao');               
            if (intval($duracao) == 1) {
                $query->where($db->quoteName('data_inicial') . ' = ' . $db->quoteName('data_final'));
            }
            elseif (intval($duracao) == 2) {
                $query->where($db->quoteName('data_inicial') . ' <> ' . $db->quoteName('data_final'));
            }
            
            // Filter by dirigente.
            $dirigente_id = $this->getState('filter.dirigente_id');
            if (is_numeric($dirigente_id))
            {
                $query->where('dir.id = ' . (int) $dirigente_id);
            }

            $featured = $this->getState('filter.featured', '');
            if( $featured != '' )
            {
                $query->where('comp.featured = ' . (int) $featured);
            }

            // Filter by data inicial | data final.
            $data_inicial = $this->getState('dates.data_inicial');
            $data_final = $this->getState('dates.data_final');
            if (!empty($data_inicial) || !empty($data_final))
            {
                require_once( JPATH_COMPONENT .'/models/rules/databrasil.php' );
                $dateVerify = new JFormRuleDataBrasil();

                if (preg_match($dateVerify->getRegex(), $data_inicial)) {
                    $data_inicial = $this->format_date_mysql($data_inicial);
                    $clause = $db->quoteName('data_inicial') . ' >= '.$db->Quote($data_inicial);
                    $query->where($clause);
                }

                if (preg_match($dateVerify->getRegex(), $data_final)) {
                    $data_final = $this->format_date_mysql($data_final);
                    $clause = '('.$db->quoteName('data_final') . ' <= '.$db->Quote($data_final);
                    $clause .= ' OR ';
                    $clause .= $db->quoteName('data_final') . ' = '.$db->Quote('0000-00-00').')';
                    $query->where($clause);
                }
            }

            // Filter by search in title
            $search = $this->getState('filter.search');
            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('comp.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->quote('%' . $db->escape($search, true) . '%');
                    $query->where('(comp.title LIKE ' . $search . ')');
                }
            }

            //restringir de acordo com as permissoes de usuario
            if($this->state->get('params')->get('restricted_list_compromissos', 0) == 1 && ! AgendaDirigentesHelper::isSuperUser() )
            {                    
                $formatedToGetPermissions = true;
                $categories = $this->getCategories( $formatedToGetPermissions );

                $allowed_categories = array();
                for ($i=0, $limit = count($categories); $i < $limit; $i++)
                { 
                    list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('compromissos', $categories[$i] );
                    if($canManage || $canChange)
                        $allowed_categories[] = $categories[$i]->catid;
                }

                if(count($allowed_categories))
                {
                    $allowed_categories = implode(', ', $allowed_categories);
                    $query->where(
                        $db->quoteName('car.catid') . ' IN (' . $allowed_categories . ')'
                    );
                } 
                else
                {
                    $allowed_categories = 0;
                    $query->where(
                        $db->quoteName('car.catid') . ' IN (' . $allowed_categories . ')'
                    );
                }                   
            }

            // Add the list ordering clause.
            $orderCol = $this->state->get('list.ordering', 'comp.id');
            $orderDirn = $this->state->get('list.direction', 'DESC');

            if($orderCol == 'comp.data_inicial' || $orderCol == 'comp.data_final')
                $ordering = $db->escape($orderCol . ' ' . $orderDirn . ', comp.horario_inicio ASC');
            else
                $ordering = $db->escape($orderCol . ' ' . $orderDirn);

            $query->order( $ordering );

            return $query;
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);

        $catid = $this->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid', '');
        $this->setState('filter.catid', $catid);

        $cargo_id = $this->getUserStateFromRequest($this->context . '.filter.cargo_id', 'filter_cargo_id', '');
        $this->setState('filter.cargo_id', $cargo_id);

        $dirigente_id = $this->getUserStateFromRequest($this->context . '.filter.dirigente_id', 'filter_dirigente_id', '');
        $this->setState('filter.dirigente_id', $dirigente_id);

        $participante_id = $this->getUserStateFromRequest($this->context . '.filter.participante_id', 'filter_participante_id', '');
        $this->setState('filter.participante_id', $participante_id);

        $dia_todo = $this->getUserStateFromRequest($this->context . '.filter.dia_todo', 'filter_dia_todo', '');
        $this->setState('filter.dia_todo', $dia_todo);

        $duracao = $this->getUserStateFromRequest($this->context . '.filter.duracao', 'filter_duracao', '');
        $this->setState('filter.duracao', $duracao);

        $featured = $this->getUserStateFromRequest($this->context . '.filter.featured', 'filter_featured', '');
        $this->setState('filter.featured', $featured);

        //alterando valor de lista se participante_id estiver preenchido
        $app = JFactory::getApplication();
        $input = $app->input;
        $filter = $input->get('filter', '', 'ARRAY');

        @$participante_id = intval($filter['participante_id']);
        if($participante_id>0)
        {
            $lists = $input->get('list', '', 'ARRAY');
            if(@isset($lists['status_dono_compromisso']))
            {
             $lists['status_dono_compromisso'] = 0;
             $input->set('list', $lists);   
            }
        }

        $params = JComponentHelper::getParams( $this->option );
        $this->setState('params', $params);

        $dates = $this->getUserStateFromRequest($this->context . '.dates', 'dates', array(), '');            
        foreach ($dates as $k => $date) {
            $this->setState('dates.'.$k, $date);   
        }            

        // List state information.
        parent::populateState('comp.id', 'DESC');
    }

    protected function format_date_mysql( $date )
    {
        $date = explode('/', $date);
        if(count($date)==3)
            $date = $date[2].'-'.$date[1].'-'.$date[0];
        else
            $date = '';
        return $date;
    }

    public function getCategories( $formatedToGetPermissions = false )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(
            ($formatedToGetPermissions)? $db->quoteName('id', 'catid') : $db->quoteName('id')
        )
        ->from(
            $db->quoteName('#__categories')
        )
        ->where(
            $db->quoteName('extension')
            . ' = ' .
            $db->Quote( $this->option )
        );
        $db->setQuery((string)$query);
        $objList = $db->loadObjectList( ($formatedToGetPermissions)? NULL : 'id' );

        if($formatedToGetPermissions)
            return $objList;
        
        $array = array_keys($objList);
        return $array;
    }
}