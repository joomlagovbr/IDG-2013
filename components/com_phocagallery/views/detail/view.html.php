<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
phocagalleryimport( 'phocagallery.image.image');
phocagalleryimport( 'phocagallery.image.imagefront');
phocagalleryimport( 'phocagallery.file.filethumbnail');
phocagalleryimport( 'phocagallery.rate.rateimage');
phocagalleryimport( 'phocagallery.picasa.picasa');
phocagalleryimport( 'phocagallery.facebook.fbsystem');
phocagalleryimport( 'phocagallery.youtube.youtube');
phocagalleryimport( 'phocagallery.user.user');

class PhocaGalleryViewDetail extends JViewLegacy
{

	public $tmpl;
	protected $params;

	function display($tpl = null) {

		$app					= JFactory::getApplication();
		$document				= JFactory::getDocument();
		$this->params			= $app->getParams();
		$user					= JFactory::getUser();
		$var['slideshow']		= $app->input->get('phocaslideshow', 0, 'int');
		$var['download'] 		= $app->input->get('phocadownload', 0, 'int');
		$uri 					= \Joomla\CMS\Uri\Uri::getInstance();
		$this->tmpl['action']	= $uri->toString();
		$path					= PhocaGalleryPath::getPath();
		$this->itemId			= $app->input->get('Itemid', 0, 'int');

		$neededAccessLevels		= PhocaGalleryAccess::getNeededAccessLevels();
		$access					= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);


		// Information from the plugin - window is displayed after plugin action
		$get				= array();
		$get['detail']		= $app->input->get( 'detail', '',  'string');
		$get['buttons']		= $app->input->get( 'buttons', '',  'string' );
		$get['ratingimg']	= $app->input->get( 'ratingimg', '', 'string' );

		$this->tmpl['picasa_correct_width_l']		= (int)$this->params->get( 'large_image_width', 640 );
		$this->tmpl['picasa_correct_height_l']		= (int)$this->params->get( 'large_image_height', 480 );
		$this->tmpl['enablecustomcss']				= $this->params->get( 'enable_custom_css', 0);
		$this->tmpl['customcss']					= $this->params->get( 'custom_css', '');
		$this->tmpl['enable_multibox']				= $this->params->get( 'enable_multibox', 0);
		$this->tmpl['multibox_height']				= (int)$this->params->get( 'multibox_height', 560 );
		$this->tmpl['multibox_width']				= (int)$this->params->get( 'multibox_width', 980 );
		$this->tmpl['multibox_map_height']			= (int)$this->params->get( 'multibox_map_height', 300 );
		$this->tmpl['multibox_map_width']			= (int)$this->params->get( 'multibox_map_width', 280 );
		$this->tmpl['multibox_height_overflow']		= (int)$this->tmpl['multibox_height'] - 10;//padding
		$this->tmpl['multibox_comments_width']		= $this->params->get( 'multibox_comments_width', 300 );
		$this->tmpl['multibox_comments_height']		= $this->params->get( 'multibox_comments_height', 600 );
		$this->tmpl['multibox_thubms_box_width']	= $this->params->get( 'multibox_thubms_box_width', 300 );
		$this->tmpl['multibox_thubms_count']		= $this->params->get( 'multibox_thubms_count', 4 );
		$this->tmpl['large_image_width']			= $this->params->get( 'large_image_width', 640 );
		$this->tmpl['large_image_height']			= $this->params->get( 'large_image_height', 640 );
		$this->tmpl['multibox_fixed_cols']			= $this->params->get( 'multibox_fixed_cols', 1 );
		$this->tmpl['display_multibox']				= $this->params->get( 'display_multibox', array(1,2));
		$this->tmpl['display_title_description']	= $this->params->get( 'display_title_description', 0);
		$this->tmpl['responsive']					= $this->params->get( 'responsive', 0 );
		$this->tmpl['bootstrap_icons']				= $this->params->get( 'bootstrap_icons', 0 );

		// CSS
		PhocaGalleryRenderFront::renderAllCSS(1);

