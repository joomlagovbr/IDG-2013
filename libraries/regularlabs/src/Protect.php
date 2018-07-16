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

use JAccess;
use JFactory;
use JFile;

/**
 * Class Protect
 * @package RegularLabs\Library
 */
class Protect
{
	static $protect_start        = '<!-- ___RL_PROTECTED___';
	static $protect_end          = '___RL_PROTECTED___ -->';
	static $protect_tags_start   = '<!-- ___RL_PROTECTED_TAGS___';
	static $protect_tags_end     = '___RL_PROTECTED_TAGS___ -->';
	static $html_safe_start      = '___RL_PROTECTED___';
	static $html_safe_end        = '___/RL_PROTECTED___';
	static $html_safe_tags_start = '___RL_PROTECTED_TAGS___';
	static $html_safe_tags_end   = '___/RL_PROTECTED_TAGS___';
	static $sourcerer_tag        = null;
	static $sourcerer_characters = '{.}';

	/**
	 * Check if page should be protected for given extension
	 *
	 * @param string $extension_alias
	 *
	 * @return bool
	 */
	public static function isDisabledByUrl($extension_alias = '')
	{
		// return if disabled via url
		if (($extension_alias && JFactory::getApplication()->input->get('disable_' . $extension_alias)))
		{
			return true;
		}
	}

	/**
	 * Check if page should be protected for given extension
	 *
	 * @param bool  $hastags
	 * @param array $restricted_formats
	 *
	 * @return bool
	 */
	public static function isRestrictedPage($hastags = false, $restricted_formats = [])
	{
		$cache_id = 'isRestrictedPage_' . $hastags . '_' . json_encode($restricted_formats);

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$input = JFactory::getApplication()->input;

		// return if current page is in protected formats
		// return if current page is an image
		// return if current page is an installation page
		// return if current page is Regular Labs QuickPage
		// return if current page is a JoomFish or Josetta page
		$is_restricted = (
			in_array($input->get('format'), $restricted_formats)
			|| in_array($input->get('view'), ['image', 'img'])
			|| in_array($input->get('type'), ['image', 'img'])
			|| in_array($input->get('task'), ['install.install', 'install.ajax_upload'])
			|| ($hastags
				&& (
					$input->getInt('rl_qp', 0)
					|| in_array($input->get('option'), ['com_joomfishplus', 'com_josetta'])
				)
			)
			|| (Document::isClient('administrator')
				&& in_array($input->get('option'), ['com_jdownloads'])
			)
		);

		return Cache::set(
			$cache_id,
			$is_restricted
		);
	}

	/**
	 * @deprecated Use isDisabledByUrl() and isRestrictedPage()
	 */
	public static function isProtectedPage($extension_alias = '', $hastags = false, $exclude_formats = [])
	{
		if (self::isDisabledByUrl($extension_alias))
		{
			return true;
		}

		return self::isRestrictedPage($hastags, $exclude_formats);
	}

	/**
	 * Check if the page is a restricted component
	 *
	 * @param array  $restricted_components
	 * @param string $area
	 *
	 * @return bool
	 */
	public static function isRestrictedComponent($restricted_components, $area = 'component')
	{
		if ($area != 'component' && ! ($area == 'article' && JFactory::getApplication()->input->get('option') == 'com_content'))
		{
			return false;
		}

		$restricted_components =
			is_array($restricted_components)
				? $restricted_components
				: explode(',', str_replace('|', ',', $restricted_components));

		if (in_array(JFactory::getApplication()->input->get('option'), $restricted_components))
		{
			return true;
		}

		if (JFactory::getApplication()->input->get('option') == 'com_acymailing'
			&& ! in_array(JFactory::getApplication()->input->get('ctrl'), ['user', 'archive'])
		)
		{
			return true;
		}

		return false;
	}

	/**
	 * Check if the component is installed
	 *
	 * @param string $extension_alias
	 *
	 * @return bool
	 */
	public static function isComponentInstalled($extension_alias)
	{
		return JFile::exists(JPATH_ADMINISTRATOR . '/components/com_' . $extension_alias . '/' . $extension_alias . '.php');
	}

