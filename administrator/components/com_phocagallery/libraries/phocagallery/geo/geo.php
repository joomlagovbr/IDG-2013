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

class PhocaGalleryGeo
{
	/*
	 * Geotagging
	 * If no lat or lng will be set by image, it will be automatically set by category
	 */
	public static function findLatLngFromCategory($categories) {
		$output['lat'] = '';
		$output['lng'] = '';
		foreach ($categories as $category) {
			if (isset($category->latitude) && isset($category->longitude)) {
				if ($category->latitude != '' && $category->latitude != '') {
					$output['lat'] = $category->latitude;
				}
				if ($category->longitude != '' && $category->longitude != '') {
					$output['lng'] = $category->longitude;
				}

				if ($output['lat'] != '' && $output['lng'] != '') {
					return $output;
				}
			} 
		}
		// If nothing will be found, paste some lng, lat
		$output['lat'] = 50.079623358200884;
		$output['lng'] = 14.429919719696045;
		return $output;
	}
	
	public static function getGeoCoords($filename){
      
		$lat = $long = '';
		$fileOriginal = PhocaGalleryFile::getFileOriginal($filename);
	  
	if (!function_exists('exif_read_data')) {
		return array('latitude' => 0, 'longitude' => 0);
	} else {
		if (strtolower(JFile::getExt($fileOriginal)) != 'jpg') {
			return array('latitude' => 0, 'longitude' => 0);
		}
		
		// Not happy but @ must be added because of different warnings returned by exif functions - can break multiple upload
		$exif 		= @exif_read_data($fileOriginal, 0, true);
		if (empty($exif)) {
			return array('latitude' => 0, 'longitude' => 0);
		}

		
		if (isset($exif['GPS']['GPSLatitude'][0])) {$GPSLatDeg 		= explode('/',$exif['GPS']['GPSLatitude'][0]);}
		if (isset($exif['GPS']['GPSLatitude'][1])) {$GPSLatMin 		= explode('/',$exif['GPS']['GPSLatitude'][1]);}
		if (isset($exif['GPS']['GPSLatitude'][2])) {$GPSLatSec 		= explode('/',$exif['GPS']['GPSLatitude'][2]);}
		if (isset($exif['GPS']['GPSLongitude'][0])) {$GPSLongDeg 	= explode('/',$exif['GPS']['GPSLongitude'][0]);}
		if (isset($exif['GPS']['GPSLongitude'][1])) {$GPSLongMin 	= explode('/',$exif['GPS']['GPSLongitude'][1]);}
		if (isset($exif['GPS']['GPSLongitude'][2])) {$GPSLongSec 	= explode('/',$exif['GPS']['GPSLongitude'][2]);}
		
		
		if (isset($GPSLatDeg[0]) && isset($GPSLatDeg[1]) && (int)$GPSLatDeg[1] > 0
		 && isset($GPSLatMin[0]) && isset($GPSLatMin[1]) && (int)$GPSLatMin[1] > 0
		 && isset($GPSLatSec[0]) && isset($GPSLatSec[1]) && (int)$GPSLatSec[1] > 0) {

			$lat = $GPSLatDeg[0]/$GPSLatDeg[1]+
				($GPSLatMin[0]/$GPSLatMin[1])/60+
				($GPSLatSec[0]/$GPSLatSec[1])/3600;
				
			if(isset($exif['GPS']['GPSLatitudeRef']) && $exif['GPS']['GPSLatitudeRef'] == 'S'){$lat=$lat*(-1);}
			
		
		}
		
		 
		if (isset($GPSLongDeg[0]) && isset($GPSLongDeg[1]) && (int)$GPSLongDeg[1] > 0
		 && isset($GPSLongMin[0]) && isset($GPSLongMin[1]) && (int)$GPSLongMin[1] > 0
		 && isset($GPSLongSec[0]) && isset($GPSLongSec[1]) && (int)$GPSLongSec[1] > 0) {

	
			$long = $GPSLongDeg[0]/$GPSLongDeg[1]+
				($GPSLongMin[0]/$GPSLongMin[1])/60+
				($GPSLongSec[0]/$GPSLongSec[1])/3600;
				
			if(isset($exif['GPS']['GPSLongitudeRef']) && $exif['GPS']['GPSLongitudeRef'] == 'W'){$long=$long*(-1);}

		}
		


		return array('latitude' => $lat, 'longitude' => $long);
	  }
   }
 
}
?>