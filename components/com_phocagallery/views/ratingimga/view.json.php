<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
phocagalleryimport( 'phocagallery.rate.rateimage');

class PhocaGalleryViewRatingImgA extends JViewLegacy
{

	function display($tpl = null){
		
		if (!JSession::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => JText::_('JINVALID_TOKEN')
			);
			echo json_encode($response);
			return;
		}
	
		$app	= JFactory::getApplication();
		$params			= $app->getParams();
		
		
		$ratingVote 	= $app->input->get( 'ratingVote', 0,  'int'  );
		$ratingId 		= $app->input->get( 'ratingId', 0,  'int'  );// ID of File
		$format 		= $app->input->get( 'format', '', 'string'  );
		$task 			= $app->input->get( 'task', '',  'string'  );
		$view 			= $app->input->get( 'view', '',  'string'  );
		$small			= $app->input->get( 'small', 1,  'string'  );//small or large rating icons
		
		
		$paramsC 		= JComponentHelper::getParams('com_phocagallery');
		$param['display_rating_img'] = $paramsC->get( 'display_rating_img', 0 );
		
		// Check if rating is enabled - if not then user should not be able to rate or to see updated reating
		
		
		
		if ($task == 'refreshrate' && (int)$param['display_rating_img'] == 2) {			
			$ratingOutput 		= PhocaGalleryRateImage::renderRateImg((int)$ratingId, $param['display_rating_img'], $small, true);// ID of 
			$response = array(
					'status' => '0',
					'message' => $ratingOutput );
				echo json_encode($response);
				return;
			//return $ratingOutput;
			
		} else if ($task == 'rate') {
		
			$user 		= JFactory::getUser();
			//$view 		= J Request::get Var( 'view', '', 'get', '', J REQUEST_NOTRIM  );
			//$Itemid		= J Request::get Var( 'Itemid', 0, '', 'int');
		
			$neededAccessLevels		= PhocaGalleryAccess::getNeededAccessLevels();
			$access					= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		
			
			$post['imgid'] 	= (int)$ratingId;
			$post['userid']		= $user->id;
			$post['rating']		= (int)$ratingVote;

			
			if ($format != 'json') {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_WRONG_RATING') ;
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
			
			$checkUserVote	= PhocaGalleryRateImage::checkUserVote( $post['imgid'], $post['userid'] );
			
			// User has already rated this category
			if ($checkUserVote) {
				$msg = JText::_('COM_PHOCAGALLERY_ALREADY_RATE_IMG');
				$response = array(
					'status' => '0',
					'error' => '',
					'message' => $msg);
				echo json_encode($response);
				return;
			} else {
				if ((int)$post['rating']  < 1 || (int)$post['rating'] > 5) {
					
					$msg = JText::_('COM_PHOCAGALLERY_ERROR_WRONG_RATING');
					$response = array(
					'status' => '0',
					'error' => $msg);
					echo json_encode($response);
					return;
				}
				
				if ($access > 0 && $user->id > 0) {
					if(!$model->rate($post)) {
						$msg = JText::_('COM_PHOCAGALLERY_ERROR_RATING_IMG');
						$response = array(
						'status' => '0',
						'error' => $msg);
						echo json_encode($response);
						return;
					} else {
						$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_RATING_IMAGE');
						$msg = '';// No changing of the box, no message, only change the rating
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