<?php
/**
 * @package         Articles Anywhere
 * @version         9.2.0
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Helpers;

use Joomla\CMS\Date\Date as JDate;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\Date as RL_Date;
use RegularLabs\Library\RegEx as RL_RegEx;

defined('_JEXEC') or die;

class ValueHelper
{
	public static function placeholderToDate($value, $apply_offset = true)
	{
		if (in_array($value, [
			'NOW',
			'now()',
			'JFactory::getDate()',
		]))
		{
			if ( ! $apply_offset)
			{
				return date('Y-m-d H:i:s', strtotime('now'));
			}

			$date = new JDate('now', JFactory::getConfig()->get('offset', 'UTC'));

			return $date->format('Y-m-d H:i:s');
		}

		if (strpos($value, ' to ') !== false)
		{
			$value = explode(' to ', $value);
			$from  = self::placeholderToDate($value[0], $apply_offset) ?: $value[0];
			$to    = self::placeholderToDate($value[1], $apply_offset) ?: $value[1];

			if ( ! $from || ! $to)
			{
				return false;
			}

			return $from . ' to ' . $to;
		}

		$regex = '^date\(\s*'
			. '(?:\'(?<datetime>.*?)\')?'
			. '(?:\\\\?,\s*\'(?<format>.*?)\')?'
			. '\s*\)$';

		if ( ! RL_RegEx::match($regex, $value, $match))
		{
			return false;
		}

		$datetime = ! empty($match['datetime']) ? $match['datetime'] : 'now';
		$format   = ! empty($match['format']) ? $match['format'] : '';

		if (empty($format))
		{
			$time   = date('His', strtotime($datetime));
			$format = (int) $time ? 'Y-m-d H:i:s' : 'Y-m-d';
		}

		if ( ! $apply_offset)
		{
			return date($format, strtotime($datetime));
		}

		$date = new JDate(strtotime($datetime), JFactory::getConfig()->get('offset', 'UTC'));

		return $date->format($format);
	}

	public static function isDateValue($value)
	{
		// Check if string could be a date

		if (is_array($value))
		{
			return false;
		}

		if (strpos($value, ' to ') !== false)
		{
			$value = explode(' to ', $value);

			return self::isDateValue($value[0]) && self::isDateValue($value[1]);
		}

		if (
			// Dates must contain a '-' and not letters
			(strpos($value, '-') == false)
			|| RL_RegEx::match('^[a-z]', $value)
			// Start with Y-m-d format
			|| ! RL_RegEx::match('^[0-9]{4}-[0-9]{2}-[0-9]{2}', $value)
			// Check string it passes a simple strtotime
			|| ! strtotime($value)
		)
		{
			return false;
		}

		return true;
	}

	public static function dateToString($value, $attributes)
	{
		$showtime = isset($attributes->showtime) ? $attributes->showtime : true;
		$format   = isset($attributes->format) ? $attributes->format : '';
//		$is_custom_field = isset($attributes->is_custom_field) ? $attributes->is_custom_field : false;

		if (empty($format))
		{
			$format = $showtime ? JText::_('DATE_FORMAT_LC2') : JText::_('DATE_FORMAT_LC1');
		}

		if (strpos($format, '%') !== false)
		{
			$format = RL_Date::strftimeToDateFormat($format);
		}

		// Don't pass custom fields through JHtml, as it will double the offset
//		if ($is_custom_field)
//		{
//			return (new JDate($value))->format($format);
//		}

		return JHtml::_('date', $value, $format);
	}
}
