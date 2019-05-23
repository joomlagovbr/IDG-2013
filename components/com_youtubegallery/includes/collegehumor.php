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
class VideoSource_CollegeHumor
{


	public static function extractCollegeHumorID($theLink)
	{
		$l=explode('/',$theLink);
		if(count($l)>5)
			return $l[4];
		
		
		return '';
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
				
		$theTitle='';
		$Description='';
		$theImage='';
	
		$HTML_SOURCE=YouTubeGalleryMisc::getURLData('http://www.collegehumor.com/video/'.$videoid);
						
		$strPart='<meta name="og:image" content="';
		$strPartLength=strlen($strPart);
				
		$p1=strpos($HTML_SOURCE,$strPart);
		if($p1>0)
		{
			$p2=strpos($HTML_SOURCE,'"',$p1+$strPartLength);
			$theImage=substr($HTML_SOURCE,$p1+$strPartLength,$p2-$p1-$strPartLength);
			$theImage=str_replace('\\','',$theImage);
		}
		
		if($theImage=='')
		{
			return array(
												  'videosource'=>'collegehumor',
												  'videoid'=>$videoid,
												  'imageurl'=>$theImage,
												  'title'=>'***Video not found***',
												  'description'=>$Description
												  
												  );	
		}

		if($customimage!='')
			$theImage=$customimage;
						

		if($customtitle=='')
		{
			if(ini_get('allow_url_fopen'))
			{
				$theTitle='CollegeHumor';
				$strPart='<meta name="og:title" content="';
				$strPartLength=strlen($strPart);
				$p1=strpos($HTML_SOURCE,$strPart);
				if($p1>0)
				{
					$p2=strpos($HTML_SOURCE,'"',$p1+$strPartLength);
					$theTitle=substr($HTML_SOURCE,$p1+$strPartLength,$p2-$p1-$strPartLength);
				}
			}//if(ini_get('allow_url_fopen'))
		}
		else
			$theTitle=$customtitle;
						
						
		if($customdescription=='')
		{
			if(ini_get('allow_url_fopen'))
			{
				$Description='CollegeHumor';
				$strPart='<meta name="description" content="';
				$strPartLength=strlen($strPart);
				$p1=strpos($HTML_SOURCE,$strPart);
				if($p1>0)
				{
					$p2=strpos($HTML_SOURCE,'"',$p1+$strPartLength);
					$Description=substr($HTML_SOURCE,$p1+$strPartLength,$p2-$p1-$strPartLength);
				}
			}//if(ini_get('allow_url_fopen'))
		}
		else
			$Description=$customdescription;
						
													
		return array(
												  'videosource'=>'collegehumor',
												  'videoid'=>$videoid,
												  'imageurl'=>$theImage,
												  'title'=>$theTitle,
												  'description'=>$Description
												  
												  );	
	}




	public static function renderCollegeHumorPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$result='';
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
			
		
		$result.=
		
		'<object'
			.' id="'.$playerid.'"'
			.' type="application/x-shockwave-flash"'
			.' data="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id='.$videoidkeyword.'&use_node_id=true&fullscreen='.($options['fullscreen'] ? '1' : '0').'"'
			.' width="'.$width.'"'
			.' height="'.$height.'"'
			.' alt="'.$title.'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.'>';
		
		$result.=''
			.'<param name="id" value="'.$playerid.'" />'			
			.'<param name="movie" quality="best" value="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id='.$videoidkeyword.'&use_node_id=true&fullscreen='.($options['fullscreen'] ? '1' : '0').'" />'
			.'<param name="allowScriptAccess" value="always" />'
			.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
			.'<param name="wmode" value="transparent"/>';
			
		//first 8 chars is a video id
		$result.=''
			.'<embed src="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id='.$videoidkeyword.'&use_node_id=true&fullscreen='.($options['fullscreen'] ? '1' : '0').'" '
				.'type="application/x-shockwave-flash" '
				.'wmode="transparent" '
				.'allowScriptAccess="always" '
				.'allowfullscreen="'.($options['fullscreen'] ? 'true' : 'false').'" '
				.'width="'.$width.'" '
				.'height="'.$height.'" /> '
		.'</object>';
	
		return $result;
	}
}
?>