	/**
	 * Check if the component is installed
	 *
	 * @param string $extension_alias
	 *
	 * @return bool
	 */
	public static function isSystemPluginInstalled($extension_alias)
	{
		return JFile::exists(JPATH_PLUGINS . '/system/' . $extension_alias . '/' . $extension_alias . '.php');
	}

	/**
	 * Return the Regular Expressions string to match:
	 * The edit form
	 *
	 * @param int $regex_format
	 *
	 * @return string
	 */
	public static function getFormRegex()
	{
		return '(<form\s[^>]*('
			. '(id|name)="(adminForm|postform|submissionForm|default_action_user|seblod_form|spEntryForm)"'
			. '|action="[^"]*option=com_myjspace&(amp;)?view=see"'
			. '))';
	}

	/**
	 * Protect all text based form fields
	 *
	 * @param string $string
	 * @param array  $search_strings
	 */
	public static function protectFields(&$string, $search_strings = [])
	{
		// No specified strings tags found in the string
		if ( ! self::containsStringsToProtect($string, $search_strings))
		{
			return;
		}

		$parts = StringHelper::split($string, ['</label>', '</select>']);

		foreach ($parts as &$part)
		{
			if ( ! self::containsStringsToProtect($part, $search_strings))
			{
				continue;
			}

			self::protectFieldsPart($part);
		}

		$string = implode('', $parts);
	}

	/**
	 * Check if the string contains certain substrings to protect
	 *
	 * @param string $string
	 * @param array  $search_strings
	 *
	 * @return bool
	 */
	private static function containsStringsToProtect($string, $search_strings = [])
	{
		if (
			empty($string)
			|| (
				strpos($string, '<input') === false
				&& strpos($string, '<textarea') === false
				&& strpos($string, '<select') === false
			)
		)
		{
			return false;
		}

		// No specified strings tags found in the string
		if ( ! empty($search_strings) && ! StringHelper::contains($string, $search_strings))
		{
			return false;
		}

		return true;
	}

	/**
	 * Protect the fields in the string
	 *
	 * @param string $string
	 */
	private static function protectFieldsPart(&$string)
	{
		self::protectFieldsTextAreas($string);
		self::protectFieldsInputFields($string);
	}

	/**
	 * Protect the textarea fields in the string
	 *
	 * @param string $string
	 */
	private static function protectFieldsTextAreas(&$string)
	{
		if (strpos($string, '<textarea') === false)
		{
			return;
		}

		// Only replace non-empty textareas
		// Todo: maybe also prevent empty textareas but with a non-empty placeholder attribute

		// Temporarily replace empty textareas
		$temp_tag = '___TEMP_TEXTAREA___';
		$string   = RegEx::replace(
			'<textarea((?:\s[^>]*)?)>(\s*)</textarea>',
			'<' . $temp_tag . '\1>\2</' . $temp_tag . '>',
			$string
		);

		self::protectByRegex(
			$string,
			'(?:'
			. '<textarea.*?</textarea>'
			. '\s*)+'
		);

		// Replace back the temporarily replaced empty textareas
		$string = str_replace($temp_tag, 'textarea', $string);
	}

	/**
	 * Protect the input fields in the string
	 *
	 * @param string $string
	 */
	private static function protectFieldsInputFields(&$string)
	{
		if (strpos($string, '<input') === false)
		{
			return;
		}

		$type_values = '(?:text|email|hidden)';
		// must be of certain type
		$param_type = '\s+type\s*=\s*(?:"' . $type_values . '"|\'' . $type_values . '\'])';
		// must have a non-empty value or placeholder attribute
		$param_value = '\s+(?:value|placeholder)\s*=\s*(?:"[^"]+"|\'[^\']+\'])';
		// Regex to match any other parameter
		$params = '(?:\s+[a-z][a-z0-9-_]*(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[0-9]+))?)*';

		self::protectByRegex(
			$string,
			'(?:(?:'
			. '<input' . $params . $param_type . $params . $param_value . $params . '\s*/?>'
			. '|<input' . $params . $param_value . $params . $param_type . $params . '\s*/?>'
			. ')\s*)+'
		);
	}

