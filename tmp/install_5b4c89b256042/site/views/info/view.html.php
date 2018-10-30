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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
class PhocaGalleryViewInfo extends JViewLegacy
{
	public 		$tmpl;
	protected 	$params;
	
	/*
	public static function getGps($exifCoord) {
		$degrees = count($exifCoord) > 0 ? self::gps2Num($exifCoord[0]) : 0;
		$minutes = count($exifCoord) > 1 ? self::gps2Num($exifCoord[1]) : 0;
		$seconds = count($exifCoord) > 2 ? self::gps2Num($exifCoord[2]) : 0;

		//normalize
		$minutes += 60 * ($degrees - floor($degrees));
		$degrees = floor($degrees);

		$seconds += 60 * ($minutes - floor($minutes));
		$minutes = floor($minutes);

		//extra normalization, probably not necessary unless you get weird data
		if($seconds >= 60)
		{
		$minutes += floor($seconds/60.0);
		$seconds -= 60*floor($seconds/60.0);
		}

		if($minutes >= 60)
		{
		$degrees += floor($minutes/60.0);
		$minutes -= 60*floor($minutes/60.0);
		}

		return array('degrees' => $degrees, 'minutes' => $minutes, 'seconds' => $seconds);
	}

	public static function gps2Num($coordPart)
	{
		$parts = explode('/', $coordPart);

		if(count($parts) <= 0)// jic
		return 0;
		if(count($parts) == 1)
		return $parts[0];

		return floatval($parts[0]) / floatval($parts[1]);
	}
*/
	function display($tpl = null) {
		
		$app	= JFactory::getApplication();
		
		// PLUGIN WINDOW - we get information from plugin
		$get			= '';
		$get['info']	= $app->input->get( 'info', '', 'string' );
		$this->itemId	= $app->input->get('Itemid', 0, 'int');
		
		$document		= JFactory::getDocument();		
		$this->params	= $app->getParams();
		
		$this->tmpl['enablecustomcss']				= $this->params->get( 'enable_custom_css', 0);
		$this->tmpl['customcss']					= $this->params->get( 'custom_css', '');

		
		// CSS
		PhocaGalleryRenderFront::renderAllCSS();
		
		// PARAMS - Open window parameters - modal popup box or standard popup window
		$this->tmpl['detailwindow'] =$this->params->get( 'detail_window', 0 );
		
		// Plugin information
		if (isset($get['info']) && $get['info'] != '') {
			$this->tmpl['detailwindow'] = $get['info'];
		}
		
		// Close and Reload links (for different window types)
		$close = PhocaGalleryRenderFront::renderCloseReloadDetail($this->tmpl['detailwindow']);
		$detail_window_close	= $close['detailwindowclose'];
		$detail_window_reload	= $close['detailwindowreload'];
		
		
		// PARAMS - Display Description in Detail window - set the font color
		$this->tmpl['detailwindowbackgroundcolor']=$this->params->get( 'detail_window_background_color', '#ffffff' );
		$description_lightbox_font_color 	= $this->params->get( 'description_lightbox_font_color', '#ffffff' );
		$description_lightbox_bg_color 		= $this->params->get( 'description_lightbox_bg_color', '#000000' );
		$description_lightbox_font_size 	= $this->params->get( 'description_lightbox_font_size', 12 );
		$this->tmpl['gallerymetakey'] 		= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 		= $this->params->get( 'gallery_metadesc', '' );

		// NO SCROLLBAR IN DETAIL WINDOW
/*		$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:".$this->tmpl['detailwindowbackgroundcolor'].";} \n" 
			." center, table {background:".$this->tmpl['detailwindowbackgroundcolor'].";} \n" 
			." #sbox-window {background-color:#fff;padding:5px} \n" 
			." </style> \n");
*/	
		
		// PARAMS - Get image height and width
		$this->tmpl['boxlargewidth']		=$this->params->get( 'front_modal_box_width', 680 );
		$this->tmpl['boxlargeheight'] 	=$this->params->get( 'front_modal_box_height', 560 );
		$front_popup_window_width 	= $this->tmpl['boxlargewidth'];//since version 2.2
		$front_popup_window_height 	= $this->tmpl['boxlargeheight'];//since version 2.2
		
		if ($this->tmpl['detailwindow'] == 1) {
			$this->tmpl['windowwidth']	= $front_popup_window_width;
			$this->tmpl['windowheight']	= $front_popup_window_height;
		} else {//modal popup window
			$this->tmpl['windowwidth']	= $this->tmpl['boxlargewidth'];
			$this->tmpl['windowheight']	= $this->tmpl['boxlargeheight'];
		}
		
		$this->tmpl['largemapwidth']		= (int)$this->tmpl['windowwidth'] - 20;
		$this->tmpl['largemapheight']		= (int)$this->tmpl['windowheight'] - 20;
		$this->tmpl['googlemapsapikey']	=$this->params->get( 'google_maps_api_key', '' );
		
		$this->tmpl['exifinformation']	=$this->params->get( 'exif_information', 'FILE.FileName,FILE.FileDateTime,FILE.FileSize,FILE.MimeType,COMPUTED.Height,COMPUTED.Width,COMPUTED.IsColor,COMPUTED.ApertureFNumber,IFD0.Make,IFD0.Model,IFD0.Orientation,IFD0.XResolution,IFD0.YResolution,IFD0.ResolutionUnit,IFD0.Software,IFD0.DateTime,IFD0.Exif_IFD_Pointer,IFD0.GPS_IFD_Pointer,EXIF.ExposureTime,EXIF.FNumber,EXIF.ExposureProgram,EXIF.ISOSpeedRatings,EXIF.ExifVersion,EXIF.DateTimeOriginal,EXIF.DateTimeDigitized,EXIF.ShutterSpeedValue,EXIF.ApertureValue,EXIF.ExposureBiasValue,EXIF.MaxApertureValue,EXIF.MeteringMode,EXIF.LightSource,EXIF.Flash,EXIF.FocalLength,EXIF.SubSecTimeOriginal,EXIF.SubSecTimeDigitized,EXIF.ColorSpace,EXIF.ExifImageWidth,EXIF.ExifImageLength,EXIF.SensingMethod,EXIF.CustomRendered,EXIF.ExposureMode,EXIF.WhiteBalance,EXIF.DigitalZoomRatio,EXIF.FocalLengthIn35mmFilm,EXIF.SceneCaptureType,EXIF.GainControl,EXIF.Contrast,EXIF.Saturation,EXIF.Sharpness,EXIF.SubjectDistanceRange,GPS.GPSLatitudeRef,GPS.GPSLatitude,GPS.GPSLongitudeRef,GPS.GPSLongitude,GPS.GPSAltitudeRef,GPS.GPSAltitude,GPS.GPSTimeStamp,GPS.GPSStatus,GPS.GPSMapDatum,GPS.GPSDateStamp' );
			
		// MODEL
		$model	= $this->getModel();
		$info	= $model->getData();
		
		// Back button
		$this->tmpl['backbutton'] = '';
		if ($this->tmpl['detailwindow'] == 7) {
			phocagalleryimport('phocagallery.image.image');
			$this->tmpl['backbutton'] = '<div><a href="'.JRoute::_('index.php?option=com_phocagallery&view=category&id='. $info->catslug.'&Itemid='. $app->input->get('Itemid', 0, 'int')).'"'
				.' title="'.JText::_( 'COM_PHOCAGALLERY_BACK_TO_CATEGORY' ).'">'
				. PhocaGalleryRenderFront::renderIcon('icon-up-images', 'media/com_phocagallery/images/icon-up-images.png', JText::_('COM_PHOCAGALLERY_BACK_TO_CATEGORY'), 'ph-icon-up-images ph-icon-button').'</a></div>';
		}
		
		// EXIF DATA
		$outputExif 	= '';
		$originalFile 	= '';
		$extImage = PhocaGalleryImage::isExtImage($info->extid);
		if ($extImage && isset($info->exto) && $info->exto != '') {
			$originalFile = $info->exto;
		} else {
			if (isset($info->filename)) {
				$originalFile = PhocaGalleryFile::getFileOriginal($info->filename);
			}
		}
		
		if ($originalFile != '' && function_exists('exif_read_data')) {
			
			$exif = @exif_read_data( $originalFile, 'IFD0');
			

		
			if ($exif === false) {
				$outputExif .= JText::_('COM_PHOCAGALLERY_NO_HEADER_DATA_FOUND');
			}
			
			$setExif 		= $this->tmpl['exifinformation'];
			$setExifArray	= explode(",", $setExif, 200);
			$exif 			= @exif_read_data($originalFile, 0, true);
			
		/*	$infoOutput = '';
			foreach ($exif as $key => $section) {
				foreach ($section as $name => $val) {
					$infoOutput .= strtoupper($key.'.'.$name).'='.$name.'<br />';
					$infoOutput .= $key.'.'.$name.';';
				}
			}*/
		
			
			$infoOutput	= '';
			$i 			= 0;
			foreach ($setExifArray as $ks => $vs) {
			
				if ($i%2==0) {
					$class = 'class="first"';
				} else {
					$class = 'class="second"';
				}
			
				if ($vs != '') {
					$vsValues	= explode(".", $vs, 2);
					if (isset($vsValues[0])) {
						$section = $vsValues[0];
					} else {
						$section = '';
					}
					if (isset($vsValues[1])) {
						$name = $vsValues[1];
					} else {
						$name = '';
					}
			
				
					if ($section != '' && $name != '') {
						
						if (isset($exif[$section][$name])) {
						
							switch ($name) {
								case 'FileDateTime':
									jimport( 'joomla.utilities.date');
									$date		= new JDate($exif[$section][$name]);
									$exifValue 	= $date->format('d/m/Y, H:m');
								break;
								
								case 'FileSize':
									$exifValue	= PhocaGalleryFile::getFileSizeReadable($exif[$section][$name]);
								break;
								
								case 'Height':
								case 'Width':
								case 'ExifImageWidth':
								case 'ExifImageLength':
									$exifValue	= $exif[$section][$name] . ' px';
								break;
								
								case 'IsColor':
									switch((int)$exif[$section][$name]) {
										case 0:
											$exifValue = JText::_('COM_PHOCAGALLERY_NO');
										break;
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_YES');
										break;
									}
								break;
								
								
								
								case 'ResolutionUnit':
									switch((int)$exif[$section][$name]) {
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_INCH');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_CM');
										break;
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_MM');
										break;
										case 5:
											$exifValue = JText::_('COM_PHOCAGALLERY_MICRO');
										break;
										case 0:
										case 1:
										default:
											$exifValue = '?';
										break;
									}
								break;
								
								case 'ExposureProgram':
									switch((int)$exif[$section][$name]) {
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_MANUAL');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_NORMAL_PROGRAM');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_APERTURE_PRIORITY');
										break;
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_SHUTTER_PRIORITY');
										break;
										case 5:
											$exifValue = JText::_('COM_PHOCAGALLERY_CREATIVE_PROGRAM');
										break;
										case 6:
											$exifValue = JText::_('COM_PHOCAGALLERY_ACTION_PROGRAM');
										break;
										case 7:
											$exifValue = JText::_('COM_PHOCAGALLERY_PORTRAIT_MODE');
										break;
										case 8:
											$exifValue = JText::_('COM_PHOCAGALLERY_LANDSCAPE_MODE');
										break;
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NOT_DEFINED');
										break;
									}
								break;
								
								case 'MeteringMode':
									switch((int)$exif[$section][$name]) {
										case 0:
											$exifValue = JText::_('COM_PHOCAGALLERY_UNKNOWN');
										break;
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_AVERAGE');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_CENTERWEIGHTEDAVERAGE');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_SPOT');
										break;
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_MULTISPOT');
										break;
										case 5:
											$exifValue = JText::_('COM_PHOCAGALLERY_PATTERN');
										break;
										case 6:
											$exifValue = JText::_('COM_PHOCAGALLERY_PARTIAL');
										break;
										
										case 255:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_OTHER');
										break;
									}
								break;
								
								
								case 'LightSource':
									switch((int)$exif[$section][$name]) {
										case 0:
											$exifValue = JText::_('COM_PHOCAGALLERY_UNKNOWN');
										break;
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_DAYLIGHT');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_FLUORESCENT');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_TUNGSTEN');
										break;
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_FLASH');
										break;
										case 9:
											$exifValue = JText::_('COM_PHOCAGALLERY_FINEWEATHER');
										break;
										case 10:
											$exifValue = JText::_('COM_PHOCAGALLERY_CLOUDYWEATHER');
										break;
										
										case 11:
											$exifValue = JText::_('COM_PHOCAGALLERY_SHADE');
										break;
										case 12:
											$exifValue = JText::_('COM_PHOCAGALLERY_DAYLIGHTFLUORESCENT');
										break;
										case 13:
											$exifValue = JText::_('COM_PHOCAGALLERY_DAYWHITEFLUORESCENT');
										break;
										case 14:
											$exifValue = JText::_('COM_PHOCAGALLERY_COOLWHITEFLUORESCENT');
										break;
										case 15:
											$exifValue = JText::_('COM_PHOCAGALLERY_WHITEFLUORESCENT');
										break;
										case 17:
											$exifValue = JText::_('COM_PHOCAGALLERY_STANDARDLIGHTA');
										break;
										case 18:
											$exifValue = JText::_('COM_PHOCAGALLERY_STANDARDLIGHTB');
										break;
										case 19:
											$exifValue = JText::_('COM_PHOCAGALLERY_STANDARDLIGHTC');
										break;
										case 20:
											$exifValue = JText::_('COM_PHOCAGALLERY_D55');
										break;
										case 21:
											$exifValue = JText::_('COM_PHOCAGALLERY_D65');
										break;
										case 22:
											$exifValue = JText::_('COM_PHOCAGALLERY_D75');
										break;
										case 23:
											$exifValue = JText::_('COM_PHOCAGALLERY_D50');
										break;
										case 24:
											$exifValue = JText::_('COM_PHOCAGALLERY_ISOSTUDIOTUNGSTEN');
										break;
										
										case 255:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_OTHERLIGHTSOURCE');
										break;
									}
								break;
								
								case 'SensingMethod':
									switch((int)$exif[$section][$name]) {
									
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_ONE-CHIP_COLOR_AREA_SENSOR');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_TWO-CHIP_COLOR_AREA_SENSOR');
										break;
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_THREE-CHIP_COLOR_AREA_SENSOR');
										break;
										case 5:
											$exifValue = JText::_('COM_PHOCAGALLERY_COLOR_SEQUENTIAL_AREA_SENSOR');
										break;
										case 7:
											$exifValue = JText::_('COM_PHOCAGALLERY_TRILINEAR_SENSOR');
										break;
										case 8:
											$exifValue = JText::_('COM_PHOCAGALLERY_COLOR_SEQUENTIAL_LINEAR_SENSOR');
										break;
										
										case 1:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NOT_DEFINED');
										break;
									}
								break;
								
								case 'CustomRendered':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_CUSTOM_PROCESS');
										break;
										
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NORMAL_PROCESS');
										break;
									}
								break;
								
								case 'ExposureMode':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_MANUAL_EXPOSURE');
										break;
										
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_AUTO_BRACKET');
										break;
										
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_AUTO_EXPOSURE');
										break;
									}
								break;
								
