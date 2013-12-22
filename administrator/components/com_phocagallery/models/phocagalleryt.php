<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.modeladmin' );
jimport( 'joomla.installer.installer' );
jimport( 'joomla.installer.helper' );
jimport( 'joomla.filesystem.folder' );


class PhocaGalleryCpModelPhocaGalleryT extends JModelAdmin
{	
	protected 	$_paths 	= array();
	protected 	$_manifest 	= null;
	protected	$option 		= 'com_phocagallery';
	protected 	$text_prefix	= 'com_phocagallery';

	function __construct(){
		parent::__construct();
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocagallery.phocagalleryt', 'phocagalleryt', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	function install($theme) {
		$app		= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		$package 	= $this->_getPackageFromUpload();

		if (!$package) {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INSTALL_PACKAGE'));
			$this->deleteTempFiles();
			return false;
		}
		
		if ($package['dir'] && JFolder::exists($package['dir'])) {
			$this->setPath('source', $package['dir']);
		} else {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_PATH_NOT_EXISTS'));
			$this->deleteTempFiles();
			return false;
		}

		// We need to find the installation manifest file
		if (!$this->_findManifest()) {
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INFO_INSTALL_PACKAGE'));
			$this->deleteTempFiles();
			return false;
		}
	
		// Files - copy files in manifest
		foreach ($this->_manifest->children() as $child)
		{
			if (is_a($child, 'JXMLElement') && $child->name() == 'files') {
				if ($this->parseFiles($child) === false) {
					JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_FIND_INFO_INSTALL_PACKAGE'));
					$this->deleteTempFiles();
					return false;
				}
			}
		}
		
		// File - copy the xml file
		$copyFile 		= array();
		$path['src']	= $this->getPath( 'manifest' ); // XML file will be copied too
		$path['dest']	= JPATH_SITE.DS.'media'.DS.'com_phocagallery'.DS.'images'.DS. basename($this->getPath('manifest')); 
		$copyFile[] 	= $path;
		
		$this->copyFiles($copyFile, array());
		$this->deleteTempFiles();
		
		// -------------------
		// Themes
		// -------------------
		// Params -  Get new themes params
		$paramsThemes = $this->getParamsThemes();
		
		
		// -------------------
		// Component
		// -------------------
		if (isset($theme['component']) && $theme['component'] == 1 ) {
			
			$component			=	'com_phocagallery';
			$paramsC			= JComponentHelper::getParams($component) ;
			
			foreach($paramsThemes as $keyT => $valueT) {
				$paramsC->set($valueT['name'], $valueT['value']);
			}
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
		}
		
		// -------------------
		// Menu Categories
		// -------------------
		if (isset($theme['categories']) && $theme['categories'] == 1 ){
		
			$link		= 'index.php?option=com_phocagallery&view=categories';
			$where 		= Array();
			$where[] 	= 'link = '. $db->Quote($link);
			$query 		= 'SELECT id, params FROM #__menu WHERE '. implode(' AND ', $where);
			$db->setQuery($query);
			$itemsCat	= $db->loadObjectList();
			
			if (!empty($itemsCat)) {
				foreach($itemsCat as $keyIT => $valueIT) {
				
					$query = 'SELECT m.params FROM #__menu AS m WHERE m.id = '.(int) $valueIT->id;
					$db->setQuery( $query );
					$paramsCJSON = $db->loadResult();
					//$paramsCJSON = $valueIT->params;
					
					$paramsMc = new JParameter;
                    $paramsMc->loadJSON($paramsCJSON);
                    
					foreach($paramsThemes as $keyT => $valueT) {
						$paramsMc->set($valueT['name'], $valueT['value']);
					}
					$dataMc['params'] 	= $paramsMc->toArray();

					
					$table =& JTable::getInstance( 'menu' );
					
					if (!$table->load((int) $valueIT->id)) {
						JError::raiseWarning( 500, 'Not a valid table' );
						return false;
					}
					
					if (!$table->bind($dataMc)) {
						JError::raiseWarning( 500, 'Not a valid table' );
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
						
				}
			}
		}
		
		// -------------------
		// Menu Category
		// -------------------
		if (isset($theme['category']) && $theme['category'] == 1 ) {
			
			// Select all categories to get possible menu links
			$query = 'SELECT c.id FROM #__phocagallery_categories AS c';
			
			$db->setQuery( $query );
			$categoriesId = $db->loadObjectList();
			
			// We get id from Phoca Gallery categories and try to find menu links from these categories
			if (!empty ($categoriesId)) {
				foreach($categoriesId as $keyI => $valueI) {
				
					$link		= 'index.php?option=com_phocagallery&view=category&id='.(int)$valueI->id;
					//$link		= 'index.php?option=com_phocagallery&view=category';
					$where 		= Array();
					$where[] 	= 'link = '. $db->Quote($link);
					$query 		= 'SELECT id, params FROM #__menu WHERE '. implode(' AND ', $where);
					$db->setQuery($query);
					$itemsCat	= $db->loadObjectList();

					if (!empty ($itemsCat)) {
						foreach($itemsCat as $keyIT2 => $valueIT2) {
							
							$query = 'SELECT m.params FROM #__menu AS m WHERE m.id = '.(int) $valueIT2->id;
							$db->setQuery( $query );
							$paramsCtJSON = $db->loadResult();
							//$paramsCtJSON = $valueIT2->params;
							
							$paramsMct = new JParameter;
							$paramsMct->loadJSON($paramsCtJSON);
							
							foreach($paramsThemes as $keyT => $valueT) {
								$paramsMct->set($valueT['name'], $valueT['value']);
							}
							$dataMct['params'] 	= $paramsMct->toArray();
							

							$table =& JTable::getInstance( 'menu' );
							
							if (!$table->load((int) $valueIT2->id)) {
								JError::raiseWarning( 500, 'Not a valid table' );
								return false;
							}
							
							if (!$table->bind($dataMct)) {
								JError::raiseWarning( 500, 'Not a valid table' );
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
						}
					}
				}
			}
		}
		return true;
	}
	
	function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('Filedata', null, 'files', 'array' );

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_FILE_UPLOAD'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_INSTALL_ZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_NO_FILE_SELECTED'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_PHOCAGALLERY_ERROR_UPLOAD_FILE'));
			return false;
		}

		// Build the appropriate paths
		$config 	=& JFactory::getConfig();
		$tmp_dest 	= $config->get('tmp_path').DS.$userfile['name'];
		
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = self::unpack($tmp_dest);
		$this->_manifest =& $manifest;
		
		$this->setPath('packagefile', $package['packagefile']);
		$this->setPath('extractdir', $package['extractdir']);
		
		return $package;
	}
	