	/**
	 * Protect the script tags
	 *
	 * @param string $string
	 */
	public static function protectScripts(&$string)
	{
		if (strpos($string, '</script>') === false)
		{
			return;
		}

		self::protectByRegex(
			$string,
			'<script[\s>].*?</script>'
		);
	}

	/**
	 * Protect all html tags with some type of attributes/content
	 *
	 * @param string $string
	 */
	public static function protectHtmlTags(&$string)
	{
		// protect comment tags
		self::protectHtmlCommentTags($string);

		// protect html tags
		self::protectByRegex($string, '<[a-z][^>]*(?:="[^"]*"|=\'[^\']*\')+[^>]*>');
	}

	/**
	 * Protect all html comment tags
	 *
	 * @param string $string
	 */
	public static function protectHtmlCommentTags(&$string)
	{
		// protect comment tags
		self::protectByRegex($string, '<\!--.*?-->');
	}

	/**
	 * Protect text by given regex
	 *
	 * @param string $string
	 * @param string $regex
	 */
	public static function protectByRegex(&$string, $regex)
	{
		RegEx::matchAll($regex, $string, $matches, null, PREG_PATTERN_ORDER);

		if (empty($matches))
		{
			return;
		}

		$matches      = array_unique($matches[0]);
		$replacements = [];

		foreach ($matches as $match)
		{
			$replacements[] = self::protectString($match);
		}

		$string = str_replace($matches, $replacements, $string);
	}

	/**
	 * Protect given plugin style tags
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param bool   $include_closing_tags
	 */
	public static function protectTags(&$string, $tags = [], $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		$string = str_replace($tags, $protected, $string);
	}

	/**
	 * Replace any protected tags to original
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param bool   $include_closing_tags
	 */
	public static function unprotectTags(&$string, $tags = [], $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		$string = str_replace($protected, $tags, $string);
	}

	/**
	 * Protect array of strings
	 *
	 * @param string $string
	 * @param array  $unprotected
	 * @param array  $protected
	 */
	public static function protectInString(&$string, $unprotected = [], $protected = [])
	{
		$protected = empty($protected) ? self::protectArray($unprotected) : $protected;

		$string = str_replace($unprotected, $protected, $string);
	}

	/**
	 * Replace any protected tags to original
	 *
	 * @param string $string
	 * @param array  $unprotected
	 * @param array  $protected
	 */
	public static function unprotectInString(&$string, $unprotected = [], $protected = [])
	{
		$protected = empty($protected) ? self::protectArray($unprotected) : $protected;

		$string = str_replace($protected, $unprotected, $string);
	}

	/**
	 * Return the sourcerer tag name and characters
	 *
	 * @return array
	 */
	public static function getSourcererTag()
	{
		if ( ! is_null(self::$sourcerer_tag))
		{
			return [self::$sourcerer_tag, self::$sourcerer_characters];
		}

		$parameters = Parameters::getInstance()->getPluginParams('sourcerer');

		self::$sourcerer_tag        = isset($parameters->syntax_word) ? $parameters->syntax_word : '';
		self::$sourcerer_characters = isset($parameters->tag_characters) ? $parameters->tag_characters : '{.}';

		return [self::$sourcerer_tag, self::$sourcerer_characters];
	}

	/**
	 * Protect all Sourcerer blocks
	 *
	 * @param string $string
	 */
	public static function protectSourcerer(&$string)
	{
		list($tag, $characters) = self::getSourcererTag();

		if (empty($tag))
		{
			return;
		}

		list($start, $end) = explode('.', $characters);

		if (strpos($string, $start . '/' . $tag . $end) === false)
		{
			return;
		}

		$regex = RegEx::quote($start . $tag)
			. '[\s\}].*?'
			. RegEx::quote($start . '/' . $tag . $end);

		RegEx::matchAll($regex, $string, $matches, null, PREG_PATTERN_ORDER);

		if (empty($matches))
		{
			return;
		}

		$matches = array_unique($matches[0]);

		foreach ($matches as $match)
		{
			$string = str_replace($match, self::protectString($match), $string);
		}
	}

