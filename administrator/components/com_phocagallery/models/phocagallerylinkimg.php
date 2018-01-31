<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');


class PhocaGalleryCpModelPhocaGalleryLinkImg extends JModelLegacy
{

	var $_data 			= null;
	var $_total 		= null;
	var $_pagination 	= null;
	var $_context		= 'com_phocagallery.phocagallerylinkimg';

	function __construct() {
		parent::__construct();

		$app	= JFactory::getApplication();
		
		// Get the pagination request variables
		$limit	= $app->getUserStateFromRequest( $this->_context.'.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart	= $app->getUserStateFromRequest( $this->_context.'.limitstart', 'limitstart',	0, 'int' );
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData() {
		
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		
		if (!empty($this->_data)) {
			foreach ($this->_data as $key => $value) {	
				$fileOriginal = PhocaGalleryFile::getFileOriginal($value->filename);
				//Let the user know that the file doesn't exists
				
				if (!JFile::exists($fileOriginal)) {
					$this->_data[$key]->filename = JText::_( 'COM_PHOCAGALLERY_IMG_FILE_NOT_EXISTS' );
					$this->_data[$key]->fileoriginalexist = 0;
				} else {
					//Create thumbnails small, medium, large
					$refresh_url 	= 'index.php?option=com_phocagallery&view=phocagalleryimgs';
					$fileThumb 		= PhocaGalleryFileThumbnail::getOrCreateThumbnail($value->filename, $refresh_url, 1, 1, 1);
					
					$this->_data[$key]->linkthumbnailpath 	= $fileThumb['thumb_name_s_no_rel'];
					$this->_data[$key]->fileoriginalexist = 1;	
				}
			}
		}
		
		return $this->_data;
	}

	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	

	function _buildQuery() {
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.*, cc.title AS category, u.name AS editor, v.average AS ratingavg'
			. ' FROM #__phocagallery AS a '
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.catid '
			. ' LEFT JOIN #__phocagallery_img_votes_statistics AS v ON v.imgid = a.id'
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			. $where
			. $orderby;
		return $query;
	}
	


	function _buildContentOrderBy() {
		$app	= JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'',	'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY category, a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , category, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere() {
		$app	= JFactory::getApplication();
		$filter_state		= $app->getUserStateFromRequest( $this->_context.'.filter_state',	'filter_state',	'',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context.'.filter_catid',	'filter_catid',	0,	'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search', 'search', '', 'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		$where[] = 'a.published = 1';
		$where[] = 'a.approved = 1';
		$where[] = 'cc.published = 1';
		$where[] = 'cc.approved = 1';
		
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
}
?>