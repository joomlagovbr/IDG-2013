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
 * Class DateSeason
 * @package RegularLabs\Library\Condition
 */
class DateSeason
	extends Date
{
	public function pass()
	{
		$season = self::getSeason($this->date, $this->params->hemisphere);

		return $this->passSimple($season);
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
}
