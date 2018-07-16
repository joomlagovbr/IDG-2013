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
 * Class RegEx
 * @package RegularLabs\Library
 */
class RegEx
{
	/**
	 * Perform a regular expression search and replace
	 *
	 * @param string $pattern
	 * @param string $replacement
	 * @param string $string
	 * @param string $options
	 * @param int    $limit
	 * @param int    $count
	 *
	 * @return string
	 */
	public static function replace($pattern, $replacement, $string, $options = null, $limit = -1, &$count = null)
	{
		if ( ! is_string($pattern) || $pattern == '' || ! is_string($string) || $string == '')
		{
			return $string;
		}

		$pattern = self::preparePattern($pattern, $options, $string);

		return preg_replace($pattern, $replacement, $string, $limit, $count);
	}

	/**
	 * Perform a regular expression search and replace once
	 *
	 * @param string $pattern
	 * @param string $replacement
	 * @param string $string
	 * @param string $options
	 *
	 * @return string
	 */
	public static function replaceOnce($pattern, $replacement, $string, $options = null)
	{
		return self::replace($pattern, $replacement, $string, $options, 1);
	}

	/**
	 * Perform a regular expression match
	 *
	 * @param string $pattern
	 * @param string $string
	 * @param null   $matches
	 * @param string $options
	 * @param int    $flags
	 *
	 * @return int
	 */
	public static function match($pattern, $string, &$matches = null, $options = null, $flags = 0)
	{
		if ( ! is_string($pattern) || $pattern == '' || ! is_string($string) || $string == '')
		{
			return false;
		}

		$pattern = self::preparePattern($pattern, $options, $string);

		return preg_match($pattern, $string, $matches, $flags);
	}

	/**
	 * Perform a global regular expression match
	 *
	 * @param string $pattern
	 * @param string $string
	 * @param null   $matches
	 * @param string $options
	 * @param int    $flags
	 *
	 * @return int
	 */
	public static function matchAll($pattern, $string, &$matches = null, $options = null, $flags = PREG_SET_ORDER)
	{
		if ( ! is_string($pattern) || $pattern == '' || ! is_string($string) || $string == '')
		{
			$matches = [];

			return false;
		}

		$pattern = self::preparePattern($pattern, $options, $string);

		return preg_match_all($pattern, $string, $matches, $flags);
	}

	/**
	 * preg_quote the given string or array of strings
	 *
	 * @param string|array $data
	 * @param string       $name
	 * @param string       $delimiter
	 *
	 * @return string
	 */
	public static function quote($data, $name = '', $delimiter = '#')
	{
		if (is_array($data))
		{
			$array = self::quoteArray($data, $delimiter);

			$name = $name ? '?<' . $name . '>' : '';

			return '(' . $name . implode('|', $array) . ')';
		}

		return preg_quote($data, $delimiter);
	}

	/**
	 * reverse preg_quote the given string
	 *
	 * @param string $string
	 * @param string $delimiter
	 *
	 * @return string
	 */
	public static function unquote($string, $delimiter = '#')
	{
		return strtr($string, [
			'\\' . $delimiter => $delimiter,
			'\\.'             => '.',
			'\\\\'            => '\\',
			'\\+'             => '+',
			'\\*'             => '*',
			'\\?'             => '?',
			'\\['             => '[',
			'\\^'             => '^',
			'\\]'             => ']',
			'\\$'             => '$',
			'\\('             => '(',
			'\\)'             => ')',
			'\\{'             => '{',
			'\\}'             => '}',
			'\\='             => '=',
			'\\!'             => '!',
			'\\<'             => '<',
			'\\>'             => '>',
			'\\|'             => '|',
			'\\:'             => ':',
			'\\-'             => '-',
		]);
	}

	/**
	 * preg_quote the given array of strings
	 *
	 * @param array  $array
	 * @param string $delimiter
	 *
	 * @return array
	 */
	public static function quoteArray($array = [], $delimiter = '#')
	{
		array_walk($array, function (&$part, $key, $delimiter) {
			$part = self::quote($part, $delimiter);
		}, $delimiter);

		return $array;
	}

	/**
	 * Make a string a valid regular expression pattern
	 *
	 * @param string $pattern
	 * @param string $options
	 * @param string $string
	 *
	 * @return string
	 */
	public static function preparePattern($pattern, $options = null, $string = '')
	{
		if (is_array($pattern))
		{
			return self::preparePatternArray($pattern, $options, $string);
		}

		if (substr($pattern, 0, 1) != '#')
		{
			$pattern = '#' . $pattern . '#';
		}

		$options = ! is_null($options) ? $options : 'si';

		if (substr($pattern, -1, 1) == '#')
		{
			$pattern .= $options;
		}

		if (StringHelper::detectUTF8($string))
		{
			// use utf-8
			return $pattern . 'u';
		}

		return $pattern;
	}

	/**
	 * Make an array of strings valid regular expression patterns
	 *
	 * @param array  $pattern
	 * @param string $options
	 * @param string $string
	 *
	 * @return array
	 */
	private static function preparePatternArray($pattern, $options = null, $string = '')
	{
		array_walk($pattern, function (&$subpattern, $key, $string) {
			$subpattern = self::preparePattern($subpattern, $options = null, $string);
		}, $string);

		return $pattern;
	}
}
