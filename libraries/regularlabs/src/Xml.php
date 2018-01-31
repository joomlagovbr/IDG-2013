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
		$hash = md5('xmlToObject_' . $url . '_' . $root);

		if (Cache::has($hash))
		{
			return Cache::get($hash);
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
				$hash,
				(object) []
			);
		}

		if ($root)
		{
			if ( ! isset($xml->{$root}))
			{
				return Cache::set(
					$hash,
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
			$hash,
			$xml
		);
	}
}
