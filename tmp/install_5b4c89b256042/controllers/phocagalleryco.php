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
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class PhocaGalleryCpControllerPhocaGalleryCo extends JControllerForm
{
	protected	$option 		= 'com_phocagallery';
	
	function __construct($config=array()) {
		parent::__construct($config);
	}

	protected function allowAdd($data = array()) {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocagallery');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocagallery');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
}
?>
