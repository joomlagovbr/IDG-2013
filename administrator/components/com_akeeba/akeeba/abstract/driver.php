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
 * Database interface class.
 *
 * Based on Joomla! Platform 11.2
 */
interface AEAbstractDriverInterface
{
	/**
	 * Test to see if the connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 */
	public static function test();

	/**
	 * Test to see if the connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   11.2
	 */
	public static function isSupported();
}


/**
 * Database driver superclass. Used as the base of all Akeeba Engine database drivers.
 * Strongly based on Joomla Platform's JDatabase class.
 */
abstract class AEAbstractDriver extends AEAbstractObject implements AEAbstractDriverInterface
{
	/** @var string The name of the database. */
	protected $_database;

	/** @var string The name of the database driver. */
	public $name;

	/** @var resource The db conenction resource */
	protected $connection = '';

	/** @var    integer  The number of SQL statements executed by the database driver. */
	protected $count = 0;

	/** @var resource The database connection cursor from the last query. */
	protected $cursor;

	/** @var    boolean  The database driver debugging state. */
	protected $debug = false;

	/** @var int Query's limit */
	protected $limit = 0;

	/** @var    array  The log of executed SQL statements by the database driver. */
	protected $log = array();

	/** @var string Quote for named objects */
	protected $nameQuote = '';

	/** @var string  The null or zero representation of a timestamp for the database driver. */
	protected $nullDate;

	/** @var int Query's offset */
	protected $offset = 0;

	/** @var    array  Passed in upon instantiation and saved. */
	protected $options;

	/** @var mixed The SQL query string */
	protected $sql = '';

	/** @var string The prefix used in the database, if any */
	protected $tablePrefix = '';

	/** @var bool Support for UTF-8 */
	protected $utf = true;

	/** @var int The db server's error number */
	protected $errorNum = 0;

	/** @var string The db server's error string */
	protected $errorMsg = '';

	/** @var    array  JDatabaseDriver instances container. */
	protected static $instances = array();

	/** @var    string  The minimum supported database version. */
	protected static $dbMinimum;

	/** @var string Driver type, e.g. mysql, mssql, pgsql and so on */
	protected $driverType = '';

	/**
	 * Splits a string of multiple queries into an array of individual queries.
	 *
	 * @param   string $query Input SQL string with which to split into individual queries.
	 *
	 * @return  array  The queries from the input string separated into an array.
	 */
	public static function splitSql($query)
	{
		$start = 0;
		$open = false;
		$char = '';
		$end = strlen($query);
		$queries = array();

		for ($i = 0; $i < $end; $i++)
		{
			$current = substr($query, $i, 1);
			if (($current == '"' || $current == '\''))
			{
				$n = 2;

				while (substr($query, $i - $n + 1, 1) == '\\' && $n < $i)
				{
					$n++;
				}

				if ($n % 2 == 0)
				{
					if ($open)
					{
						if ($current == $char)
						{
							$open = false;
							$char = '';
						}
					}
					else
					{
						$open = true;
						$char = $current;
					}
				}
			}

			if (($current == ';' && !$open) || $i == $end - 1)
			{
				$queries[] = substr($query, $start, ($i - $start + 1));
				$start = $i + 1;
			}
		}

		return $queries;
	}

	/**
	 * Magic method to provide method alias support for quote() and quoteName().
	 *
	 * @param   string $method The called method.
	 * @param   array  $args   The array of arguments passed to the method.
	 *
	 * @return  string  The aliased method's return value or null.
	 */
	public function __call($method, $args)
	{
		if (empty($args))
		{
			return;
		}

		switch ($method)
		{
			case 'q':
				return $this->quote($args[0], isset($args[1]) ? $args[1] : true);
				break;
			case 'nq':
			case 'qn':
				return $this->quoteName($args[0]);
				break;
		}
	}

	/**
	 * Database object constructor
	 *
	 * @param    array    List of options used to configure the connection
	 */
	public function __construct($options)
	{
		$prefix = array_key_exists('prefix', $options) ? $options['prefix'] : '';
		$database = array_key_exists('database', $options) ? $options['database'] : '';
		$connection = array_key_exists('connection', $options) ? $options['connection'] : null;

		$this->tablePrefix = $prefix;
		$this->_database = $database;
		$this->connection = $connection;
		$this->errorNum = 0;
		$this->count = 0;
		$this->log = array();
		$this->options = $options;
	}

