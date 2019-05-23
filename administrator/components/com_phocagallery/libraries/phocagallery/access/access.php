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

class PhocaGalleryAccess
{
	/*
	 * Get info about access in only one category
	 */
	public static function getCategoryAccess($id) {
		
		$output = array();
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT c.access, c.accessuserid, c.uploaduserid, c.deleteuserid, c.userfolder' .
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $id .
				' ORDER BY c.id';
		$db->setQuery($query, 0, 1);
		$output = $db->loadObject();
		return $output;
	}
	
	
	/**
	 * Method to check if the user have access to category
	 * Display or hide the not accessible categories - subcat folder will be not displayed
	 * Check whether category access level allows access
	 *
	 * E.g.: Should the link to Subcategory or to Parentcategory be displayed
	 * E.g.: Should the delete button displayed, should be the upload button displayed
	 *
	 * @param string $params rightType: accessuserid, uploaduserid, deleteuserid - access, upload, delete right
	 * @param int $params rightUsers - All selected users which should have the "rightType" right
	 * @param int $params rightGroup - All selected Groups of users(public, registered or special ) which should have the "rT" right
	 * @param int $params userAID - Specific group of user who display the category in front (public, special, registerd)
	 * @param int $params userId - Specific id of user who display the category in front (1,2,3,...)
	 * @param int $params Additional param - e.g. $display_access_category (Should be unaccessed category displayed)
	 * @return boolean 1 or 0
	 */
	
	public static function getUserRight($rightType = 'accessuserid', $rightUsers, $rightGroup = 0, $userAID = array(), $userId = 0 , $additionalParam = 0 ) {	
		$user = JFactory::getUser();
		// we can get the variables here, not before function call
		$userAID = $user->getAuthorisedViewLevels();
		$userId = $user->get('id', 0);
		$guest = 0;
		if (isset($user->guest) && $user->guest == 1) {
			$guest = 1;
		}
		

/*		// User ACL
		$rightGroupAccess = 0;
		// User can be assigned to different groups
		foreach ($userAID as $keyUserAID => $valueUserAID) {
			if ((int)$rightGroup == (int)$valueUserAID) {
				$rightGroupAccess = 1;
				break;
			}
		}*/
		// Normally we use "registered" group
		// But if user defines own "registered" groups in registered_access_level, these need to be taken in effect too
		$nAL = self::getNeededAccessLevels();
		$rightGroupA 	= array();
		$rightGroupA[]	= (int)$rightGroup;
		if(!empty($nAL)){
			//$rightGroupA = array_merge($nAL, $rightGroupA);
		}
		
		// User ACL
		$rightGroupAccess = 0;
		// User can be assigned to different groups
		foreach ($userAID as $keyUserAID => $valueUserAID) {
			/*if ((int)$rightGroup == (int)$valueUserAID) {
				$rightGroupAccess = 1;
				break;
			}*/
			foreach($rightGroupA as $keyRightGroupA => $valueRightGroupA) {
				if ((int)$valueRightGroupA == (int)$valueUserAID) {
					$rightGroupAccess = 1;
					break 2;
				}
			}
		}
		
		
		$rightUsersIdArray = array();
		if (!empty($rightUsers) && isset($rightUsers) && $rightUsers != '') {
			$rightUsersIdArray = explode( ',', trim( $rightUsers ) );
		} else {
			$rightUsersIdArray = array();
		}


		// Access rights (Default open for all)
		// Upload and Delete rights (Default closed for all)
		switch ($rightType) {
			case 'accessuserid':
				$rightDisplay = 1;
			break;
			
			default:
				$rightDisplay = 0;
			break;
		}
	
		if ($additionalParam == 0) { // We want not to display unaccessable categories ($display_access_category)
			if ($rightGroup != 0) {
			
				if ($rightGroupAccess == 0) {
					$rightDisplay  = 0;
				} else { // Access level only for one registered user
					if (!empty($rightUsersIdArray)) {
						// Check if the user is contained in selected array
						$userIsContained = 0;
						foreach ($rightUsersIdArray as $key => $value) {
							if ($userId == $value) {
								$userIsContained = 1;// check if the user id is selected in multiple box
								
								break;// don't search again
							}
							// for access (-1 not selected - all registered, 0 all users)
							// Access is checked by group, but upload and delete not
							
							
							if ($value == -1) {
								if ($guest == 0) {
									$userIsContained = 1;// in multiple select box is selected - All registered users
								}
							
								break;// don't search again
							}
						}
						
						if ($userIsContained == 0) {
							$rightDisplay = 0;
						} else {
							if ($rightType == 'uploaduserid' || $rightType == 'deleteuserid') {
								$rightDisplay = 1;
							}
							
						}
//						else {
//							// E.g. upload right begins with 0, so we need to set it to 1
//							$rightDisplay = 1;
//						}
					} else {
						
						// Access rights (Default open for all)
						// Upload and Delete rights (Default closed for all)
						switch ($rightType) {
							case 'accessuserid':
								$rightDisplay = 1;
							break;
							
							default:
								$rightDisplay = 0;
							break;
						}
						
					}
				}	
			}
		}
		
		return $rightDisplay;
	}
	
	/**
	 * Method to display multiple select box
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $nouser Select no user
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $reg Only registered users
	 * @return array of id
	 */
	
