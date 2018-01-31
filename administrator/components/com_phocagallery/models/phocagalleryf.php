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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class PhocaGalleryCpModelPhocaGalleryF extends JModelLegacy
{
	function getState($property = null) {
		static $set;

		if (!$set) {
			$folder = JRequest::getVar( 'folder', '', '', 'path' );
			$upload = JRequest::getVar( 'upload', '', '', 'int' );
			$this->setState('folder', $folder);
			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}



	function getFolders() {
		$list = $this->getList();
		return $list['folders'];
	}

	function getList() {
		static $list;		
		
		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}
		
		//Get folder variables from Helper
		$path = PhocaGalleryPath::getPath();
		
		// Initialize variables
		if (strlen($current) > 0) {
			$orig_path = JPath::clean($path->image_abs.$current);
		} else {
			$orig_path = $path->image_abs;
		}
		$orig_path_server 	= str_replace(DS, '/', $path->image_abs);
		
		$folders 	= array ();

		// Get the list of files and folders from the given folder
		$folder_list 	= JFolder::folders($orig_path, '', false, false, array(0 => 'thumbs'));
		
		// Iterate over the folders if they exist
		if ($folder_list !== false) {
			foreach ($folder_list as $folder) {
				$tmp 							= new JObject();
				$tmp->name 						= basename($folder);
				$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $folder));
				$tmp->path_without_name_relative= $path->image_rel . str_replace($orig_path_server, '', $tmp->path_with_name);
				$tmp->path_with_name_relative_no= str_replace($orig_path_server, '', $tmp->path_with_name);	
				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders);
		return $list;
	}
}
?>