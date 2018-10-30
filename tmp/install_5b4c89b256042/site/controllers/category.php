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
phocagalleryimport('phocagallery.access.access');
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.file.fileupload');
phocagalleryimport('phocagallery.file.fileuploadfront');
phocagalleryimport('phocagallery.file.filefolder');
phocagalleryimport('phocagallery.rate.ratecategory');
phocagalleryimport('phocagallery.comment.comment');
phocagalleryimport('phocagallery.comment.commentcategory');
phocagalleryimport('phocagallery.upload.uploadfront');
phocagalleryimport('phocagallery.user.user');
phocagalleryimport('phocagallery.youtube.youtube');

class PhocaGalleryControllerCategory extends PhocaGalleryController
{
	
	function display($cachable = false, $urlparams = false) {
		if ( ! JFactory::getApplication()->input->get( 'view' ) ) {
			$this->input->set('view', 'category' );
		}
		parent::display($cachable, $urlparams);
    }

	function remove() {
		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$view 		= $this->input->get('view', '');
		$id 		= $this->input->get( 'id', '', 'string');
		$catid 		= $this->input->get( 'catid', '', 'string');
		$Itemid		= $this->input->get('Itemid', '');
		$limitStart	= $this->input->get('limitstart', 0);
		
		$catid		= (int)$catid;
		$id 		= (int)$id;
		
		
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
			$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}
		
