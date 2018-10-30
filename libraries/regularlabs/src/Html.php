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

use DOMDocument;

/**
 * Class Html
 * @package RegularLabs\Library
 */
class Html
{
	/**
	 * Convert content saved in a WYSIWYG editor to plain text (like removing html tags)
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function convertWysiwygToPlainText($string)
	{
		// replace chr style enters with normal enters
		$string = str_replace([chr(194) . chr(160), '&#160;', '&nbsp;'], ' ', $string);

		// replace linebreak tags with normal linebreaks (paragraphs, enters, etc).
		$enter_tags = ['p', 'br'];
		$regex      = '</?((' . implode(')|(', $enter_tags) . '))+[^>]*?>\n?';
		$string     = RegEx::replace($regex, " \n", $string);

		// replace indent characters with spaces
		$string = RegEx::replace('<img [^>]*/sourcerer/images/tab\.png[^>]*>', '    ', $string);

		// strip all other tags
		$regex  = '<(/?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?)>';
		$string = RegEx::replace($regex, '', $string);

		// reset htmlentities
		$string = StringHelper::html_entity_decoder($string);

		// convert protected html entities &_...; -> &...;
		$string = RegEx::replace('&_([a-z0-9\#]+?);', '&\1;', $string);

		return $string;
	}

	/**
	 * Extract the <body>...</body> part from an entire html output string
	 *
	 * @param string $html
	 *
	 * @return array
	 */
	public static function getBody($html)
	{
		if (strpos($html, '<body') === false || strpos($html, '</body>') === false)
		{
			return ['', $html, ''];
		}

		// Force string to UTF-8
		$html = StringHelper::convertToUtf8($html);

		$html_split = explode('<body', $html, 2);
		$pre        = $html_split[0];
		$body       = '<body' . $html_split[1];
		$body_split = explode('</body>', $body);
		$post       = array_pop($body_split);
		$body       = implode('</body>', $body_split) . '</body>';

		return [$pre, $body, $post];
	}

	/**
	 * Search the string for the start and end searches and split the string in a pre, body and post part
	 * This is used to be able to do replacements on the body part, which will be lighter than doing it on the entire string
	 *
	 * @param string $string
	 * @param array  $start_searches
	 * @param array  $end_searches
	 * @param int    $start_offset
	 * @param null   $end_offset
	 *
	 * @return array
	 */
	public static function getContentContainingSearches($string, $start_searches = [], $end_searches = [], $start_offset = 1000, $end_offset = null)
	{
		// String is too short to split and search through
		if (strlen($string) < 2000)
		{
			return ['', $string, ''];
		}

		$end_offset = is_null($end_offset) ? $start_offset : $end_offset;

		$found       = false;
		$start_split = strlen($string);

		foreach ($start_searches as $search)
		{
			$pos = strpos($string, $search);

			if ($pos === false)
			{
				continue;
			}

			$start_split = min($start_split, $pos);
			$found       = true;
		}

		// No searches are found
		if ( ! $found)
		{
			return [$string, '', ''];
		}

		// String is too short to split
		if (strlen($string) < ($start_offset + $end_offset + 1000))
		{
			return ['', $string, ''];
		}

		$start_split = max($start_split - $start_offset, 0);

		$pre    = substr($string, 0, $start_split);
		$string = substr($string, $start_split);

		self::fixBrokenTagsByPreString($pre, $string);

		if (empty($end_searches))
		{
			$end_searches = $start_searches;
		}

		$end_split = 0;
		$found     = false;

		foreach ($end_searches as $search)
		{
			$pos = strrpos($string, $search);

			if ($pos === false)
			{
				continue;
			}

			$end_split = max($end_split, $pos + strlen($search));
			$found     = true;
		}

		// No end split is found, so don't split remainder
		if ( ! $found)
		{
			return [$pre, $string, ''];
		}

		$end_split = min($end_split + $end_offset, strlen($string));

		$post   = substr($string, $end_split);
		$string = substr($string, 0, $end_split);

		self::fixBrokenTagsByPostString($post, $string);

		return [$pre, $string, $post];
	}

	/**
	 * Check if string contains block elements
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function containsBlockElements($string)
	{
		return RegEx::match('</?(' . implode('|', self::getBlockElements()) . ')(?: [^>]*)?>', $string);
	}

	/**
	 * Fix broken/invalid html syntax in a string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function fix($string)
	{
		if ( ! self::containsBlockElements($string))
		{
			return $string;
		}

		// Convert utf8 characters to html entities
		if (function_exists('mb_convert_encoding'))
		{
			$string = mb_convert_encoding($string, 'html-entities', 'utf-8');
		}

		$string = self::protectSpecialCode($string);

		$string = self::convertDivsInsideInlineElementsToSpans($string);
		$string = self::removeParagraphsAroundBlockElements($string);
		$string = self::removeInlineElementsAroundBlockElements($string);
		$string = self::fixParagraphsAroundParagraphElements($string);

		$string = class_exists('DOMDocument')
			? self::fixUsingDOMDocument($string)
			: self::fixUsingCustomFixer($string);

		$string = self::unprotectSpecialCode($string);

		// Convert html entities back to utf8 characters
		if (function_exists('mb_convert_encoding'))
		{
			// Make sure &lt; and &gt; don't get converted
			$string = str_replace(['&lt;', '&gt;'], ['&amp;lt;', '&amp;gt;'], $string);

			$string = mb_convert_encoding($string, 'utf-8', 'html-entities');
		}

		$string = self::removeParagraphsAroundComments($string);

		return $string;
	}

	/**
	 * Fix broken/invalid html syntax in an array of strings
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function fixArray($array)
	{
		$splitter = ':|:';

		$string = self::fix(implode($splitter, $array));

		$parts = self::removeEmptyTags(explode($splitter, $string));

		// use original keys but new values
		return array_combine(array_keys($array), $parts);
	}

	/**
	 * Removes empty tags which span concatenating parts in the array
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function removeEmptyTags($array)
	{
		$splitter = ':|:';
		$comments = '(?:\s*<\!--.*?-->\s*)*';

		$string = implode($splitter, $array);

		$string = RegEx::replace(
			'<([a-z][a-z0-9]*)(?: [^>]*)?>\s*(' . $comments . RegEx::quote($splitter) . $comments . ')\s*</\1>',
			'\2',
			$string
		);

		return explode($splitter, $string);
	}

	/**
	 * Fix broken/invalid html syntax in a string using php DOMDocument functionality
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	private static function fixUsingDOMDocument($string)
	{
		$doc = new DOMDocument;

		$doc->substituteEntities = false;

		// Add temporary surrounding div
		$string = '<div>' . $string . '</div>';

		@$doc->loadHTML($string);
		$string = $doc->saveHTML();

		// Remove html document structures
		$string = RegEx::replace('^<[^>]*>(.*?)<html>.*?(?:<head>(.*)</head>.*?)?<body>(.*)</body>.*?$', '\1\2\3', $string);

		// Remove temporary surrounding div
		$string = RegEx::replace('^\s*<div>(.*)</div>\s*$', '\1', $string);

		// Remove leading/trailing empty paragraph
		$string = RegEx::replace('(^\s*<div>\s*</div>|<div>\s*</div>\s*$)', '', $string);

		// Remove leading/trailing empty paragraph
		$string = RegEx::replace('(^\s*<p(?: [^>]*)?>\s*</p>|<p(?: [^>]*)?>\s*</p>\s*$)', '', $string);

		return $string;
	}

	/**
	 * Fix broken/invalid html syntax in a string using custom code as an alternative to php DOMDocument functionality
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private static function fixUsingCustomFixer($string)
	{
		$block_regex = '<(' . implode('|', self::getBlockElementsNoDiv()) . ')[\s>]';

		$string = RegEx::replace('(' . $block_regex . ')', '[:SPLIT-BLOCK:]\1', $string);
		$parts  = explode('[:SPLIT-BLOCK:]', $string);

		foreach ($parts as $i => &$part)
		{
			if ( ! RegEx::match('^' . $block_regex, $part, $type))
			{
				continue;
			}

			$type = strtolower($type[1]);

			// remove endings of other block elements
			$part = RegEx::replace('</(?:' . implode('|', self::getBlockElementsNoDiv($type)) . ')>', '', $part);

			if (strpos($part, '</' . $type . '>') !== false)
			{
				continue;
			}

			// Add ending tag once
			$part = RegEx::replaceOnce('(\s*)$', '</' . $type . '>\1', $part);

			// Remove empty block tags
			$part = RegEx::replace('^<' . $type . '(?: [^>]*)?>\s*</' . $type . '>', '', $part);
		}

		return implode('', $parts);
	}

	/**
	 * Removes complete html tag pairs from the concatenated parts
	 *
	 * @param array $parts
	 * @param array $elements
	 *
	 * @return array
	 */
	public static function cleanSurroundingTags($parts, $elements = ['p', 'span'])
	{
		$breaks = '(?:(?:<br ?/?>|<\!--.*?-->|:\|:)\s*)*';
		$keys   = array_keys($parts);

		$string = implode(':|:', $parts);

		// Remove empty tags
		$regex = '<(' . implode('|', $elements) . ')(?: [^>]*)?>\s*(' . $breaks . ')<\/\1>\s*';

		while (RegEx::match($regex, $string, $match))
		{
			$string = str_replace($match[0], $match[2], $string);
		}

		// Remove paragraphs around block elements
		$block_elements = [
			'p', 'div',
			'table', 'tr', 'td', 'thead', 'tfoot',
			'h[1-6]',
		];
		$block_elements = '(' . implode('|', $block_elements) . ')';

		$regex = '(<p(?: [^>]*)?>)(\s*' . $breaks . ')(<' . $block_elements . '(?: [^>]*)?>)';

		while (RegEx::match($regex, $string, $match))
		{
			if ($match[4] == 'p')
			{
				$match[3] = $match[1] . $match[3];
				self::combinePTags($match[3]);
			}

			$string = str_replace($match[0], $match[2] . $match[3], $string);
		}

		$regex = '(</' . $block_elements . '>\s*' . $breaks . ')</p>';

		while (RegEx::match($regex, $string, $match))
		{
			$string = str_replace($match[0], $match[1], $string);
		}

		$parts = explode(':|:', $string);

		$new_tags = [];

		foreach ($parts as $key => $val)
		{
			$key            = isset($keys[$key]) ? $keys[$key] : $key;
			$new_tags[$key] = $val;
		}

		return $new_tags;
	}

	/**
	 * Remove <p> tags around block elements
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	private static function removeParagraphsAroundBlockElements($string)
	{
		if (strpos($string, '</p>') == false)
		{
			return $string;
		}

		$string = RegEx::replace(
			'<p(?: [^>]*)?>\s*'
			. '((?:<\!--.*?-->\s*)*</?(?:' . implode('|', self::getBlockElements()) . ')' . '(?: [^>]*)?>)',
			'\1',
			$string
		);

		$string = RegEx::replace(
			'(</?(?:' . implode('|', self::getBlockElements()) . ')' . '(?: [^>]*)?>(?:\s*<\!--.*?-->)*)'
			. '(?:\s*</p>)',
			'\1',
			$string
		);

		return $string;
	}

	/**
	 * Remove <p> tags around comments
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	private static function removeParagraphsAroundComments($string)
	{
		if (strpos($string, '</p>') == false)
		{
			return $string;
		}

		$string = RegEx::replace(
			'(?:<p(?: [^>]*)?>\s*)'
			. '(<\!--.*?-->)'
			. '(?:\s*</p>)',
			'\1',
			$string
		);

		return $string;
	}

	/**
	 * Fix <p> tags around other <p> elements
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	private static function fixParagraphsAroundParagraphElements($string)
	{
		if (strpos($string, '</p>') == false)
		{
			return $string;
		}

		$parts  = explode('</p>', $string);
		$ending = '</p>' . array_pop($parts);

		foreach ($parts as &$part)
		{
			if (strpos($part, '<p>') === false && strpos($part, '<p ') === false)
			{
				$part = '<p>' . $part;
				continue;
			}

			$part = RegEx::replace(
				'(<p(?: [^>]*)?>.*?)(<p(?: [^>]*)?>)',
				'\1</p>\2',
				$part
			);
		}

		return implode('</p>', $parts) . $ending;
	}

	/*
	 * Remove empty tags
	 *
	 * @param string $string
	 * @param array $elements
	 *
	 * @return mixed
	 */
	public static function removeEmptyTagPairs($string, $elements = ['p', 'span'])
	{
		$breaks = '(?:(?:<br ?/?>|<\!--.*?-->)\s*)*';

		$regex = '<(' . implode('|', $elements) . ')(?: [^>]*)?>\s*(' . $breaks . ')<\/\1>\s*';

		while (RegEx::match($regex, $string, $match))
		{
			$string = str_replace($match[0], $match[2], $string);
		}

		return $string;
	}

	/**
	 * Convert <div> tags inside inline elements to <span> tags
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	private static function convertDivsInsideInlineElementsToSpans($string)
	{
		if (strpos($string, '</div>') == false)
		{
			return $string;
		}

		// Ignore block elements inside anchors
		$regex = '<(' . implode('|', self::getInlineElementsNoAnchor()) . ')(?: [^>]*)?>.*?</\1>';
		RegEx::matchAll($regex, $string, $matches, '', PREG_PATTERN_ORDER);

		if (empty($matches))
		{
			return $string;
		}

		$matches      = array_unique($matches[0]);
		$searches     = [];
		$replacements = [];

		foreach ($matches as $match)
		{
			if (strpos($match, '</div>') === false)
			{
				continue;
			}

			$searches[]     = $match;
			$replacements[] = str_replace(
				['<div>', '<div ', '</div>'],
				['<span>', '<span ', '</span>'],
				$match
			);
		}

		if (empty($searches))
		{
			return $string;
		}

		return str_replace($searches, $replacements, $string);
	}

	/**
	 * Combine duplicate <p> tags
	 * input: <p class="aaa" a="1"><!-- ... --><p class="bbb" b="2">
	 * output: <p class="aaa bbb" a="1" b="2"><!-- ... -->
	 *
	 * @param $string
	 */
	public static function combinePTags(&$string)
	{
		if (empty($string))
		{
			return;
		}

		$p_start_tag   = '<p(?: [^>]*)?>';
		$optional_tags = '\s*(?:<\!--.*?-->|&nbsp;|&\#160;)*\s*';
		RegEx::matchAll('(' . $p_start_tag . ')(' . $optional_tags . ')(' . $p_start_tag . ')', $string, $tags);

		if (empty($tags))
		{
			return;
		}

		foreach ($tags as $tag)
		{
			$string = str_replace($tag[0], $tag[2] . HtmlTag::combine($tag[1], $tag[3]), $string);
		}
	}

	/**
	 * Remove inline elements around block elements
	 *
	 * @param string $string
	 *
	 * @return mixed
	 */
	public static function removeInlineElementsAroundBlockElements($string)
	{
		$string = RegEx::replace(
			'(?:<(?:' . implode('|', self::getInlineElementsNoAnchor()) . ')(?: [^>]*)?>\s*)'
			. '(</?(?:' . implode('|', self::getBlockElements()) . ')(?: [^>]*)?>)',
			'\1',
			$string
		);

		$string = RegEx::replace(
			'(</?(?:' . implode('|', self::getBlockElements()) . ')(?: [^>]*)?>)'
			. '(?:\s*</(?:' . implode('|', self::getInlineElementsNoAnchor()) . ')>)',
			'\1',
			$string
		);

		return $string;
	}

	/**
	 * Return an array of block element names, optionally without any of the names given $exclude
	 *
	 * @param array $exclude
	 *
	 * @return array
	 */
	public static function getBlockElements($exclude = [])
	{
		if ( ! is_array($exclude))
		{
			$exclude = [$exclude];
		}

		$elements = [
			'div', 'p', 'pre',
			'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		];

		$elements = array_diff($elements, $exclude);

		$elements = implode(',', $elements);
		$elements = str_replace('h1,h2,h3,h4,h5,h6', 'h[1-6]', $elements);
		$elements = explode(',', $elements);

		return $elements;
	}

	/**
	 * Return an array of inline element names, optionally without any of the names given $exclude
	 *
	 * @param array $exclude
	 *
	 * @return array
	 */
	public static function getInlineElements($exclude = [])
	{
		if ( ! is_array($exclude))
		{
			$exclude = [$exclude];
		}

		$elements = [
			'span', 'code', 'a',
			'strong', 'b', 'em', 'i', 'u', 'big', 'small', 'font',
		];

		return array_diff($elements, $exclude);
	}

	/**
	 * Return an array of block element names, without divs and any of the names given $exclude
	 *
	 * @param array $exclude
	 *
	 * @return array
	 */
	public static function getBlockElementsNoDiv($exclude = [])
	{
		return array_diff(self::getBlockElements($exclude), ['div']);
	}

	/**
	 * Return an array of block element names, without anchors (a) and any of the names given $exclude
	 *
	 * @param array $exclude
	 *
	 * @return array
	 */
	public static function getInlineElementsNoAnchor($exclude = [])
	{
		return array_diff(self::getInlineElements($exclude), ['a']);
	}

	/**
	 * Protect plugin style tags and php
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	private static function protectSpecialCode($string)
	{
		// Protect PHP code
		Protect::protectByRegex($string, '(<|&lt;)\?php\s.*?\?(>|&gt;)');

		// Protect {...} tags
		Protect::protectByRegex($string, '\{[a-z0-9].*?\}');

		// Protect [...] tags
		Protect::protectByRegex($string, '\[[a-z0-9].*?\]');

		// Protect scripts
		Protect::protectByRegex($string, '<script[^>]*>.*?</script>');

		// Protect css
		Protect::protectByRegex($string, '<style[^>]*>.*?</style>');

		Protect::convertProtectionToHtmlSafe($string);

		return $string;
	}

	/**
	 * Unprotect protected tags
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	private static function unprotectSpecialCode($string)
	{
		Protect::unprotectHtmlSafe($string);

		return $string;
	}

	/**
	 * Prevents broken html tags at the end of $pre (other half at beginning of $string)
	 * It will move the broken part to the beginning of $string to complete it
	 *
	 * @param $pre
	 * @param $string
	 */
	private static function fixBrokenTagsByPreString(&$pre, &$string)
	{
		if ( ! RegEx::match('<(\![^>]*|/?[a-z][^>]*(="[^"]*)?)$', $pre, $match))
		{
			return;
		}

		$pre    = substr($pre, 0, strlen($pre) - strlen($match[0]));
		$string = $match[0] . $string;
	}

	/**
	 * Prevents broken html tags at the beginning of $pre (other half at end of $string)
	 * It will move the broken part to the end of $string to complete it
	 *
	 * @param $post
	 * @param $string
	 */
	private static function fixBrokenTagsByPostString(&$post, &$string)
	{
		if ( ! RegEx::match('<(\![^>]*|/?[a-z][^>]*(="[^"]*)?)$', $string, $match))
		{
			return;
		}

		if ( ! RegEx::match('^[^>]*>', $post, $match))
		{
			return;
		}

		$post = substr($post, strlen($match[0]));

		$string .= $match[0];
	}

	/**
	 * Removes html tags from string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function removeHtmlTags($string)
	{
		// remove pagenavcounter
		$string = RegEx::replace('(<div class="pagenavcounter">.*?</div>)', ' ', $string);
		// remove pagenavbar
		$string = RegEx::replace('(<div class="pagenavbar">(<div>.*?</div>)*</div>)', ' ', $string);
		// remove inline scripts
		$string = RegEx::replace('(<script[^a-z0-9].*?</script>)', ' ', $string);
		$string = RegEx::replace('(<noscript[^a-z0-9].*?</noscript>)', ' ', $string);
		// remove inline styles
		$string = RegEx::replace('(<style[^a-z0-9].*?</style>)', ' ', $string);
		// remove other tags
		$string = RegEx::replace('(</?[a-z][a-z0-9]?.*?>)', ' ', $string);
		// remove double whitespace
		$string = trim(RegEx::replace('(\s)[ ]+', '\1', $string));

		return $string;
	}
}
