<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Installer.webinstaller
 *
 * @copyright   Copyright (C) 2013 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// If the minimum PHP version constant hasn't been defined (really old Joomla version), set it now
if (!defined('JOOMLA_MINIMUM_PHP'))
{
	// Minimum as of Joomla! 3.3
	define('JOOMLA_MINIMUM_PHP', '5.3.10');
}

// Stub the JInstallerScript class for older versions to perform the minimum required checks
if (!class_exists('JInstallerScript'))
{
	/**
	 * Base install script for use by extensions providing helper methods for common behaviours.
	 *
	 * @since  3.6
	 */
	class JInstallerScript
	{
		/**
		 * Minimum PHP version required to install the extension
		 *
		 * @var    string
		 * @since  3.6
		 */
		protected $minimumPhp;

		/**
		 * Minimum Joomla! version required to install the extension
		 *
		 * @var    string
		 * @since  3.6
		 */
		protected $minimumJoomla;

		/**
		 * Function called before extension installation/update/removal procedure commences
		 *
		 * @param   string             $type    The type of change (install, update or discover_install, not uninstall)
		 * @param   JInstallerAdapter  $parent  The class calling this method
		 *
		 * @return  boolean  True on success
		 *
		 * @since   3.6
		 */
		public function preflight($type, $parent)
		{
			// Check for the minimum PHP version before continuing
			if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<'))
			{
				JLog::add(JText::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), JLog::WARNING, 'jerror');

				return false;
			}

			// Check for the minimum Joomla version before continuing
			if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<'))
			{
				JLog::add(JText::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), JLog::WARNING, 'jerror');

				return false;
			}

			// Theoretically we should not reach this line in this stub because triggering it means we aren't matching the minimum Joomla version
			return true;
		}
	}
}

/**
 * Support for the "Install from Web" tab
 *
 * @since  1.0
 */
class plginstallerwebinstallerInstallerScript extends JInstallerScript
{
	/**
	 * A list of files to be deleted
	 *
	 * @var    array
	 * @since  2.0
	 */
	protected $deleteFiles = array(
		'/plugins/installer/webinstaller/css/client.css',
		'/plugins/installer/webinstaller/css/client.min.css',
		'/plugins/installer/webinstaller/css/index.html',
		'/plugins/installer/webinstaller/index.html',
		'/plugins/installer/webinstaller/js/client.js',
		'/plugins/installer/webinstaller/js/client.min.js',
	);

	/**
	 * A list of folders to be deleted
	 *
	 * @var    array
	 * @since  2.0
	 */
	protected $deleteFolders = array(
		'/plugins/installer/webinstaller/css',
		'/plugins/installer/webinstaller/js',
	);

	/**
	 * Minimum PHP version required to install the extension
	 *
	 * @var    string
	 * @since  2.0
	 */
	protected $minimumPhp = JOOMLA_MINIMUM_PHP;

	/**
	 * Minimum Joomla! version required to install the extension
	 *
	 * @var    string
	 * @since  2.0
	 */
	protected $minimumJoomla = '3.9';

	/**
	 * Function called before extension installation/update/removal procedure commences
	 *
	 * @param   string             $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   JInstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.6
	 */
	public function preflight($type, $parent)
	{
		if (!parent::preflight($type, $parent))
		{
			return false;
		}

		// Disallow installs on 4.0 as the plugin is part of core
		if (version_compare(JVERSION, '4.0', '>='))
		{
			JLog::add(JText::_('PLG_INSTALLER_WEBINSTALLER_ERROR_PLUGIN_INCLUDED_IN_CORE'), JLog::WARNING, 'jerror');

			return false;
		}

		return true;
	}

	/**
	 * Function called after extension installation/update/removal procedure commences
	 *
	 * @param   string            $route    The action being performed
	 * @param   JInstallerPlugin  $adapter  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function postflight($route, $adapter)
	{
		// When initially installing the plugin, enable it as well
		if ($route === 'install')
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					$db->getQuery(true)
						->update($db->quoteName('#__extensions'))
						->set($db->quoteName('enabled') . ' = 1')
						->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
						->where($db->quoteName('element') . ' = ' . $db->quote('webinstaller'))
				)->execute();
			}
			catch (RuntimeException $e)
			{
				// Don't let this fatal out the install process, proceed as normal from here
			}
		}
	}
}
