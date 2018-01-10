<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modellist' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
phocagalleryimport( 'phocagallery.file.filefolder' );

class PhocaGalleryCpModelPhocaGalleryEfs extends JModelList
{
	protected	$option 		= 'com_phocagallery';
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'type', 'a.type',
				'published', 'a.published',
				'ordering', 'a.ordering',
				'language', 'a.language'
			);
		}

		parent::__construct($config);
	}
	
	
	protected function populateState($ordering = null, $direction = null)
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

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null);
		$this->setState('filter.category_id', $categoryId);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocagallery');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.ordering', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');

		return parent::getStoreId($id);
	}
	
	
	protected function getListQuery()
	{
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
		$query->from('`#__phocagallery_styles` AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.type = ' . (int) $categoryId);
		}

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
				$query->where('( a.title LIKE '.$search.' OR a.filename LIKE '.$search.')');
			}
		}
		
	//	$query->group('a.id');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'a.type '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		
		return $query;
	}
	
	protected function getItemsCheck() {
		$db = JFactory::getDBO();
		$query = 'SELECT a.id, a.filename, a.type'
		.' FROM #__phocagallery_styles AS a';
		$db->setQuery($query);
		$items = $db->loadObjectList();
		return $items;
	}
	
	public function checkItems() {
	
		$db = JFactory::getDBO();
		$files = $this->getFiles();
		$items = $this->getItemsCheck();
		if (!empty($files)) {
			foreach ($files as $fk => $fv) {
				if ($fv->exists) {
					$exists = 0;
					foreach ($items as $ik => $iv) {
						if ($fv->filename == $iv->filename && $fv->type == $iv->type){
							// we cannot break because there are two types
							$exists = 1;
						}
					}
					if ($exists == 0) {
						
						$query = 'SELECT a.ordering'
								.' FROM #__phocagallery_styles AS a'
								.' WHERE a.type = '.(int) $fv->type;
						$db->setQuery($query, 0, 1);
						$ordO = $db->loadObject();
						if (!isset($ordO->ordering)) {
							$ordering = 1;
						} else {
							$ordering = (int)$ordO->ordering + 1;
						}
						
						$title 		= ucfirst(str_replace('.css', '', htmlspecialchars($fv->filename)));
						$published	= 1;
						$query = 'INSERT into #__phocagallery_styles'
							.' (id, title, filename, type, published, ordering, language)'
							.' VALUES (null, '. $db->quote($title)
							.' , '.$db->quote(htmlspecialchars($fv->filename))
							.' , '.(int)$fv->type
							.' , '.(int)$published
							.' , '.(int)$ordering
							.' , '.$db->quote('*')
							.')';
						$db->setQuery($query);
						
						if (!$db->query()) {
							$this->setError('Database Error - Inserting CSS Style');
							return false;
						}
					}
				
				}
			
			}
		
		}
		return true;
	
	}
	
	public function getFiles()
	{
		$result	= array();
		jimport('joomla.filesystem.folder');

		$paths		= PhocaGalleryPath::getPath();
		$path		= JPath::clean($paths->media_css_abs . '/main/');
		
		if (is_dir($path)) {
			$files = JFolder::files($path, '\.css$', false, false);
		
			foreach ($files as $file) {
				$fileO 	= new stdClass;
				$fileO->filename 	= $file;
				$fileO->exists 		= file_exists($path.$file);
				$fileO->type 		= 1;
				$result[] 			= $fileO;
			}
		} else {
			$this->setError(JText::_('COM_PHOCAGALLERY_ERROR_CSS_FOLDER_NOT_FOUND') . ' (1)');
			return false;
		}
		
		$path	= JPath::clean($paths->media_css_abs . '/custom/');
		if (is_dir($path)) {
			$files = JFolder::files($path, '\.css$', false, false);
		
			foreach ($files as $file) {
				$fileO 	= new stdClass;
				$fileO->filename 	= $file;
				$fileO->exists 		= file_exists($path.$file);
				$fileO->type 		= 2;
				$result[] 			= $fileO;
			}
		} else {
			$this->setError(JText::_('COM_PHOCAGALLERY_ERROR_CSS_FOLDER_NOT_FOUND') . ' (2)');
			return false;
		}
		return $result;
	}
}
?>