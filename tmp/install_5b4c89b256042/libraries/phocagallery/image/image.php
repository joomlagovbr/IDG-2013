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

class PhocaGalleryImage
{

	public static function getImageSize($filename, $returnString = 0, $extLink = 0) {
		
		phocagalleryimport('phocagallery.image.image');
		phocagalleryimport('phocagallery.path.path');
		
		if ($extLink == 1) {
			$fileNameAbs	= $filename;
		} else {
			$path			= PhocaGalleryPath::getPath();
			$fileNameAbs	= JPath::clean($path->image_abs . $filename);
		
			if (!JFile::exists($fileNameAbs)) {
				$fileNameAbs	= $path->image_abs_front . 'phoca_thumb_l_no_image.png';
			}
		}

		if ($returnString == 1) {
			$imageSize = @getimagesize($fileNameAbs);
			return $imageSize[0] . ' x '.$imageSize[1];
		} else {
			return @getimagesize($fileNameAbs);
		}
	}
	
	public static function getRealImageSize($filename, $size = 'large', $extLink = 0) {
	
		phocagalleryimport('phocagallery.file.thumbnail');
		
		if ($extLink == 1) {
			list($w, $h, $type) = @getimagesize($filename);
		} else {
			$thumbName			= PhocaGalleryFileThumbnail::getThumbnailName ($filename, $size);
			list($w, $h, $type) = @getimagesize($thumbName->abs);
		}
		$size = array();
		if (isset($w) && isset($h)) {
			$size['w'] 	= $w;
			$size['h']	= $h;
		} else {
			$size['w'] 	= 0;
			$size['h']	= 0;
		}
		return $size;
	}
	
	
	public static function correctSizeWithRate($width, $height, $corWidth = 100, $corHeight = 100) {
		$image['width']		= $corWidth;
		$image['height']	= $corHeight;
		

		
		if ($width > $height) {
			if ($width > $corWidth) {
				$image['width']		= $corWidth;
				$rate 				= $width / $corWidth;
				$image['height']	= $height / $rate;
			} else {
				$image['width']		= $width;
				$image['height']	= $height;
			}
		} else {
			if ($height > $corHeight) {
				$image['height']	= $corHeight;
				$rate 				= $height / $corHeight;
				$image['width'] 	= $width / $rate;
			} else {
				$image['width']		= $width;
				$image['height']	= $height;
			}
		}
		return $image;
	}
	
	public static function correctSize($imageSize, $size = 100, $sizeBox = 100, $sizeAdd = 0) {

		$image['size']	= $imageSize;
		if ((int)$image['size'] < (int)$size ) {
			$image['size']		= $size;
			$image['boxsize'] 	= (int)$size + (int)$sizeAdd;
		} else {
			$image['boxsize'] 	= (int)$image['size'] + (int)$sizeAdd;
		}
		return $image;		
	}
	
	public static function correctSwitchSize($switchHeight, $switchWidth) {

		$switchImage['height'] 	= $switchHeight;
		$switchImage['centerh']	= ($switchHeight / 2) - 18;
		$switchImage['width'] 	= $switchWidth;
		$switchImage['centerw']	= ($switchWidth / 2) - 18;
		$switchImage['height']	= $switchImage['height'] + 5;
		return $switchImage;		
	}
	
