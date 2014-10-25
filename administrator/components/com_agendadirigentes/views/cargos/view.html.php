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
jimport('joomla.application.component.view');
 
/**
 * Cargos View
 */
class AgendaDirigentesViewCargos extends JViewLegacy
{
        protected $canDo;

        /**
         * Cargos view display method
         * @return void
         */
        function display($tpl = null) 
        {
            $this->canDo  = JHelperContent::getActions('com_agendadirigentes');
            
            if (!$this->canDo->get('cargos.list')) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
                
            // Get data from the model
            $this->state = $this->get('State');
            $this->items = $this->get('Items');
            $this->pagination = $this->get('Pagination');
            $this->filterForm = $this->get('FilterForm');
            $this->activeFilters = $this->get('ActiveFilters');

            AgendaDirigentesHelper::addSubmenu('cargos');

            // Check for errors.
            if (count($errors = $this->get('Errors'))) 
            {
                    JError::raiseError(500, implode('<br />', $errors));
                    return false;
            }
            // Assign data to the view
            $this->user = JFactory::getUser();
            $this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
            $this->listDirn  = $this->escape($this->state->get('list.direction', 'DESC'));

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
                    JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGOS').
                    //Reflect number of items in title!
                    ($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.' '.JText::_('COM_AGENDADIRIGENTES_ITEMS').')</span>':'')
                    );

            if ( $this->canDo->get('cargos.create') )
                JToolBarHelper::addNew('cargo.add');

            if ( $this->canDo->get('cargos.edit') )
                JToolBarHelper::editList('cargo.edit');

            if ($this->canDo->get('cargos.edit.state'))
            {
                JToolBarHelper::publishList('cargos.publish');
                JToolBarHelper::unpublishList('cargos.unpublish');
            }

            if ($this->canDo->get('cargos.delete'))
            {
                JToolBarHelper::deleteList('', 'cargos.delete');
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
                $document->setTitle(JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGOS'));
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
                'a.id' => JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_ID'),
                'a.published' => JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_PUBLISHED'),
                'a.name' => JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_CARGO'),
                'a.ordering' => JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_ORDERING'),
                'b.title' => JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_CATEGORIA')
            );
        }
}