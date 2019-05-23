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
phocagalleryimport('phocagallery.path.path');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.utils.utils');

/*
 * Obsolete
 */

class PhocaGalleryImageBgImage
{
	public static function createBgImage($data, &$errorMsg) {
	
		$params 		= JComponentHelper::getParams('com_phocagallery') ;
		$jfile_thumbs	= $params->get( 'jfile_thumbs', 1 );
		$jpeg_quality	= $params->get( 'jpeg_quality', 85 );
		$jpeg_quality	= PhocaGalleryImage::getJpegQuality($jpeg_quality);
		$formatIcon		= 'png';
		$path			= PhocaGalleryPath::getPath();
		
		$fileIn 	= $fileOut = $path->image_abs_front. $data['image'] .'.'. $formatIcon;
	
		if ($fileIn !== '' && JFile::exists($fileIn)) {
		
			$memory 			= 8;
			$memoryLimitChanged = 0;
			
			$memory = (int)ini_get( 'memory_limit' );
			if ($memory == 0) {
				$memory = 8;
			}
			
			// Try to increase memory
			if ($memory < 50) {
				ini_set('memory_limit', '50M');
				$memoryLimitChanged = 1;
			}
			
			$imageWidth 			= $data['iw'];
			$imageHeight			= $data['ih'];
			$completeImageWidth 	= $imageWidth + 18;
			$completeImageHeight	= $imageHeight + 18;

			$completeImageBackground	= $data['sbgc'];
			$retangleColor				= $data['ibgc'];
			$borderColor				= $data['ibrdc'];
			$shadowColor				= $data['iec'];
			$effect						= $data['ie'];// shadow or glow

			$imgX	= 6; $imgWX = $imageWidth + 5 + $imgX;// Image Width + space (padding) + Start Position
			$imgY	= 6; $imgHY = $imageHeight + 5 + $imgY;
			$brdX 	= $imgX - 1; $brdWX = $imgWX + 1;
			$brdY	= $imgY - 1; $brdHY = $imgHY + 1;
			
			// Crate an image
			$img 	= @imagecreatetruecolor($completeImageWidth, $completeImageHeight);
			if (!$img) {
				$errorMsg = 'ErrorNoImageCreateTruecolor';
				return false;
			}
			
			if ($completeImageBackground == '') {
				switch($formatIcon) {
					case 'jpg':
					case 'jpeg':
					case 'gif':
						$completeImageBackground = '#ffffff';
					break;
					case 'png':
					case 'webp':
						@imagealphablending($img,false);
						imagefilledrectangle($img,0,0,$completeImageWidth,$completeImageHeight,imagecolorallocatealpha($img,255,255,255,127));
						@imagealphablending($img,true);
					break;
				}
			} else {
				$bGClr	= PhocaGalleryUtils::htmlToRgb($completeImageBackground);
				imagefilledrectangle($img, 0, 0, $completeImageWidth, $completeImageHeight, imagecolorallocate($img, $bGClr[0], $bGClr[1], $bGClr[2]));
			}
			
			// Create Retangle
			if ($retangleColor != '') {
				$rtgClr		= PhocaGalleryUtils::htmlToRgb($retangleColor);
				$retangle 	= imagecolorallocate($img, $rtgClr[0], $rtgClr[1], $rtgClr[2]);
			}
			// Create Border
			if ($borderColor != '') {
				$brdClr		= PhocaGalleryUtils::htmlToRgb($borderColor);
				$border 	= imagecolorallocate($img, $brdClr[0], $brdClr[1], $brdClr[2]);
			}

			// Effect (shadow,glow)
			if ((int)$effect > 0)
			if ($shadowColor != '') {
				$shdClr	= PhocaGalleryUtils::htmlToRgb($shadowColor);
			
				if ((int)$effect == 3) {
					$shdX = $brdX  - 1;
					$shdY = $brdY  - 1;
					$effectArray = array(55,70,85,100,115);
				} else if ((int)$effect == 2) {
					$shdX = $brdX  + 3;
					$shdY = $brdY  + 3;
					$effectArray = array(50, 70, 90, 110);
				} else {
					$shdX = $brdX  + 3;
					$shdY = $brdY  + 3;
					$effectArray = array(0,0,0,0);
				}
				$shdWX 	= $brdWX + 1;
				$shdHY	= $brdHY + 1;
				
				
				foreach($effectArray as $key => $value) {
					$effectImg = @imagecolorallocatealpha($img, $shdClr[0], $shdClr[1], $shdClr[2],$value);
					if (!$effectImg) {
						$errorMsg = 'ErrorNoImageColorAllocateAlpha';
						return false;
					}
					imagerectangle($img, $shdX, $shdY, $shdWX, $shdHY, $effectImg);
				
					if ((int)$effect == 3) {
						$shdX--;
						$shdY--;
						
					} else if ((int)$effect == 2) {
						$shdX++;
						$shdY++;
						
					} else {
						//$shdX++;
						//$shdY++;
					}
					
					$shdWX++;
					$shdHY++;
					
				}
			}
				
			// Write Rectangle over the shadow
			if ($retangleColor != '') {
				imagefilledrectangle($img, $imgX, $imgY, $imgWX, $imgHY, $retangle);
			}
			if ($borderColor != '') {
				imagerectangle($img, $brdX, $brdY, $brdWX, $brdHY, $border);
			}
		
			
			switch($formatIcon) {
				case 'jpg':
				case 'jpeg':
					if (!function_exists('ImageJPEG')) {
						$errorMsg = 'ErrorNoJPGFunction';
						return false;
					}

					if ($jfile_thumbs == 1) {
						ob_start();
						if (!@ImageJPEG($img, NULL, $jpeg_quality)) {
							ob_end_clean();
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
						$imgJPEGToWrite = ob_get_contents();
						ob_end_clean();
						
						if(!JFile::write( $fileOut, $imgJPEGToWrite)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					} else {
						if (!@ImageJPEG($img, $fileOut, $jpeg_quality)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					}
				break;
				
				case 'png' :
					if (!function_exists('ImagePNG')) {
						$errorMsg = 'ErrorNoPNGFunction';
						return false;
					}
					@imagesavealpha($img, true);
					if ($jfile_thumbs == 1) {
						ob_start();
						if (!@ImagePNG($img, NULL)) {
							ob_end_clean();
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
						$imgPNGToWrite = ob_get_contents();
						ob_end_clean();
						
						if(!JFile::write( $fileOut, $imgPNGToWrite)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					} else {
						if (!@ImagePNG($img, $fileOut)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					}
				break;
				
				case 'gif' :
					if (!function_exists('ImageGIF')) {
						$errorMsg = 'ErrorNoGIFFunction';
						return false;
					}
					
					if ($jfile_thumbs == 1) {
						ob_start();
						if (!@ImageGIF($img, NULL)) {
							ob_end_clean();
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
						$imgGIFToWrite = ob_get_contents();
						ob_end_clean();
						
						if(!JFile::write( $fileOut, $imgGIFToWrite)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					} else {
						if (!@ImageGIF($img, $fileOut)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					}
				break;

				case 'webp' :
					if (!function_exists('ImageWebp')) {
						$errorMsg = 'ErrorNoWEBPFunction';
						return false;
					}
					@imagesavealpha($img, true);
					if ($jfile_thumbs == 1) {
						ob_start();
						if (!@imagewebp($img, NULL)) {
							ob_end_clean();
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
						$imgWEBPToWrite = ob_get_contents();
						ob_end_clean();
						
						if(!JFile::write( $fileOut, $imgWEBPToWrite)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					} else {
						if (!@imagewebp($img, $fileOut)) {
							$errorMsg = 'ErrorWriteFile';
							return false;
						}
					}
				break;				
				
				Default:
					$errorMsg =  'ErrorNotSupportedImage';
					return false;
				break;
			}
			
			// free memory
			ImageDestroy($img);// Original
	            
			if ($memoryLimitChanged == 1) {
				$memoryString = $memory . 'M';
				ini_set('memory_limit', $memoryString);
			}
	        
			return true; // Success
		}
		
		$errorMsg = 'Error2';
		return false;
	}
}
?>