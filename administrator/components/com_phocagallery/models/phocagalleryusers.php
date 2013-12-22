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
jimport('joomla.application.component.modellist');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
phocagalleryimport( 'phocagallery.file.filefolder' );

class PhocaGalleryCpModelPhocaGalleryUsers extends JModelList
{

	protected	$option 		= 'com_phocagallery';
	public 		$context		= 'com_phocagallery.phocagalleryusers';
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'username', 'ua.username',
				
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'category_id', 'category_id',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'language', 'a.language',
				'hits', 'a.hits',
				'published','a.published',
				'authorized','a.approved'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
/*
		$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);
*/
		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);
/*
		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null);
		$this->setState('filter.category_id', $categoryId);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
*/
		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocagallery');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('uc.username', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		//$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		

		return parent::getStoreId($id);
	}
	
	protected function getListQuery()
	{
		/*
		$query = ' SELECT a.*, us.name AS username, u.name AS editor, c.countcid, i.countiid'
			. ' FROM #__phocagallery_user AS a '
		
			   . ' LEFT JOIN #__users AS us ON us.id = a.userid '
			   . ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			
			. ' LEFT JOIN (SELECT  c.owner_id, c.id, count(*) AS countcid'
			. ' FROM #__phocagallery_categories AS c'
			. ' GROUP BY c.owner_id) AS c '
			. ' ON a.userid = c.owner_id'
			
			. ' LEFT JOIN (SELECT i.catid, uc.userid AS uid, count(i.id) AS countiid'
			. ' FROM #__phocagallery AS i'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = i.catid'
			. ' LEFT JOIN #__phocagallery_user AS uc ON uc.userid = cc.owner_id'
			//. ' WHERE cc.owner_id = uc.userid'
			//. ' AND cc.id = i.catid'
			. ' GROUP BY uc.userid'
			. ' ) AS i '
			. ' ON i.uid = c.owner_id'
			
			. $where
			. $orderby;
		*/
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__phocagallery_user AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '#__languages AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('ua.id AS userid, ua.username AS username, ua.name AS usernameno');
		$query->join('LEFT', '#__users AS ua ON ua.id=a.userid');
		
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		$query->select('c.countcid');
		$query->join('LEFT', '(SELECT  c.owner_id, c.id, count(*) AS countcid'
			. ' FROM #__phocagallery_categories AS c'
			. ' GROUP BY c.owner_id) AS c '
			. ' ON a.userid = c.owner_id ');
			
		$query->select('i.countiid');	
		$query->join('LEFT', '(SELECT i.catid, uc.userid AS uid, count(i.id) AS countiid'
			. ' FROM #__phocagallery AS i'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = i.catid'
			. ' LEFT JOIN #__phocagallery_user AS uc ON uc.userid = cc.owner_id'
			. ' GROUP BY uc.userid'
			. ' ) AS i '
			. ' ON i.uid = c.owner_id');

/*		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
*/

		// Filter by access level.
	/*	if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}*/


		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.
		/*$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int) $categoryId);
		}*/

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('( ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			}
		}
		
		$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		/*if ($orderCol == 'a.ordering' || $orderCol == 'username') {
			$orderCol = 'username '.$orderDirn.', a.ordering';
		}*/
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));
		return $query;
	}
	
	
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
}
?>