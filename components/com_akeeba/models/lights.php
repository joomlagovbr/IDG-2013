<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 2.1
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaModelLights extends F0FModel
{
	/**
	 * Returns a list of all configured profiles
	 * @return unknown_type
	 */
	function &getProfiles()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true)
			->select(array(
				$db->qn('id'),
				$db->qn('description')
			))->from($db->qn('#__ak_profiles'));
		$db->setQuery($query);
		$rawList = $db->loadAssocList();

		$options = array();
		if(!is_array($rawList)) {
			return $options;
		}

		foreach($rawList as $row)
		{
			$options[] = JHTML::_('select.option', $row['id'], $row['description']);
		}

		return $options;
	}
}