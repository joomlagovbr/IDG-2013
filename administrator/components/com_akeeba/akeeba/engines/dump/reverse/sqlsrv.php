<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * A SQL Server database dump class, using reverse engineering of the
 * INFORMATION_SCHEMA views to deduce the DDL of the database entities.
 *
 * Configuration parameters:
 * host            <string>    SQL database server host name or IP address
 * port            <string>    SQL database server port (optional)
 * username        <string>    SQL user name, for authentication
 * password        <string>    SQL password, for authentication
 * database        <string>    SQL database
 * dumpFile        <string>    Absolute path to dump file; must be writable (optional; if left blank it is automatically calculated)
 */
class AEDumpReverseSqlsrv extends AEDumpNativeMysql
{
	/**
	 * Implements the constructor of the class
	 *
	 * @return AEDumpNativeMysql
	 */
	public function __construct()
	{
		// DO NOT RUN THE PARENT::__CONSTRUCT; we are directly instanciating an AEAbstractObject here
		// parent::__construct();
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: New instance");

		$this->postProcessValues = true;
	}

	/**
	 * Applies the SQL compatibility setting
	 */
	protected function enforceSQLCompatibility()
	{
		//  Do nothing for SQL server
	}

	protected function getTablesToBackup()
	{
		$configuration = AEFactory::getConfiguration();
		$notracking = $configuration->get('engine.dump.native.nodependencies', 0);

		// First, get a map of table names <--> abstract names
		$this->reverse_engineer_db();
		if ($this->getError())
		{
			return;
		}

		if (!$notracking)
		{
			// Process dependencies and rearrange tables respecting them
			$this->process_dependencies();
			if ($this->getError())
			{
				return;
			}

			// Remove dependencies array
			$this->dependencies = array();
		}
	}

	private function reverse_engineer_db()
	{
		// Get a database connection
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Reverse engineering database");
		$db = $this->getDB();
		if ($this->getError())
		{
			return;
		}

		// Reset internal tables
		$this->table_name_map = array();
		$this->tables_data = array();
		$this->dependencies = array();

		// Get the list of all database tables and views
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Reverse engineering Tables");
		$this->reverse_engineer_tables($db);
		$this->reverse_engineer_views($db);

		// Optional backup of triggers, functions and procedures
		$registry = AEFactory::getConfiguration();
		$enable_entities = $registry->get('engine.dump.native.advanced_entitites', true);
		$notracking = $registry->get('engine.dump.native.nodependencies', 0);

		if ($enable_entities && ($notracking == 0))
		{
			// @todo Triggers
			// AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__." :: Reverse engineering Triggers");

			// @todo Functions
			// AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__." :: Reverse engineering Functions");

			// @todo Procedures
			// AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__." :: Reverse engineering Procedures");
		}
	}

	/**
	 * Reverse engineers the Table definitions of this database
	 *
	 * @param   AEAbstractDriver $dbi Database connection to INFORMATION_SCHEMA
	 */
	protected function reverse_engineer_tables(&$dbi)
	{
		$schema_name = $this->database;
		$sql = 'SELECT * FROM sys.objects WHERE type = ' . $dbi->quote('U') . ' ORDER BY [name] ASC';
		$dbi->setQuery($sql);
		$all_tables = $dbi->loadObjectList();

		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');

		// If we have filters, make sure the tables pass the filtering
		$filters = AEFactory::getFilters();
		if (!empty($all_tables))
		{
			foreach ($all_tables as $table_object)
			{
				// Extract the table name
				$table_name = $table_object->name;

				// Filter and convert
				if (substr($table_name, 0, 3) == '#__')
				{
					AEUtilLogger::WriteLog(_AE_LOG_WARNING, __CLASS__ . " :: Table $table_name has a prefix of #__. This would cause restoration errors; table skipped.");
					continue;
				}
				$table_abstract = $this->getAbstract($table_name);
				if (substr($table_abstract, 0, 4) != 'bak_') // Skip backup tables
				{
					// Apply exclusion filters
					if (!$filters->isFiltered($table_abstract, $root, 'dbobject', 'all'))
					{
						AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Adding $table_name (internal name $table_abstract)");
						$this->table_name_map[$table_name] = $table_abstract;
					}
					else
					{
						AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Skipping $table_name (internal name $table_abstract)");
						continue;
					}
				}
				else
				{
					AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Backup table $table_name automatically skipped.");
					continue;
				}

				// Still here? The table is added. We now have to store its
				// create command, dependency info and so on
				$new_entry = array(
					'type'         => 'table',
					'dump_records' => true
				);

				// Table Data Filter - skip dumping table contents of filtered out tables
				if ($filters->isFiltered($table_abstract, $root, 'dbobject', 'content'))
				{
					$new_entry['dump_records'] = false;
				}

				$new_entry['create'] = $this->get_create_table($dbi, $table_name, $table_abstract, $table_object, $dependencies);
				$new_entry['dependencies'] = $dependencies;

				$this->tables_data[$table_name] = $new_entry;
			}
		}
	}

