<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Installer controller for Joomla! installer class.
 *
 * @since  1.5
 */
class InstallerControllerInstall extends JControllerLegacy
{
	/**
	 * Install an extension.
	 *
	 * @return  void
	 *
	 * @since   1.5
	 */
	public function install()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel('install');

		if ($model->install())
		{
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();

			// TODO: Reset the users acl here as well to kill off any missing bits.
		}

		$app = JFactory::getApplication();
		$redirect_url = $app->getUserState('com_installer.redirect_url');

		// Don't redirect to an external URL.
		if (!JUri::isInternal($redirect_url))
		{
			$redirect_url = '';
		}

		if (empty($redirect_url))
		{
			$redirect_url = JRoute::_('index.php?option=com_installer&view=install', false);
		}
		else
		{
			// Wipe out the user state when we're going to redirect.
			$app->setUserState('com_installer.redirect_url', '');
			$app->setUserState('com_installer.message', '');
			$app->setUserState('com_installer.extension_message', '');
		}

		$this->setRedirect($redirect_url);
	}
}
