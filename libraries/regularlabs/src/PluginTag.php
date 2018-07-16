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

/**
 * Class PluginTag
 * @package RegularLabs\Library
 */
class PluginTag
{
	/**
	 * @var array
	 */
	static $protected_characters = [
		'=' => '[[:EQUAL:]]',
		'"' => '[[:QUOTE:]]',
		',' => '[[:COMMA:]]',
		'|' => '[[:BAR:]]',
		':' => '[[:COLON:]]',
	];

	/**
	 * Cleans the given tag word
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function clean($string = '')
	{
		return RegEx::replace('[^a-z0-9-_]', '', $string);
	}

	/**
	 * Get the attributes from  plugin style string
	 *
	 * @param string $string
	 * @param string $main_key
	 * @param array  $known_boolean_keys
	 * @param array  $keep_escaped_chars
	 *
	 * @return object
	 */
	public static function getAttributesFromString($string = '', $main_key = 'title', $known_boolean_keys = [], $keep_escaped_chars = [','])
	{
		if (empty($string))
		{
			return (object) [];
		}

		// Replace html entity quotes to normal quotes
		$string = str_replace('&quot;', '"', $string);

		self::protectSpecialChars($string);

		// replace weird whitespace
		$string = str_replace(chr(194) . chr(160), ' ', $string);

		// Replace html entity spaces between attributes to normal spaces
		$string = RegEx::replace('((?:^|")\s*)&nbsp;(\s*(?:[a-z]|$))', '\1 \2', $string);

		// Only one value, so return simple key/value object
		if (strpos($string, '|') == false && ! RegEx::match('=\s*"', $string))
		{
			self::unprotectSpecialChars($string, $keep_escaped_chars);

			return (object) [$main_key => $string];
		}

		// No foo="bar" syntax found, so assume old syntax
		if ( ! RegEx::match('=\s*"', $string))
		{
			self::unprotectSpecialChars($string, $keep_escaped_chars);

			$attributes = self::getAttributesFromStringOld($string, [$main_key]);
			self::convertOldSyntax($attributes, $known_boolean_keys);

			return $attributes;
		}

		// Cannot find right syntax, so return simple key/value object
		if ( ! RegEx::matchAll('(?:^|\s)(?<key>[a-z0-9-_]+)\s*(?<not>\!?)=\s*"(?<value>.*?)"', $string, $matches))
		{
			self::unprotectSpecialChars($string, $keep_escaped_chars);

			return (object) [$main_key => $string];
		}

		$tag = (object) [];

		foreach ($matches as $match)
		{
			$tag->{$match['key']} = self::getAttributeValueFromMatch($match, $known_boolean_keys, $keep_escaped_chars);
		}

		return $tag;
	}

	/**
	 * Get the value from a found attribute match
	 *
	 * @param array $match
	 * @param array $known_boolean_keys
	 * @param array $keep_escaped_chars
	 *
	 * @return bool|int|string
	 */
	private static function getAttributeValueFromMatch($match, $known_boolean_keys = [], $keep_escaped_chars = [','])
	{
		$value = $match['value'];

		self::unprotectSpecialChars($value, $keep_escaped_chars);

		if (is_numeric($value)
			&& (
				in_array($match['key'], $known_boolean_keys)
				|| in_array(strtolower($match['key']), $known_boolean_keys)
			)
		)
		{
			$value = $value ? 'true' : 'false';
		}

		// Convert numeric values to ints/floats
		if (is_numeric($value))
		{
			$value = $value + 0;
		}

		// Convert boolean values to actual booleans
		switch ($value)
		{
			case 'true':
				return $match['not'] ? false : true;
				break;

			case 'false':
				return $match['not'] ? true : false;
				break;

			default:
				return $match['not'] ? '!NOT!' . $value : $value;
				break;
		}
	}

