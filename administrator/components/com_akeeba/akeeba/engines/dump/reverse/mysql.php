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
 * A MySQL database dump class, using reverse engineering of the
 * INFORMATION_SCHEMA views to deduce the DDL of the database entities.
 *
 * Configuration parameters:
 * host            <string>    MySQL database server host name or IP address
 * port            <string>    MySQL database server port (optional)
 * username        <string>    MySQL user name, for authentication
 * password        <string>    MySQL password, for authentication
 * database        <string>    MySQL database
 * dumpFile        <string>    Absolute path to dump file; must be writable (optional; if left blank it is automatically calculated)
 */
class AEDumpReverseMysql extends AEDumpNativeMysql
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

		// Clone the db object and point it to information_schema
		$dbi = clone $this->getDB();
		if (!$dbi->select('information_schema'))
		{
			AEUtilLogger::WriteLog(_AE_LOG_ERROR, __CLASS__ . " :: Could not connect to the INFORMATION_SCHEMA database");
		}

		// Get the list of all database tables and views
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Reverse engineering Tables");
		$this->reverse_engineer_tables($dbi);
		$this->reverse_engineer_views($dbi);

		// If we have MySQL > 5.0 add the list of stored procedures, stored functions
		// and triggers, but only if user has allows that and the target compatibility is
		// not MySQL 4! Also, if dependency tracking is disabled, we won't dump triggers,
		// functions and procedures.
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

		$dbi->select($this->database);
	}

	/**
	 * Reverse engineers the Table definitions of this database
	 *
	 * @param   AEAbstractDriver $dbi Database connection to INFORMATION_SCHEMA
	 */
	protected function reverse_engineer_tables(&$dbi)
	{
		$schema_name = $this->database;
		$sql = 'SELECT * FROM `tables` WHERE `table_schema` = ' . $dbi->quote($schema_name);
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
				$table_name = $table_object->TABLE_NAME;

				// Skip system objects
				if ($table_object->TABLE_TYPE != 'BASE TABLE')
				{
					continue;
				}

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

				switch ($table_object->ENGINE)
				{
					// Merge tables
					case 'MRG_MYISAM':
						$new_entry['type'] = 'merge';
						$new_entry['dump_records'] = false;
						break;

					// Tables whose data we do not back up (memory, federated and can-have-no-data tables)
					case 'MEMORY':
					case 'EXAMPLE':
					case 'BLACKHOLE':
					case 'FEDERATED':
						$new_entry['dump_records'] = false;
						break;

					// Normal tables
					default:
						break;
				}

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
	 * @param   stdClass         $table_object   The TABLES object for this table
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

		// =====================================================================
		// ========== GENERATE SQL FOR COLUMNS
		// =====================================================================

		// Get columns
		$query = 'SELECT * FROM `columns` WHERE `table_schema` = ' . $dbi->quote($this->database)
			. ' AND `table_name` = ' . $dbi->quote($table_name) . ' ORDER BY `ordinal_position` ASC';
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();
		foreach ($allColumns as $oColumn)
		{
			$line = $dbi->quoteName($oColumn->COLUMN_NAME) . ' ' . $oColumn->COLUMN_TYPE . ' ';
			$line .= ($oColumn->IS_NULLABLE == 'YES') ? 'NULL ' : 'NOT NULL ';
			if (!in_array($oColumn->EXTRA, array('CURRENT_TIMESTAMP')))
			{
				$line .= ($oColumn->EXTRA == '') ? '' : ($oColumn->EXTRA . ' ');
			}
			if (in_array($oColumn->COLUMN_DEFAULT, array('CURRENT_TIMESTAMP')))
			{
				$line .= ' DEFAULT ' . $oColumn->COLUMN_DEFAULT;
			}
			else
			{
				$line .= ($oColumn->COLUMN_DEFAULT == '') ? '' : (' DEFAULT ' . $dbi->quote($oColumn->COLUMN_DEFAULT) . ' ');
			}
			$line .= ($oColumn->COLUMN_COMMENT == '') ? '' : (' COMMENT ' . $dbi->quote($oColumn->COLUMN_COMMENT));
			$columns_sql[] = $line;
		}

		// =====================================================================
		// ========== GENERATE SQL FOR KEYS AND INDICES
		// =====================================================================

		// Get the primary and unique key names
		$query = 'SELECT `constraint_name`, `constraint_type` FROM `table_constraints` WHERE `table_schema` = ' . $dbi->quote($this->database)
			. ' AND `table_name` = ' . $dbi->quote($table_name) . ' AND `constraint_type` IN(' .
			$dbi->quote('PRIMARY KEY') . ', ' . $dbi->quote('UNIQUE') . ')';
		$dbi->setQuery($query);
		$specialKeys = $dbi->loadObjectList('constraint_name');

		// Get the columns per key and key information
		$query = 'SELECT * FROM `statistics` WHERE `table_schema` = ' . $dbi->quote($this->database)
			. ' AND `table_name` = ' . $dbi->quote($table_name) . ' ORDER BY `INDEX_NAME` ASC, SEQ_IN_INDEX ASC';
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();
		$rawKeys = array();
		if (!empty($allColumns)) foreach ($allColumns as $oColumn)
		{
			if (!array_key_exists($oColumn->INDEX_NAME, $rawKeys))
			{
				$entry = array(
					'name'    => $oColumn->INDEX_NAME,
					'def'     => 'KEY',
					'columns' => array(),
					'type'    => 'BTREE',
				);
				if (array_key_exists($oColumn->INDEX_NAME, $specialKeys))
				{
					$entry['def'] = $specialKeys[$oColumn->INDEX_NAME]->constraint_type;
					if ($entry['def'] == 'UNIQUE')
					{
						$entry['def'] = 'UNIQUE KEY';
					}
					elseif ($entry['def'] == 'PRIMARY')
					{
						$entry['def'] = 'PRIMARY KEY';
					}
				}
				$rawKeys[$oColumn->INDEX_NAME] = $entry;
			}

			// This is the optional key length for each column
			$subpart = '';
			if ($oColumn->SUB_PART)
			{
				$subpart = '(' . $oColumn->SUB_PART . ')';
			}
			// Add the column to the index
			$rawKeys[$oColumn->INDEX_NAME]['columns'][] = '`' . $oColumn->COLUMN_NAME . '`' . $subpart;
			// Setup the index type
			$rawKeys[$oColumn->INDEX_NAME]['type'] = $oColumn->INDEX_TYPE;
		}

		// Piece together the keys' SQL statements
		if (!empty($rawKeys)) foreach ($rawKeys as $keydef)
		{
			$line = ' ' . $keydef['def'] . ' ';
			if ($keydef['type'] == 'FULLTEXT')
			{
				$line = ' ' . $keydef['type'] . $line;
				$keydef['type'] = '';
			}
			if ($keydef['def'] != 'PRIMARY KEY')
			{
				$line .= "`{$keydef['name']}` ";
			}
			$line .= '(';
			$line .= implode(',', $keydef['columns']);
			$line .= ')';

			if (!empty($keydef['type']) && ($keydef['def'] != 'PRIMARY KEY'))
			{
				$line .= ' USING ' . $keydef['type'];
			}

			$keys_sql[] = $line;
		}

		// =====================================================================
		// ========== GENERATE SQL FOR CONSTRAINTS
		// =====================================================================
		// Get the foreign key names
		$query = 'SELECT * FROM `referential_constraints` WHERE `constraint_schema` = ' . $dbi->quote($this->database)
			. ' AND `table_name` = ' . $dbi->quote($table_name);
		$dbi->setQuery($query);
		$foreignKeyInfo = $dbi->loadObjectList('CONSTRAINT_NAME');

		// Get the columns per key and key information
		$query = 'SELECT * FROM `key_column_usage` WHERE `constraint_schema` = ' . $dbi->quote($this->database)
			. ' AND `table_name` = ' . $dbi->quote($table_name) . ' AND `referenced_table_name` IS NOT NULL';
		$dbi->setQuery($query);
		$allFKColumns = $dbi->loadObjectList();
		$rawConstraints = array();

		if (!empty($allFKColumns)) foreach ($allFKColumns as $oColumn)
		{
			if (!array_key_exists($oColumn->CONSTRAINT_NAME, $rawConstraints))
			{
				$entry = array(
					'name'     => $oColumn->CONSTRAINT_NAME,
					'cols'     => array(),
					'refcols'  => array(),
					'reftable' => '',
					'update'   => '',
					'delete'   => '',
				);
				if ($useabstract)
				{
					$entry['name'] = $this->getAbstract($entry['name']);
				}
				if (array_key_exists($oColumn->CONSTRAINT_NAME, $foreignKeyInfo))
				{
					$entry['update'] = $foreignKeyInfo[$oColumn->CONSTRAINT_NAME]->UPDATE_RULE;
					$entry['delete'] = $foreignKeyInfo[$oColumn->CONSTRAINT_NAME]->DELETE_RULE;

					$reftable = $foreignKeyInfo[$oColumn->CONSTRAINT_NAME]->REFERENCED_TABLE_NAME;
					// Add a reference hit
					$this->dependencies[$reftable][] = $table_name;
					// Add the dependency to this table's metadata
					$dependencies[] = $reftable;

					if ($useabstract)
					{
						$reftable = $this->getAbstract($reftable);
					}
					$entry['reftable'] = $reftable;
				}
				$rawConstraints[$oColumn->CONSTRAINT_NAME] = $entry;
			}

			$rawConstraints[$oColumn->CONSTRAINT_NAME]['cols'][] = '`' . $oColumn->COLUMN_NAME . '`';
			$rawConstraints[$oColumn->CONSTRAINT_NAME]['refcols'][] = '`' . $oColumn->REFERENCED_COLUMN_NAME . '`';
		}

		// Piece together the constraints' SQL statements
		if (!empty($rawConstraints)) foreach ($rawConstraints as $keydef)
		{
			$line = ' CONSTRAINT ';
			if ($keydef['name'])
			{
				$line .= "`{$keydef['name']}` ";
			}
			$line .= 'FOREIGN KEY (';
			$line .= implode(',', $keydef['cols']);
			$line .= ') REFERENCES `' . $keydef['reftable'] . '` (';
			$line .= implode(',', $keydef['refcols']);
			$line .= ')';
			if ($keydef['delete'])
			{
				$line .= ' ON DELETE ' . $keydef['delete'];
			}
			if ($keydef['update'])
			{
				$line .= ' ON UPDATE ' . $keydef['update'];
			}

			$constraints_sql[] = $line;
		}


		// =====================================================================
		// ========== CONSTRUCT THE TABLE CREATE STATEMENT
		// =====================================================================

		// Create the SQL output
		if ($useabstract)
		{
			$table_sql = "CREATE TABLE `$table_abstract` (";
		}
		else
		{
			$table_sql = "CREATE TABLE `$table_name` (";
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

		// Engine and stuff must also be exported here
		if ($table_object->ENGINE)
		{
			$table_sql .= ' ENGINE=' . $table_object->ENGINE;
		}
		if ($table_object->TABLE_COLLATION)
		{
			$table_sql .= ' DEFAULT COLLATE ' . $table_object->TABLE_COLLATION;
		}

		$table_sql .= ";\n";

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
		$sql = 'SELECT * FROM `views` WHERE `table_schema` = ' . $dbi->quote($schema_name);
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
				AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Backup table $table_name automatically skipped.");
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
			$table_sql = 'CREATE OR REPLACE VIEW `' . $table_name . '` AS ' . $table_object->VIEW_DEFINITION;
			$old_table_sql = $table_sql;
			foreach ($this->table_name_map as $ref_normal => $ref_abstract)
			{
				if ($pos = strpos($table_sql, "`$ref_normal`"))
				{
					// Add a reference hit
					$this->dependencies[$ref_normal][] = $table_name;
					// Add the dependency to this table's metadata
					$dependencies[] = $ref_normal;
					// Do the replacement
					$table_sql = str_replace("`$ref_normal`", "`$ref_abstract`", $table_sql);
				}
			}

			// On DB only backup we don't want any replacing to take place, do we?
			if (!AEUtilScripting::getScriptingParameter('db.abstractnames', 1)) $table_sql = $old_table_sql;

			// Replace newlines with spaces
			$table_sql = str_replace("\n", " ", $table_sql) . ";\n";
			$table_sql = str_replace("\r", " ", $table_sql);
			$table_sql = str_replace("\t", " ", $table_sql);

			$new_entry['create'] = $table_sql;
			$new_entry['dependencies'] = $dependencies;

			$this->tables_data[$table_name] = $new_entry;
		}
	}
}