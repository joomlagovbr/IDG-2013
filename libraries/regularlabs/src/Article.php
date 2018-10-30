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

use JFactory;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Class Article
 * @package RegularLabs\Library
 */
class Article
{
	static $articles = [];

	/**
	 * Method to get article data.
	 *
	 * @param   integer $id The id of the article.
	 *
	 * @return  object|boolean Menu item data object on success, boolean false
	 */
	public static function get($id = null, $get_unpublished = false)
	{
		$id = ! empty($id) ? $id : (int) self::getId();

		if (isset(self::$articles[$id]))
		{
			return self::$articles[$id];
		}

		$db   = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true)
			->select(
				[
					'a.id', 'a.asset_id', 'a.title', 'a.alias', 'a.introtext', 'a.fulltext',
					'a.state', 'a.catid', 'a.created', 'a.created_by', 'a.created_by_alias',
					// Use created if modified is 0
					'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified',
					'a.modified_by', 'a.checked_out', 'a.checked_out_time', 'a.publish_up', 'a.publish_down',
					'a.images', 'a.urls', 'a.attribs', 'a.version', 'a.ordering',
					'a.metakey', 'a.metadesc', 'a.access', 'a.hits', 'a.metadata', 'a.featured', 'a.language', 'a.xreference',
				]
			)
			->from($db->quoteName('#__content', 'a'))
			->where($db->quoteName('a.id') . ' = ' . (int) $id);

		// Join on category table.
		$query->select([
			$db->quoteName('c.title', 'category_title'),
			$db->quoteName('c.alias', 'category_alias'),
			$db->quoteName('c.access', 'category_access'),
		])
			->innerJoin($db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'))
			->where($db->quoteName('c.published') . ' > 0');

		// Join on user table.
		$query->select($db->quoteName('u.name', 'author'))
			->join('LEFT', $db->quoteName('#__users', 'u') . ' ON ' . $db->quoteName('u.id') . ' = ' . $db->quoteName('a.created_by'));

		// Join over the categories to get parent category titles
		$query->select([
			$db->quoteName('parent.title', 'parent_title'),
			$db->quoteName('parent.id', 'parent_id'),
			$db->quoteName('parent.path', 'parent_route'),
			$db->quoteName('parent.alias', 'parent_alias'),
		])
			->join('LEFT', $db->quoteName('#__categories', 'parent') . ' ON ' . $db->quoteName('parent.id') . ' = ' . $db->quoteName('c.parent_id'));

		// Join on voting table
		$query->select([
			'ROUND(v.rating_sum / v.rating_count, 0) AS rating',
			$db->quoteName('v.rating_count', 'rating_count'),
		])
			->join('LEFT', $db->quoteName('#__content_rating', 'v') . ' ON ' . $db->quoteName('v.content_id') . ' = ' . $db->quoteName('a.id'));

		if ( ! $get_unpublished
			&& ( ! $user->authorise('core.edit.state', 'com_content'))
			&& ( ! $user->authorise('core.edit', 'com_content'))
		)
		{
			// Filter by start and end dates.
			$nullDate = $db->quote($db->getNullDate());
			$date     = JFactory::getDate();

			$nowDate = $db->quote($date->toSql());

			$query->where($db->quoteName('a.state') . ' = 1')
				->where('(' . $db->quoteName('a.publish_up') . ' = ' . $nullDate . ' OR ' . $db->quoteName('a.publish_up') . ' <= ' . $nowDate . ')')
				->where('(' . $db->quoteName('a.publish_down') . ' = ' . $nullDate . ' OR ' . $db->quoteName('a.publish_down') . ' >= ' . $nowDate . ')');
		}

		$db->setQuery($query);

		$data = $db->loadObject();

		if (empty($data))
		{
			return false;
		}

		// Convert parameter fields to objects.
		$data->params   = new Registry($data->attribs);
		$data->metadata = new Registry($data->metadata);

		self::$articles[$id] = $data;

		return self::$articles[$id];
	}

	/**
	 * Gets the current article id based on url data
	 */
	public static function getId()
	{
		$input = JFactory::getApplication()->input;

		if ( ! $id = $input->getInt('id')
			|| ! (($input->get('option') == 'com_content' && $input->get('view') == 'article')
				|| ($input->get('option') == 'com_flexicontent' && $input->get('view') == 'item')
			)
		)
		{
			return false;
		}

		return $id;
	}

	/**
	 * Passes the different article parts through the given plugin method
	 *
	 * @param object $article
	 * @param string $context
	 * @param object $helper
	 * @param string $method
	 * @param array  $params
	 * @param array  $ignore
	 */
	public static function process(&$article, &$context, &$helper, $method, $params = [], $ignore = [])
	{
		self::processText('title', $article, $helper, $method, $params, $ignore);
		self::processText('created_by_alias', $article, $helper, $method, $params, $ignore);

		if (Document::isCategoryList($context))
		{
			self::processText('description', $article, $helper, $method, $params, $ignore);

			return;
		}

		self::processText('text', $article, $helper, $method, $params, $ignore);

		if ( ! empty($article->text))
		{
			return;
		}

		self::processText('introtext', $article, $helper, $method, $params, $ignore);
		self::processText('fulltext', $article, $helper, $method, $params, $ignore);
	}

	private static function processText($type = '', &$article, &$helper, $method, $params = [], $ignore = [])
	{
		if (empty($article->{$type}))
		{
			return;
		}

		if (in_array($type, $ignore))
		{
			return;
		}

		call_user_func_array([$helper, $method], array_merge([&$article->{$type}], $params));
	}
}
