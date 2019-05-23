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
phocagalleryimport('phocagallery.comment.comment');
phocagalleryimport('phocagallery.comment.commentimage');
phocagalleryimport( 'phocagallery.picasa.picasa');
phocagalleryimport( 'phocagallery.facebook.fbsystem');

class PhocaGalleryViewComment extends JViewLegacy
{

	public $tmpl;
	protected $params;

	function display($tpl = null) {
		$app	= JFactory::getApplication();

		$document		= JFactory::getDocument();
		$this->params	= $app->getParams();
		$user 			= JFactory::getUser();
		$uri 			= \Joomla\CMS\Uri\Uri::getInstance();
		$this->itemId	= $app->input->get('Itemid', 0, 'int');
		$this->tmpl['icon_path']	= 'media/com_phocagallery/images/';



		$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
		$access				= PhocaGalleryAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);


		// PLUGIN WINDOW - we get information from plugin
		$get = array();
		$get['comment']			= $app->input->get( 'comment', '', 'string' );
		$this->tmpl['id']		= $app->input->get('id', 0, 'int');
		$this->tmpl['catid'] 	= $app->input->get('catid', '', 'string');

		$this->tmpl['maxcommentchar']			= $this->params->get( 'max_comment_char', 1000 );
		$this->tmpl['displaycommentimg']		= $this->params->get( 'display_comment_img', 0 );
		$this->tmpl['detailwindowbackgroundcolor']= $this->params->get( 'detail_window_background_color', '#ffffff' );
		$this->tmpl['commentwidth']				= $this->params->get( 'comment_width', 500 );
		$this->tmpl['enable_multibox']			= $this->params->get( 'enable_multibox', 0);
		$this->tmpl['multibox_comments_width']		= $this->params->get( 'multibox_comments_width', 300 );
		$this->tmpl['externalcommentsystem'] 	= $this->params->get( 'external_comment_system', 0 );
		$this->tmpl['gallerymetakey'] 			= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 			= $this->params->get( 'gallery_metadesc', '' );
		$this->tmpl['altvalue']		 			= $this->params->get( 'alt_value', 1 );
		$this->tmpl['largewidth'] 				= $this->params->get( 'large_image_width', 640 );
		$this->tmpl['largeheight'] 				= $this->params->get( 'large_image_height', 480 );
		$this->tmpl['picasa_correct_width_l']	= (int)$this->params->get( 'large_image_width', 640 );
		$this->tmpl['picasa_correct_height_l']	= (int)$this->params->get( 'large_image_height', 480 );

		$paramsFb = PhocaGalleryFbSystem::getCommentsParams($this->params->get( 'fb_comment_user_id', ''));// Facebook
		$this->tmpl['fb_comment_app_id']		= isset($paramsFb['fb_comment_app_id']) ? $paramsFb['fb_comment_app_id'] : '';
		$this->tmpl['fb_comment_width']			= isset($paramsFb['fb_comment_width']) ? $paramsFb['fb_comment_width'] : 550;
		$this->tmpl['fb_comment_lang'] 			= isset($paramsFb['fb_comment_lang']) ? $paramsFb['fb_comment_lang'] : 'en_US';
		$this->tmpl['fb_comment_count'] 		= isset($paramsFb['fb_comment_count']) ? $paramsFb['fb_comment_count'] : '';
		$this->tmpl['display_comment_nopup']	= $this->params->get( 'display_comment_nopup', 0);
		$this->tmpl['enablecustomcss']			= $this->params->get( 'enable_custom_css', 0);
		$this->tmpl['customcss']				= $this->params->get( 'custom_css', '');

		// Multibox
		if ($this->tmpl['enable_multibox'] == 1) {
			$this->tmpl['commentwidth'] = (int)$this->tmpl['multibox_comments_width'] - 70;//padding - margin
		}
		$get['commentsi']						= $app->input->get( 'commentsi', '', 'int' );
		$this->tmpl['enable_multibox_iframe'] 	= 0;
		if ($get['commentsi'] == 1) {
			// Seems we are in iframe
			$this->tmpl['enable_multibox_iframe'] = 1;
		}

		// CSS
		PhocaGalleryRenderFront::renderAllCSS();

		if ($this->tmpl['gallerymetakey'] != '') {
			$document->setMetaData('keywords', $this->tmpl['gallerymetakey']);
		}
		if ($this->tmpl['gallerymetadesc'] != '') {
			$document->setMetaData('description', $this->tmpl['gallerymetadesc']);
		}



		// PARAMS - Open window parameters - modal popup box or standard popup window
		$detail_window = $this->params->get( 'detail_window', 0 );


		// Plugin information
		if (isset($get['comment']) && $get['comment'] != '') {
			$detail_window = $get['comment'];
		}


