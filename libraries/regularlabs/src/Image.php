<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\Folder as JFolder;
use Joomla\CMS\Image\Image as JImage;
use Joomla\CMS\Uri\Uri as JUri;

class Image
{
//	public static function getSet($source, $width, $height, $folder = 'resized', $resize = true, $quality = 'medium', $possible_suffix = '')
//	{
//		$paths = self::getPaths($source, $width, $height, $folder, $resize, $quality, $possible_suffix);
//
//		return (object) [
//			'original'     => (object) [
//				'url'    => $paths->image,
//				'width'  => self::getWidth($paths->original),
//				'height' => self::getHeight($paths->original),
//			],
//			'resized' => (object) [
//				'url'    => $paths->resized,
//				'width'  => self::getWidth($paths->resized),
//				'height' => self::getHeight($paths->resized),
//			],
//		];
//	}

	public static function getUrls($source, $width, $height, $folder = 'resized', $resize = true, $quality = 'medium', $possible_suffix = '')
	{
		if ($image = self::isResized($source, $folder, $possible_suffix))
		{
			$source = $image;
		}

		$original = $source;
		$resized  = self::getResize($source, $width, $height, $folder, $resize, $quality);

		return (object) compact('original', 'resized');
	}

	public static function getResize($source, $width, $height, $folder = 'resized', $resize = true, $quality = 'medium')
	{
		$destination_folder = File::getDirName($source) . '/' . $folder;

		$override = File::getDirName($source) . '/' . $folder . '/' . File::getBaseName($source);

		if (file_exists(JPATH_SITE . '/' . $override))
		{
			$source = $override;
		}

		if ( ! self::setNewDimensions($source, $width, $height))
		{
			return $source;
		}

		if ( ! $width && ! $height)
		{
			return $source;
		}

		$destination = self::getNewPath(
			$source,
			$width,
			$height,
			$destination_folder
		);

		if ( ! file_exists(JPATH_SITE . '/' . $destination) && $resize)
		{
			// Create new resized image
			$destination = self::resize(
				$source,
				$width,
				$height,
				$destination_folder,
				$quality
			);
		}

		if ( ! file_exists(JPATH_SITE . '/' . $destination))
		{
			return $source;
		}

		return $destination;
	}

	public static function isResized($file, $folder = 'resized', $possible_suffix = '')
	{
		if (File::isExternal($file))
		{
			return false;
		}

		if ( ! file_exists($file))
		{
			return false;
		}

		if ($main_image = self::isResizedWithFolder($file, $folder))
		{
			return $main_image;
		}

		if ($possible_suffix && $main_image = self::isResizedWithSuffix($file, $possible_suffix))
		{
			return $main_image;
		}

		return false;
	}

	public static function isResizedWithSuffix($file, $suffix = '_t')
	{
		// Remove the suffix from the file
		// image_t.jpg => image.jpg
		$main_file = RegEx::replace(
			RegEx::quote($suffix) . '(\.[^.]+)$',
			'\1',
			$file
		);

		// Nothing removed, so not a resized image
		if ($main_file == $file)
		{
			return false;
		}

		if ( ! file_exists(JPATH_SITE . '/' . utf8_decode($main_file)))
		{
			return false;
		}

		return $main_file;
	}

	private static function isResizedWithFolder($file, $resize_folder = 'resized')
	{
		$folder             = File::getDirName($file);
		$file               = File::getBaseName($file);
		$parent_folder_name = File::getBaseName($folder);
		$parent_folder      = File::getDirName($folder);

		// Image is not inside the resize folder
		if ($parent_folder_name != $resize_folder)
		{
			return false;
		}

		// Check if image with same name exists in parent folder
		if (file_exists(JPATH_SITE . '/' . $parent_folder . '/' . utf8_decode($file)))
		{
			return $parent_folder . '/' . $file;
		}

		// Remove any dimensions from the file
		// image_300x200.jpg => image.jpg
		$file = RegEx::replace(
			'_[0-9]+x[0-9]*(\.[^.]+)$',
			'\1',
			$file
		);

		// Check again if image with same name (but without dimensions) exists in parent folder
		if (file_exists(JPATH_SITE . '/' . $parent_folder . '/' . utf8_decode($file)))
		{
			return $parent_folder . '/' . $file;
		}

		return false;
	}

