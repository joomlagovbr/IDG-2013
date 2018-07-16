<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
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

	public static function getInstance($library = '') {
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

	public static function getLibrary( $library = '' ) {
		
		$instance 			= PhocaGalleryLibrary::getInstance($library);
		$instance->name		= $library;
		
		return $instance;
	}

	public static function setLibrary( $library = '', $value = 1 ) {
		$instance 			= PhocaGalleryLibrary::getInstance($library);
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