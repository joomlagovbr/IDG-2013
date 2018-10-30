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

/**
 * Class Conditions
 * @package RegularLabs\Library
 */
class Conditions
{
	static $installed_extensions = null;
	static $params               = null;

	public static function pass($conditions, $matching_method = 'all', $article = null, $module = null)
	{
		if (empty($conditions))
		{
			return true;
		}

		$article_id      = isset($article->id) ? $article->id : '';
		$module_id       = isset($module->id) ? $module->id : '';
		$matching_method = in_array($matching_method, ['any', 'or']) ? 'any' : 'all';
		$cache_id        = 'pass_' . $article_id . '_' . $module_id . '_' . $matching_method . '_' . json_encode($conditions);

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$pass = (bool) ($matching_method == 'all');

		foreach (self::getTypes() as $type)
		{
			// Break if not passed and matching method is ALL
			// Or if passed and matching method is ANY
			if (
				( ! $pass && $matching_method == 'all')
				|| ($pass && $matching_method == 'any')
			)
			{
				break;
			}

			if ( ! isset($conditions[$type]))
			{
				continue;
			}

			$pass = self::passByType($conditions[$type], $type, $article, $module);
		}

		return Cache::set(
			$cache_id,
			$pass
		);
	}

	public static function hasConditions($conditions)
	{
		if (empty($conditions))
		{
			return false;
		}

		foreach (self::getTypes() as $type)
		{
			if (isset($conditions[$type]) && isset($conditions[$type]->include_type) && $conditions[$type]->include_type)
			{
				return true;
			}
		}

		return false;
	}

	public static function getConditionsFromParams(&$params)
	{
		$cache_id = 'getConditionsFromParams_' . json_encode($params);

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		self::renameParamKeys($params);

		$types = [];

		foreach (self::getTypes() as $id => $type)
		{
			if (empty($params->conditions[$id]))
			{
				continue;
			}

			$types[$type] = (object) [
				'include_type' => $params->conditions[$id],
				'selection'    => [],
				'params'       => (object) [],
			];

			if (isset($params->conditions[$id . '_selection']))
			{
				$types[$type]->selection = self::getSelection($params->conditions[$id . '_selection'], $type);
			}

			self::addParams($types[$type], $type, $id, $params);
		}

		return Cache::set(
			$cache_id,
			$types
		);
	}

	public static function getConditionsFromTagAttributes(&$attributes, $only_types = [])
	{
		$conditions = [];

		PluginTag::replaceKeyAliases($attributes, self::getTypeAliases(), true);
		$types = self::getTypes($only_types);

		if (empty($types))
		{
			return $conditions;
		}

		foreach ($attributes as $type => $value)
		{
			if (empty($value))
			{
				continue;
			}

			$condition_type = self::getType($type, $only_types);

			if ( ! $condition_type)
			{
				continue;
			}

			$value   = html_entity_decode($value);
			$params  = self::getDefaultParamsByType($condition_type, $type);
			$reverse = false;

			$selection = self::getSelectionFromTagAttribute($condition_type, $value, $params, $reverse);

			$condition = (object) [
				'include_type' => $reverse ? 2 : 1,
				'selection'    => $selection,
				'params'       => (object) [],
			];

			self::addParams($condition, $condition_type, $type, $params);

			$conditions[$condition_type] = $condition;
		}

		return $conditions;
	}

	private static function initParametersByType(&$params, $type = '')
	{
		$params->class_name = str_replace('.', '', $type);

		$params->include_type = self::getConditionState($params->include_type);
	}

	private static function passByType($condition, $type, $article = null, $module = null)
	{
		$article_id   = isset($article->id) ? $article->id : '';
		$module_id    = isset($module->id) ? $module->id : '';
		$cache_prefix = 'passByType_' . $type . '_' . $article_id . '_' . $module_id;
		$cache_id     = $cache_prefix . '_' . json_encode($condition);

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		self::initParametersByType($condition, $type);

		$cache_id = $cache_prefix . '_' . json_encode($condition);

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$pass = false;

		switch ($condition->include_type)
		{
			case 'all':
				$pass = true;
				break;

			case 'none':
				$pass = false;
				break;

			default:
				if ( ! JFile::exists(__DIR__ . '/Condition/' . $condition->class_name . '.php'))
				{
					break;
				}

				$className = '\\RegularLabs\\Library\\Condition\\' . $condition->class_name;

				$class = new $className($condition, $article, $module);

				$class->beforePass();

				$pass = $class->pass();

				break;
		}

		return Cache::set(
			$cache_id,
			$pass
		);
	}