	public static function resize($source, &$width, &$height, $destination_folder = '', $quality = 'medium', $overwrite = false)
	{
		if (File::isExternal($source))
		{
			return $source;
		}

		$clean_source = ltrim(str_replace(JUri::root(), '', $source), '/');
		$source_path  = JPATH_SITE . '/' . $clean_source;

		$destination_folder = ltrim($destination_folder ?: File::getDirName($clean_source));

		if ( ! file_exists($source_path))
		{
			return false;
		}

		if ( ! self::setNewDimensions($source, $width, $height))
		{
			return $source;
		}

		if ( ! $width && ! $height)
		{
			return $source;
		}

		$image = new JImage($source_path);

		$destination      = self::getNewPath($source, $width, $height, $destination_folder);
		$destination_path = JPATH_SITE . '/' . $destination;

		if (file_exists($destination_path) && ! $overwrite)
		{
			return $destination;
		}

		JFolder::create(JPATH_SITE . '/' . $destination_folder);

		$info = JImage::getImageFileProperties($source_path);

		$options = ['quality' => self::getQuality($info->type, $quality)];

		$image->cropResize($width, $height, false)
			->toFile($destination_path, $info->type, $options);

		$image->destroy();

		return $destination;
	}

	public static function setNewDimensions($source, &$width, &$height)
	{
		if ( ! $width && ! $height)
		{
			return false;
		}

		if (File::isExternal($source))
		{
			return false;
		}

		$clean_source = ltrim(str_replace(JUri::root(), '', $source), '/');
		$source_path  = JPATH_SITE . '/' . $clean_source;

		if ( ! file_exists($source_path))
		{
			return false;
		}

		$image = new JImage($source_path);

		$original_width  = $image->getWidth();
		$original_height = $image->getHeight();

		$width  = $width ?: round($original_width / $original_height * $height);
		$height = $height ?: round($original_height / $original_width * $width);

		$image->destroy();

		if ($width == $original_width && $height == $original_height)
		{
			return false;
		}

		return true;
	}

	public static function getNewPath($source, $width, $height, $destination_folder = '')
	{
		$clean_source = self::cleanPath($source);

		$source_parts = pathinfo($clean_source);

		$destination_folder = ltrim($destination_folder ?: File::getDirName($clean_source));
		$destination_file   = File::getFileName($clean_source) . '_' . $width . 'x' . $height . '.' . $source_parts['extension'];

		JFolder::create(JPATH_SITE . '/' . $destination_folder);

		return ltrim($destination_folder . '/' . $destination_file);
	}

	public static function cleanPath($source)
	{
		return ltrim(str_replace(JUri::root(), '', $source), '/');
	}

	public static function getWidth($source)
	{
		$dimensions = self::getDimensions($source);

		return $dimensions->width;
	}

	public static function getHeight($source)
	{
		$dimensions = self::getDimensions($source);

		return $dimensions->height;
	}

	public static function getDimensions($source)
	{
		if (File::isExternal($source))
		{
			return (object) [
				'width'  => 0,
				'height' => 0,
			];
		}

		$image = new JImage(JPATH_SITE . '/' . $source);

		return (object) [
			'width'  => $image->getWidth(),
			'height' => $image->getHeight(),
		];
	}

	public static function getQuality($type, $quality = 'medium')
	{
		switch ($type)
		{
			case IMAGETYPE_JPEG:
				return min(max(self::getJpgQuality($quality), 0), 100);

			case IMAGETYPE_PNG:
				return 9;

			default:
				return '';
		}
	}

	public static function getJpgQuality($quality = 'medium')
	{
		switch ($quality)
		{
			case 'low':
				return 50;

			case 'high':
				return 90;

			case 'medium':
			default:
				return 70;
		}
	}

}
