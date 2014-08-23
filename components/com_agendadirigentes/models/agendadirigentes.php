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
         * @var array messages
         */
        protected $messages;
 
        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         * @since       2.5
         */
        public function getTable($type = 'AgendaDirigentes', $prefix = 'AgendaDirigentesTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
 
        /**
         * Get the message
         * @param  int    The corresponding id of the message to be retrieved
         * @return string The message to be displayed to the user
         */
        public function getMsg($id = 1) 
        {
                if (!is_array($this->messages))
                {
                        $this->messages = array();
                }
 
                if (!isset($this->messages[$id])) 
                {
                        //request the selected id
                        $jinput = JFactory::getApplication()->input;
                        $id = $jinput->get('id', 1, 'INT' );
 
                        // Get a TableHelloWorld instance
                        $table = $this->getTable();
 
                        // Load the message
                        $table->load($id);
 
                        // Assign the message
                        $this->messages[$id] = $table->greeting;
                }
 
                return $this->messages[$id];
        }
 
        // public function getMsg() 
        // {
        //         if (!isset($this->msg)) 
        //         {
        //                 $jinput = JFactory::getApplication()->input;
        //                 $id     = $jinput->get('show_compromissos', 0, 'INT');

        //                 switch ($id) 
        //                 {
        //                         case 1:
        //                                 $this->msg = 'Good bye World!';
        //                         break;
        //                         default:
        //                         case 0:
        //                                 $this->msg = 'Hello World!';
        //                         break;
        //                 }
        //         }
        //         return $this->msg;
        // }
}