<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.menus
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// require_once JPATH_SITE.'/components/com_content/router.php';
// require_once JPATH_SITE.'/templates/padraogoverno01/html/com_search/search/_helper.php';

class plgSearchMenus extends JPlugin {

	function onContentSearchAreas() {
		static $areas = array(
			'menus' => 'Menus'
			);
		return $areas;
	}

	function isUtf8($string) {
		if (!function_exists('mb_detect_encoding')) {
			return preg_match('%^(?:
				[\x09\x0A\x0D\x20-\x7E]          	 # ASCII
				| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
				|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
				| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
				|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
				|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
				| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
				|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
				)*$%xs', $string);
		}

		return mb_detect_encoding($string, 'UTF-8', true);
	}

	/**
	 * Menu Search method
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null) {

		// verifica se esta contido na pesquisa.
		$searchText = (self::isUTF8($text) != 'UTF-8') ? utf8_decode($text) : $text;

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$tag   = JFactory::getLanguage()->getTag();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query1 = $db->getQuery(true);
		$query2 = $db->getQuery(true);

		require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

		$section 	= JText::_('Menus');
		$sContent	= $this->params->get('search_content',		1);
		$limit   	= $this->params->def('search_limit', 50);
		$filtro 	= $this->params->def('search_menu', 'Destaque');

		$text = trim($text);
		$text = strtolower($text);
		// $text = (self::isUTF8($text) != 'UTF-8') ? $text : utf8_decode($text);
		if ($text === '') {
			return array();
		}

		if ($sContent && $limit > 0) {

			// $filtro = self::isUTF8($filtro) ? $filtro : utf8_encode($filtro);
			$filtro	= $db->Quote('%'.$db->escape($filtro, true).'%', false);

			$wheres = array();
			switch ($phrase) {
				case 'exact':
				$text		= $db->Quote('%'.$db->escape($text, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'LOWER(a.title) LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

				case 'all':
				case 'any':
				default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%'.$db->escape($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'LOWER(a.title) LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
			}
			switch ($ordering) {
				case 'alpha':
				default:
				$order = 'LOWER(TRIM(a.title)) ASC';
				break;
			}

			$query1->select('a.id, a.title, a.path, a.link, a.params, 1 as sort_col');
			$query1->from('#__menu AS a');
			$query1->where($where);
			$query1->where('a.alias <> ' . $db->quote('root'));
			$query1->where($db->qn('a.published') . ' = 1');
			$query1->where($db->qn('a.params') . ' <> ""');
			$query1->where($db->qn('a.type') . ' = "component"');
			$query1->where('a.params' . ' LIKE ' . $filtro );
			$query1->order($order);
			$query1->setLimit($limit);

			$query2->select('a.id, a.title, a.path, a.link, a.params, 2 as sort_col');
			$query2->from('#__menu AS a');
			$query2->where($where);
			$query2->where('a.alias <> ' . $db->quote('root'));
			$query2->where($db->qn('a.published') . ' = 1');
			$query2->where($db->qn('a.params') . ' <> ""');
			$query2->where($db->qn('a.type') . ' = "component"');
			$query2->where('a.params' . ' NOT LIKE ' . $filtro );
			$query2->order($order);
			$query2->setLimit($limit);

			// Union the two queries.
			$query->select('a.id, a.title, a.path, a.link, a.params, 0 as sort_col');
			$query->from('#__menu AS a');
			$query->where('1 = 0');
			$query->union($query2);
			$query->union($query1);
			$query->order('sort_col');

			$db->setQuery($query, 0);

			// echo $query->dump();
			// die;

			$rows = $db->loadObjectList();

			if ($rows)
			{
				foreach ($rows as $key => $row)
				{
					$rows[$key]->text       = $row->title;
					$rows[$key]->href       = ($row->path !== '' ? $row->path : $row->link);
					$rows[$key]->section    =  $section;
					$rows[$key]->created    = "";
					$rows[$key]->browsernav = 0;
					$rows[$key]->params 	= $row->params;
				}
			}

			return $rows;

			// $results = array();
			// if (count($rows))	{
			// 	foreach($rows as $row)
			// 	{
			// 		$new_row = array();
			// 		foreach($row as $key => $article) {
			// 			if (searchHelper::checkNoHTML($article, $searchText, array('text', 'title', 'metadesc', 'metakey'))) {
			// 				$new_row[] = $article;
			// 			}
			// 		}
			// 		$results = array_merge($results, (array) $new_row);
			// 	}
			// }
		}

		// return $results;
	}
}