	/**
	 * Gets the CREATE TABLE command of a given table
	 *
	 * @param   AEAbstractDriver $dbi            The db connection to the INFORMATION_SCHEMA db
	 * @param   string           $table_name     The name of the table
	 * @param   string           $table_abstract The abstract name of the table
	 * @param   stdClass         $table_object   The SYS.OBJECTS record for this table
	 * @param   array            $dependencies   Dependency tracking information
	 *
	 * @return  string  The CREATE TABLE definition
	 */
	protected function get_create_table(&$dbi, $table_name, $table_abstract, $table_object, &$dependencies)
	{
		$configuration = AEFactory::getConfiguration();
		$notracking = $configuration->get('engine.dump.native.nodependencies', 0);
		$useabstract = AEUtilScripting::getScriptingParameter('db.abstractnames', 1);

		$columns_sql = array();
		$keys_sql = array();
		$constraints_sql = array();
		$indexes_sql = array();

		// =====================================================================
		// ========== GENERATE SQL FOR COLUMNS
		// =====================================================================
		// Get identity columns for this table
		$sysObjectID = $table_object->object_id;
		$query = 'SELECT COUNT(*) FROM sys.identity_columns WHERE object_id = ' . $dbi->quote($sysObjectID);
		$dbi->setQuery($query);
		$countIdentityColumns = $dbi->loadResult();
		if ($countIdentityColumns)
		{
			/**
			 * $query = 'SELECT column_id, name, seed_value, increment_value FROM sys.identity_columns WHERE object_id = ' . $dbi->quote($sysObjectID)
			 * . ' ORDER BY column_id ASC';
			 **/
			$query = 'select * from INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ' . $dbi->quote($table_name) .
				'and COLUMNPROPERTY(object_id(TABLE_NAME), COLUMN_NAME, \'IsIdentity\') = 1';
			$dbi->setQuery($query);
			$identityColumns = $dbi->loadAssocList('COLUMN_NAME');
		}
		else
		{
			$identityColumns = array();
		}

		// Get columns
		$query = 'SELECT * FROM information_schema.columns WHERE table_catalog = ' . $dbi->quote($this->database)
			. ' AND table_name = ' . $dbi->quote($table_name) . ' ORDER BY ordinal_position ASC';
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();
		foreach ($allColumns as $oColumn)
		{
			$line = '[' . $oColumn->COLUMN_NAME . '] [' . $oColumn->DATA_TYPE . ']';
			switch ($oColumn->DATA_TYPE)
			{
				case 'bigint':
				case 'int':
				case 'smallint':
				case 'tinyint':
					/* NOT SUPPORTED *
					$precision = $oColumn->NUMERIC_PRECISION;
					$scale = $oColumn->NUMERIC_SCALE;
					if ($precision)
					{
						$line .= '(';
						if ($precision && $scale)
						{
							$line .= $precision . ', ' . $scale;
						}
						else
						{
							$line .= $precision;
						}
						$line .= ')';
					}
					*/
					break;

				case 'nvarchar':
				case 'nchar':
					$len = $oColumn->CHARACTER_MAXIMUM_LENGTH;
					if ($len < 0)
					{
						$len = 'max';
					}
					$line .= '(' . $len . ')';
					break;

				case 'datetime':
				case 'datetime2':
					/* NOT SUPPORTED *
					$precision = $oColumn->DATETIME_PRECISION;
					if ($precision)
					{
						$line .= '(' . $precision . ')';
					}
					*/
					break;

				case 'float':
				case 'real':
					break;
			}
			$line .= ($oColumn->IS_NULLABLE == 'YES') ? 'NULL ' : 'NOT NULL ';

			if (array_key_exists($oColumn->COLUMN_NAME, $identityColumns))
			{
				/**
				 * $seed = $identityColumns[$oColumn->COLUMN_NAME]['seed_value'];
				 * $increment = $identityColumns[$oColumn->COLUMN_NAME]['increment_value'];
				 **/
				// fake the seed and increment because Microsoft sucks and their API is broken!

				$qLala = 'SELECT MAX(' . $oColumn->COLUMN_NAME . ') FROM ' . $dbi->quoteName($table_name);
				$dbi->setQuery($qLala);
				$seed = (int)($dbi->loadResult());

				$increment = 1;

				$line .= ' IDENTITY (' . $seed . ', ' . $increment . ')';
			}

			$line .= ($oColumn->COLUMN_DEFAULT == '') ? '' : (' DEFAULT ' . $oColumn->COLUMN_DEFAULT . ' ');

			$columns_sql[] = $line;
		}

		// =====================================================================
		// ========== GENERATE SQL FOR KEYS AND INDICES
		// =====================================================================
		// Get the primary and unique key names
		$query = 'SELECT * from sys.indexes where object_id = OBJECT_ID(' . $dbi->q($table_name) . ')';
		$dbi->setQuery($query);
		$allKeys = $dbi->loadObjectList('name');

		// Get the columns per key and key information
		$query = 'select c.name, ic.* from sys.index_columns as ic inner join sys.columns as c on(c.object_id = ic.object_id and c.column_id = ic.column_id) '
			. 'where ic.object_id = OBJECT_ID(' . $dbi->q($table_name) . ') order by index_id ASC, index_column_id ASC';
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();

		$rawKeys = array();
		if (!empty($allKeys))
		{
			foreach ($allKeys as $currentKey)
			{
				if (empty($currentKey->name))
				{
					continue;
				}

				$isUnique = $currentKey->is_unique == 1;
				$isPrimary = $currentKey->is_primary_key == 1;

				$keyName = $currentKey->name;
				if ($useabstract && strlen($this->prefix) && substr($this->prefix, -1) == '_')
				{
					$keyName = str_replace($this->prefix, '#__', $keyName);
				}

				if ($isPrimary)
				{
					$line = 'CONSTRAINT [' . $keyName . '] ';
					$line .= 'PRIMARY KEY ' . $currentKey->type_desc;
				}
				elseif ($isUnique)
				{
					$line = 'CONSTRAINT [' . $keyName . '] ';
					$line .= 'UNIQUE ' . $currentKey->type_desc;
				}
				else
				{
					//$line = 'CREATE ' . $currentKey->type_desc . ' INDEX [' . $this->getAbstract($currentKey->name) . '] ON [' . $table_abstract . ']';
					if ($useabstract)
					{
						$line = 'CREATE INDEX [' . $keyName . '] ON [' . $table_abstract . ']';
					}
					else
					{
						$line = 'CREATE INDEX [' . $keyName . '] ON [' . $table_name . ']';
					}
				}
				$line .= '(';

				// Add columns
				$cols = array();
				foreach ($allColumns as $oColumn)
				{
					if ($oColumn->index_id != $currentKey->index_id)
					{
						continue;
					}

					$cols[] = $oColumn->name . ' ' . ($oColumn->is_descending_key ? 'DESC' : 'ASC');
				}
				$line .= implode(', ', $cols);

				$line .= ')';

				// append WITH (...blah...) ON [PRIMARY]
				$line .= ' WITH (';
				if ($isPrimary || $isUnique)
				{
					$line .= 'PAD_INDEX = ' . ($currentKey->is_padded ? 'ON' : 'OFF');
					$line .= ', STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]';
				}
				else
				{
					$line .= 'STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF)';
				}

				// Add primary/unique keys to $keys_sql, add indices to $indexes_sql and paste them as new lines below the create command
				if ($isPrimary || $isUnique)
				{
					$keys_sql[] = $line;
				}
				else
				{
					$indexes_sql[] = $line . ';';
				}
			}
		}

		// =====================================================================
		// ========== GENERATE SQL FOR FOREIGN KEYS
		// =====================================================================
		// Get the foreign key names
		$query = 'SELECT * FROM sys.foreign_keys WHERE parent_object_id = ' . $dbi->quote($sysObjectID)
			. ' AND [type] = ' . $dbi->quote('F');
		$dbi->setQuery($query);
		$foreignKeyInfo = $dbi->loadObjectList('name');


		$rawConstraints = array();

		if (!empty($foreignKeyInfo)) foreach ($foreignKeyInfo as $oKey)
		{
			// Get the columns per key and key information
			$query = 'SELECT * FROM sys.foreign_key_columns WHERE parent_object_id = ' . $dbi->quote($sysObjectID) .
				' AND constraint_object_id = ' . $dbi->q($oKey->object_id);
			$dbi->setQuery($query);
			$allFKColumns = $dbi->loadObjectList();

			// Get referenced table's name
			$refID = $oKey->referenced_object_id;
			$query = 'SELECT name FROM sys.objects WHERE object_id = ' . $dbi->q($refID);
			$dbi->setQuery($query);
			$refObjectName = $dbi->loadResult();

			// Initialise column maps
			$FKcolumnMap = array();

			// Loop through each column and map parent_object_id / parent_column_id,  referenced_object_id / referenced_column_id to column names
			foreach ($allFKColumns as $oColumn)
			{
				$objectIDs = array($oColumn->parent_object_id, $oColumn->referenced_object_id);
				foreach ($objectIDs as $oid)
				{
					if (!array_key_exists($oid, $FKcolumnMap))
					{
						$query = $dbi->getQuery(true)
							->select(array('name', 'column_id'))
							->from('sys.columns')
							->where('object_id = ' . $dbi->q($oid));
						$dbi->setQuery($query);
						$FKcolumnMap[$oid] = $dbi->loadObjectList('column_id');
					}
				}
			}

			$keyName = $oKey->name;
			if (strlen($this->prefix) && substr($this->prefix, -1) == '_')
			{
				$keyName = str_replace($this->prefix, '#__', $keyName);
			}
			$line = 'CONSTRAINT [' . $keyName . '] FOREIGN KEY (';

			$tempCols = array();
			foreach ($allFKColumns as $oColumn)
			{
				$oid = $oColumn->parent_object_id;
				$cid = $oColumn->parent_column_id;
				$tempCols[] = '[' . $FKcolumnMap[$oid][$cid]->name . ']';
			}
			$line .= implode(', ', $tempCols);

			// Add a reference hit
			$this->dependencies[$refObjectName][] = $table_name;
			// Add the dependency to this table's metadata
			$dependencies[] = $refObjectName;

			if ($useabstract)
			{
				$refObjectName = $this->getAbstract($refObjectName);
			}

			$line .= ') REFERENCES [' . $refObjectName . '] (';

			$tempCols = array();
			foreach ($allFKColumns as $oColumn)
			{
				$oid = $oColumn->referenced_object_id;
				$cid = $oColumn->referenced_column_id;
				$tempCols[] = '[' . $FKcolumnMap[$oid][$cid]->name . ']';
			}
			$line .= implode(', ', $tempCols);

			$line .= ') ';

			// Tuck the delete and update actions
			$line .= ' ON DELETE ';
			switch ($oKey->delete_referential_action_desc)
			{
				case 'NO_ACTION';
					$line .= 'NO ACTION';
					break;

				case 'CASCADE';
					$line .= 'CASCADE';
					break;

				case 'SET_NULL';
					$line .= 'SET NULL';
					break;

				case 'SET_DEFAULT';
					$line .= 'SET DEFAULT';
					break;
			}

			$line .= ' ON UPDATE ';
			switch ($oKey->update_referential_action_desc)
			{
				case 'NO_ACTION';
					$line .= 'NO ACTION';
					break;

				case 'CASCADE';
					$line .= 'CASCADE';
					break;

				case 'SET_NULL';
					$line .= 'SET NULL';
					break;

				case 'SET_DEFAULT';
					$line .= 'SET DEFAULT';
					break;
			}

			if ($oKey->is_not_for_replication)
			{
				$line .= ' NOT FOR REPLICATION';
			}

			// add to the $constraints_sql array
			$constraints_sql[] = $line;
		}

		// =====================================================================
		// ==========CONSTRUCT THE TABLE CREATE STATEMENT
		// =====================================================================
		// Create the SQL output
		if ($useabstract)
		{
			$table_sql = "CREATE TABLE [$table_abstract] (";
		}
		else
		{
			$table_sql = "CREATE TABLE [$table_name] (";
		}

		$table_sql .= implode(',', $columns_sql);
		if (count($keys_sql))
		{
			$table_sql .= ',' . implode(',', $keys_sql);
		}
		if (count($constraints_sql))
		{
			$table_sql .= ',' . implode(',', $constraints_sql);
		}
		$table_sql .= ")";

		$table_sql .= ";\n";

		$table_sql .= implode(";\n", $indexes_sql);
		if (count($indexes_sql) >= 1)
		{
			$table_sql .= "\n";
		}

		return $table_sql;
	}

