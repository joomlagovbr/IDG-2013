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

class PhocaGalleryCommentCategory
{
	public static function checkUserComment($catid, $userid) {
		$db =JFactory::getDBO();
		$query = 'SELECT co.id AS id'
			    .' FROM #__phocagallery_comments AS co'
			    .' WHERE co.catid = '. (int)$catid 
				.' AND co.userid = '. (int)$userid
				.' ORDER BY co.id';
		$db->setQuery($query, 0, 1);
		$checkUserComment = $db->loadObject();
			
		if ($checkUserComment) {
			return true;
		}
		return false;
	}
	
	public static function displayComment($catid) {
	
		$db =JFactory::getDBO();
		$query = 'SELECT co.id AS id, co.title AS title, co.comment AS comment, co.date AS date, u.name AS name, u.username AS username'
			    .' FROM #__phocagallery_comments AS co'
				.' LEFT JOIN #__users AS u ON u.id = co.userid '
			    .' WHERE co.catid = '. (int)$catid
				.' AND co.published = 1'
				.' ORDER by ordering';
		$db->setQuery($query);
		$commentItem = $db->loadObjectList();
			
		return $commentItem;
	}
}
?>