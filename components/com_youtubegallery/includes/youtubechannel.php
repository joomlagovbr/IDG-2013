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

class VideoSource_YoutubeChannel
{
	protected static function extractID($youtubeURL)
	{
		//https://www.youtube.com/channel/UCRND2QLhATXcgrTgFfsZHyg/videos
		$matches=explode('/',$youtubeURL);
	
		if (count($matches) >4)
		{
			
			$channelid = $matches[4];
			$pair=explode('?',$channelid);
			return $pair[0];
		}
				
		return '';
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$channelid,&$datalink)
	{
		$optionalparameters_arr=explode(',',$optionalparameters);
		$videolist=array();
		$base_url='https://www.googleapis.com/youtube/v3';
		$api_key = YouTubeGalleryMisc::getSettingValue('youtube_api_key');
		
		if($api_key=='')
			return $videolist;
		
		$spq=implode('&',$optionalparameters_arr);
		$channelid=VideoSource_YoutubeChannel::extractID($youtubeURL);
		
		if($channelid=='')
			return $videolist; //user id not found
		
		$part='id,snippet';
		$spq=str_replace('max-results','maxResults',$spq);
		$datalink = $base_url.'/search?channelId='.$channelid.'&part='.$part.'&key='.$api_key.($spq!='' ? '&'.$spq : '' );
		
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

			$url = $base_url.'/search?channelId='.$channelid.'&part='.$part.'&key='.$api_key.($newspq!='' ? '&'.$newspq : '' );
			if($nextPageToken!='')
				$url.='&pageToken='.$nextPageToken;
		
			$htmlcode=YouTubeGalleryMisc::getURLData($url);

			if($htmlcode=='')
				return $videolist;
		
			$j=json_decode($htmlcode);
			if(!$j)
				return 'Connection Error';
			
			$nextPageToken=$j->nextPageToken;
				
			$pageinfo=$j->pageInfo;
			if($pageinfo->totalResults<$count)
				$count=$pageinfo->totalResults;
		
			$items=$j->items;
			
			if(count($items)<$maxResults)
				$maxResults=count($items);
				
			foreach($items as $item)
			{
				if($item->kind=='youtube#searchResult')
				{
					$idKind=$item->id->kind;
					if($idKind=='youtube#video')
					{
						$videoId=$item->id->videoId;
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