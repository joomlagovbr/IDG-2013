<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Media helper class.
 *
 * @since       1.6
 * @deprecated  4.0  Use JHelperMedia instead
 */
abstract class MediaHelper
{
	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JHelperMedia::isImage instead
	 */
	public static function isImage($fileName)
	{
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperMedia::isImage() instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		$mediaHelper = new JHelperMedia;

		return $mediaHelper->isImage($fileName);
	}

	/**
	 * Gets the file extension for the purpose of using an icon.
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  string  File extension
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JHelperMedia::getTypeIcon instead
	 */
	public static function getTypeIcon($fileName)
	{
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperMedia::getTypeIcon() instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		$mediaHelper = new JHelperMedia;

		return $mediaHelper->getTypeIcon($fileName);
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param   array   $file   File information
	 * @param   string  $error  An error message to be returned
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JHelperMedia::canUpload instead
	 */
	public static function canUpload($file, $error = '')
	{
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperMedia::canUpload() instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		$mediaHelper = new JHelperMedia;

		return $mediaHelper->canUpload($file, 'com_media');
	}

	/**
	 * Method to parse a file size
	 *
	 * @param   integer  $size  The file size in bytes
	 *
	 * @return  string  The converted file size
	 *
	 * @since   1.6
	 * @deprecated  4.0  Use JHtml::_('number.bytes') instead
	 */
	public static function parseSize($size)
	{
		try
		{
			JLog::add(
				sprintf("%s() is deprecated. Use JHtml::_('number.bytes') instead.", __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		return JHtml::_('number.bytes', $size);
	}

	/**
	 * Calculate the size of a resized image
	 *
	 * @param   integer  $width   Image width
	 * @param   integer  $height  Image height
	 * @param   integer  $target  Target size
	 *
	 * @return  array  The new width and height
	 *
	 * @since   3.2
	 * @deprecated  4.0  Use JHelperMedia::imageResize instead
	 */
	public static function imageResize($width, $height, $target)
	{
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperMedia::imageResize() instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		$mediaHelper = new JHelperMedia;

		return $mediaHelper->imageResize($width, $height, $target);
	}

	/**
	 * Counts the files and directories in a directory that are not php or html files.
	 *
	 * @param   string  $dir  Directory name
	 *
	 * @return  array  The number of files and directories in the given directory
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JHelperMedia::countFiles instead
	 */
	public static function countFiles($dir)
	{
		try
		{
			JLog::add(
				sprintf('%s() is deprecated. Use JHelperMedia::countFiles() instead.', __METHOD__),
				JLog::WARNING,
				'deprecated'
			);
		}
		catch (RuntimeException $exception)
		{
			// Informational log only
		}

		$mediaHelper = new JHelperMedia;

		return $mediaHelper->countFiles($dir);
	}
}
