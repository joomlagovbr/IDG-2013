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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data;

defined('_JEXEC') or die;

use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

class Text extends Data
{
	var $helpers = [];
	var $params  = null;

	public function get($key, $attributes)
	{
		$value = $this->item->get($key);

		return $this->process($value, $attributes);
	}

	public function process($string, $attributes)
	{
		if (isset($attributes->page))
		{
			return self::getPage($string, $attributes);
		}

		if (isset($attributes->strip))
		{
			return self::strip($string, $attributes);
		}

		if (isset($attributes->noimages))
		{
			// remove images
			$string = RL_RegEx::replace(
				'(<p><img\s.*?></p>|<img\s.*?>)',
				' ',
				$string
			);
		}

		if (empty($attributes->limit) && empty($attributes->words) && empty($attributes->paragraphs))
		{
			return $string;
		}

		if (strpos($string, '<') === false || strpos($string, '>') === false)
		{
			// No html tags found. Do a simple limit.
			return self::limit($string, $attributes);
		}

		return self::limitHtml($string, $attributes);
	}

	private static function limitHtml($string, $data)
	{
		if (empty($data->limit) && empty($data->words) && empty($data->paragraphs))
		{
			return $string;
		}

		if ( ! empty($data->paragraphs))
		{
			return self::limitHtmlParagraphs($string, (int) $data->paragraphs);
		}

		if ( ! empty($data->words))
		{
			return self::limitHtmlWords($string, (int) $data->words);
		}

		return self::limitHtmlLetters($string, (int) $data->limit);
	}

	private static function limitHtmlParagraphs($string, $limit)
	{
		if ( ! RL_RegEx::match('^' . str_repeat('.*?</p>', $limit), $string, $match))
		{
			return $string;
		}

		return $match[0];
	}

	private static function limitHtmlWords($string, $limit)
	{
		return self::limitHtmlByType($string, $limit, 'words');
	}

	private static function limitHtmlLetters($string, $limit)
	{
		return self::limitHtmlByType($string, $limit);
	}

	private static function limitHtmlByType($string, $limit, $type = 'letters')
	{
		if (strlen($string) < $limit)
		{
			return $string;
		}

		// store pagenavcounter & pagenav (exclude from count)
		$pagenavcounter = '';
		if (strpos($string, 'pagenavcounter') !== false)
		{
			if (RL_RegEx::match('<div class="pagenavcounter">.*?</div>', $string, $pagenavcounter))
			{
				$pagenavcounter = $pagenavcounter[0];
				$string         = str_replace($pagenavcounter, '<!-- ARTA_PAGENAVCOUNTER -->', $string);
			}
		}

		$pagenavbar = '';
		if (strpos($string, 'pagenavbar') !== false)
		{
			if (RL_RegEx::match('<div class="pagenavbar">(<div>.*?</div>)*</div>', $string, $pagenavbar))
			{
				$pagenavbar = $pagenavbar[0];
				$string     = str_replace($pagenavbar, '<!-- ARTA_PAGENAV -->', $string);
			}
		}

		// add explode helper strings around tags
		$explode_str = '<!-- ARTA_TAG -->';
		$string      = RL_RegEx::replace(
			'(<\/?[a-z][a-z0-9]?.*?>|<!--.*?-->)',
			$explode_str . '\1' . $explode_str,
			$string
		);

		$str_array = explode($explode_str, $string);

		$string    = [];
		$tags      = [];
		$count     = 0;
		$is_script = 0;

		foreach ($str_array as $i => $str_part)
		{
			if (fmod($i, 2))
			{
				// is tag
				$string[] = $str_part;
				RL_RegEx::match(
					'^<(\/?([a-z][a-z0-9]*))',
					$str_part,
					$tag
				);

				if ( ! empty($tag))
				{
					if ($tag[1] == 'script')
					{
						$is_script = 1;
					}

					if ( ! $is_script
						// only if tag is not a single html tag
						&& (strpos($str_part, '/>') === false)
						// just in case single html tag has no closing character
						&& ! in_array($tag[1], ['area', 'br', 'hr', 'img', 'input', 'link', 'param'])
					)
					{
						$tags[] = $tag[1];
					}

					if ($tag[1] == '/script')
					{
						$is_script = 0;
					}
				}

				continue;
			}

			if ($is_script)
			{
				$string[] = $str_part;
				continue;
			}

			if ($type == 'words')
			{
				// word limit
				if ($str_part)
				{
					$words      = explode(' ', trim($str_part));
					$word_count = count($words);

					if ($limit < ($count + $word_count))
					{
						$words_part = [];
						$word_count = 0;
						foreach ($words as $word)
						{
							if ($word)
							{
								$word_count++;
							}

							if ($limit < ($count + $word_count))
							{
								break;
							}

							$words_part[] = $word;
						}

						$string_part = rtrim(implode(' ', $words_part));

						$string[] = self::addEllipsis($string_part);
						break;
					}

					$count += $word_count;
				}

				$string[] = $str_part;

				continue;
			}

			// character limit
			if ($limit < ($count + strlen($str_part)))
			{
				// strpart has to be cut off
				$maxlen = $limit - $count;

				if ($maxlen < 3)
				{
					$string_part = '';
					if (RL_RegEx::match('[^a-z0-9]$', $str_part))
					{
						$string_part .= ' ';
					}

					$string[] = self::addEllipsis($string_part);

					break;
				}

				$string[] = self::shorten($str_part, $limit);

				break;
			}

			$count += strlen($str_part);

			$string[] = $str_part;
		}

		// revers sort open tags
		krsort($tags);
		$tags  = array_values($tags);
		$count = count($tags);

		for ($i = 0; $i < 3; $i++)
		{
			foreach ($tags as $ti => $tag)
			{
				if ($tag[0] != '/')
				{
					continue;
				}

				for ($oi = $ti + 1; $oi < $count; $oi++)
				{
					if ( ! isset($tags[$oi]))
					{
						unset($tags[$ti]);
						break;
					}

					$opentag = $tags[$oi];

					if ($opentag == $tag)
					{
						break;
					}

					if ('/' . $opentag == $tag)
					{
						unset($tags[$ti]);
						unset($tags[$oi]);
						break;
					}
				}
			}
		}

		foreach ($tags as $tag)
		{
			// add closing tag to end of string
			if ($tag[0] != '/')
			{
				$string[] = '</' . $tag . '>';
			}
		}
		$string = implode('', $string);

		if ($pagenavcounter)
		{
			$string = str_replace('<!-- ARTA_PAGENAVCOUNTER -->', $pagenavcounter, $string);
		}

		if ($pagenavbar)
		{
			$string = str_replace('<!-- ARTA_PAGENAV -->', $pagenavbar, $string);
		}

		return $string;
	}

