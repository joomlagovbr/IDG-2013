<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
phocagalleryimport('phocagallery.file.filefolderlist');
setlocale(LC_ALL, 'C.UTF-8', 'C');

class PhocaGalleryCpModelPhocaGalleryM extends JModelAdmin
{
	protected $option 			= 'com_phocagallery';
	protected $text_prefix		= 'com_phocagallery';
	public 		$typeAlias 		= 'com_phocagallery.phocagallerym';

	protected $imageCount		= 0;
	protected $categoryCount	= 0;
	protected $firstImageFolder	= '';

	function __construct() {
		$this->imageCount 		= 0;
		$this->categoryCount 	= 0;
		$this->firstImageFolder	= '';
		parent::__construct();
	}

	public function getForm($data = array(), $loadData = true) {

		$form 	= $this->loadForm('com_phocagallery.phocagallerym', 'phocagallerym', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_phocagallery.phocagalleryimg.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_phocagallery.phocagalleryimg.'.(int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'PhocaGallery', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}



	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocagallerym.edit.phocagallerym.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}








	/*function &getData() {
		$this->_initData();
		return $this->_data;
	}*/

	/*
	 * - If we add only image, then the thumbnail creation will be run
	 * - If we add folder with image, we need to know the first image in the folder
	 *   to run thumbnail creating (PhocaGalleryFileThumbnail::getOrCreateThumbnail())
	 */
	function setFirstImageFolder($filename) {
		$this->firstImageFolder = $filename;
	}

	function setImageCount($count) {
		$this->imageCount = $this->imageCount + $count;
	}

	function setCategoryCount($count) {
		$this->categoryCount = $this->categoryCount + $count;
	}


	function save($data) {
		$app	= JFactory::getApplication();

		$foldercid	= JFactory::getApplication()->input->get('foldercid', array(), 'raw');
		$cid	= JFactory::getApplication()->input->get('cid', 0, 'raw');
		$data	= JFactory::getApplication()->input->get('jform', array(0), 'post', 'array');


		if(isset($foldercid)) {
			$data['foldercid']	= $foldercid;
		} else {
			$data['foldercid']	= array();
		}
		if(isset($cid)) {
			$data['cid']		= $cid;
		} else {
			$data['cid']	= array();
		}

		if (isset($data['catid']) && (int)$data['catid'] > 0) {
			$data['catid']		= (int)$data['catid'];
		} else {
			$data['catid']		= 0;
		}

		//Params
		$params				= JComponentHelper::getParams( 'com_phocagallery' );
		$clean_thumbnails 	= $params->get( 'clean_thumbnails', 0 );

		//Get folder variables from Helper
		$path 			= PhocaGalleryPath::getPath();
		$origPath 		= $path->image_abs;
		$origPathServer = str_replace('\\', '/', $path->image_abs);

		// Cache all existing categories
		$query = 'SELECT id, title, parent_id'
	    . ' FROM #__phocagallery_categories' ;
		$this->_db->setQuery( $query );
	    $existingCategories = $this->_db->loadObjectList() ;

		// Cache all existing images
		$query = 'SELECT catid, filename'
	    . ' FROM #__phocagallery';
		$this->_db->setQuery( $query );
	    $existingImages = $this->_db->loadObjectList() ;

		$result = new stdClass();
		$result->category_count = 0;
		$result->image_count 	= 0;

		// Category will be saved - Images will be saved in recursive function
		if (isset($data['foldercid'])) {
			foreach ($data['foldercid'] as $foldername) {
				if (strlen($foldername) > 0) {
					$fullPath 		= $path->image_abs.$foldername;
					$result 		= $this->_createCategoriesRecursive( $origPathServer, $fullPath, $existingCategories, $existingImages, $data['catid'], $data );
				}
			}
		}

		// Only Imagees will be saved
		if (isset($data['cid']) && !empty($data['cid'])) {
			foreach ($data['cid'] as $filename) {
				if ($filename) {
					$ext = strtolower(JFile::getExt($filename));
					// Don't create thumbnails from defined files (don't save them into a database)...
					$dontCreateThumb	= PhocaGalleryFileThumbnail::dontCreateThumb ($filename);
					if ($dontCreateThumb == 1) {
						$ext = '';// WE USE $ext FOR NOT CREATE A THUMBNAIL CLAUSE
					}
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg' || $ext == 'webp') {

						$row = $this->getTable('phocagallery');

						$datam = array();
						$datam['published']		= $data['published'];
						$datam['catid']			= $data['catid'];
						$datam['approved']		= $data['approved'];
						$datam['language']		= $data['language'];
						$datam['filename']		= $filename;

						if ($data['title']	!= '') {
							$datam['title']		= $data['title'];
						} else {
							$datam['title']		= PhocaGalleryFile::getTitleFromFile($filename);
						}

						if ($data['alias']	!= '') {
							$datam['alias']		= $data['alias'];
						} else {
							$datam['alias']		= $datam['title'];//PhocaGalleryText::getAliasName($datam['title']);
						}

						$datam['imgorigsize'] 	= PhocaGalleryFile::getFileSize($datam['filename'], 0);
						$datam['format'] 		= PhocaGalleryFile::getFileFormat($datam['filename']);


						// Geo
						phocagalleryimport('phocagallery.geo.geo');
						$coords = PhocaGalleryGeo::getGeoCoords($datam['filename']);
						$datam['longitude'] = $coords['longitude'];
						$datam['latitude'] = $coords['latitude'];
						if ($datam['latitude'] != '' && $datam['longitude'] != ''){
							$datam['zoom'] = PhocaGallerySettings::getAdvancedSettings('geozoom');
						}



						// Save
						// Bind the form fields to the Phoca gallery table
						if (!$row->bind($datam)) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						// Create the timestamp for the date
						$row->date = gmdate('Y-m-d H:i:s');

						// if new item, order last in appropriate group

						if (!$row->id) {
							$where = 'catid = ' . (int) $row->catid ;
							$row->ordering = $row->getNextOrder( $where );
						}


						// Make sure the Phoca gallery table is valid
						if (!$row->check()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						// Store the Phoca gallery table to the database
						if (!$row->store()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}
						$result->image_count++;
					}
				}
			}
			$this->setImageCount($result->image_count);

		}


		// - - - - - - - - - - - - - - - - -
		//Create thumbnail small, medium, large
		//file - abc.img, file_no - folder/abc.img
		//Get folder variables from Helper
		//	$refresh_url 	= 'index.php?option=com_phocagallery&task=phocagalleryimg.thumbs';

		$msg = $this->categoryCount. ' ' .JText::_('COM_PHOCAGALLERY_CATEGORIES_ADDED') .', '.$this->imageCount. ' ' . JText::_('COM_PHOCAGALLERY_IMAGES_ADDED');
		$app->enqueueMessage($msg);
		$app->redirect(JRoute::_('index.php?option=com_phocagallery&view=phocagalleryimgs&countimg='.$this->imageCount.'&imagesid='.md5(time()), false));

		// Only image without folder was added to the system
		if (isset($row->filename) && $row->filename != '') {
			$fileNameThumb 	= $row->filename;
		} else if ($this->firstImageFolder != '') {
			$fileNameThumb	= $this->firstImageFolder;
		} else {
			$fileNameThumb == '';
		}

		if ($fileNameThumb != '') {

			$refresh_url 	= 'index.php?option=com_phocagallery&view=phocagalleryimgs&countimg='.$this->imageCount;
			$fileThumb 		= PhocaGalleryFileThumbnail::getOrCreateThumbnail($fileNameThumb, $refresh_url, 1, 1, 1);
		}

		//Clean Thumbs Folder if there are thumbnail files but not original file
		if ($clean_thumbnails == 1) {
			PhocaGalleryFolder::cleanThumbsFolder();
		}
		// - - - - - - - - - - - - - - - - -

		return true;

	}

	protected function _getCategoryId( &$existingCategories, &$title, $parentId ) {
	    $id = -1 ;
		$i 	= 0;
		$count = count($existingCategories);
		while ( $id == -1 && $i < $count ) {

			if ( $existingCategories[$i]->title == $title &&
			     $existingCategories[$i]->parent_id == $parentId ) {
				$id = $existingCategories[$i]->id ;
			}
			$i++;
		}
		return $id ;
	}

	protected function _ImageExist( &$existing_image, &$filename, $catid ) {
	    $result = false ;
		$i 		= 0;
		$count = count($existing_image);

		while ( $result == false && $i < $count ) {
			if ( $existing_image[$i]->filename == $filename &&
			     $existing_image[$i]->catid == $catid ) {
				$result = true;
			}
			$i++;
		}
		return $result;
	}

	protected function _addAllImagesFromFolder(&$existingImages, $category_id, $fullPath, $rel_path, $data = array()) {
		$count = 0;
		$fileList = JFolder::files( $fullPath );
		natcasesort($fileList);
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img

		if ($fileList !== false) {
			foreach ($fileList as $filename) {
			    $storedfilename	= ltrim(str_replace('\\', '/', JPath::clean($rel_path . '/'. $filename )), '/');
				$ext = strtolower(JFile::getExt($filename));
				// Don't create thumbnails from defined files (don't save them into a database)...
				$dontCreateThumb	= PhocaGalleryFileThumbnail::dontCreateThumb ($filename);
				if ($dontCreateThumb == 1) {
					$ext = '';// WE USE $ext FOR NOT CREATE A THUMBNAIL CLAUSE
				}
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg' || $ext == 'webp') {
					if (JFile::exists($fullPath. '/'. $filename) &&
					    substr($filename, 0, 1) != '.' &&
						strtolower($filename) !== 'index.html' &&
						!$this->_ImageExist($existingImages, $storedfilename, $category_id) ) {

						$row = $this->getTable('phocagallery');

						$datam = array();
						$datam['published']		= $data['published'];
						$datam['catid']			= $category_id;
						$datam['filename']		= $storedfilename;
						$datam['approved']		= $data['approved'];
						$datam['language']		= $data['language'];
						if ($data['title']	!= '') {
							$datam['title']		= $data['title'];
						} else {
							$datam['title']		= PhocaGalleryFile::getTitleFromFile($filename);
						}

						if ($data['alias']	!= '') {
							$datam['alias']		= $data['alias'];
						} else {
							$datam['alias']		= $datam['title'];//PhocaGalleryText::getAliasName($datam['title']);
						}
						$datam['imgorigsize'] 	= PhocaGalleryFile::getFileSize($datam['filename'], 0);
						$datam['format'] 		= PhocaGalleryFile::getFileFormat($datam['filename']);
						// Geo
						phocagalleryimport('phocagallery.geo.geo');
						$coords = PhocaGalleryGeo::getGeoCoords($datam['filename']);
						$datam['longitude'] = $coords['longitude'];
						$datam['latitude'] = $coords['latitude'];
						if ($datam['latitude'] != '' && $datam['longitude'] != ''){
							$datam['zoom'] = PhocaGallerySettings::getAdvancedSettings('geozoom');
						}

						// Save
						// Bind the form fields to the Phoca gallery table
						if (!$row->bind($datam)) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						// Create the timestamp for the date
						$row->date = gmdate('Y-m-d H:i:s');

						// if new item, order last in appropriate group
						if (!$row->id) {
							$where = 'catid = ' . (int) $row->catid ;
							$row->ordering = $row->getNextOrder( $where );
						}

						// Make sure the Phoca gallery table is valid
						if (!$row->check()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						// Store the Phoca gallery table to the database
						if (!$row->store()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						if ($this->firstImageFolder == '') {
							$this->setFirstImageFolder($row->filename);
						}

						$image 				= new JObject();
					    $image->filename 	= $storedfilename ;
					    $image->catid 		= $category_id;
					    $existingImages[] 	= &$image ;
						$count++ ;
					}
				}
			}
		}

	//	$this->setImageCount($count);
		return $count;
	}

	protected function _createCategoriesRecursive(&$origPathServer, $path, &$existingCategories, &$existingImages, $parentId = 0, $data = array() ) {

		$totalresult = new stdClass();
		$totalresult->image_count 		= 0 ;
		$totalresult->category_count	= 0 ;

		$categoryName 	= basename($path);
		$id 			= $this->_getCategoryId( $existingCategories, $categoryName, $parentId ) ;
		$category 		= null;

		// Full path: eg. "/home/www/joomla/images/categ/subcat/"
		$fullPath	   	= str_replace('\\', '/', JPath::clean('/' . $path));
		// Relative path eg "categ/subcat"
		$relativePath 	= str_replace($origPathServer, '', $fullPath);

		// Category doesn't exist
		if ( $id == -1 ) {
		  $row = $this->getTable('phocagalleryc');
		  $row->published 	= $data['published'];
		  $row->approved	= $data['approved'];
		  $row->language	= $data['language'];
		  $row->parent_id 	= $parentId;
		  $row->title 		= $categoryName;

		  // Create the timestamp for the date
		  $row->date 		= gmdate('Y-m-d H:i:s');
		 // $row->alias 		= PhocaGalleryText::getAliasName($categoryName);
		  $row->userfolder	= ltrim(str_replace('\\', '/', JPath::clean($relativePath )), '/');
		  $row->ordering 	= $row->getNextOrder( "parent_id = " . $this->_db->Quote($row->parent_id) );

		  if (!$row->check()) {

			throw new Exception($db->stderr('Check Problem'), 500);
		  }

		  if (!$row->store()) {

			throw new Exception($db->stderr('Store Problem'), 500);
		  }

		  $category 			= new JObject();
		  $category->title 		= $categoryName ;
		  $category->parent_id 	= $parentId;
		  $category->id 		= $row->id;
		  $totalresult->category_count++;
		  $id = $category->id;
		  $existingCategories[] = &$category ;
		  $this->setCategoryCount(1);//This subcategory was added
		}



		// Add all images from this folder
		$totalresult->image_count += $this->_addAllImagesFromFolder( $existingImages, $id, $path, $relativePath, $data );
		$this->setImageCount($totalresult->image_count);

		// Do sub folders
		$parentId 		= $id;
		$folderList 	= JFolder::folders( $path, $filter = '.', $recurse = false, $fullpath = true, $exclude = array('thumbs') );
		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder) {
				//$this->setCategoryCount(1);//This subcategory was added
				$folderName = $relativePath .'/' . str_replace($origPathServer, '', $folder);
				$result = $this->_createCategoriesRecursive( $origPathServer, $folder, $existingCategories, $existingImages, $id , $data);
				$totalresult->image_count += $result->image_count ;
				$totalresult->category_count += $result->category_count ;
			}
		}
		return $totalresult ;
	}


	/*
	 * Images
	 */
	function getFolderState($property = null) {
		static $set;

		if (!$set) {
			$folder = JFactory::getApplication()->input->get( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImages() {
		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,0,0,$refreshUrl);
		return $list['Images'];
	}

	function getFolders() {
		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,0,0,$refreshUrl);
		return $list['folders'];
	}

}
?>