	/**
	 * Database object destructor
	 * @return bool
	 */
	public function __destruct()
	{
		return $this->close();
	}

	/**
	 * By default, when the object is shutting down, the connection is closed
	 */
	public function _onSerialize()
	{
		$this->close();
	}

	public function __wakeup()
	{
		$this->open();
	}

	/**
	 * Alter database's character set, obtaining query string from protected member.
	 *
	 * @param   string $dbName The database name that will be altered
	 *
	 * @return  string  The query that alter the database query string
	 *
	 * @throws  RuntimeException
	 */
	public function alterDbCharacterSet($dbName)
	{
		if (is_null($dbName))
		{
			throw new RuntimeException('Database name must not be null.');
		}

		$this->setQuery($this->getAlterDbCharacterSet($dbName));

		return $this->execute();
	}

	/**
	 * Opens a database connection. It MUST be overriden by children classes
	 * @return AEAbstractDriver
	 */
	public function open()
	{
		// Don't try to reconnect if we're already connected
		if (is_resource($this->connection) && !is_null($this->connection))
		{
			return $this;
		}

		// Determine utf-8 support
		$this->utf = $this->hasUTF();

		// Set charactersets (needed for MySQL 4.1.2+)
		if ($this->utf)
		{
			$this->setUTF();
		}

		// Select the current database
		$this->select($this->_database);

		return $this;
	}

	/**
	 * Closes the database connection
	 */
	abstract public function close();

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return  boolean  True if connected to the database engine.
	 */
	abstract public function connected();

	/**
	 * Create a new database using information from $options object, obtaining query string
	 * from protected member.
	 *
	 * @param   stdClass $options         Object used to pass user and database name to database driver.
	 *                                    This object must have "db_name" and "db_user" set.
	 * @param   boolean  $utf             True if the database supports the UTF-8 character set.
	 *
	 * @return  string  The query that creates database
	 *
	 * @throws  RuntimeException
	 */
	public function createDatabase($options, $utf = true)
	{
		if (is_null($options))
		{
			throw new RuntimeException('$options object must not be null.');
		}
		elseif (empty($options->db_name))
		{
			throw new RuntimeException('$options object must have db_name set.');
		}
		elseif (empty($options->db_user))
		{
			throw new RuntimeException('$options object must have db_user set.');
		}

		$this->setQuery($this->getCreateDatabaseQuery($options, $utf));

		return $this->execute();
	}

	/**
	 * Drops a table from the database.
	 *
	 * @param   string  $table    The name of the database table to drop.
	 * @param   boolean $ifExists Optionally specify that the table must exist before it is dropped.
	 *
	 * @return  AEAbstractDriver  Returns this object to support chaining.
	 */
	public abstract function dropTable($table, $ifExists = true);

	/**
	 * Method to escape a string for usage in an SQL statement.
	 *
	 * @param   string  $text  The string to be escaped.
	 * @param   boolean $extra Optional parameter to provide extra escaping.
	 *
	 * @return  string   The escaped string.
	 */
	abstract public function escape($text, $extra = false);

	/**
	 * Method to fetch a row from the result set cursor as an array.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	abstract protected function fetchArray($cursor = null);

	/**
	 * Method to fetch a row from the result set cursor as an associative array.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	abstract public function fetchAssoc($cursor = null);

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @param   mixed  $cursor The optional result set cursor from which to fetch the row.
	 * @param   string $class  The class name to use for the returned row object.
	 *
	 * @return  mixed   Either the next row from the result set or false if there are no more rows.
	 */
	abstract protected function fetchObject($cursor = null, $class = 'stdClass');

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @param   mixed $cursor The optional result set cursor from which to fetch the row.
	 *
	 * @return  void
	 */
	abstract public function freeResult($cursor = null);

	/**
	 * Get the number of affected rows for the previous executed SQL statement.
	 *
	 * @return  integer  The number of affected rows.
	 */
	abstract public function getAffectedRows();

