<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
phocagalleryimport('phocagallery.access.access');
phocagalleryimport('phocagallery.comment.comment');
phocagalleryimport('phocagallery.comment.commentimage');
class PhocaGalleryControllerComment extends PhocaGalleryController
{
	
	function display() {
	
		if ( ! JFactory::getApplication()->input->get('view') )  {
			JRequest::setVar('view', 'comment' );
		}
		parent::display();
    }
	
	function comment() {
	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		phocagalleryimport('phocagallery.comment.comment');
		phocagalleryimport('phocagallery.comment.commentimage');
		$app				= JFactory::getApplication();
		$user 				= JFactory::getUser();
		$view 				= $this->input->get('view', '', 'string');
		$catid 				= $this->input->get('catid', '', 'string');
		$id 				= $this->input->get('id', '', 'string' );
		$post['title']		= $this->input->get('phocagallerycommentstitle', '', 'string');
		$post['comment']	= $this->input->get('phocagallerycommentseditor', '', 'string');
		$Itemid				= $this->input->get('Itemid', 0,  'int');
		$limitStart			= $this->input->get('limitstart', 0,  'int');
		$tab				= $this->input->get('tab', 0,  'int' );
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		$params				= $app->getParams();
		$detailWindow		= $params->get( 'detail_window', 0 );
		$maxCommentChar		= $params->get( 'max_comment_char', 1000 );
		$displayCommentNoPopup	= $params->get( 'display_comment_nopup', 0);
		// Maximum of character, they will be saved in database
		$post['comment']	= substr($post['comment'], 0, (int)$maxCommentChar);
		
		if ($detailWindow == 7 || $displayCommentNoPopup == 1) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		// Close Tags
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[u]', '[/u]');
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[i]', '[/i]');
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[b]', '[/b]');
		
		
		
		$post['imgid'] 	= (int)$id;
		$post['userid']	= $user->id;
		
		$catidAlias 	= $catid;
		$imgidAlias 	= $id;
		if ($view != 'comment') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		$model = $this->getModel('comment');
		
		$checkUserComment	= PhocaGalleryCommentImage::checkUserComment( $post['imgid'], $post['userid'] );
		
		// User has already submitted a comment
		if ($checkUserComment) {
			$msg = JText::_('COM_PHOCAGALLERY_COMMENT_ALREADY_SUBMITTED');
		} else {
			// If javascript will not protect the empty form
			$msg 		= '';
			$emptyForm	= 0;
			if ($post['title'] == '') {
				$msg .= JText::_('COM_PHOCAGALLERY_ERROR_COMMENT_TITLE') . ' ';
				$emtyForm = 1;
			}
			if ($post['comment'] == '') {
				$msg .= JText::_('COM_PHOCAGALLERY_ERROR_COMMENT_COMMENT');
				$emtyForm = 1;
			}
			if ($emptyForm == 0) {
				if ($access > 0 && $user->id > 0) {
					if(!$model->comment($post)) {
					$msg = JText::_('COM_PHOCAGALLERY_ERROR_COMMENT_SUBMITTING');
					} else {
					$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_COMMENT_SUBMIT');
					} 
				} else {
					$app->redirect(JRoute::_('index.php?option=com_users&view=login', false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
					exit;
				}
			}
		}
		
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=comment&catid='.$catidAlias.'&id='.$imgidAlias.$tmplCom.'&Itemid='. $Itemid, false), $msg );
	}
}
?>