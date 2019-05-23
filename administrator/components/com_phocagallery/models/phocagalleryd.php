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

class PhocaGalleryCpModelPhocaGalleryD extends JModelLegacy
{
	protected $id;
	protected $data;
	
	public function __construct() {
		parent::__construct();
		$id = JFactory::getApplication()->input->get('id',  0, '', 'int');
		$this->setId((int)$id);
	}

	protected function setId($id) {
		$this->id		= $id;
		$this->data		= null;
	}

	public function getData() {
		if (!$this->loadData()) {
			$this->initData();
		}
		return $this->data;
	}
	
	function loadData() {
		if (empty($this->data)) {
			$query = 'SELECT a.*' .
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $this->id;
			$this->_db->setQuery($query);
			
			$fileObject = $this->_db->loadObject();
			
			$file 	= new JObject();

			$refresh_url = 'index.php?option=com_phocagallery&view=phocagalleryd&tmpl=component&id='.(int)$this->id;
			
			//Creata thumbnails if not exist
			PhocaGalleryFileThumbnail::getOrCreateThumbnail($fileObject->filename, $refresh_url, 1, 1, 1);
			
			jimport( 'joomla.filesystem.file' );
			if (!isset($fileObject->filename)) {					
				$file->set('linkthumbnailpath', '');			
			} else {
				$thumbFile = PhocaGalleryFileThumbnail::getThumbnailName ($fileObject->filename, 'large');
				$file->set('linkthumbnailpath', $thumbFile->rel);
				$file->set('extid', $fileObject->extid);
				$file->set('extl', $fileObject->extl);
				$file->set('extw', $fileObject->extw);
				$file->set('exth', $fileObject->exth);
			}
				
			$this->data = $file;
			return (boolean) $this->data;
		}
		return true;
	}
	
	protected function initData() {
		if (empty($this->data)) {
			$this->data	= '';
			return (boolean) $this->data;
		}
		return true;
	}	
}
?>
