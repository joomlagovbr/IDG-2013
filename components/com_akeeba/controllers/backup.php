<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 2, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

defined('AKEEBA_BACKUP_ORIGIN') or define('AKEEBA_BACKUP_ORIGIN','frontend');

class AkeebaControllerBackup extends F0FController
{
	public function __construct($config = array()) {
		$config['csrf_protection'] = false;
		parent::__construct($config);
	}

	public function execute($task) {
		if($task != 'step') {
			$task = 'browse';
		}
		parent::execute($task);
	}

	public function browse()
	{
		// Check permissions
		$this->_checkPermissions();
		// Set the profile
		$this->_setProfile();

		// Start the backup
		JLoader::import('joomla.utilities.date');
		AECoreKettenrad::reset(array(
			'maxrun'	=> 0
		));
		AEUtilTempfiles::deleteTempFiles();
		AEUtilTempvars::reset(AKEEBA_BACKUP_ORIGIN);

		$kettenrad = AECoreKettenrad::load(AKEEBA_BACKUP_ORIGIN);
		$dateNow = new JDate();
		/*
		$user = JFactory::getUser();
		$userTZ = $user->getParam('timezone',0);
		$dateNow->setOffset($userTZ);
		*/
		$description = JText::_('BACKUP_DEFAULT_DESCRIPTION').' '.$dateNow->format(JText::_('DATE_FORMAT_LC2'), true);
		$options = array(
			'description'	=> $description,
			'comment'		=> ''
		);
		$kettenrad->setup($options);
		$kettenrad->tick();
		$kettenrad->tick();
		$array = $kettenrad->getStatusArray();
		AECoreKettenrad::save(AKEEBA_BACKUP_ORIGIN);

		if($array['Error'] != '')
		{
			// An error occured
			die('500 ERROR -- '.$array['Error']);
		}
		else
		{
			$noredirect = $this->input->get('noredirect', 0, 'int');
			if($noredirect != 0)
			{
				@ob_end_clean();
				echo "301 More work required";
				flush();
				JFactory::getApplication()->close();
			}
			else
			{
				$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=backup&task=step&key='.$this->input->get('key', '', 'none', 2).'&profile='.$this->input->get('profile', 1, 'int'));
			}
		}
	}

	public function step()
	{
		// Check permissions
		$this->_checkPermissions();
		// Set the profile
		$this->_setProfile();

		$kettenrad = AECoreKettenrad::load(AKEEBA_BACKUP_ORIGIN);
		$kettenrad->tick();
		$array = $kettenrad->getStatusArray();
		$kettenrad->resetWarnings(); // So as not to have duplicate warnings reports
		AECoreKettenrad::save(AKEEBA_BACKUP_ORIGIN);

		if($array['Error'] != '')
		{
			@ob_end_clean();
			echo '500 ERROR -- '.$array['Error'];
			flush();
			JFactory::getApplication()->close();
		}
		elseif($array['HasRun'] == 1)
		{
			// All done
			AEFactory::nuke();
			AEUtilTempvars::reset();
			@ob_end_clean();
			echo '200 OK';
			flush();
			JFactory::getApplication()->close();
		}
		else
		{
			$noredirect = $this->input->get('noredirect', 0, 'int');
			if($noredirect != 0)
			{
				@ob_end_clean();
				echo "301 More work required";
				flush();
				JFactory::getApplication()->close();
			}
			else
			{
				$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=backup&task=step&key='.$this->input->get('key', '', 'none', 2).'&profile='.$this->input->get('profile', 1, 'int'));
			}
		}
	}
	/**
	 * Check that the user has sufficient permissions, or die in error
	 *
	 */
	private function _checkPermissions()
	{
		// Is frontend backup enabled?
		$febEnabled = AEPlatform::getInstance()->get_platform_configuration_option('frontend_enable', 0) != 0;
		if(!$febEnabled)
		{
			@ob_end_clean();
			echo '403 '.JText::_('ERROR_NOT_ENABLED');
			flush();
			JFactory::getApplication()->close();
		}

		// Is the key good?
		$key = $this->input->get('key', '', 'none', 2);
		$validKey=AEPlatform::getInstance()->get_platform_configuration_option('frontend_secret_word','');
		$validKeyTrim = trim($validKey);
		if( ($key != $validKey) || (empty($validKeyTrim)) )
		{
			@ob_end_clean();
			echo '403 '.JText::_('ERROR_INVALID_KEY');
			flush();
			JFactory::getApplication()->close();
		}
	}

	private function _setProfile()
	{
		// Set profile
		$profile = $this->input->get('profile', 1, 'int');
		if(!is_numeric($profile)) $profile = 1;
		$session = JFactory::getSession();
		$session->set('profile', $profile, 'akeeba');

		AEPlatform::getInstance()->load_configuration($profile);
	}
}