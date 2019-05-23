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

class VideoSource_Yahoo
{
	
	
	public static function extractYahooID($theLink)
	{
		//http://animalvideos.yahoo.com/?vid=25433859&lid=24721185
		
		$l=explode('/',$theLink);
		if(count($l)>5)
			return $l[4].'*'.$l[5];
		
		
		return '';
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription, $theLink)
	{
		
		
		echo '<!-- ';
		$theTitle='';
		$Description='';
		$theImage='';
						
		
		
		$XML_SOURCE=YouTubeGalleryMisc::getURLData('http://video.yahoo.com/services/oembed?url='.$theLink);
		
					

		if($customimage=='')
		{
			$p1=strpos($XML_SOURCE,'thumbUrl=');
			if($p1>0)
			{
				$p2=strpos($XML_SOURCE,'.jpg',$p1);
				$theImage=substr($XML_SOURCE,$p1+9,$p2-$p1-9+4);
				$theImage=str_replace('\\','',$theImage);
			}
		}
		else
			$theImage=$customimage;
						

		if($customtitle=='')
		{
			if(ini_get('allow_url_fopen'))
			{
				$theTitle='Yahoo Video';
				$p1=strpos($XML_SOURCE,'"title":"');
				if($p1>0)
				{
					$p2=strpos($XML_SOURCE,'"',$p1+9);
					$theTitle=substr($XML_SOURCE,$p1+9,$p2-$p1-9);
					}
				}//if(ini_get('allow_url_fopen'))
		}
		else
			$theTitle=$customtitle;
						
		if($customdescription=='')
		{
			if(ini_get('allow_url_fopen'))
			{
				$Description='Yahoo Video';
				$p1=strpos($XML_SOURCE,'"description":"');
				if($p1>0)
				{
					$p2=strpos($XML_SOURCE,'"',$p1+9);
					$Description=substr($XML_SOURCE,$p1+9,$p2-$p1-9);
				}
			}//if(ini_get('allow_url_fopen'))
		}
		else
			$Description=$customdescription;
						
		
		echo ' -->';
													
		return array(
			'videosource'=>'yahoo',
			'videoid'=>$videoid,
			'imageurl'=>$theImage,
			'title'=>$theTitle,
			'description'=>$Description
			
		);	
	}
	

	public static function renderYahooPlayer($options, $width, $height, &$videolist_row, &$theme_row)
	{
		$videoidkeyword='****youtubegallery-video-id****';
		
		return '<p>Not supported in this version due to Yahoo service changes.</p>';
		
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;	

		$idpair=explode('*',$videoidkeyword);
		
		$image=str_replace(':','%3A',$options['thumbnail']);
		
		$result='<object width="'.$width.'" height="'.$height.'">'
			.'<param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=6" />'
			.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
			.'<param name="AllowScriptAccess" VALUE="always" />'
			.'<param name="bgcolor" value="'.($options['color1']!='' ? '#'.$options['color1'] : '#000000' ).'" />'
			.'<param name="flashVars" '
				.'value="id='.$idpair[1].'&'
				.'vid='.$idpair[0].'&lang=en-us&'
				.'intl=us&'
				.'thumbUrl='.$image.'&'
				.'embed=1&'
				.($options['autoplay'] ? 'autoPlay=true&' : '')
				.'ap=20683543'
				.'"'
			.'/>'
			
			.'<embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=6" '
			.'type="application/x-shockwave-flash" '
			.'width="'.$width.'" '
			.'height="'.$height.'" '
			.'allowFullScreen="'.($options['fullscreen'] ? 'true' : 'false').'" '
			.'AllowScriptAccess="always" '
			.'bgcolor="'.($options['color1']!='' ? '#'.$options['color1'] : '#000000' ).'" '
			.'flashVars='
			.'"id='.$idpair[1].'&'
				.'vid='.$idpair[0].'&'
				.'lang=en-us&'
				.'intl=us&'
				.'thumbUrl='.$image.'&'
				.'embed=1&'
				.($options['autoplay'] ? 'autoPlay=true&' : '')
				.'ap=20683543"'
			.'>'
			.'</embed></object>';
			

		return $result;
	}	
}