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
defined( '_JEXEC' ) or die( 'Restricted access' );


/*
 * These are advanced settings
 * because not all possible settings
 * can be saved in parameters (because of different limitations)
 */
class PhocaGallerySettings
{
	private static $settings = array();
	
	private function __construct(){}

	public static function getAdvancedSettings( $element = null ) {
		if( is_null( $element ) ) {
			JError::raiseWarning(500, 'Function Error: No element added');// No JText - for developers only
			return false;
		}
		if( !array_key_exists( $element, self::$settings ) ) {
			
			$params = array();
			// =============================
			$params['geozoom']			= 8;
			$params['youtubeheight']	= 360;
			$params['youtubewidth']		= 480;
			// =============================
			
			
			if (isset($params[$element])) {
				self::$settings[$element] = $params[$element];
			} else {
				self::$settings[$element] = '';
			}
		}
		
		return self::$settings[$element];
		
	}
	public final function __clone() {
		JError::raiseWarning(500, 'Function Error: Cannot clone instance of Singleton pattern');// No JText - for developers only
		return false;
	}
}
?>