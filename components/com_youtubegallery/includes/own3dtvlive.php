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
class VideoSource_Own3DTvLive
{

	public static function extractOwn3DTvLiveID($theLink)
	{
		
		preg_match('/http:\/\/own3d.tv\/l\/(\d+)$/', $theLink, $matches);
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
			
			
			$url = 'http://api.own3d.tv/rest/live/list.json?liveid='.$videoid;
			
			$htmlcode=YouTubeGalleryMisc::getURLData($url);
			
			if(strpos($htmlcode,'[{"')===false)
			{
				$pair=array('Invalid id','Invalid id','','0','0','0','0','0','0','0','');
				return $pair;
			}
			$streamData = json_decode($htmlcode);
			
			$p=explode('/',$streamData[0]->thumbnail_small);
			if(count($p)<5)
				$video_thumb_file_small='';
			else
				$video_thumb_file_small='http://owned.vo.llnwd.net/e2/live/'.$p[count($p)-1];
			
			
			$videodata=array(
				'videosource'=>'own3dtvlive',
				'videoid'=>$videoid,
				'imageurl'=>$video_thumb_file_small,
				'title'=>$streamData[0]->live_name,
				'description'=>$streamData[0]->live_description,
				'publisheddate'=>$streamData[0]->live_since,
				'duration'=>0,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>$streamData[0]->live_viewers,
				'keywords'=>''
			);
			
			
			$url_live_status = 'http://api.own3d.tv/liveCheck.php?live_id='.$videoid;
			$htmlcode=YouTubeGalleryMisc::getURLData($url_live_status);
			if(strpos($htmlcode,'<?xml version')===false)
			{
				$videodata['description'].='Cannot get Live Status';
				return $videodata;
			}
			$doc = new DOMDocument;
			$doc->loadXML($htmlcode);
			
			$isLive=$doc->getElementsByTagName("isLive")->item(0)->nodeValue;
			$liveViewers=$doc->getElementsByTagName("liveViewers")->item(0)->nodeValue;
			$liveDuration=$doc->getElementsByTagName("liveDuration")->item(0)->nodeValue;
			
			$videodata['statistics_viewCount']=$liveViewers;
			$videodata['duration']=$liveDuration;
			
			return $videodata;
    
		
		}
		catch(Exception $e)
		{
			//$description='cannot get youtibe video data';
			return 'cannot get youtube video data';
		}
		
		
		
		return array('videosource'=>'own3dtvlive', 'videoid'=>$videoid, 'imageurl'=>$theImage, 'title'=>$theTitle,'description'=>$Description);
	}
	
	public static function renderOwn3DTvLivePlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$border_width=3;
		
		if((int)$options['border']==1 and $options['color1']!='')
		{
			$width=((int)$width)-($border_width*2);
			$height=((int)$height)-($border_width*2);
		}

		
		if($options['playertype']==1)
		{
			//NewPlayer
			$result='<iframe src="http://www.own3d.tv/liveembed/'.$videoidkeyword.'?';
		
			if((int)$options['autoplay']==1)
				$result.='autoPlay=true';
		
			$result.='"';
		
			$result.=''
				.' id="'.$playerid.'"'
				.' width="'.$width.'" height="'.$height.'" frameborder="'.(int)$options['border'].'"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '');
			
			if((int)$options['border']==1 and $options['color1']!='')
				$result.=' style="border: '.$border_width.'px solid #'.$options['color1'].'"';
			
			$result.='></iframe>';
		}
		elseif($options['playertype']==0 or $options['playertype']==3 or $options['playertype']==2 or $options['playertype']==4)
		{
			//Flash AS3.0 Player (detection not availabale)
			$result='<object width="'.$width.'" height="'.$height.'">'
				.'<param name="movie" value="http://www.own3d.tv/livestream/'.$videoidkeyword.';autoplay='.( ((int)$options['autoplay'])==1 ? 'true' : '').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.'<param name="allowfullscreen" value="true" />'
				.'<param name="wmode" value="transparent" />'
				.'<embed src="http://www.own3d.tv/livestream/'.$videoidkeyword.';autoplay=true" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'.$width.'" height="'.$height.'" wmode="transparent"></embed></object>'
			;
		}
			
		
		return $result;
	}
	

}


?>