	/**
	 * Protect complete AdminForm
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param bool   $include_closing_tags
	 */
	public static function protectForm(&$string, $tags = [], $include_closing_tags = true)
	{
		if ( ! Document::isEditPage())
		{
			return;
		}

		list($tags, $protected_tags) = self::prepareTags($tags, $include_closing_tags);

		$string = RegEx::replace(self::getFormRegex(), '<!-- TMP_START_EDITOR -->\1', $string);
		$string = explode('<!-- TMP_START_EDITOR -->', $string);

		foreach ($string as $i => &$string_part)
		{
			if (empty($string_part) || ! fmod($i, 2))
			{
				continue;
			}

			self::protectFormPart($string_part, $tags, $protected_tags);
		}

		$string = implode('', $string);
	}

	/**
	 * Protect part of the AdminForm
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param array  $protected_tags
	 */
	private static function protectFormPart(&$string, $tags = [], $protected_tags = [])
	{
		if (strpos($string, '</form>') === false)
		{
			return;
		}

		// Protect entire form
		if (empty($tags))
		{
			$form_parts    = explode('</form>', $string, 2);
			$form_parts[0] = self::protectString($form_parts[0] . '</form>');
			$string        = implode('', $form_parts);

			return;
		}

		$regex_tags = RegEx::quote($tags);

		if ( ! RegEx::match($regex_tags, $string))
		{
			return;
		}

		$form_parts = explode('</form>', $string, 2);
		// protect tags only inside form fields
		RegEx::matchAll(
			'(?:<textarea[^>]*>.*?<\/textarea>|<input[^>]*>)',
			$form_parts[0],
			$matches,
			null,
			PREG_PATTERN_ORDER
		);

		if (empty($matches))
		{
			return;
		}

		$matches = array_unique($matches[0]);

		foreach ($matches as $match)
		{
			$field         = str_replace($tags, $protected_tags, $match);
			$form_parts[0] = str_replace($match, $field, $form_parts[0]);
		}

		$string = implode('</form>', $form_parts);
	}

	/**
	 * Replace any protected text to original
	 *
	 * @param string|array $string
	 */
	public static function unprotect(&$string)
	{
		if (is_array($string))
		{
			foreach ($string as &$part)
			{
				self::unprotect($part);
			}

			return;
		}

		self::unprotectByDelimiters(
			$string,
			[self::$protect_tags_start, self::$protect_tags_end]
		);

		self::unprotectByDelimiters(
			$string,
			[self::$protect_start, self::$protect_end]
		);

		if (StringHelper::contains($string, [self::$protect_tags_start, self::$protect_tags_end, self::$protect_start, self::$protect_end]))
		{
			self::unprotect($string);
		}
	}

	/**
	 * @param string $string
	 * @param array  $delimiters
	 */
	private static function unprotectByDelimiters(&$string, $delimiters)
	{
		if ( ! StringHelper::contains($string, $delimiters))
		{
			return;
		}

		$regex = RegEx::preparePattern(RegEx::quote($delimiters), 's', $string);

		$parts = preg_split($regex, $string);

		foreach ($parts as $i => &$part)
		{
			if ($i % 2 == 0)
			{
				continue;
			}

			$part = base64_decode($part);
		}

		$string = implode('', $parts);
	}

	/**
	 * Replace any protected text to original
	 *
	 * @param string $string
	 */
	public static function convertProtectionToHtmlSafe(&$string)
	{
		$string = str_replace(
			[
				self::$protect_start,
				self::$protect_end,
				self::$protect_tags_start,
				self::$protect_tags_end,
			],
			[
				self::$html_safe_start,
				self::$html_safe_end,
				self::$html_safe_tags_start,
				self::$html_safe_tags_end,
			],
			$string
		);
	}

	/**
	 * Replace any protected text to original
	 *
	 * @param string $string
	 */
	public static function unprotectHtmlSafe(&$string)
	{
		$string = str_replace(
			[
				self::$html_safe_start,
				self::$html_safe_end,
				self::$html_safe_tags_start,
				self::$html_safe_tags_end,
			],
			[
				self::$protect_start,
				self::$protect_end,
				self::$protect_tags_start,
				self::$protect_tags_end,
			],
			$string
		);

		self::unprotect($string);
	}

