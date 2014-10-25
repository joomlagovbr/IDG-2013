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
            $this->form = $this->get('Form');
            $this->item = $this->get('Item');
            $this->script = $this->get('Script');
            $this->isNew = ($this->item->id == 0);
            $app = JFactory::getApplication();

            // Check for errors.
            if (count($errors = $this->get('Errors'))) 
            {
                    JError::raiseError(500, implode('<br />', $errors));
                    return false;
            }

            $this->canDo = JHelperContent::getActions('com_agendadirigentes');
            $this->canCreate = $this->canDo->get('core.create');

            if ($this->isNew)
            {
                if(!$this->canCreate)
                {
                    JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
                    $app->redirect('index.php');
                }

                $this->canManage = $this->canDo->get('core.edit') || $this->canDo->get('core.edit.own');
                $this->canChange = $this->canDo->get('core.edit.state');
            }
            else
            {
                list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('compromissos', $this->item, 'manage' );
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
            $this->permitir_participantes_locais = $params->get('permitir_participantes_locais', 1);
            $this->permitir_participantes_externos = $params->get('permitir_participantes_externos', 1);

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
            JToolBarHelper::title($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_NEW')
                                         : JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_EDIT'), 'compromisso');

            if ($this->isNew)
            {
                if ($this->canCreate)
                {
                    JToolBarHelper::apply('compromisso.apply');
                    JToolBarHelper::save('compromisso.save');
                    JToolBarHelper::save2new('compromisso.save2new');
                }                   
            }
            else
            {
                if ($this->canManage)
                {
                    JToolBarHelper::apply('compromisso.apply');
                    JToolBarHelper::save('compromisso.save');
                    JToolBarHelper::save2new('compromisso.save2new');
                }

                if ($this->canCreate)
                {
                    JToolBarHelper::save2copy('compromisso.save2copy');
                }                     
            }

            JToolBarHelper::cancel('compromisso.cancel', $this->isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
            $document = JFactory::getDocument();
            $document->setTitle($this->isNew ? JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_NEW')
                                       : JText::_('COM_AGENDADIRIGENTES_MANAGER_COMPROMISSO_EDIT'));
            //regras de validacao
            $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                              . "/assets/js/rules.compromisso.js");
            //funcao de submit geral
            $document->addScript(JURI::root() . "/administrator/components/com_agendadirigentes"
                                              . "/assets/js/submitbutton.js");
            //strings a serem traduzidas nos arquivos js
            JText::script('COM_AGENDADIRIGENTES_FORMVALIDATOR_ERROR_UNACCEPTABLE');
            JText::script('COM_AGENDADIRIGENTES_FORMVALIDATOR_DATAFINAL_MENORQUE_DATAINICIAL');
        }
}