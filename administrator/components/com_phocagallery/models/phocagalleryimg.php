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
phocagalleryimport('phocagallery.tag.tag');

class PhocaGalleryCpModelPhocaGalleryImg extends JModelAdmin
{
	protected	$option 		= 'com_phocagallery';
	protected 	$text_prefix	= 'com_phocagallery';
	
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
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocagallery.phocagalleryimg', 'phocagalleryimg', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocagallery.edit.phocagallery.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
		public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();
		}

		return $item;
	}
	
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocagallery WHERE catid = '.(int)$table->catid);
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}
	

	
	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'catid = '. (int) $table->catid;
		//$condition[] = 'state >= 0';
		return $condition;
	}

	function approve(&$pks, $value = 1)
	{ 
		// Initialise variables.
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable('phocagallery');
		$pks		= (array) $pks;

		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$this->canEditState($table)) {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDIT_STATE_NOT_PERMITTED'));
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->approve($pks, $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		$context = $this->option.'.'.$this->name;

		// Trigger the onContentChangeState event.
		/*$result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));
		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}*/

		return true;
	}
	
	function save($data) {
		
		$params						= &JComponentHelper::getParams( 'com_phocagallery' );
		$clean_thumbnails 			= $params->get( 'clean_thumbnails', 0 );
		$fileOriginalNotExist		= 0;
		
		
		if ((int)$data['extid'] > 0) {
			$data['imgorigsize'] 	= 0;
			if ($data['title'] == '') {
				$data['title'] = 'External Image';
			}
		} else {
			//If this file doesn't exists don't save it
			if (!PhocaGalleryFile::existsFileOriginal($data['filename'])) {
				//$this->setError('Original File does not exist');
				//return false;
				$fileOriginalNotExist = 1;
				$errorMsg = JText::_('COM_PHOCAGALLERY_ORIGINAL_IMAGE_NOT_EXIST');
			}
		
			$data['imgorigsize'] 	= PhocaGalleryFile::getFileSize($data['filename'], 0);
			$data['format'] 		= PhocaGalleryFile::getFileFormat($data['filename']);
			
			//If there is no title and no alias, use filename as title and alias
			if ($data['title'] == '') {
				$data['title'] = PhocaGalleryFile::getTitleFromFile($data['filename']);
			}
		}
		
		if ($data['extlink1link'] != '') {
			$extlink1			= str_replace('http://','', $data['extlink1link']);
			$data['extlink1'] 	= $extlink1 . '|'.$data['extlink1title'].'|'.$data['extlink1target'].'|'.$data['extlink1icon'];
		} else {
			$data['extlink1'] 	= $data['extlink1link'] . '|'.$data['extlink1title'].'|'.$data['extlink1target'].'|'.$data['extlink1icon'];
		}
		
		if ($data['extlink2link'] != '') {
			$extlink2			= str_replace('http://','', $data['extlink2link']);
			$data['extlink2'] 	= $extlink2 . '|'.$data['extlink2title'].'|'.$data['extlink2target'].'|'.$data['extlink2icon'];
		} else {
			$data['extlink2'] 	= $data['extlink2link'] . '|'.$data['extlink2title'].'|'.$data['extlink2target'].'|'.$data['extlink2icon'];
		}
		
		// Geo
		if($data['longitude'] == '' || $data['latitude'] == '') {
			phocagalleryimport('phocagallery.geo.geo');
			$coords = PhocaGalleryGeo::getGeoCoords($data['filename']);
		
			if ($data['longitude'] == '' ){
				$data['longitude'] = $coords['longitude'];
			}
      
			if ($data['latitude'] == '' ){
				$data['latitude'] = $coords['latitude'];
			}
			
			if ($data['latitude'] != '' && $data['longitude'] != '' && $data['zoom'] == ''){
				$data['zoom'] = PhocaGallerySettings::getAdvancedSettings('geozoom');
			}
		}

		

		if ($data['alias'] == '') {
			$data['alias'] = $data['title'];
		}
		
		//clean alias name (no bad characters)
		//$data['alias'] = PhocaGalleryText::getAliasName($data['alias']);
		

		// if new item, order last in appropriate group
		//if (!$row->id) {
		//	$where = 'catid = ' . (int) $row->catid ;
		//	$row->ordering = $row->getNextOrder( $where );
		//}
		
		// = = = = = = = = = = 
		
		
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing record.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		if(intval($table->date) == 0) {
			$table->date = JFactory::getDate()->toSql();
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Trigger the onContentBeforeSave event.
		/*$result = $dispatcher->trigger($this->event_before_save, array($this->option.'.'.$this->name, $table, $isNew));
		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}*/

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		// Store to ref table
		if (!isset($data['tags'])) {
			$data['tags'] = array();
		}
		if ((int)$table->id > 0) {
			PhocaGalleryTag::storeTags($data['tags'], (int)$table->id);
		}

		// Clean the cache.
		$cache = JFactory::getCache($this->option);
		$cache->clean();

		// Trigger the onContentAfterSave event.
		//$dispatcher->trigger($this->event_after_save, array($this->option.'.'.$this->name, $table, $isNew));

		$pkName = $table->getKeyName();
		if (isset($table->$pkName)) {
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		$this->setState($this->getName().'.new', $isNew);
		
		// = = = = = = 
		
		
		$task = JRequest::getVar('task');
		if (isset($table->$pkName)) {
			$id = $table->$pkName;
		}
		
		if ((int)$data['extid'] > 0 || $fileOriginalNotExist == 1) {
		
		} else {
			// - - - - - - - - - - - - - - - - - -
			//Create thumbnail small, medium, large		
			//file - abc.img, file_no - folder/abc.img
			//Get folder variables from Helper
			//Create thumbnails small, medium, large
			$refresh_url = 'index.php?option=com_phocagallery&task=phocagalleryimg.thumbs';
			$task = JRequest::getVar('task');
			if (isset($table->$pkName) && $task == 'apply') {
				$id = $table->$pkName;
				$refresh_url = 'index.php?option=com_phocagallery&task=phocagalleryimg.edit&id='.(int)$id;
			}
			if ($task == 'save2new') {
				// Don't create automatically thumbnails in case, we are going to add new image
			} else {
				$file_thumb = PhocaGalleryFileThumbnail::getOrCreateThumbnail($data['filename'], $refresh_url, 1, 1, 1);
			}
			//Clean Thumbs Folder if there are thumbnail files but not original file
			if ($clean_thumbnails == 1) {
				phocagalleryimport('phocagallery.file.filefolder');
				PhocaGalleryFileFolder::cleanThumbsFolder();
			}
			// - - - - - - - - - - - - - - - - - - - - -
		}
		
		return true;
	}
		
		

	function delete($cid = array()) {
		$params				= &JComponentHelper::getParams( 'com_phocagallery' );
		$clean_thumbnails 	= $params->get( 'clean_thumbnails', 0 );
		$result 			= false;

		
		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			// - - - - - - - - - - - - - 
			// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
			$queryd = 'SELECT filename as filename FROM #__phocagallery WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery($queryd);
			$fileObject = $this->_db->loadObjectList();
			// - - - - - - - - - - - - - 

			//Delete it from DB
			$query = 'DELETE FROM #__phocagallery'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			// - - - - - - - - - - - - - - 
			// Delete thumbnails - medium and large, small from server
			// All id we want to delete - gel all filenames
			foreach ($fileObject as $key => $value) {
				//The file can be stored in other category - don't delete it from server because other category use it
				$querys = "SELECT id as id FROM #__phocagallery WHERE filename='".$value->filename."' ";
				$this->_db->setQuery($queryd);
				$sameFileObject = $this->_db->loadObject();
				// same file in other category doesn't exist - we can delete it
				if (!$sameFileObject) {
					PhocaGalleryFileThumbnail::deleteFileThumbnail($value->filename, 1, 1, 1);
				}
			}
			// Clean Thumbs Folder if there are thumbnail files but not original file
			if ($clean_thumbnails == 1) {
				phocagalleryimport('phocagallery.file.filefolder');
				PhocaGalleryFileFolder::cleanThumbsFolder();
			}
			// - - - - - - - - - - - - - - 
		}
		return true;
	}
	
	function recreate($cid = array(), &$message) {

		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'SELECT a.filename, a.extid'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id IN ( '.$cids.' )';
			$this->_db->setQuery($query);
			$files = $this->_db->loadObjectList();
			if (isset($files) && count($files)) {
				foreach($files as $key => $value) {
				
					
				
					if (isset($value->extid) && ((int)$value->extid > 0)) {
						// Picasa cannot be recreated
						$message = JText::_('COM_PHOCAGALLERY_ERROR_EXT_IMG_NOT_RECREATE');
						return false;
					} else if (isset($value->filename) && $value->filename != '') {
						
						$original	= PhocaGalleryFile::existsFileOriginal($value->filename);
						if (!$original) {
							// Original does not exist - cannot generate new thumbnail
							$message = JText::_('COM_PHOCAGALLERY_FILEORIGINAL_NOT_EXISTS');
							return false;
						}
						// Delete old thumbnails
						$deleteThubms = PhocaGalleryFileThumbnail::deleteFileThumbnail($value->filename, 1, 1, 1);
					} else {
						$message = JText::_('COM_PHOCAGALLERY_FILENAME_NOT_EXISTS');
						return false;
					}
					if (!$deleteThubms) {
						$message = JText::_('COM_PHOCAGALLERY_ERROR_DELETE_THUMBNAIL');
						return false;
					}
				}
			} else {
				$message = JText::_('COM_PHOCAGALLERY_ERROR_LOADING_DATA_DB');
				return false;
			}

		} else {
			$message = JText::_('COM_PHOCAGALLERY_ERROR_ITEM_NOT_SELECTED');
			return false;
		}
		return true;
	}
	/*
	function deletethumbs($id) {
		
		if ($id > 0) {
			$query = 'SELECT a.filename as filename'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $id;
			$this->_db->setQuery($query);
			$file = $this->_db->loadObject();
			if (isset($file->filename) && $file->filename != '') {
				
				$deleteThubms = PhocaGalleryFileThumbnail::deleteFileThumbnail($file->filename, 1, 1, 1);
				
				if ($deleteThubms) {
					return true;
				} else {
					return false;
				}
			} return false;
		} return false;
	}*/

	public function disableThumbs() {
		
		$component			=	'com_phocagallery';
		$paramsC			= JComponentHelper::getParams($component) ;
		$paramsC->set('enable_thumb_creation', 0);
		$data['params'] 	= $paramsC->toArray();
		$table 				= JTable::getInstance('extension');
		$idCom				= $table->find( array('element' => $component ));
		$table->load($idCom);

		if (!$table->bind($data)) {
			JError::raiseWarning( 500, 'Not a valid component' );
			return false;
		}
			
		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning( 500, $table->getError('Check Problem') );
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning( 500, $table->getError('Store Problem') );
			return false;
		}
		return true;
	}
	
	function rotate($id, $angle, &$errorMsg) {
		phocagalleryimport('phocagallery.image.imagerotate');
		
		if ($id > 0 && $angle !='') {
			$query = 'SELECT a.filename as filename'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $id;
			$this->_db->setQuery($query);
			$file = $this->_db->loadObject();
			
			if (isset($file->filename) && $file->filename != '') {
				
				$thumbNameL	= PhocaGalleryFileThumbnail::getThumbnailName ($file->filename, 'large');
				$thumbNameM	= PhocaGalleryFileThumbnail::getThumbnailName ($file->filename, 'medium');
				$thumbNameS	= PhocaGalleryFileThumbnail::getThumbnailName ($file->filename, 'small');
				
				$errorMsg = $errorMsgS = $errorMsgM = $errorMsgL ='';				
				PhocaGalleryImageRotate::rotateImage($thumbNameL, 'large', $angle, $errorMsgS);
				if ($errorMsgS != '') {
					$errorMsg = $errorMsgS;
					return false;
				}
				PhocaGalleryImageRotate::rotateImage($thumbNameM, 'medium', $angle, $errorMsgM);
				if ($errorMsgM != '') {
					$errorMsg = $errorMsgM;
					return false;
				} 
				PhocaGalleryImageRotate::rotateImage($thumbNameS, 'small', $angle, $errorMsgL);
				if ($errorMsgL != '') {
					$errorMsg = $errorMsgL;
					return false;
				} 

				if ($errorMsgL == '' && $errorMsgM == '' && $errorMsgS == '' ) {
					return true;
				} else {
					$errorMsg = ' ('.$errorMsg.')';
					return false;
				}
			}
			$errorMsg = JText::_('COM_PHOCAGALLERY_FILENAME_NOT_EXISTS');
			return false;
		}
		$errorMsg = JText::_('COM_PHOCAGALLERY_ERROR_ITEM_NOT_SELECTED');
		return false;
	}
	
	function deletethumbs($id) {
		
		if ($id > 0) {
			$query = 'SELECT a.filename as filename'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $id;
			$this->_db->setQuery($query);
			$file = $this->_db->loadObject();
			if (isset($file->filename) && $file->filename != '') {
				
				$deleteThubms = PhocaGalleryFileThumbnail::deleteFileThumbnail($file->filename, 1, 1, 1);
				
				if ($deleteThubms) {
					return true;
				} else {
					return false;
				}
			} return false;
		} return false;
	}
	
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId	= (int) $value;

		$table	= $this->getTable();
		$db		= $this->getDbo();

		// Check that the category exists
		if ($categoryId) {
			$categoryTable = JTable::getInstance('PhocaGalleryC', 'Table');
			
			if (!$categoryTable->load($categoryId)) {
				if ($error = $categoryTable->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
		}

		if (empty($categoryId)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}

		// Check that the user has create permission for the component
		$extension	= JRequest::getCmd('option');
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', $extension)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}
		
		//NEW
		$i		= 0;
		//ENDNEW

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk)) {
				if ($error = $table->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($categoryId, $table->alias, $table->title);
			$table->title   = $data['0'];
			$table->alias   = $data['1'];

			// Reset the ID because we are making a copy
			$table->id		= 0;

			// New category ID
			$table->catid	= $categoryId;
			
			// Ordering
			$table->ordering = $this->increaseOrdering($categoryId);

			$table->hits = 0;

			// Check the row.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}

			// Store the row.
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
			
			//NEW
			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$i]	= $newId;
			$i++;
			//ENDNEW
		}

		// Clean the cache
		$this->cleanCache();

		//NEW
		return $newIds;
		//END NEW
	}

	/**
	 * Batch move articles to a new category
	 *
	 * @param   integer  $value  The new category ID.
	 * @param   array    $pks    An array of row IDs.
	 *
	 * @return  booelan  True if successful, false otherwise and internal error is set.
	 *
	 * @since	11.1
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		$categoryId	= (int) $value;

		$table	= $this->getTable();
		//$db		= $this->getDbo();

		// Check that the category exists
		if ($categoryId) {
			$categoryTable = JTable::getInstance('PhocaGalleryC', 'Table');
			if (!$categoryTable->load($categoryId)) {
				if ($error = $categoryTable->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
		}

		if (empty($categoryId)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}

		// Check that user has create and edit permission for the component
		$extension	= JRequest::getCmd('option');
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', $extension)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}

		if (!$user->authorise('core.edit', $extension)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		// Parent exists so we let's proceed
		foreach ($pks as $pk)
		{
			// Check that the row actually exists
			if (!$table->load($pk)) {
				if ($error = $table->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Set the new category ID
			$table->catid = $categoryId;

			// Check the row.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}

			// Store the row.
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}
	
	
	public function increaseOrdering($categoryId) {
		
		$ordering = 1;
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocagallery WHERE catid='.(int)$categoryId);
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
		return $ordering;
	}
}
?>