		// Only registered (VOTES + COMMENTS)
		$this->tmpl['not_registered'] 	= true;
		$this->tmpl['name']		= '';
		if ($access) {
			$this->tmpl['not_registered'] 	= false;
			$this->tmpl['name']		= $user->name;
		}

		$document->addScript(JURI::base(true).'/media/com_phocagallery/js/comments.js');
		$document->addCustomTag(PhocaGalleryRenderFront::renderCommentJS((int)$this->tmpl['maxcommentchar']));

		$this->tmpl['already_commented'] = PhocaGalleryCommentImage::checkUserComment( (int)$this->tmpl['id'], (int)$user->id );
		$commentItem					= PhocaGalleryCommentImage::displayComment( (int)$this->tmpl['id'] );




		// PARAMS - Display Description in Detail window - set the font color
		$this->tmpl['detailwindowbackgroundcolor']	= $this->params->get( 'detail_window_background_color', '#ffffff' );
		$this->tmpl['detailwindow']			 		= $this->params->get( 'detail_window', 0 );
		$description_lightbox_font_color 			= $this->params->get( 'description_lightbox_font_color', '#ffffff' );

		$description_lightbox_bg_color 				= $this->params->get( 'description_lightbox_bg_color', '#000000' );
		$description_lightbox_font_size 			= $this->params->get( 'description_lightbox_font_size', 12 );

		// NO SCROLLBAR IN DETAIL WINDOW
		$document->addCustomTag( "<style type=\"text/css\"> \n"
			." html,body, .contentpane{background:".$this->tmpl['detailwindowbackgroundcolor'].";text-align:left;} \n"
			." center, table {background:".$this->tmpl['detailwindowbackgroundcolor'].";} \n"
			." #sbox-window {background-color:#fff;padding:5px} \n"
			." </style> \n");

		$model	= $this->getModel();
		$item	= $model->getData();

		$this->tmpl['imgtitle']	=	$item->title;

		// Back button
		$this->tmpl['backbutton'] = '';
		if ($this->tmpl['detailwindow'] == 7 || $this->tmpl['display_comment_nopup']) {

			// Display Image
			// Access check - don't display the image if you have no access to this image (if user add own url)
			// USER RIGHT - ACCESS - - - - - - - - - -
			$rightDisplay	= 0;
			if (!empty($item)) {
				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $item->cataccessuserid, $item->cataccess, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
			}

			if ($rightDisplay == 0) {
				$this->tmpl['pl']		= 'index.php?option=com_users&view=login&return='.base64_encode($uri->toString());
				$app->redirect(JRoute::_($this->tmpl['pl'], false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
				exit;
			}
			// - - - - - - - - - - - - - - - - - - - -

			phocagalleryimport('phocagallery.image.image');
			$this->tmpl['backbutton'] = '<div><a href="'.JRoute::_('index.php?option=com_phocagallery&view=category&id='. $this->tmpl['catid'].'&Itemid='. $this->itemId).'"'
				.' title="'.JText::_( 'COM_PHOCAGALLERY_BACK_TO_CATEGORY' ).'">'
				. PhocaGalleryRenderFront::renderIcon('icon-up-images', 'media/com_phocagallery/images/icon-up-images.png', JText::_('COM_PHOCAGALLERY_BACK_TO_CATEGORY'), 'ph-icon-up-images ph-icon-button').'</a></div>';

			// Get file thumbnail or No Image
			$item->filenameno		= $item->filename;
			$item->filename			= PhocaGalleryFile::getTitleFromFile($item->filename, 1);
			$item->filesize			= PhocaGalleryFile::getFileSize($item->filenameno);
			$altValue				= PhocaGalleryRenderFront::getAltValue($this->tmpl['altvalue'], $item->title, $item->description, $item->metadesc);
			$item->altvalue			= $altValue;
			$realImageSize			= '';
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
				$correctImageRes 		= PhocaGalleryPicasa::correctSizeWithRate($item->extw, $item->exth, $this->tmpl['picasa_correct_width_l'], $this->tmpl['picasa_correct_height_l']);
				$item->linkimage		= JHtml::_( 'image', $item->extl, $item->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
				$item->realimagewidth 	= $correctImageRes['width'];
				$item->realimageheight	= $correctImageRes['height'];

			} else {
				$item->linkthumbnailpath	= PhocaGalleryImageFront::displayCategoryImageOrNoImage($item->filenameno, 'large');
				$item->linkimage			= JHtml::_( 'image', $item->linkthumbnailpath, $item->altvalue);
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

			$this->assignRef( 'item', $item );
		}

		// ACTION
		$this->assignRef( 'item', $item );
		$this->tmpl['action']	= $uri->toString();
		$this->assignRef( 'commentitem',$commentItem);
		$this->_prepareDocument($item);
		parent::display($tpl);
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
	}
}
