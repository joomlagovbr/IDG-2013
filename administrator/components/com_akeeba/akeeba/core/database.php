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
 * A utility class to return a database connection object
 */
class AECoreDatabase extends AEAbstractObject
{
	/**
	 * Returns a database connection object. It caches the created objects for future use.
	 *
	 * @param array $options Options to use when instanciating the database connection
	 *
	 * @return AEAbstractDriver
	 */
	public static function &getDatabase($options, $unset = false)
	{
		static $instances;

		if (!isset($instances))
		{
			$instances = array();
		}

		$signature = serialize($options);

		if ($unset)
		{
			if (!empty($instances[$signature]))
			{
				$db = $instances[$signature];
				$db = null;
				unset($instances[$signature]);
			}
			$null = null;

			return $null;
		}

		if (empty($instances[$signature]))
		{
			$driver = array_key_exists('driver', $options) ? $options['driver'] : '';
			$select = array_key_exists('select', $options) ? $options['select'] : true;
			$database = array_key_exists('database', $options) ? $options['database'] : null;

			$driver = preg_replace('/[^A-Z0-9_\.-]/i', '', $driver);
			if (empty($driver))
			{
				// No driver specified; try to guess
				$default_signature = serialize(AEPlatform::getInstance()->get_platform_database_options());
				if ($signature == $default_signature)
				{
					$driver = AEPlatform::getInstance()->get_default_database_driver(true);
				}
				else
				{
					$driver = AEPlatform::getInstance()->get_default_database_driver(false);
				}
			}
			else
			{
				// Make sure a full driver name was given
				if (substr($driver, 0, 2) != 'AE')
				{
					$driver = 'AEDriver' . ucfirst($driver);
				}
			}

			$instances[$signature] = new $driver($options);
		}

		return $instances[$signature];
	}

	public static function unsetDatabase($options)
	{
		self::getDatabase($options, true);
	}
}