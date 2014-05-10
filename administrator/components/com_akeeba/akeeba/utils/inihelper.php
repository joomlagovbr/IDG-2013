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
 * A class to load INI files describing the various Akeeba engines and GUI definitions,
 * along with their parameters.
 */
abstract class AEUtilInihelper
{
	/**
	 * Holds the known paths holding INI definitions of engines, installers and configuration gui elements
	 *
	 * @var  array
	 */
	protected static $paths = array();

	/**
	 * Append a path to the end of the paths list for a specific section
	 *
	 * @param   string $path    Absolute filesystem path to add
	 * @param   string $section The section to add it to (gui, engine, installer, filters)
	 *
	 * @return  void
	 */
	public static function addPath($path, $section = 'gui')
	{
		$path = AEUtilFilesystem::TranslateWinPath($path);

		// If the array is empty, populate with the defaults
		if (!array_key_exists($section, static::$paths))
		{
			static::getPaths($section);
		}

		// If the path doesn't already exist, add it
		if (!in_array($path, static::$paths[$section]))
		{
			static::$paths[$section][] = $path;
		}
	}

	/**
	 * Add a path to the beginning of the paths list for a specific section
	 *
	 * @param   string $path    Absolute filesystem path to add
	 * @param   string $section The section to add it to (gui, engine, installer, filters)
	 *
	 * @return  void
	 */
	public static function prependPath($path, $section = 'gui')
	{
		$path = AEUtilFilesystem::TranslateWinPath($path);

		// If the array is empty, populate with the defaults
		if (!array_key_exists($section, static::$paths))
		{
			static::getPaths($section);
		}

		// If the path doesn't already exist, add it
		if (!in_array($path, static::$paths[$section]))
		{
			array_unshift(static::$paths[$section], $path);
		}
	}

	/**
	 * Get the paths for a specific section
	 *
	 * @param   string $section The section to get the path list for (engine, installer, gui, filter)
	 *
	 * @return  array
	 */
	public static function getPaths($section = 'gui')
	{
		// Create the key if it's not already present
		if (!array_key_exists($section, static::$paths))
		{
			static::$paths[$section] = array();
		}

		// Add the defaults if the list is empty
		if (empty(static::$paths[$section]))
		{
			switch ($section)
			{
				case 'engine':
					static::$paths[$section] = array(
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/engines'),
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/plugins/engines'),
					);
					break;

				case 'installer':
					static::$paths[$section] = array(
						AEUtilFilesystem::TranslateWinPath(AEPlatform::getInstance()->get_installer_images_path())
					);
					break;

				case 'gui':
					// Add core GUI definitions
					static::$paths[$section] = array(
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/core')
					);

					// Add additional core GUI definitions
					if (AKEEBA_PRO)
					{
						AEUtilFilesystem::TranslateWinPath(static::$paths[$section][] = AEFactory::getAkeebaRoot() . '/plugins/core');
					}

					// Add platform GUI definition files
					$platform_paths = AEPlatform::getInstance()->getPlatformDirectories();

					foreach ($platform_paths as $p)
					{
						static::$paths[$section][] = AEUtilFilesystem::TranslateWinPath($p . '/config');
					}
					break;

				case 'filter':
					static::$paths[$section] = array(
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/platform/filters/stack'),
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/filters/stack'),
						AEUtilFilesystem::TranslateWinPath(AEFactory::getAkeebaRoot() . '/plugins/filters/stack')
					);

					$platform_paths = AEPlatform::getInstance()->getPlatformDirectories();

					foreach ($platform_paths as $p)
					{
						static::$paths[$section][] = AEUtilFilesystem::TranslateWinPath($p . '/filters/stack');
					}

					break;
			}
		}

		return static::$paths[$section];
	}

	/**
	 * Returns a hash list of Akeeba engines and their data. Each entry has the engine
	 * name as key and contains two arrays, under the 'information' and 'parameters' keys.
	 *
	 * @param string $engine_type The engine type to return information for
	 *
	 * @return array
	 */
	public static function getEnginesList($engine_type)
	{
		// This is a static cache which persists between subsequent calls, but not
		// between successive page loads.
		static $engine_list = array();

		// Try to serve cached data first
		if (isset($engine_list[$engine_type]))
		{
			return $engine_list[$engine_type];
		}

		// Find absolute path to normal and plugins directories
		$temp = static::getPaths('engine');
		$path_list = array();

		foreach ($temp as $path)
		{
			$path_list[] = $path . '/' . $engine_type;
		}

		// Initialize the array where we store our data
		$engine_list[$engine_type] = array();

		// Loop for the paths where engines can be found
		foreach ($path_list as $path)
		{
			if (is_dir($path))
			{
				if (is_readable($path))
				{
					if ($handle = @opendir($path))
					{
						while (false !== $filename = @readdir($handle))
						{
							if ((strtolower(substr($filename, -4)) == '.ini') && @is_file($path . '/' . $filename))
							{
								$bare_name = strtolower(basename($filename, '.ini'));

								// Some hosts copy .ini and .php files, renaming them (ie foobar.1.php)
								// We need to exclude them, otherwise we'll get a fatal error for declaring the same class twice
								if (preg_match('/[^a-z0-9]/', $bare_name))
								{
									continue;
								}

								$information = array();
								$parameters = array();

								AEUtilINI::parseEngineINI($path . '/' . $filename, $information, $parameters);

								$engine_name = substr($filename, 0, strlen($filename) - 4);
								$engine_list[$engine_type][$engine_name] = array(
									'information' => $information,
									'parameters'  => $parameters
								);
							}
						}
						@closedir($handle);
					}
				}
			}
		}

		return $engine_list[$engine_type];
	}

