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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output;

use JText;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\Protect as RL_Protect;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class DataTags extends OutputObject
{
	public function handle(&$content)
	{
		list($data_tag_start, $data_tag_end) = Params::getDataTagCharacters();

		$spaces = RL_PluginTag::getRegexSpaces();

		$regex_datatags = RL_RegEx::quote($data_tag_start)
			. '(?<type>/?[a-z][a-z0-9-_\:]*)(?:' . $spaces . '(?<attributes>.*?))?'
			. RL_RegEx::quote($data_tag_end);
		RL_RegEx::matchAll($regex_datatags, $content, $matches);

		if (empty($matches))
		{
			return;
		}

		$tags = RL_RegEx::quote(Params::getTagNames(), 'tag');
		list($tag_start, $tag_end) = Params::getTagCharacters();

		$regex_plugintags = RL_RegEx::quote($tag_start) . $tags
			. '.*?'
			. RL_RegEx::quote($tag_start) . '/\1' . RL_RegEx::quote($tag_end);

		foreach ($matches as $match)
		{
			$value = $this->getValueFromTag($match);

			if (is_null($value))
			{
				continue;
			}

			$content = str_replace($match[0], $value, $content);

			// Protect Articles Anywhere tags to prevent nested stuff getting replaced
			RL_Protect::protectByRegex(
				$content,
				$regex_plugintags
			);
		}
	}

	private function getValueFromTag($tag)
	{
		$tag = $this->getTagValues($tag);

		$value = $this->values->get($tag->type, null, $tag->attributes);

		if (is_bool($value))
		{
			$value = $value ? JText::_('JYES') : JText::_('JNO');
		}

		return $value;
	}

	private function getTagValues($tag)
	{
		$type       = $tag['type'];
		$attributes = isset($tag['attributes']) ? $tag['attributes'] : '';

		$attributes = $this->getTagValuesFromString($type, $attributes);

		$key_aliases = [
			'limit'      => ['letters', 'letter_limit', 'characters', 'character_limit'],
			'words'      => ['word', 'word_limit'],
			'strip'      => ['trim'],
			'paragraphs' => ['paragraph', 'paragraph_limit'],
			'class'      => ['classes'],
		];

		RL_PluginTag::replaceKeyAliases($attributes, $key_aliases);

		return (object) compact('type', 'attributes');
	}

	private function getTagValuesFromString($type, $attributes)
	{
		if (empty($attributes))
		{
			return (object) [];
		}

		if ($type == 'article' && strpos($attributes, '=') === false)
		{
			$attributes = 'article layout="' . trim($attributes) . '"';
		}

		return RL_PluginTag::getAttributesFromString($attributes);
	}
}