	/**
	 * Return the query string to alter the database character set.
	 *
	 * @param   string $dbName The database name
	 *
	 * @return  string  The query that alter the database query string
	 */
	protected function getAlterDbCharacterSet($dbName)
	{
		$query = 'ALTER DATABASE ' . $this->quoteName($dbName) . ' CHARACTER SET `utf8`';

		return $query;
	}

	/**
	 * Return the query string to create new Database.
	 * Each database driver, other than MySQL, need to override this member to return correct string.
	 *
	 * @param   stdClass $options         Object used to pass user and database name to database driver.
	 *                                    This object must have "db_name" and "db_user" set.
	 * @param   boolean  $utf             True if the database supports the UTF-8 character set.
	 *
	 * @return  string  The query that creates database
	 */
	protected function getCreateDatabaseQuery($options, $utf)
	{
		if ($utf)
		{
			$query = 'CREATE DATABASE ' . $this->quoteName($options->db_name) . ' CHARACTER SET `utf8`';
		}
		else
		{
			$query = 'CREATE DATABASE ' . $this->quoteName($options->db_name);
		}

		return $query;
	}

	/**
	 * Method to get the database collation in use by sampling a text field of a table in the database.
	 *
	 * @return  mixed  The collation in use by the database or boolean false if not supported.
	 */
	abstract public function getCollation();

	/**
	 * Method that provides access to the underlying database connection. Useful for when you need to call a
	 * proprietary method such as postgresql's lo_* methods.
	 *
	 * @return  resource  The underlying database connection resource.
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Inherits the connection of another database driver. Useful for cloning
	 * the CMS database connection into an Akeeba Engine database driver.
	 *
	 * @param resource $connection
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Get the total number of SQL statements executed by the database driver.
	 *
	 * @return  integer
	 *
	 * @since   11.1
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * Gets the name of the database used by this conneciton.
	 *
	 * @return  string
	 */
	protected function getDatabase()
	{
		return $this->_database;
	}

	/**
	 * Returns a PHP date() function compliant date format for the database driver.
	 *
	 * @return  string  The format string.
	 */
	public function getDateFormat()
	{
		return 'Y-m-d H:i:s';
	}

	/**
	 * Get the database driver SQL statement log.
	 *
	 * @return  array  SQL statements executed by the database driver.
	 *
	 * @since   11.1
	 */
	public function getLog()
	{
		return $this->log;
	}

	/**
	 * Get the minimum supported database version.
	 *
	 * @return  string  The minimum version number for the database driver.
	 *
	 * @since   12.1
	 */
	public function getMinimum()
	{
		return static::$dbMinimum;
	}

	/**
	 * Get the null or zero representation of a timestamp for the database driver.
	 *
	 * @return  string  Null or zero representation of a timestamp.
	 */
	public function getNullDate()
	{
		return $this->nullDate;
	}

	/**
	 * Get the number of returned rows for the previous executed SQL statement.
	 *
	 * @param   resource $cursor An optional database cursor resource to extract the row count from.
	 *
	 * @return  integer   The number of returned rows.
	 */
	abstract public function getNumRows($cursor = null);

	/**
	 * Get the database table prefix
	 *
	 * @return string The database prefix
	 */
	public final function getPrefix()
	{
		return $this->tablePrefix;
	}

	/**
	 * Get the current query object or a new AEAbstractQuery object.
	 *
	 * @param   boolean $new False to return the current query object, True to return a new AEAbstractQuery object.
	 *
	 * @return  AEAbstractQuery  The current query object or a new object extending the AEAbstractQuery class.
	 */
	abstract public function getQuery($new = false);

	/**
	 * Retrieves field information about the given tables.
	 *
	 * @param   string  $table    The name of the database table.
	 * @param   boolean $typeOnly True (default) to only return field types.
	 *
	 * @return  array  An array of fields by table.
	 */
	abstract public function getTableColumns($table, $typeOnly = true);

	/**
	 * Shows the table CREATE statement that creates the given tables.
	 *
	 * @param   mixed $tables A table name or a list of table names.
	 *
	 * @return  array  A list of the create SQL for the tables.
	 */
	abstract public function getTableCreate($tables);

	/**
	 * Retrieves field information about the given tables.
	 *
	 * @param   mixed $tables A table name or a list of table names.
	 *
	 * @return  array  An array of keys for the table(s).
	 */
	abstract public function getTableKeys($tables);

