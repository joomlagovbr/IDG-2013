<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Newsfeeds search plugin.
 *
 * @since  1.6
 */
class PlgSearchNewsfeeds extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.6
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'newsfeeds' => 'PLG_SEARCH_NEWSFEEDS_NEWSFEEDS'
		);

		return $areas;
	}

	/**
	 * Search content (newsfeeds).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		if (is_array($areas) && !array_intersect($areas, array_keys($this->onContentSearchAreas())))
		{
			return array();
		}

		$sContent = $this->params->get('search_content', 1);
		$sArchived = $this->params->get('search_archived', 1);
		$limit = $this->params->def('search_limit', 50);
		$state = array();

		if ($sContent)
		{
			$state[] = 1;
		}

		if ($sArchived)
		{
			$state[] = 2;
		}

		if (empty($state))
		{
			return array();
		}

		$text = trim($text);

		if ($text === '')
		{
			return array();
		}

		switch ($phrase)
		{
			case 'exact':
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.name LIKE ' . $text;
				$wheres2[] = 'a.link LIKE ' . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'a.name LIKE ' . $word;
					$wheres2[] = 'a.link LIKE ' . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}

				$where = '(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'alpha':
				$order = 'a.name ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.name ASC';
				break;

			case 'oldest':
			case 'popular':
			case 'newest':
			default:
				$order = 'a.name ASC';
		}

		$searchNewsfeeds = JText::_('PLG_SEARCH_NEWSFEEDS_NEWSFEEDS');

		$query = $db->getQuery(true);

		// SQLSRV changes.
		$case_when  = ' CASE WHEN ';
		$case_when .= $query->charLength('a.alias', '!=', '0');
		$case_when .= ' THEN ';
		$a_id       = $query->castAsChar('a.id');
		$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id . ' END as slug';

		$case_when1  = ' CASE WHEN ';
		$case_when1 .= $query->charLength('c.alias', '!=', '0');
		$case_when1 .= ' THEN ';
		$c_id        = $query->castAsChar('c.id');
		$case_when1 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $c_id . ' END as catslug';

		$query->select('a.name AS title, \'\' AS created, a.link AS text, ' . $case_when . ',' . $case_when1)
			->select($query->concatenate(array($db->quote($searchNewsfeeds), 'c.title'), ' / ') . ' AS section')
			->select('\'1\' AS browsernav')
			->from('#__newsfeeds AS a')
			->join('INNER', '#__categories as c ON c.id = a.catid')
			->where('(' . $where . ') AND a.published IN (' . implode(',', $state) . ') AND c.published = 1 AND c.access IN (' . $groups . ')')
			->order($order);

		// Filter by language.
		if ($app->isClient('site') && JLanguageMultilang::isEnabled())
		{
			$tag = JFactory::getLanguage()->getTag();
			$query->where('a.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')')
				->where('c.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
		}

		$db->setQuery($query, 0, $limit);

		try
		{
			$rows = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$rows = array();
			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		if ($rows)
		{
			foreach ($rows as $key => $row)
			{
				$rows[$key]->href = 'index.php?option=com_newsfeeds&view=newsfeed&catid=' . $row->catslug . '&id=' . $row->slug;
			}
		}

		return $rows;
	}
}
