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
jimport('joomla.application.component.model');
phocagalleryimport('phocagallery.ordering.ordering');
phocagalleryimport('phocagallery.file.filethumbnail');

class PhocagalleryModelCategory extends JModelLegacy
{
	var $_id 				= null;
	var $_data 				= null;
	var $_category 			= null;
	var $_total 			= null;
	var $_context 			= 'com_phocagallery.category';
	private $_ordering		= null;

	function __construct() {

		$app	= JFactory::getApplication();
		parent::__construct();

		$config 			= JFactory::getConfig();
		$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
		$default_pagination	= $paramsC->get( 'default_pagination_category', '20' );
		$image_ordering		= $paramsC->get( 'image_ordering', 1 );
		$context			= $this->_context.'.';


		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest($context .'limit', 'limit', $default_pagination, 'int'));
		$this->setState('limitstart', $app->input->get('limitstart', 0, 'int'));
		// In case limit has been changed, adjust limitstart accordingly
		$this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));
		// Get the filter request variables

		$this->setState('filter.language',$app->getLanguageFilter());

		$this->setState('imgordering', $app->getUserStateFromRequest($context .'imgordering', 'imgordering', $image_ordering, 'int'));

		//$this->setState('filter_order', J Request::get Cmd('filter_order', 'ordering'));
		//$this->setState('filter_order_dir', J Request::get Cmd('filter_order_Dir', 'ASC'));

		$id = $app->input->get('id', 0, 'int');
		$this->setId((int)$id);
	}

	function setId($id) {
		$this->_id			= $id;
		$this->_category	= null;
	}

	/*
	 * IMAGES
	 */
	function getData( $rightDisplayDelete = 0, $tagId) {
		if (empty($this->_data)) {

			$query = $this->_buildQuery($rightDisplayDelete, $tagId);
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	 }

	function getTotal($rightDisplayDelete = 0, $tagId) {
		if (empty($this->_total)) {
			$query = $this->_buildQuery($rightDisplayDelete, $tagId, 1);
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination($rightDisplayDelete = 0, $tagId) {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new PhocaGalleryPaginationCategory( $this->getTotal($rightDisplayDelete, $tagId), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function getOrdering() {
		if(empty($this->_ordering)) {
			$this->_ordering = PhocaGalleryOrdering::renderOrderingFront($this->getState('imgordering'), 1);
		}
		return $this->_ordering;
	}

	function _buildQuery($rightDisplayDelete = 0, $tagId = 0, $count = 0) {

		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$params		= $app->getParams();
		//$image_ordering		= $params->get( 'image_ordering', 1 );

		$wheres		= array();

		$enable_overlib		= $params->get( 'enable_overlib', 0 );
		$imageOrdering 		= PhocaGalleryOrdering::getOrderingString($this->getState('imgordering'), 1);

		// Filter by language
		if ($this->getState('filter.language')) {
			$wheres[]	= ' a.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		// Link from comment system
		$cimgid			= $app->input->get( 'cimgid', 0, 'int');
		if ($cimgid > 0) {
			$wheres[]	= ' a.id = '.(int)$cimgid;
		}

		$selectUser = '';
		$leftUser	= '';
		if ($enable_overlib > 3) {
			$selectUser	= ', ua.id AS userid, ua.username AS username, ua.name AS usernameno';
			$leftUser 	= ' LEFT JOIN #__users AS ua ON ua.id = a.userid';
			//$whereUser	= ' AND ua.id ='.(int)$user->id;
		}

		if ($rightDisplayDelete == 0 ) {
			$published  = ' AND a.published = 1';
			$published  .= ' AND a.approved = 1';
		} else {
			$published  = '';
		}

		$leftTag = '';
		if ((int)$tagId > 0) {
			$leftTag = ' LEFT JOIN #__phocagallery_tags_ref AS t ON t.imgid = a.id';
		}
		if ((int)$tagId > 0) {
			$wheres[]	= ' t.tagid= '.(int)$tagId;
		} else {
			$wheres[]	= ' a.catid= '.(int)$this->_id;
		}

		$leftCat = ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid';


		if ($count == 1) {
			$query = 'SELECT a.id'
			//. $selectUser
			.' FROM #__phocagallery AS a'
			//.' LEFT JOIN #__phocagallery_img_votes_statistics AS r ON r.imgid = a.id'
			. $leftCat
			//. $leftUser
			. $leftTag
			. ' WHERE ' . implode( ' AND ', $wheres )
			. $published
			//. $imageOrdering['output'];
			. ' ORDER BY a.id';
		} else {
			$query = 'SELECT a.*, cc.alias AS catalias, cc.accessuserid AS cataccessuserid, cc.access AS cataccess,'
			. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as catslug'
			. $selectUser
			.' FROM #__phocagallery AS a'
			.' LEFT JOIN #__phocagallery_img_votes_statistics AS r ON r.imgid = a.id'
			. $leftCat
			. $leftUser
			. $leftTag
			. ' WHERE ' . implode( ' AND ', $wheres )
			. $published
			. $imageOrdering['output'];
		}

		return $query;
	}

	/*
	 * CATEGORY - get info about this category
	 */
	function getCategory() {

		$app	= JFactory::getApplication();
		if ($this->_id == 0) {
			return '';
		}
		if ($this->_loadCategory()) {

			$user = JFactory::getUser();
			if (!$this->_category->published) {
				//$mainframe->redirect(JRoute::_('index.php', false), JText::_("COM_PHOCAGALLERY_CATEGORY_IS_UNPUBLISHED"));

				throw new Exception(JText::_( "COM_PHOCAGALLERY_CATEGORY_IS_UNPUBLISHED" ), 404);
				exit;
			}
			if (!$this->_category->approved) {
				//$mainframe->redirect(JRoute::_('index.php', false), JText::_("COM_PHOCAGALLERY_CATEGORY_IS_UNAUTHORIZED"));// don't loop

				throw new Exception(JText::_( "COM_PHOCAGALLERY_ERROR_CATEGORY_IS_UNAUTHORIZED" ), 404);
				exit;
			}

			// USER RIGHT - ACCESS - - - - - -
			$rightDisplay	= 1;//default is set to 1 (all users can see the category)
			if (!empty($this->_category)) {
				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $this->_category->accessuserid, $this->_category->access, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
			}


			if ($rightDisplay == 0) {
				$uri 			= \Joomla\CMS\Uri\Uri::getInstance();
				$tmpl['pl']		= 'index.php?option=com_users&view=login&return='.base64_encode($uri->toString());
				$app->redirect(JRoute::_($tmpl['pl'], false), JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION'));
				exit;
			}
			// - - - - - - - - - - - - - - - -
		}
		return $this->_category;
	}

	function _loadCategory() {
		if (empty($this->_category)){

			//$query = 'SELECT c.*,' .

			$query = 'SELECT c.id, c.title, c.alias, c.description, c.published, c.approved, c.parent_id, c.deleteuserid, c.accessuserid, c.uploaduserid, c.owner_id, c.access, c.metakey, c.metadesc, c.latitude, c.longitude, c.zoom, c.geotitle, c.userfolder, c.image_id,' .
				' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $this->_id;
				' AND c.approved = 1';
			$this->_db->setQuery($query, 0, 1);
			$this->_category = $this->_db->loadObject();
		}
		return true;
	}


	/*
	 * PARENT CATEGORIES
	 */
	 function getParentCategory() {

		$parentCategory = 0;

		if (isset($this->_category->parent_id) && isset($this->_category->id)) {
			$app	= JFactory::getApplication();
			$params				= $app->getParams();
			$category_ordering	= $params->get( 'category_ordering', 1 );
			$categoryOrdering 	= PhocaGalleryOrdering::getOrderingString($category_ordering, 2);

			//$query = 'SELECT cc.*' .
			$query = 'SELECT cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access' .
				' FROM #__phocagallery_categories AS cc' .
				' WHERE cc.id = '.(int) $this->_category->parent_id.
				' AND cc.published = 1' .
				' AND cc.approved = 1' .
				' AND cc.id <> '.(int) $this->_category->id.
				$categoryOrdering['output'];
			$this->_db->setQuery($query, 0, 1);
			$parentCategory = $this->_db->loadObject();
		}

		return $parentCategory ;
	}

	/*
	 * SUB CATEGORIES
	 */
	function getSubCategory() {

		$app	= JFactory::getApplication();
		$params				= $app->getParams();
		$category_ordering	= $params->get( 'category_ordering', 1 );
		$categoryOrdering 	= PhocaGalleryOrdering::getOrderingString($category_ordering, 2);

		// Filter by language
		$whereLang = '';
		if ($this->getState('filter.language')) {
			$whereLang =  ' AND cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}

		//$query = 'SELECT c.*, COUNT(a.id) countimage' ... Cannot be used because get error if there is no image
		//$query = 'SELECT cc.*, a.filename, a.extm, a.exts, a.extw, a.exth'
		//$query = 'SELECT cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access, cc.image_id';

		//$query = 'SELECT DISTINCT cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access, cc.image_id, a.filename, a.extm, a.exts, a.extw, a.exth, a.extid';

		$query = 'SELECT DISTINCT cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access, cc.image_id, min(a.filename) as filename, min(a.extm) as extm, min(a.exts) as exts, min(a.extw) as extw, min(a.exth) as exth, min(a.extid) as extid';

		$query .= ' FROM #__phocagallery_categories AS cc'
			.' LEFT JOIN #__phocagallery AS a ON cc.id = a.catid'
			.' WHERE cc.parent_id = '.(int) $this->_id
			.' AND cc.published = 1'
			.' AND cc.approved = 1'
			.' AND cc.id <> '.(int) $this->_id
		//	.' AND a.published = 1'
		//	.' AND countimage > 0'
		//	.' AND (SELECT COUNT(a.id) AS countimage'
		//	.' FROM #__phocagallery as a'
        //	.' WHERE a.catid = c.id'
        //	.' AND a.published = 1) > 0'
			. $whereLang
			//.' GROUP BY cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access, cc.image_id, a.filename, a.extm, a.exts, a.extw, a.exth, a.extid'
			.' GROUP BY cc.id, cc.title, cc.alias, cc.published, cc.approved, cc.parent_id, cc.deleteuserid, cc.accessuserid, cc.uploaduserid, cc.access, cc.image_id'
			.$categoryOrdering['output'];



		$this->_db->setQuery($query);
		$subCategory = $this->_db->loadObjectList();

		return $subCategory;
	}

	// Called from SubCategories
	// Called from Category Controller
	function getCountItem($catid = 0, $rightDisplayDelete = 0) {

		if ($rightDisplayDelete == 0 ) {
			$published  = ' WHERE a.published = 1 AND a.approved = 1 AND a.catid = '.$catid;
		} else {
			$published  = ' WHERE a.catid = '.$catid;
		}

		$query = 'SELECT COUNT(a.id) FROM #__phocagallery AS a'
			. $published;
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 3');
			return false;
		}
		return $this->_db->loadRow();
	}


	/*
	 * Called from Controller
	 */
	function getCategoryIdFromImageId($id) {
		// id is id
		$query = 'SELECT a.catid' .
			' FROM #__phocagallery AS a' .
			' WHERE a.id = '. (int) $id;
		$this->_db->setQuery($query, 0, 1);
		$categoryId = $this->_db->loadObject();

		return $categoryId;
	}

	function getCategoryAlias($id) {
		// id is catid
		$query = 'SELECT c.alias' .
			' FROM #__phocagallery_categories AS c' .
			' WHERE c.id = '. (int) $id;
		$this->_db->setQuery($query, 0, 1);
		$categoryAlias = $this->_db->loadObject();

		return $categoryAlias;
	}

	/*
	 * Actions
	 */
	function delete($id = 0) {

		// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
		$queryd = 'SELECT filename as filename FROM #__phocagallery WHERE id ='.(int)$id;
		$this->_db->setQuery($queryd);
		$file_object = $this->_db->loadObjectList();

		$query = 'DELETE FROM #__phocagallery'
			. ' WHERE id ='.(int)$id;

		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}

		// Delete thumbnails - medium and large, small from server
		// All id we want to delete - gel all filenames
		foreach ($file_object as $key => $value) {
			//The file can be stored in other category - don't delete it from server because other category use it
			$querys = "SELECT id as id FROM #__phocagallery WHERE filename='".$value->filename."' ";
			$this->_db->setQuery($queryd);
			$same_file_object = $this->_db->loadObject();

			//same file in other category doesn't exist - we can delete it
			if (!$same_file_object){
				//Delete all thumbnail files but not original
				PhocaGalleryFileThumbnail::deleteFileThumbnail($value->filename, 1, 1, 1);
				PhocaGalleryFile::deleteFile($value->filename);
			}
		}
		return true;
	}

	function publish($id = 0, $publish = 1) {

		$user 	= JFactory::getUser();
		$query = 'UPDATE #__phocagallery'
			. ' SET published = '.(int) $publish
			. ' WHERE id = '.(int)$id
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )';

		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}
		return true;
	}

	function store($data, $return) {
		//If this file doesn't exists don't save it



		if (!PhocaGalleryFile::existsFileOriginal($data['filename'])) {
			$this->setError('File not exists');
			return false;
		}

		$data['imgorigsize'] 	= PhocaGalleryFile::getFileSize($data['filename'], 0);
		$data['format'] 		= PhocaGalleryFile::getFileFormat($data['filename']);

		//If there is no title and no alias, use filename as title and alias
		if (!isset($data['title']) || (isset($data['title']) && $data['title'] == '')) {
			$data['title'] = PhocaGalleryFile::getTitleFromFile($data['filename']);
		}

		if (!isset($data['alias']) || (isset($data['alias']) && $data['alias'] == '')) {
			$data['alias'] = PhocaGalleryFile::getTitleFromFile($data['filename']);
		}

		//clean alias name (no bad characters)
		//$data['alias'] = PhocaGalleryText::getAliasName($data['alias']);



		if((!isset($data['longitude']) || (isset($data['longitude']) && $data['longitude'] == '')) ||
         (!isset($data['latitude'])  || (isset($data['latitude'])  && $data['latitude'] ==''))) {
			phocagalleryimport('phocagallery.geo.geo');
			$coords = PhocaGalleryGeo::getGeoCoords($data['filename']);

			if (!isset($data['longitude']) || (isset($data['longitude']) && $data['longitude'] =='')){
				$data['longitude'] = $coords['longitude'];
			}

			if (!isset($data['latitude']) || (isset($data['latitude']) && $data['latitude'] =='')){
				$data['latitude'] = $coords['latitude'];
			}

			if ((!isset($data['zoom']) || (isset($data['zoom']) && $data['zoom'] == '')) && $data['longitude'] != '' && $data['latitude']  != ''){
				$data['zoom'] = PhocaGallerySettings::getAdvancedSettings('geozoom');
			}
		}

		$row = $this->getTable('phocagallery', 'Table');


		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
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

		//Create thumbnail small, medium, large
		$returnFrontMessage = PhocaGalleryFileThumbnail::getOrCreateThumbnail($row->filename, $return, 1, 1, 1, 1);

		if ($returnFrontMessage == 'Success') {
			return true;
		} else {
			return false;
		}

	}

	function rate($data) {
		$row = $this->getTable('phocagalleryvotes', 'Table');



		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');

		$row->published = 1;

		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Update the Vote Statistics
		phocagalleryimport('phocagallery.rate.ratecategory');
		if (!PhocaGalleryRateCategory::updateVoteStatistics( $data['catid'])) {
			return false;
		}

		return true;
	}

	function comment($data) {

		$row = $this->getTable('phocagallerycomments', 'Table');

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');
		$row->published = 1;

		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function hit($id) {

		$app	= JFactory::getApplication();
		$table = JTable::getInstance('phocagalleryc', 'Table');
		$table->hit($id);
		return true;
	}



	function getCountImages($catId, $published = 1) {
		$app	= JFactory::getApplication();

		$query = 'SELECT COUNT(i.id) AS countimg'
			.' FROM #__phocagallery AS i'
			.' WHERE i.catid = '. (int) $catId
			.' AND i.published ='.(int)$published
			.' AND i.approved = 1';
		$this->_db->setQuery($query, 0, 1);
		$countPublished = $this->_db->loadObject();

		return $countPublished;
	}

	function getHits($catId) {
		$app	= JFactory::getApplication();

		$query = 'SELECT cc.hits AS catviewed'
			.' FROM #__phocagallery_categories AS cc'
			.' WHERE cc.id = '. (int) $catId;
		$this->_db->setQuery($query, 0, 1);
		$categoryViewed = $this->_db->loadObject();

		return $categoryViewed;
	}

	function getStatisticsImages($catId, $order, $order2 = 'ASC', $limit = 3) {

		$query = 'SELECT i.*'
			.' FROM #__phocagallery AS i'
			.' WHERE i.catid = '.(int) $catId
			.' AND i.published = 1'
			.' AND i.approved = 1'
			.' ORDER BY '.$order.' '.$order2;

		$this->_db->setQuery($query, 0, $limit);
		$statistics = $this->_db->loadObjectList();
		$item = array();

			$count = 0;
			$total = count($statistics);
			for($i = 0; $i < $total; $i++) {
				$statisticsData[$count] 		= $statistics[$i] ;
				$item[$i] 						=& $statisticsData[$count];
				$item[$i]->slug 				= $item[$i]->id.':'.$item[$i]->alias;
				$item[$i]->item_type 			= "image";
				$extImg = PhocaGalleryImage::isExtImage($item[$i]->extid);
				if ($extImg) {
					$item[$i]->linkthumbnailpath = $item[$i]->extm;
				} else {
					$item[$i]->linkthumbnailpath  = PhocaGalleryImageFront::displayCategoryImageOrNoImage($item[$i]->filename, 'medium');
				}
				$count++;
			}
		return $item;
	}

}
?>
