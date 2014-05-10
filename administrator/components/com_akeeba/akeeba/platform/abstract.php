<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

abstract class AEPlatformAbstract implements AEPlatformInterface
{
	/** @var int Priority of this platform. A lower number denotes higher priority. */
	public $priority = 50;

	/** @var string The name of the platform (same as the directory name) */
	public $platformName = null;

	public $configOverrides = array();

	/** @var string The name of the table where backup profiles are stored */
	public $tableNameProfiles = '#__ak_profiles';

	/** @var string The name of the table where backup records are stored */
	public $tableNameStats = '#__ak_stats';

	public function getPlatformDirectories()
	{
		return array(dirname(__FILE__) . '/' . $this->platformName);
	}

	public function isThisPlatform()
	{
		return true;
	}

	public function register_autoloader()
	{

	}

	/**
	 * Saves the current configuration to the database table
	 *
	 * @param    int $profile_id The profile where to save the configuration to, defaults to current profile
	 *
	 * @return    bool    True if everything was saved properly
	 */
	public function save_configuration($profile_id = null)
	{
		// Load Joomla! database class
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		// Get the active profile number, if no profile was specified
		if (is_null($profile_id))
		{
			$profile_id = $this->get_active_profile();
		}

		// Get an INI format registry dump
		$registry = AEFactory::getConfiguration();
		$dump_profile = $registry->exportAsINI();

		// Encrypt the registry dump if required
		$dump_profile = AEUtilSecuresettings::encryptSettings($dump_profile);

		$sql = $db->getQuery(true)
			->update($db->qn($this->tableNameProfiles))
			->set($db->qn('configuration') . ' = ' . $db->q($dump_profile))
			->where($db->qn('id') . ' = ' . $db->q($profile_id));
		$db->setQuery($sql);

		try
		{
			$db->query();
		}
		catch (Exception $exc)
		{
			return false;
		}

		return true;
	}

	/**
	 * Loads the current configuration off the database table
	 *
	 * @param    int $profile_id The profile where to read the configuration from, defaults to current profile
	 *
	 * @return    bool    True if everything was read properly
	 */
	public function load_configuration($profile_id = null)
	{
		// Load Joomla! database class
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		// Get the active profile number, if no profile was specified
		if (is_null($profile_id))
		{
			$profile_id = $this->get_active_profile();
		}

		// Initialize the registry
		$registry = AEFactory::getConfiguration();
		$registry->reset();

		// Load the INI format local configuration dump off the database
		$sql = $db->getQuery(true)
			->select($db->qn('configuration'))
			->from($db->qn($this->tableNameProfiles))
			->where($db->qn('id') . ' = ' . $db->q($profile_id));

		$db->setQuery($sql);
		$ini_data_local = $db->loadResult();
		if (empty($ini_data_local) || is_null($ini_data_local))
		{
			// No configuration was saved yet - store the defaults
			$this->save_configuration($profile_id);
		}
		else
		{
			// Configuration found. Convert to array format.
			if (function_exists('get_magic_quotes_runtime'))
			{
				if (@get_magic_quotes_runtime())
				{
					$ini_data_local = stripslashes($ini_data_local);
				}
			}
			// Decrypt the data if required
			$ini_data_local = AEUtilSecuresettings::decryptSettings($ini_data_local);

			$ini_data_local = AEUtilINI::parse_ini_file_php($ini_data_local, true, true);
			$ini_data = array();
			foreach ($ini_data_local as $section => $row)
			{
				if (is_array($row) && !empty($row))
				{
					foreach ($row as $key => $value)
					{
						$ini_data["$section.$key"] = $value;
					}
				}
			}
			unset($ini_data_local);

			// Import the configuration array
			$registry->mergeArray($ini_data, false, false);
		}

		// Apply config overrides
		if (is_array($this->configOverrides) && !empty($this->configOverrides))
		{
			$registry->mergeArray($this->configOverrides, false, false);
		}

		$registry->activeProfile = $profile_id;
	}

