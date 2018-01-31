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
phocagalleryimport('phocagallery.rate.rateimage');
class PhocaGalleryControllerDetail extends PhocaGalleryController
{
	
	function display() {
		if ( ! JFactory::getApplication()->input->get('view') ) {
			JRequest::setVar('view', 'detail' );
		}
		parent::display();
    }

	function rate() {
		$app	= JFactory::getApplication();
		$params			= &$app->getParams();
		$detailWindow	= $params->get( 'detail_window', 0 );
		
		$user 		=& JFactory::getUser();
		$view 		= $this->input->get( 'view', '', 'string'  );
		$imgid 		= $this->input->get( 'id', '', 'string'  );
		$catid 		= $this->input->get( 'catid', '', 'string'  );
		$rating		= $this->input->get( 'rating', '', 'string' );
		$Itemid		= $this->input->get( 'Itemid', 0, 'int');
	
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
	
		if ($detailWindow == 7) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		$post['imgid'] 		= (int)$imgid;
		$post['userid']		= $user->id;
		$post['rating']		= (int)$rating;

		$imgIdAlias 	= $imgid;
		$catIdAlias 	= $catid;		//Itemid
		if ($view != 'detail') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		$model = $this->getModel('detail');
		
		$checkUserVote	= PhocaGalleryRateImage::checkUserVote( $post['imgid'], $post['userid'] );
		
		// User has already rated this category
	
		if ($checkUserVote) {
			$msg = JText::_('COM_PHOCAGALLERY_RATING_IMAGE_ALREADY_RATED');
		} else {
			if ((int)$post['rating']  < 1 || (int)$post['rating'] > 5) {
				
				$app->redirect( JRoute::_('index.php?option=com_phocagallery', false)  );
				exit;
			}
			
			if ($access > 0 && $user->id > 0) {
				if(!$model->rate($post)) {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_RATING_IMAGE');
				} else {
				$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_RATING_IMAGE');
				} 
			} else {
				$app->redirect(JRoute::_('index.php?option=com_users&view=login', false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
				exit;
			}
		}
		// Do not display System Message in Detail Window as there are no scrollbars, so other items will be not displayed
		// we send infor about already rated via get and this get will be worked in view (detail - default.php) - vote=1
		$msg = '';
		
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$catIdAlias.'&id='.$imgIdAlias.$tmplCom.'&vote=1&Itemid='. $Itemid, false), $msg );
	}
}
?>