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

/* @DEPRECATED */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class RLAssignmentsDateTime extends RLAssignment
{
	var $timezone = null;
	var $dates    = [];

	public function passDate()
	{
		if ( ! $this->params->publish_up && ! $this->params->publish_down)
		{
			// no date range set
			return ($this->assignment == 'include');
		}

		require_once dirname(__DIR__) . '/text.php';

		RLText::fixDate($this->params->publish_up);
		RLText::fixDate($this->params->publish_down);

		$now  = $this->getNow();
		$up   = $this->getDate($this->params->publish_up);
		$down = $this->getDate($this->params->publish_down);

		if (isset($this->params->recurring) && $this->params->recurring)
		{
			if ( ! (int) $this->params->publish_up || ! (int) $this->params->publish_down)
			{
				// no date range set
				return ($this->assignment == 'include');
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
				return ($this->assignment == 'include');
			}

			// outside date range
			return $this->pass(false);
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
			return $this->pass(false);
		}

		// pass
		return ($this->assignment == 'include');
	}

	public function passSeasons()
	{
		$season = self::getSeason($this->date, $this->params->hemisphere);

		return $this->passSimple($season);
	}

	public function passMonths()
	{
		$month = $this->date->format('m', true); // 01 (for January) through 12 (for December)

		return $this->passSimple((int) $month);
	}

	public function passDays()
	{
		$day = $this->date->format('N', true); // 1 (for Monday) though 7 (for Sunday )

		return $this->passSimple($day);
	}

	public function passTime()
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
				return $this->pass(true);
			}

			return $this->pass(false);
		}

		// publish down is after publish up (simple time span)
		// current time should be:
		// - after publish up
		// - AND before publish down
		if ($now >= $up && $now < $down)
		{
			return $this->pass(true);
		}

		return $this->pass(false);
	}

	private function getSeason(&$d, $hemisphere = 'northern')
	{
		// Set $date to today
		$date = strtotime($d->format('Y-m-d H:i:s', true));

		// Get year of date specified
		$date_year = $d->format('Y', true); // Four digit representation for the year

		// Specify the season names
		$season_names = ['winter', 'spring', 'summer', 'fall'];

		// Declare season date ranges
		switch (strtolower($hemisphere))
		{
			case 'southern':
				if (
					$date < strtotime($date_year . '-03-21')
					|| $date >= strtotime($date_year . '-12-21')
				)
				{
					return $season_names[2]; // Must be in Summer
				}

				if ($date >= strtotime($date_year . '-09-23'))
				{
					return $season_names[1]; // Must be in Spring
				}

				if ($date >= strtotime($date_year . '-06-21'))
				{
					return $season_names[0]; // Must be in Winter
				}

				if ($date >= strtotime($date_year . '-03-21'))
				{
					return $season_names[3]; // Must be in Fall
				}
				break;
			case 'australia':
				if (
					$date < strtotime($date_year . '-03-01')
					|| $date >= strtotime($date_year . '-12-01')
				)
				{
					return $season_names[2]; // Must be in Summer
				}

				if ($date >= strtotime($date_year . '-09-01'))
				{
					return $season_names[1]; // Must be in Spring
				}

				if ($date >= strtotime($date_year . '-06-01'))
				{
					return $season_names[0]; // Must be in Winter
				}

				if ($date >= strtotime($date_year . '-03-01'))
				{
					return $season_names[3]; // Must be in Fall
				}
				break;
			default: // northern
				if (
					$date < strtotime($date_year . '-03-21')
					|| $date >= strtotime($date_year . '-12-21')
				)
				{
					return $season_names[0]; // Must be in Winter
				}

				if ($date >= strtotime($date_year . '-09-23'))
				{
					return $season_names[3]; // Must be in Fall
				}

				if ($date >= strtotime($date_year . '-06-21'))
				{
					return $season_names[2]; // Must be in Summer
				}

				if ($date >= strtotime($date_year . '-03-21'))
				{
					return $season_names[1]; // Must be in Spring
				}
				break;
		}

		return 0;
	}

	private function getNow()
	{
		return strtotime($this->date->format('Y-m-d H:i:s', true));
	}

	private function getDate($date = '')
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
