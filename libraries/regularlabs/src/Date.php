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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use DateTimeZone;
use JFactory;

class Date
{
	/**
	 * Convert string to a correct date format ('00-00-00 00:00:00' or '00-00-00') or null
	 *
	 * @param string $date
	 *
	 * @return null|string
	 */
	public static function fix($date)
	{
		if ( ! $date)
		{
			return null;
		}

		$date = trim($date);

		// Check if date has correct syntax: 00-00-00 00:00:00
		// If so, the date format is correct
		if ( ! RegEx::match('^[0-9]+-[0-9]+-[0-9]+( [0-9][0-9]:[0-9][0-9]:[0-9][0-9])?$', $date))
		{
			return $date;
		}

		// Check if date has syntax: 00-00-00 00:00
		// If so, it is missing the seconds, so add :00 (seconds)
		if (RegEx::match('^[0-9]+-[0-9]+-[0-9]+ [0-9][0-9]:[0-9][0-9]$', $date))
		{
			return $date . ':00';
		}

		// Check if date has a prepending date syntax: 00-00-00
		// If so, it is missing a correct time time, so add 00:00:00 (hours, minutes, seconds)
		if (RegEx::match('^([0-9]+-[0-9]+-[0-9]+)$', $date, $match))
		{
			return $match[1] . ' 00:00:00';
		}

		// Date format is not correct, so return null
		return null;
	}

	/**
	 * Applies offset to a date
	 *
	 * @param string $date
	 * @param string $timezone
	 */
	public static function applyTimezone(&$date, $timezone = '')
	{
		if ($date <= 0)
		{
			$date = 0;

			return;
		}

		$timezone = $timezone ?: JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset'));

		$date = JFactory::getDate($date, $timezone);
		$date->setTimezone(new DateTimeZone('UTC'));

		$date = $date->format('Y-m-d H:i:s', true, false);
	}

	/**
	 * Convert string with 'date' format to 'strftime' format
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public static function strftimeToDateFormat($format)
	{
		if (strpos($format, '%') === false)
		{
			return $format;
		}

		return strtr((string) $format, self::getStrftimeToDateFormats());
	}

	/**
	 * Convert string with 'date' format to 'strftime' format
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public static function dateToStrftimeFormat($format)
	{
		return strtr((string) $format, self::getDateToStrftimeFormats());
	}

	private static function getStrftimeToDateFormats()
	{
		return [
			// Day
			'%d'  => 'd',
			'%a'  => 'D',
			'%#d' => 'j',
			'%A'  => 'l',
			'%u'  => 'N',
			'%w'  => 'w',
			'%j'  => 'z',
			// Week
			'%V'  => 'W',
			// Month
			'%B'  => 'F',
			'%m'  => 'm',
			'%b'  => 'M',
			// Year
			'%G'  => 'o',
			'%Y'  => 'Y',
			'%y'  => 'y',
			// Time
			'%P'  => 'a',
			'%p'  => 'A',
			'%l'  => 'g',
			'%I'  => 'h',
			'%H'  => 'H',
			'%M'  => 'i',
			'%S'  => 's',
			// Timezone
			'%z'  => 'O',
			'%Z'  => 'T',
			// Full Date / Time
			'%s'  => 'U',
		];
	}

	private static function getDateToStrftimeFormats()
	{
		return [
			// Day - no strf eq : S
			'd'  => '%d',
			'D'  => '%a',
			'jS' => '%#d[TH]',
			'j'  => '%#d',
			'l'  => '%A',
			'N'  => '%u',
			'w'  => '%w',
			'z'  => '%j',
			// Week - no date eq : %U, %W
			'W'  => '%V',
			// Month - no strf eq : n, t
			'F'  => '%B',
			'm'  => '%m',
			'M'  => '%b',
			// Year - no strf eq : L; no date eq : %C, %g
			'o'  => '%G',
			'Y'  => '%Y',
			'y'  => '%y',
			// Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
			'a'  => '%P',
			'A'  => '%p',
			'g'  => '%l',
			'h'  => '%I',
			'H'  => '%H',
			'i'  => '%M',
			's'  => '%S',
			// Timezone - no strf eq : e, I, P, Z
			'O'  => '%z',
			'T'  => '%Z',
			// Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x
			'U'  => '%s',
		];
	}
}
