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
 * Class DateDay
 * @package RegularLabs\Library\Condition
 */
class DateDay
	extends Date
{
	public function pass()
	{
		$day = $this->date->format('N', true); // 1 (for Monday) though 7 (for Sunday )

		return $this->passSimple($day);
	}
}
