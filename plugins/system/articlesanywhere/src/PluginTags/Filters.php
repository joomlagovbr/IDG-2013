<?php
/**
 * @package         Articles Anywhere
 * @version         9.2.0
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\PluginTags;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
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
		$params = Params::get();

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

		if (empty($filters['items'])
			&& $this->plugin_tag->getTagType() != Params::get()->articles_tag
			&& $id = CurrentArticle::get('id', $this->component))
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

		$filter_fields        = [];
		$filter_custom_fields = [];

		foreach ($attributes as $key => $value)
		{
			if (in_array($key, $reserved_keys))
			{
				continue;
			}

			if (in_array($key, $fields))
			{
				$this->addFilter(
					$filter_fields,
					$key,
					$this->fields->getFieldValue($key, $value)
				);

				continue;
			}

			if ($field = CustomFields::getByName($custom_fields, $key))
			{
				$this->addFilter(
					$filter_custom_fields,
					$field->id,
					$this->custom_fields->getFieldValue($key, $value)
				);

				continue;
			}
		}

		if ( ! empty($filter_fields))
		{
			$filters['fields'] = $filter_fields;
		}
		if ( ! empty($filter_custom_fields))
		{
			$filters['custom_fields'] = $filter_custom_fields;
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

			if (
				! empty($value_if_is_current)
				&& (
					$tag_value === 'current'
					|| ($tag_value != $id && in_array($tag_value, $values_equaling_current, true))
				)
			)
			{
				$this->addValues($value_if_is_current, $values, $negative);

				continue;
			}

			// It's a current article value [this:id], [this:title], etc
			if (RL_RegEx::match('^this:([a-z0-9_\-]+)$', $tag_value, $match))
			{
				$this->addValues(CurrentArticle::get($match[1]), $values, $negative);

				continue;
			}

			// It's a user value [user:id], [user:name], etc
			if (RL_RegEx::match('^user:([a-z0-9_\-]+)$', $tag_value, $match))
			{
				$this->addValues(JFactory::getUser()->get($match[1]), $values, $negative);

				continue;
			}

			// It's an input value [input:id], [input:name:default], etc
			if (RL_RegEx::match('^input:([^"]+)$', $tag_value, $match))
			{
				list($value, $default) = explode(':', $match[1] . ':none');

				$this->addValues(JFactory::getApplication()->input->get($value, $default), $values, $negative);

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
			$values = [$values];
		}

		$values = RL_Array::trim($values);

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
