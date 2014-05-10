<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Control Panel controller class
 *
 */
class AkeebaControllerCpanel extends F0FController
{
	public function execute($task) {
		if(!in_array($task, array('switchprofile','disablephpwarning', 'updateinfo'))) {
			$task = 'browse';
		}
		parent::execute($task);
	}

	public function onBeforeBrowse()
    {
		$result = parent::onBeforeBrowse();

		if($result)
        {
            $params = JComponentHelper::getParams('com_akeeba');
			$model  = $this->getThisModel();
			$view   = $this->getThisView();

			/** @var AkeebaModelCpanels $model */

			$view->setModel($model);

			$aeconfig = AEFactory::getConfiguration();

			// Invalidate stale backups
			AECoreKettenrad::reset( array('global' => true,'log' => false,'maxrun' => $params->get('failure_timeout', 180)) );

			// Just in case the reset() loaded a stale configuration...
			AEPlatform::getInstance()->load_configuration();

			// Let's make sure the temporary and output directories are set correctly and writable...
			$wizmodel = F0FModel::getAnInstance('Confwiz','AkeebaModel');
			$wizmodel->autofixDirectories();

			// Check if we need to toggle the settings encryption feature
			$model->checkSettingsEncryption();
			// Update the magic component parameters
			$model->updateMagicParameters();
			// Run the automatic database check
			$model->checkAndFixDatabase();

			// Run the automatic update site refresh
			/** @var AkeebaModelUpdates $updateModel */
			$updateModel = F0FModel::getTmpInstance('Updates', 'AkeebaModel');
			$updateModel->refreshUpdateSite();

			// Check the last installed version
			$versionLast = null;
			if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.'/akeeba.lastversion.php')) {
				include_once JPATH_COMPONENT_ADMINISTRATOR.'/akeeba.lastversion.php';
				if(defined('AKEEBA_LASTVERSIONCHECK')) $versionLast = AKEEBA_LASTVERSIONCHECK;
			}
			if(is_null($versionLast)) {
				$component = JComponentHelper::getComponent( 'com_akeeba' );
				if(is_object($component->params) && ($component->params instanceof JRegistry)) {
					$params = $component->params;
				} else {
					$params = new JParameter($component->params);
				}
				$versionLast = $params->get('lastversion','');
			}
			if(version_compare(AKEEBA_VERSION, $versionLast, 'ne') || empty($versionLast)) {
				$this->setRedirect('index.php?option=com_akeeba&view=postsetup');
				return true;
			}
		}
		return $result;
	}

	public function switchprofile()
	{
		// CSRF prevention
		if($this->csrfProtection) {
			$this->_csrfProtection();
		}

		$newProfile = $this->input->get('profileid', -10, 'int');

		if(!is_numeric($newProfile) || ($newProfile <= 0))
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba', JText::_('PANEL_PROFILE_SWITCH_ERROR'), 'error' );
			return true;
		}

		$session = JFactory::getSession();
		$session->set('profile', $newProfile, 'akeeba');
		$url = '';
		$returnurl = $this->input->get('returnurl', '', 'base64');
		if(!empty($returnurl)) {
			$url = base64_decode($returnurl);
		}
		if(empty($url)) {
			$url = JURI::base().'index.php?option=com_akeeba';
		}
		$this->setRedirect($url, JText::_('PANEL_PROFILE_SWITCH_OK'));
	}

	public function disablephpwarning()
	{
		// CSRF prevention
		if($this->csrfProtection) {
			$this->_csrfProtection();
		}

		// Fetch the component parameters
		$db = JFactory::getDbo();
		$sql = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q('com_akeeba'));
		$db->setQuery($sql);
		$rawparams = $db->loadResult();
		$params = new JRegistry();
		$params->loadString($rawparams, 'JSON');

		// Set the displayphpwarning parameter to 0
		$params->set('displayphpwarning', 0);

		// Save the component parameters
		$data = $params->toString('JSON');
		$sql = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set($db->qn('params').' = '.$db->q($data))
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('element').' = '.$db->q('com_akeeba'));

		$db->setQuery($sql);
		$db->execute();

		// Redirect back to the control panel
		$url = '';
		$returnurl = $this->input->get('returnurl', '', 'base64');
		if(!empty($returnurl)) {
			$url = base64_decode($returnurl);
		}
		if(empty($url)) {
			$url = JURI::base().'index.php?option=com_akeeba';
		}
		$this->setRedirect($url);
	}

	public function updateinfo()
	{
		/** @var AkeebaModelUpdates $updateModel */
		$updateModel = F0FModel::getTmpInstance('Updates', 'AkeebaModel');
		$updateInfo = (object)$updateModel->getUpdates();

		$result = '';

		if ($updateInfo->hasUpdate)
		{
			$strings = array(
				'header'		=> JText::sprintf('COM_AKEEBA_CPANEL_MSG_UPDATEFOUND', $updateInfo->version),
				'button'		=> JText::sprintf('COM_AKEEBA_CPANEL_MSG_UPDATENOW', $updateInfo->version),
				'infourl'		=> $updateInfo->infoURL,
				'infolbl'		=> JText::_('COM_AKEEBA_CPANEL_MSG_MOREINFO'),
			);

			$result = <<<ENDRESULT
	<div class="alert alert-warning">
		<h3>
			<span class="icon icon-exclamation-sign glyphicon glyphicon-exclamation-sign"></span>
			{$strings['header']}
		</h3>
		<p>
			<a href="index.php?option=com_installer&view=update" class="btn btn-primary">
				{$strings['button']}
			</a>
			<a href="{$strings['infourl']}" target="_blank" class="btn btn-small btn-info">
				{$strings['infolbl']}
			</a>
		</p>
	</div>
ENDRESULT;
		}

		echo '###' . $result . '###';

		// Cut the execution short
		JFactory::getApplication()->close();
	}
}