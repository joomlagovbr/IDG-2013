<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Menus component helper.
 *
 * @since  1.6
 */
class MenusHelper
{
	/**
	 * Defines the valid request variables for the reverse lookup.
	 */
	protected static $_filter = array('option', 'view', 'layout');

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_MENUS_SUBMENU_MENUS'),
			'index.php?option=com_menus&view=menus',
			$vName == 'menus'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MENUS_SUBMENU_ITEMS'),
			'index.php?option=com_menus&view=items',
			$vName == 'items'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  $parentId  The menu ID.
	 *
	 * @return  JObject
	 *
	 * @since   1.6
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions($parentId = 0)
	{
		// Log usage of deprecated function
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperContent::getActions() with new arguments order instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		// Get list of actions
		return JHelperContent::getActions('com_menus');
	}

	/**
	 * Gets a standard form of a link for lookups.
	 *
	 * @param   mixed  $request  A link string or array of request variables.
	 *
	 * @return  mixed  A link in standard option-view-layout form, or false if the supplied response is invalid.
	 *
	 * @since   1.6
	 */
	public static function getLinkKey($request)
	{
		if (empty($request))
		{
			return false;
		}

		// Check if the link is in the form of index.php?...
		if (is_string($request))
		{
			$args = array();

			if (strpos($request, 'index.php') === 0)
			{
				parse_str(parse_url(htmlspecialchars_decode($request), PHP_URL_QUERY), $args);
			}
			else
			{
				parse_str($request, $args);
			}

			$request = $args;
		}

		// Only take the option, view and layout parts.
		foreach ($request as $name => $value)
		{
			if ((!in_array($name, self::$_filter)) && (!($name == 'task' && !array_key_exists('view', $request))))
			{
				// Remove the variables we want to ignore.
				unset($request[$name]);
			}
		}

		ksort($request);

		return 'index.php?' . http_build_query($request, '', '&');
	}

	/**
	 * Get the menu list for create a menu module
	 *
	 * @param   int  $clientId  Optional client id - viz 0 = site, 1 = administrator, can be NULL for all
	 *
	 * @return  array  The menu array list
	 *
	 * @since    1.6
	 */
	public static function getMenuTypes($clientId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.menutype')
			->from('#__menu_types AS a');

		if (isset($clientId))
		{
			$query->where('a.client_id = ' . (int) $clientId);
		}

		$db->setQuery($query);

		return $db->loadColumn();
	}

	/**
	 * Get a list of menu links for one or all menus.
	 *
	 * @param   string   $menuType   An option menu to filter the list on, otherwise all menu with given client id links
	 *                               are returned as a grouped array.
	 * @param   integer  $parentId   An optional parent ID to pivot results around.
	 * @param   integer  $mode       An optional mode. If parent ID is set and mode=2, the parent and children are excluded from the list.
	 * @param   array    $published  An optional array of states
	 * @param   array    $languages  Optional array of specify which languages we want to filter
	 * @param   int      $clientId   Optional client id - viz 0 = site, 1 = administrator, can be NULL for all (used only if menutype not givein)
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public static function getMenuLinks($menuType = null, $parentId = 0, $mode = 0, $published = array(), $languages = array(), $clientId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(a.id) AS value,
					  a.title AS text,
					  a.alias,
					  a.level,
					  a.menutype,
					  a.client_id,
					  a.type,
					  a.published,
					  a.template_style_id,
					  a.checked_out,
					  a.language,
					  a.lft')
			->from('#__menu AS a');

		$query->select('e.name as componentname, e.element')
			->join('left', '#__extensions e ON e.extension_id = a.component_id');

		if (JLanguageMultilang::isEnabled())
		{
			$query->select('l.title AS language_title, l.image AS language_image, l.sef AS language_sef')
				->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');
		}

		// Filter by the type if given, this is more specific than client id
		if ($menuType)
		{
			$query->where('(a.menutype = ' . $db->quote($menuType) . ' OR a.parent_id = 0)');
		}
		elseif (isset($clientId))
		{
			$query->where('a.client_id = ' . (int) $clientId);
		}

		// Prevent the parent and children from showing if requested.
		if ($parentId && $mode == 2)
		{
			$query->join('LEFT', '#__menu AS p ON p.id = ' . (int) $parentId)
				->where('(a.lft <= p.lft OR a.rgt >= p.rgt)');
		}

		if (!empty($languages))
		{
			if (is_array($languages))
			{
				$languages = '(' . implode(',', array_map(array($db, 'quote'), $languages)) . ')';
			}

			$query->where('a.language IN ' . $languages);
		}

		if (!empty($published))
		{
			if (is_array($published))
			{
				$published = '(' . implode(',', $published) . ')';
			}

			$query->where('a.published IN ' . $published);
		}

		$query->where('a.published != -2');
		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$links = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		if (empty($menuType))
		{
			// If the menutype is empty, group the items by menutype.
			$query->clear()
				->select('*')
				->from('#__menu_types')
				->where('menutype <> ' . $db->quote(''))
				->order('title, menutype');

			if (isset($clientId))
			{
				$query->where('client_id = ' . (int) $clientId);
			}

			$db->setQuery($query);

			try
			{
				$menuTypes = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage());

				return false;
			}

			// Create a reverse lookup and aggregate the links.
			$rlu = array();

			foreach ($menuTypes as &$type)
			{
				$rlu[$type->menutype] = & $type;
				$type->links = array();
			}

			// Loop through the list of menu links.
			foreach ($links as &$link)
			{
				if (isset($rlu[$link->menutype]))
				{
					$rlu[$link->menutype]->links[] = & $link;

					// Cleanup garbage.
					unset($link->menutype);
				}
			}

			return $menuTypes;
		}
		else
		{
			return $links;
		}
	}

	/**
	 * Get the associations
	 *
	 * @param   integer  $pk  Menu item id
	 *
	 * @return  array
	 *
	 * @since   3.0
	 */
	public static function getAssociations($pk)
	{
		$langAssociations = JLanguageAssociations::getAssociations('com_menus', '#__menu', 'com_menus.item', $pk, 'id', '', '');
		$associations     = array();

		foreach ($langAssociations as $langAssociation)
		{
			$associations[$langAssociation->language] = $langAssociation->id;
		}

		return $associations;
	}
}
