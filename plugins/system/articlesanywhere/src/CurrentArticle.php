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

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

use JHelperTags;

defined('_JEXEC') or die;

class CurrentArticle
{
	static $article = null;

	public static function get($key = null, $component = 'default')
	{
		$article = self::getCurrentArticle($component);

		if (is_null($key))
		{
			return $article ?: (object) [];
		}

//		if ($key == 'id' && ! isset($article->id))
//		{
//			return 0;
//		}

		return isset($article->{$key}) ? $article->{$key} : null;
	}

	public static function set($article)
	{
		if ( ! isset($article->id))
		{
			return;
		}

		self::$article = $article;
	}

	private static function getCurrentArticle($component = 'default')
	{
		if ( ! is_null(self::$article))
		{
			return self::$article;
		}

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
