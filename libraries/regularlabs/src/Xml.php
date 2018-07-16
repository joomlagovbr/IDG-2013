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

jimport('joomla.filesystem.file');

use JFile;
use SimpleXMLElement;

/**
 * Class File
 * @package RegularLabs\Library
 */
class Xml
{
	/**
	 * Get an object filled with data from an xml file
	 *
	 * @param string $url
	 * @param string $root
	 *
	 * @return object
	 */
	public static function toObject($url, $root = '')
	{
		$cache_id = 'xmlToObject_' . $url . '_' . $root;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (JFile::exists($url))
		{
			$xml = @new SimpleXMLElement($url, LIBXML_NONET | LIBXML_NOCDATA, 1);
		}
		else
		{
			$xml = simplexml_load_string($url, "SimpleXMLElement", LIBXML_NONET | LIBXML_NOCDATA);
		}

		if ( ! @count($xml))
		{
			return Cache::set(
				$cache_id,
				(object) []
			);
		}

		if ($root)
		{
			if ( ! isset($xml->{$root}))
			{
				return Cache::set(
					$cache_id,
					(object) []
				);
			}

			$xml = $xml->{$root};
		}

		$json = json_encode($xml);
		$xml  = json_decode($json);
		if (is_null($xml))
		{
			$xml = (object) [];
		}

		if ($root && isset($xml->{$root}))
		{
			$xml = $xml->{$root};
		}

		return Cache::set(
			$cache_id,
			$xml
		);
	}
}