	public function get_stock_directories()
	{
		return array();
	}

	public function get_site_root()
	{
		return '';
	}

	public function get_installer_images_path()
	{
		return '';
	}

	public function get_active_profile()
	{
		return 1;
	}

	public function get_profile_name($id = null)
	{
		return '';
	}

	public function get_backup_origin()
	{
		return 'backend';
	}

	public function get_timestamp_database($date = 'now')
	{
		return '';
	}

	public function get_local_timestamp($format)
	{
		return '';
	}

	public function get_host()
	{
		return '';
	}

	public function get_site_name()
	{
		return '';
	}

	public function get_default_database_driver($use_platform = true)
	{
		return 'AEDriverMysqli';
	}

	/**
	 * Creates or updates the statistics record of the current backup attempt
	 *
	 * @param int              $id     Backup record ID, use null for new record
	 * @param array            $data   The data to store
	 * @param AEAbstractObject $caller The calling object
	 *
	 * @return int|null|bool The new record id, or null if this doesn't apply, or false if it failed
	 */
	public function set_or_update_statistics($id = null, $data = array(), &$caller)
	{
		if (!is_array($data))
		{
			return null;
		} // No valid data?
		if (empty($data))
		{
			return null;
		} // No data at all?

		$db = AEFactory::getDatabase($this->get_platform_database_options());

		if (is_null($id))
		{
			// Create a new record
			$sql_fields = array();
			$sql_values = '';
			foreach ($data as $key => $value)
			{
				$sql_fields[] = $db->qn($key);
				$sql_values .= (!empty($sql_values) ? ',' : '') . $db->Quote($value);
			}
			$sql = $db->getQuery(true)
				->insert($db->quoteName($this->tableNameStats))
				->columns($sql_fields)
				->values($sql_values);
			$db->setQuery($sql);
			try
			{
				$db->query();
			}
			catch (Exception $exc)
			{
				$caller->setError($exc->getMessage());

				return false;
			}

			return $db->insertid();
		}
		else
		{
			$sql_set = array();
			foreach ($data as $key => $value)
			{
				if ($key == 'id')
				{
					continue;
				}

				$sql_set[] = $db->qn($key) . '=' . $db->q($value);
			}
			$sql = $db->getQuery(true)
				->update($db->qn($this->tableNameStats))
				->set($sql_set)
				->where($db->qn('id') . '=' . $db->q($id));
			$db->setQuery($sql);

			try
			{
				$db->query();
			}
			catch (Exception $exc)
			{
				$caller->setError($exc->getMessage());

				return false;
			}

			return null;
		}
	}


	/**
	 * Loads and returns a backup statistics record as a hash array
	 *
	 * @param int $id Backup record ID
	 *
	 * @return array
	 */
	public function get_statistics($id)
	{
		$db = AEFactory::getDatabase($this->get_platform_database_options());
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($this->tableNameStats))
			->where($db->qn('id') . ' = ' . $db->q($id));
		$db->setQuery($query);

