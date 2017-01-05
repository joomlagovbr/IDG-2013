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
	
	

}


?>