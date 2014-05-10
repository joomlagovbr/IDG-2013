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
 * Akeeba Backup Configuration view class
 *
 */
class AkeebaViewSchedule extends F0FViewHtml
{
	public function onAdd($tpl = null)
	{
		// Get profile ID
		$profileid = AEPlatform::getInstance()->get_active_profile();
		$this->profileid = $profileid;

		// Get profile name
		$profileName = F0FModel::getTmpInstance('Profiles','AkeebaModel')
			->setId($profileid)
			->getItem()
			->description;
		$this->profilename = $profileName;

		// Get the CRON paths
		$this->croninfo  = $this->getModel()->getPaths();
        $this->checkinfo = $this->getModel()->getCheckPaths();

		// Add live help
		AkeebaHelperIncludes::addHelp('schedule');
	}
}