	/**
	 * Parses the GUI INI files and returns an array of groups and their data
	 *
	 * @return  array
	 */
	public static function getGUIGroups()
	{
		// This is a static cache which persists between subsequent calls, but not
		// between successive page loads.
		static $gui_list = array();

		// Try to serve cached data first
		if (!empty($gui_list) && is_array($gui_list))
		{
			if (count($gui_list) > 0)
			{
				return $gui_list;
			}
		}

		// Find absolute path to normal and plugins directories
		$path_list = static::getPaths('gui');

		// Initialize the array where we store our data
		$gui_list = array();

		// Loop for the paths where engines can be found
		foreach ($path_list as $path)
		{
			if (is_dir($path))
			{
				if (is_readable($path))
				{
					if ($handle = @opendir($path))
					{
						// Store INI names in temp array because we'll sort based on filename (GUI order IS IMPORTANT!!)
						$allINIs = array();

						while (false !== $filename = @readdir($handle))
						{
							if ((strtolower(substr($filename, -4)) == '.ini') && @is_file($path . '/' . $filename))
							{
								$allINIs[] = $path . '/' . $filename;
							}
						} // while readdir

						@closedir($handle);

						if (!empty($allINIs))
						{
							// Sort GUI files alphabetically
							asort($allINIs);

							// Include each GUI def file
							foreach ($allINIs as $filename)
							{
								$information = array();
								$parameters = array();
								AEUtilINI::parseInterfaceINI($filename, $information, $parameters);

								// This effectively skips non-GUI INIs (e.g. the scripting INI)
								if (!empty($information['description']))
								{
									if (!isset($information['merge']))
									{
										$information['merge'] = 0;
									}

									$group_name = substr(basename($filename), 0, -4);

									$def = array(
										'information' => $information,
										'parameters'  => $parameters
									);

									if (!$information['merge'] || !isset($gui_list[$group_name]))
									{
										$gui_list[$group_name] = $def;
									}
									else
									{
										$gui_list[$group_name]['information'] = array_merge($gui_list[$group_name]['information'], $def['information']);
										$gui_list[$group_name]['parameters'] = array_merge($gui_list[$group_name]['parameters'], $def['parameters']);
									}
								}
							}
						}

					} // if opendir
				} // if readable
			} // if is_dir
		}

		ksort($gui_list);

		// Push stack filter settings to the 03.filters section
		$path_list = static::getPaths('filter');

		// Loop for the paths where optional filters can be found
		foreach ($path_list as $path)
		{
			if (is_dir($path))
			{
				if (is_readable($path))
				{
					if ($handle = @opendir($path))
					{
						// Store INI names in temp array because we'll sort based on filename (GUI order IS IMPORTANT!!)
						$allINIs = array();

						while (false !== $filename = @readdir($handle))
						{
							if ((strtolower(substr($filename, -4)) == '.ini') && @is_file($path . '/' . $filename))
							{
								$allINIs[] = $path . '/' . $filename;
							}
						} // while readdir

						@closedir($handle);

						if (!empty($allINIs))
						{
							// Sort filter files alphabetically
							asort($allINIs);

							// Include each filter def file
							foreach ($allINIs as $filename)
							{
								$information = array();
								$parameters = array();
								AEUtilINI::parseInterfaceINI($filename, $information, $parameters);

								if (!array_key_exists('03.filters', $gui_list))
								{
									$gui_list['03.filters'] = array('parameters' => array());
								}

								if (!array_key_exists('parameters', $gui_list['03.filters']))
								{
									$gui_list['03.filters']['parameters'] = array();
								}

								if (!is_array($parameters))
								{
									$parameters = array();
								}
								$gui_list['03.filters']['parameters'] = array_merge($gui_list['03.filters']['parameters'], $parameters);
							}
						}
					} // if opendir
				} // if readable
			} // if is_dir
		}

		return $gui_list;
	}

