<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
phocagalleryimport('phocagallery.library.library');
phocagalleryimport('phocagallery.render.renderdetailwindow');


class PhocaGalleryCpViewPhocaGalleryImgs extends JViewLegacy
{

	protected $items;
	protected $items_thumbnail;
	protected $pagination;
	protected $state;
	protected $button;
	protected $tmpl;
	//public $_context 	= 'com_phocagallery.phocagalleryimg';

	function display($tpl = null) {


		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}


		$this->processImages();


		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		$document	= JFactory::getDocument();
		//$document->addCustomTag(PhocaGalleryRenderAdmin::renderIeCssLink(1));


		$params 	= JComponentHelper::getParams('com_phocagallery');


		$this->tmpl['enablethumbcreation']			= $params->get('enable_thumb_creation', 1 );
		$this->tmpl['enablethumbcreationstatus'] 	= PhocaGalleryRenderAdmin::renderThumbnailCreationStatus((int)$this->tmpl['enablethumbcreation']);



		/*$app	= JFactory::getApplication();
		$uri		= \Joomla\CMS\Uri\Uri::getInstance();

		$db		    = JFactory::getDBO();*/

		$this->tmpl['notapproved'] 	=  $this->get( 'NotApprovedImage' );

		// Button
		/*
		$this->button = new JObject();
		$this->button->set('modal', true);
		$this->button->set('methodname', 'modal-button');
		//$this->button->set('link', $link);
		$this->button->set('text', JText::_('COM_PHOCAGALLERY_DISPLAY_IMAGE_DETAIL'));
		//$this->button->set('name', 'image');
		$this->button->set('modalname', 'modal_phocagalleryimgs');
		$this->button->set('options', "{handler: 'image', size: {x: 200, y: 150}}");*/


		$library 			= PhocaGalleryLibrary::getLibrary();
		$libraries			= array();
		$btn 				= new PhocaGalleryRenderDetailWindow();
		$btn->popupWidth 	= '640';
		$btn->popupHeight 	= '480';
		$btn->backend		= 1;

		$btn->setButtons(12, $libraries, $library);
		$this->button = $btn->getB1();