	function getPath($name, $default=null) {
		return (!empty($this->_paths[$name])) ? $this->_paths[$name] : $default;
	}
	
	function setPath($name, $value) {
		$this->_paths[$name] = $value;
	}
	
	function _findManifest() {
		// Get an array of all the xml files from teh installation directory
		$xmlfiles = JFolder::files($this->getPath('source'), '.xml$', 1, true);
		
		// If at least one xml file exists
		if (count($xmlfiles) > 0) {
			foreach ($xmlfiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);
				if (!is_null($manifest)) {
				
					$attr = $manifest->attributes();
					if ((string)$attr['method'] != 'phocagallerytheme') {
						JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_NO_THEME_FILE'));
						return false;
					}

					// Set the manifest object and path
					$this->_manifest =& $manifest;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));
					
					return true;
				}
			}

			// None of the xml files found were valid install files
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_XML_INSTALL_PHOCA'));
			return false;
		} else {
			
			// No xml files were found in the install folder
			JError::raiseWarning(1, JText::_('COM_PHOCAGALLERY_ERROR_XML_INSTALL'));
			return false;
		}
	}
	
	function _isManifest($file) {
		$xml	= JFactory::getXML($file, true);
		if (!$xml) {
			unset ($xml);
			return null;
		}
		if (!is_object($xml) || ($xml->name() != 'install' )) {
			unset ($xml);
			return null;
		}
		return $xml;
	}
	
	
	function parseFiles($element, $cid=0) {
		$copyfiles 		= array();
		$copyfolders 	= array();

		if (!is_a($element, 'JXMLElement') || !count($element->children())) {
			return 0;// Either the tag does not exist or has no children therefore we return zero files processed.
		}
		
		$files = $element->children();// Get the array of file nodes to process

		if (count($files) == 0) {
			return 0;// No files to process
		}

		$source 	 	= $this->getPath('source');
		$destination 	= JPATH_SITE.DS.'media'.DS.'com_phocagallery';
		//$destination2 	= JPATH_SITE.DS.'media'.DS.'com_phocagallery';

		//foreach ($files as $file) {
			//if ($file->name() == 'folder') {
			if(!empty($files->folder)){
				foreach ($files->folder as $fk => $fv) {
					$path['src']	= $source.DS.$fv;
					$path['dest']	= $destination.DS.$fv;
					$copyfolders[] = $path;
				}
			}
			//}
		//}
		
		if (!empty($files->filename)) {
			foreach($files->filename as $fik => $fiv) {
				$path['src']	= $source.DS.$fiv;
				$path['dest']	= $destination.DS.$fiv;
				$copyfiles[] = $path;
			}
		}
		
		return $this->copyFiles($copyfiles, $copyfolders);
	}
	
	function copyFiles($files, $folders) {
		
		$i = 0;
		$fileIncluded = $folderIncluded = 0;
		if (is_array($folders) && count($folders) > 0)
		{
			foreach ($folders as $folder)
			{
				// Get the source and destination paths
				$foldersource	= JPath::clean($folder['src']);
				$folderdest		= JPath::clean($folder['dest']);

				if (!JFolder::exists($foldersource)) {
					JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_FOLDER_NOT_EXISTS', $foldersource));
					return false;
				} else {
					if (!(JFolder::copy($foldersource, $folderdest, '', true))) {
						JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_ERROR_COPY_FOLDER_TO', $foldersource, $folderdest));
						return false;
					} else {
						$i++;
					}					
				}
			}
			$folderIncluded = 1;
		}
		
		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				// Get the source and destination paths
				$filesource	= JPath::clean($file['src']);
				$filedest	= JPath::clean($file['dest']);

				if (!file_exists($filesource)) {
					JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_FILE_NOT_EXISTS', $filesource));
					return false;
				} else {
					if (!(JFile::copy($filesource, $filedest))) {
						JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_ERROR_COPY_FILE_TO', $filesource, $filedest));
						return false;
					} else {
						$i++;
					}					
				}
			}
			$fileIncluded = 1;
		}

		if ($fileIncluded == 0 && $folderIncluded ==0) {
			JError::raiseWarning(1, JText::sprintf('COM_PHOCAGALLERY_ERROR_INSTALL_FILE'));
			return false;
		}
		
		return $i;// Possible TO DO, now it returns count folders and files togeter, //return count($files);
	}
	
	protected function getParamsThemes() {

		$element = $this->_manifest->children()->params;
		
		if (!is_a($element, 'JXMLElement') || !count($element->children())) {
			return null;// Either the tag does not exist or has no children therefore we return zero files processed.
		}

		$params = $element->children();
		if (count($params) == 0) {
			return null;// No params to process
		}

		// Process each parameter in the $params array.
		$paramsArray = array();
		$i=0;
		foreach ($params as $param) {
			if (!$name = $param['name']) {
				continue;
			}
			if (!$value = $param['default']) {
				continue;
			}

			$paramsArray[$i]['name'] = (string)$name;
			$paramsArray[$i]['value'] = (string)$value;
			$i++;
		}
		return $paramsArray;
	}
		
	function deleteTempFiles() {
		$path = $this->getPath('source');
		if (is_dir($path)) {
			$val = JFolder::delete($path);
		} else if (is_file($path)) {
			$val = JFile::delete($path);
		}
		$packageFile = $this->getPath('packagefile');
		if (is_file($packageFile)) {
			$val = JFile::delete($packageFile);
		}
		$extractDir = $this->getPath('extractdir');
		if (is_dir($extractDir)) {
			$val = JFolder::delete($extractDir);
		}
	}
	
	public static function unpack($p_filename)
	{
		// Path to the archive
		$archivename = $p_filename;

		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');

		// Clean the paths to use for archive extraction
		$extractdir = JPath::clean(dirname($p_filename) . '/' . $tmpdir);
		$archivename = JPath::clean($archivename);

		// Do the unpacking of the archive
		try
		{
			JArchive::extract($archivename, $extractdir);
		}
		catch (Exception $e)
		{
			return false;
		}

		/*
		 * Let's set the extraction directory and package file in the result array so we can
		 * cleanup everything properly later on.
		 */
		$retval['extractdir'] = $extractdir;
		$retval['packagefile'] = $archivename;

		/*
		 * Try to find the correct install directory.  In case the package is inside a
		 * subdirectory detect this and set the install directory to the correct path.
		 *
		 * List all the items in the installation directory.  If there is only one, and
		 * it is a folder, then we will set that folder to be the installation folder.
		 */
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));

		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir . '/' . $dirList[0]))
			{
				$extractdir = JPath::clean($extractdir . '/' . $dirList[0]);
			}
		}

		/*
		 * We have found the install directory so lets set it and then move on
		 * to detecting the extension type.
		 */
		$retval['dir'] = $extractdir;

		/*
		 * Get the extension type and return the directory/type array on success or
		 * false on fail.
		 */
		$retval['type'] = self::detectType($extractdir);
		if ($retval['type'])
		{
			return $retval;
		}
		else
		{
			return false;
		}
	}
	
	public static function detectType($p_dir)
	{
		// Search the install dir for an XML file
		$files = JFolder::files($p_dir, '\.xml$', 1, true);

		if (!count($files))
		{
			JLog::add(JText::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE'), JLog::WARNING, 'jerror');
			return false;
		}

		foreach ($files as $file)
		{
			$xml = simplexml_load_file($file);
			
			if (!$xml)
			{
				continue;
			}
			
			if ($xml->getName() != 'install')
			{
				unset($xml);
				continue;
			}

			$type = (string) $xml->attributes()->type;

			// Free up memory
			unset($xml);
			return $type;
		}

		JLog::add(JText::_('JLIB_INSTALLER_ERROR_NOTFINDJOOMLAXMLSETUPFILE'), JLog::WARNING, 'jerror');

		// Free up memory.
		unset($xml);
		return false;
	}

}
?>