		// USER RIGHT - DELETE - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaGalleryAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - 	
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false)  );
		}
		
		if ($rightDisplayDelete == 1) {
			if(!$model->delete((int)$id)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_DELETING_ITEM');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_DELETING_ITEM');
			} 
		} else {
			$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			exit;
		}

		$countItem = $model->getCountItem((int)$catid, $rightDisplayDelete);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$app->enqueueMessage($msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false));
	}

	function publish() {
		$app	= JFactory::getApplication();
	
		$user 		= JFactory::getUser();
		$view 		= $this->input->get('view', '', 'string');
		$id 		= $this->input->get( 'id', '', 'string');
		$catid 		= $this->input->get('catid', '', 'string');
		$Itemid		= $this->input->get('Itemid', '', 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$id 		= (int)$id;
		
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
		$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}
		
		// USER RIGHT - DELETE - - - - - - 
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaGalleryAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - -
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ($rightDisplayDelete == 1) {
			if(!$model->publish((int)$id, 1)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_PUBLISHING_ITEM');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_PUBLISHING_ITEM');
			} 
		} else {
			$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			exit;
		}

		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$app->enqueueMessage($msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false));
	}

	function unpublish() {
		$app	= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$view 		= $this->input->get('view', '', 'string');
		$id 		= $this->input->get( 'id', '', 'string');
		$catid 		= $this->input->get('catid', '', 'string');
		$Itemid		= $this->input->get('Itemid', '', 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$id 		= (int)$id;
		$catid		= (int)$catid;
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
			$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}

		// USER RIGHT - DELETE - - - - - - - - - - 
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayDelete = PhocaGalleryAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - 
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}

		if ($rightDisplayDelete == 1) {
			if(!$model->publish((int)$id, 0)) {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_UNPUBLISHING_ITEM');
			} else {
				$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_UNPUBLISHING_ITEM');
			}
		} else {
			$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			exit;
		}

		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$app->enqueueMessage($msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false) );
	}
	
	/*
	 * Java Upload
	 */
	 /*
	function javaupload() {			    
		$app	= JFactory::getApplication();
		JSession::checkToken( 'request' ) or jexit( 'Invalid Token' );
		$errUploadMsg	= '';
		$redirectUrl 	= '';		

		if (!$this->_realJavaUpload($errUploadMsg, $redirectUrl)	) {		
			exit( 'ERROR: '.$errUploadMsg);		
		} else {		
			exit( 'SUCCESS');
		}
	}
	
	function _realJavaUpload(&$errUploadMsg, &$redirectUrl) {		
		$app	= JFactory::getApplication();
		JSession::checkToken( 'request' ) or jexit( 'Invalid Token' );		
		foreach ($ FILES as $file => $fileArray) {
			echo('File key: '. $file . "\n");
			foreach ($fileArray as $item=>$val) {
				echo(' Data received: ' . $item.'=>'.$val . "\n");
			}
			if (!$this->_singleFileUpload($errUploadMsg, $fileArray, $redirectUrl)) {
				$errUploadMsg = JText::_($errUploadMsg);
				return false;
			}
		}
		return true;
	}
	*/
	
	function javaupload() {
	
		JSession::checkToken( 'request' ) or exit( 'ERROR: '. JTEXT::_('COM_PHOCAGALLERY_INVALID_TOKEN'));
		
		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		
		$return 	= $this->input->get('return-url', null, 'base64');
		$format 	= $this->input->get('format', 'html', 'cmd');
		$viewBack 	= $this->input->get('viewback', '', 'string');
		$tab 		= $this->input->get('tab', 0, 'int');
		$catid 		= $this->input->get('id', '', 'string');
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$catid 		= (int)$catid;
		
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		
		$model 			= $this->getModel('category');
		
		
		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -	
		// USER RIGHT - FOLDER - - - - - - - - - - - - 		
		$rightFolder = '';
		
		if (isset($catAccess->userfolder)) {
			$rightFolder = $catAccess->userfolder;
		}
		// - - - - - - - - - - - - - - - - - - - - - -
		
		if ($rightDisplayUpload == 1) {
		
			if ($rightFolder == '') {
				exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_DEFINED'));
				return false;
			}
			if (!JFolder::exists($path->image_abs . $rightFolder . DS)) {
				exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS'));
				return false;
			}
		
			// Check the size of all images by users
		/*	$maxUserImageSize 	= (int)$paramsC->get( 'user_images_max_size', 20971520 );
			$allFileSize		= PhocaGalleryFileUploadFront::getSizeAllOriginalImages($file, $this->_user->id);

			if ($maxUserImageSize > 0 && (int) $allFileSize > $maxUserImageSize) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_WARNING_USERIMAGES_TOOLARGE');	
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}*/
	
			// Sending and setting data for common realsingleupload function
			$this->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			$this->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realJavaUpload(1);
			
			if ($fileName != '') {
				// Saving file name into database with relative path
				
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerCategory::save((int)$catid, $fileName, false, $succeeded, $errUploadMsg, false)) {
					//$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_SUCCESS_FILE_UPLOAD'));
					exit( 'SUCCESS');
					return true;
				} else {
					exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'));
					return false;
				}
			}
		} else {					
			exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			return false;
		}		
	}
	
	function upload() {
	
		JSession::checkToken() or jexit( 'Invalid Token' );
		
		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();

		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		
		$return 	= $this->input->get('return-url', null, 'base64');
		$format 	= $this->input->get('format', 'html', 'cmd');
		$viewBack 	= $this->input->get('viewback', '', 'string');
		$tab 		= $this->input->get('tab', 0, 'int');
		$catid 		= $this->input->get('id', '', 'string');
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		
		$catidAlias	= $catid;// for return
		$catid 		= (int)$catid;
		// Set the limistart (TO DO)
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		
		$return			= JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='.$Itemid.$limitStartUrl, false);
		$redirectUrl 	= $return;
		$model 			= $this->getModel('category');
		
		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - -	
		// USER RIGHT - FOLDER - - - - - - - - - - - - 		
		$rightFolder = '';
		if (isset($catAccess->userfolder)) {
			$rightFolder = $catAccess->userfolder;
		}
		// - - - - - - - - - - - - - - - - - - - - - -
		
		if ($rightDisplayUpload == 1) {
		
			if ($rightFolder == '') {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_DEFINED');
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}
			if (!JFolder::exists($path->image_abs . $rightFolder . DS)) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS');
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}
		
			// Check the size of all images by users
		/*	$maxUserImageSize 	= (int)$paramsC->get( 'user_images_max_size', 20971520 );
			$allFileSize		= PhocaGalleryFileUploadFront::getSizeAllOriginalImages($file, $this->_user->id);

			if ($maxUserImageSize > 0 && (int) $allFileSize > $maxUserImageSize) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_WARNING_USERIMAGES_TOOLARGE');	
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}*/
	
			// Sending and setting data for common realsingleupload function
			$this->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			$this->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realSingleUpload(1);
			
			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerCategory::save((int)$catid, $fileName, $return, $succeeded, $errUploadMsg, false)) {
					$app->redirect($redirectUrl, $errUploadMsg);
					return true;
				} else {
					$app->redirect($redirectUrl, $errUploadMsg);
					return false;
				}
			}
		} else {			
			$errUploadMsg = JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');			
			$redirectUrl = JRoute::_('index.php?option=com_users&view=login', false);
			$app->redirect($redirectUrl, $errUploadMsg);
			return false;
		}		
	}
	
	function ytbupload() {
		
		JSession::checkToken() or jexit( 'Invalid Token' );
		
		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		
		$return 	= $this->input->get('return-url', null, 'base64');
		$format 	= $this->input->get('format', 'html', 'cmd');
		$viewBack 	= $this->input->get('viewback', '', 'string');
		$tab 		= $this->input->get('tab', 0, 'int');
		$catid 		= $this->input->get('id', '', 'string');
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid',0 , 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$catid 		= (int)$catid;
		
		if ((int)$catid < 1) {
			$app->redirect($redirectUrl, JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY'));
			return false;
		}
		
		$catidAlias	= $catid;// for return
		// Set the limistart (TO DO)
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		
		$return			= JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='.$Itemid.$limitStartUrl, false);
		$redirectUrl 	= $return;
		$model 			= $this->getModel('category');
		
		
		
		
		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		
	
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - -	
		// USER RIGHT - FOLDER - - - - - - - - - - - - 		
		$rightFolder = '';
		if (isset($catAccess->userfolder)) {
			$rightFolder = $catAccess->userfolder;
		}
		// - - - - - - - - - - - - - - - - - - - - - -
		
		
		
		if ($rightDisplayUpload == 1) {
		
			if ($rightFolder == '') {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_DEFINED');
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}
			if (!JFolder::exists($path->image_abs . $rightFolder . DS)) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS');
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}
		
	
			// Sending and setting data for common realsingleupload function
			$this->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			$this->input->set('return-url', base64_encode($return));// set return url
			//$fileName = PhocaGalleryFileUpload::realSingleUpload(2);
			

		
		
			$ytbLink	= $this->input->get( 'phocagalleryytbuploadlink', '', 'string');
			
			$errorYtbMsg	= '';
			$ytbData	= PhocaGalleryYoutube::importYtb($ytbLink, $rightFolder . '/', $errorYtbMsg);
			
			
			if ($ytbData && isset($ytbData['filename'])) {
				if(PhocaGalleryControllerCategory::save((int)$catid, $ytbData['filename'], $return, $succeeded, $errUploadMsg, false, $ytbData)) {
					$app->redirect($redirectUrl, $errUploadMsg);
					return true;
				} else {
					$app->redirect($redirectUrl, $errUploadMsg);
					return false;
				}
			} else {
				$app->redirect($redirectUrl, $errorYtbMsg);
				return false;
				
			}
			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerUser::save((int)$catid, $fileName, $return, $succeeded, $errUploadMsg, false)) {
					$app->redirect($redirectUrl, $errUploadMsg);
					return true;
				} else {
					$app->redirect($redirectUrl, $errUploadMsg);
					return false;
				}
			}
		} else {			
			$errUploadMsg = JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');			
			$app->redirect($this->_loginurl, JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			return false;
		}		
	}
	
	function multipleupload() {
	
		
		$app 	= JFactory::getApplication();
		$app->allowCache(false);
		
		// Chunk Files
		header('Content-type: text/plain; charset=UTF-8');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		
		
		
		// Invalid Token
		JSession::checkToken( 'request' ) or jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 100,
		'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
		'details' => JTEXT::_('COM_PHOCAGALLERY_INVALID_TOKEN'))));
		
		
		
		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		$return 	= $this->input->get('return-url', null, 'base64');
		$format 	= $this->input->get('format', 'html', 'cmd');
		$viewBack 	= $this->input->get('viewback', '', 'string');
		$tab 		= $this->input->get('tab', 0, 'int');
		$catid 		= $this->input->get('id', '', 'string');
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		
		$model 		= $this->getModel('category');
		$catid 		= (int)$catid;
		
		
		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		
		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}
		// - - - - - - - - - - - - - - - - - - - - - -	
		// USER RIGHT - FOLDER - - - - - - - - - - - - 		
		$rightFolder = '';
		if (isset($catAccess->userfolder)) {
			$rightFolder = $catAccess->userfolder;
		}
		// - - - - - - - - - - - - - - - - - - - - - -
		
		
		if ($rightDisplayUpload == 1) {
		
	
		
			if ($rightFolder == '') {
				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
				'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
				'details' => JTEXT::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_DEFINED'))));
				return false;
			}
			
			
			if (!JFolder::exists($path->image_abs . $rightFolder . DS)) {
				
				
				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
				'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
				'details' => JTEXT::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS'))));
				return false;
			}
		
		
			// Check the size of all images by users
		/*	$maxUserImageSize 	= (int)$paramsC->get( 'user_images_max_size', 20971520 );
			$allFileSize		= PhocaGalleryFileUploadFront::getSizeAllOriginalImages($file, $this->_user->id);

			if ($maxUserImageSize > 0 && (int) $allFileSize > $maxUserImageSize) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_WARNING_USERIMAGES_TOOLARGE');	
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}*/
	
	
			// Sending and setting data for common realsingleupload function
			$this->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			$this->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realMultipleUpload(1);
			
			
			
			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerCategory::save((int)$catid, $fileName, false, $succeeded, $errUploadMsg, false)) {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'OK', 'code' => 200,
					'message' => JText::_('COM_PHOCAGALERY_SUCCESS').': ',
					'details' => JTEXT::_('COM_PHOCAGALLERY_IMAGES_UPLOADED'))));
					return true;
				} else {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
					'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
					'details' => JTEXT::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'))));
					return false;
				}
			}
		} else {					
			jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
			'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
			'details' => JTEXT::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'))));
			return false;
		}		
	}

	function save($catid, $filename, $return, &$succeeded, &$errSaveMsg, $redirect=true, $ytbData = array()) {
		
		$app	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		
		$post['filename']		= $filename;
		if (isset($ytbData['link'])) {
			$post['videocode']	= $ytbData['link'];
		}
		if (isset($ytbData['title'])) {
			$post['title']	= $ytbData['title'];
		} else {
			$post['title']			= $this->input->get( 'phocagalleryuploadtitle', '', 'string' );
		}
		if (isset($ytbData['desc'])) {
			$post['description']	= $ytbData['desc'];
		} else {
			$post['description']	= $this->input->get( 'phocagalleryuploaddescription', '', 'string' );
		}
		$post['catid']			= $catid;
		$post['published']		= 1;
		
		$paramsC 				= JComponentHelper::getParams('com_phocagallery') ;
		$maxUploadChar			= $paramsC->get( 'max_upload_char', 1000 );
		if (isset($ytbData['desc'])) {
		} else {
			$post['description']	= substr($post['description'], 0, (int)$maxUploadChar);
		}
		$enableUserImageApprove = (int)$paramsC->get( 'enable_userimage_approve', 0 );
		
		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];
		
		$post['userid']		= $user->id;
		
		$post['approved']			= 0;
		if ($enableUserImageApprove == 0) {
			$post['approved']	= 1;
		}
		
		
		$model = $this->getModel( 'category' );
		
		
		if ($model->store($post, $return)) {
			$succeeded = true;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_ITEM' );
		} else {
			$succeeded = false;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_ITEM' );
		}
		
		if ($redirect) {
			$app->enqueueMessage( $errSaveMsg);
			$app->redirect($return);
			exit;
		}
		
		if ($succeeded) {
			return true;
		} else {
			return false;
		}
	}
	
	
	function rate() {
		$app	= JFactory::getApplication();
		
		$user 		= JFactory::getUser();
		$view 		= $this->input->get('view', '', 'string');
		$catid 		= $this->input->get('id', '', 'string');
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$tab		= $this->input->get('tab', '', 'int');
		$catid 		= (int)$catid;
		
		
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
	
		
		$post['catid'] 	= (int)$catid;
		$post['userid']	= $user->id;
		$post['rating']	= (int)$rating;
	
		$catidAlias 	= $catid; //Itemid
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		
		$model = $this->getModel('category');
		
		$checkUserVote	= PhocaGalleryRateCategory::checkUserVote( $post['catid'], $post['userid'] );
		
		// User has already rated this category
		if ($checkUserVote) {
			$msg = JText::_('COM_PHOCAGALLERY_RATING_CATEGORY_ALREADY_RATED');
		} else {
			if ((int)$post['rating']  < 1 || (int)$post['rating'] > 5) {
				$app->redirect( JRoute::_('index.php?option=com_phocagallery', false)  );
				exit;
			}
			
			if ($access && $user->id > 0) {
				if(!$model->rate($post)) {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_RATING_CATEGORY');
				} else {
				$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_RATING_CATEGORY');
				// Features added by Bernard Gilly - alphaplug.com
				// load external plugins
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('phocagallery');
				$results = $dispatcher->trigger( 'onVoteCategory', array($catid, $rating, $user->id ) );				
				} 
			} else {
				$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
				$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
				exit;
			}
		}

		// Limit Start
		$countItem = $model->getCountItem((int)$catid);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		$app->enqueueMessage( $msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false) );
	}
	
	function comment() {
	
		JSession::checkToken() or jexit( 'Invalid Token' );
		phocagalleryimport('phocagallery.comment.comment');
		phocagalleryimport('phocagallery.comment.commentcategory');
		$app	= JFactory::getApplication();
		$user 			= JFactory::getUser();
		
		
		$catid 		= $this->input->get('catid', '', 'string');
		$post['title']	= $this->input->get('phocagallerycommentstitle', '', 'string' );
		$post['comment']= $this->input->get('phocagallerycommentseditor', '', 'string');
		$view		= $this->input->get('view', '', 'string');
		$return 	= $this->input->get('return-url', null, 'base64');
		$format 	= $this->input->get('format', 'html', 'cmd');
		$viewBack 	= $this->input->get('viewback', '', 'string');
		$tab 		= $this->input->get('tab', 0, 'int');
		
		$rating		= $this->input->get('rating', '', 'string');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);

		$paramsC 		= JComponentHelper::getParams('com_phocagallery') ;
		$maxCommentChar	= $paramsC->get( 'max_comment_char', 1000 );
		// Maximum of character, they will be saved in database
		$post['comment']	= substr($post['comment'], 0, (int)$maxCommentChar);
		
		// Close Tags
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[u]', '[/u]');
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[i]', '[/i]');
		$post['comment'] = PhocaGalleryComment::closeTags($post['comment'], '[b]', '[/b]');
		
		
		$post['catid'] 	= (int)$catid;
		$post['userid']	= $user->id;
		
		$catidAlias 	= $catid; //Itemid
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		$model = $this->getModel('category');
		
		$checkUserComment	= PhocaGalleryCommentCategory::checkUserComment( $post['catid'], $post['userid'] );
		
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
					// Features by Bernard Gilly - alphaplug.com
					// load external plugins
					$dispatcher = JDispatcher::getInstance();
					JPluginHelper::importPlugin('phocagallery');
					$results = $dispatcher->trigger( 'onCommentCategory', array($catid, $post['title'], $post['comment'], $user->id ) );					
					} 
				} else {
					$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
					$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
					exit;
				}
			}
		}
		
		// Limit Start
		$countItem = $model->getCountItem((int)$catid);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		$app->enqueueMessage( $msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false) );
	}

	function createsubcategory() {

		JSession::checkToken() or jexit( 'Invalid Token' );
		$task 						= $this->input->get( 'task', '', 'string');
		$post['title']				= $this->input->get( 'subcategoryname', '', 'string' );
		$post['description']		= $this->input->get( 'phocagallerycreatesubcatdescription', '', 'string' );
		$post['parent_id']			= $this->input->get('parentcategoryid',0, 'int' );
		
		
		$task		= $this->input->get('task', '', 'string');
		$post['title']	= $this->input->get('subcategoryname', '' , 'string');
		$post['description']= $this->input->get('phocagallerycreatesubcatdescription', '', 'string');
		$post['parent_id']= $this->input->get('parentcategoryid', 0, 'int');
		$catid		= $this->input->get('catid', '', 'string');
		$tab		= $this->input->get('tab', 0, 'int');
		$Itemid		= $this->input->get('Itemid', 0, 'int');
		
		$limitStart	= $this->input->get('limitstart', 0, 'int');
		$catid 		= (int)$catid;
		
		
		$paramsC 					= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar			= $paramsC->get( 'max_create_cat_char', 1000 );
		$enableUserSubCatApprove	= $paramsC->get( 'enable_usersubcat_approve', 0 );
		$enableDirectSubCat     	= $paramsC->get( 'enable_direct_subcat', 0 );
		$default_access 			= $paramsC->get( 'default_access', 1 );
		$default_accessuserid 		= $paramsC->get( 'default_accessuserid', '' );
		$default_accessuserid 		= implode(',', $default_accessuserid);
		$post['description']		= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 				= $post['title'];//PhocaGalleryText::getAliasName($post['title']);
		$post['aliasfolder'] 		= PhocaGalleryText::getAliasName($post['title']);
	
		$model 						= $this->getModel('user');
		$userSubCatCount			= $paramsC->get( 'user_subcat_count', 5 );
		$user 						= JFactory::getUser();
		$post['approved']			= 0;
		$id                         = $catid;
	
		
		if ($enableUserSubCatApprove == 0) {
			$post['approved']	= 1;
		}
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];
		
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		
		$app	= JFactory::getApplication();
		// USER IS NOT LOGGED
		if (!$access) {
			$app->enqueueMessage($this->_loginstr);
			$app->redirect($this->_loginurl);
			exit;
		}
		
		if ($enableDirectSubCat != 1) {
			$msg = JText::_( 'COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION' );
			$app->enqueueMessage($msg);
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$id.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false));
			return;
		}
		
		if ((int)$post['parent_id'] < 1) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_SELECTED' );
			$app->enqueueMessage($msg);
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$id.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false));
			return;
		}
		
		// $isOwnerCategory 			= $model->isOwnerCategory($this->_user->id, (int)$post['parent_id']);
		// $limitStartUrl 				= $this->getLimitStartUrl(0, 'subcat', (int)$isOwnerCategory );
		// if(!$isOwnerCategory) {
			// $msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_ASSIGNED_TO_USER' );
			// $app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false), $msg);
			// exit;
		// }
		
		$subCatCount = $model->getCountUserSubCat($user->id);
		$subCatCount = (int)$subCatCount + 1;
		if ((int)$subCatCount > (int)$userSubCatCount) {
			$msg = JText::_( 'COM_PHOCAGALLERY_MAX_SUBCAT_COUNT_REACHED' );
			$app->enqueueMessage($msg);
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$id.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false) );
			return;
		}
		
		$ownerMainCategory	= $model->getOwnerMainCategory($user->id);
		
		
		if (!$ownerMainCategory) {
			// - - - - - 
			// NEW
			// - - - - - 
			$msg = '';
			// Create an user folder on the server 
			$userFolder	= PhocaGalleryText::getAliasName($user->username) .'-'.substr($post['aliasfolder'], 0, 10) .'-'. substr(md5(uniqid(time())), 0, 4);
			$errorMsg	= '';
			$createdFolder = PhocaGalleryFileFolder::createFolder($userFolder, $errorMsg);
			if ($errorMsg != '') {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_FOLDER_CREATING'). ': ' . JText::_($errorMsg);
				$app->enqueueMessage($msg);
				$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$id.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false));
				return false;
			}
		}
		else
		{
			$userFolder	= $ownerMainCategory->userfolder;
		}

		if ($post['title'] != '') {
			
			$post['access'] 		= (int)$default_access;
			$post['accessuserid']	= $default_accessuserid;
			//$post['access'] 		= 0;
			$post['image_position']	= 'left';
			$post['published']		= 1;
			//$post['accessuserid']	= '-1';
			$post['uploaduserid']	= $user->id;
			$post['deleteuserid']	= $user->id;
			$post['userfolder']		= $userFolder;
			$post['owner_id']		= $user->id;
			
			
			$id						= $model->store($post);
			if ($id && $id > 0) {
				$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_CREATING_CATEGORY' );
			} else {
				$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_CREATING_CATEGORY' );
			}
		} else {
			$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_TITLE' );
		}	
		$app->enqueueMessage($msg);
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$id.'&Itemid='. $Itemid . $limitStartUrl, false) );
	}
}
?>