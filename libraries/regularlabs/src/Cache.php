<?php
/**
 * @package         Regular Labs Library
 * @version         18.1.20362
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JFactory;

/**
 * Class Cache
 * @package RegularLabs\Library
 */
class Cache
{
	static $group = 'regularlabs';
	static $cache = [];

	// Is the cached object in the cache memory?
	public static function has($hash)
	{
		return isset(self::$cache[$hash]);
	}

	// Get the cached object from the cache memory
	public static function get($hash)
	{
		if ( ! isset(self::$cache[$hash]))
		{
			return false;
		}

		return is_object(self::$cache[$hash]) ? clone self::$cache[$hash] : self::$cache[$hash];
	}

	// Save the cached object to the cache memory
	public static function set($hash, $data)
	{
		self::$cache[$hash] = $data;

		return $data;
	}

	// Get the cached object from the Joomla cache
	public static function read($hash)
	{
		if (isset(self::$cache[$hash]))
		{
			return self::$cache[$hash];
		}

		$cache = JFactory::getCache(self::$group, 'output');

		return $cache->get($hash);
	}

	// Save the cached object to the Joomla cache
	public static function write($hash, $data, $time_to_life_in_minutes = 0, $force_caching = true)
	{
		self::$cache[$hash] = $data;

		$cache = JFactory::getCache(self::$group, 'output');

		if ($time_to_life_in_minutes)
		{
			// convert ttl to minutes
			$cache->setLifeTime($time_to_life_in_minutes * 60);
		}

		if ($force_caching)
		{
			$cache->setCaching(true);
		}

		$cache->store($data, $hash);

		self::set($hash, $data);

		return $data;
	}
}
