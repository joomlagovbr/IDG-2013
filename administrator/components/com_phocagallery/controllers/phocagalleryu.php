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
		JSession::checkToken() or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);

		$path			= PhocaGalleryPath::getPath();
		//$folderNew		= J Request::getCmd( 'foldername', '');
		//$folderCheck	= JFactory::getApplication()->input->get( 'foldername', null, '', 'string', J REQUEST_ALLOWRAW);
		$folderNew      = $app->input->getstring('foldername', '');
		//$folderCheck    = $app->input->getstring('foldername', null, '', 'string', J REQUEST_ALLOWRAW);
		$folderCheck    = $app->input->getstring('foldername', null, '', 'string');
		$parent			= JFactory::getApplication()->input->get( 'folderbase', '', '', 'path' );
		$tab			= JFactory::getApplication()->input->get( 'tab', '', '', 'string' );
		$field			= JFactory::getApplication()->input->get( 'field');
		$viewBack		= JFactory::getApplication()->input->get( 'viewback', '', '', '' );
		
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
			
			default:
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_ERROR_CONTROLLER'));
				$app->redirect('index.php?option=com_phocagallery');
			break;
		
		}

		//JFactory::getApplication()->input->set('folder', $parent);
		JFactory::getApplication()->input->set('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_WARNING_DIRNAME'));
			$app->redirect($link);
		}

		if (strlen($folderNew) > 0) {
			$folder = JPath::clean($path->image_abs. '/'. $parent. '/'. $folderNew);
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
					JFile::write($folder. '/'. "index.html", $data);
				}
				
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_SUCCESS_FOLDER_CREATING'));
				$app->redirect($link);
			} else {
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_ERROR_FOLDER_CREATING_EXISTS'));
				$app->redirect($link);
			}
			//JFactory::getApplication()->input->set('folder', ($parent) ? $parent.'/'.$folder : $folder);
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
