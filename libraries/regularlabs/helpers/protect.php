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

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Protect as RL_Protect;

class RLProtect
{
	public static function isProtectedPage($extension_alias = '', $hastags = false, $exclude_formats = ['pdf'])
	{
		if ( ! class_exists('RegularLabs\Library\Protect'))
		{
			return true;
		}

		if (RL_Protect::isDisabledByUrl($extension_alias))
		{
			return true;
		}

		return class_exists('RegularLabs\Library\Protect') && RL_Protect::isRestrictedPage($hastags, $exclude_formats);
	}

	public static function isAdmin($block_login = false)
	{
		return class_exists('RegularLabs\Library\Document') && RL_Document::isAdmin($block_login);
	}

	public static function isEditPage()
	{
		return class_exists('RegularLabs\Library\Document') && RL_Document::isEditPage();
	}

	public static function isRestrictedComponent($restricted_components, $area = 'component')
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::isRestrictedComponent($restricted_components, $area);
	}

	public static function isComponentInstalled($extension_alias)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::isComponentInstalled($extension_alias);
	}

	public static function isSystemPluginInstalled($extension_alias)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::isSystemPluginInstalled($extension_alias);
	}

	public static function getFormRegex($regex_format = false)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::getFormRegex($regex_format);
	}

	public static function protectFields(&$string, $search_strings = [])
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectFields($string, $search_strings);
	}

	public static function protectScripts(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectScripts($string);
	}

	public static function protectHtmlTags(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectHtmlTags($string);
	}

	public static function protectByRegex(&$string, $regex)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectByRegex($string, $regex);
	}

	public static function protectTags(&$string, $tags = [], $include_closing_tags = true)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectTags($string, $tags, $include_closing_tags);
	}

	public static function unprotectTags(&$string, $tags = [], $include_closing_tags = true)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectTags($string, $tags, $include_closing_tags);
	}

	public static function protectInString(&$string, $unprotected = [], $protected = [])
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectInString($string, $unprotected, $protected);
	}

	public static function unprotectInString(&$string, $unprotected = [], $protected = [])
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectInString($string, $unprotected, $protected);
	}

	public static function protectSourcerer(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectSourcerer($string);
	}

	public static function protectForm(&$string, $tags = [], $include_closing_tags = true)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::protectForm($string, $tags, $include_closing_tags);
	}

	public static function unprotect(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotect($string);
	}

	public static function convertProtectionToHtmlSafe(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::convertProtectionToHtmlSafe($string);
	}

	public static function unprotectHtmlSafe(&$string)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectHtmlSafe($string);
	}

	public static function protectString($string, $is_tag = false)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::protectString($string, $is_tag);
	}

	public static function unprotectString($string, $is_tag = false)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectString($string, $is_tag);
	}

	public static function protectTag($string)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::protectTag($string);
	}

	public static function protectArray($array, $is_tag = false)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::protectArray($array, $is_tag);
	}

	public static function unprotectArray($array, $is_tag = false)
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectArray($array, $is_tag);
	}

	public static function unprotectForm(&$string, $tags = [])
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::unprotectForm($string, $tags);
	}

	public static function removeInlineComments(&$string, $name)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::removeInlineComments($string, $name);
	}

	public static function removePluginTags(&$string, $tags, $character_start = '{', $character_end = '{', $keep_content = true)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::removePluginTags($string, $tags, $character_start, $character_end, $keep_content);
	}

	public static function removeFromHtmlTagContent(&$string, $tags, $include_closing_tags = true, $html_tags = ['title'])
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::removeFromHtmlTagContent($string, $tags, $include_closing_tags, $html_tags);
	}

	public static function removeFromHtmlTagAttributes(&$string, $tags, $attributes = 'ALL', $include_closing_tags = true)
	{
		class_exists('RegularLabs\Library\Protect') && RL_Protect::removeFromHtmlTagAttributes($string, $tags, $attributes, $include_closing_tags);
	}

	public static function articlePassesSecurity(&$article, $securtiy_levels = [])
	{
		return class_exists('RegularLabs\Library\Protect') && RL_Protect::articlePassesSecurity($article, $securtiy_levels);
	}

	public static function isJoomla3()
	{
		return true;
	}
}