		return $db->loadAssoc(true);
	}


	/**
	 * Completely removes a backup statistics record
	 *
	 * @param int $id Backup record ID
	 *
	 * @return bool True on success
	 */
	public function delete_statistics($id)
	{
		$db = AEFactory::getDatabase($this->get_platform_database_options());
		$query = $db->getQuery(true)
			->delete($db->qn($this->tableNameStats))
			->where($db->qn('id') . ' = ' . $db->q($id));
		$db->setQuery($query);

		$result = true;
		try
		{
			$db->query();
		}
		catch (Exception $exc)
		{
			$result = false;
		}

		return $result;
	}


	/**
	 * Returns a list of backup statistics records, respecting the pagination
	 *
	 * The $config array allows the following options to be set:
	 * limitstart    int        Offset in the recordset to start from
	 * limit        int        How many records to return at once
	 * filters        array    An array of filters to apply to the results. Alternatively you can just pass a profile ID to filter by that profile.
	 * order        array    Record ordering information (by and ordering)
	 *
	 * @return array
	 */
	function &get_statistics_list($config = array())
	{
		$defaultConfiguration = array(
			'limitstart' => 0,
			'limit'      => 0,
			'filters'    => array(),
			'order'      => null
		);
		$config = (object)array_merge($defaultConfiguration, $config);

		$db = AEFactory::getDatabase($this->get_platform_database_options());

		$query = $db->getQuery(true);

		if (!empty($config->filters))
		{
			if (is_array($config->filters))
			{
				if (!empty($config->filters))
				{
					// Parse the filters array
					foreach ($config->filters as $f)
					{
						$clause = $db->qn($f['field']);
						if (array_key_exists('operand', $f))
						{
							$clause .= ' ' . strtoupper($f['operand']) . ' ';
							if ($f['operand'] == 'BETWEEN')
							{
								$clause .= $db->q($f['value']) . ' AND ' . $db->q($f['value2']);
							}
							elseif ($f['operand'] == 'LIKE')
							{
								$clause .= '\'%' . $db->escape($f['value']) . '%\'';
							}
							else
							{
								$clause .= $db->q($f['value']);
							}
						}
						else
						{
							$clause .= ' = ' . $db->q($f['value']);
						}

						$query->where($clause);
					}
				}
			}
			else
			{
				// Legacy mode: profile ID given
				$query->where($db->qn('profile_id') . ' = ' . $db->q($config->filters));
			}
		}

		if (empty($config->order) || !is_array($config->order))
		{
			$config->order = array(
				'by'    => 'id',
				'order' => 'DESC'
			);
		}

		$query->select('*')
			->from($db->qn($this->tableNameStats))
			->order($db->qn($config->order['by']) . " " . strtoupper($config->order['order']));

		$db->setQuery($query, $config->limitstart, $config->limit);

		$list = $db->loadAssocList();

		return $list;
	}

	/**
	 * Return the total number of statistics records
	 *
	 * @param    array $filters An array of filters to apply to the results. Alternatively you can just pass a profile ID to filter by that profile.
	 *
	 * @return int
	 */
	function get_statistics_count($filters = null)
	{
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		$query = $db->getQuery(true);

		if (!empty($filters))
		{
			if (is_array($filters))
			{
				if (!empty($filters))
				{
					// Parse the filters array
					foreach ($filters as $f)
					{
						$clause = $db->quoteName($f['field']);
						if (array_key_exists('operand', $f))
						{
							$clause .= ' ' . strtoupper($f['operand']) . ' ';
						}
						else
						{
							$clause .= ' = ';
						}
						if ($f['operand'] == 'BETWEEN')
						{
							$clause .= $db->q($f['value']) . ' AND ' . $db->q($f['value2']);
						}
						elseif ($f['operand'] == 'LIKE')
						{
							$clause .= '\'%' . $db->escape($f['value']) . '%\'';
						}
						else
						{
							$clause .= $db->q($f['value']);
						}
						$query->where($clause);
					}
				}
			}
			else
			{
				// Legacy mode: profile ID given
				$query->where($db->qn('profile_id') . ' = ' . $db->q($filters));
			}
		}

		$query->select('COUNT(*)')
			->from($db->quoteName($this->tableNameStats));
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Returns an array with the specifics of running backups
	 * @return unknown_type
	 */
	public function get_running_backups($tag = null)
	{
		$db = AEFactory::getDatabase($this->get_platform_database_options());
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($this->tableNameStats))
			->where($db->qn('status') . ' = ' . $db->q('run'))
			->where(' NOT ' . $db->qn('archivename') . ' = ' . $db->q(''));
		if (!empty($tag))
		{
			$query->where($db->qn('origin') . '=' . $db->q($tag));
		}
		$db->setQuery($query);

		return $db->loadAssocList();
	}

	/**
	 * Multiple backup attempts can share the same backup file name. Only
	 * the last backup attempt's file is considered valid. Previous attempts
	 * have to be deemed "obsolete". This method returns a list of backup
	 * statistics ID's with "valid"-looking names. IT DOES NOT CHECK FOR THE
	 * EXISTENCE OF THE BACKUP FILE!
	 *
	 * @param bool  $useprofile If true, it will only return backup records of the current profile
	 * @param array $tagFilters Which tags to include; leave blank for all. If the first item is "NOT", then all tags EXCEPT those listed will be included.
	 *
	 * @return array A list of ID's for records w/ "valid"-looking backup files
	 */
	public function &get_valid_backup_records($useprofile = false, $tagFilters = array(), $ordering = 'DESC')
	{
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		$query2 = $db->getQuery(true)
			->select('MAX(' . $db->qn('id') . ') AS ' . $db->qn('id'))
			->from($db->qn($this->tableNameStats))
			->where($db->qn('status') . ' = ' . $db->q('complete'))
			->group($db->qn('absolute_path'));;

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn($this->tableNameStats))
			->where($db->qn('filesexist') . ' = ' . $db->q(1))
			->where($db->qn('id') . ' IN (' . $query2 . ')')
			->where('NOT ' . $db->qn('absolute_path') . ' = ' . $db->q(''))
			->order($db->qn('id') . ' ' . $ordering);

		if ($useprofile)
		{
			$profile_id = $this->get_active_profile();
			$query->where($db->qn('profile_id') . " = " . $db->q($profile_id));
		}
		if (!empty($tagFilters))
		{
			$operator = '';
			$first = array_shift($tagFilters);
			if ($first == 'NOT')
			{
				$operator = 'NOT';
			}
			else
			{
				array_unshift($tagFilters, $first);
			}

			$quotedTags = array();
			foreach ($tagFilters as $tag)
			{
				$quotedTags[] = $db->q($tag);
			}
			$filter = implode(', ', $quotedTags);
			unset($quotedTags);
			$query->where($operator . ' ' . $db->quoteName('tag') . ' IN (' . $filter . ')');
		}
		$db->setQuery($query);
		$array = $db->loadColumn();

		return $array;
	}

	/**
	 * Invalidates older records sharing the same $archivename
	 *
	 * @param string $archivename
	 */
	public function remove_duplicate_backup_records($archivename)
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Removing any old records with $archivename filename");
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn($this->tableNameStats))
			->where($db->qn('archivename') . ' = ' . $db->q($archivename))
			->order($db->qn('id') . ' DESC');

		$db->setQuery($query);
		$array = $db->loadColumn();

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, count($array) . " records found");

		// No records?! Quit.
		if (empty($array))
		{
			return;
		}
		// Only one record. Quit.
		if (count($array) == 1)
		{
			return;
		}

		// Shift the first (latest) element off the array
		$currentID = array_shift($array);

		// Invalidate older records
		$this->invalidate_backup_records($array);
	}

	/**
	 * Marks the specified backup records as having no files
	 *
	 * @param array $ids Array of backup record IDs to ivalidate
	 */
	public function invalidate_backup_records($ids)
	{
		if (empty($ids))
		{
			return false;
		}
		$db = AEFactory::getDatabase($this->get_platform_database_options());
		$temp = array();
		foreach ($ids as $id)
		{
			$temp[] = $db->q($id);
		}
		$list = implode(',', $temp);
		$sql = $db->getQuery(true)
			->update($db->qn($this->tableNameStats))
			->set($db->qn('filesexist') . ' = ' . $db->q('0'))
			->where($db->qn('id') . ' IN (' . $list . ')');;
		$db->setQuery($sql);

		try
		{
			$db->query();
		}
		catch (Exception $exc)
		{
			return false;
		}

		return true;
	}

	/**
	 * Gets a list of records with remotely stored files in the selected remote storage
	 * provider and profile.
	 *
	 * @param $profile int (optional) The profile to use. Skip or use null for active profile.
	 * @param $engine  string (optional) The remote engine to looks for. Skip or use null for the active profile's engine.
	 *
	 * @return array
	 */
	public function get_valid_remote_records($profile = null, $engine = null)
	{
		$config = AEFactory::getConfiguration();
		$result = array();

		if (is_null($profile))
		{
			$profile = $this->get_active_profile();
		}
		if (is_null($engine))
		{
			$engine = $config->get('akeeba.advanced.proc_engine', '');
		}

		if (empty($engine))
		{
			return $result;
		}

		$db = AEFactory::getDatabase($this->get_platform_database_options());
		$sql = $db->getQuery(true)
			->select('*')
			->from($db->qn($this->tableNameStats))
			->where($db->qn('profile_id') . ' = ' . $db->q($profile))
			->where($db->qn('remote_filename') . ' LIKE ' . $db->q($engine . '://%'))
			->order($db->qn('id') . ' ASC');

		$db->setQuery($sql);

		return $db->loadAssocList();
	}

	/**
	 * Returns the filter data for the entire filter group collection
	 * @return array
	 */
	public function &load_filters()
	{
		// Load the filter data from the database
		$profile_id = $this->get_active_profile();
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		// Load the INI format local configuration dump off the database
		$sql = $db->getQuery(true)
			->select($db->qn('filters'))
			->from($db->qn($this->tableNameProfiles))
			->where($db->qn('id') . ' = ' . $db->q($profile_id));
		$db->setQuery($sql);
		$all_filter_data = $db->loadResult();

		if (is_null($all_filter_data) || empty($all_filter_data))
		{
			$all_filter_data = array();
		}
		else
		{
			if (function_exists('get_magic_quotes_runtime'))
			{
				if (@get_magic_quotes_runtime())
				{
					$all_filter_data = stripslashes($all_filter_data);
				}
			}
			$all_filter_data = @unserialize($all_filter_data);
			if (empty($all_filter_data))
			{
				$all_filter_data = array();
			} // Catch unserialization errors
		}

		return $all_filter_data;
	}

	/**
	 * Saves the nested filter data array $filter_data to the database
	 *
	 * @param    array $filter_data The filter data to save
	 *
	 * @return    bool    True on success
	 */
	public function save_filters(&$filter_data)
	{
		$profile_id = $this->get_active_profile();
		$db = AEFactory::getDatabase($this->get_platform_database_options());

		$sql = $db->getQuery(true)
			->update($db->qn($this->tableNameProfiles))
			->set($db->qn('filters') . '=' . $db->q(serialize($filter_data)))
			->where($db->qn('id') . ' = ' . $db->q($profile_id));
		$db->setQuery($sql);

		try
		{
			$db->query();
		}
		catch (Exception $exc)
		{
			return false;
		}

		return true;
	}

	public function get_platform_database_options()
	{
		return array();
	}

	public function translate($key)
	{
		return '';
	}

	public function load_version_defines()
	{

	}

	public function getPlatformVersion()
	{
		return array(
			'name'    => 'Platform',
			'version' => 'unknown'
		);
	}

	public function log_platform_special_directories()
	{

	}

	public function get_platform_configuration_option($key, $default)
	{
		return '';
	}

	public function get_administrator_emails()
	{
		return array();
	}

	public function send_email($to, $subject, $body, $attachFile = null)
	{
		return false;
	}

	public function unlink($file)
	{
		return @unlink($file);
	}

	public function move($from, $to)
	{
		$result = @rename($from, $to);
		if (!$result)
		{
			$result = @copy($from, $to);
			if ($result)
			{
				$result = $this->unlink($from);
			}
		}

		return $result;
	}
}
