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
 * MVC View for Log
 *
 */
class AkeebaViewLog extends F0FViewHtml
{
	public function onBrowse($tpl = null)
	{
		// Add live help
		AkeebaHelperIncludes::addHelp('log');

		// Get a list of log names
		$model = $this->getModel();
		$this->logs = $model->getLogList();

		$tag = $model->getState('tag');
		if(empty($tag)) $tag = null;
		$this->tag = $tag;

		// Get profile ID
		$profileid = AEPlatform::getInstance()->get_active_profile();
		$this->profileid = $profileid;

		// Get profile name
		$pmodel = F0FModel::getAnInstance('Profiles', 'AkeebaModel');
		$pmodel->setId($profileid);
		$profile_data = $pmodel->getItem();
		$this->profilename = $profile_data->description;

		return true;
	}

	public function onIframe($tpl = null)
	{
		$model = $this->getModel();
		$tag = $model->getState('tag');
		if(empty($tag)) $tag = null;
		$this->tag = $tag;

		return true;
	}
}