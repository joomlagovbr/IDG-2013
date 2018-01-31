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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
phocagalleryimport('phocagallery.comment.comment');
phocagalleryimport('phocagallery.comment.commentimage');

class PhocaGalleryViewCommentImgA extends JViewLegacy
{

	function display($tpl = null){
		
		if (!JRequest::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => JText::_('JINVALID_TOKEN')
			);
			echo json_encode($response);
			return;
		}
	
		$app	= JFactory::getApplication();
		$params	= $app->getParams();
		
		
		$commentValue	= $app->input->get( 'commentValue', '',  'string'  );
		$commentId 		= $app->input->get( 'commentId', 0,  'int'  );// ID of File
		$format 		= $app->input->get( 'format', '',  'string'  );
		$task 			= $app->input->get( 'task', '',  'string'  );
		$view 			= $app->input->get( 'view', '',  'string'  );
		
		
		$paramsC 		= JComponentHelper::getParams('com_phocagallery');
		$param['display_comment_img'] = $paramsC->get( 'display_comment_img', 0 );
		
		
		if ($task == 'refreshcomment' && ((int)$param['display_comment_img'] == 2 || (int)$param['display_comment_img'] == 3)) {	
		
			$user 		=& JFactory::getUser();
			//$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
			//$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		
			$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
			$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		
			
			$post['imgid'] 		= (int)$commentId;
			$post['userid']		= $user->id;
			$post['comment']	= strip_tags($commentValue);

			
			if ($format != 'json') {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_WRONG_COMMENT') ;
				$response = array(
					'status' => '0',
					'error' => $msg);
				echo json_encode($response);
				return;
			}
			
			if ((int)$post['imgid'] < 1) {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_IMAGE_NOT_EXISTS');
				$response = array(
					'status' => '0',
					'error' => $msg);
				echo json_encode($response);
				return;
			}
			
			$model = $this->getModel();

			
			$checkUserComment	= PhocaGalleryCommentImage::checkUserComment( $post['imgid'], $post['userid'] );
			
			// User has already commented this category
			if ($checkUserComment) {
				$msg = JText::_('COM_PHOCAGALLERY_COMMENT_ALREADY_SUBMITTED');
				$response = array(
					'status' => '0',
					'error' => '',
					'message' => $msg);
				echo json_encode($response);
				return;
			} else {
				
				if ($access > 0 && $user->id > 0) {
					if(!$model->comment($post)) {
						$msg = JText::_('COM_PHOCAGALLERY_ERROR_COMMENTING_IMAGE');
						$response = array(
						'status' => '0',
						'error' => $msg);
						echo json_encode($response);
						return;
					} else {
						
						$o = '<div class="pg-cv-comment-img-box-item">';
						$o .= '<div class="pg-cv-comment-img-box-avatar">';
						$avatar 			= PhocaGalleryCommentImage::getUserAvatar($user->id);
						$this->tmpl['path'] = PhocaGalleryPath::getPath();
						$img = '<div style="width: 20px; height: 20px;">&nbsp;</div>';
						if (isset($avatar->avatar) && $avatar->avatar != '') {
							$pathAvatarAbs	= $this->tmpl['path']->avatar_abs  .'thumbs'.DS.'phoca_thumb_s_'. $avatar->avatar;
							$pathAvatarRel	= $this->tmpl['path']->avatar_rel . 'thumbs/phoca_thumb_s_'. $avatar->avatar;
							if (JFile::exists($pathAvatarAbs)){
								$avSize = getimagesize($pathAvatarAbs);
								$avRatio = $avSize[0]/$avSize[1];
								$avHeight = 20;
								$avWidth = 20 * $avRatio;
								$img = '<img src="'.JURI::base().'/'.$pathAvatarRel.'" width="'.$avWidth.'" height="'.$avHeight.'" alt="" />';
							}
						}
						$o .= $img;
						$o .= '</div>';
						$o .= '<div class="pg-cv-comment-img-box-comment">'.$user->name.': '.$post['comment'].'</div>';
						$o .= '<div style="clear:both"></div>';
						$o .= '</div>';
						
						
						$msg = $o . '<br />' . JText::_('COM_PHOCAGALLERY_SUCCESS_COMMENT_SUBMIT');
						$response = array(
						'status' => '1',
						'error' => '',
						'message' => $msg);
						echo json_encode($response);
						return;
					} 
				} else {
					$msg = JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');
						$response = array(
						'status' => '0',
						'error' => $msg);
						echo json_encode($response);
						return;
				}
			}
		} else {
			$msg = JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');
			$response = array(
			'status' => '0',
			'error' => $msg);
			echo json_encode($response);
			return;
		}
	}
}
?>