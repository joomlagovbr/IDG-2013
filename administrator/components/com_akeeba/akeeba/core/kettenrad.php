<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * This is Akeeba Engine's heart. Kettenrad is reponsible for launching the
 * domain chain of a backup job.
 */
final class AECoreKettenrad extends AEAbstractPart
{
	/** @var array Cached copy of the response array */
	private $array_cache = null;

	/** @var array The list of remaining steps */
	private $domain_chain = array();

	/** @var string The current domain's name */
	private $domain = '';

	/**@ var string The active domain's class name */
	private $class = '';

	/** @var string The current backup's tag (actually: the backup's origin) */
	private $tag = null;

	/** @var int How many steps the domain_chain array contained when the backup began. Used for percentage calculations. */
	private $total_steps = 0;

	/**
	 * Returns the current backup tag. If none is specified, it sets it to be the
	 * same as the current backup origin and returns the new setting.
	 *
	 * @return string
	 */
	public function getTag()
	{
		if (empty($this->tag))
		{
			// If no tag exists, we resort to the pre-set backup origin
			$tag = AEPlatform::getInstance()->get_backup_origin();
			$this->tag = $tag;
		}

		return $this->tag;
	}

	protected function _prepare()
	{
		// Intialize the timer class
		$timer = AEFactory::getTimer();

		// Do we have a tag?
		if (!empty($this->_parametersArray['tag']))
		{
			$this->tag = $this->_parametersArray['tag'];
		}

		// Make sure a tag exists (or create a new one)
		$this->tag = $this->getTag();

		// Reset the log
		AEUtilLogger::openLog($this->tag);
		AEUtilLogger::ResetLog($this->tag);

		set_error_handler('akeebaBackupErrorHandler');

		// Reset the storage
		AEUtilTempvars::reset($this->tag);

		// Get the domain chain
		$this->domain_chain = AEUtilScripting::getDomainChain();
		$this->total_steps = count($this->domain_chain) - 1; // Init shouldn't count in the progress bar

		// Mark this engine for Nesting Logging
		$this->nest_logging = true;

		// Preparation is over
		$this->array_cache = null;
		$this->setState('prepared');
		//restore_error_handler();
	}

	protected function _run()
	{
		AEUtilLogger::openLog($this->tag);
		set_error_handler('akeebaBackupErrorHandler');

		// Maybe we're already done or in an error state?
		if (($this->getError()) || ($this->getState() == 'postrun'))
		{
			return;
		}

		// Set running state
		$this->setState('running');

		// Initialize operation counter
		$registry = AEFactory::getConfiguration();
		$registry->set('volatile.operation_counter', 0);

		// Advance step counter
		$stepCounter = $registry->get('volatile.step_counter', 0);
		$registry->set('volatile.step_counter', ++$stepCounter);

		// Log step start number
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '====== Starting Step number ' . $stepCounter . ' ======');

		if (defined('AKEEBADEBUG'))
		{
			$root = AEPlatform::getInstance()->get_site_root();
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Site root: ' . $root);
		}

		$timer = AEFactory::getTimer();
		$finished = false;
		$error = false;
		$breakFlag = false; // BREAKFLAG is optionally passed by domains to force-break current operation

		// Apply an infinite time limit if required
		if ($registry->get('akeeba.tuning.settimelimit', 0))
		{
			if (function_exists('set_time_limit'))
			{
				set_time_limit(0);
			}
		}

