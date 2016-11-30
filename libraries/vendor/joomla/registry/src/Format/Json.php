<?php
/**
 * Part of the Joomla Framework Registry Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Registry\Format;

use Joomla\Registry\AbstractRegistryFormat;

/**
 * JSON format handler for Registry.
 *
 * @since  1.0
 */
class Json extends AbstractRegistryFormat
{
	/**
	 * Converts an object into a JSON formatted string.
	 *
	 * @param   object  $object   Data source object.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  string  JSON formatted string.
	 *
	 * @since   1.0
	 */
	public function objectToString($object, $options = array())
	{
		$bitmask = isset($options['bitmask']) ? $options['bitmask'] : 0;

		// The depth parameter is only present as of PHP 5.5
		if (version_compare(PHP_VERSION, '5.5', '>='))
		{
			$depth = isset($options['depth']) ? $options['depth'] : 512;

			return json_encode($object, $bitmask, $depth);
		}

		return json_encode($object, $bitmask);
	}

	/**
	 * Parse a JSON formatted string and convert it into an object.
	 *
	 * If the string is not in JSON format, this method will attempt to parse it as INI format.
	 *
	 * @param   string  $data     JSON formatted string to convert.
	 * @param   array   $options  Options used by the formatter.
	 *
	 * @return  object   Data object.
	 *
	 * @since   1.0
	 */
	public function stringToObject($data, array $options = array('processSections' => false))
	{
		$data = trim($data);

		if ((substr($data, 0, 1) != '{') && (substr($data, -1, 1) != '}'))
		{
			return AbstractRegistryFormat::getInstance('Ini')->stringToObject($data, $options);
		}

		return json_decode($data);
	}
}
