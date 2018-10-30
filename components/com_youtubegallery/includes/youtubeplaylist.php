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

class VideoSource_YoutubePlaylist
{
	public static function extractYouTubePlayListID($youtubeURL)
	{
		$arr=YouTubeGalleryMisc::parse_query($youtubeURL);
		
		$p=$arr['list'];
		
		if(strlen($p)<3)
			return '';
		
		$allowedtypes=array('PL');//,'FL');
		$t=substr($p,0,2);
		if(!in_array($t,$allowedtypes))
			return ''; //incorrect playlist ID
		 
		return substr($p,2); //return without leading "PL"
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$playlistid,&$datalink)
	{
		$api_key = YouTubeGalleryMisc::getSettingValue('youtube_api_key');
		
		if($api_key=='')
			return array();
		
		$playlistid=VideoSource_YoutubePlaylist::extractYouTubePlayListID($youtubeURL);
		
		$videolist=VideoSource_YoutubePlaylist::getPlaylistVideos($playlistid,$datalink,$api_key,$optionalparameters);
		
		return $videolist;
	}
	
	public static function getPlaylistVideos($playlistid,&$datalink,$api_key,$optionalparameters)
	{
		$base_url='https://www.googleapis.com/youtube/v3';
		$videolist=array();
		
		if($playlistid=='')
			return $videolist; //playlist id not found
		
		$part='id,snippet';
		
		$optionalparameters_arr=explode(',',$optionalparameters);
		
		$spq=implode('&',$optionalparameters_arr);
		$spq=str_replace('max-results','maxResults',$spq); //API 2 -> API 3 conversion
		$datalink = $base_url.'/playlistItems?part='.$part.'&key='.$api_key.'&playlistId='.$playlistid.($spq!='' ? '&'.$spq : '' );
		
		$opt="";
		$count=YouTubeGalleryMisc::getMaxResults($spq,$opt);
		if($count<1)
			$maxResults=1;
		elseif($count>50)
			$maxResults=50;
		else
			$maxResults=$count;
				
		$videos_found=0;
		$nextPageToken='';
		while($videos_found<$count)
		{
			$newspq=str_replace($opt,'maxResults='.$maxResults,$spq);

			$url = $base_url.'/playlistItems?part='.$part.'&key='.$api_key.'&playlistId='.$playlistid.($newspq!='' ? '&'.$newspq : '' );
			if($nextPageToken!='')
				$url.='&pageToken='.$nextPageToken;
		
			$htmlcode=YouTubeGalleryMisc::getURLData($url);
			
			if($htmlcode=='')
				return $videolist;
		
			$j=json_decode($htmlcode);
			if(!$j)
				return 'Connection Error';

			if(isset($j->nextPageToken))
				$nextPageToken=$j->nextPageToken;
			else
				$nextPageToken='';
				
			$pageinfo=$j->pageInfo;
			if($pageinfo->totalResults<$count)
				$count=$pageinfo->totalResults;
			
			$items=$j->items;
			
			if(count($items)<$maxResults)
				$maxResults=count($items);
		
			foreach($items as $item)
			{
				if($item->kind=='youtube#playlistItem')
				{
					$s=$item->snippet->resourceId;
					if($s->kind=='youtube#video')
					{
						$videoId=$s->videoId;
						$videolist[] = 'https://www.youtube.com/watch?v='.$videoId;
					}
				}
			}
			
			$videos_found+=$maxResults;
			if($count-$videos_found<50)
				$maxResults=$count-$videos_found;
		}
		
		return $videolist;
	}
}
?>