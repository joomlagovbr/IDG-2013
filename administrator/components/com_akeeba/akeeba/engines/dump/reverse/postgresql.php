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
 * A PostgreSQL database dump class, using reverse engineering of the
 * INFORMATION_SCHEMA views to deduce the DDL of the database entities.
 *
 * Configuration parameters:
 * host            <string>    PostgreSQL database server host name or IP address
 * port            <string>    PostgreSQL database server port (optional)
 * username        <string>    PostgreSQL user name, for authentication
 * password        <string>    PostgreSQL password, for authentication
 * database        <string>    PostgreSQL database
 * dumpFile        <string>    Absolute path to dump file; must be writable (optional; if left blank it is automatically calculated)
 */
class AEDumpReversePostgresql extends AEDumpNativeMysql
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
		//  Do nothing for PostgreSQL
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
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Reverse engineering Views");
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
		$sql = 'SELECT * FROM information_schema.tables WHERE "table_schema" = \'public\' AND "table_catalog" = ' . $dbi->quote($schema_name) .
			' AND "table_type" = \'BASE TABLE\'';
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
				$table_name = $table_object->table_name;

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

				switch ($table_object->is_insertable_into)
				{
					// Tables which can be inserted to
					case 'YES':
					case '1':
						$new_entry['dump_records'] = true;
						break;

					// Tables which cannot be inserted to
					default:
						$new_entry['dump_records'] = false;
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
		$indexes_sql = array();
		$constraints_sql = array();

		// =====================================================================
		// ========== GENERATE SQL FOR COLUMNS
		// =====================================================================
		// Get columns
		$query = 'SELECT * FROM information_schema.columns WHERE "table_catalog" = ' . $dbi->quote($this->database)
			. ' AND "table_schema" = \'public\''
			. ' AND "table_name" = ' . $dbi->quote($table_name) . ' ORDER BY "ordinal_position" ASC';
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();

		foreach ($allColumns as $oColumn)
		{
			$default = $oColumn->column_default;

			if (!empty($default) && (strstr($default, 'nextval(') !== false) && (strstr($default, '::regclass') !== false) && (strstr($oColumn->data_type, 'int') !== false))
			{
				$line = $dbi->quoteName($oColumn->column_name) . ' serial NOT NULL';
			}
			else
			{
				$line = $dbi->quoteName($oColumn->column_name) . ' ' . $oColumn->data_type . ' ';

				if (!empty($oColumn->character_maximum_length) && !in_array($oColumn->data_type, array('text')))
				{
					$line .= '(' . $oColumn->character_maximum_length . ') ';
				}

				$line .= ($oColumn->is_nullable == 'YES') ? 'NULL ' : 'NOT NULL ';

				if (!empty($default))
				{
					$line .= ' DEFAULT ' . $default;
				}
			}

			$columns_sql[] = $line;
		}

		// =====================================================================
		// ========== GENERATE SQL FOR KEYS AND INDICES
		// =====================================================================
		// Get the primary and unique key names
		$query = 'SELECT "constraint_name", "constraint_type" FROM information_schema.table_constraints WHERE "table_catalog" = ' . $dbi->quote($this->database)
			. ' AND "table_schema" = \'public\''
			. ' AND "table_name" = ' . $dbi->quote($table_name) . ' AND "constraint_type" IN(' .
			$dbi->quote('PRIMARY KEY') . ', ' . $dbi->quote('UNIQUE') . ')';
		$dbi->setQuery($query);
		$specialKeys = $dbi->loadObjectList('constraint_name');

		// Get the columns per key and key information
		$query = 'SELECT * FROM information_schema.constraint_column_usage WHERE "table_catalog" = ' . $dbi->quote($this->database)
			. ' AND "table_schema" = \'public\''
			. ' AND "table_name" = ' . $dbi->quote($table_name);
		$dbi->setQuery($query);
		$allColumns = $dbi->loadObjectList();
		$rawKeys = array();

		if (!empty($allColumns))
		{
			foreach ($allColumns as $oColumn)
			{
				if (!array_key_exists($oColumn->constraint_name, $rawKeys))
				{
					$entry = array(
						'name'    => $oColumn->constraint_name,
						'def'     => 'INDEX',
						'columns' => array(),
					);

					if ($useabstract)
					{
						$entry['name'] = $this->getAbstract($entry['name']);
					}

					if (array_key_exists($oColumn->constraint_name, $specialKeys))
					{
						$entry['def'] = $specialKeys[$oColumn->constraint_name]->constraint_type;
						if ($entry['def'] == 'PRIMARY')
						{
							$entry['def'] = 'PRIMARY KEY';
						}
					}
					$rawKeys[$oColumn->constraint_name] = $entry;
				}

				// Add the column to the index
				$rawKeys[$oColumn->constraint_name]['columns'][] = '"' . $oColumn->column_name . '"';
			}
		}

		// Piece together the keys' SQL statements
		if (!empty($rawKeys))
		{
			foreach ($rawKeys as $keydef)
			{
				$line = ' ' . $keydef['def'] . ' ';
				if (!in_array($keydef['def'], array('PRIMARY KEY', 'UNIQUE')))
				{
					$line = 'CREATE ' . $line;
					$thistable = $useabstract ? $table_abstract : $table_name;
					$line .= "\"{$keydef['name']}\" ON \"$thistable\"";
				}
				$line .= '(';
				$line .= implode(',', $keydef['columns']);
				$line .= ')';

				if (!in_array($keydef['def'], array('PRIMARY KEY', 'UNIQUE')))
				{
					$indexes_sql[] = $line;
				}
				else
				{
					$keys_sql[] = $line;
				}
			}
		}

		// =====================================================================
		// ========== GENERATE SQL FOR CONSTRAINTS
		// =====================================================================
		// Get the foreign key names
		$query =
			'SELECT ccu.table_name as ftable, ccu.column_name as fcolumn, ' .
			'kku.table_name as ltable, kku.column_name as lcolumn,' .
			'ccu.constraint_name AS constraint_name, ' .
			'rc.match_option, rc.update_rule, rc.delete_rule ' .
			'FROM ' .
			'information_schema.constraint_column_usage as ccu ' .
			'INNER JOIN information_schema.key_column_usage AS kku ON ' .
			'(kku.constraint_name = ccu.constraint_name) ' .
			'INNER JOIN information_schema.referential_constraints AS rc ON (rc.constraint_name = ccu.constraint_name) ' .
			'WHERE ccu.table_name <> kku.table_name ' .
			'AND kku.table_name = ' . $dbi->quote($table_name) .
			' AND kku.constraint_catalog = ' . $dbi->quote($this->database) .
			' AND kku.constraint_schema = \'public\'';
		$dbi->setQuery($query);
		$allFKColumns = $dbi->loadObjectList();
		$rawConstraints = array();

		if (!empty($allFKColumns))
		{
			foreach ($allFKColumns as $oColumn)
			{
				if (!array_key_exists($oColumn->constraint_name, $rawConstraints))
				{
					$entry = array(
						'name'     => $oColumn->constraint_name,
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

					$entry['update'] = $oColumn->update_rule;
					$entry['delete'] = $oColumn->delete_rule;

					$reftable = $oColumn->ftable;

					// Add a reference hit
					$this->dependencies[$reftable][] = $table_name;
					// Add the dependency to this table's metadata
					$dependencies[] = $reftable;

					if ($useabstract)
					{
						$reftable = $this->getAbstract($reftable);
					}
					$entry['reftable'] = $reftable;

					$rawConstraints[$oColumn->constraint_name] = $entry;
				}

				$rawConstraints[$oColumn->constraint_name]['cols'][] = '"' . $oColumn->lcolumn . '"';
				$rawConstraints[$oColumn->constraint_name]['refcols'][] = '"' . $oColumn->fcolumn . '"';
			}
		}

		// Piece together the constraints' SQL statements
		if (!empty($rawConstraints))
		{
			foreach ($rawConstraints as $keydef)
			{
				$line = ' CONSTRAINT ';
				if ($keydef['name'])
				{
					$line .= "\"{$keydef['name']}\" ";
				}
				$line .= 'FOREIGN KEY (';
				$line .= implode(',', $keydef['cols']);
				$line .= ') REFERENCES "' . $keydef['reftable'] . '" (';
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
		}


		// =====================================================================
		// ========== CONSTRUCT THE TABLE CREATE STATEMENT
		// =====================================================================
		// Create the SQL output
		if ($useabstract)
		{
			$table_sql = "CREATE TABLE \"$table_abstract\" (";
		}
		else
		{
			$table_sql = "CREATE TABLE \"$table_name\" (";
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

		if (!empty($indexes_sql))
		{
			foreach ($indexes_sql as $index)
			{
				$table_sql .= $index . ";\n";
			}
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
		$sql = 'SELECT * FROM information_schema.views WHERE "table_catalog" = ' . $dbi->quote($schema_name) .
			' AND "table_schema" = \'public\'';
		$dbi->setQuery($sql);
		$all_views = $dbi->loadObjectList();

		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');

		// If we have filters, make sure the tables pass the filtering
		$filters = AEFactory::getFilters();
		// First pass: populate the table_name_map
		if (!empty($all_views))
		{
			foreach ($all_views as $table_object)
			{
				// Extract the table name
				$table_name = $table_object->table_name;

				// Filter and convert
				if (substr($table_name, 0, 3) == '#__')
				{
					AEUtilLogger::WriteLog(_AE_LOG_WARNING, __CLASS__ . " :: View $table_name has a prefix of #__. This would cause restoration errors; table skipped.");
					continue;
				}
				$table_abstract = $this->getAbstract($table_name);
				if (substr($table_abstract, 0, 4) != 'bak_') // Skip backup tables
				{
					// Apply exclusion filters
					if (!$filters->isFiltered($table_abstract, $root, 'dbobject', 'all'))
					{
						AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Adding view $table_name (internal name $table_abstract)");
						$this->table_name_map[$table_name] = $table_abstract;
					}
					else
					{
						AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Skipping view $table_name (internal name $table_abstract)");
						continue;
					}
				}
				else
				{
					AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Backup view $table_name automatically skipped.");
					continue;
				}
			}
		}

		// Second pass: get the create commands
		if (!empty($all_views))
		{
			foreach ($all_views as $table_object)
			{
				// Extract the table name
				$table_name = $table_object->table_name;

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
				$table_sql = 'CREATE OR REPLACE VIEW "' . $table_name . '" AS ' . $table_object->view_definition;
				$old_table_sql = $table_sql;
				foreach ($this->table_name_map as $ref_normal => $ref_abstract)
				{
					if ($pos = strpos($table_sql, "$ref_normal"))
					{
						// Add a reference hit
						$this->dependencies[$ref_normal][] = $table_name;
						// Add the dependency to this table's metadata
						$dependencies[] = $ref_normal;
						// Do the replacement
						$table_sql = str_replace("$ref_normal", "$ref_abstract", $table_sql);
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
			// Is there a double quote?
			if (substr($restOfQuery, 0, 1) == '"')
			{
				// There is... Good, we'll just find the matching double quote
				$pos = strpos($restOfQuery, '"', 1);
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
			$dropQuery = 'DROP TABLE IF EXISTS ' . $db->qn($tableName) . ';';
		}
		// Parse CREATE VIEW commands
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, ' VIEW ') !== false))
		{
			// Try to get the view name
			$view_pos = strpos($query, ' VIEW ');
			$restOfQuery = trim(substr($query, $view_pos + 6)); // Rest of query, after VIEW string
			// Is there a double quote?
			if (substr($restOfQuery, 0, 1) == '"')
			{
				// There is... Good, we'll just find the matching double quote
				$pos = strpos($restOfQuery, '"', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the table name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			unset($restOfQuery);
			$dropQuery = 'DROP TABLE IF EXISTS ' . $db->qn($tableName) . ';';
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
		if ((strstr($value, "\n") === false) && (strstr($value, "\r") === false))
		{
			return $value;
		}
		else
		{
			// Mark this string as "C-style Escaped".
			// See http://www.postgresql.org/docs/9.2/interactive/sql-syntax-lexical.html#SQL-SYNTAX-STRINGS-ESCAPE
			$temp = 'E' . $value;
			// Escape special characters. Note that the escaped character looks like \\n (two slashes).
			// Single slashes must also be escaped.
			$temp = str_replace("\\", '\\\\', $temp);
			$temp = str_replace("\n", '\\n', $temp);
			$temp = str_replace("\r", '\\r', $temp);
			$temp = str_replace("\f", '\\f', $temp);
			$temp = str_replace("\t", '\\t', $temp);

			return $temp;
		}
	}
}