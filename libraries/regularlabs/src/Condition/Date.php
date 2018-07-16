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

use DateTimeZone;
use JFactory;

/**
 * Class Date
 * @package RegularLabs\Library\Condition
 */
abstract class Date
	extends \RegularLabs\Library\Condition
{
	var $timezone = null;
	var $dates    = [];

	public function getNow()
	{
		return strtotime($this->date->format('Y-m-d H:i:s', true));
	}

	public function getDate($date = '')
	{
		$id = 'date_' . $date;

		if (isset($this->dates[$id]))
		{
			return $this->dates[$id];
		}

		$this->dates[$id] = JFactory::getDate($date);

		if (empty($this->params->ignore_time_zone))
		{
			$this->dates[$id]->setTimeZone($this->getTimeZone());
		}

		return $this->dates[$id];
	}

	private function getTimeZone()
	{
		if ( ! is_null($this->timezone))
		{
			return $this->timezone;
		}

		$this->timezone = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));

		return $this->timezone;
	}
}
