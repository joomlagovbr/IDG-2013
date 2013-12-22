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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
phocagalleryimport('phocagallery.file.filefolderlist');

class PhocaGalleryCpModelPhocaGalleryI extends JModelLegacy
{
	protected $option 			= 'com_phocagallery';
	protected $text_prefix		= 'com_phocagallery';

	function getFolderState($property = null) {
		static $set;

		if (!$set) {
			$folder = JRequest::getVar( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImages() {
		$tab 			= JRequest::getVar( 'tab', '', '', 'string' );
		$muFailed		= JRequest::getVar( 'mufailed', '0', '', 'int' );
		$muUploaded		= JRequest::getVar( 'muuploaded', '0', '', 'int' );

		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tab='.$tab.'&mufailed='.$muFailed.'&muuploaded='.$muUploaded.'&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,1,0,$refreshUrl);
		return $list['Images'];
	}

	function getFolders() {
		$tab = JRequest::getVar( 'tab', 0, '', 'int' );
		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tab='.$tab.'&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,0,0,$refreshUrl);
		return $list['folders'];
	}
}
?>