	/**
	 * Replace special characters in the string with the protected versions
	 *
	 * @param string $string
	 */
	public static function protectSpecialChars(&$string)
	{
		$unescaped_chars = array_keys(self::$protected_characters);
		array_walk($unescaped_chars, function (&$char) {
			$char = '\\' . $char;
		});

		// replace escaped characters with special markup
		$string = str_replace(
			$unescaped_chars,
			array_values(self::$protected_characters),
			$string
		);

		if ( ! RegEx::matchAll(
			'(<.*?>|{.*?}|\[.*?\])',
			$string,
			$tags,
			null,
			PREG_PATTERN_ORDER
		)
		)
		{
			return;
		}

		foreach ($tags[0] as $tag)
		{
			// replace unescaped characters with special markup
			$protected = str_replace(
				['=', '"'],
				[self::$protected_characters['='], self::$protected_characters['"']],
				$tag
			);

			$string = str_replace($tag, $protected, $string);
		}
	}

	/**
	 * Replace protected characters in the string with the original special versions
	 *
	 * @param string $string
	 * @param array  $keep_escaped_chars
	 */
	public static function unprotectSpecialChars(&$string, $keep_escaped_chars = [])
	{
		$unescaped_chars = array_keys(self::$protected_characters);

		if ( ! empty($keep_escaped_chars))
		{
			array_walk($unescaped_chars, function (&$char, $key, $keep_escaped_chars) {
				if (is_array($keep_escaped_chars) && ! in_array($char, $keep_escaped_chars))
				{
					return;
				}
				$char = '\\' . $char;
			}, $keep_escaped_chars);
		}

		// replace special markup with unescaped characters
		$string = str_replace(
			array_values(self::$protected_characters),
			$unescaped_chars,
			$string
		);
	}

	/**
	 * Only used for old syntaxes
	 *
	 * @param string $string
	 * @param array  $keys
	 * @param string $separator
	 * @param string $equal
	 * @param int    $limit
	 *
	 * @return object
	 */
	public static function getAttributesFromStringOld($string = '', $keys = ['title'], $separator = '|', $equal = '=', $limit = 0)
	{
		$temp_separator = '[[SEPARATOR]]';
		$temp_equal     = '[[EQUAL]]';
		$tag_start      = '[[TAG]]';
		$tag_end        = '[[/TAG]]';

		// replace separators and equal signs with special markup
		$string = str_replace([$separator, $equal], [$temp_separator, $temp_equal], $string);
		// replace protected separators and equal signs back to original
		$string = str_replace(['\\' . $temp_separator, '\\' . $temp_equal], [$separator, $equal], $string);

		// protect all html tags
		RegEx::matchAll('</?[a-z][^>]*>', $string, $tags);

		if ( ! empty($tags))
		{
			foreach ($tags as $tag)
			{
				$string = str_replace(
					$tag[0],
					$tag_start . base64_encode(str_replace([$temp_separator, $temp_equal], [$separator, $equal], $tag[0])) . $tag_end,
					$string
				);
			}
		}

		// split string into array
		$attribs = $limit
			? explode($temp_separator, $string, (int) $limit)
			: explode($temp_separator, $string);

		$attributes = (object) [
			'params' => [],
		];

		// loop through splits
		foreach ($attribs as $i => $keyval)
		{
			// spit part into key and val by equal sign
			$keyval = explode($temp_equal, $keyval, 2);
			if (isset($keyval[1]))
			{
				$keyval[1] = str_replace([$temp_separator, $temp_equal], [$separator, $equal], $keyval[1]);
			}

			// unprotect tags in key and val
			foreach ($keyval as $key => $val)
			{
				RegEx::matchAll(RegEx::quote($tag_start) . '(.*?)' . RegEx::quote($tag_end), $val, $tags);

				if ( ! empty($tags))
				{
					foreach ($tags as $tag)
					{
						$val = str_replace($tag[0], base64_decode($tag[1]), $val);
					}

					$keyval[trim($key)] = $val;
				}
			}

			if (isset($keys[$i]))
			{
				$key = trim($keys[$i]);
				// if value is in the keys array add as defined in keys array
				// ignore equal sign
				$val = implode($equal, $keyval);
				if (substr($val, 0, strlen($key) + 1) == $key . '=')
				{
					$val = substr($val, strlen($key) + 1);
				}
				$attributes->{$key} = $val;
				unset($keys[$i]);

				continue;
			}

			// else add as defined in the string
			if (isset($keyval[1]))
			{
				$attributes->{$keyval[0]} = $keyval[1];
				continue;
			}

			$attributes->params[] = implode($equal, $keyval);
		}

		return $attributes;
	}

