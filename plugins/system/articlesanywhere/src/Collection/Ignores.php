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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection;

use JDatabaseQuery;
use JFactory;
use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class Ignores extends CollectionObject
{
	public function set(JDatabaseQuery $query, $table = 'items', $group = '')
	{
		$this->setState($query, $table, $group);
		$this->setAccess($query, $table, $group);
		$this->setLanguage($query, $table, $group);
	}

	protected function setState(JDatabaseQuery $query, $table = 'items', $group = '')
	{
		$ignore = $this->getState($group);

		if ($ignore)
		{
			return;
		}

		$state = $this->config->get($table . '_state', false) ?: 'published';

		$query->where($this->db->quoteName($table . '.' . $state) . ' = 1');

		if ($table == 'items')
		{
			$now      = JFactory::getDate()->toSql();
			$nullDate = $this->db->getNullDate();

			$query->where('( ' . $this->db->quoteName($table . '.publish_up') . ' <= ' . $this->db->quote($now) . ' )')
				->where('( ' . $this->db->quoteName($table . '.publish_down') . ' = ' . $this->db->quote($nullDate)
					. ' OR ' . $this->db->quoteName($table . '.publish_down') . ' >= ' . $this->db->quote($now) . ' )');
		}
	}

	protected function setAccess(JDatabaseQuery $query, $table = 'items', $group = '')
	{
		$ignore = $this->getAccess($group);

		if ($ignore)
		{
			return;
		}

		$query->where($this->db->quoteName($table . '.access')
			. RL_DB::in(Params::getAuthorisedViewLevels()));
	}

	protected function setLanguage(JDatabaseQuery $query, $table = 'items', $group = '')
	{
		$ignore = $this->getLanguage($group);

		if ($ignore)
		{
			return;
		}

		$query->where($this->db->quoteName($table . '.language')
			. RL_DB::in([JFactory::getLanguage()->getTag(), '*']));
	}

	protected function getState($group = '')
	{
		return $this->getByType('state', $group);
	}

	protected function getAccess($group = '')
	{
		return $this->getByType('access', $group);
	}

	protected function getLanguage($group = '')
	{
		return $this->getByType('language', $group);
	}

	protected function getByType($type = 'state', $group = '')
	{
		$params  = Params::get();
		$ignores = $this->config->getIgnores();

		$suffix = $group ? '_' . $group : '';

		$fallback = $params->{'ignore_' . $type . $suffix} != -1
			? $params->{'ignore_' . $type . $suffix}
			: $params->{'ignore_' . $type};

		$default = isset($ignores[$type])
			? $ignores[$type]
			: $fallback;

		return isset($ignores[$type . $suffix])
			? $ignores[$type . $suffix]
			: $default;
	}
}
