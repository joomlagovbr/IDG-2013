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

use JFactory;
use JUri;

/**
 * Class Uri
 * @package RegularLabs\Library
 */
class Uri
{
	/**
	 * Returns the full uri and optionally adds/replaces the hash
	 *
	 * @param string $hash
	 *
	 * @return string
	 */
	public static function get($hash = '')
	{
		$url = JUri::getInstance()->toString();

		if ($hash == '')
		{
			return $url;
		}

		return self::appendHash($url, $hash);
	}

	/**
	 * Appends the given hash to the url or replaces it if there is already one
	 *
	 * @param string $url
	 * @param string $hash
	 *
	 * @return string
	 */
	private static function appendHash($url = '', $hash = '')
	{
		if (empty($hash))
		{
			return $url;
		}

		if (strpos($url, '#') !== false)
		{
			$url = substr($url, 0, strpos($url, '#'));
		}

		return $url . '#' . $hash;
	}

	public static function isExternal($url)
	{
		if (strpos($url, '://') === false)
		{
			return false;
		}

		// hostname: give preference to SERVER_NAME, because this includes subdomains
		$hostname = ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];

		return ! (strpos(RegEx::replace('^.*?://', '', $url), $hostname) === 0);
	}

	public static function encode($string)
	{
		return urlencode(base64_encode(gzdeflate($string)));
	}

	public static function decode($string)
	{
		return gzinflate(base64_decode(urldecode($string)));
	}

	public static function createCompressedAttributes($string)
	{
		$parameters = [];

		$compressed   = base64_encode(gzdeflate($string));
		$chunk_length = ceil(strlen($compressed) / 10);
		$chunks       = str_split($compressed, $chunk_length);

		foreach ($chunks as $i => $chunk)
		{
			$parameters[] = 'rlatt_' . $i . '=' . urlencode($chunk);
		}

		return implode('&', $parameters);
	}

	public static function getCompressedAttributes()
	{
		$input = JFactory::getApplication()->input;

		$compressed = '';

		for ($i = 0; $i < 10; $i++)
		{
			$compressed .= $input->getString('rlatt_' . $i, '');
		}

		return gzinflate(base64_decode($compressed));
	}
}
