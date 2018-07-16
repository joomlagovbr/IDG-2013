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

use RegularLabs\Library\Alias as RL_Alias;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\Date as RL_Date;
use RegularLabs\Library\Form as RL_Form;
use RegularLabs\Library\Html as RL_Html;
use RegularLabs\Library\HtmlTag as RL_HtmlTag;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;
use RegularLabs\Library\Title as RL_Title;
use RegularLabs\Library\Uri as RL_Uri;

class RLText
{
	/* Date functions */

	public static function fixDate(&$date)
	{
		$date = RL_Date::fix($date);
	}

	public static function fixDateOffset(&$date)
	{
		RL_Date::applyTimezone($date);
	}

	public static function dateToDateFormat($dateFormat)
	{
		return RL_Date::strftimeToDateFormat($dateFormat);
	}

	public static function dateToStrftimeFormat($dateFormat)
	{
		return RL_Date::dateToStrftimeFormat($dateFormat);
	}

	/* String functions */

	public static function html_entity_decoder($string, $quote_style = ENT_QUOTES, $charset = 'UTF-8')
	{
		return RL_String::html_entity_decoder($string, $quote_style, $charset);
	}

	public static function stringContains($haystacks, $needles)
	{
		return RL_String::contains($haystacks, $needles);
	}

	public static function is_alphanumeric($string)
	{
		return RL_String::is_alphanumeric($string);
	}

	public static function splitString($string, $delimiters = [], $max_length = 10000, $maximize_parts = true)
	{
		return RL_String::split($string, $delimiters, $max_length, $maximize_parts);
	}

	public static function strReplaceOnce($search, $replace, $string)
	{
		return RL_String::replaceOnce($search, $replace, $string);
	}

	/* Array functions */

	public static function toArray($data, $separator = '')
	{
		return RL_Array::toArray($data, $separator);
	}

	public static function createArray($data, $separator = ',')
	{
		return RL_Array::toArray($data, $separator, true);
	}

	/* RegEx functions */

	public static function regexReplace($pattern, $replacement, $string)
	{
		return RL_RegEx::replace($pattern, $replacement, $string);
	}

	public static function pregQuote($string = '', $delimiter = '#')
	{
		return RL_RegEx::quote($string, $delimiter);
	}

	public static function pregQuoteArray($array = [], $delimiter = '#')
	{
		return RL_RegEx::quoteArray($array, $delimiter);
	}

	/* Title functions */

	public static function cleanTitle($string, $strip_tags = false, $strip_spaces = true)
	{
		return RL_Title::clean($string, $strip_tags, $strip_spaces);
	}

	public static function createUrlMatches($titles = [])
	{
		return RL_Title::getUrlMatches($titles);
	}

	/* Alias functions */

	public static function createAlias($string)
	{
		return RL_Alias::get($string);
	}

	/* Uri functions */

	public static function getURI($hash = '')
	{
		return RL_Uri::get($hash);
	}

	/* Plugin Tag functions */

	public static function getTagRegex($tags, $include_no_attributes = true, $include_ending = true, $required_attributes = [])
	{
		return RL_PluginTag::getRegexTags($tags, $include_no_attributes, $include_ending, $required_attributes);
	}

	/* HTML functions */
	public static function getBody($html)
	{
		return RL_Html::getBody($html);
	}

	public static function getContentContainingSearches($string, $start_searches = [], $end_searches = [], $start_offset = 1000, $end_offset = null)
	{
		return RL_Html::getContentContainingSearches($string, $start_searches, $end_searches, $start_offset, $end_offset);
	}

	public static function convertWysiwygToPlainText($string)
	{
		return RL_Html::convertWysiwygToPlainText($string);
	}

	public static function combinePTags(&$string)
	{
		RL_Html::combinePTags($string);
	}

	/* HTML Tag functions */

	public static function combineTags($tag1, $tag2)
	{
		return RL_HtmlTag::combine($tag1, $tag2);
	}

	public static function getAttribute($key, $string)
	{
		return RL_HtmlTag::getAttributeValue($key, $string);
	}

	public static function getAttributes($string)
	{
		return RL_HtmlTag::getAttributes($string);
	}

	public static function combineAttributes($string1, $string2)
	{
		return RL_HtmlTag::combineAttributes($string1, $string2);
	}

	/* Form functions */

	public static function prepareSelectItem($string, $published = 1, $type = '', $remove_first = 0)
	{
		return RL_Form::prepareSelectItem($string, $published, $type, $remove_first);
	}
}