	public static function usersList( $name, $id, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		
		$activeArray = $active;
		if ($active != '') {
			$activeArray = explode(',',$active);
		}
		
		$db		= JFactory::getDBO();
		$and 	= '';
		if ($reg) {
			// does not include registered users in the list
			$and = ' AND m.group_id != 2';
		}

		$query = 'SELECT u.id AS value, u.name AS text'
		. ' FROM #__users AS u'
		. ' JOIN #__user_usergroup_map AS m ON m.user_id = u.id'
		. ' WHERE u.block = 0'
		. $and
		. ' GROUP BY u.id, u.name'
		. ' ORDER BY '. $order;
		
		
		$db->setQuery( $query );
		if ( $nouser ) {
			
			// Access rights (Default open for all)
			// Upload and Delete rights (Default closed for all)
			
			$idInput1 	= $idInput2 = $idInput3 = $idInput4 = false;
			$idText1	= $idText2	= $idText3 	= $idText4 = false;
			
			switch ($name) {
				case 'jform[accessuserid][]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'COM_PHOCAGALLERY_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'COM_PHOCAGALLERY_NOBODY' );
				break;
				
				case 'batch[accessuserid][]':
					$idInput4 	= -3;
					$idText4	= JText::_( 'COM_PHOCAGALLERY_KEEP_ORIGINAL_ACCESS_RIGHTS_LEVELS' );
					$idInput3 	= 0;
					$idText3	= JText::_( 'COM_PHOCAGALLERY_NOT_SET' );
					$idInput1 	= -1;
					$idText1	= JText::_( 'COM_PHOCAGALLERY_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'COM_PHOCAGALLERY_NOBODY' );
				break;
				
				case 'jform[default_accessuserid][]':
					$idInput3 	= 0;
					$idText3	= JText::_( 'COM_PHOCAGALLERY_NOT_SET' );
					$idInput1 	= -1;
					$idText1	= JText::_( 'COM_PHOCAGALLERY_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'COM_PHOCAGALLERY_NOBODY' );
				break;
				
				default:
					$idInput1 	= -2;
					$idText1	= JText::_( 'COM_PHOCAGALLERY_NOBODY' );
					$idInput2 	= -1;
					$idText2	= JText::_( 'COM_PHOCAGALLERY_ALL_REGISTERED_USERS' );
				break;
			}
			
			$users = array();
			
			if ($idText4) {
				$users[] = JHTML::_('select.option',  $idInput4, '- '. $idText4 .' -' );
			}
			if ($idText3) {
				$users[] = JHTML::_('select.option',  $idInput3, '- '. $idText3 .' -' );
			}
			$users[] = JHTML::_('select.option',  $idInput1, '- '. $idText1 .' -' );
			$users[] = JHTML::_('select.option',  $idInput2, '- '. $idText2 .' -' );
			
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="4" multiple="multiple"'. $javascript, 'value', 'text', $activeArray, $id );

		return $users;
	}
	
	
	/*
	 * Get list of users to select Owner of the category
	 */
	public static function usersListOwner( $name, $id, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		
		$db		= JFactory::getDBO();
		$and 	= '';
		if ($reg) {
			// does not include registered users in the list
			$and = ' AND m.group_id != 2';
		}

		$query = 'SELECT u.id AS value, u.name AS text'
		. ' FROM #__users AS u'
		. ' JOIN #__user_usergroup_map AS m ON m.user_id = u.id'
		. ' WHERE u.block = 0'
		. $and
		. ' GROUP BY u.id, u.name'
		. ' ORDER BY '. $order;
		
		
		$db->setQuery( $query );
		if ( $nouser ) {
			
			$idInput1 	= -1;
			$idText1	= JText::_( 'COM_PHOCAGALLERY_NOBODY' );
			$users[] = JHTML::_('select.option',  -1, '- '. $idText1 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="4" '. $javascript, 'value', 'text', $active, $id );

		return $users;
	}
	
	/*
	 * Used for commenting and rating
	 */
	public static function getNeededAccessLevels() {
	
		$paramsC 				= JComponentHelper::getParams('com_phocagallery');
		$registeredAccessLevel 	= $paramsC->get( 'registered_access_level', array(2,3,4) );
		return $registeredAccessLevel;
	}
	
	/*
	 * Check if user's groups access rights (e.g. user is public, registered, special) can meet needed Levels
	 */
	
	public static function isAccess($userLevels, $neededLevels) {
		
		$rightGroupAccess = 0;
		
		// User can be assigned to different groups
		foreach($userLevels as $keyuserLevels => $valueuserLevels) {
			foreach($neededLevels as $keyneededLevels => $valueneededLevels) {
			
				if ((int)$valueneededLevels == (int)$valueuserLevels) {
					$rightGroupAccess = 1;
					break;
				}
			}
			if ($rightGroupAccess == 1) {
				break;
			}
		}
		return (boolean)$rightGroupAccess;
	}
	
	/**
	 * Method to get the array of values for one parameters saved in param array
	 * @param string $params
	 * @param string $param param: e.g. accessuserid, uploaduserid, deleteuserid, userfolder
	 * @return array of values from one param in params array which is saved in db table in 'params' column
	 */
	/*///
	function getParamsArray($params='', $param='accessuserid')  {	
		// All params from category / params for userid only
		if ($params != '') {
			$paramsArray	= trim ($params);
			$paramsArray	= explode( ',', $params );
								
			if (is_array($paramsArray))
			{
				foreach ($paramsArray as $value)
				{
					$find = '/'.$param.'=/i';
					$replace = $param.'=';
					
					$idParam = preg_match( "".$find."" , $value );
					if ($idParam) {
						$paramsId = str_replace($replace, '', $value);
						if ($paramsId != '') {
							$paramsIdArray	= trim ($paramsId);
							$paramsIdArray	= explode( ',', $paramsId );
							// Unset empty keys
							foreach ($paramsIdArray as $key2 => $value2)
							{
								if ($value2 == '') {
									unset($paramsIdArray[$key2]);
								}
							}
							
							return $paramsIdArray;
						}
					}
				}
			}
		}
		return array();
	}*/
}
?>