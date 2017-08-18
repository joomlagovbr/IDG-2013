<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Groups Table
 *
 * @since  3.7.0
 */
class FieldsTableGroup extends JTable
{
	/**
	 * Class constructor.
	 *
	 * @param   JDatabaseDriver  $db  JDatabaseDriver object.
	 *
	 * @since   3.7.0
	 */
	public function __construct($db = null)
	{
		parent::__construct('#__fields_groups', 'id', $db);

		$this->setColumnAlias('published', 'state');
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.7.0
	 * @throws  InvalidArgumentException
	 */
	public function bind($src, $ignore = '')
	{
		if (isset($src['params']) && is_array($src['params']))
		{
			$registry = new Registry;
			$registry->loadArray($src['params']);
			$src['params'] = (string) $registry;
		}

		// Bind the rules.
		if (isset($src['rules']) && is_array($src['rules']))
		{
			$rules = new JAccessRules($src['rules']);
			$this->setRules($rules);
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @link    https://docs.joomla.org/Special:MyLanguage/JTable/check
	 * @since   3.7.0
	 */
	public function check()
	{
		// Check for a title.
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_FIELDS_MUSTCONTAIN_A_TITLE_GROUP'));

			return false;
		}

		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			$this->modified = $date->toSql();
			$this->modified_by = $user->get('id');
		}
		else
		{
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}

			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		return true;
	}

	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form table_name.id
	 * where id is the value of the primary key of the table.
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	protected function _getAssetName()
	{
		$component = explode('.', $this->context);

		return $component[0] . '.fieldgroup.' . (int) $this->id;
	}

	/**
	 * Method to return the title to use for the asset table.  In
	 * tracking the assets a title is kept for each asset so that there is some
	 * context available in a unified access manager.  Usually this would just
	 * return $this->title or $this->name or whatever is being used for the
	 * primary name of the row. If this method is not overridden, the asset name is used.
	 *
	 * @return  string  The string to use as the title in the asset table.
	 *
	 * @link    https://docs.joomla.org/Special:MyLanguage/JTable/getAssetTitle
	 * @since   3.7.0
	 */
	protected function _getAssetTitle()
	{
		return $this->title;
	}

	/**
	 * Method to get the parent asset under which to register this one.
	 * By default, all assets are registered to the ROOT node with ID,
	 * which will default to 1 if none exists.
	 * The extended class can define a table and id to lookup.  If the
	 * asset does not exist it will be created.
	 *
	 * @param   JTable   $table  A JTable object for the asset parent.
	 * @param   integer  $id     Id to look up
	 *
	 * @return  integer
	 *
	 * @since   3.7.0
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$component = explode('.', $this->context);
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($component[0]));
		$db->setQuery($query);

		if ($assetId = (int) $db->loadResult())
		{
			return $assetId;
		}

		return parent::_getAssetParentId($table, $id);
	}
}