	private static function getConditionState($include_type)
	{
		switch ($include_type . '')
		{
			case 1:
			case 'include':
				return 'include';

			case 2:
			case 'exclude':
				return 'exclude';

			case 3:
			case -1:
			case 'none':
				return 'none';

			default:
				return 'all';
		}
	}

	private static function makeArray($array = '', $delimiter = ',', $trim = true)
	{
		if (empty($array))
		{
			return [];
		}

		$cache_id = 'makeArray_' . json_encode($array) . '_' . $delimiter . '_' . $trim;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$array = self::mixedDataToArray($array, $delimiter);

		if (empty($array))
		{
			return $array;
		}

		if ( ! $trim)
		{
			return $array;
		}

		foreach ($array as $k => $v)
		{
			if ( ! is_string($v))
			{
				continue;
			}

			$array[$k] = trim($v);
		}

		return Cache::set(
			$cache_id,
			$array
		);
	}

	private static function mixedDataToArray($array = '', $delimiter = ',')
	{
		if ( ! is_array($array))
		{
			return explode($delimiter, $array);
		}

		if (empty($array))
		{
			return $array;
		}

		if (isset($array[0]) && is_array($array[0]))
		{
			return $array[0];
		}

		if (count($array) === 1 && strpos($array[0], $delimiter) !== false)
		{
			return explode($delimiter, $array[0]);
		}

		return $array;
	}

	private static function renameParamKeys(&$params)
	{
		$params->conditions = isset($params->conditions) ? $params->conditions : [];

		foreach ($params as $key => $value)
		{
			if (strpos($key, 'condition_') === false && strpos($key, 'assignto_') === false)
			{
				continue;
			}

			$new_key                      = substr($key, strpos($key, '_') + 1);
			$params->conditions[$new_key] = $value;

			unset($params->{$key});
		}
	}

	private static function getSelection($selection, $type = '')
	{
		if (in_array($type, self::getNotArrayTextAreaTypes()))
		{
			return $selection;
		}

		$delimiter = in_array($type, self::getTextAreaTypes()) ? "\n" : ',';

		return self::makeArray($selection, $delimiter);
	}

	private static function getSelectionFromTagAttribute($type, $value, &$params, &$reverse)
	{
		if ($type == 'Date.Date')
		{
			$value = str_replace('from', '', $value);
			$dates = explode(' - ', str_replace('to', ' - ', $value));

			$params->ignore_time_zone = true;

			if ( ! empty($dates[0]))
			{
				$params->publish_up = date('Y-m-d H:i:s', strtotime($dates[0]));
			}

			if ( ! empty($dates[1]))
			{
				$params->publish_down = date('Y-m-d H:i:s', strtotime($dates[1]));
			}

			return [];
		}

		if ($type == 'Date.Time')
		{
			$value = str_replace('from', '', $value);
			$dates = explode(' - ', str_replace('to', ' - ', $value));

			$params->publish_up   = $dates[0];
			$params->publish_down = isset($dates[1]) ? $dates[1] : $dates[0];

			return [];
		}

		if (in_array($type, self::getTextAreaTypes()))
		{
			$value = Html::convertWysiwygToPlainText($value);
		}

		if (strpos($value, '!NOT!') === 0)
		{
			$reverse = true;
			$value   = substr($value, 5);
		}

		if ( ! in_array($type, self::getNotArrayTextAreaTypes()))
		{
			$value = str_replace('[[:COMMA:]]', ',', str_replace(',', '[[:SPLIT:]]', str_replace('\\,', '[[:COMMA:]]', $value)));
			$value = explode('[[:SPLIT:]]', $value);
		}

		return $value;
	}

	private static function getDefaultParamsByType($condition_type, $type)
	{
		switch ($condition_type)
		{
			case 'Content.Category':
				return (object) [
					'assignto_' . $type . '_inc' => [
						'inc_cats',
						'inc_arts',
					],
				];

			case 'Easyblog.Category':
			case 'K2.Category':
			case 'Zoo.Category':
			case 'Hikashop.Category':
			case 'Mijoshop.Category':
			case 'Redshop.Category':
			case 'Virtuemart.Category':
				return (object) [
					'assignto_' . $type . '_inc' => [
						'inc_cats',
						'inc_items',
					],
				];

			default:
				return (object) [];
		}
	}