		// Loop until time's up, we're done or an error occurred, or BREAKFLAG is set
		$this->array_cache = null;
		while (($timer->getTimeLeft() > 0) && (!$finished) && (!$error) && (!$breakFlag))
		{
			// Reset the break flag
			$registry->set('volatile.breakflag', false);

			// Do we have to switch domains? This only happens if there is no active
			// domain, or the current domain has finished
			$have_to_switch = false;
			$object = null;

			if ($this->class == '')
			{
				$have_to_switch = true;
			}
			else
			{
				$object = AEFactory::getDomainObject($this->class);

				if (!is_object($object))
				{
					$have_to_switch = true;
				}
				else
				{
					if (!in_array('getState', get_class_methods($object)))
					{
						$have_to_switch = true;
					}
					elseif ($object->getState() == 'finished')
					{
						$have_to_switch = true;
					}
				}
			}

			// Switch domain if necessary
			if ($have_to_switch)
			{
				if (!AEFactory::getConfiguration()->get('akeeba.tuning.nobreak.domains', 0))
				{
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: BREAKING STEP BEFORE SWITCHING DOMAIN");
					$registry->set('volatile.breakflag', true);
				}

				// Free last domain
				$object = null;

				if (empty($this->domain_chain))
				{
					// Aw, we're done! No more domains to run.
					$this->setState('postrun');
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: No more domains to process");
					$this->array_cache = null;

					//restore_error_handler();
					return;
				}

				// Shift the next definition off the stack
				$this->array_cache = null;
				$new_definition = array_shift($this->domain_chain);

				if (array_key_exists('class', $new_definition))
				{
					$this->domain = $new_definition['domain'];
					$this->class = $new_definition['class'];
					// Get a working object
					$object = AEFactory::getDomainObject($this->class);
					$object->setup($this->_parametersArray);
				}
				else
				{
					AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Kettenrad :: No class defined trying to switch domains. The backup will crash.");
					$this->domain = null;
					$this->class = null;
				}
			}
			else
			{
				if (!is_object($object))
				{
					$object = AEFactory::getDomainObject($this->class);
				}
			}

			// Tick the object
			$result = $object->tick();

			// Propagate errors
			$this->propagateFromObject($object);

			// Advance operation counter
			$currentOperationNumber = $registry->get('volatile.operation_counter', 0);
			$currentOperationNumber++;
			$registry->set('volatile.operation_counter', $currentOperationNumber);

			// Process return array
			$this->setDomain($this->domain);
			$this->setStep($result['Step']);
			$this->setSubstep($result['Substep']);

			// Check for BREAKFLAG
			$breakFlag = $registry->get('volatile.breakflag', false);

			// Process errors
			$error = false;

			if ($this->getError())
			{
				$error = true;
			}

			// Check if the backup procedure should finish now
			$finished = $error ? true : !($result['HasRun']);

			// Log operation end
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '----- Finished operation ' . $currentOperationNumber . ' ------');
		}

		// Log the result
		if (!$error)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Successful Smart algorithm on " . get_class($object));
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_ERROR, "Failed Smart algorithm on " . get_class($object));
		}

		// Log if we have to do more work or not
		if (!is_object($object))
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "Kettenrad :: Empty object found when processing domain '" . $this->domain . "'. This should never happen.");
		}
		else
		{
			if ($object->getState() == 'running')
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: More work required in domain '" . $this->domain . "'");
				// We need to set the break flag for the part processing to not batch successive steps
				$registry->set('volatile.breakflag', true);
			}
			elseif ($object->getState() == 'finished')
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: Domain '" . $this->domain . "' has finished.");
				$registry->set('volatile.breakflag', false);
			}
		}

		// Log step end
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '====== Finished Step number ' . $stepCounter . ' ======');

		if (!$registry->get('akeeba.tuning.nobreak.domains', 0))
		{
			// Force break between steps
			$registry->set('volatile.breakflag', true);
		}
		//restore_error_handler();
	}

	protected function _finalize()
	{
		// Open the log
		AEUtilLogger::openLog($this->tag);

		//set_error_handler('akeebaBackupErrorHandler');		

		// Kill the cached array
		$this->array_cache = null;

		// Remove the memory file
		AEUtilTempvars::reset($this->tag);

		// All done.
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: Just finished");
		$this->setState('finished');
		//restore_error_handler();
	}

	/**
	 * Saves the whole factory to temporary storage
	 */
	public static function save($tag = null)
	{
		$kettenrad = AEFactory::getKettenrad();

		if (empty($tag))
		{
			$kettenrad = AEFactory::getKettenrad();
			$tag = $kettenrad->tag;
		}

		$ret = $kettenrad->getStatusArray();

		if ($ret['HasRun'] == 1)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Will not save a finished Kettenrad instance");
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Saving Kettenrad instance $tag");
			// Save a Factory snapshot:
			AEUtilTempvars::set(AEFactory::serialize(), $tag);
		}
	}

	/**
	 * Loads the factory from the storage (if it exists) and returns a reference to the
	 * Kettenrad object.
	 *
	 * @param $tag string The backup tag to load
	 *
	 * @return AECoreKettenrad A reference to the Kettenrad object
	 */
	public static function &load($tag = null)
	{
		if (is_null($tag) && defined('AKEEBA_BACKUP_ORIGIN'))
		{
			$tag = AKEEBA_BACKUP_ORIGIN;
		}

		// In order to load anything, we need to have the correct profile loaded. Let's assume
		// that the latest backup record in this tag has the correct profile number set.
		$config = AEFactory::getConfiguration();

		if (empty($config->activeProfile))
		{
			// Only bother loading a configuration if none has been already loaded
			$statList = AEPlatform::getInstance()->get_statistics_list(array(
					'filters'  => array(
						array('field' => 'tag', 'value' => $tag)
					), 'order' => array(
						'by' => 'id', 'order' => 'DESC'
					)
				)
			);

			if (is_array($statList))
			{
				$stat = array_pop($statList);
				$profile = $stat['profile_id'];
				AEPlatform::getInstance()->load_configuration($profile);
			}
		}

		AEUtilLogger::openLog($tag);
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Kettenrad :: Attempting to load from database ($tag)");

		$serialized_factory = AEUtilTempvars::get($tag);

		if ($serialized_factory !== false)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, " -- Loaded stored Akeeba Factory ($tag)");
			AEFactory::unserialize($serialized_factory);
		}
		else
		{
			// There is no serialized factory. Nuke the in-memory factory.
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, " -- Stored Akeeba Factory ($tag) not found - hard reset");
			AEFactory::nuke();
			AEPlatform::getInstance()->load_configuration();
		}

		unset($serialized_factory);

		return AEFactory::getKettenrad();
	}

	/**
	 * Resets the Kettenrad state, wipping out any pending backups and/or stale
	 * temporary data.
	 *
	 * @param array $config Configuration parameters for the reset operation
	 */
	public static function reset($config = array())
	{
		$default_config = array(
			'global' => true, // Reset all origins when true
			'log'    => false, // Log our actions
			'maxrun' => 180, // Consider "pending" backups as failed after this many seconds
		);

		$config = (object)array_merge($default_config, $config);

		// Pause logging if so desired
		if (!$config->log)
		{
			AEUtilLogger::WriteLog(false, '');
		}

		$tag = null;

		if (!$config->global)
		{
			// If we're not resetting globally, get a list of running backups per tag
			$tag = AEPlatform::getInstance()->get_backup_origin();
		}

		// Cache the factory before proceeding
		$factory = AEFactory::serialize();

		$runningList = AEPlatform::getInstance()->get_running_backups($tag);

		// Origins we have to clean
		$origins = array(
			AEPlatform::getInstance()->get_backup_origin()
		);

		// 1. Detect failed backups
		if (is_array($runningList) && !empty($runningList))
		{
			// The current timestamp
			$now = time();

			// Mark running backups as failed
			foreach ($runningList as $running)
			{
				if (empty($tag))
				{
					// Check the timestamp of the log file to decide if it's stuck,
					// but only if a tag is not set
					$tstamp = @filemtime(AEUtilLogger::logName($running['origin']));

					if ($tstamp !== false)
					{
						// We can only check the timestamp if it's returned. If not, we assume the backup is stale
						$difference = abs($now - $tstamp);

						// Backups less than maxrun seconds old are not considered stale (default: 3 minutes)
						if ($difference < $config->maxrun)
						{
							continue;
						}
					}
				}

				$filenames = AEUtilStatistics::get_all_filenames($running, false);
				$totalSize = 0;

				// Process if there are files to delete...
				if (!is_null($filenames))
				{
					// Delete the failed backup's archive, if exists
					foreach ($filenames as $failedArchive)
					{
						if (file_exists($failedArchive))
						{
							$totalSize += (int)@filesize($failedArchive);
							AEPlatform::getInstance()->unlink($failedArchive);
						}
					}
				}

				// Mark the backup failed
				if (!$running['total_size'])
				{
					$running['total_size'] = $totalSize;
				}

				$running['status'] = 'fail';
				$running['multipart'] = 0;
				$dummy = null;
				AEPlatform::getInstance()->set_or_update_statistics($running['id'], $running, $dummy);

				$origins[] = $running['origin'];
			}
		}

		if (!empty($origins))
		{
			$origins = array_unique($origins);

			foreach ($origins as $tag)
			{
				AECoreKettenrad::load($tag);
				// Remove temporary files
				AEUtilTempfiles::deleteTempFiles();
				// Delete any stale temporary data
				AEUtilTempvars::reset($tag);
			}
		}

		// Reload the factory
		AEFactory::unserialize($factory);
		unset($factory);

		// Unpause logging if it was previously paused
		if (!$config->log)
		{
			AEUtilLogger::WriteLog(true, '');
		}
	}

	/**
	 * Returns a copy of the class's status array
	 *
	 * @return array
	 */
	public function getStatusArray()
	{
		if (empty($this->array_cache))
		{
			// Get the default table
			$array = $this->_makeReturnTable();

			// Add the archive name
			$statistics = AEFactory::getStatistics();
			$record = $statistics->getRecord();
			$array['Archive'] = isset($record['archivename']) ? $record['archivename'] : '';

			// Translate HasRun to what the rest of the suite expects
			$array['HasRun'] = ($this->getState() == 'finished') ? 1 : 0;

			// Translate no errors
			$array['Error'] = ($array['Error'] == false) ? '' : $array['Error'];

			$array['tag'] = $this->tag;

			$array['Progress'] = $this->getProgress();

			$this->array_cache = $array;
		}

		return $this->array_cache;
	}

	/**
	 * Gets the percentage of the backup process done so far.
	 *
	 * @return string
	 */
	public function getProgress()
	{
		// Get the overall percentage (based on domains complete so far)
		$remaining_steps = count($this->domain_chain);
		$remaining_steps++;
		$overall = 1 - ($remaining_steps / $this->total_steps);

		// How much is this step worth?
		$this_max = 1 / $this->total_steps;

		// Get the percentage done of the current object
		if (!empty($this->class))
		{
			$object = AEFactory::getDomainObject($this->class);
		}
		else
		{
			$object = null;
		}

		if (!is_object($object))
		{
			$local = 0;
		}
		else
		{
			$local = $object->getProgress();
		}

		$percentage = (int)(100 * ($overall + $local * $this_max));

		if ($percentage < 0)
		{
			$percentage = 0;
		}
		elseif ($percentage > 100)
		{
			$percentage = 100;
		}

		return $percentage;
	}
}

