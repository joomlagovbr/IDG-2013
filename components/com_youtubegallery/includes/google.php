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

class VideoSource_Google
{
		
	public static function extractGoogleID($theLink)
	{
				
		$arr=YouTubeGalleryMisc::parse_query($theLink);
	    return $arr['docid'];
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		$theTitle='';
		$Description='';
		$theImage='';
						
		//if($firstvideo=='')
			//$firstvideo=$videoid;
		$XML_SOURCE='';
							
		if($customimage!='')
			$theImage=$customimage;
		else
		{
			if(ini_get('allow_url_fopen'))
			{
				$XML_SOURCE=YouTubeGalleryMisc::getURLData('http://video.google.com/videofeed?docid='.$videoid);
				
				$match = array();
				preg_match("/media:thumbnail url=\"([^\"]\S*)\"/siU",$XML_SOURCE,$match);
				$theImage=$match[1];
			}//if(ini_get('allow_url_fopen'))
		}//if($customimage!='')
						
							
			$theTitle='Google Video';
						
			if($customtitle!='')
				$theTitle=$customtitle;
									
			if($customdescription!='')
				$Description=$customdescription;
									
			return array('videosource'=>'google', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>$theTitle,'description'=>$Description);
	
		
	}
	
	
	public static function renderGooglePlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$result='<embed'
			.' id="'.$playerid.'"'
			.' src="http://video.google.com/googleplayer.swf?docid='.$videoidkeyword.'&hl=en&fs='.($options['fullscreen'] ? 'true' : 'false').'"'
			.' style="width:'.$width.'px;height:'.$height.'px;"'
			.' allowFullScreen="'.($options['fullscreen'] ? 'true' : 'false').'"'
			.' allowScriptAccess="always"'
			.($options['autoplay'] ? ' Flashvars="autoPlay=true"' : '')
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')			
			.' type=application/x-shockwave-flash>'
		.'</embed>';
		
		return $result;
	}
	
}