<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
phocagalleryimport('phocagallery.pagination.paginationusersubcat');
phocagalleryimport('phocagallery.pagination.paginationuserimage');
use Joomla\String\StringHelper;

class PhocagalleryModelUser extends JModelLegacy
{
	var $_data_subcat 			= null;
	var $_total_subcat	 		= null;
	var $_pagination_subcat 	= null;
	var $_context_subcat		= 'com_phocagallery.phocagalleryusersubcat';
	
	var $_data_image 			= null;
	var $_total_image 			= null;
	var $_pagination_image 		= null;
	var $_context_image			= 'com_phocagallery.phocagalleryuserimage';

	function __construct() {
		parent::__construct();

		$app	= JFactory::getApplication();
		// SubCategory
		$limit_subcat		= $app->getUserStateFromRequest( $this->_context_subcat.'.list.limitsubcat', 'limitsubcat', 20, 'int' );
		$limitstart_subcat 	= $app->input->get('limitstartsubcat', 0, 'int');
		$limitstart_subcat 	= ($limit_subcat != 0 ? (floor($limitstart_subcat / $limit_subcat) * $limit_subcat) : 0);
		$this->setState($this->_context_subcat.'.list.limitsubcat', $limit_subcat);
		$this->setState($this->_context_subcat.'.list.limitstartsubcat', $limitstart_subcat);

		// Image
		$limit_image		= $app->getUserStateFromRequest( $this->_context_image.'.list.limitimage', 'limitimage', 20, 'int' );
		$limitstart_image 	= $app->input->get('limitstartimage', 0, 'int');
		$limitstart_image 	= ($limit_image != 0 ? (floor($limitstart_image / $limit_image) * $limit_image) : 0);
		$this->setState($this->_context_image.'.list.limitimage', $limit_image);
		$this->setState($this->_context_image.'.list.limitstartimage', $limitstart_image);
	}
	
	function getDataSubcat($userId) {
		$app	= JFactory::getApplication();
		if (empty($this->_data_subcat)) {
			$query = $this->_buildQuerySubCat($userId);
			$this->_data_subcat = $this->_getList( $query );// We need all data because of tree
			// Order Categories to tree
			$text = ''; // test is tree name e.g. Category >> Subcategory
			$tree = array();
			$filter_catid		= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_catid',	'filter_catid',	0,	'int' );
			
			if ($filter_catid == 0) {
				$ownerMainCategory = $this->getOwnerMainCategory($userId);
				if (isset($ownerMainCategory->id)) {
					$filter_catid = $ownerMainCategory->id;
				}
			}
			$this->_data_subcat = $this->_categoryTree($this->_data_subcat, $tree,$filter_catid, $text, -1);
		}
		return $this->_data_subcat;
	}
	
	function getDataImage($userId) {
		
		if (empty($this->_data_image)) {
			$query = $this->_buildQueryImage($userId);
			$this->_data_image = $this->_getList($query, $this->getState($this->_context_image.'.list.limitstartimage'), $this->getState($this->_context_image.'.list.limitimage'));
		}
		return $this->_data_image;
	}
	
	/*
	* Is called after setTotal from the view
	*/
	function getTotalSubCat() {
		return $this->_total_subcat;
	}
	function setTotalSubCat($total) {
		$this->_total_subcat = (int)$total;
	}
	function getTotalImage($userId) {
		if (empty($this->_total_image)) {
			$query = $this->_buildQueryImage($userId);
			$this->_total_image = $this->_getListCount($query);
		}
		return $this->_total_image;
	}
	/*
	 * Is called after setTotal from the view
	 */
	function getPaginationSubCat($userId) {
		if (empty($this->_pagination_subcat)) {
			jimport('joomla.html.pagination');
			$this->_pagination_subcat = new PhocaGalleryPaginationUserSubCat( $this->getTotalSubCat(), $this->getState($this->_context_subcat.'.list.limitstartsubcat'), $this->getState($this->_context_subcat.'.list.limitsubcat') );
		}
		return $this->_pagination_subcat;
	}
	
	function getPaginationImage($userId) {
		if (empty($this->_pagination_image)) {
			jimport('joomla.html.pagination');
			$this->_pagination_image = new PhocaGalleryPaginationUserImage( $this->getTotalImage($userId), $this->getState($this->_context_image.'.list.limitstartimage'), $this->getState($this->_context_image.'.list.limitimage') );
		}
		return $this->_pagination_image;
	}
	
