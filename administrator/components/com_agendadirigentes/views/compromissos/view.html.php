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
 * Compromissos View
 */
class AgendaDirigentesViewCompromissos extends JViewLegacy
{
        protected $canDo;

        /**
         * Compromissos view display method
         * @return void
         */
        function display($tpl = null) 
        {
                $this->canDo  = JHelperContent::getActions('com_agendadirigentes');
                
                if (!$this->canDo->get('cargos.list')) {
                    
                }

                // Get data from the model
                $this->state = $this->get('State');
                $this->items = $this->get('Items');
                $this->pagination = $this->get('Pagination');
                $this->filterForm = $this->get('FilterForm');
                $this->activeFilters = $this->get('ActiveFilters');

                AgendaDirigentesHelper::addSubmenu('compromissos');

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
                $this->status_dono_compromisso = $this->state->get('list.status_dono_compromisso');

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
                JToolBarHelper::title(JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSOS').
                        //Reflect number of items in title!
                        ($total?' <span style="font-size: 0.5em; vertical-align: middle;">('.$total.')</span>':'')
                        , 'compromisso');


                if ($this->canDo->get('core.create')) 
                    JToolBarHelper::addNew('compromisso.add');

                if ($this->canDo->get('core.edit')) 
                    JToolBarHelper::editList('compromisso.edit');

                    // JToolbarHelper::custom('dirigentes.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
                

                if ($this->canDo->get('core.delete')) 
                    JToolBarHelper::deleteList('', 'compromissos.delete');

                // Options button.
                if ($this->canDo->get('core.admin')) 
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
                $document->setTitle(JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSOS'));
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
                'comp.id' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ID'),
                'comp.title' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_TITLE'),
                'dir.name' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER'),
                'dir.name' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIRIGENTE'),
                'comp.state' => JText::_('JSTATUS'),
                'comp.data_inicial' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_INICIAL'),
                'comp.data_final' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_FINAL'),
                'comp.dia_todo' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIA_TODO'),
                'comp.horario_inicio' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_INICIO'),
                'comp.horario_fim' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_FIM'),
                'comp.local' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_LOCAL'),
                'comp.ordering' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ORDERING'),
                'car.name' => JText::_('COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER_CARGO')
            );
        }
}