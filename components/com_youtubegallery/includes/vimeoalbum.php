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


class VideoSource_VimeoAlbum
{
	public static function extractVimeoAlbumID($vimeo_user_link)
	{
		//https://vimeo.com/album/2585295
		$matches=explode('/',$vimeo_user_link);
		
		if (count($matches) >4)
		{
			if($matches[3]!='album')
				return ''; //not a channel link
			
			return $matches[4];
			
		}
				
	    return '';
	}
	
	public static function getVideoIDList($vimeo_user_link,$optionalparameters,&$userid)
	{
		
		$videolist=array();
		$optionalparameters_arr=explode(',',$optionalparameters);
		
		$album_id=VideoSource_VimeoAlbum::extractVimeoAlbumID($vimeo_user_link);
				
		
		//-------------- prepare our Consumer Key and Secret
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		
		$consumer_key = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_id');
		$consumer_secret = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_secret');
		
		if($consumer_key=='' or $consumer_secret=='')
		{
			return $videolist;
		}
		//--------------

		require_once('vimeo_api.php');
		
		$session = JFactory::getSession();
		
		if(!isset($session))
			session_start();
	
		
		if(isset($session->get('oauth_access_token')))
			$s_oauth_access_token=$session->get('oauth_access_token');
		else
			$s_oauth_access_token='';
			
		if(isset($session->get('oauth_access_token_secret')))
			$s_oauth_access_token_secret=$session->get('oauth_access_token_secret');
		else
			$s_oauth_access_token_secret='';
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $s_oauth_access_token, $s_oauth_access_token_secret);
		

		$params = array();
		$params['album_id'] = $album_id;
		
		foreach($optionalparameters_arr as $p)
		{
			$pair=explode('=',$p);
			if($pair[0]=='page')
				$params['page'] = (int)$pair[1];
				
			if($pair[0]=='per_page')
				$params['per_page'] = (int)$pair[1];
		}
		
		$videos = $vimeo->call('vimeo.albums.getVideos',$params);
		

		
		foreach($videos->videos->video as $video)
		{
			$videolist[] = 'http://vimeo.com/'.$video->id;
		}
		
	
		return $videolist;
		
	}
	

}


?>