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

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

use JHelperTags;

defined('_JEXEC') or die;

class CurrentArticle
{
	public static function get($key = null, $component = 'default')
	{
		$article = self::getCurrentArticle($component);

		if (is_null($key))
		{
			return $article ?: (object) [];
		}

		return isset($article->{$key}) ? $article->{$key} : null;
	}

	private static function getCurrentArticle($component = 'default')
	{
		return Factory::getCurrentItem($component)->get();
	}

	public static function getTags($id = null)
	{
		$id = $id ?: self::get('id');

		if (empty($id))
		{
			return [];
		}

		$tags = new JHelperTags;
		$tags->getItemTags('com_content.article', $id);

		return $tags->itemTags;
	}

	public static function getTagIds($id = null)
	{
		$tags = self::getTags($id);

		if (empty($tags))
		{
			return [];
		}

		return array_map(function ($tag) {
			return $tag->id;
		}, $tags);
	}

}
