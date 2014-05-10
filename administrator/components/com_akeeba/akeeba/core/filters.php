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
 * Akeeba filtering feature
 */
final class AECoreFilters extends AEAbstractObject
{
	/** @var array An array holding data for all defined filters */
	private $filter_registry = array();

	/** @var array Hash array with instances of all filters as $filter_name => filter_object */
	private $filters = array();

	/** @var bool True after the filter clean up has run */
	private $cleanup_has_run = false;

	/**
	 * Public constructor, loads filter data and filter classes
	 */
	public final function __construct()
	{
		static $initializing = false;

		parent::__construct(); // Call parent's constructor

		// Load filter data from platform's database
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Fetching filter data from database');
		$this->filter_registry = AEPlatform::getInstance()->load_filters();

		// Load platform, plugin and core filters
		$this->filters = array();
		$locations = array(
			AEFactory::getAkeebaRoot() . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'filters',
			AEFactory::getAkeebaRoot() . DIRECTORY_SEPARATOR . 'filters'
		);
		$platform_paths = AEPlatform::getInstance()->getPlatformDirectories();
		foreach ($platform_paths as $p)
		{
			$locations[] = $p . '/filters';
		}
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Loading filters');
		foreach ($locations as $folder)
		{
			$is_platform = $this->isPlatformDirectory($folder);
			$files = AEUtilScanner::getFiles($folder);
			if ($files === false)
			{
				continue;
			} // Skip inexistent folders
			if (empty($files))
			{
				continue;
			} // Skip no-match folders

			// Loop all files
			foreach ($files as $file)
			{
				if (substr($file, -4) != '.php')
				{
					continue; // Skip non-PHP files
				}
				if (in_array(substr($file, 0, 1), array('.', '_')))
				{
					continue; // Skip filter files starting with dot or dash
				}

                // Some hosts copy .ini and .php files, renaming them (ie foobar.1.php)
                // We need to exclude them, otherwise we'll get a fatal error for declaring the same class twice
                $bare_name = strtolower(basename($file, '.php'));

                if(preg_match('/[^a-z0-9]/', $bare_name))
                {
                    continue;
                }

				$filter_name = ($is_platform ? 'Platform' : '') . ucfirst(basename($file, '.php')); // Extract filter base name
				if (array_key_exists($filter_name, $this->filters))
				{
					continue; // Skip already loaded filters
				}
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '-- Loading filter ' . $filter_name);
				$this->filters[$filter_name] = AEFactory::getFilterObject($filter_name); // Add the filter
			}
		}

		// Load platform, plugin and core stacked filters
		$locations = array(
			AEFactory::getAkeebaRoot() . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'filters' . DIRECTORY_SEPARATOR . 'stack',
			AEFactory::getAkeebaRoot() . DIRECTORY_SEPARATOR . 'filters' . DIRECTORY_SEPARATOR . 'stack'
		);
		$platform_paths = AEPlatform::getInstance()->getPlatformDirectories();
		$platform_stack_paths = array();
		foreach ($platform_paths as $p)
		{
			$locations[] = $p . '/filters';
			$locations[] = $p . '/filters/stack';
			$platform_stack_paths[] = $p . '/filters/stack';
		}

