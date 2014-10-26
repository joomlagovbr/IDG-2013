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
 * DirigenteList Model
 */
class AgendaDirigentesModelDirigentes extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'dir.id', 'id', 
                'dir.name', 'name', 
                'dir.state', 'state',
                'car.name', 'name', 
                'car.catid', 'catid',
                'car.cargo_id', 'cargo_id',
                'cat.title', 'title'              
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

            $query
                ->select(
                    $db->quoteName('dir.id') . ', ' .
                    $db->quoteName('dir.name') . ', ' .
                    $db->quoteName('dir.cargo_id') . ', ' .
                    $db->quoteName('dir.state') . ', ' .
                    $db->quoteName('dir.interino') . ', ' .
                    $db->quoteName('dir.em_atividade') . ', ' .
                    $db->quoteName('dir.sexo') . ', ' .
                    $db->quoteName('car.catid') . ', ' .
                    $db->quoteName('car.name', 'cargo') . ', ' .
                    $db->quoteName('car.name_f', 'cargo_f') . ', ' .
                    $db->quoteName('cat.title', 'categoria') . ', ' .
                    $db->quoteName('cat.title', 'categoria')                        
                )
                ->from(
                    $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                )
                ->join(
                    'LEFT',
                    $db->quoteName('#__agendadirigentes_cargos', 'car')
                    . ' ON (' . $db->quoteName('car.id') . ' = ' . $db->quoteName('dir.cargo_id') . ')'
                )
                ->join(
                    'INNER',
                    $db->quoteName('#__categories', 'cat')
                    . ' ON (' . $db->quoteName('cat.id') . ' = ' . $db->quoteName('car.catid') . ')'
                );
            
            // Filter by state
            $state = $this->state->get('filter.state');
            if (is_numeric($state))
            {
                $query->where('dir.state = ' . (int) $state);
            }
            elseif ($state === '')
            {
                $query->where('(dir.state IN (0, 1))');
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

            // Filter by search in title
            $search = $this->getState('filter.search');
            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('dir.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->quote('%' . $db->escape($search, true) . '%');
                    $query->where('(dir.name LIKE ' . $search . ')');
                }
            }

            //restringir de acordo com as permissoes de usuario
            if($this->state->get('params')->get('restricted_list_dirigentes', 0) == 1 && ! AgendaDirigentesHelper::isSuperUser() )
            {
                $formatedToGetPermissions = true;
                $categories = $this->getCategories( $formatedToGetPermissions );

                $allowed_categories = array();
                for ($i=0, $limit = count($categories); $i < $limit; $i++)
                { 
                    list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('dirigentes', $categories[$i] );
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
            $orderCol = $this->state->get('list.ordering', 'dir.name');
            $orderDirn = $this->state->get('list.direction', 'ASC');
            $query->order($db->escape($orderCol . ' ' . $orderDirn));

            // echo $query->dump();die();
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
        @$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        @$published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);

        @$catid = $this->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid', '');
        $this->setState('filter.catid', $catid);

        @$cargo_id = $this->getUserStateFromRequest($this->context . '.filter.cargo_id', 'filter_cargo_id', '');
        $this->setState('filter.cargo_id', $cargo_id);

        $params = JComponentHelper::getParams( $this->option );
        $this->setState('params', $params);

        // List state information.
        parent::populateState('dir.name', 'ASC');
    }

    protected function getCategories( $formatedToGetPermissions = false )
    {
        $compromissosModel = $this->getInstance( 'compromissos', 'AgendaDirigentesModel' );
        return $compromissosModel->getCategories( $formatedToGetPermissions );
    }
}