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