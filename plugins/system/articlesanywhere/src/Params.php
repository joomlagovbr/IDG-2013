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

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;

class Params
{
	protected static $params         = null;
	protected static $content_config = null;
	protected static $regexes        = null;
	protected static $view_levels    = null;

	public static function get()
	{
		if ( ! is_null(self::$params))
		{
			return self::$params;
		}

		$params = RL_Parameters::getInstance()->getPluginParams('articlesanywhere');

		$params->article_tag = RL_PluginTag::clean($params->article_tag);

		

		self::$params = $params;

		return self::$params;
	}

	public static function getTagNames()
	{
		$params = self::get();

		return
			[
				$params->article_tag,
			];
	}

	public static function getTags($only_start_tags = false)
	{
		list($tag_start, $tag_end) = self::getTagCharacters();

		$tags = [[], []];

		foreach (self::getTagNames() as $tag)
		{
			$tags[0][] = $tag_start . $tag;
			$tags[0][] = $tag_start . '/' . $tag . $tag_end;
		}

		return $only_start_tags ? $tags[0] : $tags;
	}

	public static function getRegex($type = 'tag')
	{
		$regexes = self::getRegexes();

		return isset($regexes->{$type}) ? $regexes->{$type} : $regexes->tag;
	}

	private static function getRegexes()
	{
		if ( ! is_null(self::$regexes))
		{
			return self::$regexes;
		}

		// Tag character start and end
		list($tag_start, $tag_end) = Params::getTagCharacters();
		$tag_start = RL_RegEx::quote($tag_start);
		$tag_end   = RL_RegEx::quote($tag_end);

		$pre        = RL_PluginTag::getRegexSurroundingTagsPre();
		$post       = RL_PluginTag::getRegexSurroundingTagsPost();
		$inside_tag = RL_PluginTag::getRegexInsideTag();
		$spaces     = RL_PluginTag::getRegexSpaces();

		self::$regexes = (object) [];

		$tags = RL_RegEx::quote(self::getTagNames(), 'tag');

		self::$regexes->tag =
			'(?<opening_tags_before_open>' . $pre . ')'
			. $tag_start . $tags . '(?:' . $spaces . '(?<id>' . $inside_tag . '))?' . $tag_end
			. '(?<closing_tags_after_open>' . $post . ')'
			. '\s*'
			. '(?<opening_tags_before_content>' . $pre . ')'
			. '(?<content>.*?)'
			. '(?<closing_tags_after_content>' . $post . ')'
			. '\s*'
			. '(?<opening_tags_before_close>' . $pre . ')'
			. $tag_start . '/\2' . $tag_end
			. '(?<closing_tags_after_close>' . $post . ')';

		return self::$regexes;
	}

	public static function getTagCharacters()
	{
		$params = self::get();

		if ( ! isset($params->tag_character_start))
		{
			self::setTagCharacters();
		}

		return [$params->tag_character_start, $params->tag_character_end];
	}

	public static function setTagCharacters()
	{
		$params = self::get();

		list(self::$params->tag_character_start, self::$params->tag_character_end) = explode('.', $params->tag_characters);
	}

	public static function getDataTagCharacters()
	{
		$params = self::get();

		if ( ! isset($params->tag_character_data_start))
		{
			self::setDataTagCharacters();
		}

		return [$params->tag_character_data_start, $params->tag_character_data_end];
	}

	public static function setDataTagCharacters()
	{
		$params = self::get();

		list(self::$params->tag_character_data_start, self::$params->tag_character_data_end) = explode('.', $params->tag_characters_data);
	}

	public static function getAuthorisedViewLevels()
	{
		if ( ! is_null(self::$view_levels))
		{
			return self::$view_levels;
		}

		self::$view_levels = JFactory::getUser()->getAuthorisedViewLevels();
		self::$view_levels = array_unique(self::$view_levels);

		if (empty(self::$view_levels))
		{
			self::$view_levels = [0];
		}

		return self::$view_levels;
	}
}
