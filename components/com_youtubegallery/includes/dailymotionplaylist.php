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

class VideoSource_DailymotionPlaylist
{
	public static function extractDailymotionPlayListID($URL)
	{
		//http://www.dailymotion.com/playlist/x1crql_BigCatRescue_funny-action-big-cats/1#video=x7k9rx
		//$arr=YouTubeGalleryMisc::parse_query($URL);
		$p=explode('/',$URL);
                
		//$p=$arr['list'];
		
		if(count($p)<4)
			return '';
		
                $p2=explode('_',$p[4]);
		if(count($p2)<1)
			return ''; //incorrect playlist ID
		 
	    return $p2[0]; //return without everything after _
	}
	
	public static function getVideoIDList($URL,$optionalparameters,&$playlistid)
	{
                //https://api.dailymotion.com/playlist/xy4h8/videos
		//$optionalparameters_arr=explode(',',$optionalparameters);
		
		//$videolist=array();
		
		//$spq=implode('&',$optionalparameters_arr);
		
		$videolist=array();
		
		$playlistid=VideoSource_DailymotionPlaylist::extractDailymotionPlayListID($URL);
		if($playlistid=='')
			return $videolist; //playlist id not found
                    
                    
                //echo '$playlistid='.$playlistid.'<br/>';
		
		$apiurl = 'https://api.dailymotion.com/playlist/'.$playlistid.'/videos';
                //$apiurl = 'https://api.dailymotion.com/playlist/xy4h8/videos';
		//echo '$apiurl ='.$apiurl .'<br/>';
		//$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($apiurl);
                //echo '$htmlcode='.$htmlcode.'<br/>';
                
                //die;
		if(!isset($htmlcode) or $htmlcode=='' or $htmlcode[0]!='{')

		{
			//if(strpos($htmlcode,'Invalid id')===false)
			//	return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection or access denied';
		}
		$streamData = json_decode($htmlcode);
//                print_r($streamData );



		foreach ($streamData->list as $entry)
		{
                    //print_r($entry);
                    $videolist[] = 'http://www.dailymotion.com/playlist/'.$entry->id;
                    //http://www.dailymotion.com/playlist/x1crql_BigCatRescue_funny-action-big-cats/1#video=x986zk

				//$media = $entry->children('http://search.yahoo.com/mrss/');
				//$link = $media->group->player->attributes();
				//if(isset($link))
				//{
				//	if(isset($link['url']))
				//	{
				///		$videolist[] = $link['url'];
				//	}
				//}//if(isset($link)
		}//foreach ($xml->entry as $entry)
		
		
                //print_r($videolist);
                //die;
		return $videolist;
            
            
		
	}
	
	


}


?>