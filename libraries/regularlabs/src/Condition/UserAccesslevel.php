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
 * Class UserAccesslevel
 * @package RegularLabs\Library\Condition
 */
class UserAccesslevel
	extends User
{
	public function pass()
	{
		$user = JFactory::getUser();

		$levels = $user->getAuthorisedViewLevels();

		$this->selection = $this->convertAccessLevelNamesToIds($this->selection);

		return $this->passSimple($levels);
	}

	private function convertAccessLevelNamesToIds($selection)
	{
		$names = [];

		foreach ($selection as $i => $level)
		{
			if (is_numeric($level))
			{
				continue;
			}

			unset($selection[$i]);

			$names[] = strtolower(str_replace(' ', '', $level));
		}

		if (empty($names))
		{
			return $selection;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from('#__viewlevels')
			->where('LOWER(REPLACE(' . $db->quoteName('title') . ', " ", ""))'
				. RL_DB::in($names));
		$db->setQuery($query);

		$level_ids = $db->loadColumn();

		return array_unique(array_merge($selection, $level_ids));
	}
}
