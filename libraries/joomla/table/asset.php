<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Table class supporting modified pre-order tree traversal behavior.
 *
 * @since  11.1
 */
class JTableAsset extends JTableNested
{
	/**
	 * The primary key of the asset.
	 *
	 * @var    integer
	 * @since  11.1
	 */
	public $id = null;

	/**
	 * The unique name of the asset.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $name = null;

	/**
	 * The human readable title of the asset.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $title = null;

	/**
	 * The rules for the asset stored in a JSON string
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $rules = null;

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   11.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__assets', 'id', $db);
	}

	/**
	 * Method to load an asset by its name.
	 *
	 * @param   string  $name  The name of the asset.
	 *
	 * @return  integer
	 *
	 * @since   11.1
	 */
	public function loadByName($name)
	{
		$query = $this->_db->getQuery(true)
			->select($this->_db->quoteName('id'))
			->from($this->_db->quoteName('#__assets'))
			->where($this->_db->quoteName('name') . ' = ' . $this->_db->quote($name));
		$this->_db->setQuery($query);
		$assetId = (int) $this->_db->loadResult();

		if (empty($assetId))
		{
			return false;
		}

		return $this->load($assetId);
	}

	/**
	 * Assert that the nested set data is valid.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @since   11.1
	 */
	public function check()
	{
		$this->parent_id = (int) $this->parent_id;

		if (empty($this->rules))
		{
			$this->rules = '{}';
		}
		// JTableNested does not allow parent_id = 0, override this.
		if ($this->parent_id > 0)
		{
			// Get the JDatabaseQuery object
			$query = $this->_db->getQuery(true)
				->select('COUNT(id)')
				->from($this->_db->quoteName($this->_tbl))
				->where($this->_db->quoteName('id') . ' = ' . $this->parent_id);
			$this->_db->setQuery($query);

			if ($this->_db->loadResult())
			{
				return true;
			}
			else
			{
				$this->setError('Invalid Parent ID');

				return false;

			}
		}

		return true;
	}

	/**
	 * Method to recursively rebuild the whole nested set tree.
	 *
	 * @param   integer  $parentId  The root of the tree to rebuild.
	 * @param   integer  $leftId    The left id to start with in building the tree.
	 * @param   integer  $level     The level to assign to the current nodes.
	 * @param   string   $path      The path to the current nodes.
	 *
	 * @return  integer  1 + value of root rgt on success, false on failure
	 *
	 * @since   3.5
	 * @throws  RuntimeException on database error.
	 */
	public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = null)
	{
		// If no parent is provided, try to find it.
		if ($parentId === null)
		{
			// Get the root item.
			$parentId = $this->getRootId();

			if ($parentId === false)
			{
				return false;
			}
		}

		$query = $this->_db->getQuery(true);

		// Build the structure of the recursive query.
		if (!isset($this->_cache['rebuild.sql']))
		{
			$query->clear()
				->select($this->_tbl_key)
				->from($this->_tbl)
				->where('parent_id = %d');

			// If the table has an ordering field, use that for ordering.
			if (property_exists($this, 'ordering'))
			{
				$query->order('parent_id, ordering, lft');
			}
			else
			{
				$query->order('parent_id, lft');
			}

			$this->_cache['rebuild.sql'] = (string) $query;
		}

		// Make a shortcut to database object.

		// Assemble the query to find all children of this node.
		$this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int) $parentId));

		$children = $this->_db->loadObjectList();

		// The right value of this node is the left value + 1
		$rightId = $leftId + 1;

		// Execute this function recursively over all children
		foreach ($children as $node)
		{
			/*
			 * $rightId is the current right value, which is incremented on recursion return.
			 * Increment the level for the children.
			 * Add this item's alias to the path (but avoid a leading /)
			 */
			$rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $level + 1);

			// If there is an update failure, return false to break out of the recursion.
			if ($rightId === false)
			{
				return false;
			}
		}

		// We've got the left value, and now that we've processed
		// the children of this node we also know the right value.
		$query->clear()
			->update($this->_tbl)
			->set('lft = ' . (int) $leftId)
			->set('rgt = ' . (int) $rightId)
			->set('level = ' . (int) $level)
			->where($this->_tbl_key . ' = ' . (int) $parentId);
		$this->_db->setQuery($query)->execute();

		// Return the right value of this node + 1.
		return $rightId + 1;
	}
}
