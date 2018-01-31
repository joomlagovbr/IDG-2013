<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryUser
{
	function getUserLang( $formName = 'language') {
		$user 		= &JFactory::getUser();
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$userLang	= $paramsC->get( 'user_ucp_lang', 1 );
		
		$o = array();
		
		switch ($userLang){
			case 2:
				$registry = new JRegistry;
				$registry->loadString($user->params);
				$o['lang'] 		= $registry->get('language','*');
				
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			case 3:
				$o['lang'] 		= JFactory::getLanguage()->getTag();
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			default:
			case 1:
				$o['lang'] 		= '*';
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="*" />';
			break;
		}
		return $o;
	}
	
	function getUserAvatar($userId) {
	
		$db =& JFactory::getDBO();
		
		$query = 'SELECT a.*'
		. ' FROM #__phocagallery_user AS a'
		. ' WHERE a.userid = '.(int)$userId;
		$db->setQuery( $query );
		$avatar = $db->loadObject();
		if(isset($avatar->id)) {
			return $avatar;
		}
		return false;
	}
	
}
?>