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

/**
 * Class DateTime
 * @package RegularLabs\Library\Condition
 */
class DateTime
	extends Date
{
	public function pass()
	{
		$now  = $this->getNow();
		$up   = strtotime($this->date->format('Y-m-d ', true) . $this->params->publish_up);
		$down = strtotime($this->date->format('Y-m-d ', true) . $this->params->publish_down);

		if ($up > $down)
		{
			// publish up is after publish down (spans midnight)
			// current time should be:
			// - after publish up
			// - OR before publish down
			if ($now >= $up || $now < $down)
			{
				return $this->_(true);
			}

			return $this->_(false);
		}

		// publish down is after publish up (simple time span)
		// current time should be:
		// - after publish up
		// - AND before publish down
		if ($now >= $up && $now < $down)
		{
			return $this->_(true);
		}

		return $this->_(false);
	}
}
