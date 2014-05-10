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
 * A generic MySQL database dump class, using Joomla!'s JDatabase class for handling the connection.
 * Now supports views; merge, in-memory, federated, blackhole, etc tables
 * Configuration parameters:
 * host            <string>    MySQL database server host name or IP address
 * port            <string>    MySQL database server port (optional)
 * username        <string>    MySQL user name, for authentication
 * password        <string>    MySQL password, for authentication
 * database        <string>    MySQL database
 * dumpFile        <string>    Absolute path to dump file; must be writable (optional; if left blank it is automatically calculated)
 */
class AEDumpNativeMysql extends AEAbstractDump
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

	/**
	 * Applies the SQL compatibility setting
	 */
	protected function enforceSQLCompatibility()
	{
		$configuration = AEFactory::getConfiguration();
		$db = $this->getDB();
		if ($this->getError())
		{
			return;
		}

		$sql = "SET sql_mode='HIGH_NOT_PRECEDENCE'";
		$db->setQuery($sql);
		$db->query();
		// Try to enforce SQL_BIG_SELECTS option
		try
		{
			$dbVersion = $db->getVersion();
			if (version_compare($dbVersion, '5.6', 'lt'))
			{
				// Pre-5.6 MySQL
				$db->setQuery('SET OPTION SQL_BIG_SELECTS=1');
			}
			else
			{
				// MySQL 5.6 or later
				$db->setQuery('SET SQL_BIG_SELECTS=1');
			}
			$db->query();
		}
		catch (Exception $e)
		{
			// Do nothing; some versions of MySQL don't allow you to use the BIG_SELECTS option.
		}

		$db->resetErrors();
	}

	/**
	 * Performs one more step of dumping database data
	 * @return type
	 */
	protected function stepDatabaseDump()
	{
		// Initialize local variables
		$db = $this->getDB();
		if ($this->getError())
		{
			return;
		}

		if (!is_object($db) || ($db === false))
		{
			$this->setError(__CLASS__ . '::_run() Could not connect to database?!');

			return;
		}

		$outData = ''; // Used for outputting INSERT INTO commands

		$this->enforceSQLCompatibility(); // Apply MySQL compatibility option

		if ($this->getError())
		{
			return;
		}

		// Touch SQL dump file
		$nada = "";
		$this->writeline($nada);

		// Get this table's information
		$tableName = $this->nextTable;
		$this->setStep($tableName);
		$this->setSubstep('');
		$tableAbstract = trim($this->table_name_map[$tableName]);
		$dump_records = $this->tables_data[$tableName]['dump_records'];

		// If it is the first run, find number of rows and get the CREATE TABLE command
		if ($this->nextRange == 0)
		{
			if ($this->getError())
			{
				return;
			}

			$outCreate = '';

			if (is_array($this->tables_data[$tableName]))
			{
				if (array_key_exists('create', $this->tables_data[$tableName]))
				{
					$outCreate = $this->tables_data[$tableName]['create'];
				}
			}

			if (empty($outCreate) && !empty($tableName))
			{
				// The CREATE command wasn't cached. Time to create it. The $type and $dependencies
				// variables will be thrown away.
				$type = 'table';
				$dependencies = array();
				$outCreate = $this->get_create($tableAbstract, $tableName, $type, $dependencies);
			}

			// Create drop statements if required (the key is defined by the scripting engine)
			$configuration = AEFactory::getConfiguration();
			if (AEUtilScripting::getScriptingParameter('db.dropstatements', 0))
			{
				if (array_key_exists('create', $this->tables_data[$tableName]))
				{
					// @todo This looks cheesy...
					$dropStatement = $this->createDrop($this->tables_data[$tableName]['create']);
				}
				else
				{
					$type = 'table';
					$createStatement = $this->get_create($tableAbstract, $tableName, $type, $dependencies);
					$dropStatement = $this->createDrop($createStatement);
				}

				if (!empty($dropStatement))
				{
					$dropStatement .= "\n";

					if (!$this->writeDump($dropStatement))
					{
						return;
					}
				}
			}

			// Write the CREATE command after any DROP command which might be necessary.
			if (!$this->writeDump($outCreate))
			{
				return;
			}

			if ($dump_records)
			{
				// We are dumping data from a table, get the row count
				$this->getRowCount($tableAbstract);
			}
			else
			{
				// We should not dump any data
				AEUtilLogger::WriteLog(_AE_LOG_INFO, "Skipping dumping data of " . $tableAbstract);
				$this->maxRange = 0;
				$this->nextRange = 1;
				$outData = '';
				$numRows = 0;
			}

			// Output any data preamble commands, e.g. SET IDENTITY_INSERT for SQL Server
			if ($dump_records && AEUtilScripting::getScriptingParameter('db.dropstatements', 0))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Writing data dump preamble for " . $tableAbstract);
				$preamble = $this->getDataDumpPreamble($tableAbstract, $tableName, $this->maxRange);
				if (!empty($preamble))
				{
					if (!$this->writeDump($preamble))
					{
						return;
					}
				}
			}
		}

		// Check if we have more work to do on this table
		$configuration = AEFactory::getConfiguration();
		$batchsize = intval($configuration->get('engine.dump.common.batchsize', 1000));
		if ($batchsize <= 0)
		{
			$batchsize = 1000;
		}
		if (($this->nextRange < $this->maxRange))
		{
			$timer = AEFactory::getTimer();

			// Get the number of rows left to dump from the current table
			$sql = $db->getQuery(true)
				->select('*')
				->from($db->nameQuote($tableAbstract));

			if ($this->nextRange == 0)
			{
				// First run, get a cursor to all records
				$db->setQuery($sql, 0, $batchsize);
				AEUtilLogger::WriteLog(_AE_LOG_INFO, "Beginning dump of " . $tableAbstract);
			}
			else
			{
				// Subsequent runs, get a cursor to the rest of the records
				$db->setQuery($sql, $this->nextRange, $batchsize);
				$this->setSubstep($this->nextRange . ' / ' . $this->maxRange);
				AEUtilLogger::WriteLog(_AE_LOG_INFO, "Continuing dump of " . $tableAbstract . " from record #{$this->nextRange}");
			}

			$this->query = '';
			$numRows = 0;
			$use_abstract = AEUtilScripting::getScriptingParameter('db.abstractnames', 1);

			$filters = AEFactory::getFilters();
			$mustFilter = $filters->hasFilterType('dbobject', 'children');

			try
			{
				$cursor = $db->query();
			}
			catch (Exception $exc)
			{
				$cursor = null;
			}

			while (is_array($myRow = $db->fetchAssoc()) && ($numRows < ($this->maxRange - $this->nextRange)))
			{
				$this->createNewPartIfRequired();
				$numRows++;
				$numOfFields = count($myRow);

				// On MS SQL Server there's always a RowNumber pseudocolumn added at the end, screwing up the backup (GRRRR!)
				if ($db->getDriverType() == 'mssql')
				{
					$numOfFields--;
				}

				// If row-level filtering is enabled, please run the filtering
				if ($mustFilter)
				{
					$isFiltered = $filters->isFiltered(
						array(
							 'table' => $tableAbstract,
							 'row'   => $myRow
						),
						$configuration->get('volatile.database.root', '[SITEDB]'),
						'dbobject',
						'children'
					);

					if ($isFiltered)
					{
						continue;
					}
				}

				if (
					(!$this->extendedInserts) || // Add header on simple INSERTs, or...
					($this->extendedInserts && empty($this->query)) //...on extended INSERTs if there are no other data, yet
				)
				{
					$newQuery = true;
					$fieldList = $this->getFieldListSQL(array_keys($myRow), $numOfFields);

					if ($numOfFields > 0)
					{
						$this->query = "INSERT INTO " . $db->nameQuote((!$use_abstract ? $tableName : $tableAbstract)) . " $fieldList VALUES ";
					}
				}
				else
				{
					// On other cases, just mark that we should add a comma and start a new VALUES entry
					$newQuery = false;
				}

				$outData = '(';

				// Step through each of the row's values
				$fieldID = 0;

				// Used in running backup fix
				$isCurrentBackupEntry = false;

				// Fix 1.2a - NULL values were being skipped
				if ($numOfFields > 0)
				{
					foreach ($myRow as $value)
					{
						// The ID of the field, used to determine placement of commas
						$fieldID++;

						if ($fieldID > $numOfFields)
						{
							// This is required for SQL Server backups, do NOT remove!
							continue;
						}

						// Fix 2.0: Mark currently running backup as successful in the DB snapshot
						if ($tableAbstract == '#__ak_stats')
						{
							if ($fieldID == 1)
							{
								// Compare the ID to the currently running
								$statistics = AEFactory::getStatistics();
								$isCurrentBackupEntry = ($value == $statistics->getId());
							}
							elseif ($fieldID == 6)
							{
								// Treat the status field
								$value = $isCurrentBackupEntry ? 'complete' : $value;
							}
						}

						// Post-process the value
						if (is_null($value))
						{
							$outData .= "NULL"; // Cope with null values
						}
						else
						{
							// Accommodate for runtime magic quotes
							$value = @get_magic_quotes_runtime() ? stripslashes($value) : $value;
							$value = $db->Quote($value);
							if ($this->postProcessValues)
							{
								$value = $this->postProcessQuotedValue($value);
							}
							$outData .= $value;
						}

						if ($fieldID < $numOfFields)
						{
							$outData .= ', ';
						}
					}
				}

				$outData .= ')';

				if ($numOfFields)
				{
					// If it's an existing query and we have extended inserts
					if ($this->extendedInserts && !$newQuery)
					{
						// Check the existing query size
						$query_length = strlen($this->query);
						$data_length = strlen($outData);

						if (($query_length + $data_length) > $this->packetSize)
						{
							// We are about to exceed the packet size. Write the data so far.
							$this->query .= ";\n";

							if (!$this->writeDump($this->query))
							{
								return;
							}

							// Then, start a new query
							$this->query = '';
							$this->query = "INSERT INTO " . $db->nameQuote((!$use_abstract ? $tableName : $tableAbstract)) . " VALUES ";
							$this->query .= $outData;
						}
						else
						{
							// We have room for more data. Append $outData to the query.
							$this->query .= ', ';
							$this->query .= $outData;
						}
					}
					// If it's a brand new insert statement in an extended INSERTs set
					elseif ($this->extendedInserts && $newQuery)
					{
						// Append the data to the INSERT statement
						$this->query .= $outData;
						// Let's see the size of the dumped data...
						$query_length = strlen($this->query);

						if ($query_length >= $this->packetSize)
						{
							// This was a BIG query. Write the data to disk.
							$this->query .= ";\n";

							if (!$this->writeDump($this->query))
							{
								return;
							}

							// Then, start a new query
							$this->query = '';
						}
					}
					// It's a normal (not extended) INSERT statement
					else
					{
						// Append the data to the INSERT statement
						$this->query .= $outData;
						// Write the data to disk.
						$this->query .= ";\n";

						if (!$this->writeDump($this->query))
						{
							return;
						}

						// Then, start a new query
						$this->query = '';
					}
				}
				$outData = '';

				unset($myRow);

				// Check for imminent timeout
				if ($timer->getTimeLeft() <= 0)
				{
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Breaking dump of $tableAbstract after $numRows rows; will continue on next step");

					break;
				}
			}

			$db->freeResult($cursor);

			// Advance the _nextRange pointer
			$this->nextRange += ($numRows != 0) ? $numRows : 1;

			$this->setStep($tableName);
			$this->setSubstep($this->nextRange . ' / ' . $this->maxRange);
		}

		// Finalize any pending query
		// WARNING! If we do not do that now, the query will be emptied in the next operation and all
		// accumulated data will go away...
		if (!empty($this->query))
		{
			$this->query .= ";\n";

			if (!$this->writeDump($this->query))
			{
				return;
			}

			$this->query = '';
		}

		// Check for end of table dump (so that it happens inside the same operation)
		if (!($this->nextRange < $this->maxRange))
		{
			// Tell the user we are done with the table
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Done dumping " . $tableAbstract);

			// Output any data preamble commands, e.g. SET IDENTITY_INSERT for SQL Server
			if ($dump_records && AEUtilScripting::getScriptingParameter('db.dropstatements', 0))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Writing data dump epilogue for " . $tableAbstract);
				$epilogue = $this->getDataDumpEpilogue($tableAbstract, $tableName, $this->maxRange);

				if (!empty($epilogue))
				{
					if (!$this->writeDump($epilogue))
					{
						return;
					}
				}
			}

			if (count($this->tables) == 0)
			{
				// We have finished dumping the database!
				AEUtilLogger::WriteLog(_AE_LOG_INFO, "End of database detected; flushing the dump buffers...");
				$null = null;
				$this->writeDump($null);
				AEUtilLogger::WriteLog(_AE_LOG_INFO, "Database has been successfully dumped to SQL file(s)");
				$this->setState('postrun');
				$this->setStep('');
				$this->setSubstep('');
				$this->nextTable = '';
				$this->nextRange = 0;
			}
			elseif (count($this->tables) != 0)
			{
				// Switch tables
				$this->nextTable = array_shift($this->tables);
				$this->nextRange = 0;
				$this->setStep($this->nextTable);
				$this->setSubstep('');
			}
		}
	}

	/**
	 * Gets the row count for table $tableAbstract. Also updates the $this->maxRange variable.
	 *
	 * @param string $tableAbstract The abstract name of the table (works with canonical names too, though)
	 *
	 * @return integer Row count of the table
	 */
	private function getRowCount($tableAbstract)
	{
		$db = $this->getDB();

		if ($this->getError())
		{
			return;
		}

		$sql = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->nameQuote($tableAbstract));

		$db->setQuery($sql);
		$this->maxRange = $db->loadResult();
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Rows on " . $tableAbstract . " : " . $this->maxRange);

		return $this->maxRange;
	}

