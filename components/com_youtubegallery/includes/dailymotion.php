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
class VideoSource_DailyMotion
{


	public static function extractDailyMotionID($theLink)
	{

		if(strpos($theLink,'dailymotion.com/video/')===false)
		{
			//http://www.dailymotion.com/au/relevance/search/camp+fire+1/1#video=x16ckln
			$l=explode('#',$theLink);

			if(count($l)>1)
			{
				$a=explode('=',$l[1]);

				if(count($a)>1)
					return $a[1];
			}
			//die;
		}
		else
		{
			//http://www.dailymotion.com/video/xrcy5b
			$l=explode('/',$theLink);
			if(count($l)>4)
			{
				$a=explode('_',$l[4]);
				$b=explode('#',$a[0]);
				return $b[0];
			}
		}

		return '';
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		//API
		//http://www.dailymotion.com/doc/api/obj-video.html
		
		$theTitle='';
		$Description='';
		$theImage='';
		$fields='created_time,description,duration,rating,ratings_total,thumbnail_small_url,thumbnail_medium_url,title,views_total';
		$HTML_SOURCE=YouTubeGalleryMisc::getURLData('https://api.dailymotion.com/video/'.$videoid.'?fields='.$fields);
		//echo '$HTML_SOURCE='.$HTML_SOURCE.'<br/>';
		
		if($HTML_SOURCE!='' and $HTML_SOURCE[0]=='{')
		{
			$streamData = json_decode($HTML_SOURCE);
			
			if($customimage=='')
				$theImage=$streamData->thumbnail_small_url;
			else
				$theImage=$customimage;
		
			if($customtitle=='')
				$theTitle=$streamData->title;
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description=$streamData->description;
			else
				$Description=$customdescription;
		
		$videodata=array(
				'videosource'=>'dailymotion',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>date('Y-m-d H:i:s',$streamData->created_time),
				'duration'=>$streamData->duration,
				'rating_average'=>$streamData->rating,
				'rating_max'=>$streamData->ratings_total,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>$streamData->views_total,
				'keywords'=>''
			);
			
			return $videodata;
		}
		else
		{
			return array(
					'videosource'=>'collegehumor',
					'videoid'=>$videoid,
					'imageurl'=>$theImage,
					'title'=>'***Video not found***',
					'description'=>$Description
					);
		}
	}




	public static function renderDailyMotionPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		//http://www.dailymotion.com/doc/api/player.html
		
		$videoidkeyword='****youtubegallery-video-id****';
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
			
			
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$settings=array();
		$settings[]=array('autoplay',(int)$options['autoplay']);
		$settings[]=array('related',$options['relatedvideos']);
		$settings[]=array('controls',$options['controls']);
		if($theme_row->logocover)
			$settings[]=array('logo','0');
		else
			$settings[]=array('logo','1');
			
		if($options['color1']!='')
			$settings[]=array('foreground',$options['color1']);
			
		if($options['color2']!='')
			$settings[]=array('highlight',$options['color2']);
			
		$settings[]=array('info',$options['showinfo']);
		
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		$result='';
		//echo '$videoidkeyword='.$videoidkeyword.'<br/>';
		
		$result.='<iframe '
			.' id="'.$playerid.'"'
			.' alt="'.$title.'"'
			.' frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.dailymotion.com/embed/video/'.$videoidkeyword.'?'.$settingline.'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.'></iframe>';
		
		return $result;
	}
}
?>

