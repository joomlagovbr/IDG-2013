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
class VideoSource_PresentMe
{


	public static function extractPresentMeID($theLink)
	{
		//http://www.present.me/view/82240-video-cv-blog-tutorials
		$l=explode('/',$theLink);
		if(count($l)>4)
		{
			$a=explode('-',$l[4]);
			
			return $a[0];
		}
		
		
		return '';
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		
		
		$theTitle='';
		$Description='';
		$theImage='';
		$Url='https://api.present.me/v1/oEmbed?url=https://https://present.me/view/'.$videoid.'&amp;format=json';
		
		$HTML_SOURCE=YouTubeGalleryMisc::getURLData($Url);
		

		
		if($HTML_SOURCE!='' and $HTML_SOURCE[0]=='{')
		{
			$streamData = json_decode($HTML_SOURCE);
			
			
			if($customimage=='')
				$theImage=$streamData->thumbnail_url;
			else
				$theImage=$customimage;
		
			if($customtitle=='')
				$theTitle=$streamData->title;
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description='';
			else
				$Description=$customdescription;
		
		$videodata=array(
				'videosource'=>'presentme',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>'',
				'duration'=>0,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>0,
				'keywords'=>'',
				'channel_username'=>$streamData->provider_name,
				'channel_title'=>$streamData->author_name
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




	public static function renderPresentMePlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		//https://www.present.me/view/82240-video-cv-blog-tutorials
		
		$videoidkeyword='****youtubegallery-video-id****';
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
			
			
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		/*
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
		*/
		
		
		$result='';
		
		$result.='<iframe '
			.' id="'.$playerid.'"';
			
		if(isset($options['title']))
			$result.=' alt="'.$options['title'].'"';
			
		$result.=' frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.present.me/embed/'.$width.'/'.$height.'/'.$videoidkeyword.'"';//.'?'.$settingline.'"'
		$result.=($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.' allowfullscreen></iframe>';
		
		
		return $result;
	}
}
?>

