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

class PhocaGalleryFileThumbnail
{
	/*
	 *Get thumbnailname
	 */
	public static function getThumbnailName($filename, $size) {
	
		$path		= PhocaGalleryPath::getPath();		
		$title 		= PhocaGalleryFile::getTitleFromFile($filename , 1);

		$thumbName	= new JObject();
		
		switch ($size) {
			case 'large':
			$fileNameThumb 	= 'phoca_thumb_l_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs'. '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;
			
			case 'large1':
			$fileNameThumb 	= 'phoca_thumb_l1_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs'. '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;
			
			case 'medium1':
			$fileNameThumb 	= 'phoca_thumb_m1_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			case 'medium2':
			$fileNameThumb 	= 'phoca_thumb_m2_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			case 'medium3':
			$fileNameThumb 	= 'phoca_thumb_m3_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			case 'medium':
			$fileNameThumb 	= 'phoca_thumb_m_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs'. '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;
			
			
			case 'small1':
			$fileNameThumb 	= 'phoca_thumb_s1_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			case 'small2':
			$fileNameThumb 	= 'phoca_thumb_s2_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			case 'small3':
			$fileNameThumb 	= 'phoca_thumb_s3_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
			
			default:
			case 'small':
			$fileNameThumb 	= 'phoca_thumb_s_'. $title;
			$thumbName->abs	= JPath::clean(str_replace($title, 'thumbs' . '/'. $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;	
		}
		
		$thumbName->rel = str_replace('//', '/', $thumbName->rel);

		return $thumbName;
	}
	
	public static function deleteFileThumbnail ($filename, $small=0, $medium=0, $large=0) {					
		
		if ($small == 1) {
			$fileNameThumbS = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'small');
			if (JFile::exists($fileNameThumbS->abs)) {
				JFile::delete($fileNameThumbS->abs);
			}
			$fileNameThumbS = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'small1');
			if (JFile::exists($fileNameThumbS->abs)) {
				JFile::delete($fileNameThumbS->abs);
			}
			$fileNameThumbS = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'small2');
			if (JFile::exists($fileNameThumbS->abs)) {
				JFile::delete($fileNameThumbS->abs);
			}
			$fileNameThumbS = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'small3');
			if (JFile::exists($fileNameThumbS->abs)) {
				JFile::delete($fileNameThumbS->abs);
			}
		}
		
		if ($medium == 1) {
			$fileNameThumbM = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'medium');
			if (JFile::exists($fileNameThumbM->abs)) {
				JFile::delete($fileNameThumbM->abs);
			}
			$fileNameThumbM = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'medium1');
			if (JFile::exists($fileNameThumbM->abs)) {
				JFile::delete($fileNameThumbM->abs);
			}
			$fileNameThumbM = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'medium2');
			if (JFile::exists($fileNameThumbM->abs)) {
				JFile::delete($fileNameThumbM->abs);
			}
			$fileNameThumbM = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'medium3');
			if (JFile::exists($fileNameThumbM->abs)) {
				JFile::delete($fileNameThumbM->abs);
			}
			
		}
		
		if ($large == 1) {
			$fileNameThumbL = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'large');
			if (JFile::exists($fileNameThumbL->abs)) {
				JFile::delete($fileNameThumbL->abs);
			}
			$fileNameThumbL = PhocaGalleryFileThumbnail::getThumbnailName ($filename, 'large1');
			if (JFile::exists($fileNameThumbL->abs)) {
				JFile::delete($fileNameThumbL->abs);
			}
		}
		return true;
	}
	
	/*
	 * Main Thumbnail creating function
	 *
	 * file 		= abc.jpg
	 * fileNo	= folder/abc.jpg
	 * if small, medium, large = 1, create small, medium, large thumbnail
	 */
	public static function getOrCreateThumbnail($fileNo, $refreshUrl, $small = 0, $medium = 0, $large = 0, $frontUpload = 0) {
	
		
		if ($frontUpload) {
			$returnFrontMessage = '';
		}
		
		$onlyThumbnailInfo = 0;
		if ($small == 0 && $medium == 0 && $large == 0) {
			$onlyThumbnailInfo = 1;
		}
		
		$paramsC 								= JComponentHelper::getParams('com_phocagallery');
		$additional_thumbnails 					= $paramsC->get( 'additional_thumbnails',0 );
		
		$path 									= PhocaGalleryPath::getPath();
		$origPathServer 						= str_replace(DS, '/', $path->image_abs);
		$file['name']							= PhocaGalleryFile::getTitleFromFile($fileNo, 1);
		$file['name_no']						= ltrim($fileNo, '/');
		$file['name_original_abs']				= PhocaGalleryFile::getFileOriginal($fileNo);
		$file['name_original_rel']				= PhocaGalleryFile::getFileOriginal($fileNo, 1);
		$file['path_without_file_name_original']= str_replace($file['name'], '', $file['name_original_abs']);
		$file['path_without_file_name_thumb']	= str_replace($file['name'], '', $file['name_original_abs'] . 'thumbs' . DS);
		//$file['path_without_name']				= str_replace(DS, '/', JPath::clean($origPathServer));
		//$file['path_with_name_relative_no']		= str_replace($origPathServer, '', $file['name_original']);
		/*
		$file['path_with_name_relative']		= $path['orig_rel_ds'] . str_replace($origPathServer, '', $file['name_original']);
		$file['path_with_name_relative_no']		= str_replace($origPathServer, '', $file['name_original']);
		
		$file['path_without_name']				= str_replace(DS, '/', JPath::clean($origPath.DS));
		$file['path_without_name_relative']		= $path['orig_rel_ds'] . str_replace($origPathServer, '', $file['path_without_name']);
		$file['path_without_name_relative_no']	= str_replace($origPathServer, '', $file['path_without_name']);
		$file['path_without_name_thumbs'] 		= $file['path_without_name'] .'thumbs';
		$file['path_without_file_name_original'] 			= str_replace($file['name'], '', $file['name_original']);
		$file['path_without_name_thumbs_no'] 	= str_replace($file['name'], '', $file['name_original'] .'thumbs');*/
		
		
		$ext = strtolower(JFile::getExt($file['name']));
		switch ($ext) {
			case 'jpg':
			case 'png':
			case 'gif':
			case 'jpeg':

			//Get File thumbnails name

			$thumbNameS 					= PhocaGalleryFileThumbnail::getThumbnailName ($fileNo, 'small');
			$file['thumb_name_s_no_abs'] 	= $thumbNameS->abs;
			$file['thumb_name_s_no_rel'] 	= $thumbNameS->rel;
			
			$thumbNameM						= PhocaGalleryFileThumbnail::getThumbnailName ($fileNo, 'medium');
			$file['thumb_name_m_no_abs'] 	= $thumbNameM->abs;
			$file['thumb_name_m_no_rel'] 	= $thumbNameM->rel;
			
			$thumbNameL						= PhocaGalleryFileThumbnail::getThumbnailName ($fileNo, 'large');
			$file['thumb_name_l_no_abs'] 	= $thumbNameL->abs;
			$file['thumb_name_l_no_rel'] 	= $thumbNameL->rel;


			// Don't create thumbnails from watermarks...			
			$dontCreateThumb	= PhocaGalleryFileThumbnail::dontCreateThumb($file['name']);
			if ($dontCreateThumb == 1) {
				$onlyThumbnailInfo = 1; // WE USE $onlyThumbnailInfo FOR NOT CREATE A THUMBNAIL CLAUSE
			}
			
			// We want only information from the pictures OR
			if ( $onlyThumbnailInfo == 0 ) {

				$thumbInfo = $fileNo;	
				//Create thumbnail folder if not exists
				$errorMsg = 'ErrorCreatingFolder';
				$creatingFolder = PhocaGalleryFileThumbnail::createThumbnailFolder($file['path_without_file_name_original'], $file['path_without_file_name_thumb'], $errorMsg );
					
				switch ($errorMsg) {
					case 'Success':
					//case 'ThumbnailExists':
					case 'DisabledThumbCreation':
					//case 'OnlyInformation':
					break;
					
					Default:
					
						// BACKEND OR FRONTEND
						if ($frontUpload !=1) {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl, $errorMsg, $frontUpload);
							exit;
						} else {
							$returnFrontMessage = $errorMsg;
						}
						
					break;	
				}
				
				// Folder must exist
				if (JFolder::exists($file['path_without_file_name_thumb'])) {				
					
					$errorMsgS = $errorMsgM = $errorMsgL = '';
					//Small thumbnail
					if ($small == 1) {
						PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], $thumbNameS->abs, 'small', $frontUpload, $errorMsgS);
						if ($additional_thumbnails == 2 || $additional_thumbnails == 3 || $additional_thumbnails == 7) {
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_s_', 'phoca_thumb_s1_', $thumbNameS->abs), 'small1', $frontUpload, $errorMsgS);
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_s_', 'phoca_thumb_s2_', $thumbNameS->abs), 'small2', $frontUpload, $errorMsgS);
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_s_', 'phoca_thumb_s3_', $thumbNameS->abs), 'small3', $frontUpload, $errorMsgS);
						}
					} else {
						$errorMsgS = 'ThumbnailExists';// in case we only need medium or large, because of if clause bellow
					}
					
					//Medium thumbnail
					if ($medium == 1) {
						PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], $thumbNameM->abs, 'medium', $frontUpload, $errorMsgM);
						if ($additional_thumbnails == 1 || $additional_thumbnails == 3 || $additional_thumbnails == 7) {
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_m_', 'phoca_thumb_m1_', $thumbNameM->abs), 'medium1', $frontUpload, $errorMsgM);
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_m_', 'phoca_thumb_m2_', $thumbNameM->abs), 'medium2', $frontUpload, $errorMsgM);
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_m_', 'phoca_thumb_m3_', $thumbNameM->abs), 'medium3', $frontUpload, $errorMsgM);
							
						}
					} else {
						$errorMsgM = 'ThumbnailExists'; // in case we only need small or large, because of if clause bellow
					}
					
					//Large thumbnail
					if ($large == 1) {
						PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], $thumbNameL->abs, 'large', $frontUpload, $errorMsgL);
						if ($additional_thumbnails == 7) {
							PhocaGalleryFileThumbnail::createFileThumbnail($file['name_original_abs'], str_replace('phoca_thumb_l_', 'phoca_thumb_l1_', $thumbNameL->abs), 'large1', $frontUpload, $errorMsgL);
						}
					} else {
						$errorMsgL = 'ThumbnailExists'; // in case we only need small or medium, because of if clause bellow
					}
					
					// Error messages for all 3 thumbnails (if the message contains error string, we got error
					// Other strings can be:
					// - ThumbnailExists  - do not display error message nor success page
					// - OnlyInformation - do not display error message nor success page
					// - DisabledThumbCreation - do not display error message nor success page
					
					$creatingSError = $creatingMError = $creatingLError = false;
					$creatingSError = preg_match("/Error/i", $errorMsgS);
					$creatingMError = preg_match("/Error/i", $errorMsgM);
					$creatingLError = preg_match("/Error/i", $errorMsgL);
					
					// BACKEND OR FRONTEND
					if ($frontUpload != 1) {
					
						// There is an error while creating thumbnail in m or in s or in l
						if ($creatingSError || $creatingMError || $creatingLError) {
							// if all or two errors appear, we only display the last error message	
							// because the errors in this case is the same
							if ($errorMsgS != '') {
								$creatingError = $errorMsgS;
							}
							if ($errorMsgM != '') {
								$creatingError = $errorMsgM;
							}
							if ($errorMsgL != '') {
								$creatingError = $errorMsgL;
							}
						
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl, $creatingError);exit;
						} else if ($errorMsgS == '' && $errorMsgM == '' && $errorMsgL == '') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == '' && $errorMsgM == '' && $errorMsgL == 'ThumbnailExists') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == '' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == 'ThumbnailExists') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == '' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == '') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == '') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == '' && $errorMsgL == '') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == '' && $errorMsgL == 'ThumbnailExists') {
							PhocaGalleryRenderProcess::getProcessPage( $file['name'], $thumbInfo, $refreshUrl);exit;
						}
					} else {
						// There is an error while creating thumbnail in m or in s or in l
						if ($creatingSError || $creatingMError || $creatingLError) {
							// if all or two errors appear, we only display the last error message	
							// because the errors in this case is the same
							if ($errorMsgS != '') {
								$creatingError = $errorMsgS;
							}
							if ($errorMsgM != '') {
								$creatingError = $errorMsgM;
							}
							if ($errorMsgL != '') {
								$creatingError = $errorMsgL;
							}							// because the errors in this case is the same
						
							$returnFrontMessage = $creatingError;
						} else if ($errorMsgS == '' && $errorMsgM == '' && $errorMsgL == '') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == '' && $errorMsgM == '' && $errorMsgL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == '' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == '' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == '') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == 'ThumbnailExists' && $errorMsgL == '') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == '' && $errorMsgL == '') {
							$returnFrontMessage = 'Success';
						} else if ($errorMsgS == 'ThumbnailExists' && $errorMsgM == '' && $errorMsgL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						}
					}
					
					if ($frontUpload == 1) {
						return $returnFrontMessage;
					}
				}
			}
			break;
		}
		return $file;
	}
	
	public static function dontCreateThumb ($filename) {
		$dontCreateThumb		= false;
		$dontCreateThumbArray	= array ('watermark-large.png', 'watermark-medium.png');
		foreach ($dontCreateThumbArray as $key => $value) {
			if (strtolower($filename) == strtolower($value)) {
				return true;
			}
		}
		return false;
	}
	
	public static function createThumbnailFolder($folderOriginal, $folderThumbnail, &$errorMsg) {	
		
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		$enable_thumb_creation = $paramsC->get( 'enable_thumb_creation', 1 );
		$folder_permissions = $paramsC->get( 'folder_permissions', 0755 );
		//$folder_permissions = octdec((int)$folder_permissions);
		
		// disable or enable the thumbnail creation
		if ($enable_thumb_creation == 1) {

			if (JFolder::exists($folderOriginal)) {
				if (strlen($folderThumbnail) > 0) {
					$folderThumbnail = JPath::clean($folderThumbnail);				
					if (!JFolder::exists($folderThumbnail) && !JFile::exists($folderThumbnail)) {
						switch((int)$folder_permissions) {
							case 777:
								JFolder::create($folderThumbnail, 0777 );
							break;
							case 705:
								JFolder::create($folderThumbnail, 0705 );
							break;
							case 666:
								JFolder::create($folderThumbnail, 0666 );
							break;
							case 644:
								JFolder::create($folderThumbnail, 0644 );
							break;				
							case 755:
							Default:
								JFolder::create($folderThumbnail, 0755 );
							break;
						}
						
						//JFolder::create($folderThumbnail, $folder_permissions );
						if (isset($folderThumbnail)) {
							$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
							JFile::write($folderThumbnail. '/'. "index.html", $data);
						}
						// folder was not created
						if (!JFolder::exists($folderThumbnail)) {
							$errorMsg = 'ErrorCreatingFolder';
							return false;	
						}
					}
				}
			}
			$errorMsg = 'Success';
			
			return true;
		} else {
			$errorMsg = 'DisabledThumbCreation';
			return false; // User have disabled the thumbanil creation e.g. because of error
		}
	}
	
	public static function createFileThumbnail($fileOriginal, $fileThumbnail, $size, $frontUpload=0, &$errorMsg) {	
		
		$paramsC 					= JComponentHelper::getParams('com_phocagallery');
		$enable_thumb_creation 		= $paramsC->get( 'enable_thumb_creation', 1);
		$watermarkParams['create']	= $paramsC->get( 'create_watermark', 0 );// Watermark
		$watermarkParams['x'] 		= $paramsC->get( 'watermark_position_x', 'center' );
		$watermarkParams['y']		= $paramsC->get( 'watermark_position_y', 'middle' );
		$crop_thumbnail				= $paramsC->get( 'crop_thumbnail', 5);// Crop or not
		$crop 						= null;
		
		switch ($size) {	
			case 'small1':
			case 'small2':
			case 'small3':
			case 'medium1':
			case 'medium2':
			case 'medium3':
			case 'large1':
				$crop = 1;
			break;
			
			case 'small':
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'medium':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'large':
			default:
				if ($crop_thumbnail == 1 || $crop_thumbnail == 4 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
		}		
		
		// disable or enable the thumbnail creation
		if ($enable_thumb_creation == 1) {	
			$fileResize	= PhocaGalleryFileThumbnail::getThumbnailResize($size);

			if (JFile::exists($fileOriginal)) {
				//file doesn't exist, create thumbnail
				if (!JFile::exists($fileThumbnail)) {
					$errorMsg = 'Error4';
					//Don't do thumbnail if the file is smaller (width, height) than the possible thumbnail
					list($width, $height) = GetImageSize($fileOriginal);
					//larger
					phocagalleryimport('phocagallery.image.imagemagic');
					if ($width > $fileResize['width'] || $height > $fileResize['height']) {
					
						$imageMagic = PhocaGalleryImageMagic::imageMagic($fileOriginal, $fileThumbnail, $fileResize['width'] , $fileResize['height'], $crop, null, $watermarkParams, $frontUpload, $errorMsg);
						
					} else {
						$imageMagic = PhocaGalleryImageMagic::imageMagic($fileOriginal, $fileThumbnail, $width , $height, $crop, null, $watermarkParams, $frontUpload, $errorMsg);
					}
					if ($imageMagic) {
						return true;
					} else {
						return false;// error Msg will be taken from imageMagic
					}
				} else {
					$errorMsg = 'ThumbnailExists';//thumbnail exists
					return false;
				}	
			} else {
				$errorMsg = 'ErrorFileOriginalNotExists';
				return false;
			}
			$errorMsg = 'Error3';
			return false;
		} else {
			$errorMsg = 'DisabledThumbCreation'; // User have disabled the thumbanil creation e.g. because of error
			return false;
		}
	}
	
	public static function getThumbnailResize($size = 'all') {	
		
		// Get width and height from Default settings
		$params 			= JComponentHelper::getParams('com_phocagallery') ;
		$large_image_width 	= $params->get( 'large_image_width', 640 );
		$large_image_height = $params->get( 'large_image_height', 480 );
		$medium_image_width = $params->get( 'medium_image_width', 100 );
		$medium_image_height= $params->get( 'medium_image_height', 100 );
		$small_image_width 	= $params->get( 'small_image_width', 50 );
		$small_image_height = $params->get( 'small_image_height', 50 );
		$additional_thumbnail_margin = $params->get( 'additional_thumbnail_margin', 0 );
		
		switch ($size) {			
			case 'large':
			$fileResize['width']	=	$large_image_width;
			$fileResize['height']	=	$large_image_height;
			break;
			
			case 'medium':
			$fileResize['width']	=	$medium_image_width;
			$fileResize['height']	=	$medium_image_height;
			break;
			
			case 'medium1':
			$fileResize['width']	=	$medium_image_width;
			$fileResize['height']	=	((int)$medium_image_height * 2) + (int)$additional_thumbnail_margin;
			break;
			
			case 'medium2':
			$fileResize['width']	=	(int)$medium_image_width * 2;
			$fileResize['height']	=	$medium_image_height;
			break;
			
			case 'medium3':
			$fileResize['width']	=	(int)$medium_image_width * 2;
			$fileResize['height']	=	(int)$medium_image_height * 2;
			break;
			
			case 'small':
			$fileResize['width']	=	$small_image_width;
			$fileResize['height']	=	$small_image_height;
			break;
			
			case 'small1':
				$fileResize['width']	=	$small_image_width;
				$fileResize['height']	=	(int)$small_image_height * 2;
			break;
			
			case 'small2':
				$fileResize['width']	=	(int)$small_image_width * 2;
				$fileResize['height']	=	$small_image_height;
			break;
			
			case 'small3':
				$fileResize['width']	=	(int)$small_image_width * 2;
				$fileResize['height']	=	(int)$small_image_height * 2;
			break;
			
			case 'large1':
				$fileResize['width']	=	$large_image_width;
				$scale					= 	(int)$large_image_height / $medium_image_height;
				$fileResize['height']	=	((int)$large_image_height * 2) + ((int)$additional_thumbnail_margin * $scale);
			break;
			
			
			default:
			case 'all':
			$fileResize['smallwidth']	=	$small_width;
			$fileResize['smallheight']	=	$small_height;
			$fileResize['mediumwidth']	=	$medium_width;
			$fileResize['mediumheight']	=	$medium_height;
			$fileResize['largewidth']	=	$large_width;
			$fileResize['largeheight']	=	$large_height;
			break;			
		}
		return $fileResize;
	}
}
?>