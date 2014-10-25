<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
class AgendaDirigentesTableDirigente extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) 
    {
            parent::__construct('#__agendadirigentes_dirigentes', 'id', $db);
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
		$table = JTable::getInstance('Dirigente', 'AgendaDirigentesTable');

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
            if(!isset($this->catid))
            {
                $query = $this->_db->getQuery(true);
           		$query->select(
                    $this->_db->quoteName('cat.id')
                )->from(
                    $this->_db->quoteName('#__categories', 'cat')
                )->join(
                    'INNER',
                    $this->_db->quoteName('#__agendadirigentes_cargos', 'car')
                    . ' ON ' . $this->_db->quoteName('car.catid') . ' = ' . $this->_db->quoteName('cat.id')
                )->where(
                    $this->_db->quoteName('car.id') . ' = ' . (int) $this->cargo_id
                );
                $this->_db->setQuery((string)$query);
                $this->catid = $this->_db->loadResult();
            }

            if( empty($this->sexo) )
                $this->sexo = 'M';

            return true;
        }
        else
        {
            return false;
        }
    }

    function check()
    {
	    //item retirado antes de salvamento. utilizado somente para fins de controle de permissoes ao carregar um item.
	    unset($this->catid);

	    //check padrao
		return parent::check();
    }
}