		// Plugin information
		$this->tmpl['detailwindow']	= $this->params->get( 'detail_window', 0 );
		if (isset($get['detail']) && $get['detail'] != '') {
			$this->tmpl['detailwindow'] 		= $get['detail'];
		}

		// Plugin information
		$this->tmpl['detailbuttons']	= $this->params->get( 'detail_buttons', 1 );
		if (isset($get['buttons']) && $get['buttons'] != '') {
			$this->tmpl['detailbuttons'] = $get['buttons'];
		}

		// Close and Reload links (for different window types)
		$close = PhocaGalleryRenderFront::renderCloseReloadDetail($this->tmpl['detailwindow']);
		$this->tmpl['detailwindowclose']	= $close['detailwindowclose'];
		$this->tmpl['detailwindowreload']	= $close['detailwindowreload'];


		$this->tmpl['displaydescriptiondetail']		= $this->params->get( 'display_description_detail', 0 );

		$this->tmpl['display_rating_img']				= $this->params->get( 'display_rating_img', 0 );
		$this->tmpl['display_icon_download'] 			= $this->params->get( 'display_icon_download', 0 );
		$this->tmpl['externalcommentsystem'] 			= $this->params->get( 'external_comment_system', 0 );
		$this->tmpl['largewidth'] 					= $this->params->get( 'large_image_width', 640 );
		$this->tmpl['largeheight'] 					= $this->params->get( 'large_image_height', 480 );
		$this->tmpl['boxlargewidth'] 					= $this->params->get( 'front_modal_box_width', 680 );
		$this->tmpl['boxlargeheight'] 				= $this->params->get( 'front_modal_box_height', 560 );
		$this->tmpl['slideshow_delay'] 				= $this->params->get( 'slideshow_delay', 3000 );
		$this->tmpl['slideshow_pause'] 				= $this->params->get( 'slideshow_pause', 2500 );
		$this->tmpl['slideshowrandom'] 				= $this->params->get( 'slideshow_random', 0 );
		$this->tmpl['slideshow_description'] 			= $this->params->get( 'slideshow_description', 'peekaboo' );
		$this->tmpl['gallerymetakey'] 				= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 				= $this->params->get( 'gallery_metadesc', '' );
		$this->tmpl['altvalue']		 				= $this->params->get( 'alt_value', 1 );
		$this->tmpl['enablecustomcss']				= $this->params->get( 'enable_custom_css', 0);
		$this->tmpl['customcss']					= $this->params->get( 'custom_css', '');
		$this->tmpl['display_tags_links'] 			= $this->params->get( 'display_tags_links', 0 );
		$this->tmpl['ytb_display'] 					= $this->params->get( 'ytb_display', 0 );

		$paramsFb = PhocaGalleryFbSystem::getCommentsParams($this->params->get( 'fb_comment_user_id', ''));// Facebook
		$this->tmpl['fb_comment_app_id']		= isset($paramsFb['fb_comment_app_id']) ? $paramsFb['fb_comment_app_id'] : '';
		$this->tmpl['fb_comment_width']			= isset($paramsFb['fb_comment_width']) ? $paramsFb['fb_comment_width'] : 550;
		$this->tmpl['fb_comment_lang'] 			= isset($paramsFb['fb_comment_lang']) ? $paramsFb['fb_comment_lang'] : 'en_US';
		$this->tmpl['fb_comment_count'] 		= isset($paramsFb['fb_comment_count']) ? $paramsFb['fb_comment_count'] : '';

		$oH = '';
		if ($this->tmpl['enable_multibox'] == 1) {
			$this->tmpl['fb_comment_width'] = $this->tmpl['multibox_comments_width'];
			$oH = 'overflow:hidden;';
		}


		// CSS
		JHtml::stylesheet('media/com_phocagallery/css/phocagallery.css' );
		if ($this->tmpl['enablecustomcss'] == 1) {
			JHtml::stylesheet('media/com_phocagallery/css/phocagallerycustom.css' );
			if ($this->tmpl['customcss'] != ''){
				$document->addCustomTag( "\n <style type=\"text/css\"> \n"
				.$this->escape(strip_tags($this->tmpl['customcss']))
				."\n </style> \n");

			}
		}

