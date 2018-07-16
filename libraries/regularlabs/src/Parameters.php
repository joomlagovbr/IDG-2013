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

use JComponentHelper;
use JFile;
use JPluginHelper;

/**
 * Class Parameters
 * @package RegularLabs\Library
 */
class Parameters
{
	public static $instance = null;

	/**
	 * @return static instance
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Get a usable parameter object based on the Joomla Registry object
	 * The object will have all the available parameters with their value (default value if none is set)
	 *
	 * @param \Registry $params
	 * @param string    $path
	 * @param string    $default
	 *
	 * @return object
	 */
	public function getParams($params, $path = '', $default = '', $use_cache = true)
	{
		$cache_id = 'getParams_' . json_encode($params) . '_' . $path . '_' . $default;

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$xml = $this->loadXML($path, $default);

		if (empty($params))
		{
			return Cache::set(
				$cache_id,
				(object) $xml
			);
		}

		if ( ! is_object($params))
		{
			$params = json_decode($params);
			if (is_null($xml))
			{
				$xml = (object) [];
			}
		}
		elseif (method_exists($params, 'toObject'))
		{
			$params = $params->toObject();
		}

		if ( ! $params)
		{
			return Cache::set(
				$cache_id,
				(object) $xml
			);
		}

		if (empty($xml))
		{
			return Cache::set(
				$cache_id,
				$params
			);
		}

		foreach ($xml as $key => $val)
		{
			if (isset($params->{$key}) && $params->{$key} != '')
			{
				continue;
			}

			$params->{$key} = $val;
		}

		return Cache::set(
			$cache_id,
			$params
		);
	}

	/**
	 * Get a usable parameter object for the component
	 *
	 * @param string    $name
	 * @param \Registry $params
	 *
	 * @return object
	 */
	public function getComponentParams($name, $params = null, $use_cache = true)
	{
		$name = 'com_' . RegEx::replace('^com_', '', $name);

		$cache_id = 'getComponentParams_' . $name . '_' . json_encode($params);

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (empty($params) && JComponentHelper::isInstalled($name))
		{
			$params = JComponentHelper::getParams($name);
		}

		return Cache::set(
			$cache_id,
			$this->getParams($params, JPATH_ADMINISTRATOR . '/components/' . $name . '/config.xml')
		);
	}

	/**
	 * Get a usable parameter object for the module
	 *
	 * @param string    $name
	 * @param int       $admin
	 * @param \Registry $params
	 *
	 * @return object
	 */
	public function getModuleParams($name, $admin = true, $params = '', $use_cache = true)
	{
		$name = 'mod_' . RegEx::replace('^mod_', '', $name);

		$cache_id = 'getModuleParams_' . $name . '_' . json_encode($params);

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (empty($params))
		{
			$params = null;
		}

		return Cache::set(
			$cache_id,
			$this->getParams($params, ($admin ? JPATH_ADMINISTRATOR : JPATH_SITE) . '/modules/' . $name . '/' . $name . '.xml')
		);
	}

	/**
	 * Get a usable parameter object for the plugin
	 *
	 * @param string    $name
	 * @param string    $type
	 * @param \Registry $params
	 *
	 * @return object
	 */
	public function getPluginParams($name, $type = 'system', $params = '', $use_cache = true)
	{
		$cache_id = 'getPluginParams_' . $name . '_' . $type . '_' . json_encode($params);

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if (empty($params))
		{
			$plugin = JPluginHelper::getPlugin($type, $name);
			$params = (is_object($plugin) && isset($plugin->params)) ? $plugin->params : null;
		}

		return Cache::set(
			$cache_id,
			$this->getParams($params, JPATH_PLUGINS . '/' . $type . '/' . $name . '/' . $name . '.xml')
		);
	}

	/**
	 * Returns an object based on the data in a given xml array
	 *
	 * @param $xml
	 *
	 * @return bool|mixed
	 */
	public function getObjectFromXml(&$xml, $use_cache = true)
	{
		$cache_id = 'getObjectFromXml_' . json_encode($xml);

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if ( ! is_array($xml))
		{
			$xml = [$xml];
		}

		$object = $this->getObjectFromXmlNode($xml);

		return Cache::set(
			$cache_id,
			$object
		);
	}

	/**
	 * Returns an array based on the data in a given xml file
	 *
	 * @param string $path
	 * @param string $default
	 *
	 * @return array
	 */
	private function loadXML($path, $default = '', $use_cache = true)
	{
		$cache_id = 'loadXML_' . $path . '_' . $default;

		if ($use_cache && Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		if ( ! $path
			|| ! JFile::exists($path)
			|| ! $file = JFile::read($path)
		)
		{
			return Cache::set(
				$cache_id,
				[]
			);
		}

		$xml = [];

		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $file, $fields);
		xml_parser_free($xml_parser);

		$default = $default ? strtoupper($default) : 'DEFAULT';
		foreach ($fields as $field)
		{
			if ($field['tag'] != 'FIELD'
				|| ! isset($field['attributes'])
				|| ( ! isset($field['attributes']['DEFAULT']) && ! isset($field['attributes'][$default]))
				|| ! isset($field['attributes']['NAME'])
				|| $field['attributes']['NAME'] == ''
				|| $field['attributes']['NAME'][0] == '@'
				|| ! isset($field['attributes']['TYPE'])
				|| $field['attributes']['TYPE'] == 'spacer'
			)
			{
				continue;
			}

			if (isset($field['attributes'][$default]))
			{
				$field['attributes']['DEFAULT'] = $field['attributes'][$default];
			}

			if ($field['attributes']['TYPE'] == 'textarea')
			{
				$field['attributes']['DEFAULT'] = str_replace('<br>', "\n", $field['attributes']['DEFAULT']);
			}

			$xml[$field['attributes']['NAME']] = $field['attributes']['DEFAULT'];
		}

		return Cache::set(
			$cache_id,
			$xml
		);
	}

	/**
	 * Returns the main attributes key from an xml object
	 *
	 * @param $xml
	 *
	 * @return mixed
	 */
	private function getKeyFromXML($xml)
	{
		if ( ! empty($xml->_attributes) && isset($xml->_attributes['name']))
		{
			return $xml->_attributes['name'];
		}

		return $xml->_name;
	}

	/**
	 * Returns the value from an xml object / node
	 *
	 * @param $xml
	 *
	 * @return object
	 */
	private function getValFromXML($xml)
	{
		if ( ! empty($xml->_attributes) && isset($xml->_attributes['value']))
		{
			return $xml->_attributes['value'];
		}

		if (empty($xml->_children))
		{
			return $xml->_data;
		}

		return $this->getObjectFromXmlNode($xml->_children);
	}

	/**
	 * Create an object from the given xml node
	 *
	 * @param $xml
	 *
	 * @return object
	 */
	private function getObjectFromXmlNode($xml)
	{
		$object = (object) [];

		foreach ($xml as $child)
		{
			$key   = $this->getKeyFromXML($child);
			$value = $this->getValFromXML($child);

			if ( ! isset($object->{$key}))
			{
				$object->{$key} = $value;
				continue;
			}

			if ( ! is_array($object->{$key}))
			{
				$object->{$key} = [$object->{$key}];
			}

			$object->{$key}[] = $value;
		}

		return $object;
	}
}
