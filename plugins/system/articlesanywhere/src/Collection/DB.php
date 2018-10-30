<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection;

use JDatabaseQuery;
use JFactory;
use RegularLabs\Library\Cache as RL_Cache;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class DB
{
	static $query_time;

	public static function getResults(JDatabaseQuery $query, $method = 'loadColumn', $arguments = [], $limit = 0, $offset = 0)
	{
		if ( ! $query)
		{
			return null;
		}

		$cache_id = self::getQueryId($query, [$method, $arguments, $limit, $offset]);

		if ($cache_id && $result = RL_Cache::read($cache_id))
		{
			return $result;
		}

		$db = JFactory::getDbo();

		$db->setQuery($query, $offset, $limit);

		$result = call_user_func_array([$db, $method], $arguments);

		if ( ! $cache_id)
		{
			return $result;
		}

		return RL_Cache::write($cache_id, $result, self::getQueryTime(), false);
	}

	private static function getQueryTime()
	{
		if ( ! is_null(self::$query_time))
		{
			return self::$query_time;
		}

		self::$query_time = (int) Params::get()->query_cache_time ?: JFactory::getConfig()->get('cachetime');

		return self::$query_time;
	}

	private static function getQueryId(JDatabaseQuery $query, $arguments)
	{
		if ( ! Params::get()->use_query_cache)
		{
			return false;
		}

		$query = (string) $query;

		// Don't cache queries with random ordering
		if (strpos($query, 'RAND()') !== false)
		{
			return false;
		}

		return 'getResults' . md5(json_encode([$query, $arguments]));
	}
}
