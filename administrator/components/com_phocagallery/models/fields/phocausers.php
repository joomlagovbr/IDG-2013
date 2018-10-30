<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
if (! class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}
phocagalleryimport('phocagallery.access.access');

class JFormFieldPhocaUsers extends JFormField
{
	protected $type 		= 'PhocaUsers';

	protected function getInput() {
		
		$userId	= $this->form->getValue($this->element['name']);	
	
		$owner	= ( (string)$this->element['typeowner'] ? $this->element['typeowner'] : 0 );
		if ($owner == 1) {
			return PhocaGalleryAccess::usersListOwner($this->name, $this->id, $userId, 1, NULL, 'name', 0 );
		} else {
			// Joomla! 3.1.5: $this->name.'[]'
			// Joomla! 3.2.0: $this->name
			$userIdString = $userId;
			if (is_array($userId)) {
				$userIdString = implode(',', $userId);
				
			}
			return PhocaGalleryAccess::usersList($this->name, $this->id, $userIdString, 1, NULL,'name', 0 );
		
		}
	}
}
?>