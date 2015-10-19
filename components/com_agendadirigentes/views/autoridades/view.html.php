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
            @$params_page_title = $this->params->get('page_title', '');

            if( empty($params_page_title) )
            {
                $this->page_heading = JText::_('COM_AGENDADIRIGENTES_VIEW_AUTORIDADES_DEFAULT_PAGE_HEADING');
            }
            else
            {
                $this->page_heading = $params_page_title;
            }

            $this->introtext = $this->params->get('introtext', '');
            $this->document = JFactory::getDocument();


            //correcao de valor de variavel de classe de pagina, nao informada
            if( !isset($this->pageclass_sfx) )
            {
                $this->pageclass_sfx = '';
            }
        }
}

?>