		$config = AEFactory::getConfiguration();
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Loading optional filters');
		foreach ($locations as $folder)
		{
			$is_platform = $this->isPlatformDirectory($folder);
			$files = AEUtilScanner::getFiles($folder);
			if ($files === false)
			{
				continue;
			} // Skip inexistent folders
			if (empty($files))
			{
				continue;
			} // Skip no-match folders

			// Loop all files
			foreach ($files as $file)
			{
				if (substr($file, -4) != '.php')
				{
					continue;
				} // Skip non-PHP files

                // Some hosts copy .ini and .php files, renaming them (ie foobar.1.php)
                // We need to exclude them, otherwise we'll get a fatal error for declaring the same class twice
                $bare_name = strtolower(basename($file, '.php'));

                if(preg_match('/[^a-z0-9]/', $bare_name))
                {
                    continue;
                }

				$filter_name = 'Stack' . ($is_platform ? 'Platform' : '') . ucfirst(basename($file, '.php')); // Extract filter base name
				if (array_key_exists($filter_name, $this->filters))
				{
					continue;
				} // Skip already loaded filters
				if (!file_exists($folder . '/' . substr($file, 0, -4) . '.ini'))
				{
					continue;
				} // Make sure the INI file also exists
				$key = "core.filters.$bare_name.enabled";
				if ($config->get($key, 0))
				{
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '-- Loading optional filter ' . $filter_name);
					$this->filters[$filter_name] = AEFactory::getFilterObject($filter_name); // Add the filter
				}
			}
		}
	}

	private function isPlatformDirectory($path)
	{
		static $allPlatformPaths = array();

		if (empty($allPlatformPaths))
		{
			$platform_paths = AEPlatform::getInstance()->getPlatformDirectories();
			foreach ($platform_paths as $p)
			{
				$allPlatformPaths[] = AEUtilFilesystem::TranslateWinPath($p . '/filters');
				$allPlatformPaths[] = AEUtilFilesystem::TranslateWinPath($p . '/filters/stack');
			}
		}

		$search = AEUtilFilesystem::TranslateWinPath($path);

		return in_array($search, $allPlatformPaths);
	}

	/**
	 * Extended filtering information of a given object. Applies only to exclusion filters.
	 *
	 * @param    string $test      The string to check for filter status (e.g. filename, dir name, table name, etc)
	 * @param    string $root      The exclusion root test belongs to
	 * @param    string $object    What type of object is it? dir|file|dbobject
	 * @param    string $subtype   Filter subtype (all|content|children)
	 * @param    string $by_filter [out] The filter name which first matched $test, or an empty string
	 *
	 * @return    bool    True if it is a filtered element
	 */
	public final function isFilteredExtended($test, $root, $object, $subtype, &$by_filter)
	{
		if (!$this->cleanup_has_run)
		{
			// Loop the filters and clean up those with no data
			foreach ($this->filters as $filter_name => $filter)
			{
				if (!$this->filters[$filter_name]->hasFilters())
				{
					unset($this->filters[$filter_name]);
				} // Remove empty filters
			}
			$this->cleanup_has_run = true;
		}

		$by_filter = '';
		if (!empty($this->filters))
		{
			foreach ($this->filters as $filter_name => $filter)
			{
				if ($filter->isFiltered($test, $root, $object, $subtype))
				{
					$by_filter = strtolower($filter_name);

					return true;
				}
			}

			// If we are still here, no filter matched
			return false;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Returns the filtering status of a given object
	 *
	 * @param    string $test    The string to check for filter status (e.g. filename, dir name, table name, etc)
	 * @param    string $root    The exclusion root test belongs to
	 * @param    string $object  What type of object is it? dir|file|dbobject
	 * @param    string $subtype Filter subtype (all|content|children)
	 *
	 * @return    bool    True if it is a filtered element
	 */
	public final function isFiltered($test, $root, $object, $subtype)
	{
		$by_filter = '';

		return $this->isFilteredExtended($test, $root, $object, $subtype, $by_filter);
	}

	/**
	 * Returns the inclusion filters for a specific object type
	 *
	 * @param    string $object The inclusion object (dir|db)
	 *
	 * @return unknown_type
	 */
	public final function &getInclusions($object)
	{
		$inclusions = array();
		if (!empty($this->filters))
		{
			foreach ($this->filters as $filter_name => $filter)
			{
				$new_inclusions = $filter->getInclusions($object);
				if (!empty($new_inclusions))
				{
					$inclusions = array_merge($inclusions, $new_inclusions);
				}
			}
		}

		return $inclusions;
	}

	/**
	 * Returns the filter registry information for a specified filter class
	 *
	 * @param    string $filter_name The name of the filter we want data for
	 *
	 * @return    array    The filter data for the requested filter
	 */
	public final function &getFilterData($filter_name)
	{
		if (array_key_exists($filter_name, $this->filter_registry))
		{
			return $this->filter_registry[$filter_name];
		}
		else
		{
			$dummy = array();

			return $dummy;
		}
	}

	/**
	 * Replaces the filter data of a specific filter with the new data
	 *
	 * @param    string $filter_name The filter for which to modify the stored data
	 * @param    string $data        The new data
	 */
	public final function setFilterData($filter_name, &$data)
	{
		$this->filter_registry[$filter_name] = $data;
	}

	/**
	 * Saves all filters to the platform defined database
	 * @return bool    True on success
	 */
	public final function save()
	{
		return AEPlatform::getInstance()->save_filters($this->filter_registry);
	}

	/**
	 * Get SQL statements to append to the database backup file
	 *
	 * @param string $root
	 *
	 * @return string
	 */
	public final function &getExtraSQL($root)
	{
		$ret = "";
		if (count($this->filters) >= 1)
		{
			foreach ($this->filters as $filter_name => $filter)
			{
				$extra_sql = $filter->getExtraSQL($root);
				if (!empty($extra_sql))
				{
					if (!empty($ret))
					{
						$ret .= "\n";
					}
					$ret .= $extra_sql;
				}
			}
		}

		return $ret;
	}

	/**
	 * Checks if there is an active filter for the object/subtype requested.
	 *
	 * @param string $object  The filtering object: dir|file|dbobject|db
	 * @param string $subtype The filtering subtype: all|content|children|inclusion
	 *
	 * @return bool
	 */
	public final function hasFilterType($object, $subtype = null)
	{
		foreach ($this->filters as $filter_name => $filter)
		{
			if ($filter->object == $object)
			{
				if (is_null($subtype))
				{
					return true;
				}
				elseif ($filter->subtype == $subtype)
				{
					return true;
				}
			}
		}

		return false;
	}

}