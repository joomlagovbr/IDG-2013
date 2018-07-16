<?php
/**
 * YoutubeGallery
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'youtubeplaylist.php');

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
	
	

}


?>