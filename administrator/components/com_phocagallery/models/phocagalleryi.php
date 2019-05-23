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
	//public 		$typeAlias 		= 'com_phocagallery.phocagalleryi';

	function getFolderState($property = null) {
		static $set;

		if (!$set) {
			$folder = JFactory::getApplication()->input->get( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImages() {
		$tab 			= JFactory::getApplication()->input->get( 'tab', '', '', 'string' );
		$muFailed		= JFactory::getApplication()->input->get( 'mufailed', '0', '', 'int' );
		$muUploaded		= JFactory::getApplication()->input->get( 'muuploaded', '0', '', 'int' );

		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tab='.$tab.'&mufailed='.$muFailed.'&muuploaded='.$muUploaded.'&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,1,0,$refreshUrl);
		return $list['Images'];
	}

	function getFolders() {
		$tab = JFactory::getApplication()->input->get( 'tab', 0, '', 'int' );
		$refreshUrl = 'index.php?option=com_phocagallery&view=phocagalleryi&tab='.$tab.'&tmpl=component';
		$list = PhocaGalleryFileFolderList::getList(0,0,0,$refreshUrl);
		return $list['folders'];
	}
}
?>