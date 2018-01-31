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
			JError::raiseWarning(500, 'Function Error: No element added');// No JText - for developers only
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
		JError::raiseWarning(500, 'Function Error: Cannot clone instance of Singleton pattern');// No JText - for developers only
		return false;
	}
}
?>