	/**
	 * Reverse engineers the View definitions of this database
	 *
	 * @param   AEAbstractDriver $dbi Database connection to INFORMATION_SCHEMA
	 */
	protected function reverse_engineer_views(&$dbi)
	{
		$schema_name = $this->database;
		$sql = 'SELECT * FROM [INFORMATION_SCHEMA].[VIEWS] WHERE [table_catalog] = ' . $dbi->quote($schema_name);
		$dbi->setQuery($sql);
		$all_views = $dbi->loadObjectList();

		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');

		// If we have filters, make sure the tables pass the filtering
		$filters = AEFactory::getFilters();
		// First pass: populate the table_name_map
		if (!empty($all_views)) foreach ($all_views as $table_object)
		{
			// Extract the table name
			$table_name = $table_object->TABLE_NAME;

			// Filter and convert
			if (substr($table_name, 0, 3) == '#__')
			{
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, __CLASS__ . " :: Table $table_name has a prefix of #__. This would cause restoration errors; table skipped.");
				continue;
			}
			$table_abstract = $this->getAbstract($table_name);
			if (substr($table_abstract, 0, 4) != 'bak_') // Skip backup tables
			{
				// Apply exclusion filters
				if (!$filters->isFiltered($table_abstract, $root, 'dbobject', 'all'))
				{
					AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Adding $table_name (internal name $table_abstract)");
					$this->table_name_map[$table_name] = $table_abstract;
				}
				else
				{
					AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Skipping $table_name (internal name $table_abstract)");
					continue;
				}
			}
			else
			{
				AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Backup view $table_name automatically skipped.");
				continue;
			}
		}

