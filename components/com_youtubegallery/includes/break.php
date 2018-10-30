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
class VideoSource_Break
{
	//http://www.break.com/pranks/biker-falls-off-dock-wall-2392751

	public static function extractBreakID($theLink,&$HTML_SOURCE)
	{
		if($HTML_SOURCE=='')
			$HTML_SOURCE=YouTubeGalleryMisc::getURLData($theLink);
		
		$ActualLink=VideoSource_Break::getValueByAlmostTag($HTML_SOURCE,'<meta name="embed_video_url" content="');
		
		preg_match('/break.com\/(\d+)$/', $ActualLink, $matches);
		if (count($matches) != 0)
		{
			$video_id = $matches[1];

			return $video_id;
		}

		return '';
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
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription, &$HTML_SOURCE='')
	{
		$theTitle='';
		$Description='';
		$theImage='';
		
		$theImage=VideoSource_Break::getValueByAlmostTag($HTML_SOURCE,'<meta name="embed_video_thumb_url" content="');		
		
		if($theImage=='')
			return array('videosource'=>'break', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>'***Video not found***','description'=>'Video not Found or Permission Denied.');					
					
		if($customimage!='')
			$theImage=$customimage;
		

			if($customtitle=='')
			{
					$theTitle=VideoSource_Break::getValueByAlmostTag($HTML_SOURCE,'<meta name="embed_video_title" id="vid_title" content="');		
			}
			else
				$theTitle=$customtitle;


		
			if($customdescription=='')
			{
					$Description=VideoSource_Break::getValueByAlmostTag($HTML_SOURCE,'<meta name="embed_video_description" id="vid_desc" content="');		
			}
			else
				$Description=$customdescription;

			
							
		return array('videosource'=>'break', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>$theTitle,'description'=>$Description);

		
	}


	public static function renderBreakPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$result='';
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
		
		$result.=
		
		'<object'
			.' width="'.$width.'"'
			.' height="'.$height.'"'
			.' id="'.$playerid.'"'
			.' type="application/x-shockwave-flash"'
			.' classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'
			.' alt="'.$title.'"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.'> ';
		
		$result.=''
			.'<param name="movie" value="http://embed.break.com/'.base64_encode($videoidkeyword).'" />'
			.'<param name="allowScriptAccess" value="always" />'
			.'<param name="flashvars" value="playerversion=12&defaultHD=true" />'
			.'<param name="id" value="'.$playerid.'" />'			
			.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />';
			
		
			
			
		$result.=''
			.'<embed src="http://embed.break.com/'.base64_encode($videoidkeyword).'" '
				.'type="application/x-shockwave-flash" '
				.'flashvars="playerversion=12&defaultHD=true" '
				.'allowScriptAccess="always" '
				
				.'allowfullscreen="'.($options['fullscreen'] ? 'true' : 'false').'" '
				.'width="'.$width.'" '
				.'height="'.$height.'" />'
		.'</object>';
		
	
		return $result;
	}
}


?>