	/**
	 * Prepare the tags and protected tags array
	 *
	 * @param array $tags
	 * @param bool  $include_closing_tags
	 *
	 * @return bool|mixed
	 */
	private static function prepareTags($tags, $include_closing_tags = true)
	{
		if ( ! is_array($tags))
		{
			$tags = [$tags];
		}

		$cache_id = 'prepareTags_' . json_encode($tags) . '_' . $include_closing_tags;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		foreach ($tags as $i => $tag)
		{
			if (StringHelper::is_alphanumeric($tag[0]))
			{
				$tag = '{' . $tag;
			}

			$tags[$i] = $tag;

			if ($include_closing_tags)
			{
				$tags[] = RegEx::replace('^([^a-z0-9]+)', '\1/', $tag);
			}
		}

		return Cache::set(
			$cache_id,
			[$tags, self::protectArray($tags, 1)]
		);
	}

	/**
	 * Encode string
	 *
	 * @param string $string
	 * @param int    $is_tag
	 *
	 * @return string
	 */
	public static function protectString($string, $is_tag = false)
	{
		if ($is_tag)
		{
			return self::$protect_tags_start . base64_encode($string) . self::$protect_tags_end;
		}

		return self::$protect_start . base64_encode($string) . self::$protect_end;
	}

	/**
	 * Decode string
	 *
	 * @param string $string
	 * @param int    $is_tag
	 *
	 * @return string
	 */
	public static function unprotectString($string, $is_tag = false)
	{
		if ($is_tag)
		{
			return self::$protect_tags_start . base64_decode($string) . self::$protect_tags_end;
		}

		return self::$protect_start . base64_decode($string) . self::$protect_end;
	}

	/**
	 * Encode tag string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function protectTag($string)
	{
		return self::protectString($string, 1);
	}

	/**
	 * Encode array of strings
	 *
	 * @param array $array
	 * @param int   $is_tag
	 *
	 * @return mixed
	 */
	public static function protectArray($array, $is_tag = false)
	{
		foreach ($array as &$string)
		{
			$string = self::protectString($string, $is_tag);
		}

		return $array;
	}

	/**
	 * Decode array of strings
	 *
	 * @param array $array
	 * @param int   $is_tag
	 *
	 * @return mixed
	 */
	public static function unprotectArray($array, $is_tag = false)
	{
		foreach ($array as &$string)
		{
			$string = self::unprotectString($string, $is_tag);
		}

		return $array;
	}

	/**
	 * Replace any protected tags to original
	 *
	 * @param string $string
	 * @param array  $tags
	 */
	public static function unprotectForm(&$string, $tags = [])
	{
		// Protect entire form
		if (empty($tags))
		{
			self::unprotect($string);

			return;
		}

		self::unprotectTags($string, $tags);
	}

	/**
	 * Wrap string in comment tags
	 *
	 * @param string $name
	 * @param string $comment
	 *
	 * @return string
	 */
	public static function wrapInCommentTags($name, $string)
	{
		list($start, $end) = self::getCommentTags($name);

		return $start . $string . $end;
	}

	/**
	 * Get the html comment tags
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public static function getCommentTags($name = '')
	{
		return [self::getCommentStartTag($name), self::getCommentEndTag($name)];
	}

	/**
	 * Get the html start comment tags
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public static function getCommentStartTag($name = '')
	{
		return '<!-- START: ' . $name . ' -->';
	}

	/**
	 * Get the html end comment tags
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public static function getCommentEndTag($name = '')
	{
		return '<!-- END: ' . $name . ' -->';
	}

	/**
	 * Create a html comment from given comment string
	 *
	 * @param string $name
	 * @param string $comment
	 *
	 * @return string
	 */
	public static function getMessageCommentTag($name, $comment)
	{
		list($start, $end) = self::getMessageCommentTags($name);

		return $start . $comment . $end;
	}

