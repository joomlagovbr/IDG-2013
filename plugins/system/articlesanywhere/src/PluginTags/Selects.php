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

use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields\CustomFields;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields\Fields;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class Selects
{
	protected $component;
	protected $custom_fields;

	public function __construct($component, Fields $fields, CustomFields $custom_fields)
	{
		$this->component     = $component;
		$this->custom_fields = $custom_fields->getAvailableFields();
		$this->ignore_words  = array_merge($fields->getAvailableFields(), [
			'DESC', 'ASC',
		]);
	}

	public function get($string, $ordering)
	{
		$selects = [
			'users'         => false,
			'modifiers'     => false,
			'categories'    => false,
			'custom_fields' => [],
		];

		if (empty($string))
		{
			return $selects;
		}

		if ($ordering)
		{
			$this->addSelectFromString($ordering, $selects);
		}

		$string = str_replace('&nbsp;', ' ', $string);

		list($tag_start, $tag_end) = Params::getTagCharacters();
		list($data_tag_start, $data_tag_end) = Params::getDataTagCharacters();

		// Check if there are any tags found in the content
		$regex = '(?:'
			. RL_RegEx::quote($tag_start) . '(?:if|else if|elseif|else) (?<ifs>[a-z].*?)' . RL_RegEx::quote($tag_end)
			. '|' . RL_RegEx::quote($data_tag_start) . '(?<tags>[a-z].*?)' . RL_RegEx::quote($data_tag_end)
			. ')';

		if ( ! RL_RegEx::matchAll($regex, $string, $matches, null, PREG_PATTERN_ORDER))
		{
			return $selects;
		}

		$keys = RL_Array::clean($matches['tags']);
		$ifs  = RL_Array::clean($matches['ifs']);

		$keys = array_map(function ($key) {
			return RL_RegEx::replace('[ :].*', '', $key);
		}, $keys);

		foreach ($keys as $key)
		{
			$this->addSelectFromString($key, $selects);
		}

		foreach ($ifs as $if)
		{
			$this->addSelectFromString($if, $selects);
		}

		return $selects;
	}

	protected function addSelectFromString($string, &$selects)
	{
		$parts = $this->getPartsFromString($string);

		foreach ($parts as $string)
		{
			$this->addSelect($string, $selects);
		}
	}

	protected function addSelect($key, &$selects)
	{
		if (in_array($key, $this->ignore_words))
		{
			return;
		}

		if (strpos($key, 'author') === 0)
		{
			$selects['users'] = true;

			return;
		}

		if (strpos($key, 'modifier') === 0)
		{
			$selects['modifiers'] = true;

			return;
		}

		if (strpos($key, 'category') !== false)
		{
			$selects['categories'] = true;

			return;
		}

	}

	protected function getPartsFromString($string)
	{
		$string = RL_RegEx::replace('(".*?"|\'.*?\')', '', $string);
		$string = RL_RegEx::replace('[^a-z0-9-_]', ' ', $string);

		$parts = preg_split('# +#i', $string);

		$parts = array_map(function ($part) {
			return RL_RegEx::replace('^[^a-z]*', '', $part);
		}, $parts);

		return RL_Array::clean($parts);
	}
}
