<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );
phocagalleryimport('phocagallery.render.renderprocess');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.image.image');

class PhocaGalleryImageRotate
{
	function rotateImage($thumbName, $size, $angle=90, &$errorMsg) {
	
		$params 		= JComponentHelper::getParams('com_phocagallery') ;
		$jfile_thumbs	= $params->get( 'jfile_thumbs', 1 );
		$jpeg_quality	= $params->get( 'jpeg_quality', 85 );
		$jpeg_quality	= PhocaGalleryImage::getJpegQuality($jpeg_quality);
	
		// Try to change the size
		$memory = 8;
		$memoryLimitChanged = 0;
		$memory = (int)ini_get( 'memory_limit' );
		if ($memory == 0) {
			$memory = 8;
		}

		$fileIn 	= $thumbName->abs;
		$fileOut 	= $thumbName->abs;
	
		if ($fileIn !== '' && file_exists($fileIn)) {
			
			//array of width, height, IMAGETYPE, "height=x width=x" (string)
	        list($w, $h, $type) = GetImageSize($fileIn);
			
			// we got the info from GetImageSize
			if ($w > 0 && $h > 0 && $type !='') {
				// Change the $w against $h because of rotating
				$src = array(0,0, $w, $h);
				$dst = array(0,0, $h, $w);
			} else {
				$errorMsg = 'ErrorWorHorType';
				return false;
			}
			
			// Try to increase memory
			if ($memory < 50) {
				ini_set('memory_limit', '50M');
				$memoryLimitChanged = 1;
			}
			
	        switch($type)
	        {
	            case IMAGETYPE_JPEG:
					if (!function_exists('ImageCreateFromJPEG')) {
						$errorMsg = 'ErrorNoJPGFunction';
						return false;
					}
					$image1 = ImageCreateFromJPEG($fileIn);
					break;
	            case IMAGETYPE_PNG :
					if (!function_exists('ImageCreateFromPNG')) {
						$errorMsg = 'ErrorNoPNGFunction';
						return false;
					}
					$image1 = ImageCreateFromPNG($fileIn);
					break;
	            case IMAGETYPE_GIF :
					if (!function_exists('ImageCreateFromGIF')) {
						$errorMsg = 'ErrorNoGIFFunction';
						return false;
					}
					$image1 = ImageCreateFromGIF($fileIn);
					break;
	            case IMAGETYPE_WBMP:
					if (!function_exists('ImageCreateFromWBMP')) {
						$errorMsg = 'ErrorNoWBMPFunction';
						return false;
					}
					$image1 = ImageCreateFromWBMP($fileIn);
					break;
	            Default:
					$errorMsg = 'ErrorNotSupportedImage';
					return false;
					break;
	        }
			
			if ($image1) {
				// Building image for ROTATING
			/*	$image2 = @ImageCreateTruecolor($dst[2], $dst[3]);
				if (!$image2) {
					return 'ErrorNoImageCreateTruecolor';
				}*/
				
			/*	if(!function_exists("imagerotate")) {
					$errorMsg = 'ErrorNoImageRotate';
					return false;
				}*/
				switch($type)
				{
					case IMAGETYPE_PNG:
					//	imagealphablending($image1, false);
					//	imagesavealpha($image1, true);
						if(!function_exists("imagecolorallocate")) {
							$errorMsg = 'ErrorNoImageColorAllocate';
							return false;
						}
						if(!function_exists("imagefill")) {
							$errorMsg = 'ErrorNoImageFill';
							return false;
						}
						if(!function_exists("imagecolortransparent")) {
							$errorMsg = 'ErrorNoImageColorTransparent';
							return false;
						}
						$colBlack 	= imagecolorallocate($image1, 0, 0, 0);
						if(!function_exists("imagerotate")) {
							$image2 	= PhocaGalleryImageRotate::imageRotate($image1, $angle, $colBlack);
						} else {
							$image2 	= imagerotate($image1, $angle, $colBlack);
						}
						imagefill($image2, 0, 0, $colBlack);
						imagecolortransparent($image2, $colBlack);
					break;
					Default:
						if(!function_exists("imagerotate")) {
							$image2 	= PhocaGalleryImageRotate::imageRotate($image1, $angle, 0);
						} else {
							$image2 = imageRotate($image1, $angle, 0);
						}
					break;
				}

				// Get the image size and resize the rotated image if necessary
				$rotateWidth 	= imagesx($image2);// Get the size from rotated image
				$rotateHeight 	= imagesy($image2);// Get the size from rotated image
				$parameterSize 	= PhocaGalleryFileThumbnail::getThumbnailResize($size);
				$newWidth		= $parameterSize['width']; // Get maximum sizes, they can be displayed
				$newHeight		= $parameterSize['height'];// Get maximum sizes, they can be displayed
					
				$scale = (($newWidth / $rotateWidth) < ($newHeight / $rotateHeight)) ? ($newWidth / $rotateWidth) : ($newHeight / $rotateHeight); // smaller rate
				$src = array(0,0, $rotateWidth, $rotateHeight);
				$dst = array(0,0, floor($rotateWidth*$scale), floor($rotateHeight*$scale));
						
				// If original is smaller than thumbnail size, don't resize it
				if ($src[2] > $dst[2] || $src[3] > $dst[3]) {
					
					// Building image for RESIZING THE ROTATED IMAGE
					$image3 = @ImageCreateTruecolor($dst[2], $dst[3]);
					if (!$image3) {
						$errorMsg = 'ErrorNoImageCreateTruecolor';
						return false;
					}
					ImageCopyResampled($image3, $image2, $dst[0],$dst[1], $src[0],$src[1], $dst[2],$dst[3], $src[2],$src[3]);
					switch($type)
					{
						case IMAGETYPE_PNG:
						//	imagealphablending($image2, true);
						//	imagesavealpha($image2, true);
							if(!function_exists("imagecolorallocate")) {
								$errorMsg = 'ErrorNoImageColorAllocate';
								return false;
							}
							if(!function_exists("imagefill")) {
								$errorMsg = 'ErrorNoImageFill';
								return false;
							}
							if(!function_exists("imagecolortransparent")) {
								$errorMsg = 'ErrorNoImageColorTransparent';
								return false;
							}
							$colBlack 	= imagecolorallocate($image3, 0, 0, 0);
							imagefill($image3, 0, 0, $colBlack);
							imagecolortransparent($image3, $colBlack);
						break;
					}
						
				} else {
					$image3 = $image2;
					
				}
						
				switch($type) {
		            case IMAGETYPE_JPEG:
						if (!function_exists('ImageJPEG')) {
							$errorMsg = 'ErrorNoJPGFunction';
							return false;
						}

						if ($jfile_thumbs == 1) {
							ob_start();
							if (!@ImageJPEG($image3, NULL, $jpeg_quality)) {
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
							if (!@ImageJPEG($image3, $fileOut, $jpeg_quality)) {
								$errorMsg = 'ErrorWriteFile';
								return false;
							}
						}
					break;
		            
					case IMAGETYPE_PNG :
						if (!function_exists('ImagePNG')) {
							$errorMsg = 'ErrorNoPNGFunction';
							return false;
						}
						
						if ($jfile_thumbs == 1) {
							ob_start();
							if (!@ImagePNG($image3, NULL)) {
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
							if (!@ImagePNG($image3, $fileOut)) {
								$errorMsg = 'ErrorWriteFile';
								return false;
							}
						}
					break;
		            
					case IMAGETYPE_GIF :
						if (!function_exists('ImageGIF')) {
							$errorMsg = 'ErrorNoGIFFunction';
							return false;
						}
						
						if ($jfile_thumbs == 1) {
							ob_start();
							if (!@ImageGIF($image3, NULL)) {
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
							if (!@ImageGIF($image3, $fileOut)) {
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
				ImageDestroy($image1);// Original
	            ImageDestroy($image2);// Rotated
				ImageDestroy($image3);// Resized
	            
				if ($memoryLimitChanged == 1) {
					$memoryString = $memory . 'M';
					ini_set('memory_limit', $memoryString);
				}
	            return true; // Success
	        } else {
				$errorMsg = PhocaGalleryUtils::setMessage($errorMsg, JText::_('COM_PHOCAGALLERY_ERROR_IMAGE_NOT_PROCESS'));
				return false;
			}
			
			if ($memoryLimitChanged == 1) {
				$memoryString = $memory . 'M';
				ini_set('memory_limit', $memoryString);
			}
	    }
		$errorMsg = JText::_('COM_PHOCAGALLERY_FILEORIGINAL_NOT_EXISTS');
		return false;
	}
	
		/* This function is provided by php manual (function.imagerotate.php)
	It's a workaround to enables image rotation on distributions which do not
	use the bundled gd library (e.g. Debian, Ubuntu).
	*/
	function imageRotate($src_img, $angle, $colBlack = 0) {

		if (!imageistruecolor($src_img))
		{
			$w = imagesx($src_img);
			$h = imagesy($src_img);
			$t_im = imagecreatetruecolor($w,$h);
			imagecopy($t_im,$src_img,0,0,0,0,$w,$h);
			$src_img = $t_im;
		}

		$src_x = imagesx($src_img);
		$src_y = imagesy($src_img);
		if ($angle == 180)
		{
			$dest_x = $src_x;
			$dest_y = $src_y;
		}
		elseif ($src_x <= $src_y)
		{
			$dest_x = $src_y;
			$dest_y = $src_x;
		}
		elseif ($src_x >= $src_y)
		{
			$dest_x = $src_y;
			$dest_y = $src_x;
		}

		$rotate=imagecreatetruecolor($dest_x,$dest_y);
		imagealphablending($rotate, false);

		switch ($angle)
		{
			case 270:
				for ($y = 0; $y < ($src_y); $y++)
				{
					for ($x = 0; $x < ($src_x); $x++)
					{
						$color = imagecolorat($src_img, $x, $y);
						imagesetpixel($rotate, $dest_x - $y - 1, $x, $color);
					}
				}
				break;
			case 90:
				for ($y = 0; $y < ($src_y); $y++)
				{
					for ($x = 0; $x < ($src_x); $x++)
					{
						$color = imagecolorat($src_img, $x, $y);
						imagesetpixel($rotate, $y, $dest_y - $x - 1, $color);
					}
				}
				break;
			case 180:
				for ($y = 0; $y < ($src_y); $y++)
				{
					for ($x = 0; $x < ($src_x); $x++)
					{
						$color = imagecolorat($src_img, $x, $y);
						imagesetpixel($rotate, $dest_x - $x - 1, $dest_y - $y - 1,
								$color);
					}
				}
				break;
			Default: $rotate = $src_img;
		};
		return $rotate;
	}
}
?>