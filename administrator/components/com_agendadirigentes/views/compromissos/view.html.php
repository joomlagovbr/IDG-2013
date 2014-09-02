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
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
                AgendaDirigentesHelper::addSubmenu('compromissos');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
 
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
}