	/**
	 * Get the start and end parts for the html message comment tag
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public static function getMessageCommentTags($name = '')
	{
		return ['<!--  ' . $name . ' Message: ', ' -->'];
	}

	/**
	 * Get the start and end parts for the inline comment tags for scripts/styles
	 *
	 * @param string $name
	 * @param string $type
	 *
	 * @return array
	 */
	public static function getInlineCommentTags($name = '', $type = '', $regex = false)
	{
		if ($regex)
		{
			$type = 'TYPE_PLACEHOLDER';
		}

		$start = '/* START: ' . $name . ' ' . $type . ' */';
		$end   = '/* END: ' . $name . ' ' . $type . ' */';

		if ($regex)
		{
			$start = str_replace($type, '[a-z]*', RegEx::quote($start));
			$end   = str_replace($type, '[a-z]*', RegEx::quote($end));
		}

		return [$start, $end];
	}

	/**
	 * Wraps a style or javascript declaration with comment tags
	 *
	 * @param string $content
	 * @param string $name
	 * @param string $type
	 * @param bool   $minify
	 */
	public static function wrapDeclaration($content = '', $name = '', $type = 'styles', $minify = true)
	{
		if (empty($name))
		{
			return $content;
		}

		list($start, $end) = self::getInlineCommentTags($name, $type);

		$spacer = $minify ? ' ' : "\n";

		return $start . $spacer . $content . $spacer . $end;
	}

	/**
	 * Wraps a javascript declaration with comment tags
	 *
	 * @param string $content
	 * @param string $name
	 * @param bool   $minify
	 */
	public static function wrapScriptDeclaration($content = '', $name = '', $minify = true)
	{
		return self::wrapDeclaration($content, $name, 'scripts', $minify);
	}

	/**
	 * Wraps a stylesheet declaration with comment tags
	 *
	 * @param string $content
	 * @param string $name
	 * @param bool   $minify
	 */
	public static function wrapStyleDeclaration($content = '', $name = '', $minify = true)
	{
		return self::wrapDeclaration($content, $name, 'styles', $minify);
	}

	/**
	 * Remove area comments in html
	 *
	 * @param string $string
	 * @param string $prefix
	 */
	public static function removeAreaTags(&$string, $prefix = '')
	{
		$string = RegEx::replace('<!-- (START|END): ' . $prefix . '_[A-Z]+ -->', '', $string, 's');
	}

	/**
	 * Remove comments in html
	 *
	 * @param string $string
	 * @param string $name
	 */
	public static function removeCommentTags(&$string, $name = '')
	{
		list($start, $end) = self::getCommentTags($name);

		$string = str_replace(
			[
				$start, $end,
				htmlentities($start), htmlentities($end),
				urlencode($start), urlencode($end),
			], '', $string
		);

		list($start, $end) = self::getMessageCommentTags($name);

		$string = RegEx::replace(
			RegEx::quote($start) . '.*?' . RegEx::quote($end),
			'',
			$string
		);
	}

	/**
	 * Remove inline comments in scrips and styles
	 *
	 * @param string $string
	 * @param string $name
	 */
	public static function removeInlineComments(&$string, $name)
	{
		list($start, $end) = Protect::getInlineCommentTags($name, null, true);
		$string = RegEx::replace('(' . $start . '|' . $end . ')', "\n", $string);
	}

	/**
	 * Remove left over plugin tags
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param string $character_start
	 * @param string $character_end
	 * @param bool   $keep_content
	 */
	public static function removePluginTags(&$string, $tags, $character_start = '{', $character_end = '{', $keep_content = true)
	{
		$character_start = RegEx::quote($character_start);
		$character_end   = RegEx::quote($character_end);

		foreach ($tags as $tag)
		{
			if ( ! is_array($tag))
			{
				$tag = [$tag, $tag];
			}

			if (count($tag) < 2)
			{
				$tag = [$tag[0], $tag[0]];
			}

			$regex = $character_start . RegEx::quote($tag[0]) . '(?:\s.*?)?' . $character_end
				. '(.*?)'
				. $character_start . '/' . RegEx::quote($tag[1]) . $character_end;

			$replace = $keep_content ? '\1' : '';

			$string = RegEx::replace($regex, $replace, $string);
		}
	}

