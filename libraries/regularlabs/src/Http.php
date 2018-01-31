<?php
/**
 * @package         Regular Labs Library
 * @version         18.1.20362
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JFactory;
use JHttpFactory;
use RuntimeException;

/**
 * Class Http
 * @package RegularLabs\Library
 */
class Http
{
	/**
	 * Get the contents of the given internal url
	 *
	 * @param string $url
	 * @param int    $timeout
	 *
	 * @return string
	 */
	public static function get($url, $timeout = 20)
	{
		if (Uri::isExternal($url))
		{
			return '';
		}

		return self::getFromUrl($url, $timeout);
	}

	/**
	 * Get the contents of the given url
	 *
	 * @param string $url
	 * @param int    $timeout
	 *
	 * @return string
	 */
	public static function getFromUrl($url, $timeout = 20)
	{
		$hash = md5('getUrl_' . $url);

		if (Cache::has($hash))
		{
			return Cache::get($hash);
		}

		if (JFactory::getApplication()->input->getInt('cache', 0)
			&& $content = Cache::read($hash)
		)
		{
			return $content;
		}

		$content = self::getContents($url, $timeout);

		if (empty($content))
		{
			return '';
		}

		if ($ttl = JFactory::getApplication()->input->getInt('cache', 0))
		{
			return Cache::write($hash, $content, $ttl > 1 ? $ttl : 0);
		}

		return Cache::set($hash, $content);
	}

	/**
	 * Get the contents of the given external url from the Regular Labs server
	 *
	 * @param string $url
	 * @param int    $timeout
	 *
	 * @return string
	 */
	public static function getFromServer($url, $timeout = 20)
	{
		$hash = md5('getByUrl_' . $url);

		if (Cache::has($hash))
		{
			return Cache::get($hash);
		}

		// only allow url calls from administrator
		if ( ! Document::isClient('administrator'))
		{
			die;
		}

		// only allow when logged in
		$user = JFactory::getUser();
		if ( ! $user->id)
		{
			die;
		}

		if (substr($url, 0, 4) != 'http')
		{
			$url = 'http://' . $url;
		}

		// only allow url calls to regularlabs.com domain
		if ( ! (RegEx::match('^https?://([^/]+\.)?regularlabs\.com/', $url)))
		{
			die;
		}

		// only allow url calls to certain files
		if (
			strpos($url, 'download.regularlabs.com/extensions.php') === false
			&& strpos($url, 'download.regularlabs.com/extensions.json') === false
			&& strpos($url, 'download.regularlabs.com/extensions.xml') === false
		)
		{
			die;
		}

		$content = self::getContents($url, $timeout);

		if (empty($content))
		{
			return '';
		}

		$format = (strpos($url, '.json') !== false || strpos($url, 'format=json') !== false)
			? 'application/json'
			: 'text/xml';

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-type: " . $format);

		if ($ttl = JFactory::getApplication()->input->getInt('cache', 0))
		{
			return Cache::write($hash, $content, $ttl > 1 ? $ttl : 0);
		}

		return Cache::set($hash, $content);
	}

	/**
	 * Load the contents of the given url
	 *
	 * @param string $url
	 * @param int    $timeout
	 *
	 * @return string
	 */
	private static function getContents($url, $timeout = 20)
	{
		try
		{
			$content = JHttpFactory::getHttp()->get($url, null, $timeout)->body;
		}
		catch (RuntimeException $e)
		{
			return '';
		}

		return $content;
	}

}