	function _buildQuerySubCat($userId) {
		$where		= $this->_buildContentWhereSubCat($userId);
		$orderby	= $this->_buildContentOrderBySubCat();
			
		$query = 'SELECT a.*, a.title AS category, c.countid AS countid,'
			. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug '
			. ' FROM #__phocagallery_categories AS a'
			. ' JOIN (SELECT c.parent_id, count(*) AS countid'
			. ' FROM #__phocagallery_categories AS c'
			.' GROUP BY c.parent_id ) AS c'
			.' ON a.parent_id = c.parent_id'
			. $where
			. $orderby;
			
		return $query;
	}
	
	function _buildQueryImage($userId) {
		$where		= $this->_buildContentWhereImage($userId);
		$orderby	= $this->_buildContentOrderByImage();

		$query = ' SELECT a.*, cc.title AS category,'
			. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug '
			. ' FROM #__phocagallery AS a '
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid '
			. $where
			. $orderby;
		return $query;
	}


	function _buildContentOrderBySubCat() {
		$app	= JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order',	'filter_order_subcat',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order_Dir',	'filter_order_Dir_subcat',	'',	'word' );
		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY  a.ordering '.$filter_order_Dir;
		}  else {
			$orderby 	= ' ORDER BY '.$filter_order . ' ' . $filter_order_Dir .  ' ';
		}
		return $orderby;
	}
	
	function _buildContentOrderByImage() {
		$app	= JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context_image.'.filter_order',	'filter_order_image',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_image.'.filter_order_Dir',	'filter_order_Dir_image',	'',	'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY category, a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , category, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhereSubCat($userId) {
		$app	= JFactory::getApplication();
		$filter_state		= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_state','filter_state_subcat','',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_catid','filter_catid_subcat',0,'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order','filter_order_subcat','a.ordering','cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_subcat.'.filter_order_Dir','filter_order_Dir_subcat',	'', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context_subcat.'.search', 'phocagallerysubcatsearch', '', 'string' );
		
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search				= StringHelper::strtolower( $search );

		$where = array();
		
		$where[] = 'a.parent_id <> 0';// no parent category
		$where[] = 'a.owner_id = '.(int)$userId;
		$where[] = 'a.owner_id > 0'; // Ignore -1

		/*if ($filter_catid > 0) {
			$where[] = 'a.parent_id = '.(int) $filter_catid;
		}*/
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
	
	function _buildContentWhereImage($userId) {
		$app	= JFactory::getApplication();
		$filter_state		= $app->getUserStateFromRequest( $this->_context_image.'.filter_state','filter_state_image','','word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context_image.'.filter_catid','filter_catid_image',0,'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context_image.'.filter_order','filter_order_image','a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context_image.'.filter_order_Dir','filter_order_Dir_image','', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context_image.'.search', 'phocagalleryimagesearch', '', 'string' );
		$search				= StringHelper::strtolower( $search );

		$where = array();
		
		$where[] = 'cc.owner_id = '.(int)$userId;
		$where[] = 'cc.owner_id > 0'; // Ignore -1
		
		
		if ($filter_catid > 0) {
			$where[] = 'a.catid = '.(int) $filter_catid;
		}
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
	
	/*
	 * Create category tree
	 */
	function _categoryTree( $data, $tree, $id = 0, $text='', $currentId) {		
		
		// Ordering
		$countItemsInCat 	= 0;
		foreach ($data as $key) {	
			$show_text =  $text . $key->title;
			
			static $iCT = 0;// All displayed items
	
			if ($key->parent_id == $id && $currentId != $id && $currentId != $key->id ) {	

				$tree[$iCT] 					= new JObject();
	
				// Ordering MUST be solved here
				if ($countItemsInCat > 0) {
					$tree[$iCT]->orderup				= 1;
				} else {
					$tree[$iCT]->orderup 				= 0;
				}
				
				if ($countItemsInCat < ($key->countid - 1)) {
					$tree[$iCT]->orderdown 				= 1;
				} else {
					$tree[$iCT]->orderdown 				= 0;
				}
				
				$tree[$iCT]->id 				= $key->id;
				$tree[$iCT]->title 				= $show_text;
				$tree[$iCT]->title_self 		= $key->title;
				$tree[$iCT]->parent_id			= $key->parent_id;
				$tree[$iCT]->owner_id			= $key->owner_id;
				$tree[$iCT]->name				= $key->name;
				$tree[$iCT]->alias				= $key->alias;
				$tree[$iCT]->image				= $key->image;
				$tree[$iCT]->section			= $key->section;
				$tree[$iCT]->image_position		= $key->image_position;
				$tree[$iCT]->description		= $key->description;
				$tree[$iCT]->published			= $key->published;
				$tree[$iCT]->approved			= $key->approved;
				$tree[$iCT]->editor				= $key->editor;
				$tree[$iCT]->ordering			= $key->ordering;
				$tree[$iCT]->access				= $key->access;
				$tree[$iCT]->count				= $key->count;
				$tree[$iCT]->params				= $key->params;
				$tree[$iCT]->checked_out		= $key->checked_out;
				$tree[$iCT]->slug				= $key->slug;
				$tree[$iCT]->hits				= $key->hits;
				$tree[$iCT]->accessuserid		= $key->accessuserid;
				$tree[$iCT]->uploaduserid		= $key->uploaduserid;
				$tree[$iCT]->deleteuserid		= $key->deleteuserid;
				$tree[$iCT]->userfolder			= $key->userfolder;
				$tree[$iCT]->latitude			= $key->latitude;
				$tree[$iCT]->longitude			= $key->longitude;
				$tree[$iCT]->zoom				= $key->zoom;
				$tree[$iCT]->geotitle			= $key->geotitle;
				$tree[$iCT]->link				= '';
				$tree[$iCT]->filename			= '';// Will be added in View (after items will be reduced)
				$tree[$iCT]->linkthumbnailpath	= '';
				$iCT++;
				$tree = $this->_categoryTree($data, $tree, $key->id, $show_text . " &raquo; ", $currentId );
				$countItemsInCat++;
			}	
		}
		return($tree);
	}
	
	/*
	 * AUTHOR - OWNER
	 * Get information about owner's category
	 */
	function getOwnerMainCategory($userId) {

		$query = 'SELECT cc.*'
			. ' FROM #__phocagallery_categories AS cc'
			. ' WHERE cc.owner_id = '.(int)$userId
			//. ' AND cc.id <> '.(int)$categoryId // Check other categories
			. ' AND cc.owner_id > 0' // Ignore -1
			. ' AND cc.parent_id = 0';
		
		$this->_db->setQuery( $query );
		$ownerMainCategoryId = $this->_db->loadObject();
		if (isset($ownerMainCategoryId->id)) {
			return $ownerMainCategoryId;
		}
		return false;
	}
	
	function isOwnerCategory($userId, $categoryId) {

		$query = 'SELECT cc.id'
			. ' FROM #__phocagallery_categories AS cc'
			. ' WHERE cc.owner_id = '.(int)$userId
			. ' AND cc.id = '.(int)$categoryId;
		
		$this->_db->setQuery( $query );
		
		$ownerCategoryId = $this->_db->loadObject();
		if (isset($ownerCategoryId->id)) {
			return true;
		}
		return false;
	}
	/*
	 * Return false or catid
	 * Check if owner category is catid
	 */
	function isOwnerCategorySubCat($userId, $categoryId) {

		$query = 'SELECT cc.id'
			. ' FROM #__phocagallery_categories AS cc'
			. ' LEFT JOIN #__phocagallery_categories AS s ON s.parent_id = cc.id'
			. ' WHERE cc.owner_id = '.(int)$userId
			. ' AND s.id = '.(int)$categoryId;
		
		
		$this->_db->setQuery( $query );
		$ownerCategoryId = $this->_db->loadObject();
		if (isset($ownerCategoryId->id)) {
			return $ownerCategoryId->id;
		}
		return false;
	}
	
	/*
	 * Return false or catid
	 * Check if owner category is catid
	 */
	
	function isOwnerCategoryImage($userId, $imageId) {

		$query = 'SELECT cc.id'
			. ' FROM #__phocagallery_categories AS cc'
			. ' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id'
			. ' WHERE cc.owner_id = '.(int)$userId
			. ' AND a.id = '.(int)$imageId;

		$this->_db->setQuery( $query );
		$ownerCategoryId = $this->_db->loadObject();
		if (isset($ownerCategoryId->id)) {
			return $ownerCategoryId->id;
		}
		return false;
	}
	

	
	function getCountUserSubCat($userId) {
		$query = 'SELECT count(cc.id) AS countid'
			. ' FROM #__phocagallery_categories AS cc'
			. ' WHERE cc.owner_id = '.(int)$userId
			. ' AND cc.parent_id <> 0';

		$this->_db->setQuery( $query );
		$categoryCount = $this->_db->loadObject();
		if (isset($categoryCount->countid)) {
			return $categoryCount->countid;
		}
		return 0;
	}
	
	function getCountUserImage($userId) {
		$query = 'SELECT count(a.id) AS count'
			. ' FROM #__phocagallery AS a'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid '
			. ' WHERE cc.owner_id = '.(int)$userId;

		$this->_db->setQuery( $query );
		$imageCount = $this->_db->loadObject();
		if (isset($imageCount->count)) {
			return $imageCount->count;
		}
		return 0;
	}
	
	function getSumUserImage($userId) {
		$query = 'SELECT sum(a.imgorigsize) AS sum'
			. ' FROM #__phocagallery AS a'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid '
			. ' WHERE cc.owner_id = '.(int)$userId;

		$this->_db->setQuery( $query );
		$imageSum = $this->_db->loadObject();
		if (isset($imageSum->sum)) {
			return $imageSum->sum;
		}
		return 0;
	}
	
	/*
	 * Publish SubCat
	 */
	 function publishsubcat($id = 0, $publish = 1) {
		
		$user 	= JFactory::getUser();
		$query = 'UPDATE #__phocagallery_categories AS c'
			. ' SET c.published = '.(int) $publish
			. ' WHERE c.id = '.(int)$id
			. ' AND c.owner_id = '.(int) $user->get('id');
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}
		return true;
	}
	
	/*
	 * Publish Image
	 */
	 function publishimage($id = 0, $publish = 1) {
		
		$user 	= JFactory::getUser();
		$query = 'UPDATE #__phocagallery AS a'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid '
			. ' SET a.published = '.(int) $publish
			. ' WHERE a.id = '.(int)$id
			. ' AND cc.owner_id = '.(int) $user->get('id');
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}
		return true;
	}
	
	/*
	 * Move Subcat
	 */
	
	function movesubcat($direction, $id) {
		$row = $this->getTable('phocagalleryc', 'Table');
		
		if (!$row->load((int)$id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' parent_id = '.(int) $row->parent_id.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		return true;
	}
	
	/*
	 * Move Image
	 */
	
	function moveimage($direction, $id) {
		$row = $this->getTable('phocagallery', 'Table');
		
		if (!$row->load((int)$id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' catid = '.(int) $row->catid.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		return true;
	}
	
	/*
	 * Save order subcat
	 */
	 function saveordersubcat($cid = array(), $order){
		$row = $this->getTable('phocagalleryc', 'Table');
		$groupings 	= array();

		// $catid is null -  update ordering values
		for( $i=0; $i < count($cid); $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->parent_id; // track categories
			
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('parent_id = '.(int) $group);
		}
		return true;
	}
	
	/*
	 * Save order Image
	 */
	 function saveorderimage($cid = array(), $order){
		$row = $this->getTable('phocagallery', 'Table');
		$groupings 	= array();

		// $catid is null -  update ordering values
		for( $i=0; $i < count($cid); $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->catid; // track categories
			
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}
		return true;
	}
	
	/*
	 * Delete
	 */
	function delete($id = 0, &$errorMsg) {
		
		$app	= JFactory::getApplication();
		
		$result = false;
		if ((int)$id > 0) {
			
			// FIRST - if there are subcategories - - - - - 	
			$query = 'SELECT c.id, c.name, c.title, COUNT( s.parent_id ) AS numcat'
			. ' FROM #__phocagallery_categories AS c'
			. ' LEFT JOIN #__phocagallery_categories AS s ON s.parent_id = c.id'
			. ' WHERE c.id ='.(int)$id
			. ' GROUP BY c.id';
			$this->_db->setQuery( $query );
				
			if (!($rows2 = $this->_db->loadObjectList())) {
				throw new Exception($this->_db->stderr('Load Data Problem') , 500);
				return false;
			}

			// Add new CID without categories which have subcategories (we don't delete categories with subcat)
			$err_cat = array();
			$cid 	 = array();
			foreach ($rows2 as $row) {
				if ($row->numcat == 0) {
					$cid[] = (int) $row->id;
				} else {
					$err_cat[] = $row->title;
				}
			}
			// - - - - - - - - - - - - - - -
			
			// Images with new cid - - - - -
			if (count( $cid )) {
				JArrayHelper::toInteger($cid);
				$cids = implode( ',', $cid );
			
				// Select id's from phocagallery tables. If the category has some images, don't delete it
				$query = 'SELECT c.id, c.name, c.title, COUNT( s.catid ) AS numcat'
				. ' FROM #__phocagallery_categories AS c'
				. ' LEFT JOIN #__phocagallery AS s ON s.catid = c.id'
				. ' WHERE c.id IN ( '.$cids.' )'
				. ' GROUP BY c.id';
			
				$this->_db->setQuery( $query );

				if (!($rows = $this->_db->loadObjectList())) {
				
					throw new Exception($this->_db->stderr('Load Data Problem') , 500);
				return false;
				}
				
				$err_img = array();
				$cid 	 = array();
				foreach ($rows as $row) {
					if ($row->numcat == 0) {
						$cid[] = (int) $row->id;
					} else {
						$err_img[] = $row->title;
					}
				}
				
				if (count( $cid )) {
					$cids = implode( ',', $cid );
					$query = 'DELETE FROM #__phocagallery_categories'
					. ' WHERE id IN ( '.$cids.' )';
					$this->_db->setQuery( $query );
					if (!$this->_db->query()) {
				
						throw new Exception($this->_db->stderr('Delete Data Problem') , 500);
				return false;
					}

				}
			}
			
			// There are some images in the category - don't delete it
			$msg = '';
			if (count( $err_cat ) || count( $err_img )) {
				if (count( $err_cat )) {
					$cids_cat = implode( ", ", $err_cat );
					$msg .= JText::sprintf( 'COM_PHOCAGALLERY_ERROR_DELETE_CONTAIN_CAT', $cids_cat );
				}
				
				if (count( $err_img )) {
					$cids_img = implode( ", ", $err_img );
					$msg .= JText::sprintf( 'COM_PHOCAGALLERY_ERROR_DELETE_CONTAIN_IMG', $cids_img );
				}
				if ($msg != '') {
					$errorMsg = $msg;
				}
				return false;
			}
		}
		
		return true;
	}
	
	function deleteimage($id = 0, &$errorMsg) {
		
		// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
		$queryd = 'SELECT filename as filename FROM #__phocagallery WHERE id ='.(int)$id;
		
		$this->_db->setQuery($queryd);
		$file_object = $this->_db->loadObjectList();
		
		if(!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}

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
			if(!$this->_db->query()) {
				$this->setError('Database Error 2');
				return false;
			}
			
			//same file in other category doesn't exist - we can delete it
			if (!$same_file_object){
				//Delete all thumbnail files but not original
				PhocaGalleryFileThumbnail::deleteFileThumbnail($value->filename, 1, 1, 1);
				PhocaGalleryFile::deleteFile($value->filename);
			}
		}
		return true;
	}
	
	
	/*
	 * Pagination Subcategory
	 */
	function getCountItemSubCat($id = 0, $userId, $catid = 0) {
	
		$where = ' WHERE c.id ='.(int)$id;
		if ((int)$catid > 0) {
			// After remove we don't know id, so we take the catid
			$where =' WHERE c.parent_id ='.(int)$catid;
		}
	
		$query = 'SELECT COUNT( c.id ) AS numcat'
				. ' FROM #__phocagallery_categories AS c'
				. ' LEFT JOIN #__phocagallery_categories AS s ON s.parent_id = c.id'
				. $where
				. ' AND c.owner_id ='.(int)$userId
				. ' GROUP BY c.parent_id';



		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 3');
			return false;
		}
		return $this->_db->loadRow();
	}
	
	/*
	 * Pagination Image
	 */
	function getCountItemImage($id = 0, $userId, $catid = 0) {
	
		$where = ' WHERE a.id ='.(int)$id;
		if ((int)$catid > 0) {
			// After remove we don't know id, so we take the catid
			$where =' WHERE a.catid ='.(int)$catid;
		}
		
		$query = 'SELECT COUNT( a.catid ) AS numimg'
			. ' FROM #__phocagallery AS a'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid'
			. $where
			. ' AND cc.owner_id ='.(int)$userId
			. ' GROUP BY a.catid';
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 3');
			return false;
		}
		return $this->_db->loadRow();
	}
	
	function getCategoryList($userId) {
		$query = 'SELECT cc.title AS text, cc.id AS value, cc.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS cc'
	//	. ' WHERE cc.published = 1' 
		. ' WHERE cc.owner_id = '.(int)$userId
		. ' AND cc.owner_id > 0'; // Ignore -1
		$this->_db->setQuery( $query );
		$categories = $this->_db->loadObjectList();
		
		return $categories;
	}
	
	/*
	 * EDIT - Subcategory - get info about the subcategory
	 */
	
	function getCategory($id, $userId) {
		$query = 'SELECT cc.id, cc.title, cc.description'
		. ' FROM #__phocagallery_categories AS cc'
		. ' WHERE cc.owner_id = '.(int)$userId
		. ' AND cc.owner_id > 0' // Ignore -1
		. ' AND cc.id = '.(int)$id;
		$this->_db->setQuery( $query );
		$category = $this->_db->loadObject();
		if(isset($category->id)) {
			return $category;
		}
		return false;
	}
	
	/*
	 * EDIT - Image - get info about the image
	 */ 
	
	function getImage($id, $userId) {
		$query = 'SELECT a.id, a.title, a.description'
		. ' FROM #__phocagallery AS a'
		. ' LEFT JOIN #__phocagallery_categories AS c ON c.id = a.catid'
		. ' WHERE c.owner_id = '.(int)$userId
		. ' AND c.owner_id > 0' // Ignore -1
		. ' AND a.id = '.(int)$id;
		$this->_db->setQuery( $query );
		$image = $this->_db->loadObject();
		if(isset($image->id)) {
			return $image;
		}
		return false;
	}
	
	/*
	 * Add Category, Add Subcategory, Edit Category, Edit Subcategory
	 */
	function store($data) {
		
		if ($data['alias'] == '') {
			$data['alias'] = $data['title'];
		}
		//$data['alias'] 	= PhocaGalleryText::getAliasName($data['alias']);
		//$data['access']	= 1;
		$row = $this->getTable('phocagalleryc', 'Table');
		
		if(isset($data['id']) && $data['id'] > 0) {
			if (!$row->load($data['id'])) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->date) {
			$row->date = gmdate('Y-m-d H:i:s');
		}
		
		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'parent_id = ' . (int) $row->parent_id ;
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row->id;
	}
	
	/*
	 * Add Image
	 */
	 function storeimage($data, $return, $edit = false) {
		
		if (!$edit) {
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
			
			$data['alias'] = $data['title'];

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
			
			
		} else {
			$data['alias'] = $data['title'];//PhocaGalleryText::getAliasName($data['title']);
		}
		
		$row = $this->getTable('phocagallery', 'Table');
		
		
		if(isset($data['id']) && $data['id'] > 0) {
			if (!$row->load($data['id'])) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
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
		
		if(!$edit) {
			//Create thumbnail small, medium, large	
			$returnFrontMessage = PhocaGalleryFileThumbnail::getOrCreateThumbnail($row->filename, $return, 1, 1, 1, 1);
			
			if ($returnFrontMessage == 'Success') {
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin('phocagallery');
				$results = $dispatcher->trigger( 'onStoreNewImage', array($row->id, $data['title']) );		
				return true;
			} else {
				return false;
			}
		} else {
			if (isset($row->id)) {
				return $row->id;
			} else {
				return false;
			}
		}
	}
	
	
	/*
	 * Get AVATAR
	 */
	 function getUserAvatar($userId) {
		$query = 'SELECT a.*'
		. ' FROM #__phocagallery_user AS a'
		. ' WHERE a.userid = '.(int)$userId;
		$this->_db->setQuery( $query );
		$avatar = $this->_db->loadObject();
		if(isset($avatar->id)) {
			return $avatar;
		}
		return false;
	}
	
	/*
	 * Store Avatar
	 */
	function storeuser($data) {

		$row = $this->getTable('phocagalleryuser', 'Table');
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// if new item, order last in appropriate group
		if (!$row->id) {
		
			$row->ordering = $row->getNextOrder( );
		}
		
		
		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row->id;
	}
	
	function removeAvatarFromDisc($avatar) {
		jimport( 'joomla.filesystem.file' );
		phocagalleryimport('phocagallery.path.path');
		phocagalleryimport('phocagallery.file.file');
		$path				= PhocaGalleryPath::getPath();
		$pathAvatarAbs[]	= $path->avatar_abs  . $avatar;
		$pathAvatarAbs[]	= $path->avatar_abs  .'thumbs/phoca_thumb_l_'. $avatar;
		$pathAvatarAbs[]	= $path->avatar_abs  .'thumbs/phoca_thumb_m_'. $avatar;
		$pathAvatarAbs[]	= $path->avatar_abs  .'thumbs/phoca_thumb_s_'. $avatar;
		
		foreach ($pathAvatarAbs as $value) {
			if (JFile::exists($value)){
				JFile::delete($value);
			}
		}
		return true;
	}
}
?>