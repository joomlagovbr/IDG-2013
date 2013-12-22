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


class VideoSource_VimeoUserVideos
{
	public static function extractVimeoUserID($vimeo_user_link)
	{
		//http://vimeo.com/user13484491
		$matches=explode('/',$vimeo_user_link);
		
		if (count($matches) >3)
		{
			
			$userid = $matches[3];
			
			//if(strpos($userid,'user')===false)
			//{
				//SEF LINK
			//	return 'SEF&'.$userid;
			//}
			//else
				return str_replace('user','',$userid);
			
		}
				
	    return '';
	}
	
	public static function getVideoIDList($vimeo_user_link,$optionalparameters,&$userid)
	{
		$videolist=array();
		$optionalparameters_arr=explode(',',$optionalparameters);

		$userid=VideoSource_VimeoUserVideos::extractVimeoUserID($vimeo_user_link);
		
		
		//-------------- prepare our Consumer Key and Secret
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		
		$consumer_key = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_id');
		$consumer_secret = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_secret');
		
		if($consumer_key=='' or $consumer_secret=='')
		{
			return $videolist;
		}
		//--------------

		require_once('vimeo_api.php');
		
		if(!isset($_SESSION))
			session_start();
		
		if(isset($_SESSION['oauth_access_token']))
			$s_oauth_access_token=$_SESSION['oauth_access_token'];
		else
			$s_oauth_access_token='';
			
		if(isset($_SESSION['oauth_access_token_secret']))
			$s_oauth_access_token_secret=$_SESSION['oauth_access_token_secret'];
		else
			$s_oauth_access_token_secret='';
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $s_oauth_access_token, $s_oauth_access_token_secret);
		
		
		$params = array();
		
		$params['user_id'] = $userid;
		
		foreach($optionalparameters_arr as $p)
		{
			$pair=explode('=',$p);
			if($pair[0]=='page')
				$params['page'] = (int)$pair[1];
				
			if($pair[0]=='per_page')
				$params['per_page'] = (int)$pair[1];
		}
		
		
		$videos = $vimeo->call('vimeo.videos.getAll',$params);
		
		foreach($videos->videos->video as $video)
		{
			$videolist[] = 'http://vimeo.com/'.$video->id;
		}
	
		return $videolist;
		
	}
	

}


?>