	private static function addParams(&$object, $type, $id, &$params)
	{
		$bool_params  = [];
		$array_params = [];
		$includes     = [];

		switch ($type)
		{
			case 'Menu':
				$bool_params = ['inc_children', 'inc_noitemid'];
				break;

			case 'Date.Date':
				$bool_params = ['publish_up', 'publish_down', 'recurring', 'ignore_time_zone'];
				break;

			case 'Date.Season':
				$bool_params = ['hemisphere'];
				break;

			case 'Date.Time':
				$bool_params = ['publish_up', 'publish_down'];
				break;

			case 'User.Grouplevel':
				$bool_params = ['inc_children'];
				break;

			case 'Url':
				if (is_array($object->selection))
				{
					$object->selection = implode("\n", $object->selection);
				}
				if (isset($params->conditions['urls_selection_sef']))
				{
					$object->selection .= "\n" . $params->conditions['urls_selection_sef'];
				}
				$object->selection     = trim(str_replace("\r", '', $object->selection));
				$object->selection     = explode("\n", $object->selection);
				$object->params->regex = isset($params->conditions['urls_regex']) ? $params->conditions['urls_regex'] : false;
				break;

			case 'Agent.Browser':
				if ( ! empty($params->conditions['mobile_selection']))
				{
					$object->selection = array_merge(self::makeArray($object->selection), self::makeArray($params->conditions['mobile_selection']));
				}
				if ( ! empty($params->conditions['searchbots_selection']))
				{
					$object->selection = array_merge($object->selection, self::makeArray($params->conditions['searchbots_selection']));
				}
				break;

			case 'Tag':
				$bool_params = ['inc_children'];
				break;

			case 'Content.Category':
				$bool_params = ['inc_children'];
				$includes    = ['cats' => 'categories', 'arts' => 'articles', 'others'];
				break;

			case 'Easyblog.Category':
			case 'K2.Category':
			case 'Hikashop.Category':
			case 'Mijoshop.Category':
			case 'Redshop.Category':
			case 'Virtuemart.Category':
				$bool_params = ['inc_children'];
				$includes    = ['cats' => 'categories', 'items'];
				break;

			case 'Zoo.Category':
				$bool_params = ['inc_children'];
				$includes    = ['apps', 'cats' => 'categories', 'items'];
				break;

			case 'Easyblog.Tag':
			case 'Flexicontent.Tag':
			case 'K2.Tag':
				$includes = ['tags', 'items'];
				break;

			case 'Content.Article':
				$bool_params = ['content_keywords', 'keywords' => 'meta_keywords', 'authors'];
				break;

			case 'K2.Item':
				$bool_params = ['content_keywords', 'meta_keywords', 'authors'];
				break;

			case 'Easyblog.Item':
				$bool_params = ['content_keywords', 'authors'];
				break;

			case 'Zoo.Item':
				$bool_params = ['authors'];
				break;
		}

		if (in_array($type, self::getMatchAllTypes()))
		{
			$bool_params[] = 'match_all';

			if (count($object->selection) == 1 && strpos($object->selection[0], '+') !== false)
			{
				$object->selection = ArrayHelper::toArray($object->selection[0], '+');
				$params->match_all = true;
			}
		}

		if (empty($bool_params) && empty($array_params) && empty($includes))
		{
			return;
		}

		self::addParamsByType($object, $id, $params, $bool_params, $array_params, $includes);
	}

	private static function addParamsByType(&$object, $id, $params, $bool_params = [], $array_params = [], $includes = [])
	{
		foreach ($bool_params as $key => $param)
		{
			$key                      = is_numeric($key) ? $param : $key;
			$object->params->{$param} = self::getTypeParamValue($id, $params, $key);
		}

		foreach ($array_params as $key => $param)
		{
			$key                      = is_numeric($key) ? $param : $key;
			$object->params->{$param} = self::getTypeParamValue($id, $params, $key, true);
		}

		if (empty($includes))
		{
			return;
		}

		$incs = self::getTypeParamValue($id, $params, 'inc', true);

		foreach ($includes as $key => $param)
		{
			$key                               = is_numeric($key) ? $param : $key;
			$object->params->{'inc_' . $param} = in_array('inc_' . $key, $incs) ? 1 : 0;
		}

		unset($object->params->inc);
	}

	private static function getTypeParamValue($id, $params, $key, $is_array = false)
	{
		if (isset($params->conditions) && isset($params->conditions[$id . '_' . $key]))
		{
			return $params->conditions[$id . '_' . $key];
		}

		if (isset($params->{'assignto_' . $id . '_' . $key}))
		{
			return $params->{'assignto_' . $id . '_' . $key};
		}

		if (isset($params->{$key}))
		{
			return $params->{$key};
		}

		if ($is_array)
		{
			return [];
		}

		return 0;
	}

