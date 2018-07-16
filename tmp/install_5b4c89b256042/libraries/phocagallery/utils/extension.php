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
defined('_JEXEC') or die;
class PhocaGalleryExtension
{
	private static $extension = array();
	
	private function __construct(){}
	
	/**
	 * Get information about extension.
	 *
	 * @param	string	Extension element (com_cpanel, com_admin, ...)
	 * @param	string	Extension type (component, plugin, module, ...)
	 * @param	string	Folder type (content, editors, search, ...)
	 *
	 * @return	int ( 0 ... extension not installed
	 *                1 ... extension installed and enabled
	 *                2 ... extension installed but not enabled )
	 */

	public static function getExtensionInfo( $element = null, $type = 'component', $folder = '' ) {
		if( is_null( $element ) ) {
			throw new Exception('Function Error: No element added', 500);
			return false;
		}
		if( !array_key_exists( $element, self::$extension ) ) {
			
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			//$query->select('extension_id AS "id", element AS "element", enabled');
			if ($type == 'component'){
				$query->select('extension_id AS id, element AS "option", params, enabled');
			} else {
				$query->select('extension_id AS "id", element AS "element", params, enabled');
			}
			$query->from('#__extensions');
			$query->where('`type` = '.$db->quote($type));
			if ($folder != '') {
				$query->where('`folder` = '.$db->quote($folder));
			}
			$query->where('`element` = '.$db->quote($element));
			$db->setQuery($query);
			
			$cache 			= JFactory::getCache('_system_phocagallery','callback');
			$extensionData	=  $cache->get(array($db, 'loadObject'), null, $element, false);
			if (isset($extensionData->enabled) && $extensionData->enabled == 1) {
				self::$extension[$element] = 1;
			} else if(isset($extensionData->enabled) && $extensionData->enabled == 0) {
				self::$extension[$element] = 2;
			} else {
				self::$extension[$element] = 0;
			}
		}
		
		return self::$extension[$element];
		
	}
	public final function __clone() {
		throw new Exception('Function Error: Cannot clone instance of Singleton pattern', 500);
		return false;
	}
}
?>