	/**
	 * Replace keys aliases with the main key names in an object
	 *
	 * @param object $attributes
	 * @param array  $key_aliases
	 * @param bool   $handle_plurals
	 */
	public static function replaceKeyAliases(&$attributes, $key_aliases = [], $handle_plurals = false)
	{
		foreach ($key_aliases as $key => $aliases)
		{
			if (self::replaceKeyAlias($attributes, $key, $key, $handle_plurals))
			{
				continue;
			}

			foreach ($aliases as $alias)
			{
				if ( ! isset($attributes->{$alias}))
				{
					continue;
				}

				if (self::replaceKeyAlias($attributes, $key, $alias, $handle_plurals))
				{
					break;
				}
			}
		}
	}

	/**
	 * Replace specific key alias with the main key name in an object
	 *
	 * @param object $attributes
	 * @param string $key
	 * @param string $alias
	 * @param bool   $handle_plurals
	 *
	 * @return bool
	 */
	private static function replaceKeyAlias(&$attributes, $key, $alias, $handle_plurals = false)
	{
		if ($handle_plurals)
		{
			if (self::replaceKeyAlias($attributes, $key, $alias . 's'))
			{
				return true;
			}

			if (substr($alias, -1) == 's' && self::replaceKeyAlias($attributes, $key, substr($alias, 0, -1)))
			{
				return true;
			}
		}

		if (isset($attributes->{$key}))
		{
			return true;
		}

		if ( ! isset($attributes->{$alias}))
		{
			return false;
		}

		$attributes->{$key} = $attributes->{$alias};
		unset($attributes->{$alias});

		return true;
	}

