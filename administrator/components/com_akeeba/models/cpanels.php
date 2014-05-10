<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Control Panel model
 *
 */
class AkeebaModelCpanels extends F0FModel
{
	/**
	 * Get an array of icon definitions for the Control Panel
	 *
	 * @return array
	 */
	public function getIconDefinitions()
	{
		AEPlatform::getInstance()->load_version_defines();
		$core	= $this->loadIconDefinitions(JPATH_COMPONENT_ADMINISTRATOR.'/views');
		if(AKEEBA_PRO) {
			$pro	= $this->loadIconDefinitions(JPATH_COMPONENT_ADMINISTRATOR.'/plugins/views');
		} else {
			$pro = array();
		}
		$ret = array_merge_recursive($core, $pro);

		return $ret;
	}

	private function loadIconDefinitions($path)
	{
		$ret = array();

		if(!@file_exists($path.'/views.ini')) return $ret;

		$ini_data = AEUtilINI::parse_ini_file($path.'/views.ini', true);
		if(!empty($ini_data))
		{
			foreach($ini_data as $view => $def)
			{
				$task = array_key_exists('task',$def) ? $def['task'] : null;
				$ret[$def['group']][] = $this->_makeIconDefinition($def['icon'], JText::_($def['label']), $view, $task);
			}
		}

		return $ret;
	}

	/**
	 * Returns a list of available backup profiles, to be consumed by JHTML in order to build
	 * a drop-down
	 *
	 * @return array
	 */
	public function getProfilesList()
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select(array(
				$db->qn('id'),
				$db->qn('description')
			))->from($db->qn('#__ak_profiles'))
			->order($db->qn('id')." ASC");
		$db->setQuery($query);
		$rawList = $db->loadAssocList();

		$options = array();
		if(!is_array($rawList)) return $options;

		foreach($rawList as $row)
		{
			$options[] = JHTML::_('select.option', $row['id'], $row['description']);
		}

