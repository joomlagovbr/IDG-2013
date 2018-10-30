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
 * Class Title
 * @package RegularLabs\Library
 */
class Title
{
	/**
	 * Cleans the string to make it usable as a title
	 *
	 * @param string $string
	 * @param bool   $strip_tags
	 * @param bool   $strip_spaces
	 *
	 * @return string
	 */
	public static function clean($string = '', $strip_tags = false, $strip_spaces = true)
	{
		if (empty($string))
		{
			return '';
		}

		// remove comment tags
		$string = RegEx::replace('<\!--.*?-->', '', $string);

		// replace weird whitespace
		$string = str_replace(chr(194) . chr(160), ' ', $string);

		if ($strip_tags)
		{
			// remove svgs
			$string = RegEx::replace('<svg.*?</svg>', '', $string);
			// remove html tags
			$string = RegEx::replace('</?[a-z][^>]*>', '', $string);
			// remove comments tags
			$string = RegEx::replace('<\!--.*?-->', '', $string);
		}

		if ($strip_spaces)
		{
			// Replace html spaces
			$string = str_replace(['&nbsp;', '&#160;'], ' ', $string);

			// Remove duplicate whitespace
			$string = RegEx::replace('[ \n\r\t]+', ' ', $string);
		}

		return trim($string);
	}

	/**
	 * Creates an array of different syntaxes of titles to match against a url variable
	 *
	 * @param array $titles
	 *
	 * @return array
	 */
	public static function getUrlMatches($titles = [])
	{
		$matches = [];
		foreach ($titles as $title)
		{
			$matches[] = $title;
			$matches[] = StringHelper::strtolower($title);
		}

		$matches = array_unique($matches);

		foreach ($matches as $title)
		{
			$matches[] = htmlspecialchars(StringHelper::html_entity_decoder($title));
		}

		$matches = array_unique($matches);

		foreach ($matches as $title)
		{
			$matches[] = urlencode($title);
			$matches[] = utf8_decode($title);
			$matches[] = str_replace(' ', '', $title);
			$matches[] = trim(RegEx::replace('[^a-z0-9]', '', $title));
			$matches[] = trim(RegEx::replace('[^a-z]', '', $title));
		}

		$matches = array_unique($matches);

		foreach ($matches as $i => $title)
		{
			$matches[$i] = trim(str_replace('?', '', $title));
		}

		$matches = array_diff(array_unique($matches), ['', '-']);

		return $matches;
	}
}
