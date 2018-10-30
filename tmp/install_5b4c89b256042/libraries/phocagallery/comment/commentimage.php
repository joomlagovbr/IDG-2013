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

class PhocaGalleryCommentImage
{
	public static function checkUserComment($imgid, $userid) {
		$db =JFactory::getDBO();
		$query = 'SELECT co.id AS id'
			    .' FROM #__phocagallery_img_comments AS co'
			    .' WHERE co.imgid = '. (int)$imgid 
				.' AND co.userid = '. (int)$userid
				.' ORDER BY co.id';
		$db->setQuery($query, 0, 1);
		$checkUserComment = $db->loadObject();
			
		if ($checkUserComment) {
			return true;
		}
		return false;
	}
	
	public static function displayComment($imgid) {
	
		$db =JFactory::getDBO();
		$query = 'SELECT co.id AS id, co.title AS title, co.comment AS comment, co.date AS date, u.name AS name, u.username AS username, uc.avatar AS avatar'
			    .' FROM #__phocagallery_img_comments AS co'
				.' LEFT JOIN #__users AS u ON u.id = co.userid'
				.' LEFT JOIN #__phocagallery_user AS uc ON uc.userid = u.id'
			    /*.' WHERE co.imgid = '. (int)$imgid
				.' AND co.published = 1'
				.' AND uc.published = 1'
				.' AND uc.approved = 1'*/
				
				 .' WHERE ' 
				. ' CASE WHEN avatar IS NOT NULL THEN'
				.' co.imgid = '. (int)$imgid
				.' AND co.published = 1'
				.' AND uc.published = 1'
				.' AND uc.approved = 1'
				.' ELSE'
				.' co.imgid = '. (int)$imgid
				.' AND co.published = 1'
				.' END'
				
				.' ORDER by co.ordering';
		$db->setQuery($query);
		$commentItem = $db->loadObjectList();
		
		return $commentItem;
	}
	
	public static function getUserAvatar($userId) {
		$db = JFactory::getDBO();
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
	
	public static function renderCommentImageJS() {
	
	
		// We only use refresh task (it means to get answer)
		// pgRequest uses pgRequestRefresh site
		$document	 = JFactory::getDocument();
		$url		  = 'index.php?option=com_phocagallery&view=commentimga&task=commentimg&format=json&'.JSession::getFormToken().'=1';
		$urlRefresh		= 'index.php?option=com_phocagallery&view=commentimga&task=refreshcomment&format=json&'.JSession::getFormToken().'=1';
		$imgLoadingUrl = JURI::base(). 'media/com_phocagallery/images/icon-loading3.gif';
		$imgLoadingHTML = '<img src="'.$imgLoadingUrl.'" alt="" />';
		$js  = '<script type="text/javascript">' . "\n" . '<!--' . "\n";
		//$js .= 'window.addEvent("domready",function() { 
		$js .= '
		function pgCommentImage(id, m, container) {
		
			var result 			= "pg-cv-comment-img-box-result" + id;
			
			var commentTxtArea	= "pg-cv-comments-editor-img" + id;
			var comment			= $(commentTxtArea).value;
			var pgRequest = new Request.JSON({
			url: "'.$urlRefresh.'",
			method: "post",
			
			onRequest: function(){
				$(result).set("html", "'.addslashes($imgLoadingHTML).'");
				if (m == 2) {
					var wall = new Masonry(document.getElementById(container));
				}
			  },
			
			onComplete: function(jsonObj) {
				try {
					var r = jsonObj;
				} catch(e) {
					var r = false;
				}
			
				if (r) {
					if (r.error == false) {
						$(result).set("html", jsonObj.message);
					} else {
						$(result).set("html", r.error);
					}
				} else {
					$(result).set("text", "'.JText::_('COM_PHOCAGALLERY_ERROR_REQUESTING_ITEM').'");
				}
				
				if (m == 2) {
					var wall = new Masonry(document.getElementById(container));
				}
			},
			
			onFailure: function() {
				$(result).set("text", "'.JText::_('COM_PHOCAGALLERY_ERROR_REQUESTING_ITEM').'");
				
				if (m == 2) {
					var wall = new Masonry(document.getElementById(container));
				}
			}
			
			})
	  
			pgRequest.send({
				data: {"commentId": id, "commentValue": comment, "format":"json"},
			});
  
		};';
		
		//$js .= '});';
		
		
		/*
		var resultcomment 	= "pg-cv-comment-img-box-newcomment" + id;
		// Refreshing Voting
						var pgRequestRefresh = new Request.JSON({
							url: "'.$urlRefresh.'",
							method: "post",
							
							onComplete: function(json2Obj) {
								try {
									var rr = json2Obj;
								} catch(e) {
									var rr = false;
								}
							
								if (rr) {
									$(resultcomment).set("html", json2Obj.message);
								} else {
									$(resultcomment).set("text", "'.JText::_('COM_PHOCAGALLERY_ERROR_REQUESTING_ITEM').'");
								}
							},
						
							onFailure: function() {
								$(resultcomment).set("text", "'.JText::_('COM_PHOCAGALLERY_ERROR_REQUESTING_ITEM').'");
							}
						})
				  
						pgRequestRefresh.send({
							data: {"commentId": id, "commentValue": comment, "format":"json"}
						});
						//End refreshing comments
						*/

		$js .= "\n" . '//-->' . "\n" .'</script>';
		$document->addCustomTag($js);
	
	}
}
?>