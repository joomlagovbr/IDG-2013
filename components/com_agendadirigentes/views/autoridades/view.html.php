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

// importar library de views do Joomla
jimport('joomla.application.component.view');
 
/**
* HTML View class para AgendaDirigentes Component
*
* @since 0.0.1
*/
class AgendaDirigentesViewAutoridades extends JViewLegacy
{
        public function display($tpl = null) 
        {

                // Assign data to the view
                $this->state = $this->get('State');
                $this->items = $this->get('Items');
                $this->params = $this->state->get('params');                

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
                        return false;
                }

                $this->_prepareDocument();

                // Display the view
                parent::display($tpl);
        }

        /**
         * Prepares the document
         */
        protected function _prepareDocument()
        {
                // $app            = JFactory::getApplication();
                // $menus          = $app->getMenu();
                // $pathway        = $app->getPathway();
                // $title          = null;

                // // Because the application sets a default page title,
                // // we need to get it from the menu item itself
                // $menu = $menus->getActive();

                if(@empty($this->params->get('page_title', '')))
                {
                        $this->page_heading = 'Agenda de Autoridades';
                }
                else
                {
                        $this->page_heading = $this->params->get('page_title', '');
                }

                $this->introtext = $this->params->get('introtext', '');
                $this->document = JFactory::getDocument();
                // if ($menu)
                // {
                //         $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
                // }
                // else
                // {
                //         $this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
                // }
        }
}

?>