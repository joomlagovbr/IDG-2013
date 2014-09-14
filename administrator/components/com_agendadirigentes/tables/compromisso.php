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


        /**
         * Method to set the publishing state for a row or list of rows in the database
         * table.  The method respects checked out rows by other users and will attempt
         * to checkin rows that it can after adjustments are made.
         *
         * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
         * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published, 2=archived, -2=trashed]
         * @param   integer  $userId  The user id of the user performing the operation.
         *
         * @return  boolean  True on success.
         *
         * @since   1.6
         */
        public function publish($pks = null, $state = 1, $userId = 0)
        {
            $k = $this->_tbl_key;

            // Sanitize input.
            JArrayHelper::toInteger($pks);
            $userId = (int) $userId;
            $state = (int) $state;

            // If there are no primary keys set check to see if the instance key is set.
            if (empty($pks))
            {
                if ($this->$k)
                {
                    $pks = array($this->$k);
                }
                // Nothing to set publishing state on, return false.
                else
                {
                    $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                    return false;
                }
            }

            // Get an instance of the table
            $table = JTable::getInstance('Compromisso', 'AgendaDirigentesTable');

            // For all keys
            foreach ($pks as $pk)
            {
                // Load the banner
                if (!$table->load($pk))
                {
                    $this->setError($table->getError());
                }

                // Change the state
                $table->state = $state;

                // Check the row
                $table->check();

                // Store the row
                if (!$table->store())
                {
                    $this->setError($table->getError());
                }

            }

            return count($this->getErrors()) == 0;
        }
}