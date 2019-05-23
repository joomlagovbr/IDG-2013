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
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.file.fileupload');
phocagalleryimport('phocagallery.file.filefolder');
phocagalleryimport('phocagallery.file.filethumbnail');
phocagalleryimport('phocagallery.comment.comment');
phocagalleryimport('phocagallery.comment.commentcategory');
phocagalleryimport('phocagallery.upload.uploadfront');
phocagalleryimport('phocagallery.user.user');
phocagalleryimport('phocagallery.youtube.youtube');
class PhocaGalleryControllerUser extends PhocaGalleryController
{
	var $_user 				= null;
	var $_view 				= 'user';
	var $_tab				= 0;
	var $_limitstartsubcat 	= 0;
	var $_limitstartimage	= 0;
	var $_itemid			= 0;
	var $_loginurl;
	var $_loginstr;
	var $_url;

	function __construct() {
		parent::__construct();

		$app	= JFactory::getApplication();
		$paramsC = JComponentHelper::getParams('com_phocagallery') ;
		// UCP is disabled (security reasons)

		$enable_user_cp	= $paramsC->get( 'enable_user_cp', 0 );
		if ($enable_user_cp == 0) {
			$app->redirect( JURI::base(true), JText::_('COM_PHOCAGALLERY_UCP_DISABLED') );
			exit;
		}

		// Category
		$this->registerTask( 'createcategory', 'createcategory' );//

		// Subcategory
		$this->registerTask( 'createsubcategory', 'createsubcategory' );//
		$this->registerTask( 'editsubcategory', 'editsubcategory' );//

		$this->registerTask( 'publishsubcat', 'publishsubcat' );//
		$this->registerTask( 'unpublishsubcat', 'unpublishsubcat' );//
		$this->registerTask( 'orderupsubcat', 'ordersubcat' );//
		$this->registerTask( 'orderdownsubcat', 'ordersubcat' );//
		$this->registerTask( 'saveordersubcat', 'saveordersubcat' );//
		$this->registerTask( 'removesubcat', 'removesubcat' );//

		// Image
		$this->registerTask( 'upload', 'upload' );//
		$this->registerTask( 'javaupload', 'javaupload' );//
		$this->registerTask( 'ytbupload', 'ytbupload' );//
		$this->registerTask( 'uploadavatar', 'uploadavatar' );//
		$this->registerTask( 'editimage', 'editimage' );

		$this->registerTask( 'publishimage', 'publishimage' );//
		$this->registerTask( 'unpublishimage', 'unpublishimage' );//
		$this->registerTask( 'orderupimage', 'orderimage' );//
		$this->registerTask( 'orderdownimage', 'orderimage' );//
		$this->registerTask( 'saveorderimage', 'saveorderimage' );//
		$this->registerTask( 'removeimage', 'removeimage' );//

		// Get variables
		$this->_user 				= JFactory::getUser();
		$this->_view 				= $this->input->get( 'view', '', 'string' );
		$this->_tab 				= $this->input->get( 'tab', 0, 'int' );
		$this->_limitstartsubcat 	= $this->input->get( 'limitstartsubcat', 0, 'int' );
		$this->_limitstartimage 	= $this->input->get( 'limitstartimage', 0, 'int' );
		$this->_itemid				= $this->input->get( 'Itemid', 0, 'int' );

		$this->_loginurl			= JRoute::_('index.php?option=com_users&view=login', false);
		$this->_loginstr			= JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');
		$this->_url					= 'index.php?option=com_phocagallery&view=user&tab='.$this->_tab.'&Itemid='. $this->_itemid;

	}

	function display($cachable = false, $urlparams = Array()) {
		if ( ! JFactory::getApplication()->input->get('view') ) {
			JFactory::getApplication()->input->set('view', 'user' );
		}
		parent::display($cachable, $urlparams);
    }

	/*
	 * Handle limitstart (images/subcategories - we are in tab view so both need to be solved at once)
	 */
	function getLimitStartUrl($id = 0, $type = 'subcat', $catid = 0) {

		$model 					= $this->getModel('user');
		$limitStartUrl			= new JObject();
		$limitStartUrl->subcat	= '&limitstartsubcat='.$this->_limitstartsubcat;
		$limitStartUrl->image	= '&limitstartsubcat='.$this->_limitstartimage;

		if ((int)$id > 0 || (int)$catid > 0) {
			if ($type == 'subcat') {
				$countItem 	= $model->getCountItemSubCat((int)$id, $this->_user->id, (int)$catid);

				if ($countItem && (int)$countItem[0] == (int)$this->_limitstartsubcat) {
					$this->_limitstartsubcat = 0;
				}
			} else if ($type == 'image') {
				$countItem 	= $model->getCountItemImage((int)$id, $this->_user->id,(int)$catid);
				if ($countItem && (int)$countItem[0] == (int)$this->_limitstartimage) {
					$this->_limitstartimage = 0;
				}
			}
		}

		if ((int)$this->_limitstartsubcat > 0) {
			$limitStartUrl->subcat	= '&limitstartsubcat='.$this->_limitstartsubcat;
		} else {
			$limitStartUrl->subcat = '';
		}
		if ((int)$this->_limitstartimage > 0) {
			$limitStartUrl->image	= '&limitstartimage='.$this->_limitstartimage;
		} else {
			$limitStartUrl->image = '';
		}

		return $limitStartUrl;
	}

