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

class VideoSource_Own3DTvVideo
{

	public static function extractOwn3DTvVideoID($theLink)
	{
		preg_match('/http:\/\/own3d.tv\/v\/(\d+)$/', $theLink, $matches);
		if (count($matches) != 0)
		{
			$video_id = $matches[1];
			
			return $video_id;
		}
		
		return '';
	}

	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription)
	{
		$theTitle='';
		$Description='';
		$theImage='';

		$videodata=array();
		
		if(phpversion()<5)
			return "Update to PHP 5+";


				
		try{
			
				    
			$url = 'http://api.own3d.tv/rest/video/list.xml?videoid='.$videoid;
			
			$htmlcode=YouTubeGalleryMisc::getURLData($url);
			
			
			if(strpos($htmlcode,'<?xml version')===false)
			{
				
				$pair=array('Invalid id','Invalid id','','0','0','0','0','0','0','0','');

				return $pair;
			}
			

			$doc = new DOMDocument;
			$doc->loadXML($htmlcode);
			

			$video_thumb_file_small='http://owned.vo.llnwd.net/v1/static/static/images/thumbnails/tn_'.$videoid.'__1.jpg';
			
			$videodata=array(
				'videosource'=>'own3dtvvideo',
				'videoid'=>$videoid,
				'imageurl'=>$video_thumb_file_small,
				'title'=>$doc->getElementsByTagName("video_name")->item(0)->nodeValue,
				'description'=>$doc->getElementsByTagName("video_description")->item(0)->nodeValue,
				'publisheddate'=>"",
				'duration'=>$doc->getElementsByTagName("video_duration")->item(0)->nodeValue,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>$doc->getElementsByTagName("video_views_total")->item(0)->nodeValue,
				'keywords'=>''
			);

			return $videodata;
    
		
		}
		catch(Exception $e)
		{
			return 'cannot get youtube video data';
		}
		
		
		
		return array('videosource'=>'own3dtvvideo', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>$theTitle,'description'=>$Description);
	}
	
	public static function renderOwn3DTvVideoPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$border_width=3;
		
		if((int)$options['border']==1 and $options['color1']!='')
		{
			$width=((int)$width)-($border_width*2);
			$height=((int)$height)-($border_width*2);
		}
		
		//Flash Player (detection not availabale)
		$result='<object width="'.$width.'" height="'.$height.'">'
				.'<param name="movie" value="http://www.own3d.tv/stream/'.$videoidkeyword.';autoplay='.( ((int)$options['autoplay'])==1 ? 'true' : '').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.'<param name="allowfullscreen" value="true" />'
				.'<param name="wmode" value="transparent" />'
				.'<embed src="http://www.own3d.tv/stream/'.$videoidkeyword.';autoplay=true" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'.$width.'" height="'.$height.'" wmode="transparent"></embed></object>'
		.'';
			
			
		
		return $result;
	}
	

}


?>
