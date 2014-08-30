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
         * @var array item
         */
        protected $item;
         
         /**
         * Method to auto-populate the model state.
         *
         * This method should only be called once per instantiation and is designed
         * to be called on the first call to the getState() method unless the model
         * configuration flag to ignore the request is set.
         *
         * Note. Calling getState in this method will result in recursion.
         *
         * @return      void
         * @since       2.5
         */
        protected function populateState() 
        {
                $app = JFactory::getApplication();
                // Get the message id
                $input = JFactory::getApplication()->input;
                $id = $input->getInt('id');
                $this->setState('compromisso.id', $id);
 
                // Load the parameters.
                $params = $app->getParams();
                $this->setState('params', $params);
                parent::populateState();
        }

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
        public function getItem() 
        {
                // if (!is_array($this->messages))
                // {
                //         $this->messages = array();
                // }
 
                // if (!isset($this->messages[$id])) 
                // {
                //         //request the selected id
                //         $jinput = JFactory::getApplication()->input;
                //         $id = $jinput->get('id', 1, 'INT' );
 
                //         // Get a TableHelloWorld instance
                //         $table = $this->getTable();
 
                //         // Load the message
                //         $table->load($id);
 
                //         // Assign the message
                //         $this->messages[$id] = $table->greeting;
                // }
 
                // return $this->messages[$id];

                //
                if (!isset($this->item)) 
                {
                        $db = JFactory::getDBO();
                        $id = $this->getState('compromisso.id');
                        
                        $db->setQuery(

                                $db->getQuery(true)
                                ->from( $db->quoteName('#__agendadirigentes_compromissos', 'comp') )
                                ->join( 'LEFT', $db->quoteName('#__categories', 'cat')
                                        .' ON (' . $db->quoteName('comp.catid') . ' = ' . $db->quoteName('cat.id') . ')' )
                                ->select('comp.title, comp.params, c.title as category, comp.local')
                                ->where('comp.id=' . (int)$id)
                        
                        );

                        if (!$this->item = $db->loadObject()) 
                        {
                                $this->setError($this->_db->getError());
                        }
                        else
                        {
                                // Load the JSON string
                                $params = new JRegistry;                                
                                $params->loadString($this->item->params, 'JSON');
                                $this->item->params = $params;
 
                                // Merge global params with item params
                                $params = clone $this->getState('params');
                                $params->merge($this->item->params);
                                $this->item->params = $params;
                        }
                }
                return $this->item;
                //
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