<?php
/**
 * YoutubeGallery
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

class VideoSource_YoutubeSearch
{
	public static function extractYouTubeSearchKeywords($youtubeURL)
	{
		//http://www.youtube.com/results?search_query=%22dogs+101%22&oq=%22dogs+101%22&gs_l=youtube.3..0l10.16119.16453.0.17975.2.2.0.0.0.0.330.649.3-2.2.0...0.0...1ac.1.GQ5tbo9Q0Cg
		$arr=YouTubeGalleryMisc::parse_query($youtubeURL);
		
		$p=urldecode($arr['search_query']);
		if(!isset($p) or $p=='')
			return ''; //incorrect Link
		//echo $p;
		//die;
		
		$keywords=str_replace('"','',$p);
		$keywords=str_replace('+',' ',$keywords);
		$keywords=str_replace(' ',',',$keywords);
		//$keywords=str_replace(',','%2C',$keywords);
		 
	    return $keywords;
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$playlistid)
	{
		$optionalparameters_arr=explode(',',$optionalparameters);
		
		$videolist=array();
		
		$spq=implode('&',$optionalparameters_arr);
		
		$videolist=array();
		
		$keywords=VideoSource_YoutubeSearch::extractYouTubeSearchKeywords($youtubeURL);
		//echo '$keywords='.$keywords.'<br/>';
		//die;
		
		if($keywords=='')
			return $videolist; //WRONG LINK id not found
		
		$url = 'https://gdata.youtube.com/feeds/api/videos?q='.urlencode($keywords).'&v=2&'.$spq;
		
		
		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);

		//print_r($htmlcode);
		//die;

		if(strpos($htmlcode,'<?xml version')===false)
		{
			if(strpos($htmlcode,'Invalid id')===false)
				return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection';
		}
		
		$xml = simplexml_load_string($htmlcode);
		
		//print_r($xml);
		//die;
		
		if($xml){
			foreach ($xml->entry as $entry)
			{

				$media = $entry->children('http://search.yahoo.com/mrss/');
				$link = $media->group->player->attributes();
				if(isset($link))
				{
					if(isset($link['url']))
					{
						$videolist[] = $link['url'];
					}
				}//if(isset($link)
			}//foreach ($xml->entry as $entry)
		}//if($xml){
		
		
		//print_r($videolist);
		//die;
		
		return $videolist;
		
	}
	
	


}


?>