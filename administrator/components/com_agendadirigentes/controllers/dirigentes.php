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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Dirigentes Controller
 */
class AgendaDirigentesControllerDirigentes extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         * @since       2.5
         */
        public function getModel($name = 'Dirigente', $prefix = 'AgendaDirigentesModel', $config = array()) 
        {
                $model = parent::getModel($name, $prefix, array('ignore_request' => true));
                return $model;
        }
}