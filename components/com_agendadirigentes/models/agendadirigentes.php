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
 
// importar library modelitem do Joomla
jimport('joomla.application.component.modelitem');
 
/**
 * AgendaDirigentes Model
 */
class AgendaDirigentesModelAgendaDirigentes extends JModelItem
{
        /**
         * @var string msg
         */
        protected $msg;
 
        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
        public function getMsg() 
        {
                if (!isset($this->msg)) 
                {
                        $jinput = JFactory::getApplication()->input;
                        $id     = $jinput->get('show_compromissos', 0, 'INT');

                        switch ($id) 
                        {
                                case 1:
                                        $this->msg = 'Good bye World!';
                                break;
                                default:
                                case 0:
                                        $this->msg = 'Hello World!';
                                break;
                        }
                }
                return $this->msg;
        }
}