<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die();

class PhocaGalleryLibrary extends JObject
{
	var $name			= '';
	var $value			= 0;
//	var $libraries		= '';

	function __construct( $library = '' ) {
		$this->name 	= $library;
		$this->value	= 0;
//		$this->libraries= '';
	}

	function &getInstance($library = '') {
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$library])) {
			$instance 				= new PhocaGalleryLibrary();
			$instances[$library]	= &$instance;
		}
		
//		Information about all libraries
//		$this->libraries[$library] = $instances[$library];
		return $instances[$library];
	}

	function getLibrary( $library = '' ) {
		
		$instance 			= &PhocaGalleryLibrary::getInstance($library);
		$instance->name		= $library;
		
		return $instance;
	}

	function setLibrary( $library = '', $value = 1 ) {
		$instance 			= &PhocaGalleryLibrary::getInstance($library);
		$instance->name		= $library;
		$instance->value	= $value;
		return $instance;
	}
/*	
	function getLibraries() {
		return $this->libraries;
	}
*/
}
?>