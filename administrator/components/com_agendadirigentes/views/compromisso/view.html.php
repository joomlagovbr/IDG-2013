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
 * Compromisso View
 */
class AgendaDirigentesViewCompromisso extends JViewLegacy
{
        /**
         * View form
         *
         * @var         form
         */
        protected $form = null;
        /**
         * display method of Compromisso
         * @return void
         */
        public function display($tpl = null) 
        {
                // get the Data
                $form = $this->get('Form');
                $item = $this->get('Item');
                $script = $this->get('Script');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign the Data
                $this->form = $form;
                $this->item = $item;
                $this->script = $script;
 
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
                JToolBarHelper::title($isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_NEW')
                                             : JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_EDIT'), 'compromisso');
                JToolBarHelper::apply('compromisso.apply');
                JToolBarHelper::save('compromisso.save');
                JToolBarHelper::save2new('compromisso.save2new');
                JToolBarHelper::cancel('compromisso.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
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
                $document->setTitle($isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_NEW')
                                           : JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_EDIT'));
                //regras de validacao
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/rules.compromisso.js");
                //funcao de submit geral
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/submitbutton.js");
                //strings a serem traduzidas nos arquivos js
                JText::script('COM_AGENDADIRIGENTES_FORMVALIDATOR_ERROR_UNACCEPTABLE');
        }
}