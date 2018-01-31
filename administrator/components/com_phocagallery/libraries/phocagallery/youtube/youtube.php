<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );

class PhocaGalleryYoutube
{
	public function displayVideo($videoCode) {
	
		$o = '';
		if ($videoCode != '' && PhocaGalleryUtils::isURLAddress($videoCode) ) {
			
			$shortvideoCode	= 'http://youtu.be/';
			$pos 		= strpos($videoCode, $shortvideoCode);
			if ($pos !== false) {
				$code 		= str_replace($shortvideoCode, '', $videoCode);
			} else {
				$codeArray 	= explode('=', $videoCode);
				$code 		= str_replace($codeArray[0].'=', '', $videoCode);
			}
			
			$youtubeheight	= PhocaGallerySettings::getAdvancedSettings('youtubeheight');
			$youtubewidth	= PhocaGallerySettings::getAdvancedSettings('youtubewidth');

			$o .= '<object height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'">'
			.'<param name="movie" value="http://www.youtube.com/v/'.$code.'"></param>'
			.'<param name="allowFullScreen" value="true"></param>'
			.'<param name="allowscriptaccess" value="always"></param>'
			.'<embed src="http://www.youtube.com/v/'.$code.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'"></embed></object>';
		}
		
		if ($o != '') {
			return $o;
		} 
		
		return $videoCode;
	}
	
	public function getCode($url) {
	
		$o = '';
		if ($url != '' && PhocaGalleryUtils::isURLAddress($url) ) {
			$shortvideoCode	= 'http://youtu.be/';
			$pos 		= strpos($url, $shortvideoCode);
			if ($pos !== false) {
				$code 		= str_replace($shortvideoCode, '', $url);
			} else {
				$codeArray 	= explode('=', $url);
				$code 		= str_replace($codeArray[0].'=', '', $url);
			}
			return $code;
		}
		return $o;
	}
	
	public function importYtb($ytbLink, $folder, &$errorMsg = '') {
		
		
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
		
			// Data
			$cUrl		= curl_init("http://gdata.youtube.com/feeds/api/videos/".strip_tags($ytbCode));
            curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
            $xml		= curl_exec($cUrl);
            curl_close($cUrl);
			
			$xml 	= str_replace('<media:', '<phcmedia', $xml);
			$xml 	= str_replace('</media:', '</phcmedia', $xml);
			
			$data 	= JFactory::getXML($xml, false);

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
			
			if (JFile::exists(JPATH_ROOT . DS . 'images' . DS . 'phocagallery' . DS . $ytb['filename'], $img)) {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_VIDEO_EXISTS');
				return false;
			}
			
            if (!JFile::write(JPATH_ROOT . DS . 'images' . DS . 'phocagallery' . DS . $ytb['filename'], $img)) {
				$errorMsg = JText::_('COM_PHOCAGALLERY_YTB_ERROR_WRITE_IMAGE');
				return false;
			}
		}
		
		return $ytb;
	
	}
}