	/**
	 * Remove tags from title tags
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param bool   $include_closing_tags
	 * @param array  $html_tags
	 */
	public static function removeFromHtmlTagContent(&$string, $tags, $include_closing_tags = true, $html_tags = ['title'])
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		if ( ! is_array($html_tags))
		{
			$html_tags = [$html_tags];
		}

		RegEx::matchAll('(<(' . implode('|', $html_tags) . ')(?:\s[^>]*?)>)(.*?)(</\2>)', $string, $matches);

		if (empty($matches))
		{
			return;
		}

		foreach ($matches as $match)
		{
			$content = $match[3];
			foreach ($tags as $tag)
			{
				$content = RegEx::replace(RegEx::quote($tag) . '.*?\}', '', $content);
			}
			$string = str_replace($match[0], $match[1] . $content . $match[4], $string);
		}
	}

	/**
	 * Remove tags from tag attributes
	 *
	 * @param string $string
	 * @param array  $tags
	 * @param string $attributes
	 * @param bool   $include_closing_tags
	 */
	public static function removeFromHtmlTagAttributes(&$string, $tags, $attributes = 'ALL', $include_closing_tags = true)
	{
		list($tags, $protected) = self::prepareTags($tags, $include_closing_tags);

		if ($attributes == 'ALL')
		{
			$attributes = ['[a-z][a-z0-9-_]*'];
		}

		if ( ! is_array($attributes))
		{
			$attributes = [$attributes];
		}

		RegEx::matchAll(
			'\s(?:' . implode('|', $attributes) . ')\s*=\s*".*?"',
			$string,
			$matches,
			null,
			PREG_PATTERN_ORDER
		);

		if (empty($matches) || empty($matches[0]))
		{
			return;
		}

		$matches = array_unique($matches[0]);

		// preg_quote all tags
		$tags_regex = RegEx::quote($tags) . '.*?\}';

		foreach ($matches as $match)
		{
			if ( ! StringHelper::contains($match, $tags))
			{
				continue;
			}

			$title = $match;

			$title = RegEx::replace($tags_regex, '', $title);

			$string = StringHelper::replaceOnce($match, $title, $string);
		}
	}

	/**
	 * Check if article passes security levels
	 *
	 * @param object $article
	 * @param array  $securtiy_levels
	 *
	 * @return bool|int
	 */
	public static function articlePassesSecurity(&$article, $securtiy_levels = [])
	{
		if ( ! isset($article->created_by))
		{
			return true;
		}

		if (empty($securtiy_levels))
		{
			return true;
		}

		if (is_string($securtiy_levels))
		{
			$securtiy_levels = [$securtiy_levels];
		}

		if (
			! is_array($securtiy_levels)
			|| in_array('-1', $securtiy_levels)
		)
		{
			return true;
		}

		// Lookup group level of creator
		$user_groups = new JAccess;
		$user_groups = $user_groups->getGroupsByUser($article->created_by);

		// Return true if any of the security levels are found in the users groups
		return count(array_intersect($user_groups, $securtiy_levels));
	}

	/**
	 * Replace in protect array
	 *
	 * @param array  $array
	 * @param string $search
	 * @param string $replacement
	 */
	public static function replaceInArray(&$array, $search, $replacement)
	{
		foreach ($array as $key => &$string)
		{
			// only do something if string is not empty
			// or on uneven count = not yet protected
			if (trim($string) == '' || fmod($key, 2))
			{
				continue;
			}

			$array[$key] = str_replace($search, $replacement, $string);
		}
	}

	/**
	 * Replace in protect array using Regular Expressions
	 *
	 * @param array  $array
	 * @param string $search
	 * @param string $replacement
	 */
	public static function pregReplaceInArray(&$array, $search, $replacement)
	{
		foreach ($array as $key => &$string)
		{
			// only do something if string is not empty
			// or on uneven count = not yet protected
			if (trim($string) == '' || fmod($key, 2))
			{
				continue;
			}

			$array[$key] = RegEx::replace($search, $replacement, $string);
		}
	}
}