		// Second pass: get the create commands
		if (!empty($all_views)) foreach ($all_views as $table_object)
		{
			// Extract the table name
			$table_name = $table_object->TABLE_NAME;

			if (!in_array($table_name, $this->table_name_map))
			{
				// Skip any views which have been filtered out
				continue;
			}

			$table_abstract = $this->getAbstract($table_name);

			// Still here? The view is added. We now have to store its
			// create command, dependency info and so on
			$new_entry = array(
				'type'         => 'view',
				'dump_records' => false
			);

			$dependencies = array();
			$table_sql = $table_object->VIEW_DEFINITION;
			$old_table_sql = $table_sql;
			foreach ($this->table_name_map as $ref_normal => $ref_abstract)
			{
				if ($pos = strpos($table_sql, ".$ref_normal"))
				{
					// Add a reference hit
					$this->dependencies[$ref_normal][] = $table_name;
					// Add the dependency to this table's metadata
					$dependencies[] = $ref_normal;
					// Do the replacement
					$table_sql = str_replace(".$ref_normal", ".$ref_abstract", $table_sql);
				}
			}

			// On DB only backup we don't want any replacing to take place, do we?
			if (!AEUtilScripting::getScriptingParameter('db.abstractnames', 1))
			{
				$table_sql = $old_table_sql;
			}

			// Replace newlines with spaces
			$table_sql = str_replace("\n", " ", $table_sql) . ";\n";
			$table_sql = str_replace("\r", " ", $table_sql);
			$table_sql = str_replace("\t", " ", $table_sql);

			$new_entry['create'] = $table_sql;
			$new_entry['dependencies'] = $dependencies;

			$this->tables_data[$table_name] = $new_entry;
		}
	}

	/**
	 * Creates a drop query from a CREATE query
	 *
	 * @param $query string The CREATE query to process
	 *
	 * @return string The DROP statement
	 */
	protected function createDrop($query)
	{
		$db = $this->getDB();

		// Initialize
		$dropQuery = '';

		// Parse CREATE TABLE commands
		if (substr($query, 0, 12) == 'CREATE TABLE')
		{
			// Try to get the table name
			$restOfQuery = trim(substr($query, 12, strlen($query) - 12)); // Rest of query, after CREATE TABLE
			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '[')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, ']', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the table name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			unset($restOfQuery);
			// Try to drop the table anyway
			$dropQuery = 'IF OBJECT_ID(' . $db->q($tableName) . ') IS NOT NULL DROP TABLE ' . $db->qn($tableName) . ';';
		}
		// Parse CREATE VIEW commands
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, ' VIEW ') !== false))
		{
			// Try to get the view name
			$view_pos = strpos($query, ' VIEW ');
			$restOfQuery = trim(substr($query, $view_pos + 6)); // Rest of query, after VIEW string
			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '[')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, ']', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the table name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			unset($restOfQuery);
			$dropQuery = 'IF OBJECT_ID(' . $db->q($tableName) . ') IS NOT NULL DROP VIEW ' . $db->qn($tableName) . ';';
		}

		return $dropQuery;
	}

	/**
	 * Post process a quoted value before it's written to the database dump.
	 * So far it's only required for SQL Server which has a problem escaping
	 * newline characters...
	 *
	 * @param   string $value The quoted value to post-process
	 *
	 * @return  string
	 */
	protected function postProcessQuotedValue($value)
	{
		$value = str_replace("\r\n", "' + CHAR(10) + CHAR(13) + N'", $value);
		$value = str_replace("\r", "' + CHAR(13) + N'", $value);
		$value = str_replace("\n", "' + CHAR(10) + N'", $value);

		return $value;
	}

	/**
	 * Returns a preamble for the data dump portion of the SQL backup. This is
	 * used to output commands before the first INSERT INTO statement for a
	 * table when outputting a plain SQL file.
	 *
	 * Practical use: the SET IDENTITY_INSERT sometable ON required for SQL Server
	 *
	 * @param   string  $tableAbstract Abstract name of the table, e.g. #__foobar
	 * @param   string  $tableName     Real name of the table, e.g. abc_foobar
	 * @param   integer $maxRange      Row count on this table
	 *
	 * @return  string   The SQL commands you want to be written in the dump file
	 */
	protected function getDataDumpPreamble($tableAbstract, $tableName, $maxRange)
	{
		if ($maxRange > 0)
		{
			// Do we have an identity column?
			$db = $this->getDB();
			$query = $db->getQuery(true)
				->select('COUNT(*)')
				->from('sys.identity_columns')
				->where("object_id = OBJECT_ID(" . $db->q($tableName) . ")");
			$db->setQuery($query);
			$idColumns = $db->loadResult();

			if ($idColumns < 1)
			{
				return '';
			}

			return "SET IDENTITY_INSERT [$tableName] ON;\n";
		}

		return '';
	}

	/**
	 * Returns an epilogue for the data dump portion of the SQL backup. This is
	 * used to output commands after the last INSERT INTO statement for a
	 * table when outputting a plain SQL file.
	 *
	 * Practical use: the SET IDENTITY_INSERT sometable OFF required for SQL Server
	 *
	 * @param   string  $tableAbstract Abstract name of the table, e.g. #__foobar
	 * @param   string  $tableName     Real name of the table, e.g. abc_foobar
	 * @param   integer $maxRange      Row count on this table
	 *
	 * @return  string   The SQL commands you want to be written in the dump file
	 */
	protected function getDataDumpEpilogue($tableAbstract, $tableName, $maxRange)
	{
		if ($maxRange > 0)
		{
			// Do we have an identity column?
			$db = $this->getDB();
			$query = $db->getQuery(true)
				->select('COUNT(*)')
				->from('sys.identity_columns')
				->where("object_id = OBJECT_ID(" . $db->q($tableName) . ")");
			$db->setQuery($query);
			$idColumns = $db->loadResult();

			if ($idColumns < 1)
			{
				return '';
			}

			return "SET IDENTITY_INSERT [$tableName] OFF;\n";
		}

		return '';
	}

	/**
	 * Return a list of field names for the INSERT INTO statements. This is only
	 * required for Microsoft SQL Server because without it the SET IDENTITY_INSERT
	 * has no effect.
	 *
	 * @param   array   $fieldNames  A list of field names in array format
	 * @param   integer $numOfFields The number of fields we should be dumping
	 *
	 * @return  string
	 */
	protected function getFieldListSQL($fieldNames, $numOfFields)
	{
		if (count($fieldNames) < $numOfFields)
		{
			return '';
		}
		elseif (count($fieldNames) > $numOfFields)
		{
			$fieldNames = array_slice($fieldNames, 0, $numOfFields);
		}

		$db = $this->getDB();

		$temp = array();
		foreach ($fieldNames as $f)
		{
			$temp[] = $db->quoteName($f);
		}

		return '(' . implode(', ', $temp) . ')';
	}
}