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
          $this->form = $this->get('Form');
          $this->item = $this->get('Item');

          // Check for errors.
          if (count($errors = $this->get('Errors'))) 
          {
                  JError::raiseError(500, implode('<br />', $errors));
                  return false;
          }

          $this->isNew = ($this->item->id == 0);
          $app = JFactory::getApplication();
          $this->canDo = JHelperContent::getActions('com_agendadirigentes');
          $this->canCreate = $this->canDo->get('cargos.create');

          if ($this->isNew)
          {
              if(!$this->canCreate)
              {
                JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                $app->redirect('index.php');
              }
              $this->canManage = $this->canDo->get('cargos.edit') || $this->canDo->get('cargos.edit.own');
              $this->canChange = $this->canDo->get('cargos.edit.state');
          }
          else
          {
              list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('cargos', $this->item, 'manage' );
              $this->canManage = $canManage;
              $this->canChange = $canChange;
  
              if (!$this->canManage) {
                JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                $app->redirect('index.php');
              }
          }

          $isSuperUser = AgendaDirigentesHelper::isSuperUser();
          $params = JComponentHelper::getParams( 'com_agendadirigentes' );
          $allowFeature = $params->get('allowFeature', 'state');
          $this->showFeatured = ($allowFeature == 'state' && $this->canChange) || ($allowFeature == 'edit' && $this->canManage) || ($allowFeature == 'superuser' && $isSuperUser);

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
          JToolBarHelper::title($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_NEW')
                                       : JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_EDIT'), 'compromisso');

          if ($this->isNew)
          {
            if ($this->canCreate)
            {
              JToolBarHelper::apply('cargo.apply');
              JToolBarHelper::save('cargo.save');
              JToolBarHelper::save2new('cargo.save2new');
            }
          }
          else
          {
            if ($this->canManage)
            {
                JToolBarHelper::apply('cargo.apply');
                JToolBarHelper::save('cargo.save');
                JToolBarHelper::save2new('cargo.save2new');
            }

            if ($this->canCreate)
            {
                JToolBarHelper::save2copy('cargo.save2copy');
            }  
          }

          JToolBarHelper::cancel('cargo.cancel', $this->isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_CARGO_NEW')
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