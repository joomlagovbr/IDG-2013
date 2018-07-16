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
defined( '_JEXEC' ) or die();
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
phocagalleryimport('phocagallery.file.fileupload');
phocagalleryimport( 'phocagallery.file.fileuploadmultiple' );
phocagalleryimport( 'phocagallery.file.fileuploadsingle' );
phocagalleryimport( 'phocagallery.file.fileuploadjava' );
phocagalleryimport('phocagallery.avatar.avatar');
phocagalleryimport('phocagallery.render.renderadmin');
phocagalleryimport('phocagallery.html.category');
//phocagalleryimport('phocagallery.pagination.paginationuser');
use Joomla\String\StringHelper;

class PhocaGalleryViewUser extends JViewLegacy
{
	protected $_context_subcat		= 'com_phocagallery.phocagalleryusersubcat';
	protected $_context_image			= 'com_phocagallery.phocagalleryuserimage';
	protected $tmpl;

	function display($tpl = null) {
		
		$app				= JFactory::getApplication();
		$document			= JFactory::getDocument();
		$uri 				= JFactory::getURI();
		$menus				= $app->getMenu();
		$menu				= $menus->getActive();
		$this->params		= $app->getParams();
		$user 				= JFactory::getUser();
		$path				= PhocaGalleryPath::getPath();
		$this->itemId			= $app->input->get('Itemid', 0, 'int');
	
		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);
		
	

		$this->tmpl['pi']		= 'media/com_phocagallery/images/';
		$this->tmpl['pp']		= 'index.php?option=com_phocagallery&view=user&controller=user';
		$this->tmpl['pl']		= 'index.php?option=com_users&view=login&return='.base64_encode($this->tmpl['pp'].'&Itemid='. $this->itemId);
		// LIBRARY
		$library 							= PhocaGalleryLibrary::getLibrary();
		//$libraries['pg-css-ie'] 			= $library->getLibrary('pg-css-ie');
		
