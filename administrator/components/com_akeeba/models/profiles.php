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
 * The Profiles MVC model class
 *
 */
class AkeebaModelProfiles extends F0FModel
{

	public function __construct($config = array()) {
		parent::__construct($config);
		// This fixes an issue where sometimes no profiles are shown
		$this->setState('configuration', '');
		$this->setState('filter', '');
	}

	/**
	 * Returns the entry for the profile whose ID is loaded in the model
	 *
	 * @return stdClass An object representing the profile
	 */
	public function &getProfile()
	{
		return $this->getItem();
	}

	/**
	 * Gets a list of all the profiles as an array of objects
	 *
	 * @param bool $overrideLimits If set, it will list all entries, without applying limits
	 * @return array List of profiles
	 */
	public function getProfilesList($overrideLimits = false)
	{
		return $this->getItemList($overrideLimits);
	}

	/**
	 * Tries to copy the profile whose ID is set in the model to a new record
	 *
	 * @return bool True on success
	 */
	public function copy()
	{
		$id = $this->getId();

		// Check for invalid id's (not numeric, or <= 0)
		if( (!is_numeric($id)) || ($id <= 0) )
		{
			$this->setError(JText::_('PROFILE_INVALID_ID'));
			return false;
		}

		$profile = F0FModel::getTmpInstance('Profiles', 'AkeebaModel')
			->setId($id)
			->getItem()
			->getData();
		$profile['id'] = 0;
		$oProfile = $this->getTable();
		$oProfile->reset();
		$status = $oProfile->save($profile);
		if($status) {
			$this->setId($oProfile->id);
		}

		return $status;
	}

	/**
	 * Ensures that the user passed on a valid ID.
	 *
	 * @return bool True if the ID belongs to a valid profile, false otherwise
	 */
	public function checkID()
	{
		// Check for invalid id's (not numeric, or <= 0)
		if( (!is_numeric($this->_id)) || ($this->_id <= 0) ) return false;

		// Check for existing ID, or return false
		$myProfile = $this->getProfile();
		return is_object($myProfile);
	}

	public function getPostProcessingEnginePerProfile()
	{
		// Cache the current profile
		$session = JFactory::getSession();
		$currentProfileID = $session->get('profile', null, 'akeeba');

		$db = $this->getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__ak_profiles'));
		$db->setQuery($query);
		$profiles = $db->loadColumn();

		$engines = array();
		foreach($profiles as $profileID) {
			AEPlatform::getInstance()->load_configuration($profileID);
			$pConf = AEFactory::getConfiguration();
			$engines[$profileID] = $pConf->get('akeeba.advanced.proc_engine');
		}

		AEPlatform::getInstance()->load_configuration($currentProfileID);

		return $engines;
	}

}