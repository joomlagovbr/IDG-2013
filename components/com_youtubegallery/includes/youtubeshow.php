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

class VideoSource_YoutubeShow
{
	protected static function deleteParameter($arr,$par)
	{
		$new_arr=array();
		foreach($arr as $a)
		{
			$pair=explode('=',$a);
			if($pair[0]!=$par)
				$new_arr[]=$a;
		}
		
		return $new_arr;
	}
	
	protected static function getValueOfParameter($arr,$par)
	{
		foreach($arr as $a)
		{
			$pair=explode('=',$a);
			if($pair[0]==$par)
			{
				if(isset($pair[1]))
					return $pair[1];
				else
					return '';
			}
		}
		return '';
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$playlistid,&$datalink)
	{
		//not ready yet
		//return array();
		//************************************************************************************************************************************
		
		$base_url='https://www.googleapis.com/youtube/v3';

		//https://developers.google.com/youtube/v3/docs/search/list
		/*
		*videoType 	string 	The videoType parameter lets you restrict a search to a particular type of videos. If you specify a value for this parameter, you must also set the type parameter's value to video.

		Acceptable values are:

		any – Return all videos.
		episode – Only retrieve episodes of shows.
		movie – Only retrieve movies.

		 */
		$optionalparameters_arr=explode(',',$optionalparameters);
		
		$videolist=array();
		$season=VideoSource_YoutubeShow::getValueOfParameter($optionalparameters_arr,'season');
		$content_type=VideoSource_YoutubeShow::getValueOfParameter($optionalparameters_arr,'content');
		if($content_type=='')
			$content_type='episodes';

		$season=explode(':',$season);
		
		if(count($season)==4)
			$season_id=$season[2];
		else
			return $videolist; //season id not found
		

		$optionalparameters_arr=VideoSource_YoutubeShow::deleteParameter($optionalparameters_arr,'season');
		$optionalparameters_arr=VideoSource_YoutubeShow::deleteParameter($optionalparameters_arr,'content');
		
		$part='id,snippet';
		$spq=implode('&',$optionalparameters_arr);
		$spq=str_replace('max-results','maxResults',$spq);
		$datalink = 'http://gdata.youtube.com/feeds/api/seasons/'.$season_id.'/'.$content_type.'?v=3'.($spq!='' ? '&'.$spq : '' ) ;

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

			$nextPageToken=$j->nextPageToken;
				
			$pageinfo=$j->pageInfo;
			if($pageinfo->totalResults<$count)
				$count=$pageinfo->totalResults;
			
			$items=$j->items;
			
			if(count($items)<$maxResults)
				$maxResults=count($items);
				
			foreach ($xml->entry as $entry)
			{
				$link = $entry->link->attributes();
				$videolist[] = $link['href'];
			}
		}
		
		$videos_found+=$maxResults;
		if($count-$videos_found<50)
			$maxResults=$count-$videos_found;
				
		return $videolist;
		
	}
	
	


}


?>