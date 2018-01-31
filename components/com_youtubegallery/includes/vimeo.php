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


class VideoSource_Vimeo
{

	public static function extractVimeoID($theLink)
	{
		
		preg_match('/http:\/\/vimeo.com\/(\d+)$/', $theLink, $matches);
		if (count($matches) != 0)
		{
			$vimeo_id = $matches[1];
			
			return $vimeo_id;
		}
		else
		{
			preg_match('/https:\/\/vimeo.com\/(\d+)$/', $theLink, $matches);
			if (count($matches) != 0)
			{
				$vimeo_id = $matches[1];
				return $vimeo_id;
			}
		}
		
		return '';
	}

	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		
		$theTitle='';
		$Description='';
		$theImage='';
				
		
		//-------------- prepare our Consumer Key and Secret
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		
		$consumer_key = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_id');
		$consumer_secret = YouTubeGalleryMisc::getSettingValue('vimeo_api_client_secret');
		
		if($consumer_key=='' or $consumer_secret=='')
		{
			return array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>'Vimeo API Key not set. (YoutubeGallery/Settings)','description'=>'It\'s important to apply for your own API key.');
		}
		//--------------
		
		
		require_once('vimeo_api.php');
		
		if(!isset($_SESSION))
			session_start();
		
		if(isset($_SESSION['oauth_access_token']))
			$oauth_access_token=$_SESSION['oauth_access_token'];
		else
			$oauth_access_token='';
			
		if(isset($_SESSION['oauth_access_token_secret']))
			$oauth_access_token_secret=$_SESSION['oauth_access_token_secret'];
		else
			$oauth_access_token_secret='';
			
		
		$vimeo = new phpVimeo($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

		$params = array();
	        $params['video_id'] = $videoid;
		
		$video_info = $vimeo->call('videos.getInfo',$params);

		if(isset($video_info))
		{
			if(!$video_info)
				return array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>'***Video not found***','description'=>'Video not Found or Permission Denied.');
			
			if($customimage!='')
				$theImage=$customimage;
			else
				$theImage=$video_info->video[0]->thumbnails->thumbnail[1]->_content;
		
			if($customtitle=='')
				$theTitle=$video_info->video[0]->title;
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description=$video_info->video[0]->description;	
			else
				$Description=$customdescription;
			
			$keywords=array();
			
			if(isset($video_info->video[0]->tags->tag))
			{
				foreach($video_info->video[0]->tags->tag as $tag)
				{
					$keywords[]=$tag->_content;
				}
			}
			
			return array(
				'videosource'=>'vimeo',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>$video_info->video[0]->upload_date,
				'duration'=>$video_info->video[0]->duration,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>$video_info->video[0]->number_of_likes,
				'statistics_viewCount'=>$video_info->video[0]->number_of_plays,
				'keywords'=>implode(',',$keywords)
			);
		}
		else
			return array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>'***Video not found***','description'=>$Description);
	}
	
	public static function renderVimeoPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';

		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$settings=array();

		$settings[]=array('loop',(int)$options['repeat']);
		
		$settings[]=array('autoplay',(int)$options['autoplay']);
		
		if($options['showinfo']==0)
		{
			$settings[]=array('portrait',0);
			$settings[]=array('title',0);
			$settings[]=array('byline',0);
		}
		else
		{
			$settings[]=array('portrait',1);
			$settings[]=array('title',1);
			$settings[]=array('byline',1);
		}
		
		
		if($options['color1']!='')
			$settings[]=array('color',$options['color1']);
			
		
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		
		$border_width=3;
		
		if((int)$options['border']==1 and $options['color1']!='')
		{
			$width=((int)$width)-($border_width*2);
			$height=((int)$height)-($border_width*2);
		}
		
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
			$http='https://';
		else
			$http='http://';
				
		$vimeoserver=$http.'vimeo.com/';
		
		
		if($options['playertype']==1 or $options['playertype']==5) //new HTML 5 player
		{
			$data=$http.'player.vimeo.com/video/'.$videoidkeyword.'?'.$settingline;
		
			$result='<iframe src="'.$data.'"';
			$result.=''
			.' id="'.$playerid.'"'
			.' width="'.$width.'" height="'.$height.'" frameborder="'.(int)$options['border'].'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '');
			
			if((int)$options['border']==1 and $options['color1']!='')
			$result.=' style="border: '.$border_width.'px solid #'.$options['color1'].'"';
			
			
			$result.='></iframe>';
		}
		else
		{
			//if($options['playertype']==0 or $options['playertype']==3) //Flash AS 2.0 or 3.0 Player
			//elseif($options['playertype']==0 or $options['playertype']==3) //Flash AS 2.0 or 3.0 Player
			$data=$vimeoserver.'moogaloop.swf?clip_id='.$videoidkeyword.'&amp;'.$settingline;
			
			$result='<object'
				.' id="'.$playerid.'"'
				.' width="'.$width.'"'
				.' height="'.$height.'"'
				.' data="'.$data.'"'
				.' type="application/x-shockwave-flash"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '').'>'
				.'<param name="id" value="'.$playerid.'" />'
				.'<param name="movie" value="'.$data.'" />'
				.'<param name="wmode" value="transparent" />'
				.'<param name="allowfullscreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.'<embed src="'.$data.'"'
				.' type="application/x-shockwave-flash"'
				.' allowfullscreen="'.($options['fullscreen'] ? 'true' : 'false').'"'
				.' allowscriptaccess="always"'
				.' width="'.$width.'"'
				.' height="'.$height.'">'
				.'</embed>';
			$result.='</object>';
		}

		if($options['playertype']==2 or $options['playertype']==4) //Flash Player with detection 3 and 2
		{
			$data=$vimeoserver.'moogaloop.swf?clip_id='.$videoidkeyword.'&amp;'.$settingline;
			
			$alternativecode='You need Flash player 8+ and JavaScript enabled to view this video.';
			//<script src="'.$http.'www.google.com/jsapi" type="text/javascript"></script>
			$result_head='
			<!-- Youtube Gallery - Vimeo Flash Player With Detection -->
			<script src="'.$http.'ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js" type="text/javascript"></script>
			<script type="text/javascript">
			//<![CDATA[
				function youtubegallery_updateplayer_vimeo_'.$videolist_row->id.'(videoid)
				{
					var playerVersion = swfobject.getFlashPlayerVersion();
					if (playerVersion.major>0)
					{
						var playercode=\''.$result.'\';
						playercode=playercode.replace("****youtubegallery-video-id****",videoid);
						document.getElementById("YoutubeGallerySecondaryContainer'.$videolist_row->id.'").innerHTML=playercode;
					}
					else
						document.getElementById("YoutubeGallerySecondaryContainer'.$videolist_row->id.'").innerHTML="'.$alternativecode.'";
				}
			//]]>
			</script>
			<!-- end of Youtube Gallery - Vimeo Flash Player With Detection -->
			';
			
			$document = JFactory::getDocument();
			$document->addCustomTag($result_head);
			
			if($options['videoid']!='****youtubegallery-video-id****')
			{
				$result='
			<script type="text/javascript">
			//<![CDATA[
				youtubegallery_updateplayer_vimeo_'.$videolist_row->id.'("'.$options['videoid'].'");
			//]]>
			</script>
			';
			
			}
			else
				$result='<!--DYNAMIC PLAYER-->';
		}
		
		return $result;
	}
	


}


?>
