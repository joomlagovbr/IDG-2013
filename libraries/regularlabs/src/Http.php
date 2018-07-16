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
use JHttpFactory;
use Joomla\Registry\Registry;
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
		$cache_id = 'getUrl_' . $url;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (JFactory::getApplication()->input->getInt('cache', 0)
			&& $content = Cache::read($cache_id)
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
			return Cache::write($cache_id, $content, $ttl > 1 ? $ttl : 0);
		}

		return Cache::set($cache_id, $content);
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
		$cache_id = 'getByUrl_' . $url;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
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
			return Cache::write($cache_id, $content, $ttl > 1 ? $ttl : 0);
		}

		return Cache::set($cache_id, $content);
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
			// Adding a valid user agent string, otherwise some feed-servers returning an error
			$options = new Registry([
				'userAgent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0',
			]);

			$content = JHttpFactory::getHttp($options)->get($url, null, $timeout)->body;
		}
		catch (RuntimeException $e)
		{
			return '';
		}

		return $content;
	}

}