	/**
	 * Method to get an array of all tables in the database.
	 *
	 * @return  array  An array of all the tables in the database.
	 */
	abstract public function getTableList();

	/**
	 * Determine whether or not the database engine supports UTF-8 character encoding.
	 *
	 * @return  boolean  True if the database engine supports UTF-8 character encoding.
	 */
	public function getUTFSupport()
	{
		return $this->utf;
	}

	/**
	 * Determine whether or not the database engine supports UTF-8 character encoding.
	 *
	 * @return  boolean  True if the database engine supports UTF-8 character encoding.
	 */
	public function hasUTFSupport()
	{
		return $this->utf;
	}

	/**
	 * Determines if the database engine supports UTF-8 character encoding.
	 *
	 * @return  boolean  True if supported.
	 */
	public function hasUTF()
	{
		return $this->utf;
	}

	/**
	 * Get the version of the database connector
	 *
	 * @return  string  The database connector version.
	 */
	abstract public function getVersion();

	/**
	 * Method to get the auto-incremented value from the last INSERT statement.
	 *
	 * @return  integer  The value of the auto-increment field from the last inserted row.
	 */
	abstract public function insertid();

	/**
	 * Inserts a row into a table based on an object's properties.
	 *
	 * @param   string $table   The name of the database table to insert into.
	 * @param   object &$object A reference to an object whose public properties match the table fields.
	 * @param   string $key     The name of the primary key. If provided the object property is updated.
	 *
	 * @return  boolean    True on success.
	 */
	public function insertObject($table, &$object, $key = null)
	{
		$fields = array();
		$values = array();

		// Iterate over the object variables to build the query fields and values.
		foreach (get_object_vars($object) as $k => $v)
		{
			// Only process non-null scalars.
			if (is_array($v) or is_object($v) or $v === null)
			{
				continue;
			}

			// Ignore any internal fields.
			if ($k[0] == '_')
			{
				continue;
			}

			// Prepare and sanitize the fields and values for the database query.
			$fields[] = $this->quoteName($k);
			$values[] = $this->quote($v);
		}

		// Create the base insert statement.
		$query = $this->getQuery(true)
			->insert($this->quoteName($table))
			->columns($fields)
			->values(implode(',', $values));

		// Set the query and execute the insert.
		$this->setQuery($query);
		if (!$this->execute())
		{
			return false;
		}

		// Update the primary key if it exists.
		$id = $this->insertid();
		if ($key && $id && is_string($key))
		{
			$object->$key = $id;
		}

		return true;
	}

	/**
	 * Method to check whether the installed database version is supported by the database driver
	 *
	 * @return  boolean  True if the database version is supported
	 *
	 * @since   12.1
	 */
	public function isMinimumVersion()
	{
		return version_compare($this->getVersion(), static::$dbMinimum) >= 0;
	}

