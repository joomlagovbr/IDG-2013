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
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of AgendaDirigentes component
 */
class AgendaDirigentesController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = false) 
        {
                // set default view if not set
                $input = JFactory::getApplication()->input;
                $input->set('view', $input->getCmd('view', 'Compromissos'));
 
                // call parent behavior
                parent::display($cachable, $urlparams);
        }
}