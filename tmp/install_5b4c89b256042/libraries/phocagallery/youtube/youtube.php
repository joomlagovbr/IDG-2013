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
defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );

class PhocaGalleryYoutube
{
	public static function displayVideo($videoCode, $view = 0, $ywidth = 0, $yheight = 0) {
	
		$o = '';
		if ($videoCode != '' && PhocaGalleryUtils::isURLAddress($videoCode) ) {
			
			$pos1 		= strpos($videoCode, 'https');
			if ($pos1 !== false) {
				$shortvideoCode	= 'https://youtu.be/';
			} else {
				$shortvideoCode	= 'http://youtu.be/';
			}
			
			$pos 		= strpos($videoCode, $shortvideoCode);
			if ($pos !== false) {
				$code 		= str_replace($shortvideoCode, '', $videoCode);
			} else {
				$codeArray 	= explode('=', $videoCode);
				$code 		= str_replace($codeArray[0].'=', '', $videoCode);
			}
			
			
			
			$youtubeheight	= PhocaGallerySettings::getAdvancedSettings('youtubeheight');
			$youtubewidth	= PhocaGallerySettings::getAdvancedSettings('youtubewidth');
			
			if ((int)$ywidth > 0) {
				$youtubewidth	= (int)$ywidth;
			}
			if ((int)$yheight > 0) {
				$youtubeheight	= (int)$yheight;
			}																									 
			
	
			/*$o .= '<object height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'">'
			.'<param name="movie" value="http://www.youtube.com/v/'.$code.'"></param>'
			.'<param name="allowFullScreen" value="true"></param>'
			.'<param name="allowscriptaccess" value="always"></param>'
			.'<embed src="http://www.youtube.com/v/'.$code.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'"></embed></object>';*/
			
			$o .= '<iframe height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen></iframe>';
		}
		
		if ($o != '') {
			return $o;
		} 
		
		return $videoCode;
	}
	
	public static function getCode($url) {
	
		$o = '';
		
		if ($url != '' && PhocaGalleryUtils::isURLAddress($url) ) {
			$shortvideoCode		= 'http://youtu.be/';
			$shortvideoCode2	= 'https://youtu.be/';
			$pos 		= strpos($url, $shortvideoCode);
			$pos2 		= strpos($url, $shortvideoCode2);
			if ($pos !== false) {
				$code 		= str_replace($shortvideoCode, '', $url);
			} else if ($pos2 !== false) {
				$code 		= str_replace($shortvideoCode2, '', $url);
			} else {
				$codeArray 	= explode('=', $url);
				$code 		= str_replace($codeArray[0].'=', '', $url);
			}
			return $code;
		}
		return $o;
	}
	
	public static function importYtb($ytbLink, $folder, &$errorMsg = '') {

		$ytbCode 	= str_replace("&feature=related","",PhocaGalleryYoutube::getCode(strip_tags($ytbLink)));
		
		$ytb				= array();
		$ytb['title']		= '';
		$ytb['desc']		= '';
		$ytb['filename']	= '';
		$ytb['link']		= strip_tags($ytbLink);
		
		
		if(!function_exists("curl_init")){
			$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_NOT_LOADED_CURL');
			return false;
		} else if ($ytbCode == '') {
			$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_URL_NOT_CORRECT');
			return false;
		} else {
		
			$paramsC 	= JComponentHelper::getParams('com_phocagallery');
			$key 		= $paramsC->get( 'youtube_api_key', '' );
			$ssl 		= $paramsC->get( 'youtube_api_ssl', 1 );
		
			// Data
			//$cUrl		= curl_init("http://gdata.youtube.com/feeds/api/videos/".strip_tags($ytbCode));
			$cUrl 		= curl_init('https://www.googleapis.com/youtube/v3/videos?id='.strip_tags($ytbCode).'&part=snippet&key='.strip_tags($key));
			$ssl = 0;
			
			if ($ssl == 0) {
				curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, false);
			} else {
				curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, true);
			}
			  
			curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);  
            $json		= curl_exec($cUrl);
			
            curl_close($cUrl);
			
			
			
			$o 	= json_decode($json);
		
			if (!empty($o) && isset($o->error->message)) {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_IMPORTING_DATA') . '('.strip_tags($o->error->message).')';
				return false;
			} else if (!empty($o) && isset($o->items[0]->snippet)) {
				$oS = $o->items[0]->snippet;
				
				if (isset($oS->title)) {
					$ytb['title'] = (string)$oS->title;
				}
				
				if ($ytb['title'] == '' && isset($oS->localized->title)) {
					$ytb['title'] = (string)$oS->localized->title;
				}
				
				if (isset($oS->description)) {
					$ytb['desc'] = (string)$oS->description;
				}
				
				if ($ytb['desc'] == '' && isset($oS->localized->description)) {
					$ytb['desc'] = (string)$oS->localized->description;
				}
				
				$img = '';

				if (isset($oS->thumbnails->standard->url)) {
					$cUrl		= curl_init(strip_tags((string)$oS->thumbnails->standard->url));
				} else if (isset($oS->thumbnails->default->url)) {
					$cUrl		= curl_init(strip_tags((string)$oS->thumbnails->default->url));
				}
				if (isset($cUrl) && $cUrl != '') {

					if ($ssl == 0) {
						curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, false);
					} else {
						curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, true);
					}
					curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
					$img		= curl_exec($cUrl);
					curl_close($cUrl);
				} 
				

				
				$ytb['filename']	= $folder.strip_tags($ytbCode).'.jpg';
	
				
				if ($img != '') {
					if (JFile::exists(JPATH_ROOT . '/images/phocagallery' . '/'. $ytb['filename'], $img)) {
						//$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_VIDEO_EXISTS');
						//return false;
						//Overwrite the images
						
					}
					
					if (!JFile::write(JPATH_ROOT . '/images/phocagallery' . '/'. $ytb['filename'], $img)) {
						$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_WRITE_IMAGE');
						return false;
					}
				}
				
			} else {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_IMPORTING_DATA');
				return false;	
			}
			
			
			// API 2
			/*$xml 	= str_replace('<media:', '<phcmedia', $xml);
			$xml 	= str_replace('</media:', '</phcmedia', $xml);
			
			$data 	= simplexml_load_file($file);

			//Title			
			if (isset($data->title)) {
				$ytb['title'] = (string)$data->title;
			}
			
			if ($ytb['title'] == '' && isset($data->phcmediagroup->phcmediatitle)) {
				$ytb['title'] = (string)$data->phcmediagroup->phcmediatitle;
			}
			
			if (isset($data->phcmediagroup->phcmediadescription)) {
				$ytb['desc'] = (string)$data->phcmediagroup->phcmediadescription;
			}
			
			// Thumbnail
			if (isset($data->phcmediagroup->phcmediathumbnail[0]['url'])) {
				$cUrl		= curl_init(strip_tags((string)$data->phcmediagroup->phcmediathumbnail[0]['url']));
				curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
				$img		= curl_exec($cUrl);
				curl_close($cUrl);
			}
            	
			if ($img != '') {
				$cUrl		= curl_init("http://img.youtube.com/vi/".strip_tags($ytbCode)."/0.jpg");
				curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
				$img		= curl_exec($cUrl);
				curl_close($cUrl);
			}
	
			$ytb['filename']	= $folder.strip_tags($ytbCode).'.jpg';
			
			if (JFile::exists(JPATH_ROOT . '/' .'images' . '/' . 'phocagallery' . '/'. $ytb['filename'], $img)) {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_VIDEO_EXISTS');
				return false;
			}
			
            if (!JFile::write(JPATH_ROOT . '/' .'images' . '/' . 'phocagallery' . '/'. $ytb['filename'], $img)) {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_WRITE_IMAGE');
				return false;
			}*/
		}
		
		return $ytb;
	
	}
}