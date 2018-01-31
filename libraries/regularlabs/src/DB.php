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
 * Class DB
 * @package RegularLabs\Library
 */
class DB
{
	/**
	 * Check if a table exists in the database
	 *
	 * @param string $table
	 *
	 * @return bool
	 */
	public static function tableExists($table)
	{
		$db = JFactory::getDbo();

		if (strpos($table, '#__') === 0)
		{
			$table = $db->getPrefix() . substr($table, 3);
		}

		if (strpos($table, $db->getPrefix()) !== 0)
		{
			$table = $db->getPrefix() . $table;
		}

		$query = 'SHOW TABLES LIKE ' . $db->quote($table);
		$db->setQuery($query);
		$result = $db->loadResult();

		return ! empty($result);
	}

	/**
	 * Create an IN statement
	 * Reverts to a simple equals statement if array just has 1 value
	 *
	 * @param string|array $value
	 * @param bool         $include
	 *
	 * @return string
	 */
	public static function in($value, $include = true)
	{
		$db = JFactory::getDbo();

		$value = $db->quote($value);

		if (empty($value) && ! is_array($value))
		{
			return ' = 0';
		}

		$operator = $include ? ' = ' : ' != ';

		if ( ! is_array($value))
		{
			return $operator . $value;
		}

		if (count($value) == 1)
		{
			return $operator . reset($value);
		}

		$operator = $include ? ' IN ' : ' NOT IN ';
		$values   = empty($value) ? "''" : implode(',', $value);

		return $operator . '(' . $values . ')';
	}

	/**
	 * Create an LIKE statement
	 *
	 * @param string $value
	 * @param bool   $include
	 *
	 * @return string
	 */
	public static function like($value, $include = true)
	{
		$db = JFactory::getDbo();

		$operator = $include ? ' LIKE ' : ' NOT LIKE ';

		return $operator . $db->quote($value);
	}

}