	/*
	 * type ... 1 categories, 2 category view
	 */
	public static function setBoxSize($p, $type = 1) {

	
		$w 	= 20;
		$w2 = 25;
		$w3 = 18;
		$w4 = 40;
		
		$boxWidth	= 0;
		$boxSize['width'] 	= 0;
		$boxSize['height']	= 0;
		
		
		if (isset($p['imagewidth'])) {
			$boxSize['width'] = $boxSize['width'] + (int)$p['imagewidth'];
		}
	
		if (isset($p['imageheight'])) {
			$boxSize['height'] = $boxSize['height'] + (int)$p['imageheight'];
		}
		
		if (isset($p['display_name']) && ($p['display_name'] == 1 || $p['display_name'] == 2)) {
			$boxSize['height'] = $boxSize['height'] + $w;
		}
			
		if ($type == 3) {
			$boxSize['height'] = $boxSize['height'] + $w;
			return $boxSize;
		}
		
		if ( (isset($p['display_rating']) && $p['display_rating'] == 1) || (isset($p['display_rating_img']) && $p['display_rating_img'] > 0)) {
			
			
			if ($type == 1) {
				$boxSize['height'] = $boxSize['height'] + $w4;
			} else {
				$boxSize['height'] = $boxSize['height'] + $w;
			}
		}
		

		if (isset($p['displaying_tags_true']) && $p['displaying_tags_true'] == 1) {
			$boxSize['height'] = $boxSize['height'] + (int) + $w3;
		}
		
		
		

		if (isset($p['display_icon_detail']) && $p['display_icon_detail'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		if (isset($p['display_icon_download']) && (int)$p['display_icon_download'] > 0) {
			$boxWidth = $boxWidth + $w;
		}
		if (isset($p['display_icon_vm']) && $p['display_icon_vm'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
	
		if (isset($p['start_cooliris']) && $p['start_cooliris'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['trash']) && $p['trash'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['publish_unpublish']) && $p['publish_unpublish'] == 1) {
			$boxWidth = $boxWidth + $w;
		}

		if (isset($p['display_icon_geo_box']) && $p['display_icon_geo_box'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['display_camera_info']) && $p['display_camera_info'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['display_icon_extlink1_box']) && $p['display_icon_extlink1_box'] == 1) {
			$boxWidth = $boxWidth + $w;
		}

		if (isset($p['display_icon_extlink2_box']) && $p['display_icon_extlink2_box'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['approved_not_approved']) && $p['approved_not_approved'] == 1) {
			$boxWidth = $boxWidth + $w;
		}
		
		if (isset($p['display_icon_commentimg_box']) && $p['display_icon_commentimg_box'] == 1) {
			$boxWidth = $boxWidth + $w;
		}

		$boxHeightRows 			= ceil($boxWidth/$boxSize['width']);
		$boxSize['height'] 		= ($w * $boxHeightRows) + $boxSize['height'];

		// LAST
		if ($type == 1) {
			if (isset($p['categories_box_space'])) {
				$boxSize['height'] = $boxSize['height'] + (int)$p['categories_box_space'];
			}
		} else {
			if (isset($p['category_box_space'])) {
				$boxSize['height'] = $boxSize['height'] + (int)$p['category_box_space'];
			}
		}
		
		return $boxSize;
	}
	
	public static function getJpegQuality($jpegQuality) {
		if ((int)$jpegQuality < 0) {
			$jpegQuality = 0;
		}
		if ((int)$jpegQuality > 100) {
			$jpegQuality = 100;
		}
		return $jpegQuality;
	}
	
	/*
	 * Transform image (only with html method) for overlib effect e.g.
	 *
	 * @param array An array of image size (width, height)
	 * @param int Rate
	 * @access public
	 */
	 
	 public static function getTransformImageArray($imgSize, $rate) {
		if (isset($imgSize[0]) && isset($imgSize[1])) {
			$w = (int)$imgSize[0];
			$h = (int)$imgSize[1];
		
			if ($w != 0) {$w = $w/$rate;} // plus or minus should be divided, not null
			if ($h != 0) {$h = $h/$rate;}
			$wHOutput = array('width' => $w, 'height' => $h, 'style' => 'background: #fff url('.JURI::base(true).'/media/com_phocagallery/images/icon-loading2.gif) 50% 50% no-repeat;');
		} else {
			$w = $h = 0;
			$wHOutput = array();
		}
		return $wHOutput;
	}
	
	/*
	 * Used for albums or specific images
	 * Check if it is Picasa or Facebook category or image
	 * If we ask only on image, the second parameter will be empty and will be ignnored
	 * If we ask album, first check Picasa album, second check Facebook album
	 */
	
	public static function isExtImage($extid, $extfbcatid = '') {
	
		// EXTID (IMAGES): Picasa - yes, Facebook - yes
		// EXTID (ALBUMS): Picasa - yes, Facebook - no
		if (isset($extid) && $extid != '') {
			return true;
		}
		
		// EXTFBCATID (IMAGES): Picasa - no, Facebook - no
		// EXTFBCATID (ALBUMS): Picasa - no, Facebook - yes
		if (isset($extfbcatid) && $extfbcatid != '') {
			return true;
		}
		
		
		return false;
	}
	
	public static function getImageByImageId($id = 0) {
		
		$db 	= JFactory::getDBO();
		$query = ' SELECT a.id, a.title, c.title as category_title'
				.' FROM #__phocagallery AS a'
				.' LEFT JOIN #__phocagallery_categories AS c ON c.id = a.catid'
				.' WHERE a.id = '.(int)$id
				.' GROUP BY a.id, a.title, c.title'
				.' ORDER BY a.id'
				.' LIMIT 1';
		$db->setQuery($query);
		$image = $db->loadObject();
		
		return $image;
	}
}
?>