	private static function getPage($string, $data)
	{
		if (empty($data->page))
		{
			return $string;
		}

		$regex = '<hr title="([^"]*)" class="system-pagebreak".*?>';

		RL_RegEx::matchAll($regex, $string, $page_titles, null, PREG_PATTERN_ORDER);

		if (empty($page_titles))
		{
			return '';
		}

		$pages = explode('<!-- ARTA_PAGE_SPLITTER -->',
			RL_RegEx::replace($regex, '<!-- ARTA_PAGE_SPLITTER -->', $string)
		);

		if (is_numeric($data->page))
		{
			return isset($pages[$data->page - 1]) ? $pages[$data->page - 1] : '';
		}

		$title_pos = array_search($data->page, $page_titles[1]);

		if ($title_pos < 0)
		{
			return '';
		}

		return isset($pages[$title_pos + 1]) ? $pages[$title_pos + 1] : '';
	}

	private static function strip($string, $data)
	{
		$string = RL_String::removeHtml($string);

		return self::limit($string, $data);
	}

	private static function limit($string, $data)
	{
		if (empty($data->limit) && empty($data->words))
		{
			return $string;
		}

		if ( ! empty($data->words))
		{
			return self::limitWords($string, (int) $data->words);
		}

		return self::limitLetters($string, (int) $data->limit);
	}

	private static function limitWords($string, $limit)
	{
		$orig_len = strlen($string);

		// word limit
		$string = trim(
			RL_RegEx::replace(
				'^(([^\s]+\s*){' . (int) $limit . '}).*$',
				'\1',
				$string
			)
		);

		if (strlen($string) < $orig_len)
		{
			$string = self::addEllipsis($string);
		}

		return $string;
	}

	private static function limitLetters($string, $limit)
	{
		$orig_len = strlen($string);

		// character limit
		if ($limit >= $orig_len)
		{
			return $string;
		}

		return self::shorten($string, $limit);
	}

	private static function shorten($string, $limit)
	{
		if (strlen($string) <= $limit)
		{
			return $string;
		}

		$string = self::rtrim($string, $limit);

		return self::addEllipsis($string);
	}

	private static function rtrim($string, $limit)
	{
		if (function_exists('mb_substr'))
		{
			return rtrim(mb_substr($string, 0, ($limit - 3), 'utf-8'));
		}

		return rtrim(substr($string, 0, ($limit - 3)));
	}

	private static function addEllipsis($string)
	{
		if ( ! Params::get()->use_ellipsis)
		{
			return $string;
		}

		if (RL_RegEx::match('[^a-z0-9]$', $string))
		{
			$string .= ' ';
		}

		return $string . '...';
	}
}
