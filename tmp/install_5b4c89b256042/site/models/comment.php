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
phocagalleryimport('phocagallery.access.access');
phocagalleryimport('phocagallery.ordering.ordering');

class PhocagalleryModelComment extends JModelLegacy
{
	function __construct() {
		parent::__construct();
		$app = JFactory::getApplication();
		$id = $app->input->get('id', 0, 'int');
		$this->setId((int)$id);
	}
	
	function setId($id) {
		$this->_id			= $id;
		$this->_data		= null;
	}
	
	function &getData() {
		if (!$this->_loadData()) {
			$this->_initData();
		}
		return $this->_data;
	}
	
	function _loadData() {
		
		if (empty($this->_data)) {
			$app	= JFactory::getApplication();
			$params				= $app->getParams();
			$image_ordering		= $params->get( 'image_ordering', 1 );
			$imageOrdering 		= PhocaGalleryOrdering::getOrderingString($image_ordering);

			$query = 'SELECT a.*, c.accessuserid as cataccessuserid, c.access as cataccess,'
					.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug,'
					.' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
					.' FROM #__phocagallery AS a'
					.' LEFT JOIN #__phocagallery_categories AS c ON c.id = a.catid'
					.' WHERE a.id = '.(int) $this->_id
					.$imageOrdering['output'];
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;	
		}
		return true;
	}
	
	function _initData() {
		if (empty($this->_data)) {
			$this->_data = '';
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function comment($data) {
		
		$row = $this->getTable('phocagallerycommentimgs', 'Table');
		
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');
		$row->published = 1;

		if (!$row->id) {
			$where = 'imgid = ' . (int) $row->imgid ;
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
	
}
?>