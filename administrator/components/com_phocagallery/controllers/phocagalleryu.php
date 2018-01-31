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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.client.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class PhocaGalleryCpControllerPhocaGalleryu extends PhocaGalleryCpController
{
	function __construct() {
		parent::__construct();
	}

	function createfolder() {
		$app	= JFactory::getApplication();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);

		$path			= PhocaGalleryPath::getPath();
		$folderNew		= JRequest::getCmd( 'foldername', '');
		$folderCheck	= JRequest::getVar( 'foldername', null, '', 'string', JREQUEST_ALLOWRAW);
		$parent			= JRequest::getVar( 'folderbase', '', '', 'path' );
		$tab			= JRequest::getVar( 'tab', '', '', 'string' );
		$field			= JRequest::getVar( 'field');
		$viewBack		= JRequest::getVar( 'viewback', '', '', '' );
		
		$link = '';
		switch ($viewBack) {
			case 'phocagalleryi':
				$link = 'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&folder='.$parent.'&tab='.(string)$tab.'&field='.$field;
			break;
		
			case 'phocagallerym':
				$link = 'index.php?option=com_phocagallery&view=phocagallerym&layout=edit&hidemainmenu=1&tab='.(string)$tab.'&folder='.$parent;
			break;
			
			case 'phocagalleryf':
				$link = 'index.php?option=com_phocagallery&view=phocagalleryf&tmpl=component&folder='.$parent.'&field='.$field;
			break;
			
			Default:
				$app->redirect('index.php?option=com_phocagallery', JText::_('COM_PHOCAGALLERY_ERROR_CONTROLLER'));
			break;
		
		}

		JRequest::setVar('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$app->redirect($link, JText::_('COM_PHOCAGALLERY_WARNING_DIRNAME'));
		}

		if (strlen($folderNew) > 0) {
			$folder = JPath::clean($path->image_abs.DS.$parent.DS.$folderNew);
			if (!JFolder::exists($folder) && !JFile::exists($folder)) {
				//JFolder::create($path, $folder_permissions );
				switch((int)$folder_permissions) {
					case 777:
						JFolder::create($folder, 0777 );
					break;
					case 705:
						JFolder::create($folder, 0705 );
					break;
					case 666:
						JFolder::create($folder, 0666 );
					break;
					case 644:
						JFolder::create($folder, 0644 );
					break;				
					case 755:
					Default:
						JFolder::create($folder, 0755 );
					break;
				}
				if (isset($folder)) {
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($folder.DS."index.html", $data);
				}
				
				$app->redirect($link, JText::_('COM_PHOCAGALLERY_SUCCESS_FOLDER_CREATING'));
			} else {
				$app->redirect($link, JText::_('COM_PHOCAGALLERY_ERROR_FOLDER_CREATING_EXISTS'));
			}
			//JRequest::setVar('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
		$app->redirect($link);
	}
	
	function multipleupload() {
		$result = PhocaGalleryFileUpload::realMultipleUpload();
		return true;	
	}
	
	function upload() {
		$result = PhocaGalleryFileUpload::realSingleUpload();
		return true;
	}
	
	
	function javaupload() {	
		$result = PhocaGalleryFileUpload::realJavaUpload();
		return true;
	}
	
}
