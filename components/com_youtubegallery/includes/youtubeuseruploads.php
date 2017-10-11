<?php
/**
 * YoutubeGallery
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'youtubeplaylist.php');

class VideoSource_YoutubeUserUploads
{
	public static function extractYouTubeUserID($youtubeURL)
	{
		//link example: http://www.youtube.com/user/designcompasscorp
		$matches=explode('/',$youtubeURL);
	
		if (count($matches) >3)
		{
			
			$userid = $matches[4];
			$pair=explode('?',$userid);
			return $pair[0];
		}
				
	    return '';
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$userid,&$datalink)
	{
		$videolist=array();
		$base_url='https://www.googleapis.com/youtube/v3';
		$api_key = YouTubeGalleryMisc::getSettingValue('youtube_api_key');
		
		if($api_key=='')
			return $videolist;
		
		$userid=VideoSource_YoutubeUserUploads::extractYouTubeUserID($youtubeURL);
		
		if($userid=='')
			return $videolist; //user id not found
		
		//------------- first step:  get user playlist id
		$part='contentDetails';
		$url=$base_url.'/channels?forUsername='.$userid.'&key='.$api_key.'&part='.$part;
		
		$htmlcode=YouTubeGalleryMisc::getURLData($url);
		
		if($htmlcode=='')
			return $videolist;
			
		$j=json_decode($htmlcode);
		if(!$j)
			return 'Connection Error';
		
		$items=$j->items;
		
		$playlistid='';
		if(isset($items[0]->contentDetails->relatedPlaylists->uploads))
		{
			$playlistid=$items[0]->contentDetails->relatedPlaylists->uploads;
			if($playlistid=='')
				return $videolist; //user not found or no files uploaded
		}
		
		//--------------- second step: get videos
		
		$videolist=VideoSource_YoutubePlaylist::getPlaylistVideos($playlistid,$datalink,$api_key,$optionalparameters);
		
		return $videolist;
	}
	
	/*
	public static function getUserInfo($youtubeURL,&$item, $getinfomethod)
	{
				
		$userid=VideoSource_YoutubeUserUploads::extractYouTubeUserID($youtubeURL);
		
		if($userid=='')
			return 'user id not found';
		
		$url = 'http://gdata.youtube.com/feeds/api/users/'.$userid;
		$item['datalink']=$url;
		
		
		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);

		if(strpos($htmlcode,'<?xml version')===false)
		{
			if(strpos($htmlcode,'Invalid id')===false)
				return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection';
		}
		
		$blankArray['datalink']=$url;
		
		//echo '$htmlcode='.$htmlcode.'<br/>';
		//die;
	
		$doc = new DOMDocument;
		$doc->loadXML($htmlcode);
		
		
			$item['channel_username']=$doc->getElementsByTagName("username")->item(0)->nodeValue;
			$item['channel_title']=$doc->getElementsByTagName("title")->item(0)->nodeValue;
			$item['channel_description']=$doc->getElementsByTagName("content")->item(0)->nodeValue;
			$item['channel_location']=$doc->getElementsByTagName("location")->item(0)->nodeValue;
			
			
			$feedLink=$doc->getElementsByTagName("feedLink");
			if($feedLink->length>0)
			{
				foreach($feedLink as $fe)
				{
					$rel=$fe->getAttribute("rel");
					
					
					if(!(strpos($rel,'#user.subscriptions')===false))
						$item['channel_subscribed']=$fe->getAttribute("countHint");
						
					if(!(strpos($rel,'#user.contacts')===false))
						$item['channel_commentcount']=$fe->getAttribute("countHint");
						
					if(!(strpos($rel,'#user.uploads')===false))
						$item['channel_videocount']=$fe->getAttribute("countHint");
				}
			}
			
			$statistics=$doc->getElementsByTagName("statistics");
			$se=$statistics->item(0);
			$item['channel_subscribers']=$se->getAttribute("subscriberCount");
			$item['channel_viewcount']=$se->getAttribute("viewCount");
			$item['channel_totaluploadviews']=$se->getAttribute("totalUploadViews"); 
			
		
		return '';
		
	}
	*/
	

}


?>