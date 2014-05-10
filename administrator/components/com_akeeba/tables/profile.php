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
 * The JTable child implementing #__ak_profiles data handling
 *
 */
class AkeebaTableProfile extends F0FTable
{
	public function __construct( $table, $key, &$db )
	{
		parent::__construct('#__ak_profiles', 'id', $db);
	}

	/**
	 * Validation check
	 *
	 * @return bool True if the contents are valid
	 */
	public function check()
	{
		if(!$this->description)
		{
			$this->setError(JText::_('TABLE_PROFILE_NODESCRIPTION'));
			return false;
		}

		return true;
	}

	/**
	 * onBeforeDelete event - forbids deleting the default backup profile
	 *
	 * @param int $oid The ID of the profile to delete
	 *
	 * @return boolean True if the deletion is allowed
	 */
	protected function onBeforeDelete($oid)
	{
		$result = parent::onBeforeDelete($oid);
		if($result) {
			if($oid == 1) {
				$this->setError(JText::_('TABLE_PROFILE_CANNOTDELETEDEFAULT'));
				$result = false;
			}
		}
		return $result;
	}
}