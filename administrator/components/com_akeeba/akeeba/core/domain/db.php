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
 * Multiple database backup engine.
 */
class AECoreDomainDb extends AEAbstractPart
{
	/** @var array A list of the databases to be packed */
	private $database_list = array();

	/** @var array The current database configuration data */
	private $database_config = null;

	/** @var AEAbstractDump The current dumper engine used to backup tables */
	private $dump_engine = null;

	/** @var string The contents of the databases.ini file */
	private $databases_ini = '';

	/** @var array An array containing the database definitions of all dumped databases so far */
	private $dumpedDatabases = array();

	/** @var int Total number of databases left to be processed */
	private $total_databases = 0;

	/**
	 * Implements the constructor of the class
	 *
	 * @return AECoreDomainDb
	 */
	public function __construct()
	{
		parent::__construct();
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: New instance");
	}

	/**
	 * Implements the _prepare abstract method
	 *
	 */
	protected function _prepare()
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Preparing instance");
		// Populating the list of databases
		$this->populate_database_list();
		if ($this->getError())
		{
			return false;
		}
		$this->total_databases = count($this->database_list);

		$this->setState('prepared');
	}

	/**
	 * Implements the _run() abstract method
	 */
	protected function _run()
	{
		if ($this->getState() == 'postrun')
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Already finished");
			$this->setStep('');
			$this->setSubstep('');
		}
		else
		{
			$this->setState('running');
		}

		// Make sure we have a dumper instance loaded!
		if (is_null($this->dump_engine) && !empty($this->database_list))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Iterating next database");
			// Create a new instance
			$this->dump_engine = AEFactory::getDumpEngine(true);

			// Configure the dumper instance and pass on the volatile database root registry key
			$registry = AEFactory::getConfiguration();
			$rootkeys = array_keys($this->database_list);
			$root = array_shift($rootkeys);
			$registry->set('volatile.database.root', $root);
			$this->database_config = array_shift($this->database_list);
			$this->database_config['root'] = $root;
			$this->database_config['process_empty_prefix'] = ($root == '[SITEDB]') ? true : false;
			$this->dump_engine->setup($this->database_config);

			// Error propagation
			$this->propagateFromObject($this->dump_engine);

			if ($this->getError())
			{
				return false;
			}
		}
		elseif (is_null($this->dump_engine) && empty($this->database_list))
		{
			$this->setError('Current dump engine died while resuming the step');

			return false;
		}

		// Try to step the instance
		$retArray = $this->dump_engine->tick();

		// Error propagation
		$this->propagateFromObject($this->dump_engine);
		if ($this->getError())
		{
			return false;
		}

		$this->setStep($retArray['Step']);
		$this->setSubstep($retArray['Substep']);

		// Check if the instance has finished
		if (!$retArray['HasRun'])
		{
			// The instance has finished

			// Set the number of parts
			$this->database_config['parts'] = $this->dump_engine->partNumber + 1;

			// Push the definition
			array_push($this->dumpedDatabases, $this->database_config);

			// Go to the next entry in the list and dispose the old AkeebaDumperDefault instance
			$this->dump_engine = null;

			// Are we past the end of the list?
			if (empty($this->database_list))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: No more databases left to iterate");
				$this->setState('postrun');
			}
		}
	}

	/**
	 * Implements the _finalize() abstract method
	 *
	 */
	protected function _finalize()
	{
		$this->setState('finished');

		// If we are in db backup mode, don't create a databases.ini
		$configuration = AEFactory::getConfiguration();

		if (!AEUtilScripting::getScriptingParameter('db.databasesini', 1))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Skipping databases.ini");
		}
		else
		{
			// Create the databases.ini contents
			if ($this->installerSettings->databasesini)
			{
				$this->createDatabasesINI();

				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Creating databases.ini");
				// Create a new string
				$databasesINI = $this->databases_ini;

				// BEGIN FIX 1.2 Stable -- databases.ini isn't written on disk
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Writing databases.ini contents");
				$archiver = AEFactory::getArchiverEngine();
				$virtualLocation = (AEUtilScripting::getScriptingParameter('db.saveasname', 'normal') == 'short') ? '' : $this->installerSettings->sqlroot;
				$archiver->addVirtualFile('databases.ini', $virtualLocation, $databasesINI);

				// Error propagation
				$this->propagateFromObject($archiver);
				if ($this->getError())
				{
					return false;
				}
			}

		}

		// On alldb mode, we have to finalize the archive as well
		if (AEUtilScripting::getScriptingParameter('db.finalizearchive', 0))
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, "Finalizing database dump archive");
			$archiver = AEFactory::getArchiverEngine();
			$archiver->finalize();

			// Error propagation
			$this->propagateFromObject($archiver);
			if ($this->getError())
			{
				return false;
			}
		}

		// In CLI mode we'll also close the database connection
		if (defined('AKEEBACLI'))
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, "Closing the database connection to the main database");
			$db = AEFactory::unsetDatabase();
		}

		return true;
	}

	/**
	 * Populates database_list with the list of databases in the settings
	 *
	 */
	private function populate_database_list()
	{
		// Get database inclusion filters
		$filters = AEFactory::getFilters();
		$this->database_list = $filters->getInclusions('db');
		// Error propagation
		$this->propagateFromObject($filters);
		if ($this->getError())
		{
			return false;
		}

		if (AEUtilScripting::getScriptingParameter('db.skipextradb', 0))
		{
			// On database only backups we prune extra databases
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . " :: Adding only main database");
			if (count($this->database_list) > 1)
			{
				$this->database_list = array_slice($this->database_list, 0, 1);
			}
		}
	}

	private function createDatabasesINI()
	{
		// caching databases.ini contents
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . "AkeebaCUBEDomainDBBackup :: Creating databases.ini data");
		// Create a new string
		$databasesINI = '';

		$blankOutPass = AEFactory::getConfiguration()->get('engine.dump.common.blankoutpass', 0);

		// Loop through databases list
		foreach ($this->dumpedDatabases as $definition)
		{
			$section = basename($definition['dumpFile']);

			$dboInstance = AEFactory::getDatabase($definition);
			$type = $dboInstance->name;
			$tech = $dboInstance->getDriverType();

			if ($blankOutPass)
			{
				$this->databases_ini .= <<<ENDDEF
[$section]
dbtype = "$type"
dbtech = "$tech"
dbname = "{$definition['database']}"
sqlfile = "{$definition['dumpFile']}"
dbhost = "{$definition['host']}"
dbuser = ""
dbpass = ""
prefix = "{$definition['prefix']}"
parts = "{$definition['parts']}"

ENDDEF;

			}
			else
			{
				$this->databases_ini .= <<<ENDDEF
[$section]
dbtype = "$type"
dbtech = "$tech"
dbname = "{$definition['database']}"
sqlfile = "{$definition['dumpFile']}"
dbhost = "{$definition['host']}"
dbuser = "{$definition['username']}"
dbpass = "{$definition['password']}"
prefix = "{$definition['prefix']}"
parts = "{$definition['parts']}"

ENDDEF;
			}
		}
	}

	/**
	 * Implements the getProgress() percentage calculation based on how many
	 * databases we have fully dumped and how much of the current database we
	 * have dumped.
	 *
	 * @see backend/akeeba/abstract/AEAbstractPart#getProgress()
	 */
	public function getProgress()
	{
		if ($this->total_databases)
		{
			return 0;
		}

		// Get the overall percentage (based on databases fully dumped so far)
		$remaining_steps = count($this->database_list);
		$remaining_steps++;
		$overall = 1 - ($remaining_steps / $this->total_databases);

		// How much is this step worth?
		$this_max = 1 / $this->total_databases;

		// Get the percentage done of the current database
		$local = $this->dump_engine->getProgress();

		$percentage = $overall + $local * $this_max;
		if ($percentage < 0)
		{
			$percentage = 0;
		}
		if ($percentage > 1)
		{
			$percentage = 1;
		}

		return $percentage;
	}
}