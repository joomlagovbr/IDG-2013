<?php
/**
 * @package         Articles Anywhere
 * @version         7.5.1
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


		return null;
	}

	public function getArticleImage($key, $attributes)
	{
		$type = $key == 'image-intro' ? 'intro' : 'fulltext';

		$url = $this->item->getFromGroup('images', 'image_' . $type);

		if (empty($url))
		{
			return '';
		}

		$class = 'img-intro-' . $this->item->getFromGroup('images', 'float_' . $type);

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

	public function getCategoryImage($key, $attributes)
	{
		$params = json_decode($this->item->get('category_params', '{}'));

		if (empty($params) || empty($params->image))
		{
			return '';
		}

		$url = $params->image;

		if ($key == 'category_image')
		{
			return $url;
		}

		$class = 'img-category';

		return $this->getImageHtml(
			$url,
			isset($params->image_alt) ? $params->image_alt : '',
			$this->item->get('category_title'),
			$class,
			$attributes
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

		if ($in_div)
		{
			return '<div class="' . htmlspecialchars($class) . '">'
				. '<img' . $title . ' src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '" class="' . $img_class . '">'
				. '</div>';
		}

		$img_class = trim($img_class . ' ' . htmlspecialchars($class));

		$tag = '<img' . $title . ' src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '" class="' . $img_class . '">';

		return self::getImageHtmlWithAttributes($tag, $attributes);
	}

}
