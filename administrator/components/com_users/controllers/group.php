<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * User view level controller class.
 *
 * @since  1.6
 */
class UsersControllerGroup extends JControllerForm
{
	/**
	 * @var	    string  The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_USERS_GROUP';

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * Overrides JControllerForm::allowSave to check the core.admin permission.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowSave($data, $key = 'id')
	{
		return (JFactory::getUser()->authorise('core.admin', $this->option) && parent::allowSave($data, $key));
	}

	/**
	 * Overrides JControllerForm::allowEdit
	 *
	 * Checks that non-Super Admins are not editing Super Admins.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check if this group is a Super Admin
		if (JAccess::checkGroup($data[$key], 'core.admin'))
		{
			// If I'm not a Super Admin, then disallow the edit.
			if (!JFactory::getUser()->authorise('core.admin'))
			{
				return false;
			}
		}

		return parent::allowEdit($data, $key);
	}
}
