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

/* @DEPRECATED */

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use RegularLabs\Library\Cache as RL_Cache;

class RLCache
{
	static $cache = [];

	public static function has($hash)
	{
		return RL_Cache::has($hash);
	}

	public static function get($hash)
	{
		return RL_Cache::get($hash);
	}

	public static function set($hash, $data)
	{
		return RL_Cache::set($hash, $data);
	}

	public static function read($hash)
	{
		return RL_Cache::read($hash);
	}

	public static function write($hash, $data, $ttl = 0)
	{
		return RL_Cache::write($hash, $data, $ttl);
	}
}
