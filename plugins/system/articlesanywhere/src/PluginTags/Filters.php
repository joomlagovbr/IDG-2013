<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\PluginTags;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields\CustomFields;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields\Fields;
use RegularLabs\Plugin\System\ArticlesAnywhere\CurrentArticle;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

class Filters
{
	protected $component;
	protected $plugin_tag;
	protected $fields;
	protected $custom_fields;

	public function __construct($component, PluginTag $plugin_tag, Fields $fields, CustomFields $custom_fields)
	{
		$this->component     = $component;
		$this->plugin_tag    = $plugin_tag;
		$this->fields        = $fields;
		$this->custom_fields = $custom_fields;
	}

	public function get(&$attributes)
	{
		$filters = [];

		if (isset($attributes->items))
		{
			$filters['items'] = $this->getIds($attributes->items);

			// If only a list of articles is given, don't use an ordering, but use order given in tag
			if ( ! isset($attributes->ordering)
				&& strpos($attributes->items, '*') === false
			)
			{
				$attributes->ordering = 'none';
			}

			unset($attributes->items);
		}

			if (empty($filters['items']) && $id = CurrentArticle::get('id', $this->component))
			{
				$filters['items'] = [$id];
			}

			return $filters;
	}

	protected function groupNotIds($filters)
	{
		$grouped = [];

		foreach ($filters as $group => &$filter)
		{
			$grouped[$group] = $this->getGroupedFilter($filter);
		}

		return $grouped;
	}

	protected function getGroupedFilter($filter)
	{
		foreach ($filter as $key => $value)
		{
			unset($filter[$key]);

			if (empty($value))
			{
				continue;
			}

			$filter[$key] = $value;
		}

		return $filter;
	}

	protected function addFilter(&$filter, $key, $value)
	{
//		if (is_null($value))
//		{
//			return;
//		}

		$filter[$key] = $value;
	}

	protected function getIds($ids)
	{
		return $this->getIdValues(
			$this->getIdsArray($ids),
			CurrentArticle::get('id', $this->component),
			['id', 'title', 'alias']
		);
	}


	private function setOtherFieldFilters(&$filters, &$attributes)
	{
		$fields        = $this->fields->getAvailableFields();
		$custom_fields = $this->custom_fields->getAvailableFields();

		$reserved_keys = [
			'items',
			'type',
			'categories',
			'tags',
			'limit',
			'ordering',
			'separator',
			'empty',
		];

		$filters['fields']        = [];
		$filters['custom_fields'] = [];

		foreach ($attributes as $key => $value)
		{
			if (in_array($key, $reserved_keys))
			{
				continue;
			}

			if (in_array($key, $fields))
			{
				$this->addFilter(
					$filters['fields'],
					$key,
					$this->fields->getFieldValue($key, $value)
				);

				continue;
			}

			if (in_array($key, $custom_fields))
			{
				$field_id = array_search($key, $custom_fields);

				$this->addFilter(
					$filters['custom_fields'],
					$field_id,
					$this->custom_fields->getFieldValue($key, $value)
				);

				continue;
			}
		}
	}

	protected function getIdValues($ids, $value_if_is_current, $values_equaling_current = [])
	{
		if (empty($ids))
		{
			return [];
		}

		list($tag_start, $tag_end) = Params::getDataTagCharacters();
		$tag_start = RL_RegEx::quote($tag_start);
		$tag_end   = RL_RegEx::quote($tag_end);

		$value_if_is_current     = RL_Array::toArray($value_if_is_current);
		$values_equaling_current = RL_Array::toArray($values_equaling_current);

		$values = [];

		// Check for current tags
		foreach ($ids as $id)
		{
			$tag_value = RL_RegEx::replace('^' . $tag_start . '(.*)' . $tag_end . '$', '\1', $id);

			$negative = strpos($tag_value, '!NOT!') !== false;

			$tag_value = RL_RegEx::replace('^!NOT!', '', $tag_value);

			if ($tag_value === 'current'
				|| ($tag_value != $id && in_array($tag_value, $values_equaling_current, true)))
			{
				$this->addValues($value_if_is_current, $values, $negative);

				continue;
			}

			// It's a current article value [this:id], [this:title], etc
			if (RL_RegEx::match('^this:([a-z_\-0-9]+)$', $tag_value, $match))
			{
				$this->addValues(CurrentArticle::get($match[1]), $values, $negative);

				continue;
			}

			// It's a a user value [user:id], [user:name]
			if (RL_RegEx::match('^user:([a-z_\-0-9]+)$', $tag_value, $match))
			{
				$this->addValues(JFactory::getUser()->get($match[1]), $values, $negative);

				continue;
			}

			$this->addValues($tag_value, $values, $negative);

			if ($id === 'true')
			{
				$this->addValues(1, $values, $negative);
				continue;
			}

			if ($id === 'false')
			{
				$this->addValues(0, $values, $negative);
				continue;
			}
		}

		$values = RL_Array::clean($values);

		return array_values($values);
	}

	protected function addValues($values, &$ids, $negative = false)
	{
		if ( ! is_array($values))
		{
			$values = RL_Array::toArray($values);
		}

		RL_Array::trim($values);

		foreach ($values as $value)
		{
			$ids[] = ($negative ? '!NOT!' : '') . $value;
		}
	}

	protected function getIdsArray($ids)
	{
		if (empty($ids))
		{
			return [];
		}



		return [$ids];
	}

}
