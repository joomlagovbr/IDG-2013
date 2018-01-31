<?php
/**
 * @package         Articles Anywhere
 * @version         7.5.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Filters;

use JDatabaseQuery;
use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Library\StringHelper;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\CollectionObject;

defined('_JEXEC') or die;

class Filter extends CollectionObject implements FilterInterface
{
	public function set(JDatabaseQuery $query)
	{
		$class = get_called_class();
		$class = substr($class, strrpos($class, '\\') + 1);

		$filter_type = StringHelper::camelToUnderscore($class);
		$filter      = $this->config->getFilters($filter_type);

		if ( ! $this->config->getComponentName() || empty($filter))
		{
			return;
		}

		$this->setByIncludeType($query, $filter, 'include');
		$this->setByIncludeType($query, $filter, 'exclude');
	}

	protected function setByIncludeType(JDatabaseQuery &$query, $filter, $include_type = 'include')
	{
		if (empty($filter[$include_type]))
		{
			return;
		}

		$names = $filter[$include_type];
		$this->setFilter($query, $names, $include_type);
	}

	public function setFilter(JDatabaseQuery $query, $names = [], $include_type = 'include')
	{
		return;
	}

	protected function setFiltersFromNames(JDatabaseQuery &$query, $table, $names = [], $include = true)
	{
		$conditions = $this->getConditionsFromNames($table, $names, $include);

		if (empty($conditions))
		{
			return;
		}

		if (count($conditions) < 2)
		{
			$query->where($conditions);

			return;
		}

		$glue = $include ? ' OR ' : ' AND ';
		$query->where('(' . implode($glue, $conditions) . ')');
	}

	protected function getConditionsFromNames($table, $names = [], $include = true)
	{
		list($ids, $titles, $likes) = $this->getIdAndNameMatches($names);

		$conditions = [];

		if ( ! empty($ids))
		{
			$conditions[] = $this->config->getId($table)
				. RL_DB::in($ids, $include);
		}

		if ( ! empty($titles))
		{
			$conditions[] = $this->config->getTitle($table)
				. RL_DB::in($titles, $include);
			$conditions[] = $this->config->getAlias($table)
				. RL_DB::in($titles, $include);
		}

		if ( ! empty($likes))
		{
			foreach ($likes as $like)
			{
				$conditions[] = $this->config->getTitle($table)
					. RL_DB::like($like, $include);
				$conditions[] = $this->config->getAlias($table)
					. RL_DB::like($like, $include);
			}
		}

		return $conditions;
	}
}
