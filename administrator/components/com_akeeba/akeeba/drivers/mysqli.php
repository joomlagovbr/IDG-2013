<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * MySQL Improved (mysqli) database driver for Akeeba Engine
 *
 * Based on Joomla! Platform 11.2
 */
class AEDriverMysqli extends AEDriverMysql
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $name = 'mysqli';

	/**
	 * Database object constructor
	 *
	 * @param    array    List of options used to configure the connection
	 */
	public function __construct($options)
	{
		$this->driverType = 'mysql';

		// Init
		$this->nameQuote = '`';

		$host = array_key_exists('host', $options) ? $options['host'] : 'localhost';
		$port = array_key_exists('port', $options) ? $options['port'] : '';
		$user = array_key_exists('user', $options) ? $options['user'] : '';
		$password = array_key_exists('password', $options) ? $options['password'] : '';
		$database = array_key_exists('database', $options) ? $options['database'] : '';
		$prefix = array_key_exists('prefix', $options) ? $options['prefix'] : '';
		$select = array_key_exists('select', $options) ? $options['select'] : true;

		// Figure out if a port is included in the host name
		if (empty($port))
		{
			// Unlike mysql_connect(), mysqli_connect() takes the port and socket
			// as separate arguments. Therefore, we have to extract them from the
			// host string.
			$port = null;
			$socket = null;
			$targetSlot = substr(strstr($host, ":"), 1);
			if (!empty($targetSlot))
			{
				// Get the port number or socket name
				if (is_numeric($targetSlot))
				{
					$port = $targetSlot;
				}
				else
				{
					$socket = $targetSlot;
				}

				// Extract the host name only
				$host = substr($host, 0, strlen($host) - (strlen($targetSlot) + 1));
				// This will take care of the following notation: ":3306"
				if ($host == '')
				{
					$host = 'localhost';
				}
			}
		}

		// finalize initialization
		parent::__construct($options);

		// Open the connection
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->port = $port;
		$this->socket = $socket;
		$this->_database = $database;
		$this->selectDatabase = $select;

		if (!is_object($this->connection))
		{
			$this->open();
		}
	}

	public function open()
	{
		if ($this->connected())
		{
			return;
		}
		else
		{
			$this->close();
		}

		// perform a number of fatality checks, then return gracefully
		if (!function_exists('mysqli_connect'))
		{
			$this->errorNum = 1;
			$this->errorMsg = 'The MySQL adapter "mysqli" is not available.';

			return;
		}

		// connect to the server
		if (!($this->connection = @mysqli_connect($this->host, $this->user, $this->password, null, $this->port, $this->socket)))
		{
			$this->errorNum = 2;
			$this->errorMsg = 'Could not connect to MySQL';

			return;
		}

		// Set sql_mode to non_strict mode
		mysqli_query($this->connection, "SET @@SESSION.sql_mode = '';");

		if ($this->selectDatabase && !empty($this->_database))
		{
			$this->select($this->_database);
		}

		$this->setUTF();
	}

	public function close()
	{
		$return = false;
		if (is_resource($this->cursor))
		{
			mysqli_free_result($this->cursor);
		}
		if (is_object($this->connection))
		{
			$return = @mysqli_close($this->connection);
		}
		$this->connection = null;

		return $return;
	}

	/**
	 * Method to escape a string for usage in an SQL statement.
	 *
	 * @param   string  $text  The string to be escaped.
	 * @param   boolean $extra Optional parameter to provide extra escaping.
	 *
	 * @return  string  The escaped string.
	 */
	public function escape($text, $extra = false)
	{
		$result = @mysqli_real_escape_string($this->getConnection(), $text);

		if ($extra)
		{
			$result = addcslashes($result, '%_');
		}

		return $result;
	}

	/**
	 * Test to see if the MySQL connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 */
	public static function isSupported()
	{
		return (function_exists('mysqli_connect'));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return  boolean  True if connected to the database engine.
	 */
	public function connected()
	{
		if (is_object($this->connection))
		{
			return mysqli_ping($this->connection);
		}

		return false;
	}

	/**
	 * Get the number of affected rows for the previous executed SQL statement.
	 *
	 * @return  integer  The number of affected rows.
	 */
	public function getAffectedRows()
	{
		return mysqli_affected_rows($this->connection);
	}

	/**
	 * Get the number of returned rows for the previous executed SQL statement.
	 *
	 * @param   resource $cursor An optional database cursor resource to extract the row count from.
	 *
	 * @return  integer   The number of returned rows.
	 */
	public function getNumRows($cursor = null)
	{
		return mysqli_num_rows($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Get the current or query, or new JDatabaseQuery object.
	 *
	 * @param   boolean $new False to return the last query set, True to return a new JDatabaseQuery object.
	 *
	 * @return  mixed  The current value of the internal SQL variable or a new JDatabaseQuery object.
	 */
	public function getQuery($new = false)
	{
		if ($new)
		{
			return new AEQueryMysqli($this);
		}
		else
		{
			return $this->sql;
		}
	}

	/**
	 * Get the version of the database connector.
	 *
	 * @return  string  The database connector version.
	 */
	public function getVersion()
	{
		return mysqli_get_server_info($this->connection);
	}

	/**
	 * Determines if the database engine supports UTF-8 character encoding.
	 *
	 * @return  boolean  True if supported.
	 */
	public function hasUTF()
	{
		$verParts = explode('.', $this->getVersion());

		return ($verParts[0] == 5 || ($verParts[0] == 4 && $verParts[1] == 1 && (int)$verParts[2] >= 2));
	}

	/**
	 * Method to get the auto-incremented value from the last INSERT statement.
	 *
	 * @return  integer  The value of the auto-increment field from the last inserted row.
	 */
	public function insertid()
	{
		return mysqli_insert_id($this->connection);
	}

	/**
	 * Execute the SQL statement.
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 */
	public function query()
	{
		$this->open();

		if (!is_object($this->connection))
		{
			throw new RuntimeException($this->errorMsg, $this->errorNum);
		}

		// Take a local copy so that we don't modify the original query and cause issues later
		$query = $this->replacePrefix((string)$this->sql);
		if ($this->limit > 0 || $this->offset > 0)
		{
			$query .= ' LIMIT ' . $this->offset . ', ' . $this->limit;
		}

		// Increment the query counter.
		$this->count++;

		// If debugging is enabled then let's log the query.
		if ($this->debug)
		{
			// Add the query to the object queue.
			$this->log[] = $query;
		}

		// Reset the error values.
		$this->errorNum = 0;
		$this->errorMsg = '';

		// Execute the query. Error suppression is used here to prevent warnings/notices that the connection has been lost.
		$this->cursor = @mysqli_query($this->connection, $query);

		// If an error occurred handle it.
		if (!$this->cursor)
		{
			$this->errorNum = (int)mysqli_errno($this->connection);
			$this->errorMsg = (string)mysqli_error($this->connection) . ' SQL=' . $query;

			// Check if the server was disconnected.
			if (!$this->connected())
			{
				try
				{
					// Attempt to reconnect.
					$this->connection = null;
					$this->connect();
				}
					// If connect fails, ignore that exception and throw the normal exception.
				catch (RuntimeException $e)
				{
					throw new RuntimeException($this->errorMsg, $this->errorNum);
				}

				// Since we were able to reconnect, run the query again.
				return $this->execute();
			}
			// The server was not disconnected.
			else
			{
				throw new RuntimeException($this->errorMsg, $this->errorNum);
			}
		}

		return $this->cursor;
	}

	/**
	 * Select a database for use.
	 *
	 * @param   string $database The name of the database to select for use.
	 *
	 * @return  boolean  True if the database was successfully selected.
	 */
	public function select($database)
	{
		if (!$database)
		{
			return false;
		}

		if (!mysqli_select_db($this->connection, $database))
		{
			return false;
		}

		return true;
	}

	/**
	 * Set the connection to use UTF-8 character encoding.
	 *
	 * @return  boolean  True on success.
	 */
	public function setUTF()
	{
		return mysqli_set_charset($this->connection, 'utf8');
	}

	/**
	 * Method to fetch a row from the result set cursor as an array.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	protected function fetchArray($cursor = null)
	{
		return mysqli_fetch_row($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Method to fetch a row from the result set cursor as an associative array.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	public function fetchAssoc($cursor = null)
	{
		return mysqli_fetch_assoc($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @param   mixed  $cursor The optional result set cursor from which to fetch the row.
	 * @param   string $class  The class name to use for the returned row object.
	 *
	 * @return  mixed   Either the next row from the result set or false if there are no more rows.
	 */
	protected function fetchObject($cursor = null, $class = 'stdClass')
	{
		return mysqli_fetch_object($cursor ? $cursor : $this->cursor, $class);
	}

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  void
	 */
	public function freeResult($cursor = null)
	{
		mysqli_free_result($cursor ? $cursor : $this->cursor);
	}
}