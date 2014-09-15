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
                $this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
                $this->listDirn  = $this->escape($this->state->get('list.direction', 'DESC'));
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
                JToolBarHelper::title(JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTES').
                        //Reflect number of items in title!
                        ($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.')</span>':'')
                        , 'compromisso');

                if ($this->canDo->get('core.create'))
                    JToolBarHelper::addNew('dirigente.add');

                if (($this->canDo->get('core.edit')) || ($this->canDo->get('core.edit.own')))
                {
                    JToolBarHelper::editList('dirigente.edit');
                }                

                if ($this->canDo->get('core.edit.state'))
                {
                    JToolBarHelper::publishList('dirigentes.publish');
                    JToolBarHelper::unpublishList('dirigentes.unpublish');
                    JToolbarHelper::archiveList('dirigentes.archive');                    
                }

                if ($this->state->get('filter.state') == -2 && $this->canDo->get('core.delete'))
                {
                    JToolbarHelper::deleteList('', 'dirigentes.delete');
                }
                elseif ($this->canDo->get('core.edit.state'))
                {
                    JToolbarHelper::trash('dirigentes.trash');
                }

                // JToolBarHelper::deleteList('', 'dirigentes.delete');
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
                'a.id' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_ID'),
                'a.name' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_NOME'),
                'a.state' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_STATE'),
                'c.name' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CARGO'),
                'd.title' => JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CATEGORIA')
            );
        }
}