		//Multibox displaying
		$this->tmpl['mb_title'] 		= PhocaGalleryUtils::isEnabledMultiboxFeature(1);
		$this->tmpl['mb_desc'] 			= PhocaGalleryUtils::isEnabledMultiboxFeature(2);
		$this->tmpl['mb_uploaded_by'] 	= PhocaGalleryUtils::isEnabledMultiboxFeature(3);
		$this->tmpl['mb_rating'] 		= PhocaGalleryUtils::isEnabledMultiboxFeature(4);
		$this->tmpl['mb_maps'] 			= PhocaGalleryUtils::isEnabledMultiboxFeature(5);
		$this->tmpl['mb_tags'] 			= PhocaGalleryUtils::isEnabledMultiboxFeature(6);
		$this->tmpl['mb_comments'] 		= PhocaGalleryUtils::isEnabledMultiboxFeature(7);
		$this->tmpl['mb_thumbs'] 		= PhocaGalleryUtils::isEnabledMultiboxFeature(8);


		// No bar in Detail View
		if ($this->tmpl['detailwindow'] == 7) {

		} else {

			$oS = " html, body, .contentpane, #all, #main {".$oH."padding:0px !important;margin:0px !important; width: 100% !important; max-width: 100% !important;} \n"
				// gantry-fix-begin
				."body {min-width:100%} \n"
				.".rt-container {width:100%} \n";
				// gantry-fix-end
			if ($this->tmpl['responsive'] == 1) {
				$oS .= "html, body {height:100%;} \n"
				. ".pg-detail-view {
					position: relative;
					top: 50%;
					transform: perspective(1px) translateY(-50%);
				} \n";

			}


