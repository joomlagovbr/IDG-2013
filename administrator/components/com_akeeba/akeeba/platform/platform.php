<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 * @since     3.4
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

require_once 'interface.php';
require_once 'abstract.php';

class AEPlatform
{
	private $_platformObject = null;

	private static $knownPlatformsDirectories = array();

	/**
	 * Implements the Singleton pattern for this clas
	 *
	 * @staticvar AEPlatform $instance The static object instance
	 *
	 * @param string $platform Optional; platform name. Autodetect if blank.
	 *
	 * @return AEPlatformAbstract
	 */
	public static function &getInstance($platform = null)
	{
		static $instance = null;

		if (!is_object($instance))
		{
			$instance = new AEPlatform($platform);
		}

		return $instance;
	}

	/**
	 * Public class constructor
	 *
	 * @param   string $platform Optional; platform name. Leave blank to auto-detect.
	 *
	 * @throws  Exception  When the platform cannot be loaded
	 */
	public function __construct($platform = null)
	{
		if (empty($platform) || is_null($platform))
		{
			$platform = $this->detectPlatform();
		}

		if (empty($platform))
		{
			throw new Exception('Can not find a suitable Akeeba Engine platform for your site');
		}

		$this->_platformObject = $this->loadPlatform($platform);

		if (!is_object($this->_platformObject))
		{
			throw new Exception("Can not load Akeeba Engine platform $platform");
		}
	}

	/**
	 * Auto-detect the suitable platform for this site
	 *
	 * @return  string
	 *
	 * @throws  Exception  When no platform is detected
	 */
	private function detectPlatform()
	{
		$platforms = $this->listPlatforms();

		if (empty($platforms))
		{
			throw new Exception('No Akeeba Engine platform class found');
		}

		$bestPlatform = (object)array(
			'name'     => null,
			'priority' => 0,
		);

		foreach ($platforms as $platform => $path)
		{
			$o = $this->loadPlatform($platform, $path);
			if (is_null($o))
			{
				continue;
			}

			if ($o->isThisPlatform())
			{
				if ($o->priority > $bestPlatform->priority)
				{
					$bestPlatform->priority = $o->priority;
					$bestPlatform->name = $platform;
				}
			}
		}

		return $bestPlatform->name;
	}

	/**
	 * Load a given platform and return the platform object
	 *
	 * @param   string  $platform  Platform name
	 *
	 * @return  AEPlatformAbstract
	 */
	private function &loadPlatform($platform, $path = null)
	{
		if (empty($path))
		{
			if (isset(static::$knownPlatformsDirectories[$platform]))
			{
				$path = static::$knownPlatformsDirectories[$platform];
			}
		}

		if (empty($path))
		{
			$path = dirname(__FILE__) . '/' . $platform;
		}

		$classFile = $path . '/platform.php';
		$className = 'AEPlatform' . ucfirst($platform);

		$null = null;
		if (!file_exists($classFile))
		{
			return $null;
		}

		require_once($classFile);
		$o = new $className;

		return $o;
	}

	/**
	 * Lists available platforms
	 *
	 * @staticvar   array   $platforms   Static cache of the available platforms
	 *
	 * @return  array  The list of available platforms
	 */
	static public function listPlatforms()
	{
		if (empty(static::$knownPlatformsDirectories))
		{
			$basedir = dirname(__FILE__);
			$dh = opendir($basedir);
			while ($file = readdir($dh))
			{
				if (in_array($file, array('.', '..')))
				{
					continue;
				}

				if (is_dir($basedir . '/' . $file))
				{
					static::$knownPlatformsDirectories[$file] = $basedir . '/' . $file;
				}
			}
		}

		return static::$knownPlatformsDirectories;
	}

	public static function addPlatform($slug, $platformDirectory)
	{
		if (empty(static::$knownPlatformsDirectories))
		{
			static::listPlatforms();

			static::$knownPlatformsDirectories[$slug] = $platformDirectory;
		}
	}

	/**
	 * Magic method to proxy all calls to the loaded platform object
	 *
	 * @param   string  $name       The name of the method to call
	 * @param   array   $arguments  The arguments to pass
	 *
	 * @return  mixed  The result of the method being called
	 *
	 * @throws  Exception  When the platform isn't loaded or an non-existent method is called
	 */
	public function __call($name, array $arguments)
	{
		if (is_null($this->_platformObject))
		{
			throw new Exception('Akeeba Engine platform is not loaded');
		}

		if (method_exists($this->_platformObject, $name))
		{
			// Call_user_func_array is ~3 times slower than direct method calls.
			// See the on-line PHP documentation page of call_user_func_array for more information.
			switch (count($arguments))
			{
				case 0 :
					$result = $this->_platformObject->$name();
					break;
				case 1 :
					$result = $this->_platformObject->$name($arguments[0]);
					break;
				case 2:
					$result = $this->_platformObject->$name($arguments[0], $arguments[1]);
					break;
				case 3:
					$result = $this->_platformObject->$name($arguments[0], $arguments[1], $arguments[2]);
					break;
				case 4:
					$result = $this->_platformObject->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
					break;
				case 5:
					$result = $this->_platformObject->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
					break;
				default:
					// Resort to using call_user_func_array for many segments
					$result = call_user_func_array(array($this->_platformObject, $name), $arguments);
			}
			return $result;
		}
		else
		{
			throw new Exception('Method ' . $name . ' not found in Akeeba Platform');
		}
	}

	/**
	 * Magic getter for the properties of the loaded platform
	 *
	 * @param   string  $name  The name of the property to get
	 *
	 * @return  mixed  The value of the property
	 */
	public function __get($name)
	{
		if (isset($this->_platformObject->$name) || property_exists($this->_platformObject, $name))
		{
			return $this->_platformObject->$name;
		}
		else
		{
			$this->_platformObject->$name = null;
			user_error('AEPlatform does not support property ' . $name, E_NOTICE);
		}
	}

	/**
	 * Magic setter for the properties of the loaded platform
	 *
	 * @param   string  $name   The name of the property to set
	 * @param   mixed   $value  The value of the property to set
	 */
	public function __set($name, $value)
	{
		if (isset($this->_platformObject->$name) || property_exists($this->_platformObject, $name))
		{
			$this->_platformObject->$name = $value;
		}
		else
		{
			$this->_platformObject->$name = null;
			user_error('AEPlatform does not support property ' . $name, E_NOTICE);
		}
	}
}