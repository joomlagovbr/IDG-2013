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
 * Quirk detection helper class
 */
class AEUtilQuirks
{
	/**
	 *
	 * @var  array
	 */
	public static $quirks = array(
		array('code' => '001', 'severity' => 'critical', 'callback' => array('AEUtilQuirks', 'q001'), 'description' => 'Q001'),
		array('code' => '003', 'severity' => 'critical', 'callback' => array('AEUtilQuirks', 'q003'), 'description' => 'Q003'),
		array('code' => '004', 'severity' => 'critical', 'callback' => array('AEUtilQuirks', 'q004'), 'description' => 'Q004'),

		array('code' => '101', 'severity' => 'high', 'callback' => array('AEUtilQuirks', 'q101'), 'description' => 'Q101'),
		array('code' => '103', 'severity' => 'high', 'callback' => array('AEUtilQuirks', 'q103'), 'description' => 'Q103'),
		array('code' => '104', 'severity' => 'high', 'callback' => array('AEUtilQuirks', 'q104'), 'description' => 'Q104'),

		array('code' => '201', 'severity' => 'high', 'callback' => array('AEUtilQuirks', 'q201'), 'description' => 'Q201'),
		array('code' => '202', 'severity' => 'medium', 'callback' => array('AEUtilQuirks', 'q202'), 'description' => 'Q202'),
		array('code' => '204', 'severity' => 'medium', 'callback' => array('AEUtilQuirks', 'q204'), 'description' => 'Q204'),

		array('code' => '203', 'severity' => 'low', 'callback' => array('AEUtilQuirks', 'q203'), 'description' => 'Q203'),
		array('code' => '401', 'severity' => 'low', 'callback' => array('AEUtilQuirks', 'q401'), 'description' => 'Q401'),
	);

	/**
	 * Returns the output & temporary folder writable status
	 *
	 * @return  array  A hash array with the writable status
	 */
	public static function get_folder_status()
	{
		static $status = null;

		if (is_null($status))
		{
			$stock_dirs = AEPlatform::getInstance()->get_stock_directories();

			// Get output writable status
			$registry = AEFactory::getConfiguration();
			$outdir = $registry->get('akeeba.basic.output_directory');
			foreach ($stock_dirs as $macro => $replacement)
			{
				$outdir = str_replace($macro, $replacement, $outdir);
			}
			$status['output'] = @is_writable($outdir);

		}

		return $status;
	}

	/**
	 * Returns the overall status. It's true when both the temporary and
	 * output directories are writable and no critical severity quirks have
	 * been detected.
	 *
	 * @return  boolean
	 */
	public static function get_status()
	{
		// Base the status on directory writability
		$status = self::get_folder_status();
		$ret = $status['output'];

		// Scan for high severity quirks
		$quirks = self::get_quirks();
		if (!empty($quirks))
		{
			foreach ($quirks as $quirk)
			{
				if ($quirk['severity'] == 'critical')
				{
					$ret = false;
				}
			}
		}

		// Return status
		return $ret;
	}

	/**
	 * Add a quirk definition
	 *
	 * @param   string  $code         The quirk code (three digit number)
	 * @param   string  $severity     The severity (low, medium, high, critical)
	 * @param   string  $description  The description key for this quirk
	 * @param   null    $callback     The callback used to determine the status of the quirk
	 *
	 * @return  void
	 */
	public static function addQuirkDef($code, $severity = 'low', $description = null, $callback = null)
	{
		if (!is_callable($callback))
		{
			$callback = array('AEUtilQuirks', 'q' . $code);
		}

		if (empty($description))
		{
			$description = 'Q' . $code;
		}

		$quirk = array(
			'code'			=> $code,
			'severity'		=> $severity,
			'description'	=> $description,
			'callback'		=> $callback,
		);

		static::$quirks[$code] = $quirk;
	}

	/**
	 * Remove a quirk definition
	 *
	 * @param   string  $code  The code of the quirk to remove
	 *
	 * @return  void
	 */
	public static function removeQuirkDef($code)
	{
		if (isset(static::$quirks[$code]))
		{
			unset(static::$quirks[$code]);
		}
	}

	/**
	 * Clear the quirk definitions
	 *
	 * @return  void
	 */
	public static function clearQuirkDefs()
	{
		static::$quirks = array();
	}

