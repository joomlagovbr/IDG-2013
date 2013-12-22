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
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

spl_autoload_register(array('JLoader','load'));

class PhocaGalleryLoader extends JLoader
{
	private static $paths 	= array();
	protected static $classes = array();

	public static function import($filePath, $base = null, $key = 'libraries.') {

		$keyPath = $key ? $key . $filePath : $filePath;
		
		if (!isset($paths[$keyPath])) {
			if ( !$base ) {
				$base =  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'libraries';
			}

			$parts = explode( '.', $filePath );

			$className = array_pop( $parts );
			
			
			switch($className) {
				case 'helper' :
					$className = ucfirst(array_pop( $parts )).ucfirst($className);
					break;

				Default :
					$className = ucfirst($className);
					break;
			}

			$path  = str_replace( '.', DS, $filePath );
			
			if (strpos($filePath, 'phocagallery') === 0) {
				$className	= 'PhocaGallery'.$className;
				$classes	= JLoader::register($className, $base.DS.$path.'.php');
				$rs			= isset($classes[strtolower($className)]);
			} else {
				// If it is not in the joomla namespace then we have no idea if
				// it uses our pattern for class names/files so just include
				// if the file exists or set it to false if not

				$filename = $base.DS.$path.'.php';
				if (is_file($filename)) {
					$rs   = (bool) include $filename;
				} else {
					// if the file doesn't exist fail
					$rs   = false;

					// note: JLoader::register does an is_file check itself so we don't need it above, we do it here because we
					// try to load the file directly and it may not exist which could cause php to throw up nasty warning messages
					// at us so we set it to false here and hope that if the programmer is good enough they'll check the return value
					// instead of hoping it'll work. remmeber include only fires a warning, so $rs was going to be false with a nasty
					// warning message
				}
			}

			PhocaGalleryLoader::$paths[$keyPath] = $rs;
		}
		
		return PhocaGalleryLoader::$paths[$keyPath];
	}
}

function phocagalleryimport($path) {
	return PhocaGalleryLoader::import($path);
}