				$document->addCustomTag( "<style type=\"text/css\"> \n" . $oS . " </style> \n");
		}

		// Download from the detail view which is not in the popupbox
		if ($var['download'] == 2 ){
			$this->tmpl['display_icon_download'] = 2;
		}

		// Plugin Information
		if (isset($get['ratingimg']) && $get['ratingimg'] != '') {
			$this->tmpl['display_rating_img'] = $get['ratingimg'];
		}



		// Model
		$model	= $this->getModel();
		$item	= $model->getData();

		//Multibox Thumbnails
		$this->tmpl['mb_thumbs_data'] = '';
		if ($this->tmpl['mb_thumbs'] == 1) {
			// if we get item variable, we have rights to load the thumbnails, this is why we checking it
			if (isset($item->id) && isset($item->catid) && (int)$item->id > 0 && (int)$item->catid > 0) {
				$this->tmpl['mb_thumbs_data'] = $model->getThumbnails((int)$item->id, (int)$item->catid, (int)$item->ordering);
			}
		}

		// User Avatar
		$this->tmpl['useravatarimg'] 		= '';
		$this->tmpl['useravatarmiddle'] 	= '';
		$userAvatar = false;
		if (isset($item->userid)) {
			$userAvatar						= PhocaGalleryUser::getUserAvatar($item->userid);
		}
		if ($userAvatar) {
			$pathAvatarAbs	= $path->avatar_abs  .'thumbs/phoca_thumb_s_'. $userAvatar->avatar;
			$pathAvatarRel	= $path->avatar_rel . 'thumbs/phoca_thumb_s_'. $userAvatar->avatar;
			if (JFile::exists($pathAvatarAbs)){
				$sIH	= $this->params->get( 'small_image_height', 50 );
				$sIHR	= @getImageSize($pathAvatarAbs);
				if (isset($sIHR[1])) {
					$sIH = $sIHR[1];
				}
				if ((int)$sIH > 0) {
					$this->tmpl['useravatarmiddle'] = ((int)$sIH / 2) - 10;
				}
				$this->tmpl['useravatarimg']	= '<img src="'.JURI::base(true) . '/' . $pathAvatarRel.'?imagesid='.md5(uniqid(time())).'" alt="" />';
			}
		}



		// Access check - don't display the image if you have no access to this image (if user add own url)
		// USER RIGHT - ACCESS - - - - - - - - - -
		$rightDisplay	= 0;
		if (!empty($item)) {
			$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $item->cataccessuserid, $item->cataccess, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
		}

		if ((int)$rightDisplay == 0) {

			echo $close['html'];
			//Some problem with cache - Joomla! return this message if there is no reason for do it.
			//$this->tmpl['pl']		= 'index.php?option=com_users&view=login&return='.base64_encode($uri->toString());
			//$app->redirect(JRoute::_($this->tmpl['pl'], false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
			exit;

		}

		// - - - - - - - - - - - - - - - - - - - -

		phocagalleryimport('phocagallery.image.image');
		phocagalleryimport('phocagallery.render.renderdetailbutton'); // Javascript Slideshow buttons
		$detailButton 			= new PhocaGalleryRenderDetailButton();
		if ($this->tmpl['enable_multibox'] == 1) {
			$detailButton->setType('multibox');
		}
		$item->reloadbutton		= $detailButton->getReload($item->catslug, $item->slug);
		$item->closebutton		= $detailButton->getClose($item->catslug, $item->slug);
		$item->closetext		= $detailButton->getCloseText($item->catslug, $item->slug);
		$item->nextbutton		= $detailButton->getNext((int)$item->catid, (int)$item->id, (int)$item->ordering);
		$item->nextbuttonhref	= $detailButton->getNext((int)$item->catid, (int)$item->id, (int)$item->ordering, 1);
		$item->prevbutton		= $detailButton->getPrevious((int)$item->catid, (int)$item->id, (int)$item->ordering);
		$slideshowData			= $detailButton->getJsSlideshow((int)$item->catid, (int)$item->id, (int)$var['slideshow'], $item->catslug, $item->slug);
		$item->slideshowbutton	= $slideshowData['icons'];
		$item->slideshowfiles	= $slideshowData['files'];
		$item->slideshow		= $var['slideshow'];
		$item->download			= $var['download'];

		// ALT VALUE
		$altValue	= PhocaGalleryRenderFront::getAltValue($this->tmpl['altvalue'], $item->title, $item->description, $item->metadesc);
		$item->altvalue			= $altValue;

		// Get file thumbnail or No Image
		$item->filenameno		= $item->filename;
		$item->filename			= PhocaGalleryFile::getTitleFromFile($item->filename, 1);
		$item->filesize			= PhocaGalleryFile::getFileSize($item->filenameno);
		$realImageSize	= '';
		$extImage = PhocaGalleryImage::isExtImage($item->extid);
		if ($extImage) {
			$item->extl			=	$item->extl;
			$item->exto			=	$item->exto;
			$realImageSize 		= PhocaGalleryImage::getRealImageSize($item->extl, '', 1);
			$item->imagesize 	= PhocaGalleryImage::getImageSize($item->exto, 1, 1);
			if ($item->extw != '') {
				$extw 		= explode(',',$item->extw);
				$item->extw	= $extw[0];
			}
			if ($item->exth != '') {
				$exth 		= explode(',',$item->exth);
				$item->exth	= $exth[0];
			}
			$correctImageRes 		= PhocaGalleryPicasa::correctSizeWithRate($item->extw, $item->exth, $this->tmpl['picasa_correct_width_l'], $this->tmpl['picasa_correct_height_l']);
			$item->linkimage		= JHtml::_( 'image', $item->extl, $item->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-detail-image img img-responsive'));
			$item->realimagewidth 	= $correctImageRes['width'];
			$item->realimageheight	= $correctImageRes['height'];


		} else {
			$item->linkthumbnailpath	= PhocaGalleryImageFront::displayCategoryImageOrNoImage($item->filenameno, 'large');
			$item->linkimage			= JHtml::_( 'image', $item->linkthumbnailpath, $item->altvalue, array( 'class' => 'pg-detail-image img img-responsive'));
			$realImageSize 				= PhocaGalleryImage::getRealImageSize ($item->filenameno);
			$item->imagesize			= PhocaGalleryImage::getImageSize($item->filenameno, 1);
			if (isset($realImageSize['w']) && isset($realImageSize['h'])) {
				$item->realimagewidth		= $realImageSize['w'];
				$item->realimageheight		= $realImageSize['h'];
			} else {
				$item->realimagewidth	 	= $this->tmpl['largewidth'];
				$item->realimageheight		= $this->tmpl['largeheight'];
			}
		}

		// Add Statistics
		$model->hit($app->input->get( 'id', '', 'int' ));

		// R A T I N G
		// Only registered (VOTES + COMMENTS)
		$this->tmpl['not_registered_img'] 	= true;
		$this->tmpl['usernameimg']		= '';
		if ($access > 0) {
			$this->tmpl['not_registered_img'] 	= false;
			$this->tmpl['usernameimg']		= $user->name;
		}

		// VOTES Statistics Img
		if ((int)$this->tmpl['display_rating_img'] == 1 || $this->tmpl['mb_rating']) {

			$this->tmpl['votescountimg']		= 0;
			$this->tmpl['votesaverageimg'] 	= 0;
			$this->tmpl['voteswidthimg']		= 0;
			$votesStatistics	= PhocaGalleryRateImage::getVotesStatistics((int)$item->id);
			if (!empty($votesStatistics->count)) {
				$this->tmpl['votescountimg'] = $votesStatistics->count;
			}
			if (!empty($votesStatistics->average)) {
				$this->tmpl['votesaverageimg'] = $votesStatistics->average;
				if ($this->tmpl['votesaverageimg'] > 0) {
					$this->tmpl['votesaverageimg'] 	= round(((float)$this->tmpl['votesaverageimg'] / 0.5)) * 0.5;
					$this->tmpl['voteswidthimg']		= 22 * $this->tmpl['votesaverageimg'];
				} else {
					$this->tmpl['votesaverageimg'] = (int)0;// not float displaying
				}
			}
			if ((int)$this->tmpl['votescountimg'] > 1) {
				$this->tmpl['votestextimg'] = 'COM_PHOCAGALLERY_VOTES';
			} else {
				$this->tmpl['votestextimg'] = 'COM_PHOCAGALLERY_VOTE';
			}

			// Already rated?
			$this->tmpl['alreay_ratedimg']	= PhocaGalleryRateImage::checkUserVote( (int)$item->id, (int)$user->id );
		}

		// Tags
		$this->tmpl['displaying_tags_output'] = '';
		if ($this->tmpl['display_tags_links'] == 1 || $this->tmpl['display_tags_links'] == 3 || $this->tmpl['mb_tags'])  {

			if ($this->tmpl['detailwindow'] == 7) {
				$this->tmpl['displaying_tags_output'] = PhocaGalleryTag::displayTags($item->id);
			} else {
				$this->tmpl['displaying_tags_output'] = PhocaGalleryTag::displayTags($item->id, 1);
			}
		}








		// Back button
		$this->tmpl['backbutton'] = '';
		if ($this->tmpl['detailwindow'] == 7) {
			phocagalleryimport('phocagallery.image.image');
			$this->tmpl['backbutton'] = '<div><a href="'.JRoute::_('index.php?option=com_phocagallery&view=category&id='. $item->catslug.'&Itemid='. $this->itemId).'"'
				.' title="'.JText::_( 'COM_PHOCAGALLERY_BACK_TO_CATEGORY' ).'">'
				. PhocaGalleryRenderFront::renderIcon('icon-up-images', 'media/com_phocagallery/images/icon-up-images.png', JText::_('COM_PHOCAGALLERY_BACK_TO_CATEGORY'), 'ph-icon-up-images ph-icon-button').'</a></div>';

		}



		// ASIGN
		$this->assignRef( 'tmpl', $this->tmpl );
		$this->assignRef( 'item', $item );
		$this->_prepareDocument($item);



		if ($this->tmpl['enable_multibox'] == 1) {

			if ($item->download > 0) {

				if ($this->tmpl['display_icon_download'] == 2) {
					$backLink = 'index.php?option=com_phocagallery&view=category&id='. $item->catslug.'&Itemid='. $this->itemId;
					phocagalleryimport('phocagallery.file.filedownload');
					if (isset($item->exto) && $item->exto != '') {
						
						PhocaGalleryFileDownload::download($item, $backLink, 1);
					} else {
						PhocaGalleryFileDownload::download($item, $backLink);
					}
					exit;
				} else {
					parent::display('multibox');
					//parent::display('download');
				}
			} else {


				if (isset($item->videocode) && $item->videocode != '' && $item->videocode != '0') {
					$item->videocode = PhocaGalleryYoutube::displayVideo($item->videocode);
				}
				parent::display('multibox');
			}
		} else if (isset($item->videocode) && $item->videocode != ''  && $item->videocode != '0') {
			$item->videocode = PhocaGalleryYoutube::displayVideo($item->videocode);

			if ($this->tmpl['detailwindow'] != 7 && $this->tmpl['ytb_display'] == 1) {
				$document->addCustomTag( "<style type=\"text/css\"> \n"
					." html, body, .contentpane, div#all, div#main, div#system-message-container {padding: 0px !important;margin: 0px !important;} \n"
					." div#sbox-window {background-color:#fff;padding: 0px;margin: 0px;} \n"
					." </style> \n");
			}

			parent::display('video');
		} else {
			parent::display('slideshowjs');
			if ($item->slideshow == 1) {
				parent::display('slideshow');
			} else if ($item->download > 0) {

				if ($this->tmpl['display_icon_download'] == 2) {
					$backLink = 'index.php?option=com_phocagallery&view=category&id='. $item->catslug.'&Itemid='. $this->itemId;
					phocagalleryimport('phocagallery.file.filedownload');
					if (isset($item->exto) && $item->exto != '') {
						
						PhocaGalleryFileDownload::download($item, $backLink, 1);
					} else {
						PhocaGalleryFileDownload::download($item, $backLink);
					}
					exit;
				} else {
					parent::display('download');
				}
			} else {
				parent::display($tpl);
			}
		}
	}

	protected function _prepareDocument($item) {

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		//$this->params		= $app->getParams();
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

			if (isset($item->title) && $item->title != '') {
				$title = $title .' - ' .  $item->title;
			}

		} else if ($app->get('sitename_pagetitles', 0) == 2) {

			if (isset($item->title) && $item->title != '') {
				$title = $title .' - ' .  $item->title;
			}

			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
		$this->document->setTitle($title);

		if ($item->metadesc != '') {
			$this->document->setDescription($item->metadesc);
		} else if ($this->tmpl['gallerymetadesc'] != '') {
			$this->document->setDescription($this->tmpl['gallerymetadesc']);
		} else if ($this->params->get('menu-meta_description', '')) {
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		}

		if ($item->metakey != '') {
			$this->document->setMetadata('keywords', $item->metakey);
		} else if ($this->tmpl['gallerymetakey'] != '') {
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


		// Features added by Bernard Gilly - alphaplug.com
		// load external plugins
		/*$user       = JFactory::getUser();
		$imgid      = $item->id;
		$catid		= $item->catid;
		$db	   		= JFactory::getDBO();
		$query 		= "SELECT owner_id FROM #__phocagallery_categories WHERE `id`='$catid'";
		$db->setQuery( $query );
		$ownerid 	= $db->loadResult();
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('phocagallery');
		$results 	= \JFactory::getApplication()->triggerEvent('onViewImage', array($imgid, $catid, $ownerid, $user->id ) );*/

		$user       = JFactory::getUser();
		//$dispatcher = J Dispatcher::getInstance();
		JPluginHelper::importPlugin('phocagallery');
		$results 	= \JFactory::getApplication()->triggerEvent('onViewImage', array((int)$item->id, (int)$item->catid, (int)$item->owner_id, (int)$user->id ) );


	}
}
