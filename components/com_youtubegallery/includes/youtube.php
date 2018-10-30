<?php
/**
 * YoutubeGallery
 * @version 4.4.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
	
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');


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
			if(isset($arr['v']))
				return $arr['v'];
			else
				return '';
		}
		
	}
	
	public static function getVideoData($videoid,$customimage,$customtitle,$customdescription, $thumbnailcssstyle, $getinfomethod)
	{
		//onBehalfOfContentOwner
		
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
		
		$api_key = YouTubeGalleryMisc::getSettingValue('youtube_api_key');
		
		if($api_key!='')
			$answer=VideoSource_YouTube::getYouTubeVideoData_API_v3($videoid,$blankArray, $getinfomethod, $api_key); //Use API v3.0
		else
			$answer=VideoSource_YouTube::getYouTubeVideoData_API_v2($videoid,$blankArray, $getinfomethod);
			
		
			
			
		
		if($answer!='')
		{
			$blankArray['title']='***Video not found*** ('.YouTubeGalleryMisc::html2txt($answer).')';
			$blankArray['description']=YouTubeGalleryMisc::html2txt($answer);
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
	
	
	protected static function getYouTubeVideoData_API_v3($videoid, &$blankArray, $getinfomethod, $api_key)
	{
			
		
		
		if(phpversion()<5)
			return "Update to PHP 5+";
				
		try{
			
			
			$part='id,snippet,contentDetails,statistics';//,status
			$url = 'https://www.googleapis.com/youtube/v3/videos?id='.$videoid.'&part='.$part.'&key='.$api_key;
			
			$blankArray['datalink']=$url;
			
			$htmlcode=YouTubeGalleryMisc::getURLData($url);
			
			
			if(($getinfomethod=='js' or $getinfomethod=='jsmanual' ) and $htmlcode=='')
				return '';
			
			
			$j=json_decode($htmlcode);
			
			
			
			if(!$j)
			{
				return 'Connection Error';
			}
			
			$items=$j->items;
			foreach($items as $item)
			{
				if($item->kind=='youtube#video' and $item->id==$videoid)
				{
					$snippet=$item->snippet;

					
					$blankArray['title']=$snippet->title;
					$blankArray['description']=$snippet->description;
					$blankArray['publisheddate']=$snippet->publishedAt;
					
					$t=$snippet->thumbnails;
					
					$images=array();
					
					if(isset($t->default))
						$images[]=$t->default->url;
						
					if(isset($t->medium))
						$images[]=$t->medium->url;
						
					if(isset($t->high))
						$images[]=$t->high->url;
						
					if(isset($t->standard))
						$images[]=$t->standard->url;
						
					if(isset($t->maxres))
						$images[]=$t->maxres->url;
					
					$blankArray['imageurl']=implode(',',$images);
					
					$blankArray['channel_title']=$snippet->channelTitle;
					
					$d=$item->contentDetails->duration;
					$blankArray['duration']=VideoSource_YouTube::convert_duration($d);
					
					
					
					$blankArray['statistics_favoriteCount']=$item->statistics->favoriteCount;
					$blankArray['statistics_viewCount']=$item->statistics->viewCount;
			
					$blankArray['likes']=$item->statistics->likeCount;
					$blankArray['dislikes']=$item->statistics->dislikeCount;

					$blankArray['commentcount']=$item->statistics->commentCount;
			
					if(isset($snippet->tags))
						$blankArray['keywords']=$snippet->tags;
						
					return '';
				}
			}
			
	

			
		}
		catch(Exception $e)
		{
			return 'Cannot get youtube video data.';
		}
		
		return '';
	}
	
	protected static function convert_duration($youtube_time)
	{
		$parts=null;
		preg_match_all('/(\d+)/',$youtube_time,$parts);

		$hours = floor($parts[0][0]/60);
		$minutes = $parts[0][0]%60;
		if(isset($parts[0][1]))
			$seconds = $parts[0][1];
		else
			$seconds=0;
		
		return $seconds+$minutes*60+$hours*3600;
	}
	
	protected static function getYouTubeVideoData_API_v2($videoid, &$blankArray, $getinfomethod)
	{
		if(phpversion()<5)
			return "Update to PHP 5+";
				
		try{
			


			$url = 'http://gdata.youtube.com/feeds/api/videos/'.$videoid.'?v=2'; //v=2to get likes and dislikes
			
			$blankArray['datalink']=$url;
			
			
			/*
			if($getinfomethod=='js' or $getinfomethod=='jsmanual')
			{
				$rd=YouTubeGalleryMisc::getRawData($videoid);
				if($rd=='')
				{
					YouTubeGalleryMisc::setDelayedRequest($videoid,$url);
					return '';
				}
				elseif($rd=='' or $rd=='*youtubegallery_request*')
					return '';
				else $htmlcode=$rd;
			}
			else
			*/
			
			$htmlcode=YouTubeGalleryMisc::getURLData($url);
			
			if(($getinfomethod=='js' or $getinfomethod=='jsmanual' ) and $htmlcode=='')
				return '';
			
			
			//	return 'Get info method not set, go to Settings.';

			if(strpos($htmlcode,'<?xml version')===false)
			{
				if(strpos($htmlcode,'Invalid id')===false)
					return substr($htmlcode,0,30);
				else
					return 'Invalid id';

				//return $pair;
			}
			else
			{
				if(strpos($htmlcode, '<code>too_many_recent_calls</code>')!==false)
					return 'Youtube API Key needed';
			}
			
			$doc = new DOMDocument;
			$doc->loadXML($htmlcode);
			
			if(!isset($doc->getElementsByTagName("title")->item(0)->nodeValue))
			{
				return 'Youtube 2 Video "'.$videoid.'" not found.';
			}
			
			$blankArray['title']=$doc->getElementsByTagName("title")->item(0)->nodeValue;
			$blankArray['description']=$doc->getElementsByTagName("description")->item(0)->nodeValue;
			$blankArray['publisheddate']=$doc->getElementsByTagName("published")->item(0)->nodeValue;
			
			if($doc->getElementsByTagName("duration"))
			{
				if($doc->getElementsByTagName("duration")->item(0))
					$blankArray['duration']=$doc->getElementsByTagName("duration")->item(0)->getAttribute("seconds");	
			}
			
			$MediaElement=$doc->getElementsByTagName("thumbnail");
			if($MediaElement->length>0)
			{
				$images=array();
				foreach($MediaElement as $me)
					$images[]=$me->getAttribute("url");
					
				$blankArray['imageurl']=implode(',',$images);
			}
			
			
			$FeedElement=$doc->getElementsByTagName("feedLink");
			if($FeedElement->length>0)
			{
				$fe0=$FeedElement->item(0);
				$blankArray['commentcount']=$fe0->getAttribute("countHint");
			}
			
			$RatingElement=$doc->getElementsByTagName("rating");
			if($RatingElement->length>0)
			{
				
				
				$re0=$RatingElement->item(0);
				$blankArray['rating_average']=$re0->getAttribute("average");
				$blankArray['rating_max']=$re0->getAttribute("max");
				$blankArray['rating_min']=$re0->getAttribute("min");
				$blankArray['rating_numRaters']=$re0->getAttribute("numRaters");
				
				
				
				if($RatingElement->length>1)
				{
					$re1=$RatingElement->item(1);

				
					$blankArray['likes']=$re1->getAttribute("numLikes");
					$blankArray['dislikes']=$re1->getAttribute("numDislikes");
				}
				else
				{
					$blankArray['likes']=0;
					$blankArray['dislikes']=0;
				}
			}
			
			$StatElement=$doc->getElementsByTagName("statistics");
			if($StatElement->length>0)
			{
				$se0=$StatElement->item(0);
				$blankArray['statistics_favoriteCount']=$se0->getAttribute("favoriteCount");
				$blankArray['statistics_viewCount']=$se0->getAttribute("viewCount");
			}	

			$blankArray['keywords']=$doc->getElementsByTagName("keywords")->item(0)->nodeValue;
		}
		catch(Exception $e)
		{
			return 'Cannot get youtube video data.';
		}
		
	
		return '';
	}
	


	
	public static function renderYouTubePlayer($options, $width, $height, &$videolist_row, &$theme_row,$startsecond,$endsecond)
	{
		
		$videoidkeyword='****youtubegallery-video-id****';
		
		VideoSource_YouTube::ygPlayerTypeController($options, $theme_row);
		
		$playerapiid='ygplayerapiid_'.$videolist_row->id;
		$playerid='youtubegalleryplayerid_'.$videolist_row->id;
		
		$settings=VideoSource_YouTube::ygPlayerPrepareSettings($options, $theme_row,$playerapiid,$startsecond,$endsecond);
		
		$initial_volume=(int)$theme_row->volume;
		
		$playlist='';
		$full_playlist='';
		$youtubeparams=$options['youtubeparams'];
		$p=explode(';',$youtubeparams);
		
		
		if($options['allowplaylist']==1)
		{
			foreach($p as $v)
			{
				$pair=explode('=',$v);
				if($pair[0]=='playlist')
					$playlist=$pair[1];
				
				if($pair[0]=='fullplaylist')
					$full_playlist=$pair[1];	
					
			}
		}
	
		if($options['allowplaylist']!=1 or $options['playertype']==5 or $options['playertype']==2)
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
			
		
		
		
		
		if($theme_row->nocookie)
			$youtubeserver=$http.'www.youtube-nocookie.com/';
		else
			$youtubeserver=$http.'www.youtube.com/';
		
		$result='';
		
		switch($options['playertype'])
		{
			case 1:	 //new HTML 5 player		
				$result=VideoSource_YouTube::ygHTML5Player($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$videolist_row->id,$playerid,$theme_row->responsive);
				break;
		
			case 5: //new HTML 5 player API
				$result=VideoSource_YouTube::ygHTML5PlayerAPI($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$videolist_row->id,$playerid,$theme_row,$full_playlist,$initial_volume,$playerapiid,false);
				break;
		
			case 0: //Flash AS3.0 Player
				$result=VideoSource_YouTube::ygFlashPlayerWithoutDetection($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$videolist_row->id,$playerid,$theme_row->responsive,$playlist);
				break;
			
			case 2: //Flash Player with detection v.3 and v.2, run Iframe Player if no Flash found.
			
				$result=VideoSource_YouTube::ygHTML5PlayerAPI($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$videolist_row->id,$playerid,$theme_row,$full_playlist,$initial_volume,$playerapiid,true);
				$result.=VideoSource_YouTube::ygFlashPlayerWithDetection($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$videolist_row->id,$playerid,$theme_row,$full_playlist,$initial_volume,$http,$playerapiid,$startsecond,$endsecond);
				break;
		}
		return $result;
	}
	
	protected static function ygPlayerPrepareSettings(&$options, &$theme_row, $playerapiid,$startsecond,$endsecond)
	{
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
		//--------------
		if($options['playertype']!=2)
		{
			$settings[]=array('start',((int)$startsecond));
			$settings[]=array('end',((int)$endsecond));
		}
		
		
		if($options['playertype']==2)
		{
			//Player with Flash availability check
			$settings[]=array('playerapiid','ygplayerapiid_'.$playerapiid);
			$settings[]=array('enablejsapi','1');
		}
		
		
		return $settings;
	}
	
	protected static function ygPlayerTypeController(&$options, &$theme_row)
	{
		$initial_volume=(int)$theme_row->volume;
		
		
		if($options['playertype']==100) //auto
			$options['playertype']=2; //Flash with API by default
			
		//Change Flash 2 to 3
		elseif($options['playertype']==4)//Flash Version 2 is depricated (api)
			$options['playertype']=2;//Flash Version 3 (api)
		elseif($options['playertype']==3)//Flash Version 2 is depricated
			$options['playertype']=0;//Flash Version 3
			
		
		//Change to HTML5 if for Apple
		if($options['playertype']==0)
		{
			if(YouTubeGalleryMisc::check_user_agent_for_apple())
				$options['playertype']=1; //Flash Player not supported use IFrame Instead
		}
		
		//Change to HTML5 API if for Apple
		if($options['playertype']==2)
		{
			if(YouTubeGalleryMisc::check_user_agent_for_apple())
				$options['playertype']=5; //Flash Player not supported use IFrame API Instead
		}
		
		//Change to API if needed
		if($options['playertype']==0)
		{
			//Note - not available for IE
			if(($theme_row->muteonplay or $initial_volume!=-1) and $options['playertype']!=5)
					$options['playertype']=2; //because other types of player doesn't support this functionality.
		}
		
		//Change to API if needed	
		if($options['playertype']==1) 
		{
			//Note - not available for IE
			if(($theme_row->muteonplay or $initial_volume!=-1) and $options['playertype']!=5)
					$options['playertype']=5; //because other types of player doesn't support this functionality.
		}
		
		//Disable API for IE (Flash)
		if($options['playertype']==2)
		{
			if(YouTubeGalleryMisc::check_user_agent_for_ie())
				$options['playertype']=0; //Disable API for IE (so sad!)
		}
		
		
		//Disable API for IE (IFrame)
		if($options['playertype']==5)
		{
			if(YouTubeGalleryMisc::check_user_agent_for_ie())
				$options['playertype']=1; //Disable API for IE (so sad!)
		}

	}
	
	protected static function ygHTML5Player($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$videolist_row_id,$playerid,$theme_row_responsive)
	{
					//new player
			$result='<iframe width="'.$width.'" height="'.$height.'"'
				.' src="'.$youtubeserver.'embed/'.$videoidkeyword.'?'.$settingline.'"'
				.' frameborder="'.(int)$options['border'].'"'
				.' id="'.$playerid.'"'
				.($theme_row_responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$videolist_row_id.'();"' : '')
				.($options['fullscreen']==0 ? '' : ' allowfullscreen')
				.'>'
			.'</iframe>';
			
			return $result;
	}
	
	protected static function ygHTML5PlayerAPI($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$vlid,$playerid,&$theme_row,&$full_playlist,$initial_volume,$playerapiid,$withFlash=false)
	{
			$showHeadScript=false;
			
			// IFrame API Player
			
			$result='<div id="'.$playerapiid.'api"></div>';
			if($options['videoid']!='****youtubegallery-video-id****')
			{
				if(!$withFlash)
				{
					$result.='
			<script type="text/javascript">
			//<![CDATA[
				ygCurrentVideoID'.$vlid.'="'.$options['videoid'].'";
				youtubegallery_updateplayer_youtube_'.$vlid.'("'.$options['videoid'].'",false);
			//]]>
			</script>
			';
				}
				
				$showHeadScript=true;
			}
			else
				$result.='<!--DYNAMIC PLAYER-->';
				
				
			$showHeadScript=true;
				
			if($showHeadScript)
				$result.=VideoSource_YouTube::ygHTML5PlayerAPIHead($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$vlid,$playerid,$theme_row,$full_playlist,$initial_volume,$playerapiid,$withFlash);
			
			/*
			if($showHeadScript)
			{
			$result.='
			<script>
				ygCurrentVideoID'.$vlid.'="'.$options['videoid'].'";
			</script>
			';
			}
			*/
			
			
			return $result;
	}
	protected static function ygHTML5PlayerAPIHead($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$vlid,$playerid,&$theme_row,&$full_playlist,$initial_volume,$playerapiid,$withFlash=false)
	{

		
		$AdoptedPlayerVars=str_replace('&amp;','", "',$settingline);
		$AdoptedPlayerVars='"'.str_replace('=','":"',$AdoptedPlayerVars).'", "enablejsapi":"1"';
		
		if($full_playlist!='')
			$pl='"'.$full_playlist.'".split(",");';
		else
			$pl='new Array;';
		
		$document = JFactory::getDocument();	
		/*
		$result_head=<<<SCRIPTHERE
		var videoStopped$vlid=false;
SCRIPTHERE;
		*/
		
		$result_head='
			var videoStopped'.$vlid.'=false;
			var ygAutoPlay'.$vlid.'='.((int)$options['autoplay']==1 ? 'true' : 'false').';
			var ygPlayList'.$vlid.'='.$pl.'
			var ygCurrentVideoID'.$vlid.'="";
			//var ygIframeApiReady'.$vlid.'=false;
			var ygpv'.$vlid.'={'.$AdoptedPlayerVars.'};
			var ygApiStart'.$vlid.'=ygpv'.$vlid.'["start"];
			var ygApiEnd'.$vlid.'=ygpv'.$vlid.'["end"];
			var ytapi_player'.$vlid.';
			var ygAPIPlayerBodyPartLoaded=false;
		';
		
		//<script></script>
		
		//print_r($document);
		//$document->addCustomTag($result_head);//
		$document->addScriptDeclaration($result_head);//,'text/javascript');
		
		
		$result='
		
			'.($withFlash ? '
			function ygStartAPIPlayer_'.$vlid.'()
			{
			' : '' ).'
				var tag = document.createElement(\'script\');
				tag.src = "https://www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName(\'script\')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			'.($withFlash ? '}' : '' ).'
			
			function onPlayerReady'.$vlid.'(event)
			{
				'.($initial_volume!=-1 ? 'event.target.setVolume('.$initial_volume.');' : '').'
				'.($theme_row->muteonplay ? 'event.target.mute();' : '').'
				if(ygAutoPlay'.$vlid.')
					event.target.playVideo();
			}	

			function ygSetPlayer_'.$vlid.'(videoid)
			{
				ygpv'.$vlid.'["start"]=ygApiStart'.$vlid.';
				ygpv'.$vlid.'["end"]=ygApiEnd'.$vlid.';

				videoStopped'.$vlid.'=false;
				ygCurrentVideoID'.$vlid.'=videoid;
				ytapi_player'.$vlid.' = new YT.Player("'.$playerapiid.'api", {
					width: "'.$width.'",
					id: "'.$playerapiid.'api",
					height: "'.$height.'",
					playerVars: ygpv'.$vlid.',	
					videoId: videoid,
					events: {
						"onReady": onPlayerReady'.$vlid.',
						"onStateChange": onPlayerStateChange'.$vlid.'
					}
				});
			}
			
			function onYouTubeIframeAPIReady()
			{
				ygSetPlayer_'.$vlid.'(ygCurrentVideoID'.$vlid.');
			}
			'.(!$withFlash ? '
			function youtubegallery_updateplayer_youtube_'.$vlid.'(videoid,playVideo)
			{
				//alert("SetPlayer");
				ygAutoPlay'.$vlid.'=playVideo;
				ygSetPlayer_'.$vlid.'(videoid);
			}
			' : '').'
			
			function ygFindNextVideo'.$vlid.'()
			{
				var d=0;
				var v=ygCurrentVideoID'.$vlid.';
				for(i=0;ygPlayList'.$vlid.'.length;i++)
				{
					var g=ygPlayList'.$vlid.'[i].split("*");
					if(g[0]==v)
					{
						
						if(i<ygPlayList'.$vlid.'.length-1)
							d=i+1;
							
						break;
					}
				}
				var g=ygPlayList'.$vlid.'[d].split("*");
				videoid=g[0];
				ygAutoPlay'.$vlid.'=true;
				YoutubeGalleryHotVideoSwitch'.$vlid.'(videoid,"youtube",g[1]);
			}
			
			function onPlayerStateChange'.$vlid.'(event)
			{
				';
				/*
				if($endsecond!=0)
				{
				$result_head.='
				if (event.data == YT.PlayerState.PLAYING && !videoStopped'.$vlid.')
				{
					//setTimeout(stopVideo'.$vlid.', '.(($endsecond-$startsecond)*1000+10).');
					setTimeout(stopVideo'.$vlid.', 4000);
					videoStopped'.$vlid.' = true;
					PlayNext=true;
				}
				';
				}
				*/
				if($full_playlist!='')
				{
					$result.='
				if (event.data == YT.PlayerState.ENDED)
				{
					setTimeout(ygFindNextVideo'.$vlid.', 500);
					
				}
				';
				}
				$result.='
			}
			';
			/*
			if($endsecond!=0)
			{
				$result_head.='
			function stopVideo'.$vlid.'() {
				ytapi_player'.$vlid.'.stopVideo();
				';
				if($full_playlist!='')
				{
					$result_head.='
					setTimeout(ygFindNextVideo'.$vlid.', 500);
';
				}
				$result_head.='
			}
			';
			}
			*/
			$result.='
			
				ygCurrentVideoID'.$vlid.'="'.$options['videoid'].'";
				ygAPIPlayerBodyPartLoaded=true;
			
		';
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($result,'text/javascript');

		return '';
		
	}
	
	
	protected static function ygFlashPlayerWithDetection($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$vlid,$playerid,&$theme_row,&$full_playlist,$initial_volume,$http,$playerapiid,$startsecond,$endsecond)
	{
			$showHeadScript=false;
			$result='<div id="'.$playerapiid.'"></div>';
			
			if($options['videoid']!='****youtubegallery-video-id****')
			{
				$result.='
			<script type="text/javascript">
			//<![CDATA[
				ygCurrentVideoID'.$vlid.'="'.$options['videoid'].'";
				youtubegallery_updateplayer_youtube_'.$vlid.'("'.$options['videoid'].'",false);
			//]]>
			</script>
			';
				$showHeadScript=true;
			}
			else
				$result.='<!--DYNAMIC PLAYER-->';
		
			$showHeadScript=true;
	
		
			if($showHeadScript)
				VideoSource_YouTube::ygFlashPlayerWithDetectionHead($width,$height,$youtubeserver,$videoidkeyword,$settingline,$options,$vlid,$playerid,$theme_row,$full_playlist,$initial_volume,$http,$playerapiid,$startsecond,$endsecond);
	
			return $result;
	}
	
	protected static function ygFlashPlayerWithDetectionHead($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$vlid,$playerid,&$theme_row,&$full_playlist,$initial_volume,$http,$playerapiid,$startsecond,$endsecond)
	{
		$pVersion=($options['playertype']==2 ? '3': '2');
			
			//t type="text/javascript"//<![CDATA[
			if($initial_volume>100)
				$initial_volume=100;
			if($initial_volume<-1)
				$initial_volume=-1;
	
			$result_head='
			<!-- Youtube Gallery - Youtube Flash Player With Detection -->
			<script src="'.$http.'www.google.com/jsapi" type="text/javascript"></script>
			<script src="'.$http.'ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" type="text/javascript"></script>
			<script>
			
				var ygFlashNotFound'.$vlid.'=false;
				var ygpvFlash'.$vlid.'="'.$settingline.'";
				ygApiStart'.$vlid.'="'.((int)$startsecond).'";
				ygApiEnd'.$vlid.'="'.((int)$endsecond).'";
				var ytplayer;
				google.load("swfobject", "2.2");
				
				
				function yg_flash_runIframePlayerIfReady'.$vlid.'()
				{
					if(ygAPIPlayerBodyPartLoaded)
						ygStartAPIPlayer_'.$vlid.'();
					else
						setTimeout(runIframePlayerIfReady'.$vlid.', 500);
				}
				
				function onYouTubePlayerReady()
				{
					ytplayer = document.getElementById("'.$playerid.'");
					ytplayer.addEventListener("onStateChange", "yg_onPlayerStateChange_flash'.$vlid.'");
					YGYouTubePlayerReady'.$vlid.'('.($theme_row->autoplay ? 'true' : 'false').');
				}
				
				function yg_onPlayerStateChange_flash'.$vlid.'(newState)
				{
					';
					if($full_playlist!='')
					{
						$result_head.='
					if (newState == 0)
					{
						setTimeout(ygFindNextVideo'.$vlid.', 500);
					}
					';
					}
					$result_head.='
				}
				
				function YGYouTubePlayerReady'.$vlid.'(playVideo)
				{
					ytplayer = document.getElementById("'.$playerid.'");
					'.($theme_row->muteonplay ? 'ytplayer.mute();' : '').'
					'.(
					   $initial_volume!=-1
					   ?
					'
					setTimeout("changeVolumeAndPlay'.$vlid.'("+playVideo+")", 750);'
					   :
					'
					if(playVideo)
						ytplayer.playVideo();
					'
					).'
				}

				'.($initial_volume!=-1 ? '
				function changeVolumeAndPlay'.$vlid.'(playVideo)
				{
					var ytplayer = document.getElementById("'.$playerid.'");
					if(ytplayer)
					{
						ytplayer.setVolume('.$initial_volume.');
				        
						if(playVideo)
							ytplayer.playVideo();
					  
					}
				}   
				' : '').'
				
				function youtubegallery_updateplayer_youtube_'.$vlid.'(videoid,playVideo)
				{
					if(ygFlashNotFound'.$vlid.')
					{
						ygAutoPlay'.$vlid.'=playVideo;
						ygSetPlayer_'.$vlid.'(videoid);
						return;	
					}
					
					
					ygCurrentVideoID'.$vlid.'=videoid;
					var playerVersion = swfobject.getFlashPlayerVersion();
					if (playerVersion.major>0)
					{
						var params = { allowScriptAccess: "always", wmode: "transparent"'.($options['fullscreen'] ? ', allowFullScreen: "true"' : '').' };
						var atts = { id: "'.$playerid.'" '
						.' };
						
						var playerLink="'.$youtubeserver.'v/"+videoid+"?version=3&amp;"+ygpvFlash'.$vlid.'+"&amp;start="+ygApiStart'.$vlid.'+"&amp;end="+ygApiEnd'.$vlid.';
						
						if(playVideo)
							playerLink=playerLink.replace("autoplay=0","autoplay=1");
							
						swfobject.embedSWF(playerLink,"'.$playerapiid.'", "'.$width.'", "'.$height.'", "8", null, null, params, atts);
						
					}
					else
					{
						ygFlashNotFound'.$vlid.'=true;
						// run Iframe player instead
						yg_flash_runIframePlayerIfReady'.$vlid.'();
					}	
					
				}
			
			</script>
			<!-- end of Youtube Gallery - Youtube Flash Player With Detection -->
			';
////]]>
			$document = JFactory::getDocument();
			
			$document->addCustomTag($result_head);
			
			
	}
	
	protected static function ygFlashPlayerWithoutDetection($width,$height,$youtubeserver,$videoidkeyword,$settingline,&$options,$vlid,$playerid,$theme_row_responsive,$playlist)
	{
		//Old player
			$pVersion=($options['playertype']==0 ? '3': '2');
			$result='<object '
				.' id="'.$playerid.'"'
				.' width="'.$width.'"'
				.' height="'.$height.'"'
				.' data="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'"'
				.' type="application/x-shockwave-flash"'
				.($theme_row_responsive==1 ? ' onLoad="YoutubeGalleryAutoResizePlayer'.$vlid.'();"' : '').'>'
				.'<param name="id" value="'.$playerid.'" />'
				.'<param name="movie" value="'.$youtubeserver.'v/'.$videoidkeyword.'?version='.$pVersion.'&amp;'.$settingline.'" />'
				.'<param name="wmode" value="transparent" />'
				.'<param name="allowFullScreen" value="'.($options['fullscreen'] ? 'true' : 'false').'" />'
				.'<param name="allowscriptaccess" value="always" />'
				.($playlist!='' ? '<param name="playlist" value="'.$playlist.'" />' : '');
			$result.='</object>';
			
			return $result;
	}
	
	
	
}


?>