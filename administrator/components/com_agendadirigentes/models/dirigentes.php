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
                    'a.id', 'id', 
                    'a.name', 'name', 
                    'a.state', 'state',
                    'c.name', 'name', 
                    'd.title', 'title', 
                    'a.proprietario', 'proprietario', 
                    'a.habilitados' , 'habilitados', 
                    'catid', 'cargo_id'
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
                // Select some fields from the hello table
                $query
                    ->select('a.*, c.name as cargo, c.name_f as cargo_f, d.title as categoria') //, e.name as proprietario
                    ->from( $db->quoteName('#__agendadirigentes_dirigentes', 'a') )
                    ->join('LEFT', $db->quoteName('#__agendadirigentes_cargos', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.cargo_id') . ')')
                    ->join('INNER', $db->quoteName('#__categories', 'd') . ' ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('c.catid') . ')')
                    ;
                
                // Filter by state
                $state = $this->state->get('filter.state');
                if (is_numeric($state))
                {
                    $query->where('a.state = ' . (int) $state);
                }
                elseif ($state === '')
                {
                    $query->where('(a.state IN (0, 1))');
                }

                // Filter by category.
                $catid = $this->getState('filter.catid');
                if (is_numeric($catid))
                {
                    $query->where('c.catid = ' . (int) $catid);
                }

                // Filter by cargo.
                $cargo_id = $this->getState('filter.cargo_id');
                if (is_numeric($cargo_id))
                {
                    $query->where('a.cargo_id = ' . (int) $cargo_id);
                }

                // Filter by search in title
                $search = $this->getState('filter.search');
                if (!empty($search))
                {
                    if (stripos($search, 'id:') === 0)
                    {
                        $query->where('a.id = ' . (int) substr($search, 3));
                    }
                    else
                    {
                        $search = $db->quote('%' . $db->escape($search, true) . '%');
                        $query->where('(a.name LIKE ' . $search . ')');
                    }
                }

                // Add the list ordering clause.
                $orderCol = $this->state->get('list.ordering', 'a.id');
                $orderDirn = $this->state->get('list.direction', 'DESC');
                $query->order($db->escape($orderCol . ' ' . $orderDirn));

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

            // List state information.
            parent::populateState('a.id', 'DESC');
        }
}