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

		$group   = StringHelper::camelToUnderscore($class);
		$filters = $this->config->getFilters($group);

		if ( ! $this->config->getComponentName() || empty($filters))
		{
			return;
		}

		$this->setFilter($query, $filters);
	}

	public function setFilter(JDatabaseQuery $query, $filters = [])
	{
		return;
	}

	protected function setFiltersFromNames(JDatabaseQuery &$query, $table, $names = [])
	{
		$conditions = $this->getConditionsFromNames($table, $names);

		if (empty($conditions))
		{
			return;
		}

		if (count($conditions) < 2)
		{
			$query->where($conditions);

			return;
		}

		$operator = RL_DB::getOperatorFromValue($names[0]);

		$glue = $operator == '!=' ? ' AND ' : ' OR ';

		$query->where('(' . implode($glue, $conditions) . ')');
	}

	protected function getConditionsFromNames($table, $names = [])
	{
		list($ids, $titles, $likes) = $this->getIdAndNameMatches($names);

		$conditions = [];

		if ( ! empty($ids))
		{
			$conditions[] = $this->config->getId($table)
				. RL_DB::in($ids);
		}

		if ( ! empty($titles))
		{
			$conditions[] = $this->config->getTitle($table)
				. RL_DB::in($titles);
			$conditions[] = $this->config->getAlias($table)
				. RL_DB::in($titles);
		}

		if ( ! empty($likes))
		{
			foreach ($likes as $like)
			{
				$conditions[] = $this->config->getTitle($table)
					. RL_DB::like($like);
				$conditions[] = $this->config->getAlias($table)
					. RL_DB::like($like);
			}
		}

		return $conditions;
	}
}
