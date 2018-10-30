<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
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

	public static function has($id)
	{
		return RL_Cache::has($id);
	}

	public static function get($id)
	{
		return RL_Cache::get($id);
	}

	public static function set($id, $data)
	{
		return RL_Cache::set($id, $data);
	}

	public static function read($id)
	{
		return RL_Cache::read($id);
	}

	public static function write($id, $data, $ttl = 0)
	{
		return RL_Cache::write($id, $data, $ttl);
	}
}
