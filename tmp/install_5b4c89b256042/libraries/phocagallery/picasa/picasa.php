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
phocagalleryimport('phocagallery.utils.utils');

class PhocaGalleryPicasa
{

	public static function getSize(&$mediumT) {
	
		// If small and medium can be taken from $thumbSize, take it from here as these images can be cropped
		$thumbSize 	= array(32, 48, 64, 72, 104, 144, 150, 160);
		$imgMax		= array(94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600);
		$paramsC 	= JComponentHelper::getParams('com_phocagallery');
		
		$lw 		= $paramsC->get( 'large_image_width', 640 );
		$mw 		= $paramsC->get( 'medium_image_width', 100 );
		$sw 		= $paramsC->get( 'small_image_width', 50 );
		$crop 		= $paramsC->get( 'crop_thumbnail', 5 );
		$output		= array();
		$outputS	= $outputM	= $outputL	=	$outputLargeSize = '';
		
		// Large
		foreach ($imgMax as $value) {
			// First value which is greater than the large_image_width will be taken
			if ((int)$value > (int)$lw || (int)$value == (int)$lw) {
				$outputL		= '&imgmax='.(int)$value;
				$outputLargeSize= $value;
				break;
			}
		}
		// Small
		foreach ($thumbSize as $value) {
			// First value which is greater than the large_image_width will be taken
			if ((int)$value > (int)$sw || (int)$value == (int)$sw) {
				$outputS		= '&thumbsize='.(int)$value;
				break;
			}
		}
		
		// Medium
		// Try to handle it as thumbnail
		foreach ($thumbSize as $value) {
			// First value which is greater than the large_image_width will be taken
			if ((int)$value > (int)$mw || (int)$value == (int)$mw) {
				//$outputM		= '&thumbsize='.(int)$value;
				$outputM		= ','.(int)$value;
				$mediumT		= 1;
				break;
			}
		}
		// Try to find it in imgmax
		if ($mediumT != 1) {
			foreach ($imgMax as $value) {
				// First value which is greater than the large_image_width will be taken
				if ((int)$value > (int)$mw || (int)$value == (int)$mw) {
					$outputM		= '&imgmax='.(int)$value;
					$mediumT		= 0;
					break;
				}
			}
		
		}
		
		// Small Crop
		
		if ($crop == 3 || $crop == 5 || $crop == 6 ||$crop == 7) {
			$outputS = $outputS . 'c';
		} else {
			$outputS = $outputS . 'u';
		}
		
		// Medium Crop
		if ($mediumT == 1) {
			if ($crop == 2 || $crop == 4 || $crop == 5 ||$crop == 7) {
				$outputM = $outputM . 'c';
			} else {
				$outputM = $outputM . 'u';
			}
		}
		if ($mediumT == 1) {
			$output['lsm'] = $outputL . $outputS . $outputM;
		} else {
			$output['lsm'] = $outputL . $outputS;
		}
		if ($mediumT != 1) {
			$output['m'] = $outputM;
		}
		// This we need for getting info about size and and removing this size to get an original image
		// It is not lsm
		$output['ls']	= $outputLargeSize;
		
		return $output;
	}
	
	/*
	 * Used for external images: Picasa, Facebook
	 */
	 
	
	public static function correctSizeWithRate($width, $height, $corWidth = 100, $corHeight = 100, $diffThumbHeight = 0) {
		
		
		
		$image['width']		= $corWidth;
		$image['height']	= $corHeight;
		
		if ((int)$diffThumbHeight > 0) {
			$ratio = $width / $height;
			$image['height'] = $ratio * $image['height'];
			return $image;
		}
		
		// Don't do anything with images:
		if ($width < $corWidth && $height < $corHeight) {
			$image['width']		= $width;
			$image['height']	= $height;
		} else {
		
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
		}
		return $image;
	}
	
	/* 
	 * Used while pagination
	 */
	public static function renderProcessPage($id, $refreshUrl, $countInfo = '') {
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
		echo '<head>'. "\n";
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
		echo '<title>'.JText::_( 'COM_PHOCAGALLERY_PICASA_LOADING_DATA').'</title>'. "\n";
		echo '<link rel="stylesheet" href="'.JURI::root(true).'/media/com_phocagallery/css/administrator/phocagallery.css" type="text/css" />';

		echo '</head>'. "\n";
		echo '<body>'. "\n";
		
		echo '<div style="text-align:right;padding:10px"><a style="font-family: sans-serif, Arial;font-weight:bold;color:#fc0000;font-size:14px;" href="index.php?option=com_phocagallery&task=phocagalleryc.edit&id='.(int)$id.'">' .JText::_( 'COM_PHOCAGALLERY_STOP_LOADING_PICASA_IMAGES' ).'</a></div>';
		
		echo '<div id="loading-ext-img-processp" style="font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px"><div class="loading"><div><center>'. JHTML::_('image', 'media/com_phocagallery/images/administrator/icon-loading.gif', JText::_('COM_PHOCAGALLERY_LOADING') ) .'</center></div><div>&nbsp;</div><div><center>'.JText::_('COM_PHOCAGALLERY_PICASA_LOADING_DATA').'</center></div>';
		
		echo $countInfo;
		echo '</div></div>';
		
		echo '<meta http-equiv="refresh" content="1;url='.$refreshUrl.'" />';
		echo '</body></html>';
		exit;
	}
	
	public static function loadDataByAddress($address, $type, &$errorMsg) {
	
		$curl = $fopen = 1;
		$data	= '';
		
		if(!function_exists("curl_init")){
			$errorMsg .= JText::_('COM_PHOCAGALLERY_PICASA_NOT_LOADED_CURL');
			$curl = 0;
		}
		
		if(!PhocaGalleryUtils::iniGetBool('allow_url_fopen')){
			if ($errorMsg != '') {
				$errorMsg .= '<br />';
			}
			$errorMsg .= JText::_('COM_PHOCAGALLERY_PICASA_NOT_LOADED_FOPEN');
			$fopen = 0;
		}
		
		if ($fopen == 0 && $curl == 0) {
			return false;
		}
		
		if ($curl == 1) {
			$init 	= curl_init(); 
			curl_setopt ($init, CURLOPT_URL, 			$address);
			curl_setopt ($init, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); // Experimental
			curl_setopt ($init, CURLOPT_RETURNTRANSFER,	1); 
			curl_setopt ($init, CURLOPT_CONNECTTIMEOUT,	10);   			
		    $data 	= curl_exec($init); 
		    curl_close($init);
		} else {
			$data	= @file_get_contents($address);
		}
		
		if ($data == '') {
			if ($errorMsg != '') {
				$errorMsg .= '<br />';
			}
			switch ($type) {
				case 'album':
					$errorMsg = JText::_('COM_PHOCAGALLERY_PICASA_NOT_LOADED_IMAGE');
				break;
			
				case 'user':
				Default:
					$errorMsg .= JText::_('COM_PHOCAGALLERY_PICASA_NOT_LOADED_USER');
				break;
			
			}
			return false;
		}
		return $data;
	
	}

}
?>