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
class VideoSource_FLV
{


	public static function extractFLVID($theLink)
	{
		return substr(md5($theLink),2,30);
		
	}
	
	public static function getVideoData($videoid,$theLink_,$customimage,$customtitle,$customdescription)
	{
		//API
		//file.flv
		
		$a=explode('/',str_replace('\\','/',$theLink_)); // to support windows
		if(count($a)>0)
			$FileName=$a[count($a)-1];
		else
			$FileName="FLV File";
		
		$theLink=JPATH_SITE.DIRECTORY_SEPARATOR.$theLink_;

		$theTitle='';
		$Description='';
		$theImage='';
		
		
		
		if(file_exists($theLink))
		{

			if($customimage=='')
				$theImage='flvthumbnail';
			else
				$theImage=$customimage;
		
			if($customtitle=='')
				$theTitle=$FileName;
			else
				$theTitle=$customtitle;
			
			if($customdescription=='')
				$Description=$FileName;
			else
				$Description=$customdescription;
		
		$videodata=array(
				'videosource'=>'.flv',
				'videoid'=>$videoid,
				'imageurl'=>$theImage,
				'title'=>$theTitle,
				'description'=>$Description,
				'publisheddate'=>date('Y-m-d H:i:s',filectime($theLink)),
				'duration'=>VideoSource_FLV::getFLVDuration($theLink),
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
					'videosource'=>'.flv',
					'videoid'=>$videoid,
					'imageurl'=>$theImage,
					'title'=>'Video not found or no connection.',
					'description'=>$Description
					);
		}
		

	}

	static function getFLVDuration($theLink)
	{
		$flv = fopen($theLink, "rb");
		fseek($flv, -4, SEEK_END);
		$arr = unpack('N', fread($flv, 4));
		$last_tag_offset = $arr[1];
		fseek($flv, -($last_tag_offset + 4), SEEK_END);
		fseek($flv, 4, SEEK_CUR);
		$t0 = fread($flv, 3);
		$t1 = fread($flv, 1);
		$arr = unpack('N', $t1 . $t0);
		$milliseconds_duration = $arr[1];
		return (int)($milliseconds_duration/1000);
	}

	static function getThumbnailCode($link, $cssstyle,$aLink,$linkTarget)
	{
		$size=VideoSource_FLV::getThambnailSize($cssstyle);

		$width=$size[0];
		$height=$size[1];
		$videolink="flvthumbnail.php?videofile=".urlencode($link);
		//$videolink="flvthumbnail.php?videofile=".urlencode('phone.flv');
		//$videolink=urlencode('phone.flv');

		$player='components/com_youtubegallery/includes/player_flv_maxi.swf';
		
		$result='<div style="position: relative;width:'.$width.'px;height:'.$height.'px;margin:0px;padding:0px;'.$cssstyle.'" class="YoutubeGalleryFLVThumbs">';
		
		$result.='<div style="position: absolute;background-image: url(\'/components/com_youtubegallery/images/dot.png\');top:0px;left:0px;width:'.$width.'px;height:'.$height.'px;margin:0px;padding:0px;">';
		
		//http://flv-player.net/players/maxi/license/
$result.='<object type="application/x-shockwave-flash" data="'.$player.'" width="'.$width.'" height="'.$height.'" style="margin:0 !important;padding: 0 !important;border:none !important;">
     <param name="movie" value="'.$player.'" />';
     /*
     <param name="autoload" value="1" />
     <param name="loop" value="1" />
     <param name="phpstream" value="0" />
     <param name="showloading" value="never" />
*/
     $p='&amp;autoload=1&amp;showplayer=never&amp;buffermessage=&amp;skin=';
     $p.='&amp;autoplay=1';
     $p.='&amp;loop=1';
     $p.='&amp;margin=0';
     $p.='&amp;onclick='.urlencode($aLink);
     $p.='&amp;onclicktarget='.$linkTarget;
     
     $result.='<param name="FlashVars" value="flv='.$videolink.$p.'" />'
.'</object></div>

<div style="position: absolute;background-image: url(\'components/com_youtubegallery/images/dot.png\');top:0px;left:0px;width:'.$width.'px;height:'.$height.'px;margin:0px;padding:0px;"></div>      
</div>
';

/*




*/
		return $result;

	}
	static function getThambnailSize($cssstyle)
	{
		$size=array(120,90);
		$s=explode(';',$cssstyle);
		$c=0;

		foreach($s as $a)
		{
			$p=explode(':',$a);

			if(count($p)>0)
			{
				$o=strtolower($p[0]);
				if($o=='width')
				{
					$c+=1;
					$size[0]=trim(str_replace('px','',strtolower($p[1])));
				}
			
				if($o=='height')
				{
					$c+=1;
					$size[1]=trim(str_replace('px','',strtolower($p[1])));
				}
			}
			
			if($c==2)
				break;
		}

		
		return $size;
	}

	public static function renderFLVPlayer($options, $width, $height, &$videolist_row, &$theme_row, $videolink)
	{
		//FLV Player
		//$videolinkkeyword='****youtubegallery-video-link****';
		$player='components/com_youtubegallery/includes/player_flv_maxi.swf';
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		
		$title='';
		if(isset($options['title']))
			$title=$options['title'];
			
			
		
		
		
		$settings=array();
		
		$settings[]=array('autoplay',(int)$options['autoplay']);
		
		//$settings[]=array('controls',$options['controls']);
		$settings[]=array('loop',(int)$options['repeat']);
			
		if($options['color1']!='')
			$settings[]=array('bgcolor1',$options['color1']);
			
		if($options['color2']!='')
			$settings[]=array('bgcolor2',$options['color2']);
			
		if($options['controls']!='')
		{
			if($options['controls']==0)
				$settings[]=array('showplayer','never');
			else
				$settings[]=array('showplayer','autohide');
		}
			

		$settings[]=array('showfullscreen',$options['fullscreen']);

		$settings[]=array('showloading','always');
		$settings[]=array('autoload','1');
		$settings[]=array('buffermessage','');
		$settings[]=array('skin','');
		if($theme_row->border)
		{
			
			$settings[]=array('playercolor',$options['color1']);
			$settings[]=array('margin','5');
		}
		else
			$settings[]=array('margin','0');
			
		
		
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$options['youtubeparams']);
		
		$result='';
		//http://flv-player.net/players/maxi/license/
		$result.='<div style=""><object type="application/x-shockwave-flash" id="'.$playerid.'" alt="'.$title.'" data="'.$player.'" width="'.$width.'" height="'.$height.'" '
			.'style="margin:0 !important;padding: 0 !important;border:none !important;"'
			.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
			.'>'
			.'<param name="movie" value="'.$player.'" />';

			$p='';
			foreach($settings as $s)
				$p.='&amp;'.$s[0].'='.$s[1];
          
			$result.='<param name="FlashVars" value="flv=../../../'.$videolink.$p.'" /></object></div>';

		return $result;
	}
}
?>

