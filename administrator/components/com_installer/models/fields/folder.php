<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('InstallerHelper', JPATH_ADMINISTRATOR . '/components/com_installer/helpers/installer.php');

JFormHelper::loadFieldClass('list');

/**
 * Folder field.
 *
 * @since  3.5
 */
class JFormFieldFolder extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.5
	 */
	protected $type = 'Folder';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.5
	 */
	public function getOptions()
	{
		$options = InstallerHelper::getExtensionGroupes();

		return array_merge(parent::getOptions(), $options);
	}
}