		$this->addToolbar();
		parent::display($tpl);
	}



	protected function addToolbar() {

		require_once JPATH_COMPONENT.'/helpers/phocagalleryimgs.php';



		$state	= $this->get('State');
		$canDo	= PhocaGalleryImgsHelper::getActions($state->get('filter.image_id'));
		$user  = JFactory::getUser();
		$bar = JToolbar::getInstance('toolbar');
		JToolbarHelper ::title( JText::_('COM_PHOCAGALLERY_IMAGES'), 'image.png' );
		if ($canDo->get('core.create')) {
			JToolbarHelper ::addNew( 'phocagalleryimg.add','JToolbar_NEW');
			JToolbarHelper ::custom( 'phocagallerym.edit', 'multiple.png', '', 'COM_PHOCAGALLERY_MULTIPLE_ADD' , false);
		}
		if ($canDo->get('core.edit')) {
			JToolbarHelper ::editList('phocagalleryimg.edit','JToolbar_EDIT');
		}

		if ($canDo->get('core.create')) {

			/*
			$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert(\''.JText::_('COM_PHOCAGALLERY_WARNING_RECREATE_MAKE_SELECTION').'\');}else{if(confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_RECREATE_THUMBNAILS').'\')){submitbutton(\'phocagalleryimg.recreate\');}}" class="toolbar"><span class="icon-32-recreate" title="'.JText::_('COM_PHOCAGALLERY_RECREATE_THUMBS').'" type="Custom"></span>'.JText::_('COM_PHOCAGALLERY_RECREATE').'</a>');*/

			$dhtml = '<button class="btn btn-small" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert(\''.JText::_('COM_PHOCAGALLERY_WARNING_RECREATE_MAKE_SELECTION').'\');}else{if(confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_RECREATE_THUMBNAILS').'\')){submitbutton(\'phocagalleryimg.recreate\');}}" ><i class="icon-recreate" title="'.JText::_('COM_PHOCAGALLERY_RECREATE_THUMBS').'"></i> '.JText::_('COM_PHOCAGALLERY_RECREATE_THUMBS').'</button>';
			$bar->appendButton('Custom', $dhtml);

		}


		if ($canDo->get('core.edit.state')) {

			JToolbarHelper ::divider();
			JToolbarHelper ::custom('phocagalleryimgs.publish', 'publish.png', 'publish_f2.png','JToolbar_PUBLISH', true);
			JToolbarHelper ::custom('phocagalleryimgs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JToolbar_UNPUBLISH', true);
			JToolbarHelper ::custom( 'phocagalleryimgs.approve', 'approve.png', '',  'COM_PHOCAGALLERY_APPROVE' , true);
			JToolbarHelper ::custom( 'phocagalleryimgs.disapprove', 'disapprove.png', '',  'COM_PHOCAGALLERY_NOT_APPROVE' , true);
		}

		if ($canDo->get('core.delete')) {
			JToolbarHelper ::deleteList( JText::_( 'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS' ), 'phocagalleryimgs.delete', 'COM_PHOCAGALLERY_DELETE');
		}

		// Add a batch button
		if ($user->authorise('core.edit'))
		{
			JHtml::_('bootstrap.renderModal', 'collapseModal');
			$title = JText::_('JToolbar_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		JToolbarHelper ::divider();
		JToolbarHelper ::help( 'screen.phocagallery', true );
	}


	protected function processImages() {

		if (!empty($this->items)) {

			$params							= JComponentHelper::getParams( 'com_phocagallery' );
			$pagination_thumbnail_creation 	= $params->get( 'pagination_thumbnail_creation', 0 );
			$clean_thumbnails 				= $params->get( 'clean_thumbnails', 0 );


			//Server doesn't have CPU power
			//we do thumbnail for all images - there is no pagination...
			//or we do thumbanil for only listed images
			if (empty($this->items_thumbnail)) {
				if ($pagination_thumbnail_creation == 1) {
					$this->items_thumbnail 	= $this->items;
				} else {
					$this->items_thumbnail	= $this->get('ItemsThumbnail');

				}
			}

			// - - - - - - - - - - - - - - - - - - - -
			// Check if the file stored in database is on the server. If not please refer to user
			// Get filename from every object there is stored in database
			// file - abc.img, file_no - folder/abc.img
			// Get folder variables from Helper
			$path 				= PhocaGalleryPath::getPath();
			$origPath 			= $path->image_abs;
			$origPathServer 	= str_replace('\\', '/', $path->image_abs);

			//-----------------------------------------
			//Do all thumbnails no limit no pagination
			if (!empty($this->items_thumbnail)) {
				foreach ($this->items_thumbnail as $key => $value) {
					$fileOriginalThumb = PhocaGalleryFile::getFileOriginal($value->filename);
					//Let the user know that the file doesn't exists and delete all thumbnails
					if (JFile::exists($fileOriginalThumb)) {
						$refreshUrlThumb = 'index.php?option=com_phocagallery&view=phocagalleryimgs';
						$fileThumb = PhocaGalleryFileThumbnail::getOrCreateThumbnail( $value->filename, $refreshUrlThumb, 1, 1, 1);
					}
				}
			}

			$this->items_thumbnail = null; // delete data to reduce memory

			//Only the the site with limitation or pagination...
			if (!empty($this->items)) {
				foreach ($this->items as $key => $value) {
					$fileOriginal = PhocaGalleryFile::getFileOriginal($value->filename);
					//Let the user know that the file doesn't exists and delete all thumbnails

					if (!JFile::exists($fileOriginal)) {
						$this->items[$key]->filename = JText::_( 'COM_PHOCAGALLERY_IMG_FILE_NOT_EXISTS' );
						$this->items[$key]->fileoriginalexist = 0;
					} else {
						//Create thumbnails small, medium, large
						$refresh_url 	= 'index.php?option=com_phocagallery&view=phocagalleryimgs';
						$fileThumb 		= PhocaGalleryFileThumbnail::getOrCreateThumbnail($value->filename, $refresh_url, 1, 1, 1);

						$this->items[$key]->linkthumbnailpath 	= $fileThumb['thumb_name_s_no_rel'];
						$this->items[$key]->fileoriginalexist = 1;
					}
				}
			}

			//Clean Thumbs Folder if there are thumbnail files but not original file
			if ($clean_thumbnails == 1) {
				PhocaGalleryFileFolder::cleanThumbsFolder();
			}
		}
	}

	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_('COM_PHOCAGALLERY_TITLE'),
			'a.filename'	=> JText::_('COM_PHOCAGALLERY_FILENAME'),
			'a.published' 	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'a.approved' 	=> JText::_('COM_PHOCAGALLERY_APPROVED'),
			'category_id' 	=> JText::_('COM_PHOCAGALLERY_CATEGORY'),
			'category_owner_id'=> JText::_('COM_PHOCAGALLERY_OWNER'),
			'uploadusername'=> JText::_('COM_PHOCAGALLERY_UPLOADED_BY'),
			'ratingavg' 		=> JText::_('COM_PHOCAGALLERY_RATING'),
			'a.hits' 		=> JText::_('COM_PHOCAGALLERY_HITS'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>
