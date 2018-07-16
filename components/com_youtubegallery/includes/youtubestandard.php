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


class VideoSource_YoutubeStandard
{

	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$playlistid,&$datalink)
	{
		$linkPair=explode(':',$youtubeURL);
		
		if(!isset($linkPair[1]))
			return array();	
		
		$url='';
		
		$playlistid=$linkPair[1];
		
		switch($linkPair[1])
		{
			case 'top_rated':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/top_rated';
				break;
			
			case 'top_favorites':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/top_favorites';
				break;
			
			case 'most_viewed':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_viewed';
				break;
			
			case 'most_shared':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_shared';
				break;
			
			case 'most_popular':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_popular';
				break;
			
			case 'most_recent':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_recent';
				break;
			
			case 'most_discussed':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_discussed';
				break;
			
			case 'most_responded':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/most_responded';
				break;
			
			case 'recently_featured':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/recently_featured';
				break;
			
			case 'on_the_web':
				$url='https://gdata.youtube.com/feeds/api/standardfeeds/on_the_web';
				break;
			
			default:
				return array();	
			break;
		}
		$datalink=$url;
			
		
		$optionalparameters_arr=explode(',',$optionalparameters);
		$videolist=array();
		
		$spq=implode('&',$optionalparameters_arr);
		
		
		$spq=str_replace('max-results','maxResults',$spq);
		$url.= ($spq!='' ? '?'.$spq : '' );
		
		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);
		
		if($htmlcode=='')
			return $videolist;

		if(strpos($htmlcode,'<?xml version')===false)
		{
			if(strpos($htmlcode,'Invalid id')===false)
				return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection';
		}
		
		$xml = simplexml_load_string($htmlcode);
		
		if($xml){
			foreach ($xml->entry as $entry)
			{
				/*
				if(isset($entry->link[0]))
				{
					$link=$entry->link[0];
					$attr = $link->attributes();
					
					$videolist[] = $attr['href'];
				}
				*/
				
				//
				$media = $entry->children('http://search.yahoo.com/mrss/');
				$link = $media->group->player->attributes();
				if(isset($link['url']))
				{
					$videolist[] = $link['url'];
				}
				//
				
			}
		}
		
		return $videolist;
		
	}
	
	


}


?>