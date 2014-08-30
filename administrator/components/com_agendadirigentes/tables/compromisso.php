<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Compromisso Table class
 */
class AgendaDirigentesTableCompromisso extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__agendadirigentes_compromissos', 'id', $db);
        }
        /**
         * Overloaded bind function
         *
         * @param       array           named array
         * @return      null|string     null is operation was satisfactory, otherwise returns an error
         * @see JTable:bind
         * @since 1.5
         */
        public function bind($array, $ignore = '') 
        {
                if (isset($array['params']) && is_array($array['params'])) 
                {
                        // Convert the params field to a string.
                        $parameter = new JRegistry;
                        $parameter->loadArray($array['params']);
                        $array['params'] = (string)$parameter;
                }
                return parent::bind($array, $ignore);
        }

        /**
         * Overloaded load function
         *
         * @param       int $pk primary key
         * @param       boolean $reset reset data
         * @return      boolean
         * @see JTable:load
         */
        public function load($pk = null, $reset = true) 
        {
            if (parent::load($pk, $reset)) 
            {
                // Convert the params field to a registry.
                $params = new JRegistry;                 
                $params->loadString($this->params, 'JSON');

                $this->params = $params;
                return true;
            }
            else
            {
                return false;
            }
        }

        function check()
        {
        	$this->data_inicial = explode("/", $this->data_inicial);
        	$this->data_inicial = $this->data_inicial[2]."-".$this->data_inicial[1]."-".$this->data_inicial[0];
        	$this->data_final = explode("/", $this->data_final);
        	$this->data_final = $this->data_final[2]."-".$this->data_final[1]."-".$this->data_final[0];
        	
        	if(empty($this->created) || $this->created=="0000-00-00 00:00:00")
        		$this->created = date('Y-m-d H:i:s');
        	
        	if(empty($this->created_by) || $this->created_by==0)
        		$this->created_by = JFactory::getUser()->get("id");
        	
       		$this->modified = date('Y-m-d H:i:s');    	
       		$this->modified_by = JFactory::getUser()->get("id");
        	$this->version += 1;

        	return parent::check();
        }

}