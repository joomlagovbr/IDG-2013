<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.3.b1
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaControllerPostsetup extends AkeebaControllerDefault
{
	public function execute($task)
	{
		if($task != 'save') {
			$task = 'browse';
		}
		parent::execute($task);
	}

	public function save()
	{
		$enableSRP = $this->input->get('srp', 0, 'bool');
		$enableAutoupdate = $this->input->get('autoupdate', 0, 'bool');
		$enableBackuponupdate = $this->input->get('backuponupdate', 0, 'bool');
		$runConfwiz = $this->input->get('confwiz', 0, 'bool');
		$angieupgrade = $this->input->get('angieupgrade', 0, 'bool');
		$acceptlicense = $this->input->get('acceptlicense', 0, 'bool');
		$acceptsupport = $this->input->get('acceptsupport', 0, 'bool');
		$acceptbackuptest = $this->input->get('acceptbackuptest', 0, 'bool');

		// SRP is only supported on MySQL databases
		if(!$this->isMySQL()) $enableSRP = false;

		$db = JFactory::getDBO();

		if($enableSRP) {
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('enabled').' = '.$db->q('1'))
				->where($db->qn('element').' = '.$db->q('srp'))
				->where($db->qn('folder').' = '.$db->q('system'));
			$db->setQuery($query);
			$db->execute();
		} else {
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('enabled').' = '.$db->q('0'))
				->where($db->qn('element').' = '.$db->q('srp'))
				->where($db->qn('folder').' = '.$db->q('system'));
			$db->setQuery($query);
			$db->execute();
		}

		if ($enableBackuponupdate)
		{
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('enabled').' = '.$db->q('1'))
				->where($db->qn('element').' = '.$db->q('backuponupdate'))
				->where($db->qn('folder').' = '.$db->q('system'));
			$db->setQuery($query);
			$db->execute();
		} else {
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('enabled').' = '.$db->q('0'))
				->where($db->qn('element').' = '.$db->q('backuponupdate'))
				->where($db->qn('folder').' = '.$db->q('system'));
			$db->setQuery($query);
			$db->execute();
		}

		$query = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('enabled').' = '.$db->q('0'))
			->where($db->qn('element').' = '.$db->q('akeebaupdatecheck'))
			->where($db->qn('folder').' = '.$db->q('system'));
		$db->setQuery($query);
		$db->execute();

		if ($angieupgrade)
		{
			$this->_angieUpgrade();
		}

		// Update last version check and minstability. DO NOT USE JCOMPONENTHELPER!
		$sql = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q('com_akeeba'));
		$db->setQuery($sql);
		$rawparams = $db->loadResult();
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			$params = new JRegistry();
			if(version_compare(JVERSION, '3.0', 'ge')) {
				$params->loadString($rawparams);
			} else {
				$params->loadJSON($rawparams);
			}
		} else {
			$params = new JParameter($rawparams);
		}

		if($acceptlicense && $acceptsupport) {
			$version = AKEEBA_VERSION;
		} else {
			$version = '0.0.0';
		}
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$params->set('lastversion', $version);
			$params->set('acceptlicense', $acceptlicense);
			$params->set('acceptsupport', $acceptsupport);
			$params->set('acceptbackuptest', $acceptbackuptest);
			$params->set('angieupgrade', ($angieupgrade ? 1 : 0));
		} else {
			$params->setValue('lastversion', $version);
			$params->setValue('acceptlicense', $acceptlicense);
			$params->setValue('acceptsupport', $acceptsupport);
			$params->setValue('acceptbackuptest', $acceptbackuptest);
			$params->setValue('angieupgrade', ($angieupgrade ? 1 : 0));
		}

		$data = $params->toString('JSON');
		$sql = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params').' = '.$db->q($data))
			->where($db->qn('element').' = '.$db->q('com_akeeba'))
			->where($db->qn('type').' = '.$db->q('component'));
		$db->setQuery($sql);
		$db->execute();

		// Even better, create the "akeeba.lastversion.php" file with this information
		$fileData = "<"."?php\ndefined('_JEXEC') or die();\ndefine('AKEEBA_LASTVERSIONCHECK','".
			$version."');";
		JLoader::import('joomla.filesystem.file');
		$fileName = JPATH_COMPONENT_ADMINISTRATOR.'/akeeba.lastversion.php';
		JFile::write($fileName, $fileData);

		// Run the configuration wizard if requested
		$message = '';
		if($runConfwiz) {
			$url = 'index.php?option=com_akeeba&view=confwiz';
		} else {
			$url = 'index.php?option=com_akeeba&view=cpanel';
		}

		if(!$acceptlicense) {
			JFactory::getApplication()->enqueueMessage(JText::_('AKEEBA_POSTSETUP_ERR_ACCEPTLICENSE'), 'error');
			$url = 'index.php?option=com_akeeba&view=postsetup';
		}
		if(!$acceptsupport) {
			JFactory::getApplication()->enqueueMessage(JText::_('AKEEBA_POSTSETUP_ERR_ACCEPTSUPPORT'), 'error');
			$url = 'index.php?option=com_akeeba&view=postsetup';
		}
		if(!$acceptbackuptest) {
			JFactory::getApplication()->enqueueMessage(JText::_('AKEEBA_POSTSETUP_ERR_ACCEPTBACKUPTEST'), 'error');
			$url = 'index.php?option=com_akeeba&view=postsetup';
		}

		JFactory::getApplication()->redirect($url);
	}

	private function isMySQL()
	{
		$db = JFactory::getDbo();
		return strtolower(substr($db->name, 0, 5)) == 'mysql';
	}

	private function _angieUpgrade()
	{
		// Get all profiles
		$model = F0FModel::getTmpInstance('Cpanels', 'AkeebaModel');
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select(array(
				$db->qn('id'),
			))->from($db->qn('#__ak_profiles'))
			->order($db->qn('id')." ASC");
		$db->setQuery($query);
		$profiles = $db->loadColumn();

		$session = JFactory::getSession();
		$oldProfile = $session->get('profile', 1, 'akeeba');

		foreach ($profiles as $profile_id)
		{
			AEFactory::nuke();
			AEPlatform::getInstance()->load_configuration($profile_id);
			$config = AEFactory::getConfiguration();
			$config->set('akeeba.advanced.embedded_installer', 'angie');
			AEPlatform::getInstance()->save_configuration($profile_id);
		}

		AEFactory::nuke();
		AEPlatform::getInstance()->load_configuration($oldProfile);
	}
}