	private static function getTypes($only_types = [])
	{
		$types = [
			'menuitems'             => 'Menu',
			'homepage'              => 'Homepage',
			'date'                  => 'Date.Date',
			'seasons'               => 'Date.Season',
			'months'                => 'Date.Month',
			'days'                  => 'Date.Day',
			'time'                  => 'Date.Time',
			'accesslevels'          => 'User.Accesslevel',
			'usergrouplevels'       => 'User.Grouplevel',
			'users'                 => 'User.User',
			'languages'             => 'Language',
			'ips'                   => 'Ip',
			'geocontinents'         => 'Geo.Continent',
			'geocountries'          => 'Geo.Country',
			'georegions'            => 'Geo.Region',
			'geopostalcodes'        => 'Geo.Postalcode',
			'templates'             => 'Template',
			'urls'                  => 'Url',
			'devices'               => 'Agent.Device',
			'os'                    => 'Agent.Os',
			'browsers'              => 'Agent.Browser',
			'components'            => 'Component',
			'tags'                  => 'Tag',
			'contentpagetypes'      => 'Content.Pagetype',
			'cats'                  => 'Content.Category',
			'articles'              => 'Content.Article',
			'easyblogpagetypes'     => 'Easyblog.Pagetype',
			'easyblogcats'          => 'Easyblog.Category',
			'easyblogtags'          => 'Easyblog.Tag',
			'easyblogitems'         => 'Easyblog.Item',
			'flexicontentpagetypes' => 'Flexicontent.Pagetype',
			'flexicontenttags'      => 'Flexicontent.Tag',
			'flexicontenttypes'     => 'Flexicontent.Type',
			'form2contentprojects'  => 'Form2content.Project',
			'k2pagetypes'           => 'K2.Pagetype',
			'k2cats'                => 'K2.Category',
			'k2tags'                => 'K2.Tag',
			'k2items'               => 'K2.Item',
			'zoopagetypes'          => 'Zoo.Pagetype',
			'zoocats'               => 'Zoo.Category',
			'zooitems'              => 'Zoo.Item',
			'akeebasubspagetypes'   => 'Akeebasubs.Pagetype',
			'akeebasubslevels'      => 'Akeebasubs.Level',
			'hikashoppagetypes'     => 'Hikashop.Pagetype',
			'hikashopcats'          => 'Hikashop.Category',
			'hikashopproducts'      => 'Hikashop.Product',
			'mijoshoppagetypes'     => 'Mijoshop.Pagetype',
			'mijoshopcats'          => 'Mijoshop.Category',
			'mijoshopproducts'      => 'Mijoshop.Product',
			'redshoppagetypes'      => 'Redshop.Pagetype',
			'redshopcats'           => 'Redshop.Category',
			'redshopproducts'       => 'Redshop.Product',
			'virtuemartpagetypes'   => 'Virtuemart.Pagetype',
			'virtuemartcats'        => 'Virtuemart.Category',
			'virtuemartproducts'    => 'Virtuemart.Product',
			'cookieconfirm'         => 'Cookieconfirm',
			'php'                   => 'Php',
		];

		if (empty($only_types))
		{
			return $types;
		}

		return array_intersect_key($types, array_flip($only_types));
	}

	private static function getType(&$type, $only_types = [])
	{
		$types = self::getTypes($only_types);

		if (isset($types[$type]))
		{
			return $types[$type];
		}

		// Make it plural
		$type = rtrim($type, 's') . 's';

		if (isset($types[$type]))
		{
			return $types[$type];
		}

		// Replace incorrect plural endings
		$type = str_replace('ys', 'ies', $type);

		if (isset($types[$type]))
		{
			return $types[$type];
		}

		return false;
	}

	private static function getTypeAliases()
	{
		return [
			'matching_method'  => ['method'],
			'menuitems'        => ['menu'],
			'homepage'         => ['home'],
			'date'             => ['daterange'],
			'seasons'          => [''],
			'months'           => [''],
			'days'             => [''],
			'time'             => [''],
			'accesslevels'     => ['access'],
			'usergrouplevels'  => ['usergroups', 'groups'],
			'users'            => [''],
			'languages'        => ['langs'],
			'ips'              => ['ipaddress', 'ipaddresses'],
			'geocontinents'    => ['continents'],
			'geocountries'     => ['countries'],
			'georegions'       => ['regions'],
			'geopostalcodes'   => ['postalcodes', 'postcodes'],
			'templates'        => [''],
			'urls'             => [''],
			'devices'          => [''],
			'os'               => [''],
			'browsers'         => [''],
			'components'       => [''],
			'tags'             => [''],
			'contentpagetypes' => ['pagetypes'],
			'cats'             => ['categories', 'category'],
			'articles'         => [''],
			'php'              => [''],
		];
	}

	private static function getTextAreaTypes()
	{
		return [
			'Ip',
			'Url',
			'Php',
		];
	}

	private static function getNotArrayTextAreaTypes()
	{
		return [
			'Php',
		];
	}

	public static function getMatchAllTypes()
	{
		return [
			'User.Grouplevel',
			'Tag',
		];
	}
}
