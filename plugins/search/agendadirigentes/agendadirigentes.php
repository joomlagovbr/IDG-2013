<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.contacts
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Contacts search plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.contacts
 * @since       1.6
 */
class PlgSearchAgendadirigentes extends JPlugin
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
			'agendadirigentes' => 'Agenda de autoridades'
		);

		return $areas;
	}

	/**
	 * Search content (contacts).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   string  $areas     An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		// require_once JPATH_SITE . '/components/com_contact/helpers/route.php';

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$limit = $this->params->def('search_limit', 50);
		$Itemid = $this->params->def('default_itemid', 0);

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section = JText::_('Agenda de autoridades');

		switch ($ordering)
		{
			case 'alpha':
				$order = 'comp.title ASC';
			break;

			case 'category':
				$order = 'car.name ASC, dir.name ASC';
			break;				
			case 'oldest':
				$order = 'comp.data_inicial ASC';
			break;
			case 'newest':
			case 'popular':
			default:
				$order = 'comp.data_inicial DESC';
		}

		$text = $db->quote('%' . $db->escape($text, true) . '%', false);

		$query = $db->getQuery(true);

		/*
SELECT
comp.title, comp.description AS text,
CONCAT('Agenda do(a) ', car.name, ' - ', dir.name) AS section,
comp.data_inicial AS 'created', comp.data_inicial, dir.id AS 'autoridade_id'
FROM x3dts_agendadirigentes_compromissos comp
INNER JOIN x3dts_agendadirigentes_dirigentes_compromissos dc
ON dc.compromisso_id = comp.id
INNER JOIN x3dts_agendadirigentes_dirigentes dir
ON (dc.dirigente_id = dir.id AND dc.owner = 1)
INNER JOIN x3dts_agendadirigentes_cargos car
ON car.id = dir.cargo_id
WHERE 
comp.title LIKE "%teste%" OR
comp.description LIKE "%teste%" OR
comp.participantes_externos LIKE "%teste%" OR
dir.name LIKE "%teste%"
		*/
		
		$query->select(
				$db->quoteName('comp.title') . ', ' .
				$db->quoteName('comp.description', 'text') . ', ' .
				'CONCAT('.$db->Quote('Agenda do(a) ').', '.$db->quoteName('car.name').', '.$db->Quote(' ').', '.$db->quoteName('dir.name').') AS section, ' .
				$db->quoteName('comp.data_inicial', 'created') . ', ' .
				$db->quoteName('comp.data_inicial') . ', ' .
				$db->quoteName('dir.id', 'autoridade_id') 
			)
			->from(
				$db->quoteName('#__agendadirigentes_compromissos', 'comp')
			)
			->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
        		. ' ON (' . $db->quoteName('dc.compromisso_id') . ' = ' . $db->quoteName('comp.id') . ')' 
			)
			->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_dirigentes', 'dir')
        		. ' ON (' . $db->quoteName('dc.dirigente_id') . ' = ' . $db->quoteName('dir.id')
        		. ' AND ' . $db->quoteName('dc.owner') . ' = 1 )' 
			)
			->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_cargos', 'car')
        		. ' ON (' . $db->quoteName('dir.cargo_id') . ' = ' . $db->quoteName('car.id') . ')' 
			)
			->where(
				'('.
				$db->quoteName('comp.title') . ' LIKE ' . $text . ' OR ' .
				$db->quoteName('comp.description') . ' LIKE ' . $text . ' OR ' .
				$db->quoteName('comp.participantes_externos') . ' LIKE ' . $text . ' OR ' .
				$db->quoteName('dir.name') . ' LIKE ' . $text .
				') AND ' .
				$db->quoteName('comp.state') . ' IN (1,2) AND ' . 
				$db->quoteName('dc.sobreposto') . ' = 0 AND ' . 
				$db->quoteName('dir.state') . ' IN (1,2) AND ' .
				$db->quoteName('car.published') . ' = 1' 

			)
			->order($order);

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		if ($rows)
		{
			foreach ($rows as $key => $row)
			{
				$rows[$key]->href = JURI::root() . 'index.php?option=com_agendadirigentes&view=autoridade&dia='
										.$rows[$key]->data_inicial.'&id='.$rows[$key]->autoridade_id
										.(($Itemid)? '&Itemid='.$Itemid : '');
				$rows[$key]->browsernav = 2;				
			}
		}

		return $rows;
	}
}
