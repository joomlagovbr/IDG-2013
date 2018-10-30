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

/* @DEPRECATED */

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use RegularLabs\Library\Html as RL_Html;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;

class RLTags
{
	static $protected_characters = [
		'=' => '[[:EQUAL:]]',
		'"' => '[[:QUOTE:]]',
		',' => '[[:COMMA:]]',
		'|' => '[[:BAR:]]',
		':' => '[[:COLON:]]',
	];

	public static function getValuesFromString($string = '', $main_key = 'title', $known_boolean_keys = [], $keep_escaped = [','])
	{
		return RL_PluginTag::getAttributesFromString($string, $main_key, $known_boolean_keys, $keep_escaped);
	}

	public static function protectSpecialChars(&$string)
	{
		RL_PluginTag::protectSpecialChars($string);
	}

	public static function unprotectSpecialChars(&$string, $keep_escaped_chars = [])
	{
		RL_PluginTag::unprotectSpecialChars($string, $keep_escaped_chars);
	}

	public static function replaceKeyAliases(&$values, $key_aliases = [], $handle_plurals = false)
	{
		RL_PluginTag::replaceKeyAliases($values, $key_aliases, $handle_plurals);
	}

	public static function convertOldSyntax(&$values, $known_boolean_keys = [], $extra_key = 'class')
	{
		RL_PluginTag::convertOldSyntax($values, $known_boolean_keys, $extra_key);
	}

	public static function getRegexSpaces($modifier = '+')
	{
		return RL_PluginTag::getRegexSpaces($modifier);
	}

	public static function getRegexInsideTag()
	{
		return RL_PluginTag::getRegexInsideTag();
	}

	public static function getRegexSurroundingTagPre($elements = ['p', 'span'])
	{
		return RL_PluginTag::getRegexSurroundingTagPre($elements);
	}

	public static function getRegexSurroundingTagPost($elements = ['p', 'span'])
	{
		return RL_PluginTag::getRegexSurroundingTagPost($elements);
	}

	public static function getRegexTags($tags, $include_no_attributes = true, $include_ending = true, $required_attributes = [])
	{
		return RL_PluginTag::getRegexTags($tags, $include_no_attributes, $include_ending, $required_attributes);
	}

	public static function fixBrokenHtmlTags($string)
	{
		return RL_Html::fix($string);
	}

	public static function cleanSurroundingTags($tags, $elements = ['p', 'span'])
	{
		return RL_Html::cleanSurroundingTags($tags, $elements);
	}

	public static function fixSurroundingTags($tags)
	{
		return RL_Html::fixArray($tags);
	}

	public static function removeEmptyHtmlTagPairs($string, $elements = ['p', 'span'])
	{
		return RL_Html::removeEmptyTagPairs($string, $elements);
	}

	public static function getDivTags($start_tag = '', $end_tag = '', $tag_start = '{', $tag_end = '}')
	{
		$tag_start = RL_RegEx::unquote($tag_start);
		$tag_end   = RL_RegEx::unquote($tag_end);

		return RL_PluginTag::getDivTags($start_tag, $end_tag, $tag_start, $tag_end);
	}

	public static function getTagValues($string = '', $keys = ['title'], $separator = '|', $equal = '=', $limit = 0)
	{
		return RL_PluginTag::getAttributesFromStringOld($string, $keys, $separator, $equal, $limit);
	}

	/* @Deprecated */

	public static function setSurroundingTags($pre, $post, $tags = 0)
	{
		if ($tags == 0)
		{
			// tags that have a matching ending tag
			$tags = [
				'div', 'p', 'span', 'pre', 'a',
				'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'strong', 'b', 'em', 'i', 'u', 'big', 'small', 'font',
				// html 5 stuff
				'header', 'nav', 'section', 'article', 'aside', 'footer',
				'figure', 'figcaption', 'details', 'summary', 'mark', 'time',
			];
		}

		$a = explode('<', $pre);
		$b = explode('</', $post);

		if (count($b) < 2 || count($a) < 2)
		{
			return [trim($pre), trim($post)];
		}

		$a      = array_reverse($a);
		$a_pre  = array_pop($a);
		$b_pre  = array_shift($b);
		$a_tags = $a;

		foreach ($a_tags as $i => $a_tag)
		{
			$a[$i]      = '<' . trim($a_tag);
			$a_tags[$i] = RL_RegEx::replace('^([a-z0-9]+).*$', '\1', trim($a_tag));
		}

		$b_tags = $b;

		foreach ($b_tags as $i => $b_tag)
		{
			$b[$i]      = '</' . trim($b_tag);
			$b_tags[$i] = RL_RegEx::replace('^([a-z0-9]+).*$', '\1', trim($b_tag));
		}

		foreach ($b_tags as $i => $b_tag)
		{
			if (empty($b_tag) || ! in_array($b_tag, $tags))
			{
				continue;
			}

			foreach ($a_tags as $j => $a_tag)
			{
				if ($b_tag != $a_tag)
				{
					continue;
				}

				$a_tags[$i] = '';
				$b[$i]      = trim(RL_RegEx::replace('^</' . $b_tag . '.*?>', '', $b[$i]));
				$a[$j]      = trim(RL_RegEx::replace('^<' . $a_tag . '.*?>', '', $a[$j]));
				break;
			}
		}

		foreach ($a_tags as $i => $tag)
		{
			if (empty($tag) || ! in_array($tag, $tags))
			{
				continue;
			}

			array_unshift($b, trim($a[$i]));
			$a[$i] = '';
		}

		$a = array_reverse($a);
		list($pre, $post) = [implode('', $a), implode('', $b)];

		return [trim($pre), trim($post)];
	}
}