// =============================================================================
// Dependency processing - the Twilight Zone starts here
// =============================================================================

	/**
	 * Scans the database for tables to be backed up and sorts them according to
	 * their dependencies on one another.
	 */
	protected function getTablesToBackup()
	{
		// Joomla! always sets MySQL 4.0 compatibility? Most likely, we have to
		// work around it.
		$this->enforceSQLCompatibility();

		$configuration = AEFactory::getConfiguration();
		$notracking = $configuration->get('engine.dump.native.nodependencies', 0);

		// First, get a map of table names <--> abstract names
		$this->get_tables_mapping();

		if ($this->getError())
		{
			return;
		}

		if ($notracking)
		{
			// Do not process table & view dependencies
			$this->get_tables_data_without_dependencies();
			if ($this->getError())
			{
				return;
			}
		}
		// Process table & view dependencies (default)
		else
		{
			// Find the type and CREATE command of each table/view in the database
			$this->get_tables_data();

			if ($this->getError())
			{
				return;
			}

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

	/**
	 * Generates a mapping between table names as they're stored in the database
	 * and their abstract representation.
	 */
	private function get_tables_mapping()
	{
		// Get a database connection
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Finding tables to include in the backup set");
		$db = $this->getDB();

		if ($this->getError())
		{
			return;
		}

		// Reset internal tables
		$this->table_name_map = array();

		// Get the list of all database tables
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$all_tables = $db->loadResultArray();

		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');

		// If we have filters, make sure the tables pass the filtering
		$filters = AEFactory::getFilters();

		foreach ($all_tables as $table_name)
		{
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
				}
			}
			else
			{
				AEUtilLogger::WriteLog(_AE_LOG_INFO, __CLASS__ . " :: Backup table $table_name automatically skipped.");
			}
		}

		// If we have MySQL > 5.0 add the list of stored procedures, stored functions
		// and triggers, but only if user has allows that and the target compatibility is
		// not MySQL 4! Also, if dependency tracking is disabled, we won't dump triggers,
		// functions and procedures.
		$enable_entities = $registry->get('engine.dump.native.advanced_entitites', true);
		$notracking = $registry->get('engine.dump.native.nodependencies', 0);

		if ($enable_entities && ($notracking == 0))
		{
			// Cache the database name if this is the main site's database

			// 1. Stored procedures
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Listing stored PROCEDUREs");
			$sql = "SHOW PROCEDURE STATUS WHERE `Db`=" . $db->Quote($this->database);
			$db->setQuery($sql);
			$all_entries = $db->loadResultArray(1);

			// If we have filters, make sure the tables pass the filtering
			if (is_array($all_entries))
			{
				if (count($all_entries))
				{
					foreach ($all_entries as $entity_name)
					{
						$entity_abstract = $this->getAbstract($entity_name);

						if (!(substr($entity_abstract, 0, 4) == 'bak_')) // Skip backup entities
						{
							if (!$filters->isFiltered($entity_abstract, $root, 'dbobject', 'all'))
							{
								$this->table_name_map[$entity_name] = $entity_abstract;
							}
						}
					}
				}
			}

			// 2. Stored functions
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Listing stored FUNCTIONs");
			$sql = "SHOW FUNCTION STATUS WHERE `Db`=" . $db->Quote($this->database);
			$db->setQuery($sql);
			$all_entries = $db->loadResultArray(1);

			// If we have filters, make sure the tables pass the filtering
			if (is_array($all_entries))
			{
				if (count($all_entries))
				{
					foreach ($all_entries as $entity_name)
					{
						$entity_abstract = $this->getAbstract($entity_name);

						if (!(substr($entity_abstract, 0, 4) == 'bak_')) // Skip backup entities
						{
							// Apply exclusion filters if set
							if (!$filters->isFiltered($entity_abstract, $root, 'dbobject', 'all'))
							{
								$this->table_name_map[$entity_name] = $entity_abstract;
							}
						}
					}
				}
			}

			// 3. Triggers
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Listing stored TRIGGERs");
			$sql = "SHOW TRIGGERS";
			$db->setQuery($sql);
			$all_entries = $db->loadResultArray();

			// If we have filters, make sure the tables pass the filtering
			if (is_array($all_entries))
			{
				if (count($all_entries))
				{
					foreach ($all_entries as $entity_name)
					{
						$entity_abstract = $this->getAbstract($entity_name);

						if (!(substr($entity_abstract, 0, 4) == 'bak_')) // Skip backup entities
						{
							// Apply exclusion filters if set
							if (!$filters->isFiltered($entity_abstract, $root, 'dbobject', 'all'))
							{
								$this->table_name_map[$entity_name] = $entity_abstract;
							}
						}
					}
				}
			}

		} // if MySQL 5
	}

	/**
	 * Populates the _tables array with the metadata of each table and generates
	 * dependency information for views and merge tables
	 */
	private function get_tables_data()
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Starting CREATE TABLE and dependency scanning");

		// Get a database connection
		$db = $this->getDB();

		if ($this->getError())
		{
			return;
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Got database connection");

		// Reset internal tables
		$this->tables_data = array();
		$this->dependencies = array();

		// Get a list of tables where their engine type is shown
		$sql = 'SHOW TABLES';
		$db->setQuery($sql);
		$metadata_list = $db->loadRowList();

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Got SHOW TABLES");

		// Get filters and filter root
		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');
		$filters = AEFactory::getFilters();

		foreach ($metadata_list as $table_metadata)
		{
			// Skip over tables not included in the backup set
			if (!array_key_exists($table_metadata[0], $this->table_name_map))
			{
				continue;
			}

			// Basic information
			$table_name = $table_metadata[0];
			$table_abstract = $this->table_name_map[$table_metadata[0]];
			$new_entry = array(
				'type'         => 'table',
				'dump_records' => true
			);

			// Get the CREATE command
			$dependencies = array();
			$new_entry['create'] = $this->get_create($table_abstract, $table_name, $new_entry['type'], $dependencies);
			$new_entry['dependencies'] = $dependencies;

			if ($new_entry['type'] == 'view')
			{
				$new_entry['dump_records'] = false;
			}
			else
			{
				$new_entry['dump_records'] = true;
			}

			// Scan for the table engine.
			$engine = null; // So that we detect VIEWs correctly

			if ($new_entry['type'] == 'table')
			{
				$engine = 'MyISAM'; // So that even with MySQL 4 hosts we don't screw this up
				$engine_keys = array('ENGINE=', 'TYPE=');

				foreach ($engine_keys as $engine_key)
				{
					$start_pos = strrpos($new_entry['create'], $engine_key);

					if ($start_pos !== false)
					{
						// Advance the start position just after the position of the ENGINE keyword
						$start_pos += strlen($engine_key);
						// Try to locate the space after the engine type
						$end_pos = stripos($new_entry['create'], ' ', $start_pos);

						if ($end_pos === false)
						{
							// Uh... maybe it ends with ENGINE=EngineType;
							$end_pos = stripos($new_entry['create'], ';', $start_pos);
						}

						if ($end_pos !== false)
						{
							// Grab the string
							$engine = substr($new_entry['create'], $start_pos, $end_pos - $start_pos);

							if (empty($engine))
							{
								AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "*** DEBUG *** $table_name - engine $engine");
								AEUtilLogger::WriteLog(_AE_LOG_DEBUG, $new_entry['create']);
								AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "start $start_pos - end $end_pos");
							}
						}
					}
				}

				$engine = strtoupper($engine);
			}

			switch ($engine)
			{
				/*
				// Views -- They are detected based on their CREATE statement
				case null:
					$new_entry['type'] = 'view';
					$new_entry['dump_records'] = false;
					break;
				*/

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

				// Normal tables and VIEWs
				default:
					break;
			}

			// Table Data Filter - skip dumping table contents of filtered out tables
			if ($filters->isFiltered($table_abstract, $root, 'dbobject', 'content'))
			{
				$new_entry['dump_records'] = false;
			}

			$this->tables_data[$table_name] = $new_entry;
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Got table list");

		// If we have MySQL > 5.0 add stored procedures, stored functions and triggers
		$enable_entities = $registry->get('engine.dump.native.advanced_entitites', true);

		if ($enable_entities)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Listing MySQL entities");
			// Get a list of procedures
			$sql = 'SHOW PROCEDURE STATUS WHERE `Db`=' . $db->Quote($this->database);
			$db->setQuery($sql);
			$metadata_list = $db->loadRowList();

			if (is_array($metadata_list))
			{
				if (count($metadata_list))
				{
					foreach ($metadata_list as $entity_metadata)
					{
						// Skip over entities not included in the backup set
						if (!array_key_exists($entity_metadata[1], $this->table_name_map))
						{
							continue;
						}

						// Basic information
						$entity_name = $entity_metadata[1];
						$entity_abstract = $this->table_name_map[$entity_metadata[1]];
						$new_entry = array(
							'type'         => 'procedure',
							'dump_records' => false
						);

						// There's no point trying to add a non-procedure entity
						if ($entity_metadata[2] != 'PROCEDURE')
						{
							continue;
						}

						$dependencies = array();
						$new_entry['create'] = $this->get_create($entity_abstract, $entity_name, $new_entry['type'], $dependencies);
						$new_entry['dependencies'] = $dependencies;
						$this->tables_data[$entity_name] = $new_entry;
					}
				}
			} // foreach

			// Get a list of functions
			$sql = 'SHOW FUNCTION STATUS WHERE `Db`=' . $db->Quote($this->database);
			$db->setQuery($sql);
			$metadata_list = $db->loadRowList();

			if (is_array($metadata_list))
			{
				if (count($metadata_list))
				{
					foreach ($metadata_list as $entity_metadata)
					{
						// Skip over entities not included in the backup set
						if (!array_key_exists($entity_metadata[1], $this->table_name_map))
						{
							continue;
						}

						// Basic information
						$entity_name = $entity_metadata[1];
						$entity_abstract = $this->table_name_map[$entity_metadata[1]];
						$new_entry = array(
							'type'         => 'function',
							'dump_records' => false
						);

						// There's no point trying to add a non-function entity
						if ($entity_metadata[2] != 'FUNCTION')
						{
							continue;
						}

						$dependencies = array();
						$new_entry['create'] = $this->get_create($entity_abstract, $entity_name, $new_entry['type'], $dependencies);
						$new_entry['dependencies'] = $dependencies;
						$this->tables_data[$entity_name] = $new_entry;
					}
				}
			} // foreach

			// Get a list of triggers
			$sql = 'SHOW TRIGGERS';
			$db->setQuery($sql);
			$metadata_list = $db->loadRowList();

			if (is_array($metadata_list))
			{
				if (count($metadata_list))
				{
					foreach ($metadata_list as $entity_metadata)
					{
						// Skip over entities not included in the backup set
						if (!array_key_exists($entity_metadata[0], $this->table_name_map))
						{
							continue;
						}

						// Basic information
						$entity_name = $entity_metadata[0];
						$entity_abstract = $this->table_name_map[$entity_metadata[0]];
						$new_entry = array(
							'type'         => 'trigger',
							'dump_records' => false
						);

						$dependencies = array();
						$new_entry['create'] = $this->get_create($entity_abstract, $entity_name, $new_entry['type'], $dependencies);
						$new_entry['dependencies'] = $dependencies;
						$this->tables_data[$entity_name] = $new_entry;
					}
				}
			} // foreach

			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Got MySQL entities list");
		}

		/*
		// Only store unique values
		if(count($dependencies) > 0)
			$dependencies = array_unique($dependencies);
		*/
	}

	/**
	 * Populates the _tables array with the metadata of each table
	 */
	private function get_tables_data_without_dependencies()
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Pushing table data (without dependency tracking)");

		// Reset internal tables
		$this->tables_data = array();
		$this->dependencies = array();

		// Get filters and filter root
		$registry = AEFactory::getConfiguration();
		$root = $registry->get('volatile.database.root', '[SITEDB]');
		$filters = AEFactory::getFilters();

		foreach ($this->table_name_map as $table_name => $table_abstract)
		{
			$new_entry = array(
				'type'         => 'table',
				'dump_records' => true
			);

			// Table Data Filter - skip dumping table contents of filtered out tables
			if ($filters->isFiltered($table_abstract, $root, 'dbobject', 'content'))
			{
				$new_entry['dump_records'] = false;
			}

			$this->tables_data[$table_name] = $new_entry;
			$this->tables[] = $table_name;
		} // foreach

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Got table list");
	}

	/**
	 * Gets the CREATE TABLE command for a given table/view/procedure/function/trigger
	 *
	 * @param string $table_abstract The abstracted name of the entity
	 * @param string $table_name     The name of the table
	 * @param string $type           The type of the entity to scan. If it's found to differ, the correct type is returned.
	 * @param array  $dependencies   The dependencies of this table
	 *
	 * @return string The CREATE command, w/out newlines
	 */
	private function get_create($table_abstract, $table_name, &$type, &$dependencies)
	{
		$configuration = AEFactory::getConfiguration();
		$notracking = $configuration->get('engine.dump.native.nodependencies', 0);

		$db = $this->getDB();

		if ($this->getError())
		{
			return;
		}

		switch ($type)
		{
			case 'table':
			case 'merge':
			case 'view':
				$sql = "SHOW CREATE TABLE `$table_abstract`";
				break;

			case 'procedure':
				$sql = "SHOW CREATE PROCEDURE `$table_abstract`";
				break;

			case 'function':
				$sql = "SHOW CREATE FUNCTION `$table_abstract`";
				break;

			case 'trigger':
				$sql = "SHOW CREATE TRIGGER `$table_abstract`";
				break;
		}

		$db->setQuery($sql);
		$temp = $db->loadRowList();

		if (in_array($type, array('procedure', 'function', 'trigger')))
		{
			$table_sql = $temp[0][2];

			// These can contain comment lines, starting with a double dash. Remove them.
			$lines = explode("\n", $table_sql);
			$table_sql = '';

			foreach ($lines as $line)
			{
				$line = trim($line);

				if (substr($line, 0, 2) == '--')
				{
					continue;
				}

				$table_sql .= $line . ' ';
			}

			$table_sql = trim($table_sql);

			if (substr($table_sql, -1) != ';')
			{
				$table_sql .= ';';
			}
		}
		else
		{
			$table_sql = $temp[0][1];
		}
		unset($temp);

		// Smart table type detection
		if (in_array($type, array('table', 'merge', 'view')))
		{
			// Check for CREATE VIEW
			$pattern = '/^CREATE(.*) VIEW (.*)/i';
			$result = preg_match($pattern, $table_sql);

			if ($result === 1)
			{
				// This is a view.
				$type = 'view';
			}
			else
			{
				// This is a table.
				$type = 'table';

				// # Fix 3.2.1: USING BTREE / USING HASH in indices causes issues migrating Joomla! 1.6 from MySQL 5.1 to MySQL 5.0
				if ($configuration->get('engine.dump.native.nobtree', 1))
				{
					$table_sql = str_replace(' USING BTREE', ' ', $table_sql);
					$table_sql = str_replace(' USING HASH', ' ', $table_sql);
				}

				// Translate TYPE= to ENGINE=
				$table_sql = str_replace('TYPE=', 'ENGINE=', $table_sql);
			}

			// Is it a VIEW but we don't have SHOW VIEW privileges?
			if (empty($table_sql))
			{
				$type = 'view';
			}
		}

		// Replace table name and names of referenced tables with their abstracted
		// forms and populate dependency tables at the same time

		// On DB only backup we don't want any replacing to take place, do we?
		if (!AEUtilScripting::getScriptingParameter('db.abstractnames', 1))
		{
			$old_table_sql = $table_sql;
		}

		// Return dependency information only if dependency tracking is enabled
		if (!$notracking)
		{
			// Even on simple tables, we may have foreign key references.
			// As a result, we need to replace those referenced table names
			// as well. On views and merge arrays, we have referenced tables
			// by definition.
			$dependencies = array();
			// First, the table/view/merge table name itself:
			$table_sql = str_replace($table_name, $table_abstract, $table_sql);

			// Now, loop for all table entries
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

		// Post-process CREATE VIEW
		if ($type == 'view')
		{
			$pos_view = strpos($table_sql, ' VIEW ');

			if ($pos_view > 7)
			{
				// Only post process if there are view properties between the CREATE and VIEW keywords
				$propstring = substr($table_sql, 7, $pos_view - 7); // Properties string
				// Fetch the ALGORITHM={UNDEFINED | MERGE | TEMPTABLE} keyword
				$algostring = '';
				$algo_start = strpos($propstring, 'ALGORITHM=');

				if ($algo_start !== false)
				{
					$algo_end = strpos($propstring, ' ', $algo_start);
					$algostring = substr($propstring, $algo_start, $algo_end - $algo_start + 1);
				}

				// Create our modified create statement
				$table_sql = 'CREATE OR REPLACE ' . $algostring . substr($table_sql, $pos_view);
			}
		}
		elseif ($type == 'procedure')
		{
			$pos_entity = stripos($table_sql, ' PROCEDURE ');

			if ($pos_entity !== false)
			{
				$table_sql = 'CREATE' . substr($table_sql, $pos_entity);
			}
		}
		elseif ($type == 'function')
		{
			$pos_entity = stripos($table_sql, ' FUNCTION ');

			if ($pos_entity !== false)
			{
				$table_sql = 'CREATE' . substr($table_sql, $pos_entity);
			}
		}
		elseif ($type == 'trigger')
		{
			$pos_entity = stripos($table_sql, ' TRIGGER ');

			if ($pos_entity !== false)
			{
				$table_sql = 'CREATE' . substr($table_sql, $pos_entity);
			}
		}

		// Add DROP statements for DB only backup
		if (AEUtilScripting::getScriptingParameter('db.dropstatements', 0))
		{
			if (($type == 'table') || ($type == 'merge'))
			{
				// Table or merge tables, get a DROP TABLE statement
				$drop = "DROP TABLE IF EXISTS " . $db->quoteName($table_name) . ";\n";
			}
			elseif ($type == 'view')
			{
				// Views get a DROP VIEW statement
				$drop = "DROP VIEW IF EXISTS " . $db->quoteName($table_name) . ";\n";
			}
			elseif ($type == 'procedure')
			{
				// Procedures get a DROP PROCEDURE statement and proper delimiter strings
				$drop = "DROP PROCEDURE IF EXISTS " . $db->quoteName($table_name) . ";\n";
				$drop .= "DELIMITER // ";
				$table_sql = str_replace("\r", " ", $table_sql);
				$table_sql = str_replace("\t", " ", $table_sql);
				$table_sql = rtrim($table_sql, ";\n") . " // DELIMITER ;\n";
			}
			elseif ($type == 'function')
			{
				// Procedures get a DROP FUNCTION statement and proper delimiter strings
				$drop = "DROP FUNCTION IF EXISTS " . $db->quoteName($table_name) . ";\n";
				$drop .= "DELIMITER // ";
				$table_sql = str_replace("\r", " ", $table_sql);
				$table_sql = rtrim($table_sql, ";\n") . "// DELIMITER ;\n";
			}
			elseif ($type == 'trigger')
			{
				// Procedures get a DROP TRIGGER statement and proper delimiter strings
				$drop = "DROP TRIGGER IF EXISTS " . $db->quoteName($table_name) . ";\n";
				$drop .= "DELIMITER // ";
				$table_sql = str_replace("\r", " ", $table_sql);
				$table_sql = str_replace("\t", " ", $table_sql);
				$table_sql = rtrim($table_sql, ";\n") . "// DELIMITER ;\n";
			}

			$table_sql = $drop . $table_sql;
		}

		return $table_sql;
	}

	/**
	 * Process all table dependencies
	 *
	 * @return null
	 */
	protected function process_dependencies()
	{
		if (count($this->table_name_map) > 0)
		{
			foreach ($this->table_name_map as $table_name => $table_abstract)
			{
				$this->push_table($table_name);
			}
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Processed dependencies");
	}

	/**
	 * Pushes a table in the _tables stack, making sure it will appear after
	 * its dependencies and other tables/views depending on it will eventually
	 * appear after it. It's a complicated chicken-and-egg problem. Just make
	 * sure you don't have any bloody circular references!!
	 *
	 * @param string $table_name Canonical name of the table to push
	 * @param array  $stack      When called recursive, other views/tables previously processed in order to detect *ahem* dependency loops...
	 */
	private function push_table($table_name, $stack = array(), $currentRecursionDepth = 0)
	{
		// Load information
		$table_data = $this->tables_data[$table_name];
		$table_abstract = $this->table_name_map[$table_name];

		if (array_key_exists('dependencies', $table_data))
		{
			$referenced = $table_data['dependencies'];
		}
		else
		{
			$referenced = array();
		}

		unset($table_data);

		// Try to find the minimum insert position, so as to appear after the last referenced table
		$insertpos = false;

		if (count($referenced))
		{
			foreach ($referenced as $referenced_table)
			{
				if (count($this->tables))
				{
					$newpos = array_search($referenced_table, $this->tables);

					if ($newpos !== false)
					{
						if ($insertpos === false)
						{
							$insertpos = $newpos;
						}
						else
						{
							$insertpos = max($insertpos, $newpos);
						}
					}
				}
			}
		}

		// Add to the _tables array
		if (count($this->tables) && !($insertpos === false))
		{
			array_splice($this->tables, $insertpos + 1, 0, $table_name);
		}
		else
		{
			$this->tables[] = $table_name;
		}

		// Here's what... Some other table/view might depend on us, so we must appear
		// before it (actually, it must appear after us). So, we scan for such
		// tables/views and relocate them
		if (count($this->dependencies))
		{
			if (array_key_exists($table_name, $this->dependencies))
			{
				foreach ($this->dependencies[$table_name] as $depended_table)
				{
					// First, make sure that either there is no stack, or the
					// depended table doesn't belong it. In any other case, we
					// were fooled to follow an endless dependency loop and we
					// will simply bail out and let the user sort things out.
					if (count($stack) > 0)
					{
						if (in_array($depended_table, $stack))
						{
							continue;
						}
					}

					$my_position = array_search($table_name, $this->tables);
					$remove_position = array_search($depended_table, $this->tables);

					if (($remove_position !== false) && ($remove_position < $my_position))
					{
						$stack[] = $table_name;
						array_splice($this->tables, $remove_position, 1);

						// Where should I put the other table/view now? Don't tell me.
						// I have to recurse...
						if ($currentRecursionDepth < 19)
						{
							$this->push_table($depended_table, $stack, ++$currentRecursionDepth);
						}
					}
				}
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

			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '`')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, '`', 1);
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
			$dropQuery = 'DROP TABLE IF EXISTS ' . $db->nameQuote($tableName) . ';';
		}
		// Parse CREATE VIEW commands
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, ' VIEW ') !== false))
		{
			// Try to get the view name
			$view_pos = strpos($query, ' VIEW ');
			$restOfQuery = trim(substr($query, $view_pos + 6)); // Rest of query, after VIEW string

			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '`')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, '`', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the table name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$tableName = substr($restOfQuery, 1, $pos - 1);
			}

			unset($restOfQuery);

			$dropQuery = 'DROP VIEW IF EXISTS ' . $db->nameQuote($tableName) . ';';
		}
		// CREATE PROCEDURE pre-processing
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'PROCEDURE ') !== false))
		{
			// Try to get the procedure name
			$entity_keyword = ' PROCEDURE ';
			$entity_pos = strpos($query, $entity_keyword);
			$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string

			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '`')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, '`', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the entity name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}

			unset($restOfQuery);

			$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
		}
		// CREATE FUNCTION pre-processing
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'FUNCTION ') !== false))
		{
			// Try to get the procedure name
			$entity_keyword = ' FUNCTION ';
			$entity_pos = strpos($query, $entity_keyword);
			$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string

			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '`')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, '`', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the entity name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}

			unset($restOfQuery);

			// Try to drop the entity anyway
			$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
		}
		// CREATE TRIGGER pre-processing
		elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'TRIGGER ') !== false))
		{
			// Try to get the procedure name
			$entity_keyword = ' TRIGGER ';
			$entity_pos = strpos($query, $entity_keyword);
			$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string

			// Is there a backtick?
			if (substr($restOfQuery, 0, 1) == '`')
			{
				// There is... Good, we'll just find the matching backtick
				$pos = strpos($restOfQuery, '`', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}
			else
			{
				// Nope, let's assume the entity name ends in the next blank character
				$pos = strpos($restOfQuery, ' ', 1);
				$entity_name = substr($restOfQuery, 1, $pos - 1);
			}

			unset($restOfQuery);

			// Try to drop the entity anyway
			$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
		}

		return $dropQuery;
	}
}