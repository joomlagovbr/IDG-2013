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
			throw new Exception('Function Error: No element added', 500);
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
		throw new Exception('Function Error: Cannot clone instance of Singleton pattern', 500);
		return false;
	}
}
?>