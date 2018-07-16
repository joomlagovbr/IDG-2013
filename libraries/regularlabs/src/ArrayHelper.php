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

/**
 * Class ArrayHelper
 * @package RegularLabs\Library
 */
class ArrayHelper
{
	/**
	 * Convert data (string or object) to an array
	 *
	 * @param mixed  $data
	 * @param string $separator
	 * @param bool   $unique
	 *
	 * @return array
	 */
	public static function toArray($data, $separator = ',', $unique = false, $trim = true)
	{
		if (is_array($data))
		{
			return $data;
		}

		if (is_object($data))
		{
			return (array) $data;
		}

		if ($data == '')
		{
			return [];
		}

		if ($separator == '')
		{
			return [$data];
		}

		$array = explode($separator, $data);

		if ($trim)
		{
			$array = self::trim($array);
		}

		if ($unique)
		{
			$array = array_unique($array);
		}

		return $array;
	}

	/**
	 * Clean array by trimming values and removing empty/false values
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function clean($array)
	{
		// trim all values
		self::trim($array);

		// remove duplicates
		$array = array_unique($array);
		// remove empty (or false) values
		$array = array_filter($array);

		return $array;
	}

	/**
	 * Clean array by trimming values
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function trim($array)
	{
		// trim all values
		return array_map('trim', $array);
	}
}
