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

use Joomla\String\Normalise;

defined('_JEXEC') or die;

/**
 * Class StringHelper
 * @package RegularLabs\Library
 */
class StringHelper
	extends \Joomla\String\StringHelper
{
	/**
	 * Decode html entities in string or array of strings
	 *
	 * @param string $data
	 * @param int    $quote_style
	 * @param string $encoding
	 *
	 * @return array|string
	 */
	public static function html_entity_decoder($data, $quote_style = ENT_QUOTES, $encoding = 'UTF-8')
	{
		if (is_array($data))
		{
			array_walk($data, function (&$part, $key, $quote_style, $encoding) {
				$part = self::html_entity_decoder($part, $quote_style, $encoding);
			}, $quote_style, $encoding);

			return $data;
		}

		if ( ! is_string($data))
		{
			return $data;
		}

		return html_entity_decode($data, $quote_style | ENT_HTML5, $encoding);
	}

	/**
	 * Replace the given replace string once in the main string
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $string
	 *
	 * @return string
	 */
	public static function replaceOnce($search, $replace, $string)
	{
		if (empty($search) || empty($string))
		{
			return $string;
		}

		$pos = strpos($string, $search);

		if ($pos === false)
		{
			return $string;
		}

		return substr_replace($string, $replace, $pos, strlen($search));
	}

	/**
	 * Check if any of the needles are found in any of the haystacks
	 *
	 * @param $haystacks
	 * @param $needles
	 *
	 * @return bool
	 */
	public static function contains($haystacks, $needles)
	{
		$haystacks = (array) $haystacks;
		$needles   = (array) $needles;

		foreach ($haystacks as $haystack)
		{
			foreach ($needles as $needle)
			{
				if (strpos($haystack, $needle) !== false)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if string is alphanumerical
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public static function is_alphanumeric($string)
	{
		if (function_exists('ctype_alnum'))
		{
			return (bool) ctype_alnum($string);
		}

		return (bool) RegEx::match('^[a-z0-9]+$', $string);
	}

	/**
	 * Split a long string into parts (array)
	 *
	 * @param string $string
	 * @param array  $delimiters     Array of strings to split the string on
	 * @param int    $max_length     Maximum length of each part
	 * @param bool   $maximize_parts If true, the different parts will be made as large as possible (combining consecutive short string elements)
	 *
	 * @return array
	 */
	public static function split($string, $delimiters = [], $max_length = 10000, $maximize_parts = true)
	{
		// String is too short to split
		if (strlen($string) < $max_length)
		{
			return [$string];
		}

		// No delimiters given or found
		if (empty($delimiters) || ! self::contains($string, $delimiters))
		{
			return [$string];
		}

		// preg_quote all delimiters
		$array = preg_split('#' . RegEx::quote($delimiters) . '#s', $string, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		if ( ! $maximize_parts)
		{
			return $array;
		}

		$new_array = [];
		foreach ($array as $part)
		{
			// First element, add to new array
			if ( ! count($new_array))
			{
				$new_array[] = $part;
				continue;
			}

			$last_part = end($new_array);
			$last_key  = key($new_array);

			// If last and current parts are longer than max_length, then simply add as new value
			if (strlen($last_part) + strlen($part) > $max_length)
			{
				$new_array[] = $part;
				continue;
			}

			// Concatenate part to previous part
			$new_array[$last_key] .= $part;
		}

		return $new_array;
	}

	/**
	 * Check whether string is a UTF-8 encoded string
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public static function detectUTF8($string = '')
	{
		// Try to check the string via the mb_check_encoding function
		if (function_exists('mb_check_encoding'))
		{
			return mb_check_encoding($string, 'UTF-8');
		}

		// Otherwise: Try to check the string via the iconv function
		if (function_exists('iconv'))
		{
			$converted = iconv('UTF-8', 'UTF-8//IGNORE', $string);

			return (md5($converted) == md5($string));
		}

		// As last fallback, check if the preg_match finds anything using the unicode flag
		return preg_match('#.#u', $string);
	}

	/**
	 * Converts a string to a UTF-8 encoded string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function convertToUtf8(&$string = '')
	{
		if (self::detectUTF8($string))
		{
			// Already UTF-8, so skip
			return $string;
		}

		if ( ! function_exists('iconv'))
		{
			// Still need to find a stable fallback
			return $string;
		}

		$utf8_string = @iconv('UTF8', 'UTF-8//IGNORE', $string);

		if (empty($utf8_string))
		{
			return $string;
		}

		return $utf8_string;
	}

	/**
	 * Converts a camelcased string to a underscore separated string
	 * eg: FooBar => foo_bar
	 *
	 * @param string $string
	 * @param bool   $tolowercase
	 *
	 * @return string
	 */
	public static function camelToUnderscore($string = '', $tolowercase = true)
	{
		$string = Normalise::toUnderscoreSeparated(Normalise::fromCamelCase($string));

		if ( ! $tolowercase)
		{
			return $string;
		}

		return strtolower($string);
	}

	/**
	 * Removes html tags from string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function removeHtml($string)
	{
		return Html::removeHtmlTags($string);
	}
}