		// Only registered users
		if (!$access) {
			$app->redirect(JRoute::_($this->tmpl['pl'], false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			exit;
		}
		
		$this->tmpl['gallerymetakey'] 		= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 		= $this->params->get( 'gallery_metadesc', '' );
		if ($this->tmpl['gallerymetakey'] != '') {
			$document->setMetaData('keywords', $this->tmpl['gallerymetakey']);
		}
		if ($this->tmpl['gallerymetadesc'] != '') {
			$document->setMetaData('description', $this->tmpl['gallerymetadesc']);
		}
		
		PhocaGalleryRenderFront::renderAllCSS();
		
		// Custom order
		// administrator\components\com_phocagallery\libraries\phocagallery\html\grid.php replaces
		// libraries\cms\html\grid.php (libraries\joomla\grid\grid.php) and the javascript: 
		// media\system\js\core-uncompressed.js (core.js)
		PhocaGalleryGrid::renderSortJs();
		
	
		
		// = = = = = = = = = = = 
		// PANE
		// = = = = = = = = = = =
		// - - - - - - - - - - 
		// ALL TABS
		// - - - - - - - - - -
		// UCP is disabled (security reasons)
		
		if ((int)$this->params->get( 'enable_user_cp', 0 ) == 0) {
			$app->redirect(JURI::base(true), JText::_('COM_PHOCAGALLERY_UCP_DISABLED'));
			exit;
		}
		
		$this->tmpl['tab'] 					= $app->input->get('tab', 0, 'string');
		
		$this->tmpl['maxuploadchar']		= $this->params->get( 'max_upload_char', 1000 );
		$this->tmpl['maxcreatecatchar']		= $this->params->get( 'max_create_cat_char', 1000 );
		$this->tmpl['showpageheading'] 		= $this->params->get( 'show_page_heading', 1 );
		$this->tmpl['javaboxwidth'] 		= $this->params->get( 'java_box_width', 480 );
		$this->tmpl['javaboxheight'] 		= $this->params->get( 'java_box_height', 480 );
		$this->tmpl['enableuploadavatar'] 	= $this->params->get( 'enable_upload_avatar', 1 );
		$this->tmpl['uploadmaxsize'] 		= $this->params->get( 'upload_maxsize', 3145728 );
		$this->tmpl['uploadmaxsizeread'] 	= PhocaGalleryFile::getFileSizeReadable($this->tmpl['uploadmaxsize']);
		$this->tmpl['uploadmaxreswidth'] 	= $this->params->get( 'upload_maxres_width', 3072 );
		$this->tmpl['uploadmaxresheight'] 	= $this->params->get( 'upload_maxres_height', 2304 );
		$this->tmpl['multipleuploadchunk']	= $this->params->get( 'multiple_upload_chunk', 0 );
		$this->tmpl['displaytitleupload']	= $this->params->get( 'display_title_upload', 0 );
		$this->tmpl['displaydescupload'] 	= $this->params->get( 'display_description_upload', 0 );
		$this->tmpl['enablejava'] 			= $this->params->get( 'enable_java', -1);
		$this->tmpl['enablemultiple'] 		= $this->params->get( 'enable_multiple', 0 );
		$this->tmpl['ytbupload'] 			= $this->params->get( 'youtube_upload', 0 );
		$this->tmpl['multipleuploadmethod'] = $this->params->get( 'multiple_upload_method', 4 );
		$this->tmpl['multipleresizewidth'] 	= $this->params->get( 'multiple_resize_width', -1 );
		$this->tmpl['multipleresizeheight'] = $this->params->get( 'multiple_resize_height', -1 );
		$this->tmpl['usersubcatcount']		= $this->params->get( 'user_subcat_count', 5 );
		$this->tmpl['userimagesmaxspace']	= $this->params->get( 'user_images_max_size', 20971520 );
		
		$this->tmpl['iepx']				= '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
	
		//Subcateogry
		$this->tmpl['parentid']			= $app->input->get('parentcategoryid', 0, 'int');
		
		$document->addScript(JURI::base(true).'/media/com_phocagallery/js/comments.js');
		$document->addCustomTag(PhocaGalleryRenderFront::renderOnUploadJS());
		$document->addCustomTag(PhocaGalleryRenderFront::renderDescriptionCreateCatJS((int)$this->tmpl['maxcreatecatchar']));
		$document->addCustomTag(PhocaGalleryRenderFront::userTabOrdering());// SubCategory + Image
		$document->addCustomTag(PhocaGalleryRenderFront::renderDescriptionCreateSubCatJS((int)$this->tmpl['maxcreatecatchar']));
		$document->addCustomTag(PhocaGalleryRenderFront::saveOrderUserJS());
		
		$model 						= $this->getModel('user');
		$ownerMainCategory			= $model->getOwnerMainCategory($user->id);
		
		
		$this->tmpl['usertab'] 				= 1;
		$this->tmpl['createcategory'] 		= 1;
		$this->tmpl['createsubcategory'] 	= 1;
		$this->tmpl['images'] 				= 1;
		$this->tmpl['displayupload'] 		= 1;
	
		
		// Tabs
		$displayTabs	= 0;
		
		if ((int)$this->tmpl['usertab'] == 0) {
			$currentTab['user'] = -1;
		} else {
			$currentTab['user'] = $displayTabs;
			$displayTabs++;	
		}
		
		if ((int)$this->tmpl['createcategory'] == 0) {
			$currentTab['createcategory'] = -1;
		} else {
			$currentTab['createcategory'] = $displayTabs;
			$displayTabs++;	
		}
		
		if ((int)$this->tmpl['createsubcategory'] == 0) {
			$currentTab['createsubcategory'] = -1;
		} else {
			$currentTab['createsubcategory'] = $displayTabs;
			$displayTabs++;	
		}
		
		
		if ((int)$this->tmpl['displayupload'] == 0) {
			$currentTab['images'] = -1;
		}else {
			$currentTab['images'] = $displayTabs;
			$displayTabs++;	
		}
	
		$this->tmpl['displaytabs']	= $displayTabs;
		$this->tmpl['currenttab']	= $currentTab;

		
		// ACTION
		$this->tmpl['action']	= $uri->toString();
		$this->tmpl['ftp'] 		= !JClientHelper::hasCredentials('ftp');
		$sess = JFactory::getSession();
		$this->assignRef('session', $sess);

		
		// SEF problem
		$isThereQM = false;
		$isThereQM = preg_match("/\?/i", $this->tmpl['action']);
		if ($isThereQM) {
			$amp = '&';// will be translated to htmlspecialchars
		} else {
			$amp = '?';
		}	
		
		$this->tmpl['actionamp']	=	$this->tmpl['action'] . $amp;
		$this->tmpl['istheretab'] = false;
		$this->tmpl['istheretab'] = preg_match("/tab=/i", $this->tmpl['action']);
		
		
		

		
		// EDIT - subcategory, image
		$this->tmpl['task'] 		= $app->input->get( 'task', '', 'string');
		$id 						= $app->input->get( 'id', '', 'string');
		$idAlias					= $id;
		
		
		// - - - - - - - - - - - 
		// USER (AVATAR)
		// - - - - - - - - - - -
		
		$this->tmpl['user'] 				= $user->name;
		$this->tmpl['username']			= $user->username;
		$this->tmpl['useravatarimg']		= JHtml::_('image', $this->tmpl['pi'].'phoca_thumb_m_no_image.png', '');
		$this->tmpl['useravatarapproved'] = 0;
		$userAvatar					= $model->getUserAvatar($user->id);
		
		if ($userAvatar) {
			$pathAvatarAbs	= $path->avatar_abs  .'thumbs/phoca_thumb_m_'. $userAvatar->avatar;
			$pathAvatarRel	= $path->avatar_rel . 'thumbs/phoca_thumb_m_'. $userAvatar->avatar;
			if (JFile::exists($pathAvatarAbs)){
				$this->tmpl['useravatarimg']	= '<img src="'.JURI::base(true) . '/' . $pathAvatarRel.'?imagesid='.md5(uniqid(time())).'" alt="" />';
				$this->tmpl['useravatarapproved']	= 	$userAvatar->approved;
			}
		}
		
		if ($ownerMainCategory) {
			$this->tmpl['usermaincategory'] =  $ownerMainCategory->title;
		} else {	
			$this->tmpl['usermaincategory'] =  PhocaGalleryRenderFront::renderIcon('minus-sign',$this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_CREATED'))
			.' ('.JText::_('COM_PHOCAGALLERY_NOT_CREATED').')';
		}
		$this->tmpl['usersubcategory'] 		= $model->getCountUserSubCat($user->id);
		$this->tmpl['usersubcategoryleft']	= (int)$this->tmpl['usersubcatcount'] - (int)$this->tmpl['usersubcategory'];
		if ((int)$this->tmpl['usersubcategoryleft'] < 0) {$this->tmpl['usersubcategoryleft'] = 0;}
		$this->tmpl['userimages']				= $model->getCountUserImage($user->id);
		$this->tmpl['userimagesspace']		= $model->getSumUserImage($user->id);
		$this->tmpl['userimagesspaceleft']	= (int)$this->tmpl['userimagesmaxspace'] - (int)$this->tmpl['userimagesspace'];
		if ((int)$this->tmpl['userimagesspaceleft'] < 0) {$this->tmpl['userimagesspaceleft'] = 0;}
		$this->tmpl['userimagesspace']		= PhocaGalleryFile::getFileSizeReadable($this->tmpl['userimagesspace']);
		$this->tmpl['userimagesspaceleft']	= PhocaGalleryFile::getFileSizeReadable($this->tmpl['userimagesspaceleft']);
		$this->tmpl['userimagesmaxspace']		= PhocaGalleryFile::getFileSizeReadable($this->tmpl['userimagesmaxspace']);
		
		
		// - - - - - - - - - - - 
		// MAIN CATEGORY
		// - - - - - - - - - - -
		$ownerMainCategory 	= $model->getOwnerMainCategory($user->id);
		if (!empty($ownerMainCategory->id)) {
			if ((int)$ownerMainCategory->published == 1) {
				$this->tmpl['categorycreateoredithead']	= JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY');
				$this->tmpl['categorycreateoredit']		= JText::_('COM_PHOCAGALLERY_EDIT');		
				$this->tmpl['categorytitle']				= $ownerMainCategory->title;
				$this->tmpl['categoryapproved']			= $ownerMainCategory->approved;
				$this->tmpl['categorydescription']		= $ownerMainCategory->description;
				$this->tmpl['categorypublished']			= 1;
			} else {
				$this->tmpl['categorypublished']			= 0;
			}
		} else {
			$this->tmpl['categorycreateoredithead']	= JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY');
			$this->tmpl['categorycreateoredit']		= JText::_('COM_PHOCAGALLERY_CREATE');
			$this->tmpl['categorytitle']				= '';
			$this->tmpl['categorydescription']		= '';
			$this->tmpl['categoryapproved']			= '';
			$this->tmpl['categorypublished']			= -1;
		}
		
		
		// - - - - - - - - - - - 
		// SUBCATEGORY
		// - - - - - - - - - - -

		
		if (!empty($ownerMainCategory->id)) {
		
			// EDIT
			$this->tmpl['categorysubcatedit'] = $model->getCategory((int)$id, $user->id);
			$this->tmpl['displaysubcategory'] = 1;
			
			// Get All Data - Subcategories
			$this->tmpl['subcategoryitems'] 		= $model->getDataSubcat($user->id);
			$this->tmpl['subcategorytotal'] 		= count($this->tmpl['subcategoryitems']);
			$model->setTotalSubCat($this->tmpl['subcategorytotal']);
			$this->tmpl['subcategorypagination'] 	= $model->getPaginationSubCat($user->id);
			$this->tmpl['subcategoryitems'] 		= array_slice($this->tmpl['subcategoryitems'],(int)$this->tmpl['subcategorypagination']->limitstart, (int)$this->tmpl['subcategorypagination']->limit);

			$filter_state_subcat	= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_state',	'filter_state_subcat', '',	'word' );
			$filter_catid_subcat	= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_catid',	'filter_catid_subcat',	0, 'int' );
			
			$filter_order_subcat	= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order',	'filter_order_subcat',	'a.ordering', 'cmd' );
			$filter_order_Dir_subcat= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order_Dir',	'filter_order_Dir_subcat',	'',	'word' );
			$search_subcat			= $app->getUserStateFromRequest( $this->_context_subcat.'.search', 'phocagallerysubcatsearch', '',	'string' );
			if (strpos($search_subcat, '"') !== false) {
				$search_subcat = str_replace(array('=', '<'), '', $search_subcat);
			}
			$search_subcat			= StringHelper::strtolower( $search_subcat );
			
			$categories 				= $model->getCategoryList($user->id);
			if (!empty($categories)) {
				$javascript 	= 'class="inputbox" onchange="document.phocagallerysubcatform.submit();"';
				$tree = array();
				$text = '';
				$tree = PhocaGalleryCategory::CategoryTreeOption($categories, $tree,0, $text, -1);
				
				array_unshift($tree, JHtml::_('select.option', '0', '- '.JText::_('COM_PHOCAGALLERY_SELECT_CATEGORY').' -', 'value', 'text'));
				$lists_subcat['catid'] = JHtml::_( 'select.genericlist', $tree, 'filter_catid_subcat',  $javascript , 'value', 'text', $filter_catid_subcat );
			}
			
			$this->tmpl['parentcategoryid']	= $filter_catid_subcat;

			// state filter
			//$lists['state']		= JHtml::_('grid.state',  $filter_state );
			$state_subcat[] 		= JHtml::_('select.option',  '', '- '. JText::_( 'COM_PHOCAGALLERY_SELECT_STATE' ) .' -' );
			$state_subcat[] 		= JHtml::_('select.option',  'P', JText::_( 'COM_PHOCAGALLERY_PUBLISHED' ) );
			$state_subcat[] 		= JHtml::_('select.option',  'U', JText::_( 'COM_PHOCAGALLERY_UNPUBLISHED') );
			$lists_subcat['state']	= JHtml::_('select.genericlist',   $state_subcat, 'filter_state_subcat', 'class="inputbox" size="1" onchange="document.phocagallerysubcatform.submit();"', 'value', 'text', $filter_state_subcat );

			// table ordering
			$lists_subcat['order_Dir'] 	= $filter_order_Dir_subcat;
			$lists_subcat['order'] 		= $filter_order_subcat;

			$this->tmpl['subcategoryordering'] = ($lists_subcat['order'] == 'a.ordering');//Ordering allowed ?
			
			// search filter
			$lists_subcat['search']		= $search_subcat;
		} else {
			$this->tmpl['displaysubcategory'] = 0;
		}
		
		// - - - - - - - - - - - 
		// IMAGES
		// - - - - - - - - - - -
		if (!empty($ownerMainCategory->id)) {
			$catAccess		= PhocaGalleryAccess::getCategoryAccess((int)$ownerMainCategory->id);
			
			// EDIT
			$this->tmpl['imageedit'] 			= $model->getImage((int)$id, $user->id);
			
			$this->tmpl['imageitems'] 		= $model->getDataImage($user->id);
			$this->tmpl['imagetotal'] 		= $model->getTotalImage($user->id);
			$this->tmpl['imagepagination'] 	= $model->getPaginationImage($user->id);
			
			$filter_state_image	= $app->getUserStateFromRequest( $this->_context_image.'.filter_state',	'filter_state_image', '',	'word' );
			$filter_catid_image	= $app->getUserStateFromRequest( $this->_context_image.'.filter_catid',	'filter_catid_image',	0, 'int' );
			$filter_order_image	= $app->getUserStateFromRequest( $this->_context_image.'.filter_order',	'filter_order_image',	'a.ordering', 'cmd' );
			$filter_order_Dir_image= $app->getUserStateFromRequest( $this->_context_image.'.filter_order_Dir',	'filter_order_Dir_image',	'',	'word' );
			$search_image			= $app->getUserStateFromRequest( $this->_context_image.'.search', 'phocagalleryimagesearch', '',	'string' );
			if (strpos($search_image, '"') !== false) {
				$search_image = str_replace(array('=', '<'), '', $search_image);
			}
			$search_image			= StringHelper::strtolower( $search_image );
			
			$categoriesImage 		= $model->getCategoryList($user->id);
			if (!empty($categoriesImage)) {
				//$javascript     = 'class="inputbox" size="1" onchange="document.phocagalleryimageform.submit();"';
$javascript     = 'class="inputbox" size="1" onchange="document.getElementById(\'phocagalleryimageform\').submit();"';
				$tree = array();
				$text = '';
				$tree = PhocaGalleryCategory::CategoryTreeOption($categoriesImage, $tree,0, $text, -1);
				
				array_unshift($tree, JHtml::_('select.option', '0', '- '.JText::_('COM_PHOCAGALLERY_SELECT_CATEGORY').' -', 'value', 'text'));
				$lists_image['catid'] = JHtml::_( 'select.genericlist', $tree, 'filter_catid_image',  $javascript , 'value', 'text', $filter_catid_image );
			}
			
			// state filter
			$state_image[] 		= JHtml::_('select.option',  '', '- '. JText::_( 'COM_PHOCAGALLERY_SELECT_STATE' ) .' -' );
			$state_image[] 		= JHtml::_('select.option', 'P', JText::_( 'COM_PHOCAGALLERY_FIELD_PUBLISHED_LABEL' ) );
			$state_image[] 		= JHtml::_('select.option', 'U', JText::_( 'COM_PHOCAGALLERY_FIELD_UNPUBLISHED_LABEL') );
			$lists_image['state']	= JHtml::_('select.genericlist',   $state_image, 'filter_state_image', 'class="inputbox" size="1" onchange="document.getElementById(\'phocagalleryimageform\').submit();"', 'value', 'text', $filter_state_image );

			// table ordering
			$lists_image['order_Dir'] 	= $filter_order_Dir_image;
			$lists_image['order'] 		= $filter_order_image;

			$this->tmpl['imageordering']		= ($lists_image['order'] == 'a.ordering');//Ordering allowed ?
			
			// search filter
			$lists_image['search']		= $search_image;
			$this->tmpl['catidimage']			= $filter_catid_image;
			
			// Upload
			$this->tmpl['displayupload']	= 0;
			// USER RIGHT - UPLOAD - - - - - - - - - - -
			// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
			$rightDisplayUpload = 0;// default is to null (all users cannot upload)
			if (!empty($catAccess)) {
				$rightDisplayUpload = PhocaGalleryAccess::getUserRight('uploaduserid', $catAccess->uploaduserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
			}
			if ($rightDisplayUpload == 1) {
				$this->tmpl['displayupload']	= 1;
				$document->addCustomTag(PhocaGalleryRenderFront::renderDescriptionUploadJS((int)$this->tmpl['maxuploadchar']));
			}
			// - - - - - - - - - - - - - - - - - - - - - 
			
			// USER RIGHT - ACCESS - - - - - - - - - - - 
			$rightDisplay = 1;//default is set to 1 (all users can see the category)
			if (!empty($catAccess)) {
				
				$rightDisplay = PhocaGalleryAccess::getUserRight ('accessuserid', $catAccess->accessuserid, $catAccess->access, $user->getAuthorisedViewLevels(), $user->get('id', 0), 1);
			}
			if ($rightDisplay == 0) {
				$app->redirect(JRoute::_($this->tmpl['pl'], false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
				exit;
			}		
			// - - - - - - - - - - - - - - - - - - - - - 
			
			// = = = = = = = = = = 
			// U P L O A D
			// = = = = = = = = = =
			
			
			// - - - - - - - - - - -
			// Upload
			// - - - - - - - - - - -
			if ((int)$this->tmpl['displayupload'] == 1) {
				$sU							= new PhocaGalleryFileUploadSingle();
				$sU->returnUrl				= htmlspecialchars($this->tmpl['action'] . $amp .'task=upload&'. $this->session->getName().'='.$this->session->getId()
											.'&'. JSession::getFormToken().'=1&viewback=category&tab='.$this->tmpl['currenttab']['images']);
				$sU->tab					= $this->tmpl['currenttab']['images'];
				$this->tmpl['su_output']	= $sU->getSingleUploadHTML(1);
				$this->tmpl['su_url']		= htmlspecialchars($this->tmpl['action'] . $amp .'task=upload&'. $this->session->getName().'='.$this->session->getId()
											.'&'. JSession::getFormToken().'=1&viewback=category&tab='.$this->tmpl['currenttab']['images']);
			}
			
			// - - - - - - - - - - -
			// Youtube Upload (single upload form can be used)
			// - - - - - - - - - - -
			if ((int)$this->tmpl['ytbupload'] > 0) {
				$sYU						= new PhocaGalleryFileUploadSingle();
				$sYU->returnUrl				= htmlspecialchars($this->tmpl['action'] . $amp .'task=ytbupload&'. $this->session->getName().'='.$this->session->getId()
											.'&'. JSession::getFormToken().'=1&viewback=category&tab='.$this->tmpl['currenttab']['images']);
				$sYU->tab					= $this->tmpl['currenttab']['images'];
				$this->tmpl['syu_output']	= $sYU->getSingleUploadHTML(1);
				$this->tmpl['syu_url']		= htmlspecialchars($this->tmpl['action'] . $amp .'task=ytbupload&'. $this->session->getName().'='.$this->session->getId()
											.'&'. JSession::getFormToken().'=1&viewback=category&tab='.$this->tmpl['currenttab']['images']);
			}
			
			
			// - - - - - - - - - - -
			// Multiple Upload
			// - - - - - - - - - - -
			// Get infos from multiple upload
			$muFailed						= $app->input->get( 'mufailed', '0', 'int' );
			$muUploaded						= $app->input->get( 'muuploaded', '0', 'int' );
			$this->tmpl['mu_response_msg']	= $muUploadedMsg 	= '';
			
			if ($muUploaded > 0) {
				$muUploadedMsg = JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded;
			}
			if ($muFailed > 0) {
				$muFailedMsg = JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed;
			}
			if ($muFailed > 0 && $muUploaded > 0) {
				$this->tmpl['mu_response_msg'] = '<div class="alert alert-info">'
				.JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded .'<br />'
				.JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed.'</div>';
			} else if ($muFailed > 0 && $muUploaded == 0) {
				$this->tmpl['mu_response_msg'] = '<div class="alert alert-error">'
				.JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed.'</div>';
			} else if ($muFailed == 0 && $muUploaded > 0){
				$this->tmpl['mu_response_msg'] = '<div class="alert alert-success">'
				.JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded.'</div>';
			} else {
				$this->tmpl['mu_response_msg'] = '';
			}
			
			if((int)$this->tmpl['enablemultiple']  == 1 && (int)$this->tmpl['displayupload'] == 1) {
			
				PhocaGalleryFileUploadMultiple::renderMultipleUploadLibraries();
				$mU						= new PhocaGalleryFileUploadMultiple();
				$mU->frontEnd			= 2;
				$mU->method				= $this->tmpl['multipleuploadmethod'];
				$mU->url				= htmlspecialchars($this->tmpl['action'] . $amp .'controller=user&task=multipleupload&'
										 . $this->session->getName().'='.$this->session->getId().'&'
										 . JSession::getFormToken().'=1&tab='.$this->tmpl['currenttab']['images']
										 . '&catid='.$this->tmpl['catidimage']);
				$mU->reload				= htmlspecialchars($this->tmpl['action'] . $amp 
										. $this->session->getName().'='.$this->session->getId().'&'
										. JSession::getFormToken().'=1&tab='.$this->tmpl['currenttab']['images']);
				$mU->maxFileSize		= PhocaGalleryFileUploadMultiple::getMultipleUploadSizeFormat($this->tmpl['uploadmaxsize']);
				$mU->chunkSize			= '1mb';
				$mU->imageHeight		= $this->tmpl['multipleresizeheight'];
				$mU->imageWidth			= $this->tmpl['multipleresizewidth'];
				$mU->imageQuality		= 100;
				$mU->renderMultipleUploadJS(0, $this->tmpl['multipleuploadchunk']);
				$this->tmpl['mu_output']= $mU->getMultipleUploadHTML();
			}
			
			// - - - - - - - - - - -
			// Java Upload
			// - - - - - - - - - - -
			if((int)$this->tmpl['enablejava']  == 1 && (int)$this->tmpl['displayupload'] == 1) {
				$jU							= new PhocaGalleryFileUploadJava();
				$jU->width					= $this->tmpl['javaboxwidth'];
				$jU->height					= $this->tmpl['javaboxheight'];
				$jU->resizewidth			= $this->tmpl['multipleresizewidth'];
				$jU->resizeheight			= $this->tmpl['multipleresizeheight'];
				$jU->uploadmaxsize			= $this->tmpl['uploadmaxsize'];
				$jU->returnUrl				= htmlspecialchars($this->tmpl['action'] . $amp 
											. $this->session->getName().'='.$this->session->getId().'&'
											. JSession::getFormToken().'=1&tab='.$this->tmpl['currenttab']['images']);
				$jU->url					= htmlspecialchars($this->tmpl['action'] . $amp .'controller=user&task=javaupload&'
											. $this->session->getName().'='.$this->session->getId().'&'
											. JSession::getFormToken().'=1&tab='.$this->tmpl['currenttab']['images']
											. '&catid='.$this->tmpl['catidimage']);
				$jU->source 				= JURI::root(true).'/components/com_phocagallery/assets/jupload/wjhk.jupload.jar';
				$this->tmpl['ju_output']	= $jU->getJavaUploadHTML();
				
			}
			
		} else {
			$this->tmpl['displayupload'] = 0;
		}

		if (!empty($ownerMainCategory->id)) {
			$this->tmpl['ps']	= '&tab='. $this->tmpl['currenttab']['createsubcategory']
					. '&limitstartsubcat='.$this->tmpl['subcategorypagination']->limitstart
					. '&limitstartimage='.$this->tmpl['imagepagination']->limitstart;
		} else {
			$this->tmpl['ps']	= '&tab='. $this->tmpl['currenttab']['createsubcategory'];
		}
		
		if (!empty($ownerMainCategory->id)) {
			$this->tmpl['psi']	= '&tab='. $this->tmpl['currenttab']['images']
					. '&limitstartsubcat='.$this->tmpl['subcategorypagination']->limitstart
					. '&limitstartimage='.$this->tmpl['imagepagination']->limitstart;
		} else {
			$this->tmpl['psi']	= '&tab='. $this->tmpl['currenttab']['images'];
		}
		
		// ASIGN
		$this->assignRef( 'listssubcat',	$lists_subcat);
		$this->assignRef( 'listsimage',		$lists_image);
		//$this->assignRef( 'tmpl', $this->tmpl);
		//$this->assignRef( 'params', $this->params);
		$sess = JFactory::getSession();
		$this->assignRef( 'session', $sess);
		$this->_prepareDocument();
		parent::display($tpl);
	}
	
	protected function _prepareDocument() {
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$this->params	= $app->getParams();
		$title 		= null;
		
		$this->tmpl['gallerymetakey'] 		= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 		= $this->params->get( 'gallery_metadesc', '' );
		

		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');		
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
		
		$this->document->setTitle($title);
		
		if ($this->tmpl['gallerymetadesc'] != '') {
			$this->document->setDescription($this->tmpl['gallerymetadesc']);
		} else if ($this->params->get('menu-meta_description', '')) {
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		} 

		if ($this->tmpl['gallerymetakey'] != '') {
			$this->document->setMetadata('keywords', $this->tmpl['gallerymetakey']);
		} else if ($this->params->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->params->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}

		/*if ($app->get('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->author);
		}

		/*$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}*/
		
		// Breadcrumbs TO DO (Add the whole tree)
		/*if (isset($this->category[0]->parentid)) {
			if ($this->category[0]->parentid == 1) {
			} else if ($this->category[0]->parentid > 0) {
				$pathway->addItem($this->category[0]->parenttitle, JRoute::_(PhocaDocumentationHelperRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias)));
			}
		}

		if (!empty($this->category[0]->title)) {
			$pathway->addItem($this->category[0]->title);
		}*/
	}
}
?>