		return $options;
	}

	/**
	 * Returns the active Profile ID
	 *
	 * @return int The active profile ID
	 */
	public function getProfileID()
	{
		$session = JFactory::getSession();
		return $session->get('profile', null, 'akeeba');
	}

	/**
	 * Creates an icon definition entry
	 *
	 * @param string $iconFile The filename of the icon on the GUI button
	 * @param string $label The label below the GUI button
	 * @param string $view The view to fire up when the button is clicked
	 * @return array The icon definition array
	 */
	public function _makeIconDefinition($iconFile, $label, $view = null, $task = null )
	{
		return array(
			'icon'	=> $iconFile,
			'label'	=> $label,
			'view'	=> $view,
			'task'	=> $task
		);
	}

	/**
	 * Was the last backup a failed one? Used to apply magic settings as a means of
	 * troubleshooting.
	 *
	 * @return bool
	 */
	public function isLastBackupFailed()
	{
		// Get the last backup record ID
		$list = AEPlatform::getInstance()->get_statistics_list(array('limitstart' => 0, 'limit' => 1));
		if(empty($list)) return false;
		$id = $list[0];

		$record = AEPlatform::getInstance()->get_statistics($id);

		return ($record['status'] == 'fail');
	}

	/**
	 * Checks that the media permissions are 0755 for directories and 0644 for files
	 * and fixes them if they are incorrect.
	 *
	 * @param $force	bool	Forcibly check subresources, even if the parent has correct permissions
	 *
	 * @return bool False if we couldn't figure out what's going on
	 */
	public function fixMediaPermissions($force = false)
	{
		// Are we on Windows?
		if (function_exists('php_uname'))
		{
			$isWindows = stristr(php_uname(), 'windows');
		}
		else
		{
			$isWindows = (DIRECTORY_SEPARATOR == '\\');
		}

		// No point changing permissions on Windows, as they have ACLs
		if($isWindows) return true;

		// Check the parent permissions
		$parent = JPATH_ROOT.'/media/com_akeeba';
		$parentPerms = fileperms($parent);

		// If we can't determine the parent's permissions, bail out
		if($parentPerms === false) return false;

		// Fix the parent's permissions if required
		if(($parentPerms != 0755) && ($parentPerms != 40755)) {
			$this->chmod($parent, 0755);
		} else {
			if(!$force) return true;
		}

		// During development we use symlinks and we don't wanna see that big fat warning
		if(@is_link($parent)) return true;

		JLoader::import('joomla.filesystem.folder');

		$result = true;

		// Loop through subdirectories
		$folders = JFolder::folders($parent,'.',3,true);
		foreach($folders as $folder) {
			$perms = fileperms($folder);
			if(($perms != 0755) && ($perms != 40755)) $result &= $this->chmod($folder, 0755);
		}

		// Loop through files
		$files = JFolder::files($parent,'.',3,true);
		foreach($files as $file) {
			$perms = fileperms($file);
			if(($perms != 0644) && ($perms != 0100644)) {
				$result &= $this->chmod($file, 0644);
			}
		}

		return $result;
	}

	/**
	 * Tries to change a folder/file's permissions using direct access or FTP
	 *
	 * @param string	$path	The full path to the folder/file to chmod
	 * @param int		$mode	New permissions
	 */
	private function chmod($path, $mode)
	{
		if(is_string($mode))
		{
			$mode = octdec($mode);
			if( ($mode < 0600) || ($mode > 0777) ) $mode = 0755;
		}

		// Initialize variables
		JLoader::import('joomla.client.helper');
		$ftpOptions = JClientHelper::getCredentials('ftp');

		// Check to make sure the path valid and clean
		$path = JPath::clean($path);

		if ($ftpOptions['enabled'] == 1) {
			// Connect the FTP client
			JLoader::import('joomla.client.ftp');
			if(version_compare(JVERSION,'3.0','ge')) {
				$ftp = JClientFTP::getInstance(
					$ftpOptions['host'], $ftpOptions['port'], array(),
					$ftpOptions['user'], $ftpOptions['pass']
				);
			} else {
				$ftp = JFTP::getInstance(
					$ftpOptions['host'], $ftpOptions['port'], array(),
					$ftpOptions['user'], $ftpOptions['pass']
				);
			}
		}

		if(@chmod($path, $mode))
		{
			$ret = true;
		} elseif ($ftpOptions['enabled'] == 1) {
			// Translate path and delete
			$path = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $path), '/');
			// FTP connector throws an error
			$ret = $ftp->chmod($path, $mode);
		} else {
			$ret = false;
		}
		return $ret;
	}

	/**
	 * Checks if we should enable settings encryption and applies the change
	 */
	public function checkSettingsEncryption()
	{
		// Do we have a key file?
		JLoader::import('joomla.filesystem.file');
		$filename = JPATH_COMPONENT_ADMINISTRATOR.'/akeeba/serverkey.php';
		if(JFile::exists($filename)) {
			// We have a key file. Do we need to disable it?
			if(AEPlatform::getInstance()->get_platform_configuration_option('useencryption', -1) == 0) {
				// User asked us to disable encryption. Let's do it.
				$this->disableSettingsEncryption();
			}
		} else {
			if(!AEUtilSecuresettings::supportsEncryption()) return;
			if(AEPlatform::getInstance()->get_platform_configuration_option('useencryption', -1) != 0) {
				// User asked us to enable encryption (or he left us with the default setting!). Let's do it.
				$this->enableSettingsEncryption();
			}
		}
	}

	private function disableSettingsEncryption()
	{
		// Load the server key file if necessary
		JLoader::import('joomla.filesystem.file');
		$filename = JPATH_COMPONENT_ADMINISTRATOR.'/akeeba/serverkey.php';
		$key = AEUtilSecuresettings::getKey();

		// Loop all profiles and decrypt their settings
		$profilesModel = F0FModel::getTmpInstance('Profiles','AkeebaModel');
		$profiles = $profilesModel->getList(true);
		$db = $this->getDBO();
		foreach($profiles as $profile)
		{
			$id = $profile->id;
			$config = AEUtilSecuresettings::decryptSettings($profile->configuration, $key);
			$sql = $db->getQuery(true)
				->update($db->qn('#__ak_profiles'))
				->set($db->qn('configuration').' = '.$db->q($config))
				->where($db->qn('id').' = '.	$db->q($id));
			$db->setQuery($sql);
			$db->execute();
		}

		// Finally, remove the key file
		JFile::delete($filename);
	}

	private function enableSettingsEncryption()
	{
		$key = $this->createSettingsKey();
		if(empty($key) || ($key==false)) return;

		// Loop all profiles and encrypt their settings
		$profilesModel = F0FModel::getTmpInstance('Profiles','AkeebaModel');
		$profiles = $profilesModel->getList(true);
		$db = $this->getDBO();
		if(!empty($profiles)) foreach($profiles as $profile)
		{
			$id = $profile->id;
			$config = AEUtilSecuresettings::encryptSettings($profile->configuration, $key);
			$sql = $db->getQuery(true)
				->update($db->qn('#__ak_profiles'))
				->set($db->qn('configuration').' = '.$db->q($config))
				->where($db->qn('id').' = '.	$db->q($id));
			$db->setQuery($sql);
			$db->execute();
		}
	}

	private function createSettingsKey()
	{
		JLoader::import('joomla.filesystem.file');
		$seedA = md5( JFile::read(JPATH_ROOT.'/configuration.php') );
		$seedB = md5( microtime() );
		$seed = $seedA.$seedB;

		$md5 = md5($seed);
		for($i = 0; $i < 1000; $i++) {
			$md5 = md5( $md5 . md5(rand(0, 2147483647)) );
		}

		$key = base64_encode( $md5 );

		$filecontents = "<?php defined('AKEEBAENGINE') or die(); define('AKEEBA_SERVERKEY', '$key'); ?>";
		$filename = JPATH_COMPONENT_ADMINISTRATOR.'/akeeba/serverkey.php';

		$result = JFile::write($filename, $filecontents);

		if(!$result) {
			return false;
		} else {
			return base64_decode($key);
		}
	}

	/**
	 * Update the cached live site's URL for the front-end backup feature (altbackup.php)
	 * and the detected Joomla! libraries path
	 */
	public function updateMagicParameters()
	{
		$component = JComponentHelper::getComponent( 'com_akeeba' );
		if(is_object($component->params) && ($component->params instanceof JRegistry)) {
			$params = $component->params;
		} else {
			$params = new JParameter($component->params);
		}
		$params->set( 'siteurl', str_replace('/administrator','',JURI::base()) );
		if(defined('JPATH_LIBRARIES')) {
			$params->set('jlibrariesdir', AEUtilFilesystem::TranslateWinPath(JPATH_LIBRARIES));
		} elseif(defined("JPATH_PLATFORM")) {
			$params->set('jlibrariesdir', AEUtilFilesystem::TranslateWinPath(JPATH_PLATFORM));
		}
		$joomla16 = true;
		$params->set( 'jversion', '1.6' );
		$db = JFactory::getDBO();
		$data = $params->toString();
		$sql = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params').' = '.$db->q($data))
			->where($db->qn('element').' = '.$db->q('com_akeeba'))
			->where($db->qn('type').' = '.$db->q('component'));
		$db->setQuery($sql);
		$db->execute();
	}

	public function mustWarnAboutDownloadIDInCore()
	{
		$ret = false;
		$isPro = AKEEBA_PRO;

		if ($isPro)
		{
			return $ret;
		}

		JLoader::import('joomla.application.component.helper');
		$dlid = AEUtilComconfig::getValue('update_dlid', '');

		if (preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid))
		{
			$ret = true;
		}

		return $ret;
	}

	/**
	 * Does the user need to enter a Download ID in the component's Options page?
	 *
	 * @return bool
	 */
	public function needsDownloadID()
	{
		// Do I need a Download ID?
		$ret = true;
		$isPro = AKEEBA_PRO;

		if(!$isPro)
		{
			$ret = false;
		}
		else
		{
			JLoader::import('joomla.application.component.helper');
			$dlid = AEUtilComconfig::getValue('update_dlid', '');

			if(preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid))
			{
				$ret = false;
			}
		}

		return $ret;
	}

	/**
	 * Checks if the download ID provisioning plugin for the updates of this extension is published. If not, it will try
	 * to publish it automatically. It reports the status of the plugin as a boolean.
	 *
	 * @return  bool
	 */
	public function isUpdatePluginEnabled()
	{
		// We can't be bothered about the plugin in Joomla! 2.5.0 through 2.5.19
		if (version_compare(JVERSION, '2.5.19', 'lt'))
		{
			return true;
		}

		// We can't be bothered about the plugin in Joomla! 3.x
		if (version_compare(JVERSION, '3.0.0', 'gt'))
		{
			return true;
		}

		$db = $this->getDBO();

		// Let's get the information of the update plugin
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where($db->qn('folder').' = '.$db->quote('installer'))
			->where($db->qn('element').' = '.$db->quote('akeebabackup'))
			->where($db->qn('type').' = '.$db->quote('plugin'))
			->order($db->qn('ordering').' ASC');
		$db->setQuery($query);
		$plugin = $db->loadObject();

		// If the plugin is missing report it as unpublished (of course!)
		if (!is_object($plugin))
		{
			return false;
		}

		// If it's enabled there's nothing else to do
		if ($plugin->enabled)
		{
			return true;
		}

		// Otherwise, try to enable it and report false (so the user knows what he did wrong)
		$pluginObject = (object)array(
			'extension_id'	=> $plugin->extension_id,
			'enabled'		=> 1
		);

		try
		{
			$result = $db->updateObject('#__extensions', $pluginObject, 'extension_id');
			// Do not remove this line. We need to tell the user he's doing something wrong.
			$result = false;
		}
		catch (Exception $e)
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * Checks the database for missing / outdated tables using the $dbChecks
	 * data and runs the appropriate SQL scripts if necessary.
	 *
	 * @return AkeebaModelCpanels
	 */
	public function checkAndFixDatabase()
	{
		// Install or update database
		$dbInstaller = new F0FDatabaseInstaller(array(
			'dbinstaller_directory'	=> JPATH_ADMINISTRATOR . '/components/com_akeeba/sql/xml'
		));
		$dbInstaller->updateSchema();

		return $this;
	}
}