	/**
	 * Convert an object using the old param style to the new syntax
	 *
	 * @param object $attributes
	 * @param array  $known_boolean_keys
	 * @param string $extra_key
	 */
	public static function convertOldSyntax(&$attributes, $known_boolean_keys = [], $extra_key = 'class')
	{
		$extra = isset($attributes->class) ? [$attributes->class] : [];

		foreach ($attributes->params as $i => $param)
		{
			if ( ! $param)
			{
				continue;
			}

			if (in_array($param, $known_boolean_keys))
			{
				$attributes->{$param} = true;
				continue;
			}

			if (strpos($param, '=') == false)
			{
				$extra[] = $param;
				continue;
			}

			list($key, $val) = explode('=', $param, 2);

			$attributes->{$key} = $val;
		}

		$attributes->{$extra_key} = trim(implode(' ', $extra));

		unset($attributes->params);
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Different types of spaces
	 *
	 * @param string $modifier
	 *
	 * @return string
	 */
	public static function getRegexSpaces($modifier = '+')
	{
		return '(?:\s|&nbsp;|&\#160;)' . $modifier;
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Plugin type tags inside others
	 *
	 * @return string
	 */
	public static function getRegexInsideTag()
	{
		return '(?:[^\{\}]*\{[^\}]*\})*.*?';
	}

	/**
	 * Return the Regular Expressions string to match:
	 * html before plugin tag
	 *
	 * @param string $group_id
	 *
	 * @return string
	 */
	public static function getRegexLeadingHtml($group_id = '')
	{
		$group          = 'leading_block_element_' . $group_id;
		$html_tag_group = 'html_tag_' . $group_id;

		$block_elements = Html::getBlockElements(['div']);
		$block_element  = '(?<' . $group . '>' . implode('|', $block_elements) . ')';

		$other_html = '[^<]*(<(?<' . $html_tag_group . '>[a-z][a-z0-9_-]*)[\s>]([^<]*</(?P=' . $html_tag_group . ')>)?[^<]*)*';

		// Grab starting block element tag and any html after it (that is not the same block element starting/ending tag).
		return '(?:'
			. '<' . $block_element . '(?: [^>]*)?>'
			. $other_html
			. ')?';
	}

	/**
	 * Return the Regular Expressions string to match:
	 * html after plugin tag
	 *
	 * @param string $group_id
	 *
	 * @return string
	 */
	public static function getRegexTrailingHtml($group_id = '')
	{
		$group = 'leading_block_element_' . $group_id;

		// If the grouped name is found, then grab all content till ending html tag is found. Otherwise grab nothing.
		return '(?(<' . $group . '>)'
			. '(?:.*?</(?P=' . $group . ')>)?'
			. ')';
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Opening html tags
	 *
	 * @param array $block_elements
	 * @param array $inline_elements
	 * @param array $excluded_block_elements
	 *
	 * @return string
	 */
	public static function getRegexSurroundingTagsPre($block_elements = [], $inline_elements = ['span'], $excluded_block_elements = [])
	{
		$block_elements = ! empty($block_elements) ? $block_elements : Html::getBlockElements($excluded_block_elements);

		$regex = '(?:<(?:' . implode('|', $block_elements) . ')(?: [^>]*)?>\s*(?:<br ?/?>\s*)*)?';

		if ( ! empty($inline_elements))
		{
			$regex .= '(?:<(?:' . implode('|', $inline_elements) . ')(?: [^>]*)?>\s*(?:<br ?/?>\s*)*){0,3}';
		}

		return $regex;
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Closing html tags
	 *
	 * @param array $block_elements
	 * @param array $inline_elements
	 * @param array $excluded_block_elements
	 *
	 * @return string
	 */
	public static function getRegexSurroundingTagsPost($block_elements = [], $inline_elements = ['span'], $excluded_block_elements = [])
	{
		$block_elements = ! empty($block_elements) ? $block_elements : Html::getBlockElements($excluded_block_elements);

		$regex = '';

		if ( ! empty($inline_elements))
		{
			$regex .= '(?:(?:\s*<br ?/?>)*\s*<\/(?:' . implode('|', $inline_elements) . ')>){0,3}';
		}

		$regex .= '(?:(?:\s*<br ?/?>)*\s*<\/(?:' . implode('|', $block_elements) . ')>)?';

		return $regex;
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Leading html tag
	 *
	 * @param array $elements
	 *
	 * @return string
	 */
	public static function getRegexSurroundingTagPre($elements = [])
	{
		$elements = ! empty($elements) ? $elements : array_merge(Html::getBlockElements(), ['span']);

		return '(?:<(?:' . implode('|', $elements) . ')(?: [^>]*)?>\s*(?:<br ?/?>\s*)*)?';
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Trailing html tag
	 *
	 * @param array $elements
	 *
	 * @return string
	 */
	public static function getRegexSurroundingTagPost($elements = [])
	{
		$elements = ! empty($elements) ? $elements : array_merge(Html::getBlockElements(), ['span']);

		return '(?:(?:\s*<br ?/?>)*\s*<\/(?:' . implode('|', $elements) . ')>)?';
	}

	/**
	 * Return the Regular Expressions string to match:
	 * Plugin style tags
	 *
	 * @param array $tags
	 * @param bool  $include_no_attributes
	 * @param bool  $include_ending
	 * @param array $required_attributes
	 *
	 * @return string
	 */
	public static function getRegexTags($tags, $include_no_attributes = true, $include_ending = true, $required_attributes = [])
	{
		$tags = ArrayHelper::toArray($tags);
		$tags = count($tags) > 1 ? '(?:' . implode('|', $tags) . ')' : $tags[0];

		$value      = '(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[a-z0-9-_]+))?';
		$attributes = '(?:\s+[a-z0-9-_]+' . $value . ')+';

		$required_attributes = ArrayHelper::toArray($required_attributes);
		if ( ! empty($required_attributes))
		{
			$attributes = '(?:' . $attributes . ')?' . '(?:\s+' . implode('|', $required_attributes) . ')' . $value . '(?:' . $attributes . ')?';
		}

		if ($include_no_attributes)
		{
			$attributes = '\s*(?:' . $attributes . ')?';
		}

		if ( ! $include_ending)
		{
			return '<' . $tags . $attributes . '\s*/?>';
		}

		return '<(?:\/' . $tags . '|' . $tags . $attributes . '\s*/?)\s*/?>';
	}

	/**
	 * Extract the plugin style div tags with the possible attributes. like:
	 * {div width:100|float:left}...{/div}
	 *
	 * @param string $start_tag
	 * @param string $end_tag
	 * @param string $tag_start
	 * @param string $tag_end
	 *
	 * @return array
	 */
	public static function getDivTags($start_tag = '', $end_tag = '', $tag_start = '{', $tag_end = '}')
	{
		$tag_start = RegEx::quote($tag_start);
		$tag_end   = RegEx::quote($tag_end);

		$start_div = ['pre' => '', 'tag' => '', 'post' => ''];
		$end_div   = ['pre' => '', 'tag' => '', 'post' => ''];

		if ( ! empty($start_tag)
			&& RegEx::match(
				'^(?<pre>.*?)(?<tag>' . $tag_start . 'div(?: .*?)?' . $tag_end . ')(?<post>.*)$',
				$start_tag,
				$match
			)
		)
		{
			$start_div = $match;
		}

		if ( ! empty($end_tag)
			&& RegEx::match(
				'^(?<pre>.*?)(?<tag>' . $tag_start . '/div' . $tag_end . ')(?<post>.*)$',
				$end_tag,
				$match
			)
		)
		{
			$end_div = $match;
		}

		if (empty($start_div['tag']) || empty($end_div['tag']))
		{
			return [$start_div, $end_div];
		}

		$attribs = trim(RegEx::replace($tag_start . 'div(.*)' . $tag_end, '\1', $start_div['tag']));

		$start_div['tag'] = '<div>';
		$end_div['tag']   = '</div>';

		if (empty($attribs))
		{
			return [$start_div, $end_div];
		}

		$attribs = self::getDivAttributes($attribs);

		$style = [];

		if (isset($attribs->width))
		{
			if (is_numeric($attribs->width))
			{
				$attribs->width .= 'px';
			}
			$style[] = 'width:' . $attribs->width;
		}

		if (isset($attribs->height))
		{
			if (is_numeric($attribs->height))
			{
				$attribs->height .= 'px';
			}
			$style[] = 'height:' . $attribs->height;
		}

		if (isset($attribs->align))
		{
			$style[] = 'float:' . $attribs->align;
		}

		if ( ! isset($attribs->align) && isset($attribs->float))
		{
			$style[] = 'float:' . $attribs->float;
		}

		$attribs = isset($attribs->class) ? 'class="' . $attribs->class . '"' : '';

		if ( ! empty($style))
		{
			$attribs .= ' style="' . implode(';', $style) . ';"';
		}

		$start_div['tag'] = trim('<div ' . trim($attribs)) . '>';

		return [$start_div, $end_div];
	}

	/**
	 * Get the attributes from a plugin style div tag
	 *
	 * @param string $string
	 *
	 * @return object
	 */
	private static function getDivAttributes($string)
	{
		if (strpos($string, '="') !== false)
		{
			return self::getAttributesFromString($string);
		}

		$parts      = explode('|', $string);
		$attributes = (object) [];

		foreach ($parts as $e)
		{
			if (strpos($e, ':') === false)
			{
				continue;
			}

			list($key, $val) = explode(':', $e, 2);
			$attributes->{$key} = $val;
		}

		return $attributes;
	}
}
