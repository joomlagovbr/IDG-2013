<?php
/**
 * YoutubeGallery
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

//https://developers.google.com/youtube/analytics/registering_an_application

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

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
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$userid)
	{
		$optionalparameters_arr=explode(',',$optionalparameters);
		$videolist=array();
		
		$spq=implode('&',$optionalparameters_arr);
		
		$userid=VideoSource_YoutubeUserUploads::extractYouTubeUserID($youtubeURL);
		
		//alteracoes projeto portal padrao
		require_once JPATH_ADMINISTRATOR . '/components/com_youtubegallery/google/_videos.php';
		$videos = new YoutubeVideos();
		$channelID = $videos->getChannelId($userid);
		@$channelID = $channelID[0];
		$video_raw = $videos->getVideosFromChannel( $channelID, 30, 'date' );

		if($userid=='' || empty($channelID))
			return $videolist; //user id not found
		
		for ($i=0,$limit=count($video_raw); $i < $limit; $i++)
		{ 
			$videolist[] = 'https://www.youtube.com/watch?v='.$video_raw[$i]['id']['videoId'];
		}
		
		return $videolist;

	}
	
	public static function getUserInfo($youtubeURL,&$item)
	{
				
		$userid=VideoSource_YoutubeUserUploads::extractYouTubeUserID($youtubeURL);
		
		if($userid=='')
			return 'user id not found';
		
		$url = 'http://gdata.youtube.com/feeds/api/users/'.$userid;

		
		
		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);

		if(strpos($htmlcode,'<?xml version')===false)
		{
			if(strpos($htmlcode,'Invalid id')===false)
				return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection';
		}
		
		echo '$htmlcode='.$htmlcode.'<br/>';
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
	

}


?>