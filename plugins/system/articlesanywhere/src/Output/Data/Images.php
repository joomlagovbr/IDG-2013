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

use RegularLabs\Library\HtmlTag as RL_HtmlTag;
use RegularLabs\Library\RegEx as RL_RegEx;

defined('_JEXEC') or die;

class Images extends Data
{
	public function get($key, $attributes)
	{
		$regex = '^image(?<separator>[-_])(?<type>intro|fulltext)([-_](?<data>[a-z][a-z0-9-_]*))?$';

		RL_RegEx::match($regex, $key, $image_tag);

		if (empty($image_tag))
		{
			return null;
		}

		$data = isset($image_tag['data']) ? $image_tag['data'] : '';

		if ($image_tag['separator'] == '_' && empty($data))
		{
			$data = 'url';
		}

		$data = str_replace(
			[
				'thumbnail',
			],
			[
				'thumb',
			],
			$data
		);
		$data = RL_RegEx::replace('-?(tag|img)$', '', $data);

		return $this->getByType($image_tag['type'], $data, $attributes);
	}

	protected function getByType($type, $data, $attributes)
	{
		switch ($type)
		{
			case 'intro':
				return $this->getIntroImage($data, $attributes);

			case 'fulltext':
				return $this->getFulltextImage($data, $attributes);


			default:



				return '';
		}
	}

	protected function getIntroImage($data, $attributes)
	{
		return $this->getArticleImageDataByType('intro', $data, $attributes);
	}

	protected function getFulltextImage($data, $attributes)
	{
		return $this->getArticleImageDataByType('fulltext', $data, $attributes);
	}

	protected function getArticleImageDataByType($type = 'intro', $data, $attributes)
	{
		$type = $type == 'fulltext' ? 'fulltext' : 'intro';

		switch ($data)
		{
			case 'url':
				return $this->item->getFromGroup('images', 'image_' . $type);

			case 'alt':
				return $this->item->getFromGroup('images', 'image_' . $type . '_alt');

			case 'caption':
				return $this->item->getFromGroup('images', 'image_' . $type . '_caption');

			case 'class':
				return 'img-' . $type . '-' . $this->item->getFromGroup('images', 'float_' . $type);

			default:
				return $this->getArticleImageTagByType($type, $attributes);
		}
	}

	protected function getArticleImageTagByType($type = 'intro', $attributes)
	{
		$url = $this->item->getFromGroup('images', 'image_' . $type);

		if (empty($url))
		{
			return '';
		}

		$class   = 'img-intro-' . $this->item->getFromGroup('images', 'float_' . $type);
		$alt     = $this->item->getFromGroup('images', 'image_' . $type . '_alt');
		$caption = $this->item->getFromGroup('images', 'image_' . $type . '_caption');
		$title   = $caption ?: $alt;

		return $this->getImageHtml(
			$url,
			$alt,
			$title,
			$class,
			$attributes,
			! empty($caption)
		);
	}


	public static function getImageHtml(
		$url, $alt = '', $title = '', $class = '',
		$attributes = [],
		$caption = false, $in_div = true
	)
	{
		$img_class = $caption ? 'caption' : '';
		$title     = $title ? ' title="' . htmlspecialchars($title) . '"' : '';

		$img_class = trim($img_class . ' ' . htmlspecialchars($class));

		$tag = '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '"' . $title . ' class="' . $img_class . '">';

		$image = self::getImageHtmlWithAttributes($tag, $attributes);

		if ( ! $in_div)
		{
			return $image;
		}

		return '<div class="' . htmlspecialchars($class) . '">'
			. $image
			. '</div>';
	}

	static protected function getImageHtmlWithAttributes($tag, $attributes)
	{
		if (empty($attributes))
		{
			return $tag;
		}

		$tag_attributes = RL_HtmlTag::getAttributes($tag);

		if (isset($attributes->suffix) && isset($tag_attributes['src']))
		{
			$tag_attributes['src'] = RL_RegEx::replace(
				'\.[a-z]*$',
				$attributes->suffix . '\0',
				$tag_attributes['src']
			);
			unset($attributes->suffix);
		}

		$tag_attributes = array_merge($tag_attributes, (array) $attributes);

		return '<img ' . RL_HtmlTag::flattenAttributes($tag_attributes) . ' />';
	}

}
