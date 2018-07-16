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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JFactory;

/**
 * Class DB
 * @package RegularLabs\Library
 */
class DB
{
	static $tables = [];

	/**
	 * Check if a table exists in the database
	 *
	 * @param string $table
	 *
	 * @return bool
	 */
	public static function tableExists($table)
	{
		if (isset(self::$tables[$table]))
		{
			return self::$tables[$table];
		}

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

		self::$tables[$table] = ! empty($result);

		return self::$tables[$table];
	}

	/**
	 * Create an IN statement
	 * Reverts to a simple equals statement if array just has 1 value
	 *
	 * @param string|array $value
	 *
	 * @return string
	 */
	public static function in($value)
	{
		if (empty($value) && ! is_array($value))
		{
			return ' = 0';
		}

		$operator = self::getOperator($value);

		$value = JFactory::getDbo()->quote($value);

		if ( ! is_array($value))
		{
			return ' ' . $operator . ' ' . $value;
		}

		if (count($value) == 1)
		{
			return ' ' . $operator . ' ' . reset($value);
		}

		$operator = $operator == '!=' ? 'NOT IN' : 'IN';

		$values = empty($value) ? "''" : implode(',', $value);

		return ' ' . $operator . ' (' . $values . ')';
	}

	public static function getOperator(&$value, $default = '=')
	{
		if (empty($value))
		{
			return $default;
		}

		if (is_array($value))
		{
			$operator = self::getOperatorFromValue($value[0], $default);

			// remove operators from other array values
			foreach ($value as &$val)
			{
				$val = self::removeOperator($val);
			}

			return $operator;
		}

		$operator = self::getOperatorFromValue($value, $default);

		$value = self::removeOperator($value);

		return $operator;
	}

	public static function removeOperator($string)
	{
		$regex = '^' . RegEx::quote(self::getOperators(), 'operator');

		return RegEx::replace($regex, '', $string);
	}

	public static function getOperators()
	{
		return ['!NOT!', '!=', '!', '<>', '<=', '<', '>=', '>', '=', '=='];
	}

	public static function getOperatorFromValue($value, $default = '=')
	{
		$regex = '^' . RegEx::quote(self::getOperators(), 'operator');

		if ( ! RegEx::match($regex, $value, $parts))
		{
			return $default;
		}

		$operator = $parts['operator'];

		switch ($operator)
		{
			case '!':
			case '!NOT!':
				$operator = '!=';
				break;

			case '==':
				$operator = '=';
				break;
		}

		return $operator;
	}

	/**
	 * Create an LIKE statement
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function like($value)
	{
		$db = JFactory::getDbo();

		return ' LIKE ' . $db->quote($value);
	}
}
