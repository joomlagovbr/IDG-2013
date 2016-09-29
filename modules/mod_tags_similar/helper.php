<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Helper for mod_tags_similar
 *
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 * @since       3.1
 */
abstract class ModTagssimilarHelper
{
	/**
	 * Get a list of tags
	 *
	 * @param   Registry  &$params  Module parameters
	 *
	 * @return  mixed  Results array / null
	 */
	public static function getList(&$params)
	{
		$app        = JFactory::getApplication();
		$option     = $app->input->get('option');
		$view       = $app->input->get('view');

		// For now assume com_tags and com_users do not have tags.
		// This module does not apply to list views in general at this point.
		if ($option == 'com_tags' || $view == 'category' || $option == 'com_users')
		{
			return;
		}

		$db         = JFactory::getDbo();
		$user       = JFactory::getUser();
		$groups     = implode(',', $user->getAuthorisedViewLevels());
		$matchtype  = $params->get('matchtype', 'all');
		$maximum    = $params->get('maximum', 5);
		$ordering   = $params->get('ordering', 'count');
		$tagsHelper = new JHelperTags;
		$prefix     = $option . '.' . $view;
		$id         = $app->input->getInt('id');
		$now        = JFactory::getDate()->toSql();
		$nullDate   = $db->getNullDate();

		$tagsToMatch = $tagsHelper->getTagIds($id, $prefix);

		if (!$tagsToMatch || is_null($tagsToMatch))
		{
			return;
		}

		$tagCount = substr_count($tagsToMatch, ',') + 1;

		$query = $db->getQuery(true)
			->select(
			array(
				$db->quoteName('m.core_content_id'),
				$db->quoteName('m.content_item_id'),
				$db->quoteName('m.type_alias'),
					'COUNT( ' . $db->quoteName('tag_id') . ') AS ' . $db->quoteName('count'),
				$db->quoteName('ct.router'),
				$db->quoteName('cc.core_title'),
				$db->quoteName('cc.core_alias'),
				$db->quoteName('cc.core_catid'),
				$db->quoteName('cc.core_language'),
				$db->quoteName('cc.core_params')
				)
		);

		$query->from($db->quoteName('#__contentitem_tag_map', 'm'));

		$query->join('INNER', $db->quoteName('#__tags', 't') . ' ON m.tag_id = t.id')
			->join('INNER', $db->quoteName('#__ucm_content', 'cc') . ' ON m.core_content_id = cc.core_content_id')
			->join('INNER', $db->quoteName('#__content_types', 'ct') . ' ON m.type_alias = ct.type_alias');

		$query->where($db->quoteName('m.tag_id') . ' IN (' . $tagsToMatch . ')');
		$query->where('t.access IN (' . $groups . ')');
		$query->where('(cc.core_access IN (' . $groups . ') OR cc.core_access = 0)');

		// Don't show current item
		$query->where('(' . $db->quoteName('m.content_item_id') . ' <> ' . $id
			. ' OR ' . $db->quoteName('m.type_alias') . ' <> ' . $db->quote($prefix) . ')');

		// Only return published tags
		$query->where($db->quoteName('cc.core_state') . ' = 1 ')
			->where('(' . $db->quoteName('cc.core_publish_up') . '=' . $db->quote($nullDate) . ' OR '
				. $db->quoteName('cc.core_publish_up') . '<=' . $db->quote($now) . ')')
			->where('(' . $db->quoteName('cc.core_publish_down') . '=' . $db->quote($nullDate) . ' OR '
				. $db->quoteName('cc.core_publish_down') . '>=' . $db->quote($now) . ')');

		// Optionally filter on language
		$language = JComponentHelper::getParams('com_tags')->get('tag_list_language_filter', 'all');

		if ($language != 'all')
		{
			if ($language == 'current_language')
			{
				$language = JHelperContent::getCurrentLanguage();
			}

			$query->where($db->quoteName('cc.core_language') . ' IN (' . $db->quote($language) . ', ' . $db->quote('*') . ')');
		}

		$query->group(
			$db->quoteName(
				array('m.core_content_id', 'm.content_item_id', 'm.type_alias', 'ct.router', 'cc.core_title',
				'cc.core_alias', 'cc.core_catid', 'cc.core_language', 'cc.core_params')
			)
		);

		if ($matchtype == 'all' && $tagCount > 0)
		{
			$query->having('COUNT( ' . $db->quoteName('tag_id') . ')  = ' . $tagCount);
		}
		elseif ($matchtype == 'half' && $tagCount > 0)
		{
			$tagCountHalf = ceil($tagCount / 2);
			$query->having('COUNT( ' . $db->quoteName('tag_id') . ')  >= ' . $tagCountHalf);
		}

		if ($ordering == 'count' || $ordering == 'countrandom')
		{
			$query->order($db->quoteName('count') . ' DESC');
		}

		if ($ordering == 'random' || $ordering == 'countrandom')
		{
			$query->order($query->Rand());
		}

		$db->setQuery($query, 0, $maximum);

		try
		{
			$results = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$results = array();
			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		foreach ($results as $result)
		{
			$explodedAlias = explode('.', $result->type_alias);
			$result->link = 'index.php?option=' . $explodedAlias[0] . '&view=' . $explodedAlias[1]
				. '&id=' . $result->content_item_id . '-' . $result->core_alias;

			$result->core_params = new Registry($result->core_params);
		}

		return $results;
	}
}
