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
 * Class HtmlTag
 * @package RegularLabs\Library
 */
class HtmlTag
{
	/**
	 * Combine 2 opening html tags into one
	 *
	 * @param string $tag1
	 * @param string $tag2
	 *
	 * @return string
	 */
	public static function combine($tag1, $tag2)
	{
		// Return if tags are the same
		if ($tag1 == $tag2)
		{
			return $tag1;
		}

		if ( ! RegEx::match('<([a-z][a-z0-9]*)', $tag1, $tag_type))
		{
			return $tag2;
		}

		$tag_type = $tag_type[1];

		if ( ! $attribs = self::combineAttributes($tag1, $tag2))
		{
			return '<' . $tag_type . '>';
		}

		return '<' . $tag_type . ' ' . $attribs . '>';
	}

	/**
	 * Extract attribute value from a html tag string by given attribute key
	 *
	 * @param string $key
	 * @param string $string
	 *
	 * @return string
	 */
	public static function getAttributeValue($key, $string)
	{
		if (empty($key) || empty($string))
		{
			return '';
		}

		RegEx::match(RegEx::quote($key) . '="([^"]*)"', $string, $match);

		if (empty($match))
		{
			return '';
		}

		return $match[1];
	}

	/**
	 * Extract all attributes from a html tag string
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public static function getAttributes($string)
	{
		if (empty($string))
		{
			return [];
		}

		RegEx::matchAll('([a-z0-9-_]+)="([^"]*)"', $string, $matches);

		if (empty($matches))
		{
			return [];
		}

		$attribs = [];

		foreach ($matches as $match)
		{
			$attribs[$match[1]] = $match[2];
		}

		return $attribs;
	}

	/**
	 * Combine attribute values from 2 given html tag strings (or arrays of attributes)
	 * And return as a sting of attributes
	 *
	 * @param string /array $string1
	 * @param string /array $string2
	 *
	 * @return string
	 */
	public static function combineAttributes($string1, $string2, $flatten = true)
	{
		$attribsutes1 = is_array($string1) ? $string1 : self::getAttributes($string1);
		$attribsutes2 = is_array($string2) ? $string2 : self::getAttributes($string2);

		$duplicate_attributes = array_intersect_key($attribsutes1, $attribsutes2);

		// Fill $attributes with the unique ids
		$attributes = array_diff_key($attribsutes1, $attribsutes2) + array_diff_key($attribsutes2, $attribsutes1);

		// List of attrubute types that can only contain one value
		$single_value_attributes = ['id', 'href'];

		// Add/combine the duplicate ids
		foreach ($duplicate_attributes as $key => $val)
		{
			if (in_array($key, $single_value_attributes))
			{
				$attributes[$key] = $attribsutes2[$key];
				continue;
			}
			// Combine strings, but remove duplicates
			// "aaa bbb" + "aaa ccc" = "aaa bbb ccc"

			// use a ';' as a concatenated for javascript values (keys beginning with 'on')
			// Otherwise use a space (like for classes)
			$glue = substr($key, 0, 2) == 'on' ? ';' : ' ';

			$attributes[$key] = implode($glue, array_merge(explode($glue, $attribsutes1[$key]), explode($glue, $attribsutes2[$key])));
		}

		return $flatten ? self::flattenAttributes($attributes) : $attributes;
	}

	/**
	 * Convert array of attributes to a html style string
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function flattenAttributes($attributes)
	{
		foreach ($attributes as $key => &$val)
		{
			$val = $key . '="' . $val . '"';
		}

		return implode(' ', $attributes);
	}
}
