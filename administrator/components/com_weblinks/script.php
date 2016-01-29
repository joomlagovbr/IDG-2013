<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  3.4
 */
class Com_WeblinksInstallerScript
{
	/**
	 * Function to perform changes during install
	 *
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function install($parent)
	{
		// Initialize a new category
		/** @type  JTableCategory  $category  */
		$category = JTable::getInstance('Category');

		// Check if the Uncategorised category exists before adding it
		if (!$category->load(array('extension' => 'com_weblinks', 'title' => 'Uncategorised')))
		{
			$category->extension = 'com_weblinks';
			$category->title = 'Uncategorised';
			$category->description = '';
			$category->published = 1;
			$category->access = 1;
			$category->params = '{"category_layout":"","image":""}';
			$category->metadata = '{"author":"","robots":""}';
			$category->language = '*';
			$category->checked_out_time = JFactory::getDbo()->getNullDate();

			// Set the location in the tree
			$category->setLocation(1, 'last-child');

			// Check to make sure our data is valid
			if (!$category->check())
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_WEBLINKS_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Now store the category
			if (!$category->store(true))
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_WEBLINKS_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Build the path for our category
			$category->rebuildPath($category->id);
		}
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   string                      $type    The action being performed
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	public function postflight($type, $parent)
	{
		// Only execute database changes on MySQL databases
		$dbName = JFactory::getDbo()->name;

		if (strpos($dbName, 'mysql') !== false)
		{
			// Add Missing Table Colums if needed
			$this->addColumnsIfNeeded();

			// Drop the Table Colums if needed
			$this->dropColumnsIfNeeded();
		}

		// Insert missing UCM Records if needed
		$this->insertMissingUcmRecords();
	}

	/**
	 * Method to insert missing records for the UCM tables
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function insertMissingUcmRecords()
	{
		// Insert the rows in the #__content_types table if they don't exist already
		$db = JFactory::getDbo();

		// Get the type ID for a Weblink
		$query = $db->getQuery(true);
		$query->select($db->quoteName('type_id'))
			->from($db->quoteName('#__content_types'))
			->where($db->quoteName('type_alias') . ' = ' . $db->quote('com_weblinks.weblink'));
		$db->setQuery($query);

		$weblinkTypeId = $db->loadResult();

		// Get the type ID for a Weblink Category
		$query->clear('where');
		$query->where($db->quoteName('type_alias') . ' = ' . $db->quote('com_weblinks.category'));
		$db->setQuery($query);

		$categoryTypeId = $db->loadResult();

		// Set the table columns to insert table to
		$columnsArray = array(
			$db->quoteName('type_title'),
			$db->quoteName('type_alias'),
			$db->quoteName('table'),
			$db->quoteName('rules'),
			$db->quoteName('field_mappings'),
			$db->quoteName('router'),
			$db->quoteName('content_history_options'),
		);

		// If we have no type id for com_weblinks.weblink insert it
		if (!$weblinkTypeId)
		{
			// Insert the data.
			$query->clear();
			$query->insert($db->quoteName('#__content_types'));
			$query->columns($columnsArray);
			$query->values(
				$db->quote('Weblink') . ', '
				. $db->quote('com_weblinks.weblink') . ', '
				. $db->quote('{"special":{"dbtable":"#__weblinks","key":"id","type":"Weblink","prefix":"WeblinksTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}') . ', '
				. $db->quote('') . ', '
				. $db->quote('{"common":{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"hits","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"params", "core_featured":"featured", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"url", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"xreference", "asset_id":"null"}, "special":{}}') . ', '
				. $db->quote('WeblinksHelperRoute::getWeblinkRoute') . ', '
				. $db->quote('{"formFile":"administrator\\/components\\/com_weblinks\\/models\\/forms\\/weblink.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","featured","images"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"], "convertToInt":["publish_up", "publish_down", "featured", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}')
			);

			$db->setQuery($query);
			$db->execute();
		}

		// If we have no type id for com_weblinks.category insert it
		if (!$categoryTypeId)
		{
			// Insert the data.
			$query->clear();
			$query->insert($db->quoteName('#__content_types'));
			$query->columns($columnsArray);
			$query->values(
				$db->quote('Weblinks Category') . ', '
				. $db->quote('com_weblinks.category') . ', '
				. $db->quote('{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}') . ', '
				. $db->quote('') . ', '
				. $db->quote('{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}') . ', '
				. $db->quote('WeblinksHelperRoute::getCategoryRoute') . ', '
				. $db->quote('{"formFile":"administrator\\/components\\/com_categories\\/models\\/forms\\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}')
			);

			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Method to drop colums from #__weblinks if they still there.
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function dropColumnsIfNeeded()
	{
		$oldColumns = array(
			'sid',
			'date',
			'archived',
			'approved',
		);

		$db    = JFactory::getDbo();
		$table = $db->getTableColumns('#__weblinks');

		$columns = array_intersect($oldColumns, array_keys($table));

		foreach ($columns as $column)
		{
			$sql = 'ALTER TABLE ' . $db->quoteName('#__weblinks') . ' DROP COLUMN ' . $db->quoteName($column);
			$db->setQuery($sql);
			$db->execute();
		}
	}

	/**
	 * Method to add colums from #__weblinks if they are missing.
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function addColumnsIfNeeded()
	{
		$db    = JFactory::getDbo();
		$table = $db->getTableColumns('#__weblinks');

		if (!array_key_exists('version', $table))
		{
			$sql = 'ALTER TABLE ' . $db->quoteName('#__weblinks') . ' ADD COLUMN ' . $db->quoteName('version') . " int(10) unsigned NOT NULL DEFAULT '1'";
			$db->setQuery($sql);
			$db->execute();
		}

		if (!array_key_exists('images', $table))
		{
			$sql = 'ALTER TABLE ' . $db->quoteName('#__weblinks') . ' ADD COLUMN ' . $db->quoteName('images') . ' text NOT NULL';
			$db->setQuery($sql);
			$db->execute();
		}
	}
}