	// = = = = = = = = = =
	//
	// CATEGORY
	//
	// = = = = = = = = = =

	/*
	 * Create, edit
	 */
	function createcategory() {

		$app	= JFactory::getApplication();
		JSession::checkToken() or jexit( 'Invalid Token' );
		$task 						= $this->input->get( 'task', '', 'string' );
		$post['title']				= $this->input->get( 'categoryname', '', 'string'  );
		$post['description']		= $this->input->get( 'phocagallerycreatecatdescription', '', 'string'  );
		$paramsC 					= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar			= $paramsC->get( 'max_create_cat_char', 1000 );
		$enableUserCatApprove 		= (int)$paramsC->get( 'enable_usercat_approve', 0 );
		$default_access 			= $paramsC->get( 'default_access', 1 );
		$default_accessuserid 		= $paramsC->get( 'default_accessuserid', '' );
		if (is_array($default_accessuserid )) {
			$default_accessuserid 		= implode(',', $default_accessuserid);
		}
		$post['description']		= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 				= $post['title'];//PhocaGalleryText::getAliasName($post['title']);
		$post['aliasfolder'] 		= PhocaGalleryText::getAliasName($post['title']);
		$post['approved']			= 0;
		if ($enableUserCatApprove == 0) {
			$post['approved']	= 1;
		}

		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];
		/*
		switch ($this->tmpl['userucplang']){

			case 2:
				$registry = new JRegistry;
				$registry->loadString($user->params);
				$lang = $registry->get('language','*');
				$this->tmpl['userucplangvalue'] = '<input type="hidden" name="language" value="'.$lang.'" />';
			break;

			case 3:
				$lang = JFactory::getLanguage()->getTag();
				$this->tmpl['userucplangvalue'] = '<input type="hidden" name="language" value="*" />';
			break;

			default:
			case 1:
				$this->tmpl['userucplangvalue'] = '<input type="hidden" name="language" value="*" />';
			break;
		}*/

		$limitStartUrl			= new JObject();
		$limitStartUrl->subcat	= '&limitstartsubcat='.$this->_limitstartsubcat;
		$limitStartUrl->image	= '&limitstartsubcat='.$this->_limitstartimage;

		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($this->_user->getAuthorisedViewLevels(), $neededAccessLevels);

