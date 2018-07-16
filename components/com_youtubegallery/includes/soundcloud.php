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
//not finished
class VideoSource_SoundCloud
{


	public static function extractID($theLink)
	{
		// http://api.soundcloud.com/tracks/49931.json
		
		$l=explode('/',$theLink);
		
		if(count($l)>4)
		{
			$a=explode('.',$l[4]);
			return $a[0];
		}
		
		return '';
		
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		//blank	array
		$blankArray=array(
				'videosource'=>'soundcloud',
				'videoid'=>$videoid,
				'imageurl'=>'',
				'title'=>'',
				'description'=>'',
				'publisheddate'=>'',
				'duration'=>0,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>0,
				'keywords'=>'',
				'likes'=>0,
				'dislikes'=>'',
				'commentcount'=>'',
				'channel_username'=>'',
				'channel_title'=>'',
				'channel_subscribers'=>0,
				'channel_subscribed'=>0,
				'channel_location'=>'',
				'channel_commentcount'=>0,
				'channel_viewcount'=>0,
				'channel_videocount'=>0,
				'channel_description'=>''
				);
		
		$theTitle='';
		$Description='';
		$theImage='';
		
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		
		$client_id = YouTubeGalleryMisc::getSettingValue('soundcloud_api_client_id');
		//$consumer_secret = YouTubeGalleryMisc::getSettingValue('soundcloud_api_client_secret');
		$url='http://api.soundcloud.com/tracks/'.$videoid.'.json?client_id='.$client_id;
		//echo '$url='.$url.'<br/>';
		
		$HTML_SOURCE=YouTubeGalleryMisc::getURLData($url);
		
		if($HTML_SOURCE=='')
		{
			$blankArray['title']='***Video not found***';
			$blankArray['description']='';
			return $blankArray;
		}
		//-----------------------------------------------------------------------------------------------
		
		$strPart='{"kind":"track","id":';
		$strPartLength=strlen($strPart);
		$test=substr($HTML_SOURCE, 0,$strPartLength);
		if($test!=$strPart)
		{
			$blankArray['title']='***Cannot Connect to SoundCloud Server***';
			$blankArray['description']='Check your API Client ID (go to Setting).';
			return $blankArray;
		}
		
		$obj = json_decode($HTML_SOURCE);
		
		
		$blankArray['title']=$obj->title;
		
		$blankArray['description']=$obj->description;
		$blankArray['publisheddate']=$obj->created_at;
		$blankArray['duration']=floor($obj->duration/1000);
		$blankArray['keywords']=$obj->tag_list;
		$blankArray['statistics_viewCount']=$obj->playback_count;
		$blankArray['statistics_favoriteCount']=$obj->favoritings_count;
		$blankArray['commentcount']=$obj->comment_count;
		$blankArray['imageurl']=$obj->artwork_url;
		
		$u=$obj->user;
		
		$blankArray['channel_username']=$u->username;
		$blankArray['channel_title']=$u->username;
		
		
		if($customtitle!='')
			$blankArray['title']=$customtitle;

		if($customdescription!='')
			$blankArray['description']=$customdescription;
		
		if($customimage!='' and strpos($customimage, '#')===false)
		{
			$blankArray['imageurl']=$customimage;
		}
		
		return $blankArray;
		
	}




	public static function renderPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$settings=array();

		//$settings[]=array('loop',(int)$options['repeat']);
		$settings[]=array('auto_play',((int)$options['autoplay']) ? 'true' : 'false');
		$settings[]=array('hide_related',((int)$options['relatedvideos']) ? 'false' : 'true');
		
		if($options['showinfo']==0)
		{
			$settings[]=array('show_artwork',false);
			$settings[]=array('visual',false);
			
		}
		else
		{
			$settings[]=array('show_artwork',true);
			$settings[]=array('visual',true);
			
		}

		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		
		
		$result='';
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
			
		
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
			$http='https://';
		else
			$http='http://';
			
		$data=$http.'w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$videoidkeyword.'&amp;'.$settingline;
		
		
		//<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/22890406&amp;auto_play=false&amp;hide_related=false&amp;visual=true"></iframe>
		//<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/22890406&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true"></iframe>
		
		$result.=
		
		'<iframe src="'.$data.'"'
			.' id="'.$playerid.'"'
			.' width="'.$width.'"'
			.' height="'.$height.'"'
			.' alt="'.$title.'"'
			.' frameborder="'.((int)$options['border']==1 ? 'yes' : 'no').'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.'>'
		.'</iframe>';
		
		
	
		return $result;
	}
}
?>

