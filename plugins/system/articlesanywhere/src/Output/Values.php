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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\CurrentArticle;
use RegularLabs\Plugin\System\ArticlesAnywhere\Factory;
use RegularLabs\Plugin\System\ArticlesAnywhere\Helpers\ValueHelper;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data\Numbers;

class Values
{
	private $config;
	private $item;
	private $numbers;

	private $text_keys = [
		'text', 'introtext', 'fulltext',
		'title', 'description',
		'text', 'textarea', 'editor',
		'category-title', 'category-description',
		'metakey', 'metadesc',
	];

	private $text_hit_keys = [
		'text', 'fulltext',
	];

	public function __construct(Config $config, Item $item, Numbers $numbers)
	{
		$this->config  = $config;
		$this->item    = $item;
		$this->numbers = $numbers;
	}

	public function get($key, $default = null, $attributes = null)
	{
		if (is_null($attributes))
		{
			$attributes = (object) [];
		}

		if (strpos($key, ':') !== false)
		{
			list($key, $value_type) = explode(':', $key, 2);
			$attributes->value = $value_type;
		}

		$key   = $this->replaceAliases($key);
		$value = $this->getValue($key, $default, $attributes);

		if (is_null($value))
		{
			return null;
		}

		if (empty($attributes))
		{
			return $value;
		}

		if (isset($attributes->output)
			&& in_array($attributes->output, ['value', 'values', 'raw']))
		{
			return RL_Array::implode($value, ',');
		}

		if (in_array($key, $this->text_keys))
		{
			$value = $this->getData('Text')->process($value, $attributes);
		}

		if ($this->isDateValue($key, $value))
		{
			// Convert string if it is a date
			$value = ValueHelper::dateToString($value, $attributes);
		}

		if (in_array($key, $this->text_hit_keys))
		{
			$this->item->hit();
		}

		return RL_Array::implode($value);
	}

	public function replaceAliases($string)
	{
		$key_aliases = [
			'article' => ['layout'],
		];

		foreach ($key_aliases as $key => $aliases)
		{
			if ( ! in_array($string, $aliases))
			{
				continue;
			}

			return $key;
		}

		return $string;
	}

	public function isDateValue($key, $value)
	{
		if (
			is_array($value)
			// These keys are never dates
			|| in_array($key, $this->text_keys)
			|| in_array($key, [
				'id', 'title', 'alias',
				'category-id', 'category-title', 'category-alias', 'category-description',
				'author-id', 'author-name', 'author-username',
				'modifier-id', 'modifier-name', 'modifier-username',
			])
		)
		{
			return false;
		}

		return ValueHelper::isDateValue($value);
	}

	public function getValue($key, $default = null, $attributes = null)
	{
		if ( ! is_string($key))
		{
			return $default;
		}

		$key = trim($key);

		if (is_numeric($key))
		{
			return $key;
		}

		$key = $this->getFromAliases($key);

		if ($date = ValueHelper::placeholderToDate($key))
		{
			return $date;
		}

		switch ($key)
		{
			// Links & Urls
			case 'link':
			case 'url':
			case 'nonsefurl':
			case 'sefurl':
				return $this->getData('Url')->get($key, $attributes);

			// Full Article
			case 'article':
				return $this->getData('Layout')->get($key, $attributes);

			// Readmore
			case 'readmore':
				return $this->getData('ReadMore')->get($key, $attributes);

			// Div
			case 'div':
				return $this->getData('Div')->get($key, $attributes);

			// Closing link tag
			case  '/link':
			case  '/edit-link':
			case  '/category-link':
				return '</a>';

			// Closing div tag
			case  '/div':
				return '</div>';

		}

		// It's a main Numbers value, like [count]
		if ($this->numbers->exists($key))
		{
			return $this->numbers->get($key);
		}

		// It's an 'every' value [every-3]
		if (RL_RegEx::match('^every[-_]([0-9]+)$', $key, $match))
		{
			return $this->numbers->isEvery($match[1]);
		}

		// It's a column value, like [is_2_of_4] or  [col_3_of_5]
		if (RL_RegEx::match('^(?:is|col)[-_]([0-9]+)[-_]?of[-_]?([0-9]+)$', $key, $match))
		{
			return $this->numbers->isColumn($match[1], $match[2]);
		}

		// It's a normal article attribute
		if ( ! is_null($this->item->get($key)))
		{
			return $this->item->get($key);
		}

		$data_types = [
			'Extra',
			'Images',
		];

		foreach ($data_types as $data_type)
		{
			// It's an article attribute inside one of the param fields, like metadata
			$extradata = $this->getData($data_type)->get($key, $attributes);

			if ( ! is_null($extradata))
			{
				return $extradata;
			}
		}

		return $default;
	}

	static public function translateKey($key)
	{
		$key = RL_RegEx::replace('^(cat-|cat_|category_)', 'category-', $key);
		$key = RL_RegEx::replace('^(author|modifier|category-image)_', '\1-', $key);
		$key = RL_RegEx::replace('_(url|sefurl|link)$', '-\1', $key);

		$aliases = [
			'author-name'        => ['author'],
			'created_by_alias'   => ['created-by-alias', 'author-alias'],
			'modifier-name'      => ['modifier'],
			'category-title'     => ['category', 'cat', 'category-name'],
		];

		foreach ($aliases as $to_key => $alias_list)
		{
			if (in_array($key, $alias_list))
			{
				return $to_key;
			}
		}

		return $key;
	}

	public function getFromAliases($key)
	{
		$prefix = substr($key, 0, 1) == '/' ? '/' : '';

		$key = ltrim($key, '/');

		$key = self::translateKey($key);

		return $prefix . $key;
	}

	private function getData($name)
	{
		return Factory::getOutput($name, $this->config, $this->item, $this);
	}

	public static function getValueFromInput($value)
	{
		if (strpos($value, 'input:') !== 0)
		{
			return $value;
		}

		list($key, $value, $default) = explode(':', $value . ':none');

		return JFactory::getApplication()->input->get($value, $default);
	}
}
