<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.path.path');

class PhocaGalleryFile
{
	public static function getTitleFromFile(&$filename, $displayExt = 0) {


		$filename 			= str_replace('//', '/', $filename);
		$filename			= str_replace('\\', '/', $filename);
		$folderArray		= explode('/', $filename);// Explode the filename (folder and file name)
		$countFolderArray	= count($folderArray);// Count this array
		$lastArrayValue 	= $countFolderArray - 1;// The last array value is (Count array - 1)

		$title = new JObject();
		$title->with_extension 		= $folderArray[$lastArrayValue];
		$title->without_extension	= PhocaGalleryFile::removeExtension($folderArray[$lastArrayValue]);

		if ($displayExt == 1) {
			return $title->with_extension;
		} else if ($displayExt == 0) {
			return $title->without_extension;
		} else {
			return $title;
		}
	}

	public static function removeExtension($filename) {
		return substr($filename, 0, strrpos( $filename, '.' ));
	}


	public static function getMimeType($filename) {
		$ext = JFile::getExt($filename);
		switch(strtolower($ext)) {
			case 'png':
				$mime = 'image/png';
			break;
			case 'jpg':
			case 'jpeg':
				$mime = 'image/jpeg';
			break;
			case 'gif':
				$mime = 'image/gif';
			break;
			case 'webp':
				$mime = 'image/webp';
			break;
			Default:
				$mime = '';
			break;
		}
		return $mime;
	}

	public static function getFileSize($filename, $readable = 1) {

		$path			= PhocaGalleryPath::getPath();
		$fileNameAbs	= JPath::clean($path->image_abs . $filename);

		if (!JFile::exists($fileNameAbs)) {
			$fileNameAbs	= $path->image_abs_front . 'phoca_thumb_l_no_image.png';
		}

		if ($readable == 1) {
			return PhocaGalleryFile::getFileSizeReadable(filesize($fileNameAbs));
		} else {
			return filesize($fileNameAbs);
		}
	}

	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	public static function getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {

		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }


		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }

        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}

	public static function getFileOriginal($filename, $rel = 0) {
		$path	= PhocaGalleryPath::getPath();
		if ($rel == 1) {
			return str_replace('//', '/', $path->image_rel . $filename);
		} else {
			return JPath::clean($path->image_abs . $filename);
		}
	}

	public static function getFileFormat($filename) {
		$path	= PhocaGalleryPath::getPath();
		$file	= JPath::clean($path->image_abs . $filename);
		$size	= getimagesize($file);
		if (isset($size[0]) && isset($size[1]) && (int)$size[1] > (int)$size[0]) {
			return 2;
		} else {
			return 1;
		}
	}

	public static function existsFileOriginal($filename) {
		$fileOriginal = PhocaGalleryFile::getFileOriginal($filename);
		if (JFile::exists($fileOriginal)) {
			return true;
		} else {
			return false;
		}
	}


	public static function deleteFile ($filename) {
		$fileOriginal = PhocaGalleryFile::getFileOriginal($filename);
		if (JFile::exists($fileOriginal)){
			JFile::delete($fileOriginal);
			return true;
		}
		return false;
	}

	public static function existsCss($file, $type) {
		$path = self::getCSSPath($type);
		if (file_exists($path.$file) && $file != '') {
			return $path.$file;
		}
		return false;
	}

	public static function getCSSPath($type, $rel = 0) {
		$paths		= PhocaGalleryPath::getPath();
		if ($rel == 1) {
			if ($type == 1) {
				return $paths->media_css_rel . 'main/';
			} else {
				return $paths->media_css_rel . 'custom/';
			}
		} else {
			if ($type == 1) {
				return JPath::clean($paths->media_css_abs . 'main/');
			} else {
				return	JPath::clean($paths->media_css_abs . 'custom/');
			}
		}
	}

	public static function getCSSFile($id = 0, $fullPath = 0) {
		if ((int)$id > 0) {
			$db = JFactory::getDBO();
			$query = 'SELECT a.filename as filename, a.type as type'
				.' FROM #__phocagallery_styles AS a'
			    .' WHERE a.id = '.(int) $id
				.' ORDER BY a.id';
			$db->setQuery($query, 0, 1);
			$filename = $db->loadObject();
			if (isset($filename->filename) && $filename->filename != '') {
				if ($fullPath == 1 && isset($filename->type)) {
					return self::getCSSPath($filename->type). $filename->filename;
				} else {
					return $filename->filename;
				}
			}
		}

		return false;
	}
}
?>
