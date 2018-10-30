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

use JApplicationHelper;
use JFactory;

defined('_JEXEC') or die;

/**
 * Class Alias
 * @package RegularLabs\Library
 */
class Alias
{
	/**
	 * Creates an alias from a string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function get($string = '', $unicode = false)
	{
		if (empty($string))
		{
			return '';
		}

		$string = StringHelper::removeHtml($string);

		if ($unicode || JFactory::getConfig()->get('unicodeslugs') == 1)
		{
			return self::stringURLUnicodeSlug($string);
		}

		return JApplicationHelper::stringURLSafe($string);
	}

	/**
	 * Creates a unicode alias from a string
	 * Based on stringURLUnicodeSlug method from the unicode slug plugin by infograf768
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private static function stringURLUnicodeSlug($string = '')
	{
		if (empty($string))
		{
			return '';
		}

		// Remove < > html entities
		$string = str_replace(['&lt;', '&gt;'], '', $string);

		// Convert html entities
		$string = StringHelper::html_entity_decoder($string);

		// Convert to lowercase
		$string = StringHelper::strtolower($string);

		// remove html tags
		$string = RegEx::replace('</?[a-z][^>]*>', '', $string);
		// remove comments tags
		$string = RegEx::replace('<\!--.*?-->', '', $string);

		// Replace weird whitespace characters like (Â) with spaces
		//$string = str_replace(array(chr(160), chr(194)), ' ', $string);
		$string = str_replace("\xC2\xA0", ' ', $string);
		$string = str_replace("\xE2\x80\xA8", ' ', $string); // ascii only

		// Replace double byte whitespaces by single byte (East Asian languages)
		$string = str_replace("\xE3\x80\x80", ' ', $string);

		// Remove any '-' from the string as they will be used as concatenator.
		// Would be great to let the spaces in but only Firefox is friendly with this
		$string = str_replace('-', ' ', $string);

		// Replace forbidden characters by whitespaces
		$string = RegEx::replace('[' . RegEx::quote(',:#$*"@+=;&.%()[]{}/\'\\|') . ']', "\x20", $string);

		// Delete all characters that should not take up any space, like: ?
		$string = RegEx::replace('[' . RegEx::quote('?!¿¡') . ']', '', $string);

		// Trim white spaces at beginning and end of alias and make lowercase
		$string = trim($string);

		// Remove any duplicate whitespace and replace whitespaces by hyphens
		$string = RegEx::replace('\x20+', '-', $string);

		// Remove leading and trailing hyphens
		$string = trim($string, '-');

		return $string;
	}
}
