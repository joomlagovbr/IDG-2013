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
 * Dirigente View
 */
class AgendaDirigentesViewDirigente extends JViewLegacy
{
        /**
         * display method of Dirigente
         * @return void
         */
        public function display($tpl = null) 
        {
          // get the Data
          $this->form = $this->get('Form');
          $this->item = $this->get('Item');
          
          if( empty($this->item) )
          {
            $this->item = new StdClass();
            $this->item->id = 0;
          }

          $this->isNew = ($this->item->id == 0);
          $app = JFactory::getApplication();
          
          // Check for errors.
          if (count($errors = $this->get('Errors'))) 
          {
                  JError::raiseError(500, implode('<br />', $errors));
                  return false;
          }

          $this->canDo = JHelperContent::getActions('com_agendadirigentes');
          $this->canCreate = $this->canDo->get('dirigentes.create');

          if ($this->isNew)
          {
              if(!$this->canCreate)
              {
                JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                $app->redirect('index.php');
              }
              $this->canChange = $this->canDo->get('dirigentes.edit.state');
          }
          else
          {
              list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('dirigentes', $this->item, 'manage' );
              $this->canManage = $canManage;
              $this->canChange = $canChange;
  
              if (!$this->canManage) {
                JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                $app->redirect('index.php');
              }
          }

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
          JToolBarHelper::title($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTE_NEW')
                                       : JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTE_EDIT'), 'compromisso');

          if ($this->isNew)
          {
            if ($this->canCreate)
            {
              JToolBarHelper::apply('dirigente.apply');
              JToolBarHelper::save('dirigente.save');
              JToolBarHelper::save2new('dirigente.save2new');
            }
          }
          else
          {
            if ($this->canManage)
            {
                JToolBarHelper::apply('dirigente.apply');
                JToolBarHelper::save('dirigente.save');
                JToolBarHelper::save2new('dirigente.save2new');
            }

            if ($this->canCreate)
            {
                JToolBarHelper::save2copy('dirigente.save2copy');
            }  
          }

          JToolBarHelper::cancel('dirigente.cancel', $this->isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTE_NEW')
                                           : JText::_('COM_AGENDADIRIGENTES_MANAGER_DIRIGENTE_EDIT'));
                //regras de validacao
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/rules.dirigente.js");
                //funcao de submit geral
                $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                                  . "/assets/js/submitbutton.js");
                //strings a serem traduzidas nos arquivos js
                JText::script('COM_AGENDADIRIGENTES_FORMVALIDATOR_ERROR_UNACCEPTABLE');
        }
}
