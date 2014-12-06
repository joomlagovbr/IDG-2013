<?php
/**
 * @subpackage  Search.agendadirigentes
 */

defined('_JEXEC') or die;

/**
 * agendadirigentes search plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.agendadirigentes
 */
class PlgSearchAgendadirigentes extends JPlugin
{

	protected $autoloadLanguage = true;

	public function onContentSearchAreas()
	{
		static $areas = array(
			'agendadirigentes' => PLG_SEARCH_AGENDADIRIGENTES_NAME
		);

		return $areas;
	}

	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{

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

		$section = JText::_('PLG_SEARCH_AGENDADIRIGENTES_NAME');

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
		
		$query->select(
				$db->quoteName('comp.title') . ', ' .
				$db->quoteName('comp.description', 'text') . ', ' .
				'CONCAT('.$db->Quote(JText::_('PLG_SEARCH_AGENDADIRIGENTES_CARGO_INTRO').' ').', '.$db->quoteName('car.name').', '.$db->Quote('(a) ').', '.$db->quoteName('dir.name').') AS section, ' .
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
