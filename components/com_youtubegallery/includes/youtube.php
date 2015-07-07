<?php
/**
 * YoutubeGallery
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
	
require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');


class VideoSource_YouTube
{
	public static function extractYouTubeID($youtubeURL)
	{
		if(!(strpos($youtubeURL,'://youtu.be')===false) or !(strpos($youtubeURL,'://www.youtu.be')===false))
		{
			//youtu.be
			$list=explode('/',$youtubeURL);
			if(isset($list[3]))
				return $list[3];
			else
				return '';
		}
		else
		{
			//youtube.com
			$arr=YouTubeGalleryMisc::parse_query($youtubeURL);
			return $arr['v'];	
		}
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription, $thumbnailcssstyle)
	{
		//blank	array
		$blankArray=array(
				'videosource'=>'youtube',
				'videoid'=>$videoid,
				'imageurl'=>'',
				'title'=>'',
				'description'=>'',
				'publisheddate'=>'',
				'duration'=>0,
				'rating_average'=>0,
				'rating_max'=>0,
				'rating_min'=>0,
				'rating_numRaters'=>0,
				'statistics_favoriteCount'=>0,
				'statistics_viewCount'=>0,
				'keywords'=>'',
				'likes'=>0,
				'dislikes'=>'',
				'commentcount'=>'',
				'channel_username'=>'',
				'channel_title'=>'',
				'channel_subscribers'=>0,
				'channel_subscribed'=>0,
				'channel_location'=>'',
				'channel_commentcount'=>0,
				'channel_viewcount'=>0,
				'channel_videocount'=>0,
				'channel_description'=>''
				);
		
		$answer=VideoSource_YouTube::getYouTubeVideoData($videoid,$blankArray);
		
		if($answer!='')
		{
			$blankArray['title']='***Video not found***';
			$blankArray['description']=$answer;
			return $blankArray;
		}
		
		if($customtitle!='')
			$blankArray['title']=$customtitle;

		if($customdescription!='')
			$blankArray['description']=$customdescription;
		
		if($customimage!='' and strpos($customimage, '#')===false)
		{
			$blankArray['imageurl']=$customimage;
		}
		else
		{
			if($blankArray['imageurl']=='')
				$blankArray['imageurl']=VideoSource_YouTube::getYouTubeImageURL($videoid,$thumbnailcssstyle);
			
		}
	
		return $blankArray;
	}
	
	public static function getYouTubeImageURL($videoid,$thumbnailcssstyle)
	{
		
		
		if($thumbnailcssstyle == null)
			return 'http://img.youtube.com/vi/'.$videoid.'/default.jpg';
		
		//get bigger image if size of the thumbnail set;
		
		$a=str_replace(' ','',$thumbnailcssstyle);
		if(strpos($a,'width:')===false and strpos($a,'height:')===false)
			return 'http://img.youtube.com/vi/'.$videoid.'/default.jpg';
		else
			return 'http://img.youtube.com/vi/'.$videoid.'/0.jpg';
		
	}
	
	public static function getYouTubeVideoData($videoid, &$blankArray)
	{
		if(phpversion()<5)
			return "Update to PHP 5+";
		
		//
		require_once JPATH_ADMINISTRATOR . '/components/com_youtubegallery/google/_videos.php';
		$youtubeVideos = new YoutubeVideos();
		// var_dump($videoid);
		$video_info = $youtubeVideos->getVideoInfo( $videoid );
		// echo "<pre>";
		// var_dump($video_info['items']);

		if (isset($video_info['items']))
		{
			$video_info = $video_info['items'][0];
		}

		$blankArray['videoid']= $videoid;
		$blankArray['title']= $video_info['snippet']['title'];
		$blankArray['description']= $video_info['snippet']['description'];

		if (isset($video_info['snippet']['publishedAt']))
		{
			$ts = strtotime($video_info['snippet']['publishedAt']);
    		$blankArray['publisheddate']= date("Y-m-d H:i:s", $ts);
		}

		if (isset($video_info['contentDetails']['duration']))
		{
			$date = new DateTime('1970-01-01');
			$date->add(new DateInterval( $video_info['contentDetails']['duration'] ));
			$seconds = $date->format('s');
			$minutes = $date->format('i');
			$hours = $date->format('H');
			$seconds = ($hours * 60 * 60) + ($minutes * 60) + $seconds;
			$blankArray['duration'] = $seconds;
		}

		if (count($video_info['snippet']['thumbnails']) > 0)
		{
			$blankArray['imageurl'] = array();
			foreach ($video_info['snippet']['thumbnails'] as $k => $v)
			{
				$blankArray['imageurl'][] = $v;
			}
			$blankArray['imageurl'] = implode(', ', $blankArray['imageurl']);
		}
	
		return '';
	}
	


	
	public static function renderYouTubePlayer($options, $width, $height, &$videolist_row, &$theme_row,$startsecond,$endsecond)
	{
		
		$videoidkeyword='****youtubegallery-video-id****';
		
		$settings=array();
		
		$settings[]=array('autoplay',(int)$options['autoplay']);
		
		$settings[]=array('hl','en');
		
		
		if($options['fullscreen']!=0)
			$settings[]=array('fs','1');
		else
			$settings[]=array('fs','0');
			
			
		$settings[]=array('showinfo',$options['showinfo']);
		$settings[]=array('iv_load_policy','3');
		$settings[]=array('rel',$options['relatedvideos']);
		$settings[]=array('loop',(int)$options['repeat']);
		$settings[]=array('border',(int)$options['border']);
		
		if($options['color1']!='')
			$settings[]=array('color1',$options['color1']);
			
		if($options['color2']!='')
			$settings[]=array('color2',$options['color2']);

		if($options['controls']!='')
		{
			$settings[]=array('controls',$options['controls']);
			if($options['controls']==0)
				$settings[]=array('version',3);
			
		}
		if($startsecond!='')
			$settings[]=array('start',$startsecond);
			
		if($endsecond!='')
			$settings[]=array('end',$endsecond);
		
		if($theme_row->muteonplay and $options['playertype']!=5)
			$options['playertype']=2; //becouse other types of player doesn't support this functionality.
		
		$playerapiid='ygplayerapiid_'.$videolist_row->id;
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		if($options['playertype']==2)
		{
			//Player with Flash availability check
			$settings[]=array('playerapiid','ygplayerapiid_'.$playerapiid);
			$settings[]=array('enablejsapi','1');
		}
		
		$playlist='';
		$youtubeparams=$options['youtubeparams'];
		$p=explode(';',$youtubeparams);
		
		
		if($options['allowplaylist']==1)
		{
			foreach($p as $v)
			{
				$pair=explode('=',$v);
				if($pair[0]=='playlist')
					$playlist=$pair[1];
			}
		}
		else
		{
			$p_new=array();
			foreach($p as $v)
			{
				$pair=explode('=',$v);
				if($pair[0]!='playlist')
					$p_new[]=$v;
			}
			$youtubeparams=implode(';',$p_new);
		}
		
		
	
		YouTubeGalleryMisc::ApplyPlayerParameters($settings,$youtubeparams);
		
		$settingline=YouTubeGalleryMisc::CreateParamLine($settings);
		
		
		
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
			$http='https://';
		else
			$http='http://';
			
		$result='';
		
		$initial_volume=(int)$theme_row->volume;
		
		if($theme_row->nocookie)
			$youtubeserver=$http.'www.youtube-nocookie.com/';
		else
			$youtubeserver=$http.'www.youtube.com/';
		
		
		//echo '$options[playertype]='.$options['playertype'].'<br/>';
		
		if($options['playertype']==1) //new HTML 5 player
		{
			//new player
			$result.='<iframe width="'.$width.'" height="'.$height.'"'
				.' src="'.$youtubeserver.'embed/'.$videoidkeyword.'?'.$settingline.'"'
				.' frameborder="'.(int)$options['border'].'"'
				.' id="'.$playerid.'"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '')
				.($options['fullscreen']==0 ? '' : ' allowfullscreen')
				.'>'
			.'</iframe>';
		}
		elseif($options['playertype']==5) //new HTML 5 player
		{
			// IFrame API Player
			$result.='
			
			<div id="'.$playerapiid.'"></div>
		';
		
		$AdoptedPlayerVars=str_replace('&amp;','", "',$settingline);
		$AdoptedPlayerVars='"'.str_replace('=','":"',$AdoptedPlayerVars).'", "enablejsapi":"1"';
		
		
		
			/*
			events: {
					\'onReady\': \'onPlayerReady'.$videolist_row->id.'\',
					\'onStateChange\': \'onPlayerStateChange'.$videolist_row->id.'\'
				}
			*/
		$result_head='
		
		
		<script>
					var tag = document.createElement(\'script\');
			tag.src = "//www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName(\'script\')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	
			function onPlayerReady'.$videolist_row->id.'(event)
			{
				'.($initial_volume!=-1 ? 'event.target.setVolume('.$initial_volume.');' : '').'
				'.($theme_row->muteonplay ? 'event.target.mute();' : '').'
			}	
			';
			/*
			function onPlayerStateChange'.$videolist_row->id.'(event) {
				alert("State changed");
				//if (event.data == YT.PlayerState.PLAYING && !done) {
					//setTimeout(stopVideo, 6000);
					//done = true;
				//}
				//setTimeout("ytapi_player'.$videolist_row->id.'.addEventListener(\'onStateChange\', onPlayerStateChange'.$videolist_row->id.')", 1000);
			}
			*/
			$result_head.='
			var ytapi_player'.$videolist_row->id.';
		
			function onYouTubeIframeAPIReady()
			{
				ytapi_player'.$videolist_row->id.' = new YT.Player("'.$playerapiid.'", {
					width: "'.$width.'",
					id: "abrakadabra",
					height: "'.$height.'",
					playerVars: {'.$AdoptedPlayerVars.'},	
					videoId: "'.$options['videoid'].'",
				});
				
				setTimeout("ytapi_player'.$videolist_row->id.'.addEventListener(\'onReady\', \'onPlayerReady'.$videolist_row->id.'\')", 500);
			}
			
			
		
			</script>
		';
		
			$result.=$result_head;
		/*
			if($options['videoid']!='****youtubegallery-video-id****')
			{
				$document = JFactory::getDocument();
				$document->addCustomTag($result_head);
			}
			*/
		
		}
		elseif($options['playertype']==0 or $options['playertype']==3) //Flash AS3.0 Player
		{
			//Old player
			$pVersion=($options['playertype']==0 ? '3': '2');
			$result.='<object '
				.' id="'.$playerid.'"'
				.' width="'.$width.'"'
				.' height="'.$height.'"'
				.' data="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'"'
				.' type="application/x-shockwave-flash"'
				.($theme_row->responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'();"' : '').'>'
				.'<param name="id" value="'.$playerid.'" />'
				.'<param name="movie" value="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'" />'
				.'<param name="wmode" value="transparent" />'
				.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.($playlist!='' ? '<param name="playlist" value="'.$playlist.'" />' : '');
			$result.='</object>';
		}
		elseif($options['playertype']==2 or $options['playertype']==4) //Flash Player with detection 3 and 2
		{
			$pVersion=($options['playertype']==2 ? '3': '2');
			
			
			$alternativecode='You need Flash player 8+ and JavaScript enabled to view this video.';
			
			if($initial_volume>100)
				$initial_volume=100;
			if($initial_volume<-1)
				$initial_volume=-1;
	
			//Old player
			/*
			 *'.($theme_row->autoplay ? 'ytplayer.playVideo();' : '
			 * ').'
			 */
			$result_head='
			<!-- Youtube Gallery - Youtube Flash Player With Detection -->
			<script src="'.$http.'www.google.com/jsapi" type="text/javascript"></script>
			<script src="'.$http.'ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js" type="text/javascript"></script>
			<script type="text/javascript">
			//<![CDATA[
				google.load("swfobject", "2");
				function onYouTubePlayerReady(PlayerId)
				{
					YGYouTubePlayerReady'.$videolist_row->id.'('.($theme_row->autoplay ? 'true' : 'false').');
				}
				
				function YGYouTubePlayerReady'.$videolist_row->id.'(playVideo)
				{
					//alert("Play");
					ytplayer = document.getElementById("'.$playerid.'");
					'.($theme_row->muteonplay ? 'ytplayer.mute();' : '').'
					'.(
					   $initial_volume!=-1
					   ?
					'
					setTimeout("changeVolumeAndPlay'.$videolist_row->id.'("+playVideo+")", 750);'
					   :
					'
					if(playVideo)
						ytplayer.playVideo();
					'
					).'
				}
				
				'.($initial_volume!=-1 ? '
				function changeVolumeAndPlay'.$videolist_row->id.'(playVideo)
				{
					ytplayer = document.getElementById("'.$playerid.'");
					if(ytplayer)
					{
						ytplayer.setVolume('.$initial_volume.');
				        
						if(playVideo)
							ytplayer.playVideo();
					  
					}
				}   
				' : '').'
				
				function youtubegallery_updateplayer_youtube_'.$videolist_row->id.'(videoid,playVideo)
				{
					var playerVersion = swfobject.getFlashPlayerVersion();
					if (playerVersion.major>0)
					{
						var params = { allowScriptAccess: "always", wmode: "transparent"'.($options['fullscreen'] ? ', allowFullScreen: "true"' : '').' };
						var atts = { id: "'.$playerid.'" };
						var playerLink="'.$youtubeserver.'v/"+videoid+"?version='.$pVersion.'&amp;'.$settingline.'";
						
						if(playVideo)
							playerLink=playerLink.replace("autoplay=0","autoplay=1");
							
						swfobject.embedSWF(playerLink,"'.$playerapiid.'", "'.$width.'", "'.$height.'", "8", null, null, params, atts);
					}
					else
						document.getElementById("YoutubeGallerySecondaryContainer'.$videolist_row->id.'").innerHTML="'.$alternativecode.'";
					
					
				}
			//]]>
			</script>
			<!-- end of Youtube Gallery - Youtube Flash Player With Detection -->
			';

			if($options['videoid']!='****youtubegallery-video-id****')
			{
				$document = JFactory::getDocument();
				$document->addCustomTag($result_head);
			}
			
			$result='<div id="'.$playerapiid.'"></div>';
			
			if($options['videoid']!='****youtubegallery-video-id****')
			{
				$result.='
			<script type="text/javascript">
			//<![CDATA[
				youtubegallery_updateplayer_youtube_'.$videolist_row->id.'("'.$options['videoid'].'",false);
			//]]>
			</script>
			';
			
			}
			else
				$result.='<!--DYNAMIC PLAYER-->';
			
		}

		return $result;
	}
	
	
	
	
}


?>