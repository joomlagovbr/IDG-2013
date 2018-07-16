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
class VideoSource_Ustream
{


	public static function extractUstreamID($theLink)
	{
		//http://www.ustream.tv/channel/nasa-tv-wallops
		//http://www.ustream.tv/recorded/40925310 - recorded
		$l=explode('/',$theLink);
		if(count($l)>4)
		{
			//$a=explode('_',$l[4]);
			
			return $l[4];//$a[0];
		}
		
		
		return '';
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
	
		$theTitle='';
		$Description='';
		$theImage='';
		
		$HTML_SOURCE=YouTubeGalleryMisc::getURLData('http://www.ustream.tv/recorded/'.$videoid);
		
		if($HTML_SOURCE!='' and $HTML_SOURCE[0]=='<')
		{
			
			
			if($customimage!='')
				$theImage=$customimage;
			else
			{
				$theImage=VideoSource_Ustream::getValueByAlmostTag($HTML_SOURCE,'<meta property="og:image" content="');
				$theImage=str_replace(',','%2C',$theImage);
			}
			
	
			if($customtitle=='')
				$theTitle=VideoSource_Ustream::getValueByAlmostTag($HTML_SOURCE,'<meta property="og:title" content="');
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description=VideoSource_Ustream::getValueByAlmostTag($HTML_SOURCE,'<meta property="og:description" content="');
			else
				$Description=$customdescription;
		
			$videodata=array(
				'videosource'=>'ustream',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>VideoSource_Ustream::getValueByAlmostTag($HTML_SOURCE,'<span data-dateformat="%F %j at %g:%i%a" data-timestamp="'),
				'duration'=>0,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>0,
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


	public static function getValueByAlmostTag($HTML_SOURCE,$AlmostTagStart,$AlmostTagEnd='"')
	{
		$vlu='';
		
		$strPartLength=strlen($AlmostTagStart);
		$p1=strpos($HTML_SOURCE,$AlmostTagStart);
		if($p1>0)
		{
			$p2=strpos($HTML_SOURCE,$AlmostTagEnd,$p1+$strPartLength);
			$vlu=substr($HTML_SOURCE,$p1+$strPartLength,$p2-$p1-$strPartLength);
		}
		return $vlu;
	}


	public static function renderUstreamPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		//http://www.dailymotion.com/doc/api/player.html
		
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$settings=array();

			
		if($options['color1']!='')
		{
			$settings[]=array('ub',$options['color1']);
			$settings[]=array('lc',$options['color1']);
		}	
		
		if($options['color2']!='')
		{
			$settings[]=array('oc',$options['color2']);
			$settings[]=array('uc',$options['color2']);
		}
			
		$settings[]=array('info',$options['showinfo']);
		$settings[]=array('wmode','direct');
		
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		$result='';

		$result.='<iframe '
			.' id="'.$playerid.'"';
			
		if(isset($options['title']))
			$result.=' alt="'.$options['title'].'"';
			
		$result.=' frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.ustream.tv/embed/recorded/'.$videoidkeyword.'?'.$settingline.'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.' scrolling="no" style="border: 0px none transparent;"></iframe>';
		
		return $result;
	}
}
?>

