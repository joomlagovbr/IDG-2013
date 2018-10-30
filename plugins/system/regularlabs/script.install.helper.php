<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class PlgSystemRegularLabsInstallerScriptHelper
{
	public $name            = '';
	public $alias           = '';
	public $extname         = '';
	public $extension_type  = '';
	public $plugin_folder   = 'system';
	public $module_position = 'status';
	public $client_id       = 1;
	public $install_type    = 'install';
	public $show_message    = true;
	public $db              = null;
	public $softbreak       = null;

	public function __construct(&$params)
	{
		$this->extname = $this->extname ?: $this->alias;
		$this->db      = JFactory::getDbo();
	}

	public function preflight($route, JAdapterInstance $adapter)
	{
		if ( ! in_array($route, ['install', 'update']))
		{
			return true;
		}

		JFactory::getLanguage()->load('plg_system_regularlabsinstaller', JPATH_PLUGINS . '/system/regularlabsinstaller');

		if ($this->show_message && $this->isInstalled())
		{
			$this->install_type = 'update';
		}

		if ($this->onBeforeInstall($route) === false)
		{
			return false;
		}

		return true;
	}

	public function postflight($route, JAdapterInstance $adapter)
	{
		$this->removeGlobalLanguageFiles();
		$this->removeUnusedLanguageFiles();

		JFactory::getLanguage()->load($this->getPrefix() . '_' . $this->extname, $this->getMainFolder());

		if ( ! in_array($route, ['install', 'update']))
		{
			return true;
		}

		$this->fixExtensionNames();
		$this->updateUpdateSites();
		$this->removeAdminCache();

		if ($this->onAfterInstall($route) === false)
		{
			return false;
		}

		if ($route == 'install')
		{
			$this->publishExtension();
		}

		if ($this->show_message)
		{
			$this->addInstalledMessage();
		}

		JFactory::getCache()->clean('com_plugins');
		JFactory::getCache()->clean('_system');

		return true;
	}

	public function isInstalled()
	{
		if ( ! is_file($this->getInstalledXMLFile()))
		{
			return false;
		}

		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('extension_id'))
			->from('#__extensions')
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote($this->extension_type))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote($this->getElementName()));
		$this->db->setQuery($query, 0, 1);
		$result = $this->db->loadResult();

		return empty($result) ? false : true;
	}

	public function getMainFolder()
	{
		switch ($this->extension_type)
		{
			case 'plugin' :
				return JPATH_PLUGINS . '/' . $this->plugin_folder . '/' . $this->extname;

			case 'component' :
				return JPATH_ADMINISTRATOR . '/components/com_' . $this->extname;

			case 'module' :
				return JPATH_ADMINISTRATOR . '/modules/mod_' . $this->extname;

			case 'library' :
				return JPATH_SITE . '/libraries/' . $this->extname;
		}
	}

	public function getInstalledXMLFile()
	{
		return $this->getXMLFile($this->getMainFolder());
	}

	public function getCurrentXMLFile()
	{
		return $this->getXMLFile(__DIR__);
	}

	public function getXMLFile($folder)
	{
		switch ($this->extension_type)
		{
			case 'module' :
				return $folder . '/mod_' . $this->extname . '.xml';

			default :
				return $folder . '/' . $this->extname . '.xml';
		}
	}

	public function uninstallExtension($extname, $type = 'plugin', $folder = 'system', $show_message = true)
	{
		if (empty($extname))
		{
			return;
		}

		$folders = [];

		switch ($type)
		{
			case 'plugin';
				$folders[] = JPATH_PLUGINS . '/' . $folder . '/' . $extname;
				break;

			case 'component':
				$folders[] = JPATH_ADMINISTRATOR . '/components/com_' . $extname;
				$folders[] = JPATH_SITE . '/components/com_' . $extname;
				break;

			case 'module':
				$folders[] = JPATH_ADMINISTRATOR . '/modules/mod_' . $extname;
				$folders[] = JPATH_SITE . '/modules/mod_' . $extname;
				break;
		}

		if ( ! $this->foldersExist($folders))
		{
			return;
		}

		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('extension_id'))
			->from('#__extensions')
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote($this->getElementName($type, $extname)))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote($type));

		if ($type == 'plugin')
		{
			$query->where($this->db->quoteName('folder') . ' = ' . $this->db->quote($folder));
		}

		$this->db->setQuery($query);
		$ids = $this->db->loadColumn();

		if (empty($ids))
		{
			foreach ($folders as $folder)
			{
				JFolder::delete($folder);
			}

			return;
		}

		$ignore_ids = JFactory::getApplication()->getUserState('rl_ignore_uninstall_ids', []);

		if (JFactory::getApplication()->input->get('option') == 'com_installer' && JFactory::getApplication()->input->get('task') == 'remove')
		{
			// Don't attempt to uninstall extensions that are already selected to get uninstalled by them selves
			$ignore_ids = array_merge($ignore_ids, JFactory::getApplication()->input->get('cid', [], 'array'));
			JFactory::getApplication()->input->set('cid', array_merge($ignore_ids, $ids));
		}

		$ids = array_diff($ids, $ignore_ids);

		if (empty($ids))
		{
			return;
		}

		$ignore_ids = array_merge($ignore_ids, $ids);
		JFactory::getApplication()->setUserState('rl_ignore_uninstall_ids', $ignore_ids);

		foreach ($ids as $id)
		{
			$tmpInstaller = new JInstaller;
			$tmpInstaller->uninstall($type, $id);
		}

		if ($show_message)
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					'COM_INSTALLER_UNINSTALL_SUCCESS',
					JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($type))
				)
			);
		}
	}

	public function foldersExist($folders = [])
	{
		foreach ($folders as $folder)
		{
			if (is_dir($folder))
			{
				return true;
			}
		}

		return false;
	}

	public function uninstallPlugin($extname, $folder = 'system', $show_message = true)
	{
		$this->uninstallExtension($extname, 'plugin', $folder, $show_message);
	}

	public function uninstallComponent($extname, $show_message = true)
	{
		$this->uninstallExtension($extname, 'component', null, $show_message);
	}

	public function uninstallModule($extname, $show_message = true)
	{
		$this->uninstallExtension($extname, 'module', null, $show_message);
	}

	public function publishExtension()
	{
		switch ($this->extension_type)
		{
			case 'plugin' :
				$this->publishPlugin();

			case 'module' :
				$this->publishModule();
		}
	}

	public function publishPlugin()
	{
		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set($this->db->quoteName('enabled') . ' = 1')
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote($this->extname))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote($this->plugin_folder));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function publishModule()
	{
		// Get module id
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('id'))
			->from('#__modules')
			->where($this->db->quoteName('module') . ' = ' . $this->db->quote('mod_' . $this->extname))
			->where($this->db->quoteName('client_id') . ' = ' . (int) $this->client_id);
		$this->db->setQuery($query, 0, 1);
		$id = $this->db->loadResult();

		if ( ! $id)
		{
			return;
		}

		// check if module is already in the modules_menu table (meaning is is already saved)
		$query->clear()
			->select($this->db->quoteName('moduleid'))
			->from('#__modules_menu')
			->where($this->db->quoteName('moduleid') . ' = ' . (int) $id);
		$this->db->setQuery($query, 0, 1);
		$exists = $this->db->loadResult();

		if ($exists)
		{
			return;
		}

		// Get highest ordering number in position
		$query->clear()
			->select($this->db->quoteName('ordering'))
			->from('#__modules')
			->where($this->db->quoteName('position') . ' = ' . $this->db->quote($this->module_position))
			->where($this->db->quoteName('client_id') . ' = ' . (int) $this->client_id)
			->order('ordering DESC');
		$this->db->setQuery($query, 0, 1);
		$ordering = $this->db->loadResult();
		$ordering++;

		// publish module and set ordering number
		$query->clear()
			->update('#__modules')
			->set($this->db->quoteName('published') . ' = 1')
			->set($this->db->quoteName('ordering') . ' = ' . (int) $ordering)
			->set($this->db->quoteName('position') . ' = ' . $this->db->quote($this->module_position))
			->where($this->db->quoteName('id') . ' = ' . (int) $id);
		$this->db->setQuery($query);
		$this->db->execute();

		// add module to the modules_menu table
		$query->clear()
			->insert('#__modules_menu')
			->columns([$this->db->quoteName('moduleid'), $this->db->quoteName('menuid')])
			->values((int) $id . ', 0');
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function addInstalledMessage()
	{
		JFactory::getApplication()->enqueueMessage(
			JText::sprintf(
				JText::_($this->install_type == 'update' ? 'RLI_THE_EXTENSION_HAS_BEEN_UPDATED_SUCCESSFULLY' : 'RLI_THE_EXTENSION_HAS_BEEN_INSTALLED_SUCCESSFULLY'),
				'<strong>' . JText::_($this->name) . '</strong>',
				'<strong>' . $this->getVersion() . '</strong>',
				$this->getFullType()
			)
		);
	}

	public function getPrefix()
	{
		switch ($this->extension_type)
		{
			case 'plugin';
				return JText::_('plg_' . strtolower($this->plugin_folder));

			case 'component':
				return JText::_('com');

			case 'module':
				return JText::_('mod');

			case 'library':
				return JText::_('lib');

			default:
				return $this->extension_type;
		}
	}

	public function getElementName($type = null, $extname = null)
	{
		$type    = is_null($type) ? $this->extension_type : $type;
		$extname = is_null($extname) ? $this->extname : $extname;

		switch ($type)
		{
			case 'component' :
				return 'com_' . $extname;

			case 'module' :
				return 'mod_' . $extname;

			case 'plugin' :
			default:
				return $extname;
		}
	}

	public function getFullType()
	{
		return JText::_('RLI_' . strtoupper($this->getPrefix()));
	}

	public function getVersion($file = '')
	{
		$file = $file ?: $this->getCurrentXMLFile();

		if ( ! is_file($file))
		{
			return '';
		}

		$xml = JApplicationHelper::parseXMLInstallFile($file);

		if ( ! $xml || ! isset($xml['version']))
		{
			return '';
		}

		return $xml['version'];
	}

	public function isNewer()
	{
		if ( ! $installed_version = $this->getVersion($this->getInstalledXMLFile()))
		{
			return true;
		}

		$package_version = $this->getVersion();

		return version_compare($installed_version, $package_version, '<=');
	}

	public function canInstall()
	{
		// The extension is not installed yet
		if ( ! $installed_version = $this->getVersion($this->getInstalledXMLFile()))
		{
			return true;
		}

		// The free version is installed. So any version is ok to install
		if (strpos($installed_version, 'PRO') === false)
		{
			return true;
		}

		// Current package is a pro version, so all good
		if (strpos($this->getVersion(), 'PRO') !== false)
		{
			return true;
		}

		JFactory::getLanguage()->load($this->getPrefix() . '_' . $this->extname, __DIR__);

		JFactory::getApplication()->enqueueMessage(JText::_('RLI_ERROR_PRO_TO_FREE'), 'error');

		JFactory::getApplication()->enqueueMessage(
			html_entity_decode(
				JText::sprintf(
					'RLI_ERROR_UNINSTALL_FIRST',
					'<a href="https://www.regularlabs.com/extensions/' . $this->alias . '" target="_blank">',
					'</a>',
					JText::_($this->name)
				)
			), 'error'
		);

		return false;
	}

	/*
	 * Fixes incorrectly formed versions because of issues in old packager
	 */
	public function fixFileVersions($file)
	{
		if (is_array($file))
		{
			foreach ($file as $f)
			{
				self::fixFileVersions($f);
			}

			return;
		}

		if ( ! is_string($file) || ! is_file($file))
		{
			return;
		}

		$contents = file_get_contents($file);

		if (
			strpos($contents, 'FREEFREE') === false
			&& strpos($contents, 'FREEPRO') === false
			&& strpos($contents, 'PROFREE') === false
			&& strpos($contents, 'PROPRO') === false
		)
		{
			return;
		}

		$contents = str_replace(
			['FREEFREE', 'FREEPRO', 'PROFREE', 'PROPRO'],
			['FREE', 'PRO', 'FREE', 'PRO'],
			$contents
		);

		JFile::write($file, $contents);
	}

	public function onBeforeInstall($route)
	{
		if ( ! $this->canInstall())
		{
			return false;
		}

		return true;
	}

	public function onAfterInstall($route)
	{
	}

	public function delete($files = [])
	{
		foreach ($files as $file)
		{
			if (is_dir($file))
			{
				JFolder::delete($file);
			}

			if (is_file($file))
			{
				JFile::delete($file);
			}
		}
	}

	public function fixAssetsRules($rules = '{"core.admin":[],"core.manage":[]}')
	{
		// replace default rules value {} with the correct initial value
		$query = $this->db->getQuery(true)
			->update($this->db->quoteName('#__assets'))
			->set($this->db->quoteName('rules') . ' = ' . $this->db->quote($rules))
			->where($this->db->quoteName('title') . ' = ' . $this->db->quote('com_' . $this->extname))
			->where($this->db->quoteName('rules') . ' = ' . $this->db->quote('{}'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function fixExtensionNames()
	{
		switch ($this->extension_type)
		{
			case 'module' :
				$this->fixModuleNames();
		}
	}

	private function fixModuleNames()
	{
		// Get module id
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('id'))
			->from('#__modules')
			->where($this->db->quoteName('module') . ' = ' . $this->db->quote('mod_' . $this->extname))
			->where($this->db->quoteName('client_id') . ' = ' . (int) $this->client_id);
		$this->db->setQuery($query, 0, 1);
		$module_id = $this->db->loadResult();

		if (empty($module_id))
		{
			return;
		}

		$title = 'Regular Labs - ' . JText::_($this->name);

		$query->clear()
			->update('#__modules')
			->set($this->db->quoteName('title') . ' = ' . $this->db->quote($title))
			->where($this->db->quoteName('id') . ' = ' . (int) $module_id)
			->where($this->db->quoteName('title') . ' LIKE ' . $this->db->quote('NoNumber%'));
		$this->db->setQuery($query);
		$this->db->execute();

		// Fix module assets

		// Get asset id
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('id'))
			->from('#__assets')
			->where($this->db->quoteName('name') . ' = ' . $this->db->quote('com_modules.module.' . (int) $module_id))
			->where($this->db->quoteName('title') . ' LIKE ' . $this->db->quote('NoNumber%'));
		$this->db->setQuery($query, 0, 1);
		$asset_id = $this->db->loadResult();

		if (empty($asset_id))
		{
			return;
		}

		$query->clear()
			->update('#__assets')
			->set($this->db->quoteName('title') . ' = ' . $this->db->quote($title))
			->where($this->db->quoteName('id') . ' = ' . (int) $asset_id);
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function updateUpdateSites()
	{
		$this->removeOldUpdateSites();
		$this->updateNamesInUpdateSites();
		$this->updateHttptoHttpsInUpdateSites();
		$this->removeDuplicateUpdateSite();
		$this->updateDownloadKey();
	}

	private function removeOldUpdateSites()
	{
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('update_site_id'))
			->from('#__update_sites')
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('nonumber.nl%'))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%e=' . $this->alias . '%'));
		$this->db->setQuery($query, 0, 1);
		$id = $this->db->loadResult();

		if ( ! $id)
		{
			return;
		}

		$query->clear()
			->delete('#__update_sites')
			->where($this->db->quoteName('update_site_id') . ' = ' . (int) $id);
		$this->db->setQuery($query);
		$this->db->execute();

		$query->clear()
			->delete('#__update_sites_extensions')
			->where($this->db->quoteName('update_site_id') . ' = ' . (int) $id);
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function updateNamesInUpdateSites()
	{
		$name = JText::_($this->name);
		if ($this->alias != 'extensionmanager')
		{
			$name = 'Regular Labs - ' . $name;
		}

		$query = $this->db->getQuery(true)
			->update('#__update_sites')
			->set($this->db->quoteName('name') . ' = ' . $this->db->quote($name))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%download.regularlabs.com%'))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%e=' . $this->alias . '%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function updateHttptoHttpsInUpdateSites()
	{
		$query = $this->db->getQuery(true)
			->update('#__update_sites')
			->set($this->db->quoteName('location') . ' = REPLACE('
				. $this->db->quoteName('location') . ', '
				. $this->db->quote('http://') . ', '
				. $this->db->quote('https://')
				. ')')
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('http://download.regularlabs.com%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function removeDuplicateUpdateSite()
	{
		// First check to see if there is a pro entry

		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('update_site_id'))
			->from('#__update_sites')
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%download.regularlabs.com%'))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%e=' . $this->alias . '%'))
			->where($this->db->quoteName('location') . ' NOT LIKE ' . $this->db->quote('%pro=1%'));
		$this->db->setQuery($query, 0, 1);
		$id = $this->db->loadResult();

		// Otherwise just get the first match
		if ( ! $id)
		{
			$query->clear()
				->select($this->db->quoteName('update_site_id'))
				->from('#__update_sites')
				->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%download.regularlabs.com%'))
				->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%e=' . $this->alias . '%'));
			$this->db->setQuery($query, 0, 1);
			$id = $this->db->loadResult();

			// Remove pro=1 from the found update site
			$query->clear()
				->update('#__update_sites')
				->set($this->db->quoteName('location')
					. ' = replace(' . $this->db->quoteName('location') . ', ' . $this->db->quote('&pro=1') . ', ' . $this->db->quote('') . ')')
				->where($this->db->quoteName('update_site_id') . ' = ' . (int) $id);
			$this->db->setQuery($query);
			$this->db->execute();
		}

		if ( ! $id)
		{
			return;
		}

		$query->clear()
			->select($this->db->quoteName('update_site_id'))
			->from('#__update_sites')
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%download.regularlabs.com%'))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%e=' . $this->alias . '%'))
			->where($this->db->quoteName('update_site_id') . ' != ' . $id);
		$this->db->setQuery($query);
		$ids = $this->db->loadColumn();

		if (empty($ids))
		{
			return;
		}

		$query->clear()
			->delete('#__update_sites')
			->where($this->db->quoteName('update_site_id') . ' IN (' . implode(',', $ids) . ')');
		$this->db->setQuery($query);
		$this->db->execute();

		$query->clear()
			->delete('#__update_sites_extensions')
			->where($this->db->quoteName('update_site_id') . ' IN (' . implode(',', $ids) . ')');
		$this->db->setQuery($query);
		$this->db->execute();
	}

	// Save the download key from the Regular Labs Extension Manager config to the update sites
	private function updateDownloadKey()
	{
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('params'))
			->from('#__extensions')
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('com_regularlabsmanager'));
		$this->db->setQuery($query);
		$params = $this->db->loadResult();

		if ( ! $params)
		{
			return;
		}

		$params = json_decode($params);

		if ( ! isset($params->key))
		{
			return;
		}

		// Add the key on all regularlabs.com urls
		$query->clear()
			->update('#__update_sites')
			->set($this->db->quoteName('extra_query') . ' = ' . $this->db->quote('k=' . $params->key))
			->where($this->db->quoteName('location') . ' LIKE ' . $this->db->quote('%download.regularlabs.com%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function removeAdminCache()
	{
		$this->delete([JPATH_ADMINISTRATOR . '/cache/regularlabs']);
		$this->delete([JPATH_ADMINISTRATOR . '/cache/nonumber']);
	}

	private function removeGlobalLanguageFiles()
	{
		if ($this->extension_type == 'library')
		{
			return;
		}

		$language_files = JFolder::files(JPATH_ADMINISTRATOR . '/language', '\.' . $this->getPrefix() . '_' . $this->extname . '\.', true, true);

		// Remove override files
		foreach ($language_files as $i => $language_file)
		{
			if (strpos($language_file, '/overrides/') === false)
			{
				continue;
			}

			unset($language_files[$i]);
		}

		if (empty($language_files))
		{
			return;
		}

		JFile::delete($language_files);
	}

	private function removeUnusedLanguageFiles()
	{
		if ($this->extension_type == 'library')
		{
			return;
		}

		$installed_languages = array_merge(
			JFolder::folders(JPATH_SITE . '/language'),
			JFolder::folders(JPATH_ADMINISTRATOR . '/language')
		);

		$languages = array_diff(
			JFolder::folders(__DIR__ . '/language'),
			$installed_languages
		);

		$delete_languages = [];

		foreach ($languages as $language)
		{
			$delete_languages[] = $this->getMainFolder() . '/language/' . $language;
		}

		if (empty($delete_languages))
		{
			return;
		}

		// Remove folders
		$this->delete($delete_languages);
	}
}
