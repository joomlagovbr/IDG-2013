<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.5
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaModelBrowsers extends F0FModel
{
	function makeListing()
	{
		JLoader::import('joomla.filesystem.folder');
		JLoader::import('joomla.filesystem.path');

		// Get the folder to browse
		$folder = $this->getState('folder', '');
		$processfolder = $this->getState('processfolder', 0);

		if(empty($folder))
		{
			$folder = JPATH_SITE;
		}

		$stock_dirs = AEPlatform::getInstance()->get_stock_directories();
		arsort($stock_dirs);

		if($processfolder == 1)
		{
			foreach($stock_dirs as $find => $replace)
			{
				$folder = str_replace($find, $replace, $folder);
			}
		}

		// Normalise name, but only if realpath() really, REALLY works...
		$old_folder = $folder;
		$folder = @realpath($folder);
		if($folder === false) $folder = $old_folder;

		if(AEUtilFilesystem::folderExists($folder))
		{
			$isFolderThere = true;
		}
		else
		{
			$isFolderThere = false;
		}

		// Check if it's a subdirectory of the site's root
		$isInRoot = (strpos($folder, JPATH_SITE) === 0);

		// Check open_basedir restrictions
		$isOpenbasedirRestricted = AEUtilQuirks::checkOpenBasedirs($folder);

		// -- Get the meta form of the directory name, if applicable
		$folder_raw = $folder;
		foreach($stock_dirs as $replace => $find)
		{
			$folder_raw = str_replace($find, $replace, $folder_raw);
		}

		// Writable check and contents listing if it's in site root and not restricted
		if($isFolderThere && !$isOpenbasedirRestricted)
		{
			// Get writability status
			$isWritable = is_writable($folder);

			// Get contained folders
			$subfolders = JFolder::folders($folder);
		}
		else
		{
			if($isFolderThere && !$isOpenbasedirRestricted)
			{
				$isWritable = is_writable($folder);
			}
			else
			{
				$isWritable = false;
			}

			$subfolders = array();
		}

		// Get parent directory
		$pathparts = explode(DIRECTORY_SEPARATOR, $folder);
		if(is_array($pathparts))
		{
			$path = '';
			foreach($pathparts as $part)
			{
				$path .= empty($path) ? $part : DIRECTORY_SEPARATOR.$part;
				if(empty($part)) {
					if( DIRECTORY_SEPARATOR != '\\' ) $path = DIRECTORY_SEPARATOR;
					$part = DIRECTORY_SEPARATOR;
				}
				$crumb['label'] = $part;
				$crumb['folder'] = $path;
				$breadcrumbs[]=$crumb;
			}

			$junk = array_pop($pathparts);
			$parent = implode(DIRECTORY_SEPARATOR, $pathparts);
		}
		else
		{
			// Can't identify parent dir, use ourselves.
			$parent = $folder;
			$breadcrumbs = array();
		}

		$this->setState('folder',					$folder);
		$this->setState('folder_raw',				$folder_raw);
		$this->setState('parent',					$parent);
		$this->setState('exists',					$isFolderThere);
		$this->setState('inRoot',					$isInRoot);
		$this->setState('openbasedirRestricted',	$isOpenbasedirRestricted);
		$this->setState('writable',					$isWritable);
		$this->setState('subfolders',				$subfolders);
		$this->setState('breadcrumbs',				$breadcrumbs);
	}
}