/**
 * Timeout error handler
 */
function deadOnTimeOut()
{
	if (connection_status() == 1)
	{
		AEUtilLogger::WriteLog(_AE_LOG_ERROR, 'The process was aborted on user\'s request');
	}
	elseif (connection_status() >= 2)
	{
		AEUtilLogger::WriteLog(_AE_LOG_ERROR, AEPlatform::getInstance()->translate('KETTENRAD_TIMEOUT'));
	}
}

register_shutdown_function("deadOnTimeOut");

/**
 * Nifty trick to track and log PHP errors to Akeeba Backup's log
 *
 * @param int    $errno
 * @param string $errstr
 * @param string $errfile
 * @param int    $errline
 *
 * @return bool|null
 */
function akeebaBackupErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Sanity check
	if (!function_exists('error_reporting'))
	{
		return false;
	}

	// Do not proceed if the error springs from an @function() construct, or if
	// the overall error reporting level is set to report no errors.
	$error_reporting = error_reporting();

	if ($error_reporting == 0)
	{
		return false;
	}

	switch ($errno)
	{

		case E_ERROR:
		case E_USER_ERROR:
			// Can I really catch fatal errors? It doesn't seem likely...
			AEUtilLogger::WriteLog(_AE_LOG_ERROR, "PHP FATAL ERROR on line $errline in file $errfile:");
			AEUtilLogger::WriteLog(_AE_LOG_ERROR, $errstr);
			AEUtilLogger::WriteLog(_AE_LOG_ERROR, "Execution aborted due to PHP fatal error");
			break;

		case E_WARNING:
		case E_USER_WARNING:
			// Log as debug messages so that we don't spook the user with warnings
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, "PHP WARNING on line $errline in file $errfile:");
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, $errstr);
			break;

		case E_NOTICE:
		case E_USER_NOTICE:
			// Log as debug messages so that we don't spook the user with notices
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "PHP NOTICE on line $errline in file $errfile:");
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, $errstr);
			break;

		default:
			// These are E_DEPRECATED, E_STRICT etc. Ignore that crap.
			break;
	}

	// Don't execute PHP's internal error handler
	//return true;
}