	/**
	 * Method to get the first row of the result set from the database query as an associative array
	 * of ['field_name' => 'row_value'].
	 *
	 * @return  mixed  The return value or null if the query failed.
	 */
	public function loadAssoc()
	{
		$ret = null;

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get the first row from the result set as an associative array.
		if ($array = $this->fetchAssoc($cursor))
		{
			$ret = $array;
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $ret;
	}

	/**
	 * Method to get an array of the result set rows from the database query where each row is an associative array
	 * of ['field_name' => 'row_value'].  The array of rows can optionally be keyed by a field name, but defaults to
	 * a sequential numeric array.
	 *
	 * NOTE: Chosing to key the result array by a non-unique field name can result in unwanted
	 * behavior and should be avoided.
	 *
	 * @param   string $key    The name of a field on which to key the result array.
	 * @param   string $column An optional column name. Instead of the whole row, only this column value will be in
	 *                         the result array.
	 *
	 * @return  mixed   The return value or null if the query failed.
	 */
	public function loadAssocList($key = null, $column = null)
	{
		$array = array();

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get all of the rows from the result set.
		while ($row = $this->fetchAssoc($cursor))
		{
			$value = ($column) ? (isset($row[$column]) ? $row[$column] : $row) : $row;
			if ($key)
			{
				$array[$row[$key]] = $value;
			}
			else
			{
				$array[] = $value;
			}
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $array;
	}

	/**
	 * Method to get an array of values from the <var>$offset</var> field in each row of the result set from
	 * the database query.
	 *
	 * @param   integer $offset The row offset to use to build the result array.
	 *
	 * @return  mixed    The return value or null if the query failed.
	 */
	public function loadColumn($offset = 0)
	{
		$array = array();

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get all of the rows from the result set as arrays.
		while ($row = $this->fetchArray($cursor))
		{
			$array[] = $row[$offset];
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $array;
	}

	/**
	 * Method to get the next row in the result set from the database query as an object.
	 *
	 * @param   string $class The class name to use for the returned row object.
	 *
	 * @return  mixed   The result of the query as an array, false if there are no more rows.
	 */
	public function loadNextObject($class = 'stdClass')
	{
		static $cursor = null;

		// Execute the query and get the result set cursor.
		if (is_null($cursor))
		{
			if (!($cursor = $this->execute()))
			{
				return $this->errorNum ? null : false;
			}
		}

		// Get the next row from the result set as an object of type $class.
		if ($row = $this->fetchObject($cursor, $class))
		{
			return $row;
		}

		// Free up system resources and return.
		$this->freeResult($cursor);
		$cursor = null;

		return false;
	}

	/**
	 * Method to get the next row in the result set from the database query as an array.
	 *
	 * @return  mixed  The result of the query as an array, false if there are no more rows.
	 */
	public function loadNextRow()
	{
		static $cursor = null;

		// Execute the query and get the result set cursor.
		if (is_null($cursor))
		{
			if (!($cursor = $this->execute()))
			{
				return $this->errorNum ? null : false;
			}
		}

		// Get the next row from the result set as an object of type $class.
		if ($row = $this->fetchArray($cursor))
		{
			return $row;
		}

		// Free up system resources and return.
		$this->freeResult($cursor);
		$cursor = null;

		return false;
	}

	/**
	 * Method to get the first row of the result set from the database query as an object.
	 *
	 * @param   string $class The class name to use for the returned row object.
	 *
	 * @return  mixed   The return value or null if the query failed.
	 */
	public function loadObject($class = 'stdClass')
	{
		$ret = null;

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get the first row from the result set as an object of type $class.
		if ($object = $this->fetchObject($cursor, $class))
		{
			$ret = $object;
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $ret;
	}

	/**
	 * Method to get an array of the result set rows from the database query where each row is an object.  The array
	 * of objects can optionally be keyed by a field name, but defaults to a sequential numeric array.
	 *
	 * NOTE: Choosing to key the result array by a non-unique field name can result in unwanted
	 * behavior and should be avoided.
	 *
	 * @param   string $key   The name of a field on which to key the result array.
	 * @param   string $class The class name to use for the returned row objects.
	 *
	 * @return  mixed   The return value or null if the query failed.
	 */
	public function loadObjectList($key = '', $class = 'stdClass')
	{
		$array = array();

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get all of the rows from the result set as objects of type $class.
		while ($row = $this->fetchObject($cursor, $class))
		{
			if ($key)
			{
				$array[$row->$key] = $row;
			}
			else
			{
				$array[] = $row;
			}
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $array;
	}

	/**
	 * Method to get the first field of the first row of the result set from the database query.
	 *
	 * @return  mixed  The return value or null if the query failed.
	 */
	public function loadResult()
	{
		$ret = null;

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get the first row from the result set as an array.
		if ($row = $this->fetchArray($cursor))
		{
			$ret = $row[0];
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $ret;
	}

	/**
	 * Method to get the first row of the result set from the database query as an array.  Columns are indexed
	 * numerically so the first column in the result set would be accessible via <var>$row[0]</var>, etc.
	 *
	 * @return  mixed  The return value or null if the query failed.
	 */
	public function loadRow()
	{
		$ret = null;

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get the first row from the result set as an array.
		if ($row = $this->fetchArray($cursor))
		{
			$ret = $row;
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $ret;
	}

	/**
	 * Method to get an array of the result set rows from the database query where each row is an array.  The array
	 * of objects can optionally be keyed by a field offset, but defaults to a sequential numeric array.
	 *
	 * NOTE: Choosing to key the result array by a non-unique field can result in unwanted
	 * behavior and should be avoided.
	 *
	 * @param   string $key The name of a field on which to key the result array.
	 *
	 * @return  mixed   The return value or null if the query failed.
	 */
	public function loadRowList($key = null)
	{
		$array = array();

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->execute()))
		{
			return null;
		}

		// Get all of the rows from the result set as arrays.
		while ($row = $this->fetchArray($cursor))
		{
			if ($key !== null)
			{
				$array[$row[$key]] = $row;
			}
			else
			{
				$array[] = $row;
			}
		}

		// Free up system resources and return.
		$this->freeResult($cursor);

		return $array;
	}

	/**
	 * Locks a table in the database.
	 *
	 * @param   string $tableName The name of the table to unlock.
	 *
	 * @return  AEAbstractDriver  Returns this object to support chaining.
	 */
	public abstract function lockTable($tableName);

	/**
	 * Execute the SQL statement.
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 */
	abstract public function query();

	/**
	 * An alias for query(), for compatibility with Joomla! 2.5+ which has deprecated
	 * query() in favour of execute()
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 */
	public function execute()
	{
		return $this->query();
	}

	/**
	 * Method to quote and optionally escape a string to database requirements for insertion into the database.
	 *
	 * @param   string  $text   The string to quote.
	 * @param   boolean $escape True (default) to escape the string, false to leave it unchanged.
	 */
	public function quote($text, $escape = true)
	{
		return '\'' . ($escape ? $this->escape($text) : $text) . '\'';
	}

	/**
	 * Wrap an SQL statement identifier name such as column, table or database names in quotes to prevent injection
	 * risks and reserved word conflicts.
	 *
	 * @param   mixed $name       The identifier name to wrap in quotes, or an array of identifier names to wrap in quotes.
	 *                            Each type supports dot-notation name.
	 * @param   mixed $as         The AS query part associated to $name. It can be string or array, in latter case it has to be
	 *                            same length of $name; if is null there will not be any AS part for string or array element.
	 */
	public function quoteName($name, $as = null)
	{
		if (is_string($name))
		{
			$quotedName = $this->quoteNameStr(explode('.', $name));

			$quotedAs = '';
			if (!is_null($as))
			{
				settype($as, 'array');
				$quotedAs .= ' AS ' . $this->quoteNameStr($as);
			}

			return $quotedName . $quotedAs;
		}
		else
		{
			$fin = array();

			if (is_null($as))
			{
				foreach ($name as $str)
				{
					$fin[] = $this->quoteName($str);
				}
			}
			elseif (is_array($name) && (count($name) == count($as)))
			{
				$count = count($name);
				for ($i = 0; $i < $count; $i++)
				{
					$fin[] = $this->quoteName($name[$i], $as[$i]);
				}
			}

			return $fin;
		}
	}

	/**
	 * Quote strings coming from quoteName call.
	 *
	 * @param   array $strArr Array of strings coming from quoteName dot-explosion.
	 *
	 * @return  string  Dot-imploded string of quoted parts.
	 */
	protected function quoteNameStr($strArr)
	{
		$parts = array();
		$q = $this->nameQuote;

		foreach ($strArr as $part)
		{
			if (is_null($part))
			{
				continue;
			}

			if (strlen($q) == 1)
			{
				$parts[] = $q . $part . $q;
			}
			else
			{
				$parts[] = $q{0} . $part . $q{1};
			}
		}

		return implode('.', $parts);
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the string held is the
	 * <var>tablePrefix</var> class variable.
	 *
	 * @param   string $query  The SQL statement to prepare.
	 * @param   string $prefix The common table prefix.
	 *
	 * @return  string  The processed SQL statement.
	 */
	public function replacePrefix($query, $prefix = '#__')
	{
		$escaped = false;
		$startPos = 0;
		$quoteChar = '';
		$literal = '';

		$query = trim($query);
		$n = strlen($query);

		while ($startPos < $n)
		{
			$ip = strpos($query, $prefix, $startPos);
			if ($ip === false)
			{
				break;
			}

			$j = strpos($query, "'", $startPos);
			$k = strpos($query, '"', $startPos);
			if (($k !== false) && (($k < $j) || ($j === false)))
			{
				$quoteChar = '"';
				$j = $k;
			}
			else
			{
				$quoteChar = "'";
			}

			if ($j === false)
			{
				$j = $n;
			}

			$literal .= str_replace($prefix, $this->tablePrefix, substr($query, $startPos, $j - $startPos));
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n)
			{
				break;
			}

			// Quote comes first, find end of quote
			while (true)
			{
				$k = strpos($query, $quoteChar, $j);
				$escaped = false;
				if ($k === false)
				{
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $query{$l} == '\\')
				{
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped)
				{
					$j = $k + 1;
					continue;
				}
				break;
			}
			if ($k === false)
			{
				// Error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr($query, $startPos, $k - $startPos + 1);
			$startPos = $k + 1;
		}
		if ($startPos < $n)
		{
			$literal .= substr($query, $startPos, $n - $startPos);
		}

		return $literal;
	}

	/**
	 * Renames a table in the database.
	 *
	 * @param   string $oldTable The name of the table to be renamed
	 * @param   string $newTable The new name for the table.
	 * @param   string $backup   Table prefix
	 * @param   string $prefix   For the table - used to rename constraints in non-mysql databases
	 *
	 * @return  AEAbstractDriver  Returns this object to support chaining.
	 */
	public abstract function renameTable($oldTable, $newTable, $backup = null, $prefix = null);

	/**
	 * Select a database for use.
	 *
	 * @param   string $database The name of the database to select for use.
	 *
	 * @return  boolean  True if the database was successfully selected.
	 */
	abstract public function select($database);

	/**
	 * Sets the database debugging state for the driver.
	 *
	 * @param   boolean $level True to enable debugging.
	 *
	 * @return  boolean  The old debugging level.
	 */
	public function setDebug($level)
	{
		$previous = $this->debug;
		$this->debug = (bool)$level;

		return $previous;
	}

	/**
	 * Sets the SQL statement string for later execution.
	 *
	 * @param   mixed   $query  The SQL statement to set either as a AEAbstractQuery object or a string.
	 * @param   integer $offset The affected row offset to set.
	 * @param   integer $limit  The maximum affected rows to set.
	 *
	 * @return  AEAbstractDriver  This object to support method chaining.
	 */
	public function setQuery($query, $offset = 0, $limit = 0)
	{
		$this->sql = $query;
		$this->limit = (int)$limit;
		$this->offset = (int)$offset;

		return $this;
	}

	/**
	 * Set the connection to use UTF-8 character encoding.
	 *
	 * @return  boolean  True on success.
	 */
	abstract public function setUTF();

	/**
	 * Method to commit a transaction.
	 *
	 * @return  void
	 */
	abstract public function transactionCommit();

	/**
	 * Method to roll back a transaction.
	 *
	 * @return  void
	 */
	abstract public function transactionRollback();

	/**
	 * Method to initialize a transaction.
	 *
	 * @return  void
	 */
	abstract public function transactionStart();

	/**
	 * Method to truncate a table.
	 *
	 * @param   string $table The table to truncate
	 *
	 * @return  void
	 */
	public function truncateTable($table)
	{
		$this->setQuery('TRUNCATE TABLE ' . $this->quoteName($table));
		$this->query();
	}

	/**
	 * Updates a row in a table based on an object's properties.
	 *
	 * @param   string  $table   The name of the database table to update.
	 * @param   object  &$object A reference to an object whose public properties match the table fields.
	 * @param   string  $key     The name of the primary key.
	 * @param   boolean $nulls   True to update null fields or false to ignore them.
	 *
	 * @return  boolean  True on success.
	 */
	public function updateObject($table, &$object, $key, $nulls = false)
	{
		$fields = array();
		$where = array();

		if (is_string($key))
		{
			$key = array($key);
		}

		if (is_object($key))
		{
			$key = (array)$key;
		}

		// Create the base update statement.
		$statement = 'UPDATE ' . $this->quoteName($table) . ' SET %s WHERE %s';

		// Iterate over the object variables to build the query fields/value pairs.
		foreach (get_object_vars($object) as $k => $v)
		{
			// Only process scalars that are not internal fields.
			if (is_array($v) or is_object($v) or $k[0] == '_')
			{
				continue;
			}

			// Set the primary key to the WHERE clause instead of a field to update.
			if (in_array($k, $key))
			{
				$where[] = $this->quoteName($k) . '=' . $this->quote($v);
				continue;
			}

			// Prepare and sanitize the fields and values for the database query.
			if ($v === null)
			{
				// If the value is null and we want to update nulls then set it.
				if ($nulls)
				{
					$val = 'NULL';
				}
				// If the value is null and we do not want to update nulls then ignore this field.
				else
				{
					continue;
				}
			}
			// The field is not null so we prep it for update.
			else
			{
				$val = $this->quote($v);
			}

			// Add the field to be updated.
			$fields[] = $this->quoteName($k) . '=' . $val;
		}

		// We don't have any fields to update.
		if (empty($fields))
		{
			return true;
		}

		// Set the query and execute the update.
		$this->setQuery(sprintf($statement, implode(",", $fields), implode(' AND ', $where)));

		return $this->execute();
	}

	/**
	 * Unlocks tables in the database.
	 *
	 * @return  AEAbstractDriver  Returns this object to support chaining.
	 */
	public abstract function unlockTables();

	/**
	 * Get the error message
	 * @return string The error message for the most recent query
	 */
	public final function getErrorMsg($escaped = false)
	{
		if ($escaped)
		{
			return addslashes($this->errorMsg);
		}
		else
		{
			return $this->errorMsg;
		}
	}

	/**
	 * Get the error number
	 * @return int The error number for the most recent query
	 */
	public final function getErrorNum()
	{
		return $this->errorNum;
	}

	/**
	 * Method to escape a string for usage in an SQL statement.
	 *
	 * @param   string  $text  The string to be escaped.
	 * @param   boolean $extra Optional parameter to provide extra escaping.
	 *
	 * @return  string  The escaped string.
	 */
	public function getEscaped($text, $extra = false)
	{
		return $this->escape($text, $extra);
	}

	/**
	 * Retrieves field information about the given tables.
	 *
	 * @param   mixed   $tables   A table name or a list of table names.
	 * @param   boolean $typeOnly True to only return field types.
	 *
	 * @return  array  An array of fields by table.
	 */
	public function getTableFields($tables, $typeOnly = true)
	{
		$results = array();

		settype($tables, 'array');

		foreach ($tables as $table)
		{
			$results[$table] = $this->getTableColumns($table, $typeOnly);
		}

		return $results;
	}

	/**
	 * Method to get an array of values from the <var>$offset</var> field in each row of the result set from
	 * the database query.
	 *
	 * @param   integer $offset The row offset to use to build the result array.
	 *
	 * @return  mixed    The return value or null if the query failed.
	 */
	public function loadResultArray($offset = 0)
	{
		return $this->loadColumn($offset);
	}

	/**
	 * Wrap an SQL statement identifier name such as column, table or database names in quotes to prevent injection
	 * risks and reserved word conflicts.
	 *
	 * @param   string $name The identifier name to wrap in quotes.
	 *
	 * @return  string  The quote wrapped name.
	 */
	public function nameQuote($name)
	{
		return $this->quoteName($name);
	}

	/**
	 * Returns the abstracted name of a database object
	 *
	 * @param string $tableName
	 *
	 * @return srting
	 */
	public function getAbstract($tableName)
	{
		$prefix = $this->getPrefix();

		// Don't return abstract names for non-CMS tables
		if (is_null($prefix))
		{
			return $tableName;
		}

		switch ($prefix)
		{
			case '':
				// This is more of a hack; it assumes all tables are CMS tables if the prefix is empty.
				return '#__' . $tableName;
				break;

			default:
				// Normal behaviour for 99% of sites
				$tableAbstract = $tableName;
				if (!empty($prefix))
				{
					if (substr($tableName, 0, strlen($prefix)) == $prefix)
					{
						$tableAbstract = '#__' . substr($tableName, strlen($prefix));
					}
					else
					{
						$tableAbstract = $tableName;
					}
				}

				return $tableAbstract;
				break;
		}
	}

	public final function getDriverType()
	{
		return $this->driverType;
	}

	public static function test()
	{
		return self::isSupported();
	}
}