<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

/**
 * A compact class to load Joomla!'s Global Configuration options
 * @author Nicholas
 */
class AEUtilJconfig
{
	/**
	 * Returns the value of a Joomla! Global Configuration option
	 *
	 * @param    string $key     The name of the variable to return
	 * @param    mixed  $default Default value to return if the variable doesn't exist
	 *
	 * @return    mixed    The variable's contents
	 */
	public static function getValue($key, $default = null)
	{
		if (!class_exists('JConfig'))
		{
			if (defined('JPATH_CONFIGURATION'))
			{
				require_once JPATH_CONFIGURATION . '/configuration.php';
			}
			else
			{
				require_once JPATH_SITE . '/configuration.php';
			}
		}
		$config = new JConfig;

		$class_vars = get_class_vars('JConfig');
		if (array_key_exists($key, $class_vars))
		{
			return $class_vars[$key];
		}
		else
		{
			return $default;
		}
	}
}