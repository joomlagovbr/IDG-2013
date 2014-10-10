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
 * Cargo View
 */
class AgendaDirigentesViewCargo extends JViewLegacy
{
        protected $canDo = null;

        /**
         * display method of Cargo
         * @return void
         */
        public function display($tpl = null) 
        {
          // get the Data
          $form = $this->get('Form');
          $item = $this->get('Item');

          if ( !empty($item->catid) ) {
            //sempre que section != 'component', essa devera ser a funcao de getActions
            $this->canDo = AgendaDirigentesHelper::getActions('com_agendadirigentes', 'category', $item->catid);
            if (!$this->canDo->get('cargos.edit')) {
              JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
              $app = JFactory::getApplication();
              $app->redirect('index.php');
            }
          }
          else
          {
            $this->canDo  = JHelperContent::getActions('com_agendadirigentes');
            
            if (!$this->canDo->get('cargos.create')) {
                return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
            }
          }

          // Check for errors.
          if (count($errors = $this->get('Errors'))) 
          {
                  JError::raiseError(500, implode('<br />', $errors));
                  return false;
          }
          // Assign the Data
          $this->form = $form;
          $this->item = $item;

          // Set the toolbar
          $this->addToolBar();

          // Display the template
          parent::display($tpl);

          // Set the document
          $this->setDocument();
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $input = JFactory::getApplication()->input;
                $input->set('hidemainmenu', true);
                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_NEW')
                                             : JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_EDIT'), 'compromisso');
                JToolBarHelper::apply('cargo.apply');
                JToolBarHelper::save('cargo.save');
                JToolBarHelper::save2new('cargo.save2new');
                JToolBarHelper::save2copy('cargo.save2copy');
                JToolBarHelper::cancel('cargo.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $isNew = ($this->item->id < 1);
                $document = JFactory::getDocument();
                $document->setTitle($isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_NEW')
                                           : JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_EDIT'));
                //regras de validacao
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/rules.cargo.js");
                //funcao de submit geral
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/submitbutton.js");
                //strings a serem traduzidas nos arquivos js
                JText::script('COM_AGENDADIRIGENTES_FORMVALIDATOR_ERROR_UNACCEPTABLE');
        }
}