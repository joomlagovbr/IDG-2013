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
 * MVC View for Profiles management
 *
 */
class AkeebaViewProfiles extends F0FViewHtml
{
	function display($tpl = null)
	{
		// Load the util helper
		$this->loadHelper('utils');

		// Add a spacer, a help button and show the template
		JToolBarHelper::spacer();

		parent::display($tpl);
	}

	/**
	 * The default layout, shows a list of profiles
	 *
	 */
	function onBrowse($tpl = null)
	{
		// Get reference to profiles model
		$model = $this->getModel();

		// Get profile ID
		$profileid = AEPlatform::getInstance()->get_active_profile();
		$this->profileid = $profileid;

		// Get profile name
		$model->setId($profileid);
		$profile_data = $model->getProfile();
		$this->profilename = $profile_data->description;

		return parent::onBrowse($tpl);
	}
}