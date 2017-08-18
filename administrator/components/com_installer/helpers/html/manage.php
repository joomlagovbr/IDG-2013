<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Installer HTML class.
 *
 * @since  2.5
 */
abstract class InstallerHtmlManage
{
	/**
	 * Returns a published state on a grid.
	 *
	 * @param   integer  $value     The state value.
	 * @param   integer  $i         The row index.
	 * @param   boolean  $enabled   An optional setting for access control on the action.
	 * @param   string   $checkbox  An optional prefix for checkboxes.
	 *
	 * @return  string        The Html code
	 *
	 * @see JHtmlJGrid::state
	 *
	 * @since   2.5
	 */
	public static function state($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states = array(
			2 => array(
				'',
				'COM_INSTALLER_EXTENSION_PROTECTED',
				'',
				'COM_INSTALLER_EXTENSION_PROTECTED',
				true,
				'protected',
				'protected',
			),
			1 => array(
				'unpublish',
				'COM_INSTALLER_EXTENSION_ENABLED',
				'COM_INSTALLER_EXTENSION_DISABLE',
				'COM_INSTALLER_EXTENSION_ENABLED',
				true,
				'publish',
				'publish',
			),
			0 => array(
				'publish',
				'COM_INSTALLER_EXTENSION_DISABLED',
				'COM_INSTALLER_EXTENSION_ENABLE',
				'COM_INSTALLER_EXTENSION_DISABLED',
				true,
				'unpublish',
				'unpublish',
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'manage.', $enabled, true, $checkbox);
	}
}
