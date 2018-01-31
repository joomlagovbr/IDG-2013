<?php
/**
 * @package         Articles Anywhere
 * @version         7.5.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

use ReflectionClass;

defined('_JEXEC') or die;

class Factory
{
	static $classes = [];

	public static function _()
	{
		$arguments = func_get_args();

		$class     = array_shift($arguments);
		$component = self::getComponentName($arguments);

		$class = self::getClassName($class, $component);

		$reflector = new ReflectionClass(__NAMESPACE__ . '\\' . $class);

		return $reflector->newInstanceArgs($arguments);
	}

	public static function getCurrentItem($component = 'default')
	{
		$config = new Config((object) ['component' => $component]);

		return self::_('CurrentItem', $config);
	}

	public static function getCollection(Config $config)
	{
		return self::_('Collection\\Collection', $config);
	}

	public static function getIgnores(Config $config)
	{
		return self::_('Collection\\Ignores', $config);
	}

	public static function getConfig($data)
	{
		return new Config($data);
	}

	public static function getFilter($class, Config $config)
	{
		return self::_('Collection\\Filters\\' . $class, $config);
	}

	public static function getFields($class, Config $config)
	{
		return self::_('Collection\\Fields\\' . $class, $config);
	}

	public static function getOutput($class, Config $config, $item, $values)
	{
		return self::_('Output\\Data\\' . $class, $config, $item, $values);
	}

	private static function getClassName($class, $component)
	{
		if ( ! $component)
		{
			return $class;
		}

		$component_class = 'Components\\' . $component . '\\' . $class;

		if (in_array(__NAMESPACE__ . '\\' . $component_class, get_declared_classes()))
		{
			return $component_class;
		}

		$file = __DIR__ . '/' . str_replace('\\', '/', $component_class) . '.php';

		if ( ! file_exists($file))
		{
			return $class;
		}

		require_once($file);

		if (in_array(__NAMESPACE__ . '\\' . $component_class, get_declared_classes()))
		{
			return $component_class;
		}

		return $class;
	}

	private static function getComponentName($arguments)
	{
		if ( ! isset($arguments[0]))
		{
			return false;
		}

		$config = $arguments[0];

		return $config->getComponentName();
	}
}
