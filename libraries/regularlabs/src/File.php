<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JClientFtp;
use JClientHelper;
use JFactory;
use JFilesystemWrapperPath;
use JFolder;
use JLog;
use JText;
use JUri;

/**
 * Class File
 * @package RegularLabs\Library
 */
class File
{
	/**
	 * Find a matching media file in the different possible extension media folders for given type
	 *
	 * @param string $type (css/js/...)
	 * @param string $file
	 *
	 * @return bool|string
	 */
	public static function getMediaFile($type, $file)
	{
		// If http is present in filename
		if (strpos($file, 'http') === 0 || strpos($file, '//') === 0)
		{
			return $file;
		}

		$files = [];

		// Detect debug mode
		if (JFactory::getConfig()->get('debug') || JFactory::getApplication()->input->get('debug'))
		{
			$files[] = str_replace(['.min.', '-min.'], '.', $file);
		}

		$files[] = $file;

		/*
		 * Loop on 1 or 2 files and break on first find.
		 * Add the content of the MD5SUM file located in the same folder to url to ensure cache browser refresh
		 * This MD5SUM file must represent the signature of the folder content
		 */
		foreach ($files as $check_file)
		{
			$file_found = self::findMediaFileByFile($check_file, $type);

			if ( ! $file_found)
			{
				continue;
			}

			return $file_found;
		}

		return false;
	}

	/**
	 * Find a matching media file in the different possible extension media folders for given type
	 *
	 * @param string $file
	 * @param string $type (css/js/...)
	 *
	 * @return bool|string
	 */
	private static function findMediaFileByFile($file, $type)
	{
		$template = JFactory::getApplication()->getTemplate();

		// If the file is in the template folder
		$file_found = self::getFileUrl('/templates/' . $template . '/' . $type . '/' . $file);
		if ($file_found)
		{
			return $file_found;
		}

		// Try to deal with system files in the media folder
		if (strpos($file, '/') === false)
		{
			$file_found = self::getFileUrl('/media/system/' . $type . '/' . $file);

			if ( ! $file_found)
			{
				return false;
			}

			return $file_found;
		}

		$paths = [];

		// If the file contains any /: it can be in a media extension subfolder
		// Divide the file extracting the extension as the first part before /
		list($extension, $file) = explode('/', $file, 2);

		$paths[] = '/media/' . $extension . '/' . $type;
		$paths[] = '/templates/' . $template . '/' . $type . '/system';
		$paths[] = '/media/system/' . $type;

		foreach ($paths as $path)
		{
			$file_found = self::getFileUrl($path . '/' . $file);

			if ( ! $file_found)
			{
				continue;
			}

			return $file_found;
		}

		return false;
	}

	/**
	 * Get the url for the file
	 *
	 * @param string $path
	 *
	 * @return bool|string
	 */
	private static function getFileUrl($path)
	{
		if ( ! file_exists(JPATH_ROOT . $path))
		{
			return false;
		}

		return JUri::root(true) . $path;
	}

	/**
	 * Delete a file or array of files
	 *
	 * @param   mixed   $file          The file name or an array of file names
	 * @param   boolean $show_messages Whether or not to show error messages
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public static function delete($file, $show_messages = false)
	{
		$FTPOptions = JClientHelper::getCredentials('ftp');
		$pathObject = new JFilesystemWrapperPath;

		$files = is_array($file) ? $file : [$file];

		if ($FTPOptions['enabled'] == 1)
		{
			// Connect the FTP client
			$ftp = JClientFtp::getInstance($FTPOptions['host'], $FTPOptions['port'], [], $FTPOptions['user'], $FTPOptions['pass']);
		}

		foreach ($files as $file)
		{
			$file = $pathObject->clean($file);

			if ( ! is_file($file))
			{
				continue;
			}

			// Try making the file writable first. If it's read-only, it can't be deleted
			// on Windows, even if the parent folder is writable
			@chmod($file, 0777);

			if ($FTPOptions['enabled'] == 1)
			{
				$file = $pathObject->clean(str_replace(JPATH_ROOT, $FTPOptions['root'], $file), '/');

				if ( ! $ftp->delete($file))
				{
					// FTP connector throws an error
					return false;
				}
			}

			// Try the unlink twice in case something was blocking it on first try
			if ( ! @unlink($file) && ! @unlink($file))
			{
				$show_messages && JLog::add(JText::sprintf('JLIB_FILESYSTEM_DELETE_FAILED', basename($file)), JLog::WARNING, 'jerror');

				return false;
			}
		}

		return true;
	}

	/**
	 * Delete a folder.
	 *
	 * @param   string  $path          The path to the folder to delete.
	 * @param   boolean $show_messages Whether or not to show error messages
	 *
	 * @return  boolean  True on success.
	 */
	public static function deleteFolder($path, $show_messages = false)
	{
		@set_time_limit(ini_get('max_execution_time'));
		$pathObject = new JFilesystemWrapperPath;

		if ( ! $path)
		{
			$show_messages && JLog::add(__METHOD__ . ': ' . JText::_('JLIB_FILESYSTEM_ERROR_DELETE_BASE_DIRECTORY'), JLog::WARNING, 'jerror');

			return false;
		}

		$FTPOptions = JClientHelper::getCredentials('ftp');

		// Check to make sure the path valid and clean
		$path = $pathObject->clean($path);

		if ( ! is_dir($path))
		{
			$show_messages && JLog::add(JText::sprintf('JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER', $path), JLog::WARNING, 'jerror');

			return false;
		}

		// Remove all the files in folder if they exist; disable all filtering
		$files = JFolder::files($path, '.', false, true, [], []);

		if ( ! empty($files))
		{
			if (self::delete($files, $show_messages) !== true)
			{
				// JFile::delete throws an error
				return false;
			}
		}

		// Remove sub-folders of folder; disable all filtering
		$folders = JFolder::folders($path, '.', false, true, [], []);

		foreach ($folders as $folder)
		{
			if (is_link($folder))
			{
				// Don't descend into linked directories, just delete the link.

				if (self::delete($folder, $show_messages) !== true)
				{
					return false;
				}

				continue;
			}

			if ( ! self::deleteFolder($folder, $show_messages))
			{
				return false;
			}
		}

		if (@rmdir($path))
		{
			return true;
		}

		if ($FTPOptions['enabled'] == 1)
		{
			// Connect the FTP client
			$ftp = JClientFtp::getInstance($FTPOptions['host'], $FTPOptions['port'], [], $FTPOptions['user'], $FTPOptions['pass']);

			// Translate path and delete
			$path = $pathObject->clean(str_replace(JPATH_ROOT, $FTPOptions['root'], $path), '/');

			// FTP connector throws an error
			return $ftp->delete($path);
		}

		if ( ! @rmdir($path))
		{
			$show_messages && JLog::add(JText::sprintf('JLIB_FILESYSTEM_ERROR_FOLDER_DELETE', $path), JLog::WARNING, 'jerror');

			return false;
		}

		return true;
	}
}
