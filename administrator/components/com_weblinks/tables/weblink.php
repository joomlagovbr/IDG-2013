<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\String\String;

/**
 * Weblink Table class
 *
 * @since  1.5
 */
class WeblinksTableWeblink extends JTable
{
	/**
	 * Ensure the params and metadata in json encoded in the bind method
	 *
	 * @var    array
	 * @since  3.4
	 */
	protected $_jsonEncode = array('params', 'metadata', 'images');

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 *
	 * @since   1.5
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__weblinks', 'id', $db);

		// Set the published column alias
		$this->setColumnAlias('published', 'state');

		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_weblinks.weblink'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_weblinks.weblink'));
	}

	/**
	 * Overload the store method for the Weblinks table.
	 *
	 * @param   boolean	Toggle whether null values should be updated.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$this->modified = $date->toSql();

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $user->id;
		}
		else
		{
			// New weblink. A weblink created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}

			if (empty($this->created_by))
			{
				$this->created_by = $user->id;
			}
		}

		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->getDbo()->getNullDate();
		}

		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->getDbo()->getNullDate();
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Weblink', 'WeblinksTable');

		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_WEBLINKS_ERROR_UNIQUE_ALIAS'));

			return false;
		}

		// Convert IDN urls to punycode
		$this->url = JStringPunycode::urlToPunycode($this->url);

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.5
	 */
	public function check()
	{
		if (JFilterInput::checkAttribute(array('href', $this->url)))
		{
			$this->setError(JText::_('COM_WEBLINKS_ERR_TABLES_PROVIDE_URL'));

			return false;
		}

		// check for valid name
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_WEBLINKS_ERR_TABLES_TITLE'));
			return false;
		}

		// Check for existing name
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__weblinks'))
			->where($db->quoteName('title') . ' = ' . $db->quote($this->title))
			->where($db->quoteName('catid') . ' = ' . (int) $this->catid);
		$db->setQuery($query);

		$xid = (int) $db->loadResult();

		if ($xid && $xid != (int) $this->id)
		{
			$this->setError(JText::_('COM_WEBLINKS_ERR_TABLES_NAME'));

			return false;
		}

		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		$this->alias = JApplicationHelper::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $db->getNullDate() && $this->publish_down < $this->publish_up)
		{
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		/*
		 * Clean up keywords -- eliminate extra spaces between phrases
		 * and cr (\r) and lf (\n) characters from string
		 */
		if (!empty($this->metakey))
		{
			// Array of characters to remove
			$bad_characters = array("\n", "\r", "\"", "<", ">");
			$after_clean = String::str_ireplace($bad_characters, "", $this->metakey);
			$keys = explode(',', $after_clean);
			$clean_keys = array();

			foreach ($keys as $key)
			{
				// Ignore blank keywords
				if (trim($key))
				{
					$clean_keys[] = trim($key);
				}
			}

			// Put array back together delimited by ", "
			$this->metakey = implode(", ", $clean_keys);
		}

		return true;
	}
}
