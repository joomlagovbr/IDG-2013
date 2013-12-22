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


class VideoSource_YoutubeUserFavorites
{
	public static function extractYouTubeUserID($youtubeURL)
	{
		//link example: http://www.youtube.com/user/acharnesnews/favorites
		$matches=explode('/',$youtubeURL);
	

		if (count($matches) >3)
		{
			
			$userid = $matches[4];

			return $userid;
		}
				
	    return '';
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$userid)
	{
		
		
		
		$optionalparameters_arr=explode(',',$optionalparameters);
		$videolist=array();
		
		$spq=implode('&',$optionalparameters_arr);

		$userid=VideoSource_YoutubeUserFavorites::extractYouTubeUserID($youtubeURL);
		
		if($userid=='')
			return $videolist; //user id not found

		$url = 'http://gdata.youtube.com/feeds/api/users/'.$userid.'/favorites?v=2'.($spq!='' ? '&'.$spq : '' ) ; //&max-results=10

		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);

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

				$attr=$entry->link[0]->attributes();

				if(isset($entry->link[0]) && $attr['rel'] == 'alternate')
				{
					$videolist[] = $attr['href'];
                }
				else
				{
					$attr=$entry->link[1]->attributes();
					$videolist[] = $attr['href'];
				}
			}
		}
		
		return $videolist;
		
	}
	
	

}


?>