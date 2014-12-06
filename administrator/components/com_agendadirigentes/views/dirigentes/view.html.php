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
 
// import Joomla view library
jimport('joomldir.application.component.view');
 
/**
 * Dirigentes View
 */
class AgendaDirigentesViewDirigentes extends JViewLegacy
{
        /**
         * Dirigentes view display method
         * @return void
         */
        function display($tpl = null) 
        {
                $this->canDo  = JHelperContent::getActions('com_agendadirigentes');
                
                if (!$this->canDo->get('dirigentes.list')) {
                    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                }

                // Get data from the model
                $this->state = $this->get('State');
                $this->items = $this->get('Items');
                $this->pagination = $this->get('Pagination');
                $this->filterForm = $this->get('FilterForm');
                $this->activeFilters = $this->get('ActiveFilters');

                AgendaDirigentesHelper::addSubmenu('dirigentes');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->user = JFactory::getUser();
                $this->listOrder = $this->escape($this->state->get('list.ordering', 'dir.name'));
                $this->listDirn  = $this->escape($this->state->get('list.direction', 'ASC'));
                $this->archived  = $this->state->get('filter.state') == 2 ? true : false;
                $this->trashed   = $this->state->get('filter.state') == -2 ? true : false;
                // Set the toolbar
                $this->addToolBar($this->pagination->total);
 
                //set sidebar menu
                $this->sidebar = JHtmlSidebar::render();
 
                // Display the template
                parent::display($tpl);
 
                // Set the document
                $this->setDocument();
        }

        /**
         * Setting the toolbar
         */
        protected function addToolBar( $total = NULL ) 
        {
                JToolBarHelper::title(
                        JText::_('COM_AGENDADIRIGENTES') . ': ' .
                        JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTES').
                        ($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.' '.JText::_('COM_AGENDADIRIGENTES_ITEMS').')</span>':'')
                        );

                if ( $this->canDo->get('dirigentes.create') )
                    JToolBarHelper::addNew('dirigente.add');

                if (($this->canDo->get('dirigentes.edit')) || ($this->canDo->get('dirigentes.edit.own')))
                {
                    JToolBarHelper::editList('dirigente.edit');
                }

                if ($this->canDo->get('dirigentes.edit.state'))
                {
                    JToolBarHelper::publishList('dirigentes.publish');
                    JToolBarHelper::unpublishList('dirigentes.unpublish');
                    JToolbarHelper::archiveList('dirigentes.archive');                    
                }

                if ($this->state->get('filter.state') == -2 && $this->canDo->get('dirigentes.delete'))
                {
                    JToolbarHelper::deleteList('', 'dirigentes.delete');
                }
                elseif ($this->canDo->get('dirigentes.edit.state'))
                {
                    JToolbarHelper::trash('dirigentes.trash');
                }

                JToolBarHelper::preferences('com_agendadirigentes');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTES'));
        }
        
        /**
         * Returns an array of fields the table can be sorted by
         *
         * @return  array  Array containing the field name to sort by as the key and display text as value
         *
         * @since   3.0
         */
        protected function getSortFields()
        {
            return array(
                'dir.id' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_ID'),
                'dir.name' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_NOME'),
                'dir.state' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_STATE'),
                'car.name' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CARGO'),
                'cat.title' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CATEGORIA')
            );
        }
}