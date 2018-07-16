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

use RegularLabs\Library\Date as RL_Date;

/**
 * Class DateDate
 * @package RegularLabs\Library\Condition
 */
class DateDate
	extends Date
{
	public function pass()
	{
		if ( ! $this->params->publish_up && ! $this->params->publish_down)
		{
			// no date range set
			return ($this->include_type == 'include');
		}

		RL_Date::fix($this->params->publish_up);
		RL_Date::fix($this->params->publish_down);

		$now  = $this->getNow();
		$up   = $this->getDate($this->params->publish_up);
		$down = $this->getDate($this->params->publish_down);

		if (isset($this->params->recurring) && $this->params->recurring)
		{
			if ( ! (int) $this->params->publish_up || ! (int) $this->params->publish_down)
			{
				// no date range set
				return ($this->include_type == 'include');
			}

			$up   = strtotime(date('Y') . $up->format('-m-d H:i:s', true));
			$down = strtotime(date('Y') . $down->format('-m-d H:i:s', true));

			// pass:
			// 1) now is between up and down
			// 2) up is later in year than down and:
			// 2a) now is after up
			// 2b) now is before down
			if (
				($up < $now && $down > $now)
				|| ($up > $down
					&& (
						$up < $now
						|| $down > $now
					)
				)
			)
			{
				return ($this->include_type == 'include');
			}

			// outside date range
			return $this->_(false);
		}

		if (
			(
				(int) $this->params->publish_up
				&& strtotime($up->format('Y-m-d H:i:s', true)) > $now
			)
			|| (
				(int) $this->params->publish_down
				&& strtotime($down->format('Y-m-d H:i:s', true)) < $now
			)
		)
		{
			// outside date range
			return $this->_(false);
		}

		// pass
		return ($this->include_type == 'include');
	}
}