								case 'WhiteBalance':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_MANUAL_WHITE_BALANCE');
										break;
										
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_AUTO_WHITE_BALANCE');
										break;
									}
								break;
								
								
								case 'SceneCaptureType':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_LANDSCAPE');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_PORTRAIT');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_NIGHT_SCENE');
										break;
										
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_STANDARD');
										break;
									}
								break;
								
								case 'GainControl':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_LOW_GAIN_UP');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_HIGH_GAIN_UP');
										break;
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_LOW_GAIN_UP');
										break;
										
										case 4:
											$exifValue = JText::_('COM_PHOCAGALLERY_HIGH_GAIN_UP');
										break;
										
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NONE');
										break;
									}
								break;
				
								case 'ColorSpace':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_SRGB');
										break;
										case 'FFFF.H':
											$exifValue = JText::_('COM_PHOCAGALLERY_UNCALIBRATED');
										break;
						
										case 0:
										default:
											$exifValue = '-';
										break;
									}
								break;
				
				
								case 'Contrast':
								case 'Sharpness':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_SOFT');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_HARD');
										break;
						
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NORMAL');
										break;
									}
								break;
								
								case 'Saturation':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_LOW_SATURATION');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_HIGH_SATURATION');
										break;
						
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_NORMAL');
										break;
									}
								break;
				
								case 'SubjectDistanceRange':
									switch((int)$exif[$section][$name]) {
									
										case 1:
											$exifValue = JText::_('COM_PHOCAGALLERY_MACRO');
										break;
										case 2:
											$exifValue = JText::_('COM_PHOCAGALLERY_CLOSE_VIEW');
										break;
										
										case 3:
											$exifValue = JText::_('COM_PHOCAGALLERY_DISTANT_VIEW');
										break;
						
										case 0:
										default:
											$exifValue = JText::_('COM_PHOCAGALLERY_UNKNOWN');
										break;
									}
								break;
								
								case 'GPSLatitude':
								case 'GPSLongitude':
									$exifValue = '';
									
									
									//$gps = self::getGps($exif[$section][$name]);
		
									if (isset($exif[$section][$name][0])) {
										list($l,$r)	= explode("/",$exif[$section][$name][0]);
										
										$d			= ($l/$r);
										$exifValue 	.= $d . '&deg; ';
									}
									
									if (isset($exif[$section][$name][1])) {
										list($l,$r)	= explode("/",$exif[$section][$name][1]);
										$m			= ($l/$r);
										
										if ($l%$r>0) {
											$sNoInt = ($l/$r);
											$sInt 	= ($l/$r);
											$s 		= ($sNoInt - (int)$sInt)*60;
											$exifValue 	.= (int)$m . '\' ' . $s . '" ';
										} else {
											$exifValue 	.= $m . '\' ';
											if (isset($exif[$section][$name][2])) {
												list($l,$r)	= explode("/",$exif[$section][$name][2]);
												$s			= ($l/$r);
												$exifValue 	.= $s . '" ';
											}
										}
									}
								break;
								
								case 'GPSTimeStamp':
									$exifValue = '';
									if (isset($exif[$section][$name][0])) {
										list($l,$r)	= explode("/",$exif[$section][$name][0]);
										$h			= ($l/$r);
										$exifValue 	.= $h . ' h ';
									}
									
									if (isset($exif[$section][$name][1])) {
										list($l,$r)	= explode("/",$exif[$section][$name][1]);
										$m			= ($l/$r);
										$exifValue 	.= $m . ' m ';
									}
									if (isset($exif[$section][$name][2])) {
										list($l,$r)	= explode("/",$exif[$section][$name][2]);
										$s			= ($l/$r);
										$exifValue 	.= $s . ' s ';
									}

								break;
				
								
								case 'ExifVersion':
									if (is_numeric($exif[$section][$name])) {
										$exifValue = (int)$exif[$section][$name]/100;
									} else {
										$exifValue = $exif[$section][$name];
									}
								break;
								
								case 'FocalLength':
									if (isset($exif[$section][$name]) && $exif[$section][$name] != '') {
										$focalLength = explode ('/', $exif[$section][$name]);
										if (isset($focalLength[0]) && (int)$focalLength[0] > 0
										&& isset($focalLength[1]) && (int)$focalLength[1] > 0 ) {
											$exifValue = (int)$focalLength[0] / (int)$focalLength[1];
											$exifValue = $exifValue . ' mm';
										}
									
									}
								break;
								
								case 'ExposureTime':
									if (isset($exif[$section][$name]) && $exif[$section][$name] != '') {
										$exposureTime = explode ('/', $exif[$section][$name]);
										if (isset($exposureTime[0]) && (int)$exposureTime[0] > 0
										&& isset($exposureTime[1]) && (int)$exposureTime[1] > 1 ) {
										
											if ((int)$exposureTime[1] > (int)$exposureTime[0]) {
												$exifValue = (int)$exposureTime[1] / (int)$exposureTime[0];
												$exifValue = '1/'. $exifValue . ' sec';
											} 
										}
									
									}
								break;
								
								/*case 'ShutterSpeedValue':
									if (isset($exif[$section][$name]) && $exif[$section][$name] != '') {
										$shutterSpeedValue = explode ('/', $exif[$section][$name]);
										if (isset($shutterSpeedValue[0]) && (int)$shutterSpeedValue[0] > 0
										&& isset($shutterSpeedValue[1]) && (int)$shutterSpeedValue[1] > 1 ) {
										
											if ((int)$shutterSpeedValue[1] > (int)$shutterSpeedValue[0]) {
												$exifValue = (int)$shutterSpeedValue[1] / (int)$shutterSpeedValue[0];
												$exifValue = '1/'. $exifValue . ' sec';
											} 
										}
									
									}
								break;*/

								
								default:
									$exifValue = $exif[$section][$name];
								break;
							}
							
							$vs = str_replace('.', '_', $vs);
							
							$infoOutput .= '<tr '.$class.'>'
							//.'<td>'. JText::_($vs) . '('.$section.' '.$name.')</td>'
							.'<td>'. JText::_('COM_PHOCAGALLERY_'.strtoupper($vs)) . '</td>'
							.'<td>'.$exifValue. '</td>'
							.'</tr>';
						}

					}
				}
				$i++;
			}

		}	

		// ASIGN
		$this->assignRef( 'tmpl', $this->tmpl );
		$this->assignRef( 'infooutput', $infoOutput );
	//	$this->assignRef( 'infooutput', $infoOutput );
		$this->_prepareDocument($info);
		parent::display($tpl);
	}
	
	protected function _prepareDocument($item) {
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$this->params		= $app->getParams();
		$title 		= null;
		
		$this->tmpl['gallerymetakey'] 		=$this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 		=$this->params->get( 'gallery_metadesc', '' );
		

		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');		
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
			
			if (isset($item->title) && $item->title != '') {
				$title = $title .' - ' .  $item->title;
			}
			
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			
			if (isset($item->title) && $item->title != '') {
				$title = $title .' - ' .  $item->title;
			}
		
			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}
		$this->document->setTitle($title);
		
		if ($item->metadesc != '') {
			$this->document->setDescription($item->metadesc);
		} else if ($this->tmpl['gallerymetadesc'] != '') {
			$this->document->setDescription($this->tmpl['gallerymetadesc']);
		} else if ($this->params->get('menu-meta_description', '')) {
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		} 

		if ($item->metakey != '') {
			$this->document->setMetadata('keywords', $item->metakey);
		} else if ($this->tmpl['gallerymetakey'] != '') {
			$this->document->setMetadata('keywords', $this->tmpl['gallerymetakey']);
		} else if ($this->params->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->params->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}

		/*if ($app->get('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->author);
		}

		/*$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}*/
		
		// Breadcrumbs TO DO (Add the whole tree)
		/*if (isset($this->category[0]->parentid)) {
			if ($this->category[0]->parentid == 1) {
			} else if ($this->category[0]->parentid > 0) {
				$pathway->addItem($this->category[0]->parenttitle, JRoute::_(PhocaDocumentationHelperRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias)));
			}
		}

		if (!empty($this->category[0]->title)) {
			$pathway->addItem($this->category[0]->title);
		}*/
	}
}