	/**
	 * Runs the "quirks" detection scripts. These are potential problems related to server
	 * configuration, out of Akeeba's control. They are intended to give the user a
	 * chance to fix them before they cause the backup to fail, eventually saving both
	 * the user's and support personel's time.
	 *
	 * "Quirks" numbering scheme:
	 * Q0xx No-go errors
	 * Q1xx    Critical system configuration errors
	 * Q2xx    Medium and low system configuration warnings
	 * Q3xx    Critical software configuration errors
	 * Q4xx    Medium and low component configuration warnings
	 *
	 * It populates and returns the $quirks array.
	 *
	 * @param   boolean  $low_priority       Should I include low priority quirks?
	 * @param   string   $help_url_template  The sprintf template from creating a help URL from a quirk code
	 *
	 * @return array
	 */
	public static function get_quirks($low_priority = false, $help_url_template = 'https://www.akeebabackup.com/documentation/warnings/q%s.html')
	{
		static $quirks = null;

		if (is_null($quirks))
		{
			$quirks = array();

			foreach (static::$quirks as $quirkDef)
			{
				if (!$low_priority && ($quirkDef['severity'] == 'low'))
				{
					continue;
				}

				self::getQuirk($quirks, $quirkDef, $help_url_template);
			}
		}

		return $quirks;
	}

	/**
	 * Gets a "quirk" status and adds it to the list if it is active
	 *
	 * @param   array   $quirks             The quirks array
	 * @param   array   $quirkDef           The quirk definition
	 * @param   string  $help_url_template  The sprintf template from creating a help URL from a quirk code
	 */
	private static function getQuirk(&$quirks, $quirkDef, $help_url_template)
	{
		if (call_user_func($quirkDef['callback']))
		{
			$description = AEPlatform::getInstance()->translate($quirkDef['description']);
			$quirks[(string)$quirkDef['code']] = array(
				'code'        => $quirkDef['code'],
				'severity'    => $quirkDef['severity'],
				'description' => $description,
				'help_url'    => sprintf($help_url_template, $quirkDef['code']),
			);
		}
	}

	/**
	 * Q001 - HIGH - Output directory unwritable
	 *
	 * @return bool
	 */
	private static function q001()
	{
		$status = self::get_folder_status();

		return !$status['output'];
	}

	/**
	 * Q003 - HIGH - Backup output or temporary set to site's root
	 *
	 * @return bool
	 */
	private static function q003()
	{
		$stock_dirs = AEPlatform::getInstance()->get_stock_directories();

		$registry = AEFactory::getConfiguration();
		$outdir = $registry->get('akeeba.basic.output_directory');
		foreach ($stock_dirs as $macro => $replacement)
		{
			$outdir = str_replace($macro, $replacement, $outdir);
		}

		$outdir_real = @realpath($outdir);
		if (!empty($outdir_real))
		{
			$outdir = $outdir_real;
		}

		$siteroot = AEPlatform::getInstance()->get_site_root();
		$siteroot_real = @realpath($siteroot);
		if (!empty($siteroot_real))
		{
			$siteroot = $siteroot_real;
		}

		return ($siteroot == $outdir);
	}

	/**
	 * Q004 - HIGH - Free memory too low
	 *
	 * @return bool
	 */
	private static function q004()
	{
		// If we can't figure this out, don't report a problem. It doesn't
		// really matter, as the backup WILL crash eventually.
		if (!function_exists('ini_get'))
		{
			return false;
		}

		$memLimit = ini_get("memory_limit");
		$memLimit = self::_return_bytes($memLimit);
		if ($memLimit <= 0)
		{
			return false;
		} // No limit?
		$availableRAM = $memLimit - memory_get_usage();

		// We need at least 7Mb of free memory
		return ($availableRAM <= 7340032);
	}

	/**
	 * Q101 - HIGH - open_basedir on output directory
	 *
	 * @return bool
	 */
	private static function q101()
	{
		$stock_dirs = AEPlatform::getInstance()->get_stock_directories();

		// Get output writable status
		$registry = AEFactory::getConfiguration();
		$outdir = $registry->get('akeeba.basic.output_directory');
		foreach ($stock_dirs as $macro => $replacement)
		{
			$outdir = str_replace($macro, $replacement, $outdir);
		}

		return self::checkOpenBasedirs($outdir);
	}

	/**
	 * Q103 - HIGH - Less than 10" of max_execution_time with PHP Safe Mode enabled
	 *
	 * @return bool
	 */
	private static function q103()
	{
		$exectime = ini_get('max_execution_time');
		$safemode = ini_get('safe_mode');
		if (!$safemode)
		{
			return false;
		}
		if (!is_numeric($exectime))
		{
			return false;
		}
		if ($exectime <= 0)
		{
			return false;
		}

		return $exectime < 10;
	}

	/**
	 * Q104 - HIGH - Temp directory is the same as the site's root
	 *
	 * @return bool
	 */
	private static function q104()
	{

		$siteroot = AEPlatform::getInstance()->get_site_root();
		$siteroot_real = @realpath($siteroot);
		if (!empty($siteroot_real))
		{
			$siteroot = $siteroot_real;
		}

		$stockDirs = AEPlatform::getInstance()->get_stock_directories();
		$temp_directory = $stockDirs['[SITETMP]'];
		$temp_directory = @realpath($temp_directory);
		if (empty($temp_directory))
		{
			$temp_directory = $siteroot;
		}

		return ($siteroot == $temp_directory);

	}

