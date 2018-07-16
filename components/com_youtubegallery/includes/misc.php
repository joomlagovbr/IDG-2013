<?php
/**
 * YoutubeGallery for Joomla!
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class YouTubeGalleryMisc
{
	var $videolist_row;
	var $theme_row;
	
	function getVideoListTableRow($listid)
	{
		$db = JFactory::getDBO();
	
		//Load Video List
		  
		$query = 'SELECT ';
		$query .= '#__youtubegallery_videolists.id AS id, ';
		$query .= '#__youtubegallery_videolists.listname AS listname, ';
		$query .= '#__youtubegallery_videolists.videolist AS videolist, ';
		$query .= '#__youtubegallery_videolists.catid AS catid, ';
		$query .= '#__youtubegallery_videolists.updateperiod AS updateperiod, ';
		$query .= '#__youtubegallery_videolists.lastplaylistupdate AS lastplaylistupdate, ';
		$query .= '#__youtubegallery_videolists.description AS description, ';
		$query .= '#__youtubegallery_videolists.author AS author, ';
		$query .= '#__youtubegallery_videolists.authorurl AS authorurl, ';
		$query .= '#__youtubegallery_videolists.image AS image, ';
		$query .= '#__youtubegallery_videolists.note AS note, ';
		$query .= '#__youtubegallery_videolists.watchusergroup AS watchusergroup, ';

		$query .= 'COUNT(#__youtubegallery_videos.listid) AS TotalVideos FROM #__youtubegallery_videolists';
		
		$query .= ' LEFT JOIN #__youtubegallery_videos ON #__youtubegallery_videos.listid=#__youtubegallery_videolists.id AND #__youtubegallery_videos.isvideo';
		$query .= ' WHERE #__youtubegallery_videolists.id='.(int)$listid.' ';
		$query .= ' GROUP BY id';
		$query .= ' LIMIT 1';
		
		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());
	
		$videolist_rows = $db->loadObjectList();
		

			
		if(count($videolist_rows)==0)
			return false;//'<p>No video list found</p>';
			
		$this->videolist_row=$videolist_rows[0];
		return true;
	}
	
	function getThemeTableRow($themeid)
	{
		$db = JFactory::getDBO();

		//Load Theme Row
		$query = 'SELECT id, themename, width, height, playvideo, `repeat`, fullscreen, autoplay, related, allowplaylist, showinfo, bgcolor, cols,
		showtitle, cssstyle, navbarstyle, thumbnailstyle, linestyle, showlistname, listnamestyle, showactivevideotitle, activevideotitlestyle,
		description, descr_position, descr_style, color1, color2, border, openinnewwindow, rel, hrefaddon, pagination, customlimit,
		controls, youtubeparams, playertype, useglass, logocover, customlayout,  prepareheadtags, muteonplay,
		volume, orderby, customnavlayout, responsive, mediafolder, readonly, headscript, themedescription, nocookie, changepagetitle
		FROM #__youtubegallery_themes WHERE id='.(int)$themeid.' LIMIT 1';


		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());

		$theme_rows = $db->loadObjectList();
			
		if(count($theme_rows)==0)
			return false;//'<p>No theme found</p>';
			
		$this->theme_row=$theme_rows[0];
		return true;
	}
	
	
	function formVideoList($rawList,&$firstvideo)
	{
		$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
		
		
		$gallery_list=array();
		
		$main_ordering=10000; //10000 step
		
		foreach($rawList as $b)
		{
			$datalink='';
			$playlistid='';
			
			$b=str_replace("\n",'',$b);
			$b=trim(str_replace("\r",'',$b));
			
			$listitem=$this->csv_explode(',', $b, '"', false);
			
			$theLink=trim($listitem[0]);
			
			if(!(strpos($theLink, '/embed/')===false))
			{
				//Convert Embed links to Address bar version
				$theLink=str_replace('www.youtube.com/embed/','youtu.be/',$theLink);
				$theLink=str_replace('youtube.com/embed/','youtu.be/',$theLink);
			}
			
			
			$vsn=$this->getVideoSourceName($theLink);

			if(isset($listitem[4]))
				$specialparams=$listitem[4];
			else
				$specialparams='';
				
			if($vsn=='youtubeplaylist')
			{
				require_once('youtubeplaylist.php');
				$newlist=VideoSource_YoutubePlaylist::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubechannel')
			{
				require_once('youtubechannel.php');
				$newlist=VideoSource_YoutubeChannel::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubeshow')
			{
				require_once('youtubeshow.php');
				$newlist=VideoSource_YoutubeShow::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubeuserfavorites')
			{
				require_once('youtubeuserfavorites.php');
				$newlist=VideoSource_YoutubeUserFavorites::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubeuseruploads')
			{
				require_once('youtubeuseruploads.php');
				$newlist=VideoSource_YoutubeUserUploads::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubestandard')
			{
				require_once('youtubestandard.php');
				$newlist=VideoSource_YoutubeStandard::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='youtubesearch')
			{
				require_once('youtubesearch.php');
				$newlist=VideoSource_YoutubeSearch::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
				if(!is_array($newlist))
				{
					echo 'Youtube Search: '.$newlist;
					die;
				}
			}
			elseif($vsn=='vimeouservideos')
			{
				require_once('vimeouservideos.php');
				$newlist=VideoSource_VimeoUserVideos::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='vimeochannel')
			{
				require_once('vimeochannel.php');
				$newlist=VideoSource_VimeoChannel::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='vimeoalbum')
			{
				require_once('vimeoalbum.php');
				$newlist=VideoSource_VimeoAlbum::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			elseif($vsn=='dailymotionplaylist')
			{
				require_once('dailymotionplaylist.php');
				$newlist=VideoSource_DailymotionPlaylist::getVideoIDList($theLink, $specialparams, $playlistid,$datalink);
			}
			

			$channels_youtube=array('youtubeuseruploads','youtubestandard','youtubeplaylist','youtubeshow','youtubeuserfavorites','youtubesearch','youtubechannel');
			$channels_other=array('vimeouservideos','vimeochannel','vimeoalbum','dailymotionplaylist');
			$channels_vimeo=array('vimeouservideos','vimeochannel','vimeoalbum');

			if(in_array($vsn,$channels_youtube) or in_array($vsn,$channels_other)) 
			{
				
				if(in_array($vsn,$channels_youtube))
					$video_source='youtube';
				
				if(in_array($vsn,$channels_vimeo))
					$video_source='vimeo';
				
				if($vsn=='dailymotionplaylist')
					$video_source='dailymotion';
				
				$new_List_Clean=array();
			
				$ordering=1;
				$startsecond=0;
				$endsecond=0;
	
				if(isset($listitem[5]))
					$startsecond=$listitem[5];
					
				if(isset($listitem[6]))
					$endsecond=$listitem[6];
					
				//
				if(is_array($newlist)){
				foreach($newlist as $theLinkItem)
				{
					
					$item=$this->GrabVideoData($theLinkItem,$video_source,'',$getinfomethod);

					if($item['videoid']!='')
					{
					
						if($firstvideo=='')
							$firstvideo=$item['videoid'];
						
						$item['ordering']=$main_ordering+$ordering;
						
						
						if(isset($listitem[1]))
						{
							$item['title']=$listitem[1];
							$item['custom_title']=$listitem[1];
						}
			
						if(isset($listitem[2]))
						{
							$item['description']=$listitem[2];
							$item['custom_description']=$listitem[2];
						}
					
						if(isset($listitem[3]))
						{
							if(strpos($listitem[3],'#')===false)
								$item['imageurl']=$listitem[3];
								
							$item['custom_imageurl']=$listitem[3];
						}
							
							
						
						$item['startsecond']=$startsecond;
						$item['endsecond']=$endsecond;
						
						$new_List_Clean[]=$item;
						
						$ordering++;
						
					}	
						
				}
			
				$item=array(
				'datalink'=>$datalink,					
				'videosource'=>$vsn,
				'videoid'=>$playlistid,
				'imageurl'=>'',
				'title'=>'',
				'description'=>'',
				'specialparams'=>$specialparams,
				'count'=>count($new_List_Clean),
				'link'=>'',
				'ordering'=>$main_ordering,
				'channel_username'=>'',
				'channel_title'=>'',
				'channel_subscribers'=>'',
				'channel_subscribed'=>'',
				'channel_location'=>'',
				'channel_commentcount'=>'',
				'channel_viewcount'=>'',
				'channel_videocount'=>'',
				'channel_description'=>'',
				'channel_totaluploadviews'=>'',
				
				);
				
								
				if($vsn=='youtubeuseruploads' and !(strpos($specialparams,'moredetails=true')===false))
				{
					//Try to get channel info
					require_once('youtubeuseruploads.php');
					$datalink=$item['datalink'];
					$user_info=VideoSource_YoutubeUserUploads::getUserInfo($theLink,$item);
					if($user_info!='')
						$item['channel_title']=$user_info;
						
						
					$item['datalink']=$datalink.','.$item['datalink'];

				}
				
				
				$gallery_list[]=$item;
				$gallery_list=array_merge($gallery_list,$new_List_Clean);
				
				}//for

			}
			elseif($vsn=='videolist')
			{
				$linkPair=explode(':',$theLink);
				
	
				if(isset($linkPair[1]))
				{
					if(trim($linkPair[1])=='all')
						$vID=-1;
					elseif(trim($linkPair[1])=='category')
					{
						if(isset($linkPair[2]))
							$vID='category='.$linkPair[2];
					}
					elseif(trim($linkPair[1])=='catid')
					{
						if(isset($linkPair[2]))
							$vID='catid='.(int)$linkPair[2];
					}
					else
					{
						$vID=(int)$linkPair[1];

					}
				
					$item=array(
						'videosource'=>$vsn,
						'videoid'=>$vID,
						'isvideo'=>"0",
						'imageurl'=>'',
						'title'=>'',
						'description'=>'',
						'specialparams'=>'',
						'count'=>'',
						'link'=>'',
						'ordering'=>''
					);
					$gallery_list[]=$item;
				}

			}
			else
			{
				$item=$this->GrabVideoData($listitem,$vsn,'',$getinfomethod);

				if(isset($item['videoid']) and  $item['videoid']!='')
				{
					if($firstvideo=='')
							$firstvideo=$item['videoid'];
							
					$item['ordering']=$main_ordering;
					$gallery_list[]=$item;
				}
			}
			
			
			
			$main_ordering+=10000;

		}//foreach($rawList as $b)

		
		return $gallery_list;
	}
	

	
	
	
	
	static public function getRawData($videoid)
	{
		
		$db = JFactory::getDBO();
				
		$query = 'SELECT rawdata FROM #__youtubegallery_videos WHERE videoid="'.$videoid.'" LIMIT 1';
					
		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
		
		$values=$db->loadAssocList();
				
		if(count($values)==0)
			return "";
		
		$v=$values[0];
		
		return $v['rawdata'];
	}
	
	static public function setDelayedRequest($videoid,$link)
	{
		if($videoid!='')
		{
			$value='*youtubegallery_request*';//.$link;//md5(mt_rand());
		
			$db = JFactory::getDBO();
			
			$query = 'UPDATE #__youtubegallery_videos SET rawdata="'.$value.'" WHERE videoid="'.$videoid.'"';

			$db->setQuery($query);
			if (!$db->query())    die ( $db->stderr());
			

		}

	}
	
	static public function setRawData($videoid,$videoData)
	{
		if($videoid!='')
		{
			$db = JFactory::getDBO();
			
			
			$query = 'UPDATE #__youtubegallery_videos SET rawdata="'.$videoData.'" WHERE videoid="'.$videoid.'"';

			$db->setQuery($query);
			if (!$db->query())    die ( $db->stderr());
			

		}

	}
	
	
	function GrabVideoData($listitem,$vsn,$videoid_optional='',$getinfomethod='')
	{
	
			$query_video_host=true;
			
			
			//Return Video Data Array separated with commma
		
			//extract title if it's needed for navigation (thumbnail) or for active video.
			$videoitem=array();
		
			$customtitle='';
			$customdescription='';
			$customimage='';
			$startsecond=0;
			$endsecond=0;
		
			if(is_array($listitem))
			{
				$theLink=trim($listitem[0]);
				
				if(isset($listitem[1]))
					$customtitle=$listitem[1];
			
				if(isset($listitem[2]))
					$customdescription=$listitem[2];
				
				if(isset($listitem[3]))
					$customimage=$listitem[3];
					
				if(isset($listitem[5]))
					$startsecond=$listitem[5];
					
				if(isset($listitem[6]))
					$endsecond=$listitem[6];
			}
			else
				$theLink=$listitem;
					
				
			if($vsn=='youtube' and !(strpos($theLink, '/embed/')===false))
			{
				//Convert Embed links to Address bar version
				$theLink=str_replace('www.youtube.com/embed/','youtu.be/',$theLink);
				$theLink=str_replace('youtube.com/embed/','youtu.be/',$theLink);
			}
		

			switch($vsn)
			{
				
				case 'break' :
					
					require_once('break.php');
					$HTML_SOURCE='';
					
					$videoid=VideoSource_Break::extractBreakID($theLink,$HTML_SOURCE);
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Break::getVideoData($videoid, $customimage, $customtitle, $customdescription, $HTML_SOURCE);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'break', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				
				case 'vimeo' :
					
					require_once('vimeo.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Vimeo::extractVimeoID($theLink);
					else
						$videoid=$videoid_optional;
						
					if($videoid!='')
					{

						if($query_video_host)
						{
							$videoitem=VideoSource_Vimeo::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'vimeo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				
				case 'own3dtvlive' :
					
					require_once('own3dtvlive.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Own3DTvLive::extractOwn3DTvLiveID($theLink);
					else
						$videoid=$videoid_optional;						
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Own3DTvLive::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'own3dtvlive', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'own3dtvvideo' :
					
					require_once('own3dtvvideo.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Own3DTvVideo::extractOwn3DTvVideoID($theLink);
					else
						$videoid=$videoid_optional;						
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Own3DTvVideo::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;

						}
						else
							$videoitem=array('videosource'=>'own3dtvvideo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'youtube' :

				
					require_once('youtube.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Youtube::extractYouTubeID($theLink);
					else
						$videoid=$videoid_optional;
					
					if($videoid!='')
					{
						
						if($query_video_host)
						{
							if(isset($this->theme_row->thumbnailstyle))
								$theme_row_thumbnailstyle=$this->theme_row->thumbnailstyle;
							else
								$theme_row_thumbnailstyle='';
							
							
							$videoitem=VideoSource_Youtube::getVideoData(
												$videoid,
												$customimage,
												$customtitle,
												$customdescription,
												$theme_row_thumbnailstyle,
												$getinfomethod
												);
							$videoitem['link']=$theLink;
						}
						else{
							if(strpos($customimage, '#')===false)
								$customimage_=$customimage;
							else
								$customimage_='';
							
							$videoitem=array('videosource'=>'youtube', 'videoid'=>$videoid, 'imageurl'=>$customimage_, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
						}
					}
					break;
				
				case 'google' :

					require_once('google.php');
					if($videoid_optional=='')
						$videoid=VideoSource_Google::extractGoogleID($theLink);
					else
						$videoid=$videoid_optional;

					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Google::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'google', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
					
					break;
				
				case 'yahoo' :
					
					require_once('yahoo.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_Yahoo::extractYahooID($theLink);
					else
						$videoid=$videoid_optional;
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Yahoo::getVideoData($videoid,$customimage,$customtitle,$customdescription,$theLink);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'yahoo', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
				
					break;
				
				case 'collegehumor' :
					
					require_once('collegehumor.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_CollegeHumor::extractCollegeHumorID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_CollegeHumor::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'collegehumor', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}
			
					break;
				
				case 'dailymotion' :
					
					require_once('dailymotion.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_DailyMotion::extractDailyMotionID($theLink);
					else
						$videoid=$videoid_optional;

					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_DailyMotion::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'dailymotion', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				case 'presentme' :
					
					require_once('presentme.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_PresentMe::extractPresentMeID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_PresentMe::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'presentme', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				case 'ustream' :
					
					require_once('ustream.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_Ustream::extractUstreamID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_Ustream::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'ustream', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				case 'ustreamlive' :
					
					require_once('ustreamlive.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_UstreamLive::extractUstreamLiveID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_UstreamLive::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'ustreamlive', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				case 'soundcloud' :
					
					require_once('soundcloud.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_soundcloud::extractID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_soundcloud::getVideoData($videoid,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'soundcloud', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
				case '.flv' :
					
					require_once('flv.php');
					
					if($videoid_optional=='')
						$videoid=VideoSource_FLV::extractFLVID($theLink);
					else
						$videoid=$videoid_optional;
					
					
					if($videoid!='')
					{
						if($query_video_host)
						{
							$videoitem=VideoSource_FLV::getVideoData($videoid,$theLink,$customimage,$customtitle,$customdescription);
							$videoitem['link']=$theLink;
						}
						else
							$videoitem=array('videosource'=>'.flv', 'videoid'=>$videoid, 'imageurl'=>$customimage, 'title'=>$customtitle,'description'=>$customdescription,'link'=>$theLink);
					}

					break;
				
			}//switch($vsn)
			
			if(!isset($videoitem['title']))
			   $videoitem['title']='';
			
			if(!isset($videoitem['description']))
			   $videoitem['description']='';
			   
			if(!isset($videoitem['imageurl']))
			   $videoitem['imageurl']='';
			   
			$videoitem['custom_title']=$customtitle;
			$videoitem['custom_description']=$customdescription;
			$videoitem['custom_imageurl']=$customimage;
			$videoitem['startsecond']=$startsecond;
			$videoitem['endsecond']=$endsecond;
				
			
		return $videoitem;
	}
	
	
	function isVideo_record_exist($videosource,$videoid,$listid)
	{
				$db = JFactory::getDBO();
				
				$query = 'SELECT id, allowupdates FROM #__youtubegallery_videos WHERE videosource="'.$videosource.'" AND videoid="'.$videoid.'" AND listid='.$listid.' LIMIT 1';

				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				
				$videos_rows=$db->loadAssocList();
				
				if(count($videos_rows)==0)
						return 0;
				
				$videos_row=$videos_rows[0];
				
				if($videos_row['allowupdates']!=1)
						return -1; //Updates disable
				
				return $videos_row['id'];
	}
	

	
	function getVideoList_FromCache_From_Table(&$videoid,&$total_number_of_rows)
	{
		$listIDs=array();
		$listIDs[]=$this->videolist_row->id;
		
		$db = JFactory::getDBO();
		
		$where=array();
		
		$where[]='!INSTR(title,"***Video not found***")';
		$where[]='listid="'.$this->videolist_row->id.'"';
		$where[]='isvideo=0';
		$where[]='videosource="videolist"';
		
		$query = 'SELECT videoid FROM #__youtubegallery_videos WHERE '.implode(' AND ', $where);
		
		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
		$videos_lists=$db->loadAssocList();
		
		if(count($videos_lists)>0)
		{
			foreach($videos_lists as $v)
			{

				if($v['videoid']==-1)
				{
					//all videos
					$listIDs=array();
					break;
				}
				elseif(!(strpos($v['videoid'],'catid=')===false))
				{
					//Video Lists of selected category by id

					$catid=intval(str_replace('catid=','',$v['videoid']));
					
					$query = 'SELECT id FROM #__youtubegallery_videolists WHERE catid='.$catid;
					$db->setQuery($query);
					if (!$db->query())    die( $db->stderr());
					$videos_lists_=$db->loadAssocList();
					
					foreach($videos_lists_ as $vl)
					{
						$listIDs[]=$vl['id'];
					}
				}
				elseif(!(strpos($v['videoid'],'category=')===false))
				{
					//Video Lists of selected category by id

					$categoryname=str_replace('category=','',$v['videoid']);
					
					$query = 'SELECT #__youtubegallery_videolists.id AS computedcatid FROM #__youtubegallery_videolists
					INNER JOIN #__youtubegallery_categories ON #__youtubegallery_categories.id=#__youtubegallery_videolists.catid
					WHERE #__youtubegallery_categories.categoryname="'.$categoryname.'"';
					
					
					$db->setQuery($query);
					if (!$db->query())    die( $db->stderr());
					$videos_lists_=$db->loadAssocList();
					
					foreach($videos_lists_ as $vl)
					{
						$listIDs[]=$vl['computedcatid'];
					}

				}
				else
					$listIDs[]=$v['videoid'];
			}	
		
		
		}
		return $this->getVideoList_FromCacheFromTable($videoid,$total_number_of_rows,$listIDs);
	}
	
	function addSearchQuery()
	{
		//Input value sanitazed below
		$search_fields=JFactory::getApplication()->input->getVar('ygsearchfields');
		if($search_fields!='')
		{
			
			
			$search_query=str_replace('"','',JFactory::getApplication()->input->getVar('ygsearchquery'));
			$search_query=str_replace(' ',',',$search_query);
			$search_query_array=explode(',',$search_query);
			
			$search_fields_array=explode(',',$search_fields);
			$possible_fields=array('videoid','title','description','publisheddate','keywords','channel_username','channel_title','channel_description','channel_totaluploadviews');

			
			$q_where=array();
			foreach($search_query_array as $q)
			{
				if($q!='')
				{
					$f_where=array();
					foreach($search_fields_array as $f)
					{
						if(in_array($f,$possible_fields))
							$f_where[]='INSTR('.$f.',"'.$q.'")';
					}//f
				
					if(count($f_where)==1)
						$q_where[]=implode(' OR ',$f_where);
					elseif(count($f_where)>1)
						$q_where[]='('.implode(' OR ',$f_where).')';
				}
			}//q
			
			if(count($q_where)==1)
				return implode(' AND ',$q_where);
			elseif(count($q_where)>1)
				return '('.implode(' AND ',$q_where).')';
			else
				return '';
		}
	}
	
	function getVideoList_FromCacheFromTable(&$videoid,&$total_number_of_rows,&$listIDs)
	{
		$where=array();
		
		//Only for search module
		$wq=$this->addSearchQuery();
		if($wq!='')
			$where[]=$wq;
		
		
		if(count($listIDs)>0)
			$where[]='(listid="'.implode('" OR listid="',$listIDs).'")';
			
			
		$where[]='isvideo';
				
		$db = JFactory::getDBO();
	
		if($this->theme_row->rel!='' and JFactory::getApplication()->input->getCmd('tmpl')=='component')
		{
			// Get only one video, because video opens in Shadow/Lightbox
			$where[]='videoid="'.$videoid.'"';
			$limitstart=0;
			$limit=1;
		}
		else
		{
			if(((int)$this->theme_row->customlimit)==0)
				$limit=0; // UNLIMITED
			else
				$limit = (int)$this->theme_row->customlimit;
			
			$limitstart = JFactory::getApplication()->input->getInt('ygstart', 0);
		}
		
		if($this->theme_row->orderby!='')
		{
			if($this->theme_row->orderby=='randomization')
				$orderby='RAND()';
			else
				$orderby=$this->theme_row->orderby;
		}
		else
			$orderby='ordering';
			
			
		
		$query = 'SELECT * FROM #__youtubegallery_videos WHERE '.implode(' AND ', $where).' GROUP BY videoid ORDER BY '.$orderby; 

		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
		//$db->query();
		$total_number_of_rows = $db->getNumRows();
		
		if($limit==0)
			$db->setQuery($query);
		else
			$db->setQuery($query, $limitstart, $limit);
		
		if (!$db->query())    die( $db->stderr());
				


		$videos_rows=$db->loadAssocList();
		
		//if($this->theme_row->orderby=='randomization')
		//	shuffle($videos_rows);
			
		$firstvideo='';
		
		if($firstvideo=='' and count($videos_rows)>0)
		{
			$videos_row=$videos_rows[0];
			$firstvideo=$videos_row['videoid'];
			
			
		}
		if($videoid!='')
		{
			/*
			$found=false;	
			foreach($videos_rows as $videos_row)
			{
								
				if($videos_row['videoid']==$videoid)
				{
					$found=true;
					break;
				}
			}
		
			if(!$found)
			{
				if($firstvideo!='')
					$videoid=$firstvideo;
				
			}
			*/
		}
		else
		{
			if($firstvideo!='')
				$videoid=$firstvideo;
		}
	
		
		return $videos_rows;
		
	}
	
	

	function update_playlist($force_update = false)
	{

			$start  = strtotime( $this->videolist_row->lastplaylistupdate );
			$end    = strtotime( date( 'Y-m-d H:i:s') );
			$days_diff = ($end-$start)/86400;
			
			$updateperiod=(float)$this->videolist_row->updateperiod;
			if($updateperiod==0)
				$updateperiod=1;
			
			if($days_diff>abs($updateperiod) or $force_update)
			{

				$this->update_cache_table($this->videolist_row,$updateperiod>0); //$updateperiod>0 ? refresh : get new videos
				$this->videolist_row->lastplaylistupdate =date( 'Y-m-d H:i:s');
				
				$db = JFactory::getDBO();
				$query = 'UPDATE #__youtubegallery_videolists SET lastplaylistupdate="'.$this->videolist_row->lastplaylistupdate.'" WHERE id="'.$this->videolist_row->id.'"';
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
			}
	}

	function update_cache_table(&$videolist_row,$update_videolist=true) 
	{
		//$update_videolist=true - update
		//$update_videolist=false - refresh
		
		
				$videolist_array=$this->csv_explode("\n", $videolist_row->videolist, '"', true);
				
				$firstvideo='';
				
				$videolist=$this->formVideoList($videolist_array, $firstvideo);

				$ListOfVideos=array();
				
				$db = JFactory::getDBO();
				
				$parent_id=0;
				$parent_details=array();
				$this_is_a_list=false;
				$list_count_left=0;
				
				foreach($videolist as $g)
				{
						$g_title=str_replace('"','&quot;',$g['title']);
						$g_description=str_replace('"','&quot;',$g['description']);
						
						if(isset($g['custom_title']))
							$custom_g_title=str_replace('"','&quot;',$g['custom_title']);
						else
							$custom_g_title='';
						
						if(isset($g['custom_description']))
							$custom_g_description=str_replace('"','&quot;',$g['custom_description']);
						else
							$custom_g_description='';
						
						$fields=array();

						if(
						   $g['videosource']=='youtubeuseruploads' or
						   $g['videosource']=='youtubestandard' or
						   $g['videosource']=='youtubeplaylist' or
						   $g['videosource']=='youtubeshow' or
						   $g['videosource']=='youtubeuserfavorites' or
						   $g['videosource']=='youtubesearch' or
						   $g['videosource']=='youtubechannel' or
						   $g['videosource']=='vimeouservideos' or
						   $g['videosource']=='vimeochannel' or
						   $g['videosource']=='vimeoalbum' or
						   $g['videosource']=='videolist' or
						   $g['videosource']=='dailymotionplaylist'
						   )
						{
								//parent
								$parent_id=0;
								$this_is_a_list=true;
								$list_count_left=(int)$g['count'];
						}
						else
						{
								$this_is_a_list=false;
						}

						
						$fields[]='listid="'.$videolist_row->id.'"';
						$fields[]='parentid="'.$parent_id.'"';
						$fields[]='videosource="'.$g['videosource'].'"';
						
						$fields[]='videoid="'.$g['videoid'].'"';
						
						if(isset($g['datalink']))
							$fields[]='datalink="'.$g['datalink'].'"';
							
						
						if($g['imageurl']!='')
							$fields[]='imageurl="'.$g['imageurl'].'"';
							
						if($g['title']!='')
							$fields[]='title="'.$g_title.'"';
						
						if($g['description']!='')
							$fields[]='description="'.$g_description.'"';
						
						if(isset($g['custom_imageurl']))
							$fields[]='custom_imageurl="'.$g['custom_imageurl'].'"';
						else
							$fields[]='custom_imageurl=""';
							
		
						if($g['title']!='')
							$fields[]='alias="'.YouTubeGalleryMisc::get_alias($g_title,$g['videoid']).'"';
							
							
						$fields[]='custom_title="'.$custom_g_title.'"';
						$fields[]='custom_description="'.$custom_g_description.'"';

						if(isset($g['specialparams']))
							$fields[]='specialparams="'.$g['specialparams'].'"';
						else
							$fields[]='specialparams=""';
							
						if(isset($g['startsecond']))
							$fields[]='startsecond="'.$g['startsecond'].'"';
						else
							$fields[]='startsecond="0"';
						
						if(isset($g['endsecond']))
							$fields[]='endsecond="'.$g['endsecond'].'"';
						else
							$fields[]='endsecond="0"';
							
						$fields[]='link="'.$g['link'].'"';
						$fields[]='ordering="'.$g['ordering'].'"';
					
						if($this_is_a_list)
								$fields[]='lastupdate="'.date( 'Y-m-d H:i:s').'"';
						$fields[]='isvideo="'.($this_is_a_list ? '0' : '1').'"';
						

						if(isset($g['publisheddate']))
							$fields[]='publisheddate="'.$g['publisheddate'].'"';
						//else
						//	$fields[]='publisheddate=""';
					
						if(isset($g['duration']))
							$fields[]='duration="'.$g['duration'].'"';
						//else
						//	$fields[]='duration=0';
					
						if(isset($g['rating_average']))
							$fields[]='rating_average="'.$g['rating_average'].'"';
						//else
						//	$fields[]='rating_average=0';
						
						if(isset($g['rating_max']))
							$fields[]='rating_max="'.$g['rating_max'].'"';
						//else
						//	$fields[]='rating_max=0';
					
						if(isset($g['rating_min']))
							$fields[]='rating_min="'.$g['rating_min'].'"';
						//else
						//	$fields[]='rating_min=0';
					
						if(isset($g['rating_numRaters']))
							$fields[]='rating_numRaters="'.$g['rating_numRaters'].'"';
						//else
						//	$fields[]='rating_numRaters=0';
					
						if(isset($g['statistics_favoriteCount']))
							$fields[]='statistics_favoriteCount="'.$g['statistics_favoriteCount'].'"';

						if(isset($g['statistics_viewCount']))
							$fields[]='statistics_viewCount="'.$g['statistics_viewCount'].'"';

				
						if(isset($g['keywords']))
						{
							if(is_array($g['keywords']))
							{
								$key_words=implode(',',$g['keywords']);
								$fields[]='keywords="'.str_replace('"','&quot;',$key_words).'"';
							}
							else
								$key_words='';

						}	
						if(isset($g['likes']))
							$fields[]='likes="'.$g['likes'].'"';
					
						if(isset($g['dislikes']))
							$fields[]='dislikes="'.$g['dislikes'].'"';
							
						if($this_is_a_list)
						{
							$parent_details=$g;
						}
					
						if(isset($parent_details['channel_username']))
							$fields[]='channel_username="'.$parent_details['channel_username'].'"';

						if(isset($parent_details['channel_title']))
							$fields[]='channel_title="'.$parent_details['channel_title'].'"';
							
						if(isset($parent_details['channel_subscribers']))
							$fields[]='channel_subscribers="'.$parent_details['channel_subscribers'].'"';

						if(isset($parent_details['channel_subscribed']))
							$fields[]='channel_subscribed="'.$parent_details['channel_subscribed'].'"';
							
						if(isset($parent_details['channel_location']))
							$fields[]='channel_location="'.$parent_details['channel_location'].'"';
							
						if(isset($parent_details['channel_commentcount']))
							$fields[]='channel_commentcount="'.$parent_details['channel_commentcount'].'"';
							
						if(isset($parent_details['channel_viewcount']))
							$fields[]='channel_viewcount="'.$parent_details['channel_viewcount'].'"';
							
						if(isset($parent_details['channel_videocount']))
							$fields[]='channel_videocount="'.$parent_details['channel_videocount'].'"';
							
						if(isset($parent_details['channel_description']))
							$fields[]='channel_description="'.$parent_details['channel_description'].'"';

						//if(isset($parent_details['channel_totaluploadviews']))
							//$fields[]='channel_totaluploadviews="'.$parent_details['channel_totaluploadviews'].'"';
						
						
						
						
							$record_id=$this->isVideo_record_exist($g['videosource'],$g['videoid'],$videolist_row->id);
						
						$query='';
						
						
						
						if($record_id==0)
						{
							
								$query="INSERT #__youtubegallery_videos SET ".implode(', ', $fields).', allowupdates="1"';
								
						
								
								$db->setQuery($query);
								if (!$db->query())    die( $db->stderr());

								$record_id_new=$this->isVideo_record_exist($g['videosource'],$g['videoid'],$videolist_row->id);
								
								$ListOfVideos[]=$record_id_new;

								if($this_is_a_list)
								{
									$parent_id=$record_id_new;
									$parent_details=$g;
								}
						}
						elseif($record_id>0)
						{
						
							if($g_title!='***Video not found***')
							{
								//Don't update info, if cannot get the info
								$query="UPDATE #__youtubegallery_videos SET ".implode(', ', $fields).' WHERE id='.$record_id;
								
								$db->setQuery($query);
								if (!$db->query())    die( $db->stderr());
								
								$ListOfVideos[]=$record_id;
								
								if($this_is_a_list)
								{
									$parent_id=$record_id;
									$parent_details=$g;
								}
							}	
						}
						

						
						if(!$this_is_a_list)
						{
								if($list_count_left>0)
										$list_count_left-=1;
										
								
								if($list_count_left==0)
									$parent_id=0;

						}

				}
				
				
				
				if(!$update_videolist)
				{
					//Delete All videos of this video list that has been deleted form the list and allowed for updates.
					//isvideo AND
				
					$query='DELETE FROM #__youtubegallery_videos WHERE listid='.((int)$videolist_row->id).' AND allowupdates';
					if(count($ListOfVideos)>0)
						$query.=' AND id!='.implode(' AND id!=',$ListOfVideos).' ';
			
					$db->setQuery($query);
					if (!$db->query())    die( $db->stderr());
				}
			
				
			
	}
	
	
	function RefreshVideoData(&$gallery_list,$getinfomethod,$force_refresh=false)
	{
		
		
			
		$db = JFactory::getDBO();
		
		$count=count($gallery_list);
		for($i=0;$i<$count;$i++)
		{
			$listitem=$gallery_list[$i];
			
			if(!$force_refresh)
			{
				$start  = strtotime( $listitem['lastupdate'] );
				$end    = strtotime( date( 'Y-m-d H:i:s') );
				$days_diff = ($end-$start)/86400;
			
				$updateperiod=$this->videolist_row->updateperiod;
			}
			else
				$updateperiod=0;
			
			
			if($updateperiod==0)
				$updateperiod=1;
			
			
			
		
			
			if($getinfomethod=='js' or $getinfomethod=='jsmanual')
			{
				$rd=YouTubeGalleryMisc::getRawData($listitem['videoid']);
				//if($rd=='')
				//{
					//YouTubeGalleryMisc::setDelayedRequest($listitem['videoid'])
					//$days_dif=0;
				if($rd=='*youtubegallery_request*')
					$days_dif=0;
			
			}
			
			
			if($force_refresh or $listitem['status']==0 or $days_diff>$updateperiod)
			{
				
				

				$listitem_temp=array();
				$listitem_temp[]=$listitem['link'];
				$listitem_temp[]=$listitem['custom_title'];
				$listitem_temp[]=$listitem['custom_description'];
				$listitem_temp[]=$listitem['custom_imageurl'];
				
				
				
				$listitem_new=$this->GrabVideoData($listitem_temp,$listitem['videosource'],$listitem['videoid'],$getinfomethod);
				
				if($listitem_new['title']!='')
					$listitem['title']=$listitem_new['title'];
					
				if($listitem_new['description']!='')
					$listitem['description']=$listitem_new['description'];
					
				if($listitem_new['imageurl']!='')
					$listitem['imageurl']=$listitem_new['imageurl'];
				
				$fields=array();
				
				$fields[]='title="'.$this->mysqlrealescapestring($listitem_new['title']).'"';
				
				if($listitem_new['title']!='')
					$fields[]='alias="'.YouTubeGalleryMisc::get_alias($listitem_new['title'],$listitem['videoid']).'"';
							
				$fields[]='description="'.$this->mysqlrealescapestring($listitem_new['description']).'"';
				$fields[]='imageurl="'.$listitem_new['imageurl'].'"';
				$fields[]='lastupdate="'.date( 'Y-m-d H:i:s').'"';
				$fields[]='status="200"';
				
				if(isset($listitem_new['startsecond']))
					$fields[]='startsecond="'.$listitem['startsecond'].'"'; 
				
				if(isset($listitem_new['endsecond']))
					$fields[]='endsecond="'.$listitem['endsecond'].'"'; 
					
				if(isset($listitem_new['publisheddate']))
					$fields[]='publisheddate="'.$listitem_new['publisheddate'].'"';
					
				if(isset($listitem_new['duration']))
					$fields[]='duration="'.$listitem_new['duration'].'"';
					
				if(isset($listitem_new['rating_average']))
					$fields[]='rating_average="'.$listitem_new['rating_average'].'"';
					
				if(isset($listitem_new['rating_max']))
					$fields[]='rating_max="'.$listitem_new['rating_max'].'"';
					
				if(isset($listitem_new['rating_min']))
					$fields[]='rating_min="'.$listitem_new['rating_min'].'"';
					
				if(isset($listitem_new['rating_numRaters']))
					$fields[]='rating_numRaters="'.$listitem_new['rating_numRaters'].'"';
					
				if(isset($listitem_new['statistics_favoriteCount']))
				{
					$fields[]='statistics_favoriteCount="'.$listitem_new['statistics_favoriteCount'].'"';
				}
					
				
				if(isset($listitem_new['statistics_viewCount']))
					$fields[]='statistics_viewCount="'.$listitem_new['statistics_viewCount'].'"';
				
				if(isset($listitem_new['keywords']))
				{
					//updated 2016/02
					if(is_array($listitem_new['keywords']))
						$key_words=implode(',',$listitem_new['keywords']);
					else
						$key_words='';
						
					$fields[]='keywords="'.str_replace('"','&quot;',$key_words).'"';
				}	
				
				if(isset($listitem_new['likes']))
					$fields[]='likes="'.$listitem_new['likes'].'"';
					
				if(isset($listitem_new['dislikes']))
					$fields[]='dislikes="'.$listitem_new['dislikes'].'"';
					
				if(isset($listitem_new['commentcount']))
					$fields[]='commentcount="'.$listitem_new['commentcount'].'"';
					
				$query="UPDATE #__youtubegallery_videos SET ".implode(', ', $fields).' WHERE id='.(int)$listitem['id'];
				
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				
				$gallery_list[$i]=$listitem;
			}
			
		}//foreach($gallery_list as $listitem)
		

	}
	
	
	function getVideoSourceName($link)
	{

		
		if(!(strpos($link,'://youtube.com')===false) or !(strpos($link,'://www.youtube.com')===false))
		{
			if(!(strpos($link,'/playlist')===false))
				return 'youtubeplaylist';
			if(strpos($link,'&list=PL')!==false)
			{
				return 'youtubeplaylist';
				//https://www.youtube.com/watch?v=cNw8A5pwbVI&list=PLMaV6BfupUm-xIMRGKfjj-fP0BLq7b6SJ
			}
			elseif(!(strpos($link,'/favorites')===false))
				return 'youtubeuserfavorites';
			elseif(!(strpos($link,'/user')===false))
				return 'youtubeuseruploads';
			elseif(!(strpos($link,'/results')===false))
				return 'youtubesearch';
			elseif(!(strpos($link,'youtube.com/show/')===false))
				return 'youtubeshow';
			elseif(!(strpos($link,'youtube.com/channel/')===false))
				return 'youtubechannel';
			else
				return 'youtube';
		}
		
		if(!(strpos($link,'://youtu.be')===false) or !(strpos($link,'://www.youtu.be')===false))
			return 'youtube';
		
		if(!(strpos($link,'youtubestandard:')===false))
			return 'youtubestandard';
		
		if(!(strpos($link,'videolist:')===false))
			return 'videolist';
		
		
		if(!(strpos($link,'://vimeo.com/user')===false) or !(strpos($link,'://www.vimeo.com/user')===false))
			return 'vimeouservideos';
		elseif(!(strpos($link,'://vimeo.com/channels/')===false) or !(strpos($link,'://www.vimeo.com/channels/')===false))
			return 'vimeochannel';
		elseif(!(strpos($link,'://vimeo.com/album/')===false) or !(strpos($link,'://www.vimeo.com/album/')===false))
			return 'vimeoalbum';
		elseif(!(strpos($link,'://vimeo.com')===false) or !(strpos($link,'://www.vimeo.com')===false))
		{
			preg_match('/http:\/\/vimeo.com\/(\d+)$/', $link, $matches);
			if (count($matches) != 0)
			{
				//single video
				return 'vimeo';
			}
			else
			{
				preg_match('/https:\/\/vimeo.com\/(\d+)$/', $link, $matches);
				if (count($matches) != 0)
				{
					//single video
					return 'vimeo';
				}
				else
				{
					preg_match('/http:\/\/vimeo.com\/(\d+)$/', $link, $matches);
					return 'vimeouservideos'; //or anything else
				}
			}
			
			
			return '';
		}
		
		
		if(!(strpos($link,'://own3d.tv/l/')===false) or !(strpos($link,'://www.own3d.tv/l/')===false))
			return 'own3dtvlive';
		
		if(!(strpos($link,'://own3d.tv/v/')===false) or !(strpos($link,'://www.own3d.tv/v/')===false))
			return 'own3dtvvideo';
		
		
		if(!(strpos($link,'video.google.com')===false))
			return 'google';
		
		if(!(strpos($link,'video.yahoo.com')===false))
			return 'yahoo';
		
		if(!(strpos($link,'://break.com')===false) or !(strpos($link,'://www.break.com')===false))
			return 'break';
		
	
		if(!(strpos($link,'://collegehumor.com')===false) or !(strpos($link,'://www.collegehumor.com')===false))
			return 'collegehumor';
		
		//http://www.dailymotion.com/playlist/x1crql_BigCatRescue_funny-action-big-cats/1#video=x7k9rx
		if(!(strpos($link,'://dailymotion.com/playlist/')===false) or !(strpos($link,'://www.dailymotion.com/playlist/')===false))
			return 'dailymotionplaylist';
		
		if(!(strpos($link,'://dailymotion.com')===false) or !(strpos($link,'://www.dailymotion.com')===false))
			return 'dailymotion';
		
		if(!(strpos($link,'://present.me')===false) or !(strpos($link,'://www.present.me')===false))
			return 'presentme';
		
		if(!(strpos($link,'://ustream.tv/recorded')===false) or !(strpos($link,'://www.ustream.tv/recorded')===false))
			return 'ustream';
		
		if(!(strpos($link,'://ustream.tv/channel')===false) or !(strpos($link,'://www.ustream.tv/channel')===false))
			return 'ustreamlive';
		
		
		//http://api.soundcloud.com/tracks/49931.json  - accepts only resolved links
		if(!(strpos($link,'://api.soundcloud.com/tracks/')===false) )
			return 'soundcloud';
		
		//if(!(strpos($link,'://soundcloud.com')===false) or !(strpos($link,'://www.soundcloud.com')===false))
			//return 'soundcloud';
		
		if(!(strpos(strtolower($link),'.flv')===false))
			return '.flv';
		
		return '';
	}
	
	
	public static function parse_query($var)
	{
		$arr  = array();
		
		 $var  = parse_url($var);
		 $varquery=$var['query'];

		 
		 if($varquery=='')
			return $arr;
		
		 $var  = html_entity_decode($varquery);
		 $var  = explode('&', $var);
		 

		foreach($var as $val)
		{
			$x          = explode('=', $val);
			$arr[$x[0]] = $x[1];
		}
		unset($val, $x, $var);
		return $arr;
	}
	
	
	function csv_explode($delim=',', $str, $enclose='"', $preserve=false)
	{
		$resArr = array();
		$n = 0;
		$expEncArr = explode($enclose, $str);
		foreach($expEncArr as $EncItem)
		{
			if($n++%2){
				array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
			}else{
				$expDelArr = explode($delim, $EncItem);
				array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			    $resArr = array_merge($resArr, $expDelArr);
			}
		}
	return $resArr;
	}
	
	
	function mysqlrealescapestring($inp)
	{
		
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
		    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	    }

	    return $inp;

    }	

	public static function getURLData($url)
	{
		$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
		
		if($getinfomethod=='js' or $getinfomethod=='jsmanual')
		{
			$db = JFactory::getDBO();
				
				$query = 'SELECT rawdata FROM #__youtubegallery_videos WHERE INSTR(datalink,"'.$url.'") LIMIT 1';
					
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
				$values=$db->loadAssocList();
				
				if(count($values)==1)
				{
					$v=$values[0];
					$rd=$v['rawdata'];
					if($rd!='' and $rd!='*youtubegallery_request*')
						return $rd;
				}
			
			return '';
		}
		else
		{
			$htmlcode='';
		
			if (function_exists('curl_init'))
			{
				$ch = curl_init();
				$timeout = 150;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$htmlcode = curl_exec($ch);
				curl_close($ch);
			}
			elseif (ini_get('allow_url_fopen') == true)
			{
				$htmlcode = file_get_contents($url);
			}
			else
			{
				echo '<p style="color:red;">Cannot load data, enable "allow_url_fopen" or install cURL<br/>
				<a href="http://joomlaboat.com/youtube-gallery/f-a-q/why-i-see-allow-url-fopen-message" target="_blank">Here</a> is what to do.
				</p>
				';
			}

			return $htmlcode;
		}
	}
	
	
	
	public static function ApplyPlayerParameters(&$settings,$youtubeparams)
	{
		if($youtubeparams=='')
			return;
		
		$a=str_replace("\n",'',$youtubeparams);
		$a=trim(str_replace("\r",'',$a));
		$l=explode(';',$a);
		
		foreach($l as $o)
		{
			if($o!='')
			{
				$pair=explode('=',$o);
				if(count($pair)==2)
				{
					$option=trim(strtolower($pair[0]));
			
					$found=false;
			
					for($i=0;$i<count($settings);$i++)
					{
				
						if($settings[$i][0]==$option)
						{
							$settings[$i][1]=$pair[1];
							$found=true;
							break;
						}
					}
				
					if(!$found)
						$settings[]=array($option,$pair[1]);
				}//if(count($pair)==2)
			}//if($o!='')
		}
		
	}
	
	public static function CreateParamLine(&$settings)
	{
		$a=array();
		
		foreach($settings as $s)
		{
			if(isset($s[1]))
				$a[]=$s[0].'='.$s[1];
		}

		return implode('&amp;',$a);
	}
	
	
	public static function getSettingValue($option)
	{
				$db = JFactory::getDBO();
				
				$query = 'SELECT `value` FROM #__youtubegallery_settings WHERE `option`="'.$option.'" LIMIT 1';
					
				$db->setQuery($query);
				if (!$db->query())    die( $db->stderr());
					$values=$db->loadAssocList();
				
				if(count($values)==0)
								return "";
				$v=$values[0];

				return $v['value'];
	}
	
	
	public static function get_alias($title,$videoid)
	{
		if($videoid!='')
		{
			$alias=YouTubeGalleryMisc::slugify($title);
		
			if($alias!="")
			{
				$db = JFactory::getDBO();
	      
				$db->setQuery('SELECT alias FROM #__youtubegallery_videos WHERE alias="'.$alias.'"');
			
				if (!$db->query())    die ('yg get_alias err:'. $db->stderr());
					$rows = $db->loadObjectList();
	      
			  	if(count($rows)>1)
					$alias.="_".$videoid;
			}
			else
				return $videoid;
			
			if($alias=='')
				return 'x-'.$videoid;
			else
				return $alias;
		}
		else
			return '-wrong video id-';
	}
	
	
	public static function slugify($text)
	{
		//or use
		//JFilterOutput::stringURLSafe($this->alias);
		
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		
		
		//ini_set('mbstring.substitute_character', "none");
		//$text= mb_convert_encoding($text, 'UTF-8', 'UTF-8');
		// transliterate
		
		//$text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
		$text = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $text);
		//$text = iconv('utf-8', 'us-ascii//IGNORE//TRANSLIT', $text);
		//$text = iconv('utf-8', 'ISO-8859-1//TRANSLIT', $text);
		
		
		

		// trim
		$text = trim($text, '-');

		

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
			return '';

		return $text;
	}


	public static function getVideoIDbyAlias($alias)
	{
		$db = JFactory::getDBO();
	   	
		$db->setQuery('SELECT videoid FROM #__youtubegallery_videos WHERE alias="'.$alias.'" LIMIT 1');
		if (!$db->query())    die ('yg router.php 2 err:'. $db->stderr());
		$rows = $db->loadObjectList();
	   
		if(count($rows)==0)
			return '';
		else
		{
			$row=$rows[0];
			return $row->videoid;
		}
	}
	
	
	/* USER-AGENTS ================================================== */
	//http://stackoverflow.com/questions/6524301/detect-mobile-browser
	public static function check_user_agent ( $type = NULL )
	{
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( $type == 'bot' ) {
                // matches popular bots
                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                        return true;
                        // watchmouse|pingdom\.com are "uptime services"
                }
        } else if ( $type == 'browser' ) {
                // matches core browser types
                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'mobile' ) {
                // matches popular mobile devices that have small screens and/or touch inputs
                // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
                // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                        // these are the most common
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                        // these are less common, and might not be worth checking
                        return true;
                }
        }
        return false;
	}
	
	public static function check_user_agent_for_apple ()
	{
		$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
                if ( preg_match ( "/iphone|itouch|ipod|ipad/", $user_agent ) ) {
                        // these are the most common
                        return true;
                }
		
		return false;
	}
	
	
	public static function check_user_agent_for_ie ()
	{
		$u=$_SERVER['HTTP_USER_AGENT'];
		if(strpos($u, 'MSIE') !== FALSE)
			return true;
		elseif(strpos($u, 'Trident') !== FALSE)
			return true;
		
		return false;
	}
	


	

	public static function html2txt($document)
	{ 
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
		); 
		$text = preg_replace($search, '', $document); 
		return $text; 
	}
	
	
	protected static function url_origin($s, $use_forwarded_host=false)
	{
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME']);
		return $protocol . '://' . $host . $port;
	}
	
	public static function full_url($s, $use_forwarded_host=false)
	{
	    return YouTubeGalleryMisc::url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}
	
	
	public static function getMaxResults($spq,&$option)
	{
		$count=0;
		$pair=explode('&',$spq);
		foreach($pair as $p)
		{
			$opt=explode('=',$p);
			if($opt[0]=='maxResults')
			{
				$option=$opt[0].'='.$opt[1];
				$count=(int)$opt[1];
			}
		}
		
		if($count==0)
			$count=50;

		return $count;
	}
}


function _is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}



?>