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

class Div extends Data
{
	public function get($key, $attributes)
	{
		$tag_attributes = [];

		if (isset($attributes->class))
		{
			$tag_attributes[] = 'class="' . $attributes->class . '"';
		}

		$style = [];

		if (isset($attributes->width))
		{
			if (is_numeric($attributes->width))
			{
				$attributes->width .= 'px';
			}
			$style[] = 'width:' . $attributes->width;
		}

		if (isset($attributes->height))
		{
			if (is_numeric($attributes->height))
			{
				$attributes->height .= 'px';
			}
			$style[] = 'height:' . $attributes->height;
		}

		if (isset($attributes->align))
		{
			$style[] = 'float:' . $attributes->align;
		}
		else if (isset($attributes->float))
		{
			$style[] = 'float:' . $attributes->float;
		}

		if ( ! empty($style))
		{
			$tag_attributes[] = 'style="' . implode(';', $style) . ';"';
		}

		if (empty($tag_attributes))
		{
			return '<div>';
		}

		return trim('<div ' . implode(' ', $tag_attributes)) . '>';
	}
}