		// user is logged in
		if ($access) {
			if ($post['title'] != '') {
				$model 				= $this->getModel('user');
				// Owner can have only one main category - check it
				$ownerMainCategory	= $model->getOwnerMainCategory($this->_user->id);
				// User has no category, he (she) can create one
				if (!$ownerMainCategory) {
					// - - - - -
					// NEW
					// - - - - -
					$msg = '';
					// Create an user folder on the server
					$this->_userFolder	= PhocaGalleryText::getAliasName($this->_user->username) .'-'.substr($post['aliasfolder'], 0, 10) .'-'. substr(md5(uniqid(time())), 0, 4);
					$errorMsg	= '';
					$createdFolder = PhocaGalleryFileFolder::createFolder($this->_userFolder, $errorMsg);
					if ($errorMsg != '') {
						$msg = JText::_('COM_PHOCAGALLERY_ERROR_FOLDER_CREATING'). ': ' . JText::_($errorMsg);
					}
					// -----------------------------------




					// Folder Created, all right
					if ($msg == '') {
						// Set default values
						$post['access'] 		= (int)$default_access;
						$post['accessuserid']	= $default_accessuserid;
						//$post['access'] 		= 1;
						$post['parent_id'] 		= 0;
						$post['image_position']	= 'left';
						$post['published']		= 1;
						$post['uploaduserid']	= $this->_user->id;
						$post['deleteuserid']	= $this->_user->id;
						$post['userfolder']		= $this->_userFolder;
						$post['owner_id']		= $this->_user->id;



						// Create new category
						$id	= $model->store($post);
						if ($id && $id > 0) {
							$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_CATEGORY' );

							$errUploadMsg = '';
							$succeeded = '';
							PhocaGalleryControllerUser::saveUser('', $succeeded, $errUploadMsg);
							//$msg .= '<br />' . $errUploadMsg;

						} else {
							$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_CATEGORY' );
						}
					}
				} else {
					if ($post['title'] != '') {
						// - - - - -
						// EDIT
						// - - - - -
						$post['id']	= $ownerMainCategory->id;
						$id			= $model->store($post);
						if ($id && $id > 0) {
							$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_CATEGORY' );
						} else {
							$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_CATEGORY' );
						}
					}
				}
			} else {
				$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_TITLE' );
			}
			$app->enqueueMessage($msg );
			$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
		} else {
			$app	= JFactory::getApplication();
			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl);
			exit;
		}
	}


	// = = = = = = = = = =
	//
	// SUBCATEGORY
	//
	// = = = = = = = = = =
	function publishsubcat() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory((int)$this->_user->id, (int)$id);
		$id			 		= (int)$id;
		if ($isOwnerCategory) {
			if(!$model->publishsubcat((int)$id, 1)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_PUBLISHING_CATEGORY');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_PUBLISHING_CATEGORY');
			}
		} else {
			$app	= JFactory::getApplication();
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}

		$limitStartUrl = $this->getLimitStartUrl((int)$id, 'subcat');
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function unpublishsubcat() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory((int)$this->_user->id, (int)$id);
		$id			 		= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {
			if(!$model->publishsubcat((int)$id, 0)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_UNPUBLISHING_CATEGORY');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_UNPUBLISHING_CATEGORY');
			}
		} else {

			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}

		$limitStartUrl = $this->getLimitStartUrl((int)$id, 'subcat');
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function ordersubcat() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$task 				= $this->input->get( 'task', '', 'string' );
		$model 				= $this->getModel( 'user' );
		$isOwnerCategory 	= $model->isOwnerCategory((int)$this->_user->id, (int)$id);
		$id			 		= (int)$id;
		if ($isOwnerCategory) {
			if ($task == 'orderupsubcat') {
				$model->movesubcat(-1, (int)$id);
			} else if ($task == 'orderdownsubcat') {
				$model->movesubcat(1, (int)$id);
			}
		} else {
			$app	= JFactory::getApplication();
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}
		$limitStartUrl = $this->getLimitStartUrl((int)$id, 'subcat');
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function saveordersubcat() {
		$cid 			= $this->input->get( 'cid', array(), 'array' );
		$order 			= $this->input->get( 'order', array(), 'array' );
		$model 			= $this->getModel( 'user' );
		$app	= JFactory::getApplication();
		\Joomla\Utilities\ArrayHelper::toInteger($cid);
		\Joomla\Utilities\ArrayHelper::toInteger($order);

		$model->saveordersubcat($cid, $order);
		$msg = JText::_( 'COM_PHOCAGALLERY_NEW_ORDERING_SAVED' );

		$limitStartUrl = $this->getLimitStartUrl(0, 'subcat');
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
	}

	function removesubcat() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory((int)$this->_user->id, (int)$id);
		$isOwnerAndParentCategory 	= $model->isOwnerCategorySubCat((int)$this->_user->id, (int)$id);
		$errorMsg = '';
		$id 				= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {
			if(!$model->delete((int)$id, $errorMsg)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_DELETING_CATEGORY');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_DELETING_CATEGORY');
			}
		} else {

			$app->enqueueMessage($this->_loginstr);
			$app->redirect($this->_loginurl);
			exit;
		}

		if ($errorMsg != '') {
			$msg .= '<br />'.$errorMsg;
		}


		$limitStartUrl = $this->getLimitStartUrl(0, 'subcat', (int)$isOwnerAndParentCategory );
		$app->enqueueMessage($msg );

		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
	}

	function createsubcategory() {

		JSession::checkToken() or jexit( 'Invalid Token' );
		$task 						= $this->input->get( 'task', '', 'string' );
		$post['title']				= $this->input->get( 'subcategoryname', '', 'string'  );
		$post['description']		= $this->input->get( 'phocagallerycreatesubcatdescription', '', 'string'  );
		$post['parent_id']			= $this->input->get( 'parentcategoryid', 0, 'int' );
		$paramsC 					= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar			= $paramsC->get( 'max_create_cat_char', 1000 );
		$enableUserSubCatApprove	= $paramsC->get( 'enable_usersubcat_approve', 0 );
		$default_access 			= $paramsC->get( 'default_access', 1 );
		$default_accessuserid 		= $paramsC->get( 'default_accessuserid', '' );
		if (is_array($default_accessuserid )) {
			$default_accessuserid 		= implode(',', $default_accessuserid);
		}
		$post['description']		= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 				= $post['title'];//PhocaGalleryText::getAliasName($post['title']);
		$model 						= $this->getModel('user');
		$userSubCatCount			= $paramsC->get( 'user_subcat_count', 5 );
		$post['approved']			= 0;
		if ($enableUserSubCatApprove == 0) {
			$post['approved']	= 1;
		}

		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];

		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($this->_user->getAuthorisedViewLevels(), $neededAccessLevels);

		$app	= JFactory::getApplication();
		// USER IS NOT LOGGED
		if (!$access) {
			$app->enqueueMessage($this->_loginstr);
			$app->redirect($this->_loginurl);
			exit;
		}



		if ((int)$post['parent_id'] < 1) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_SELECTED' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		$isOwnerCategory 			= $model->isOwnerCategory($this->_user->id, (int)$post['parent_id']);
		$limitStartUrl 				= $this->getLimitStartUrl(0, 'subcat', (int)$isOwnerCategory );
		if(!$isOwnerCategory) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_ASSIGNED_TO_USER' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		$subCatCount = $model->getCountUserSubCat($this->_user->id);
		$subCatCount = (int)$subCatCount + 1;
		if ((int)$subCatCount > (int)$userSubCatCount) {
			$msg = JText::_( 'COM_PHOCAGALLERY_MAX_SUBCAT_COUNT_REACHED' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		$ownerMainCategory	= $model->getOwnerMainCategory($this->_user->id);
		if (!$ownerMainCategory) {
			$msg = JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY_NOT_CREATED');
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
		}

		if ($post['title'] != '') {

			$post['access'] 		= (int)$default_access;
			$post['accessuserid']	= $default_accessuserid;
			$post['image_position']	= 'left';
			$post['published']		= 1;
			$post['uploaduserid']	= $this->_user->id;
			$post['deleteuserid']	= $this->_user->id;
			$post['userfolder']		= $ownerMainCategory->userfolder;
			$post['owner_id']		= $this->_user->id;
			$id						= $model->store($post);
			if ($id && $id > 0) {
				$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_CREATING_CATEGORY' );
			} else {
				$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_CREATING_CATEGORY' );
			}
		} else {
			$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_TITLE' );
		}
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function editsubcategory() {

		JSession::checkToken() or jexit( 'Invalid Token' );
		$task 						= $this->input->get( 'task', '', 'string' );
		$post['title']				= $this->input->get( 'subcategoryname', '', 'string'  );
		$post['description']		= $this->input->get( 'phocagallerycreatesubcatdescription', '', 'string'  );
		//$post['parent_id']			= $this->input->get( 'parentcategoryid', 0, 'int' );
		$post['id']					= $this->input->get( 'id', 0, 'int' );
		$paramsC 					= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar			= $paramsC->get( 'max_create_cat_char', 1000 );
		$post['description']		= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 				= $post['title'];//PhocaGalleryText::getAliasName($post['title']);
		$model 						= $this->getModel('user');

		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];


		$app	= JFactory::getApplication();

		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($this->_user->getAuthorisedViewLevels(), $neededAccessLevels);

		// USER IS NOT LOGGED
		if (!$access) {
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}

		$isOwnerCategory = $model->isOwnerCategory($this->_user->id, (int)$post['id']);
		if(!$isOwnerCategory) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_ASSIGNED_TO_USER' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		if ((int)$post['id'] < 1) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_SELECTED' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		$ownerMainCategory	= $model->getOwnerMainCategory($this->_user->id);
		if (!$ownerMainCategory) {
			$msg = JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY_NOT_CREATED');
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
		}

		if ($post['title'] != '') {

			$id	= $model->store($post);
			if ($id && $id > 0) {
				$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_CATEGORY' );
			} else {
				$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_CATEGORY' );
			}
		} else {
			$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_TITLE' );
		}
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	// = = = = = = = = = =
	//
	// USER - Upload Avatar
	//
	// = = = = = = = = = =

	function uploadavatar() {
		$app	= JFactory::getApplication();
		$errUploadMsg	= '';
	    $redirectUrl 	= '';
		$fileArray 		= $app->input->files->get('Filedata', null);
		$this->_singleFileUploadAvatar($errUploadMsg, $fileArray, $redirectUrl);
		$app->redirect($redirectUrl, $errUploadMsg);
		exit;
	}

	function _singleFileUploadAvatar(&$errUploadMsg, $file, &$redirectUrl) {
		$app	= JFactory::getApplication();
		JSession::checkToken( 'request' ) or jexit( 'Invalid Token' );
		jimport('joomla.client.helper');
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$path		= PhocaGalleryPath::getPath();
		$format		= $this->input->get( 'format', 'html', 'cmd');
		$return		= $this->input->get( 'return-url', null, 'base64' );
		$viewBack	= $this->input->get( 'viewback', '', 'string' );
		$view 		= $this->input->get( 'view', '', 'string' );
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;

		$limitStartUrl 	= $this->getLimitStartUrl(0, 'subcat');
		$return			= JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false);

		$enableUploadAvatar = (int)$paramsC->get( 'enable_upload_avatar', 1 );
		if ($enableUploadAvatar != 1) {
			$errUploadMsg = JText::_('COM_PHOCAGALLERY_NOT_ABLE_UPLOAD_AVATAR');
			$redirectUrl = $return;
			return false;
		}


		if (isset($file['name'])) {
			$fileAvatar = md5(uniqid(time())) . '.' . JFile::getExt($file['name']);
			$filepath 	= JPath::clean($path->avatar_abs . '/'. $fileAvatar);

			if (!PhocaGalleryFileUpload::canUpload( $file, $errUploadMsg )) {
				if ($errUploadMsg == 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE') {
						$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.PhocaGalleryFile::getFileSizeReadable($file['size']).')';
					} else if ($errUploadMsg == 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGERESOLUTION') {
						$imgSize		= PhocaGalleryImage::getImageSize($file['tmp_name']);
						$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.(int)$imgSize[0].' x '.(int)$imgSize[1].' px)';
					} else {
						$errUploadMsg 	= JText::_($errUploadMsg);
					}
					$redirectUrl 	= $return;
					return false;
			}

			if (!JFile::upload($file['tmp_name'], $filepath, false, true)) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_FILE_UNABLE_UPLOAD');
				$redirectUrl = $return;
				return false;
			} else {
				$redirectUrl 	= $return;
				//Create thumbnail small, medium, large (Delete previous before)
				PhocaGalleryFileThumbnail::deleteFileThumbnail ('avatars/'.$fileAvatar, 1,1,1);
				$returnFrontMessage = PhocaGalleryFileThumbnail::getOrCreateThumbnail('avatars/'.$fileAvatar, $return, 1, 1, 1, 1);
				if ($returnFrontMessage != 'Success') {
					$errUploadMsg = JText::_('COM_PHOCAGALLERY_THUMBNAIL_AVATAR_NOT_CREATED');
					return false;
				}

				// Saving file name into database with relative path
				$succeeded 		= false;
				PhocaGalleryControllerUser::saveUser($fileAvatar, $succeeded, $errUploadMsg);
				$redirectUrl 	= $return;
				return $succeeded;
			}
		} else {
			$errUploadMsg = JText::_('COM_PHOCAGALLERY_WARNING_FILETYPE');
			$redirectUrl = $return;
			return false;
		}
		return false;
	}

	function saveUser($fileAvatar, &$succeeded, &$errSaveMsg) {

		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;

		$post['avatar']			= $fileAvatar;
		$post['userid']			= (int)$this->_user->id;
		$post['published']		= 1;
		$post['approved']		= 0;
		$enableAvatarApprove = (int)$paramsC->get( 'enable_avatar_approve', 0 );
		if ($enableAvatarApprove == 0) {
			$post['approved']	= 1;
		}
		if ($fileAvatar != '') {
			$new = 1;
		}

		$model = $this->getModel( 'user' );

		$userAvatar = $model->getUserAvatar($post['userid']);
		if($userAvatar) {
			$post['id']				= $userAvatar->id;
			if (isset($userAvatar->avatar) && $userAvatar->avatar != '' && $fileAvatar == '') {
				// No new avatar - set the old one
				$post['avatar']		= $userAvatar->avatar;
				$new = 0;
			} else if (isset($userAvatar->avatar) && $userAvatar->avatar != '' && $fileAvatar != '') {
				// New avatar loaded - try to delete the old one from harddisc (server)
				$model->removeAvatarFromDisc($userAvatar->avatar);
				$new = 1;
			}
			$post['published']		= $userAvatar->published;
			$post['approved']		= $userAvatar->approved;
		}

		if ($model->storeuser($post)) {
			$succeeded = true;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_AVATAR' );
			// Features added by Bernard Gilly - alphaplug.com
			// load external plugins
			if ( isset($new) && $new ){
				//$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('phocagallery');
				$results = \JFactory::getApplication()->triggerEvent('onUploadAvatar', array() );
			}
		} else {
			$succeeded = false;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_AVATAR' );
		}

		return $succeeded;
	}



	function javaupload() {

		JSession::checkToken( 'request' ) or exit( 'ERROR: '. JTEXT::_('COM_PHOCAGALLERY_INVALID_TOKEN'));

		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		//$folder		= $this->input->get( 'folder', '', '', 'path' );
		$tab		= $this->input->get( 'tab', 0, 'int' );
		$format		= $this->input->get( 'format', 'html', 'cmd');
		$return		= $this->input->get( 'return-url', null, 'base64' );
		$viewBack	= $this->input->get( 'viewback', '', 'string' );
		$catid 		= $this->input->get( 'catid', 0, 'string'  );
		$Itemid		= $this->input->get( 'Itemid', 0, 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$limitStartUrl 	= $this->getLimitStartUrl(0, 'subcat');
		$return			= JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false);
		$redirectUrl	= $return;
		$catid			= (int)$catid;
		if ((int)$catid < 1) {
			exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY'));
		}

		// Get user catid, we are not in the category, so we must find the catid
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory($this->_user->id, $catid);


		if (!$isOwnerCategory) {
			exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
		}

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
			if (!JFolder::exists($path->image_abs . $rightFolder . '/')) {
				exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS'));
				return false;
			}

			// Sending and setting data for common realsingleupload function
			JFactory::getApplication()->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			JFactory::getApplication()->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realJavaUpload(2);

			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerUser::save((int)$catid, $fileName, false, $succeeded, $errUploadMsg, false)) {
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

		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		//$folder		= $this->input->get( 'folder', '', '', 'path' );
		$tab		= $this->input->get( 'tab', 0, 'int' );
		$format		= $this->input->get( 'format', 'html', 'cmd');
		$return		= $this->input->get( 'return-url', null, 'base64' );
		$viewBack	= $this->input->get( 'viewback', '', '' );
		$catid 		= $this->input->get( 'catid', 0, 'string'  );
		$Itemid		= $this->input->get( 'Itemid', 0, 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$limitStartUrl 	= $this->getLimitStartUrl(0, 'subcat');
		$return			= JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false);
		$redirectUrl	= $return;
		$catid			= (int)$catid;
		if ((int)$catid < 1) {
			$app->redirect($redirectUrl, JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY'));
			return false;
		}

		// Get user catid, we are not in the category, so we must find the catid
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory($this->_user->id, $catid);


		if (!$isOwnerCategory) {
			$app->redirect($this->_loginurl, JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			return false;
		}


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
			if (!JFolder::exists($path->image_abs . $rightFolder . '/')) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS');
				$app->redirect($redirectUrl, $errUploadMsg);
				return false;
			}


			// Sending and setting data for common realsingleupload function
			JFactory::getApplication()->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			JFactory::getApplication()->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realSingleUpload(2);

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


	function ytbupload() {

		jimport('joomla.client.helper');
		$app		= JFactory::getApplication();
		$ftp 		= JClientHelper::setCredentialsFromRequest('ftp');
		$user 		= JFactory::getUser();
		$path		= PhocaGalleryPath::getPath();
		//$folder		= $this->input->get( 'folder', '', '', 'path' );
		$tab		= $this->input->get( 'tab', 0, 'int' );
		$format		= $this->input->get( 'format', 'html', 'cmd');
		$return		= $this->input->get( 'return-url', null, 'base64' );
		$viewBack	= $this->input->get( 'viewback', '', '' );
		$catid 		= $this->input->get( 'catid', 0, 'string'  );
		$Itemid		= $this->input->get( 'Itemid', 0, 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$limitStartUrl 	= $this->getLimitStartUrl(0, 'subcat');
		$return			= JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false);
		$redirectUrl	= $return;
		$catid			= (int)$catid;
		if ((int)$catid < 1) {
			$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY') );
			$app->redirect($redirectUrl);
			return false;
		}

		// Get user catid, we are not in the category, so we must find the catid
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory($this->_user->id, $catid);


		if (!$isOwnerCategory) {
			$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION') );
			$app->redirect($this->_loginurl);
			return false;
		}


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
				$app->enqueueMessage($errUploadMsg );
				$app->redirect($redirectUrl);
				return false;
			}
			if (!JFolder::exists($path->image_abs . $rightFolder . '/')) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS');
				$app->enqueueMessage($errUploadMsg );
				$app->redirect($redirectUrl);
				return false;
			}


			// Sending and setting data for common realsingleupload function
			JFactory::getApplication()->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			JFactory::getApplication()->input->set('return-url', base64_encode($return));// set return url
			//$fileName = PhocaGalleryFileUpload::realSingleUpload(2);


			$ytbLink	= $this->input->get( 'phocagalleryytbuploadlink', '', 'post', 'string');
			$errorYtbMsg	= '';
			$ytbData	= PhocaGalleryYoutube::importYtb($ytbLink, $rightFolder . '/', $errorYtbMsg);


			if ($ytbData && isset($ytbData['filename'])) {
				if(PhocaGalleryControllerUser::save((int)$catid, $ytbData['filename'], $return, $succeeded, $errUploadMsg, false, $ytbData)) {
					$app->enqueueMessage($errUploadMsg );
					$app->redirect($redirectUrl);
					return true;
				} else {
					$app->enqueueMessage($errUploadMsg );
					$app->redirect($redirectUrl);
					return false;
				}
			} else {
				$app->enqueueMessage($errorYtbMsg );
				$app->redirect($redirectUrl);
				return false;

			}
			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerUser::save((int)$catid, $fileName, $return, $succeeded, $errUploadMsg, false)) {
					$app->enqueueMessage($errUploadMsg );
					$app->redirect($redirectUrl);
					return true;
				} else {
					$app->enqueueMessage($errUploadMsg );
					$app->redirect($redirectUrl);
					return false;
				}
			}
		} else {
			$errUploadMsg = JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION');
			$app->enqueueMessage($msg, JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			$app->redirect($this->_loginurl);
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
		//$folder		= $this->input->get( 'folder', '', '', 'path' );
		$tab		= $this->input->get( 'tab', 0, 'int' );
		$format		= $this->input->get( 'format', 'html', 'cmd');
		$return		= $this->input->get( 'return-url', null, 'base64' );
		$viewBack	= $this->input->get( 'viewback', '', '' );
		$catid 		= $this->input->get( 'catid', 0, 'string'  );
		$Itemid		= $this->input->get( 'Itemid', 0, 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$limitStartUrl 	= $this->getLimitStartUrl(0, 'subcat');
		$return			= JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false);
		$redirectUrl	= $return;
		$catid			= (int)$catid;
		if ((int)$catid < 1) {
			jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
			'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
			'details' => JTEXT::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY'))));
			return false;
		}

		// Get user catid, we are not in the category, so we must find the catid
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategory($this->_user->id, $catid);


		if (!$isOwnerCategory) {
			jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
			'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
			'details' => JTEXT::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'))));
			return false;
		}

		// USER RIGHT - UPLOAD - - - - - - - - - - -
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;

		$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$catid);
		if (!empty($catAccess)) {
			$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $this->_user->getAuthorisedViewLevels(), $this->_user->get('id', 0), 0);
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
			if (!JFolder::exists($path->image_abs . $rightFolder . '/')) {
				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
				'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
				'details' => JTEXT::_('COM_PHOCAGALLERY_USER_FOLDER_NOT_EXISTS'))));
				return false;
			}

			// Sending and setting data for common realsingleupload function
			JFactory::getApplication()->input->set('folder', $rightFolder);//Set the right path for uploaded image (category folder included)
			JFactory::getApplication()->input->set('return-url', base64_encode($return));// set return url
			$fileName = PhocaGalleryFileUpload::realMultipleUpload(2);

			if ($fileName != '') {
				// Saving file name into database with relative path
				$fileName		= $rightFolder . '/' . strtolower($fileName);
				if(PhocaGalleryControllerUser::save((int)$catid, $fileName, false, $succeeded, $errUploadMsg, false)) {
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

		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];

		$post['userid']		= $user->id;

		$paramsC 				= JComponentHelper::getParams('com_phocagallery') ;
		$maxUploadChar			= $paramsC->get( 'max_upload_char', 1000 );
		if (isset($ytbData['desc'])) {
		} else {
			$post['description']	= substr($post['description'], 0, (int)$maxUploadChar);
		}
		$enableUserImageApprove = (int)$paramsC->get( 'enable_userimage_approve', 0 );

		$post['approved']			= 0;
		if ($enableUserImageApprove == 0) {
			$post['approved']	= 1;
		}

		$model = $this->getModel( 'user' );

		if ($model->storeimage($post, $return)) {
			$succeeded = true;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_ITEM' );
		} else {
			$succeeded = false;
			$errSaveMsg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_ITEM' );
		}

		if ($redirect) {
			$app->enqueueMessage($errSaveMsg );
			$app->redirect($return, $errSaveMsg);
			exit;
		}

		if ($succeeded) {
			return true;
		} else {
			return false;
		}
	}

	function publishimage() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);
		$id					= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {
			if(!$model->publishimage((int)$id, 1)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_PUBLISHING_ITEM');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_PUBLISHING_ITEM');
			}
		} else {

			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl);
			exit;
		}

		$limitStartUrl = $this->getLimitStartUrl((int)$id, 'image');
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function unpublishimage() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);
		$id					= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {
			if(!$model->publishimage((int)$id, 0)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_UNPUBLISHING_ITEM');
			} else {
			$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_UNPUBLISHING_ITEM');
			}
		} else {

			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}

		$limitStartUrl = $this->getLimitStartUrl((int)$id, 'image');
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false), $msg );
	}

	function orderimage() {
		$id 				= $this->input->get( 'id', 0, 'string'  );
		$task 				= $this->input->get( 'task', '', 'string' );
		$model 				= $this->getModel( 'user' );
		$isOwnerCategory 	= $model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);
		$id					= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {
			if ($task == 'orderupimage') {
				$model->moveimage(-1, (int)$id);
			} else if ($task == 'orderdownimage') {
				$model->moveimage(1, (int)$id);
			}
		} else {

			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}


		$limitStartUrl = $this->getLimitStartUrl(0, 'image');

		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}

	function saveorderimage() {
		$cid 			= $this->input->get( 'cid', array(), 'array' );
		$order 			= $this->input->get( 'order', array(), 'array' );
		$model 			= $this->getModel( 'user' );
		\Joomla\Utilities\ArrayHelper::toInteger($cid);
		\Joomla\Utilities\ArrayHelper::toInteger($order);
		$app	= JFactory::getApplication();

		$model->saveorderimage($cid, $order);
		$msg = JText::_( 'COM_PHOCAGALLERY_NEW_ORDERING_SAVED' );

		$limitStartUrl = $this->getLimitStartUrl(0, 'image');
		$app->enqueueMessage($msg);
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false), $msg);
	}

	function removeimage() {
		$id 				= $this->input->get( 'id', '', 'string'  );
		$model 				= $this->getModel('user');
		$isOwnerCategory 	= $model->isOwnerCategoryImage((int)$this->_user->id, (int)$id);
		$errorMsg = '';
		$id 				= (int)$id;
		$app	= JFactory::getApplication();
		if ($isOwnerCategory) {

			// USER RIGHT - DELETE - - - - - - - - -
			// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
			$rightDisplayDelete = 0;

			$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$isOwnerCategory);

			if (!empty($catAccess)) {
				$rightDisplayDelete = PhocaGalleryAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $this->_user->getAuthorisedViewLevels(), $this->_user->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - -
			if($rightDisplayDelete) {
				if(!$model->deleteimage((int)$id, $errorMsg)) {
					$msg = JText::_('COM_PHOCAGALLERY_ERROR_DELETING_ITEM');
				} else {
					$msg = JText::_('COM_PHOCAGALLERY_SUCCESS_DELETING_ITEM');
				}
			} else {
				$msg = JText::_('COM_PHOCAGALLERY_ERROR_NO_RIGHTS_TO_DO_THIS_ACTION');
			}
		} else {

			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl, $this->_loginstr);
			exit;
		}

		$limitStartUrl = $this->getLimitStartUrl(0, 'image', (int)$isOwnerCategory);
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false), $msg );
	}



	function editimage() {

		JSession::checkToken() or jexit( 'Invalid Token' );
		$task 						= $this->input->get( 'task', '', 'string' );
		$post['title']				= $this->input->get( 'imagename', '', 'string'  );
		$post['description']		= $this->input->get( 'phocagalleryuploaddescription', '', 'string'  );
		$post['id']					= $this->input->get( 'id', 0, 'int' );
		$paramsC 					= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar			= $paramsC->get( 'max_create_cat_char', 1000 );
		$post['description']		= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 				= $post['title'];//PhocaGalleryText::getAliasName($post['title']);
		$model 						= $this->getModel('user');

		// Lang
		$userLang			= PhocaGalleryUser::getUserLang();
		$post['language']	= $userLang['lang'];


		$app	= JFactory::getApplication();
		// USER IS NOT LOGGED
		if ($this->_user->aid < 1 && $this->_user->id < 1) {
			$app->enqueueMessage($this->_loginstr );
			$app->redirect($this->_loginurl);
			exit;
		}

		$isOwnerCategory = $model->isOwnerCategoryImage($this->_user->id, (int)$post['id']);
		if(!$isOwnerCategory) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_ASSIGNED_TO_USER' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		if ((int)$post['id'] < 1) {
			$msg = JText::_( 'COM_PHOCAGALLERY_PARENT_CATEGORY_NOT_SELECTED' );
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
			exit;
		}

		$ownerMainCategory	= $model->getOwnerMainCategory($this->_user->id);
		if (!$ownerMainCategory) {
			$msg = JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY_NOT_CREATED');
			$app->enqueueMessage($msg );
			$app->redirect(JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false));
		}

		if ($post['title'] != '') {
			$id	= $model->storeimage($post, '', 1);
			if ($id && $id > 0) {
				$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVING_ITEM' );
			} else {
				$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVING_ITEM' );
			}
		} else {
			$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_TITLE' );
		}
		$app->enqueueMessage($msg );
		$this->setRedirect( JRoute::_($this->_url. $limitStartUrl->subcat . $limitStartUrl->image, false) );
	}
}
?>
