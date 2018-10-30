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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\DB as RL_DB;

/**
 * Class UserGrouplevel
 * @package RegularLabs\Library\Condition
 */
class UserGrouplevel
	extends User
{
	public function pass()
	{
		$user = JFactory::getUser();

		if ( ! empty($user->groups))
		{
			$groups = array_values($user->groups);
		}
		else
		{
			$groups = $user->getAuthorisedGroups();
		}

		if ( ! $this->params->match_all && $this->params->inc_children)
		{
			$this->setUserGroupChildrenIds();
		}

		$this->selection = $this->convertUsergroupNamesToIds($this->selection);

		if ($this->params->match_all)
		{
			return $this->passMatchAll($groups);
		}

		return $this->passSimple($groups);
	}

	private function passMatchAll($groups)
	{
		$pass = ! array_diff($this->selection, $groups) && ! array_diff($groups, $this->selection);

		return $this->_($pass);
	}

	private function convertUsergroupNamesToIds($selection)
	{
		$names = [];

		foreach ($selection as $i => $group)
		{
			if (is_numeric($group))
			{
				continue;
			}

			unset($selection[$i]);

			$names[] = strtolower(str_replace(' ', '', $group));
		}

		if (empty($names))
		{
			return $selection;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from('#__usergroups')
			->where('LOWER(REPLACE(' . $db->quoteName('title') . ', " ", ""))'
				. RL_DB::in($names));
		$db->setQuery($query);

		$group_ids = $db->loadColumn();

		return array_unique(array_merge($selection, $group_ids));
	}

	private function setUserGroupChildrenIds()
	{
		$children = $this->getUserGroupChildrenIds($this->selection);

		if ($this->params->inc_children == 2)
		{
			$this->selection = $children;

			return;
		}

		$this->selection = array_merge($this->selection, $children);
	}

	private function getUserGroupChildrenIds($groups)
	{
		$children = [];

		$db = JFactory::getDbo();

		foreach ($groups as $group)
		{
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__usergroups'))
				->where($db->quoteName('parent_id') . ' = ' . (int) $group);
			$db->setQuery($query);

			$group_children = $db->loadColumn();

			if (empty($group_children))
			{
				continue;
			}

			$children = array_merge($children, $group_children);

			$group_grand_children = $this->getUserGroupChildrenIds($group_children);

			if (empty($group_grand_children))
			{
				continue;
			}

			$children = array_merge($children, $group_grand_children);
		}

		$children = array_unique($children);

		return $children;
	}
}
