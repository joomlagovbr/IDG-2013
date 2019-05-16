<?php
/**
 * @package         Articles Anywhere
 * @version         9.2.0
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Filters;

defined('_JEXEC') or die;

use JDatabaseQuery;
use Joomla\CMS\Date\Date as JDate;
use Joomla\CMS\Factory as JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\CollectionObject;
use RegularLabs\Plugin\System\ArticlesAnywhere\Helpers\ValueHelper;

class Filter extends CollectionObject implements FilterInterface
{
	public function set(JDatabaseQuery $query)
	{
		$class = get_called_class();
		$class = substr($class, strrpos($class, '\\') + 1);

		$group   = StringHelper::camelToUnderscore($class);
		$filters = $this->config->getFilters($group);

		if (empty($filters) && is_array($filters))
		{
			$this->setConditionsWhenEmpty($query);

			return;
		}

		if (empty($filters))
		{
			return;
		}

		$this->setFilter($query, $filters);
	}

	public function setConditionsWhenEmpty(JDatabaseQuery $query)
	{
		$query->where('0');
	}

	public function setFilter(JDatabaseQuery $query, $filters = [])
	{
		return;
	}

	protected function setFiltersFromNames(JDatabaseQuery &$query, $table, $names = [])
	{
		$keys             = [
			$this->config->getTitle($table, false, $table),
			$this->config->getAlias($table, false, $table),
		];
		$keys_if_nummeric = [
			$this->config->getId($table, false, $table),
		];

		$conditions = $this->getConditionsFromValues($keys, $names, $keys_if_nummeric);

		if (empty($conditions))
		{
			return;
		}

		$query->where($conditions);
	}

	protected function getConditionsFromValues($keys, $values = [], $keys_if_nummeric = [])
	{
		$values = RL_Array::toArray($values);

		if (empty($values))
		{
			$values = [''];
		}

		$conditions = [];

		foreach ($values as $value)
		{
			$this->addConditionByValue($conditions, $keys, $value, $keys_if_nummeric);
		}

		$operator = RL_DB::getOperatorFromValue($values[0]);

		if (empty($conditions))
		{
			return $operator == '!=' ? '1' : '0';
		}

		$glue = $operator == '!=' ? ' AND ' : ' OR ';

		return '((' . implode(') ' . $glue . ' (', $conditions) . '))';
	}

	protected function addConditionByValue(&$conditions, $keys = [], $value = '', $keys_if_nummeric = [])
	{
		$keys             = RL_Array::toArray($keys);
		$keys_if_nummeric = RL_Array::toArray($keys_if_nummeric);

		$check_value = RL_DB::removeOperator($value);

		if (is_numeric($check_value) && ! empty($keys_if_nummeric))
		{
			$keys = $keys_if_nummeric;
		}

		foreach ($keys as $key)
		{
			$conditions[] = $this->getConditionByKey($key, $value);
		}
	}

	protected function getConditionByKey($key, $value = '')
	{
		$operator    = RL_DB::getOperator($value);
		$check_value = $operator == '!=' ? '!' . $value : $value;

		switch ($check_value)
		{
			// Should be empty or null
			case '':
				return '('
					. $this->db->quoteName($key) . ' = ' . $this->db->quote('')
					. ' OR '
					. $this->db->quoteName($key) . ' IS NULL'
					. ')';

			// Should be empty but not null
			case '!*':
			case '!+':
				return '('
					. $this->db->quoteName($key) . ' = ' . $this->db->quote('')
					. ' AND '
					. $this->db->quoteName($key) . ' IS NOT NULL'
					. ')';

			// Should not be null
			case '*':
				return $this->db->quoteName($key) . ' IS NOT NULL';

			// Should not be empty
			case '+':
			case '!':
				return '('
					. $this->db->quoteName($key) . ' != ' . $this->db->quote('')
					. ' AND '
					. $this->db->quoteName($key) . ' IS NOT NULL'
					. ')';

			// Should match the value
			default:
				// Should do a LIKE match
				$not_null = '';

				if ($operator == '!=')
				{
					$not_null = $this->db->quoteName($key) . ' IS NULL OR ';
				}

				$where = $this->getConditionDefault($key, $value, $operator);

				return $not_null . $where;
		}
	}

	public function getConditionDefault($key, &$value, $operator)
	{
		if (strpos($value, '*') !== false)
		{
			return $this->db->quoteName($key) . RL_DB::like($operator . $value);
		}

//		$temp = $value;
//		if ($where = $this->getWhereIfDateValue($key, $temp, $operator))
//		{
//			echo "\n==========================\n";
//			print_r($value);
//			echo "\n--------------------------\n";
//			print_r($temp);
//			echo "\n--------------------------\n";
//			print_r($where);
//			echo "\n==========================\n";
//		}

		if ($where = $this->getWhereIfDateValue($key, $value, $operator))
		{
			return $where;
		}

		// Special case for if value is possibly a year or year-month format
		if (RL_RegEx::match('^[0-9]{4}(?<month>-[0-9]{2})?$', $value, $match))
		{
			$format = isset($match['month']) ? '%Y-%m' : '%Y';
			$regex  = '^[0-9]{4}-[0-9]{2}-[0-9]{2}( [0-9]{2}:[0-9]{2}:[0-9]{2})?$';
			$select = 'DATE_FORMAT(' . $this->db->quoteName($key) . ',' . $this->db->quote($format) . ')';

			$if   = ' YEAR(' . $this->db->quoteName($key) . ')'
				. ' AND ' . $this->db->quoteName($key) . ' REGEXP ' . $this->db->quote($regex);
			$then = $select . RL_DB::in($operator . $value, true);
			$else = $this->db->quoteName($key) . RL_DB::in($operator . $value, true);

			return '(CASE WHEN ' . $if . ' THEN ' . $then . ' ELSE ' . $else . ' END)';
		}

		return $this->db->quoteName($key) . RL_DB::in($operator . $value, true);
	}

	public function getWhereIfDateValue($key, &$value, $operator)
	{
		if ($date = ValueHelper::placeholderToDate($value))
		{
			if ( ! ValueHelper::isDateValue($date))
			{
				$value = $date;

				return false;
			}

			$value = ValueHelper::placeholderToDate($value, false);
		}

		if ( ! ValueHelper::isDateValue($value))
		{
			return false;
		}

		list($from, $to) = $this->getFromAndToDates($value);

		if ( ! $to)
		{
			return $this->db->quoteName($key) . ' ' . $operator . ' ' . $from;
		}

		switch ($operator)
		{
			case '<':
				return $this->db->quoteName($key) . ' < ' . $from;

			case '>':
				return $this->db->quoteName($key) . ' >= ' . $to;

			case '<=':
				return $this->db->quoteName($key) . ' < ' . $to;

			case '>=':
				return $this->db->quoteName($key) . ' >= ' . $from;

			case '!':
			case '!=':
				return '('
					. $this->db->quoteName($key) . ' < ' . $from
					. ' OR ' . $this->db->quoteName($key) . ' > ' . $to
					. ')';

			default:
				return '('
					. $this->db->quoteName($key) . ' >= ' . $from
					. ' AND ' . $this->db->quoteName($key) . ' < ' . $to
					. ')';
		}
	}

	public function getFromAndToDates($value)
	{
		if (strpos($value, ' to ') !== false)
		{
			$value = explode(' to ', $value);
			list($from, $ignore) = $this->getFromAndToDates($value[0]);
			list($ignore, $to) = $this->getFromAndToDates($value[1]);

			return [$from, $to];
		}

		list($interval, $format) = $this->getIntervalAndFormatFromDate($value);

		$date = new JDate($value, JFactory::getConfig()->get('offset', 'UTC'));

		$from = $this->db->quote($date->format($format));
		$to   = $interval ? $this->db->quote($date->modify('1' . $interval)->format($format)) : '';

		return [$from, $to];
	}

	public function getIntervalAndFormatFromDate($value)
	{
		if ( ! RL_RegEx::match(
			'^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}(?<hours> [0-9]{1,2}(?<minutes>:[0-9]{1,2}(?<seconds>\:[0-9]{1,2})?)?)?$',
			$value, $datetime_parts)
		)
		{
			return [false, 'Y-m-d H:i:s'];
		}

		switch (true)
		{
			case (isset($datetime_parts['seconds'])):
				return ['seconds', 'Y-m-d H:i:s'];

			case (isset($datetime_parts['minutes'])):
				return ['minutes', 'Y-m-d H:i:00'];

			case (isset($datetime_parts['hours'])):
				return ['hours', 'Y-m-d H:00:00'];

			default:
				return ['days', 'Y-m-d H:00:00'];
		}
	}
}
