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
 * Incremental file filter
 *
 * It will only backup files which are newer than the last backup taken with this profile
 */
class AEFilterIncremental extends AEAbstractFilter
{

	function __construct()
	{
		$this->object = 'file';
		$this->subtype = 'all';
		$this->method = 'api';

		if (AEFactory::getKettenrad()->getTag() == 'restorepoint')
		{
			$this->enabled = false;
		}
	}

	protected function is_excluded_by_api($test, $root)
	{
		static $filter_switch = null;
		static $last_backup = null;

		if (is_null($filter_switch))
		{
			$config = AEFactory::getConfiguration();
			$filter_switch = AEUtilScripting::getScriptingParameter('filter.incremental', 0);
			$filter_switch = ($filter_switch == 1);

			$last_backup = $config->get('volatile.filter.last_backup', null);

			if (is_null($last_backup) && $filter_switch)
			{
				// Get a list of backups on this profile
				$backups = AEPlatform::getInstance()->get_statistics_list(array(
																			   'filters' => array(
																				   array('field' => 'profile_id', 'value' => AEPlatform::getInstance()->get_active_profile())
																			   )
																		  ));

				// Find this backup's ID
				$model = AEFactory::getStatistics();
				$id = $model->getId();

				if (is_null($id))
				{
					$id = -1;
				}

				// Initialise
				JLoader::import('joomla.utilities.date');
				$last_backup = time();
				$now = $last_backup;

				// Find the last time a successful backup with this profile was made
				if (count($backups))
				{
					foreach ($backups as $backup)
					{
						// Skip the current backup
						if ($backup['id'] == $id)
						{
							continue;
						}

						// Skip non-complete backups
						if ($backup['status'] != 'complete')
						{
							continue;
						}

						$jdate = new JDate($backup['backupstart']);
						$backuptime = $jdate->toUnix();

						$last_backup = $backuptime;
						break;
					}
				}

				if ($last_backup == $now)
				{
					// No suitable backup found; disable this filter
					$config->set('volatile.scripting.incfile.filter.incremental', 0);
					$filter_switch = false;
				}
				else
				{
					// Cache the last backup timestamp
					$config->set('volatile.filter.last_backup', $last_backup);
				}
			}
		}

		if (!$filter_switch)
		{
			return false;
		}

		// Get the filesystem path for $root
		$config = AEFactory::getConfiguration();
		$fsroot = $config->get('volatile.filesystem.current_root', '');
		$ds = ($fsroot == '') || ($fsroot == '/') ? '' : DIRECTORY_SEPARATOR;
		$filename = $fsroot . $ds . $test;

		// Get the timestamp of the file
		$timestamp = @filemtime($filename);

		// If we could not get this information, include the file in the archive
		if ($timestamp === false)
		{
			return false;
		}

		// Compare it with the last backup timestamp and exclude if it's older than the last backup
		if ($timestamp <= $last_backup)
		{
			//AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Excluding $filename due to incremental backup restrictions");
			return true;
		}

		// No match? Just include the file!
		return false;
	}

}