	/**
	 * Gets the system temporary directory's real path... or at least it tries hard to do so!
	 * @return unknown_type
	 */
	private static function sys_get_temp_dir()
	{
		// Try system environment variables
		if (function_exists('getenv'))
		{
			if ($temp = getenv('TMP'))
			{
				return $temp;
			}
			if ($temp = getenv('TEMP'))
			{
				return $temp;
			}
			if ($temp = getenv('TMPDIR'))
			{
				return $temp;
			}
		}
		// Try sys_get_temp_dir()
		if (function_exists('sys_get_temp_dir'))
		{
			$temp = sys_get_temp_dir();
			if (!empty($temp))
			{
				$temp_real = @realpath($temp);
				if (!empty($temp_real))
				{
					$temp = $temp_real;
				}

				return $temp;
			}
		}
		// Try creating a temp file
		$temp = @tempnam(__FILE__, '');
		if (file_exists($temp))
		{
			unlink($temp);

			return dirname($temp);
		}

		// If all else fails...
		return '';
	}


	/**
	 * Q201 - HIGH  - PHP4 detected
	 * Note: Q201 was originally a LOW severity quirk, but Akeeba Engine 3 no longer
	 * works on PHP4.
	 * @return bool
	 */
	private static function q201()
	{
		return version_compare(PHP_VERSION, '5.0.0') < 0;
	}

	/**
	 * Q202 - MED  - CRC problems with hash extension not present
	 *
	 * @return bool
	 */
	private static function q202()
	{
		$registry = AEFactory::getConfiguration();
		$archiver = $registry->get('akeeba.advanced.archiver_engine');
		if ($archiver != 'zip')
		{
			return false;
		}

		return !function_exists('hash_file');
	}

	/**
	 * Q203 - MED  - Default output directory in use
	 *
	 * @return bool
	 */
	private static function q203()
	{
		$stock_dirs = AEPlatform::getInstance()->get_stock_directories();

		$registry = AEFactory::getConfiguration();
		$outdir = $registry->get('akeeba.basic.output_directory');
		foreach ($stock_dirs as $macro => $replacement)
		{
			$outdir = str_replace($macro, $replacement, $outdir);
		}

		$default = $stock_dirs['[DEFAULT_OUTPUT]'];

		$outdir = AEUtilFilesystem::TranslateWinPath($outdir);
		$default = AEUtilFilesystem::TranslateWinPath($default);

		return $outdir == $default;
	}

	/**
	 * Q204 - MED  - Disabled functions may affect operation
	 *
	 * @return bool
	 */
	private static function q204()
	{
		$disabled = ini_get('disabled_functions');

		return (!empty($disabled));
	}

	/**
	 * Q401 - LOW  - ZIP format selected
	 *
	 * @return bool
	 */
	private static function q401()
	{
		$registry = AEFactory::getConfiguration();
		$archiver = $registry->get('akeeba.advanced.archiver_engine');

		return $archiver == 'zip';
	}

	/**
	 * Checks if a path is restricted by open_basedirs
	 *
	 * @param string $check The path to check
	 *
	 * @return bool True if the path is restricted (which is bad)
	 */
	public static function checkOpenBasedirs($check)
	{
		static $paths;

		if (empty($paths))
		{
			$open_basedir = ini_get('open_basedir');
			if (empty($open_basedir))
			{
				return false;
			}
			$delimiter = strpos($open_basedir, ';') !== false ? ';' : ':';
			$paths_temp = explode($delimiter, $open_basedir);

			// Some open_basedirs are using environemtn variables
			$paths = array();
			foreach ($paths_temp as $path)
			{
				if (array_key_exists($path, $_ENV))
				{
					$paths[] = $_ENV[$path];
				}
				else
				{
					$paths[] = $path;
				}
			}
		}

		if (empty($paths))
		{
			return false; // no restrictions
		}
		else
		{
			$newcheck = @realpath($check); // Resolve symlinks, like PHP does
			if (!($newcheck === false))
			{
				$check = $newcheck;
			}
			$included = false;
			foreach ($paths as $path)
			{
				$newpath = @realpath($path);
				if (!($newpath === false))
				{
					$path = $newpath;
				}
				if (strlen($check) >= strlen($path))
				{
					// Only check if the path to check is longer than the inclusion path.
					// Otherwise, I guarantee it's not included!!
					// If the path to check begins with an inclusion path, it's permitted. Easy, huh?
					if (substr($check, 0, strlen($path)) == $path)
					{
						$included = true;
					}
				}
			}

			return !$included;
		}
	}

	private static function _return_bytes($val)
	{
		$val = trim($val);
		$last = strtolower($val{strlen($val) - 1});
		switch ($last)
		{
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
}