	/**
	 * Parses the installer INI files and returns an array of installers and their data
	 *
	 * @param   boolean  $forDisplay  If true only returns the information relevant for displaying the GUI
	 *
	 * @return  array
	 */
	public static function getInstallerList($forDisplay = false)
	{
		// This is a static cache which persists between subsequent calls, but not
		// between successive page loads.
		static $installer_list = array();

		// Try to serve cached data first
		if (!empty($installer_list) && is_array($installer_list))
		{
			if (count($installer_list) > 0)
			{
				return $installer_list;
			}
		}

		// Find absolute path to normal and plugins directories
		$path_list = array(
			AEPlatform::getInstance()->get_installer_images_path()
		);

		// Initialize the array where we store our data
		$installer_list = array();

		// Loop for the paths where engines can be found
		foreach ($path_list as $path)
		{
			if (is_dir($path))
			{
				if (is_readable($path))
				{
					if ($handle = @opendir($path))
					{
						while (false !== $filename = @readdir($handle))
						{
							if ((strtolower(substr($filename, -4)) == '.ini') && @is_file($path . '/' . $filename))
							{
								$data = AEUtilINI::parse_ini_file($path . '/' . $filename, true);

								if ($forDisplay)
								{
									$innerData = reset($data);

									if (array_key_exists('listinoptions', $innerData))
									{
										if ($innerData['listinoptions'] == 0)
										{
											continue;
										}
									}
								}

								foreach ($data as $key => $values)
								{
									$installer_list[$key] = array();

									foreach ($values as $key2 => $value)
									{
										$installer_list[$key][$key2] = $value;
									}
								}
							}
						} // while readdir
						@closedir($handle);
					} // if opendir
				} // if readable
			} // if is_dir
		}

		return $installer_list;
	}

	/**
	 * Returns the JSON representation of the GUI definition and the associated values
	 *
	 * @return   string
	 */
	public static function getJsonGuiDefinition()
	{
		// Initialize the array which will be converted to JSON representation
		$json_array = array(
			'engines'    => array(),
			'installers' => array(),
			'gui'        => array()
		);

		// Get a reference to the configuration
		$configuration = AEFactory::getConfiguration();

		// Get data for all engines
		$engine_types = array('archiver', 'dump', 'scan', 'writer', 'proc');
		foreach ($engine_types as $type)
		{
			$engines = self::getEnginesList($type);

			foreach ($engines as $engine_name => $engine_data)
			{
				// Translate information
				foreach ($engine_data['information'] as $key => $value)
				{
					switch ($key)
					{
						case 'title':
						case 'description':
							$value = AEPlatform::getInstance()->translate($value);
							break;
					}

					$json_array['engines'][$type][$engine_name]['information'][$key] = $value;
				}

				// Process parameters
				$parameters = array();

				foreach ($engine_data['parameters'] as $param_key => $param)
				{
					$param['default'] = $configuration->get($param_key, $param['default'], false);

					foreach ($param as $option_key => $option_value)
					{
						// Translate title, description, enumkeys
						switch ($option_key)
						{
							case 'title':
							case 'description':
							case 'labelempty':
							case 'labelnotempty':
								$param[$option_key] = AEPlatform::getInstance()->translate($option_value);
								break;

							case 'enumkeys':
								$enumkeys = explode('|', $option_value);
								$new_keys = array();
								foreach ($enumkeys as $old_key)
								{
									$new_keys[] = AEPlatform::getInstance()->translate($old_key);
								}
								$param[$option_key] = implode('|', $new_keys);
								break;

							default:

						}
					}

					$parameters[$param_key] = $param;
				}

				// Add processed parameters
				$json_array['engines'][$type][$engine_name]['parameters'] = $parameters;
			}
		}

		// Get data for GUI elements
		$json_array['gui'] = array();
		$groupdefs = self::getGUIGroups();

		foreach ($groupdefs as $group_ini => $definition)
		{
			$group_name = AEPlatform::getInstance()->translate($definition['information']['description']);

			// Skip no-name groups
			if (empty($group_name))
			{
				continue;
			}

			$parameters = array();

			foreach ($definition['parameters'] as $param_key => $param)
			{
				$param['default'] = $configuration->get($param_key, $param['default'], false);

				foreach ($param as $option_key => $option_value)
				{
					// Translate title, description, enumkeys
					switch ($option_key)
					{
						case 'title':
						case 'description':
							$param[$option_key] = AEPlatform::getInstance()->translate($option_value);
							break;

						case 'enumkeys':
							$enumkeys = explode('|', $option_value);
							$new_keys = array();
							foreach ($enumkeys as $old_key)
							{
								$new_keys[] = AEPlatform::getInstance()->translate($old_key);
							}
							$param[$option_key] = implode('|', $new_keys);
							break;

						default:

					}
				}
				$parameters[$param_key] = $param;
			}
			$json_array['gui'][$group_name] = $parameters;
		}

		// Get data for the installers
		$json_array['installers'] = self::getInstallerList(true);

		$json = json_encode($json_array);

		return $json;
	}
}