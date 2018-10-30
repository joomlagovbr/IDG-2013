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

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
	
require_once('misc.php');

class YoutubeGalleryLayoutRenderer
{
	public static function getValue($fld, $params, &$videolist_row, &$theme_row, $gallery_list, $width, $height, $videoid, $AllowPagination, $total_number_of_rows,$custom_itemid=0)//,$title
	{
		
		$fields_theme=array('bgcolor','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		if(in_array($fld,$fields_theme))
		{
			$theme_row_array = get_object_vars($theme_row);
			return $theme_row_array[$fld];
		}
		
		switch($fld)
		{
			case 'mediafolder':
				if($theme_row->mediafolder=='')
					return '';
				else
					return 'images/'.$theme_row->mediafolder;
			break;
		
			case 'videolist':
				if($params!='')
				{
					$pair=explode(',',$params);
					switch($pair[0])
					{
						case 'title':
							return $videolist_row->listname;
							break;
						
						case 'description':
							return YouTubeGalleryMisc::html2txt($videolist_row->description);
							break;
						
						case 'author':
							return $videolist_row->author;
							break;
						
						case 'playlist':
							$pl=YoutubeGalleryLayoutRenderer::getPlaylistIdsOnly($gallery_list);
							$vlu=implode(',',$pl);
							break;
						
						case 'watchgroup':
							return $videolist_row->watchusergroup ;
							break;
						
						case 'authorurl':
							return $videolist_row->authorurl ;
							break;
						case 'image':
							return $videolist_row->image ;
							break;
						case 'note':
							return $videolist_row->note ;
							break;
					}
				}
				return $videolist_row->listname;
			break;
			
			case 'cols':
				return $theme_row->cols;
				break;
		
			case 'listname':
				return $videolist_row->listname;
			break;
		
			case 'videotitle':
				$title=str_replace('"','&quot;',YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list));
				$title=YouTubeGalleryMisc::html2txt($title);
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					$title='<div id="YoutubeGalleryVideoTitle'.$videolist_row->id.'">'.$title.'</div>';
				}
				
				if($params!='')
				{
					$pair=explode(',',$params);
					$words=(int)$pair[0];
					if(isset($pair[1]))
						$chars=(int)$pair[1];
					else
						$chars=0;
					
					$title=YoutubeGalleryLayoutRenderer::PrepareDescription($title, $words, $chars);
				}
				
				return $title;
			break;
		
			case 'videodescription':
				$description=str_replace('"','&quot;',YoutubeGalleryLayoutRenderer::getDescriptionByVideoID($videoid,$gallery_list));
				$description=YouTubeGalleryMisc::html2txt($description);
				
				if($params!='')
				{
					$pair=explode(',',$params);
					$words=(int)$pair[0];
					if(isset($pair[1]))
						$chars=(int)$pair[1];
					else
						$chars=0;
					
					$description=YoutubeGalleryLayoutRenderer::PrepareDescription($description, $words, $chars);
				}
				
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					$description='<div id="YoutubeGalleryVideoDescription'.$videolist_row->id.'">'.$description.'</div>';
				}
				
				return $description;
			break;
		
			case 'videoplayer':
				$pair=explode(',',$params);
				
				if($params!='')
					$playerwidth=(int)$pair[0];
				else
					$playerwidth=$width;
					
				
				if(isset($pair[1]))
					$playerheight=(int)$pair[1];
				else
					$playerheight=$height;
				
				
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					//Update Player - without page reloading
					YoutubeGalleryLayoutRenderer::addHotReloadScript($gallery_list,$playerwidth,$playerheight,$videolist_row, $theme_row);
				}
				return YoutubeGalleryLayoutRenderer::ShowActiveVideo($gallery_list,$playerwidth,$playerheight,$videoid,$videolist_row, $theme_row);
		
			break;
		
			case 'navigationbar':
				//classictable
				$pair=explode(',',$params);
				
				if((int)$pair[0]>0)
					$number_of_columns=(int)$pair[0];
				else
					$number_of_columns=(int)$theme_row->cols;
					
					
				if($number_of_columns<1)
					$number_of_columns=3;
			
				if($number_of_columns>10)
					$number_of_columns=10;
					
				
				if(isset($pair[1]))
					$navbarwidth=(int)$pair[1];
				else
					$navbarwidth=$width;
					
				return YoutubeGalleryLayoutRenderer::ClassicNavTable($gallery_list, $navbarwidth, $number_of_columns, $videolist_row, $theme_row, $AllowPagination, $videoid,$custom_itemid);
			break;
		
			case 'thumbnails':
				//simple list
				return YoutubeGalleryLayoutRenderer::NavigationList($gallery_list, $videolist_row, $theme_row, $AllowPagination, $videoid,$custom_itemid);
			break;
		
			case 'count':
				if ($params=='all')
					return $videolist_row->TotalVideos;
				else
					return count($gallery_list);
			break;
		
			case 'pagination':
				return YoutubeGalleryLayoutRenderer::Pagination($theme_row,$gallery_list,$width,$total_number_of_rows);
			
				break;
			
			case 'width':
				return $width;
			break;
		
			case 'height':
				return $height;
			break;
			
			case 'instanceid':
				return $videolist_row->id;
			break;
			
			case 'videoid':
				return $videoid;
			break;
			
			case 'link':
				return  $link=YouTubeGalleryMisc::full_url($_SERVER);//$_SERVER['HTTP_REFERER'];
			break;
				
			case 'social':
				return YoutubeGalleryLayoutRenderer::SocialButtons('window.location.href','yg',$params,$videolist_row->id,$videoid);
			break;
			
			case 'video':
				
				$pair=explode(':',$params);
				if($pair[0]!="")
				{
					$options='';
					if(isset($pair[1]))
						$options=$pair[1];
						
					$tableFields=array('title','description',
					  'imageurl','videoid','videosource','publisheddate','duration',
					  'rating_average','rating_max','rating_min','rating_numRaters',
					  'keywords','commentcount','likes','dislikes','playlist');
					
					
					$listitem=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list,true);//YoutubeGalleryLayoutRenderer::object_to_array($videolist_row);
					
					
					return YoutubeGalleryLayoutRenderer::getTumbnailData($pair[0], "", "", $listitem,$tableFields,$options,$theme_row,$gallery_list,$videolist_row);
				}
				
			break;
		
		}//switch($fld)
		
	}//function
	
	public static function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = YoutubeGalleryLayoutRenderer::object_to_array($value);
        }
        return $result;
    }
    return $data;
}
/*
	public static function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {

			//return array_map(YoutubeGalleryLayoutRenderer::objectToArray, $d);
		//}
		//else {
		//	// Return array
		//	return $d;
		//}
	//}
*/	
	public static function isEmpty($fld, &$videolist_row, &$theme_row, $gallery_list, $videoid, $AllowPagination, $total_number_of_rows)
	{
		
		$fields_theme=array('bgcolor','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		if(in_array($fld,$fields_theme))
		{
			$theme_row_array = get_object_vars($theme_row);
			if($theme_row_array[$fld]=='')
				return true;
			else
				return false;
		}
		
		
		switch($fld)
		{
			case 'cols':
				return false;
			case 'social':
				return false;
			break;
			case 'link':
				return false;
			case 'video':
				return false;
			break;
		
		
			case 'videolist':
				if($videolist_row->listname=='')
					return true;
				else
					return false;
			break;
		
			case 'listname':
				if($videolist_row->listname=='')
					return true;
				else
					return false;
			break;
		
			case 'videotitle':
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
					return false;
				
				$title=YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list);
				if($title=='')
					return true;
				else
					return false;
			break;
		
			case 'videodescription':
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
					return false;
				
				$description=YoutubeGalleryLayoutRenderer::getDescriptionByVideoID($videoid,$gallery_list);
				if($description=='')
					return true;
				else
					return false;
			break;
		
			case 'videoplayer':
				
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
					return false;
				
				return !$videoid;
			break;
		
			case 'navigationbar':
				if($total_number_of_rows==0)
					return true; //hide nav bar
				elseif($total_number_of_rows>0)
					return false;
			break;
		
			case 'thumbnails':
				if($total_number_of_rows==0)
					return true; //hide nav bar
				elseif($total_number_of_rows>0)
					return false;
			break;
		
			case 'mediafolder':
				if($theme_row->mediafolder=='')
					return true;
				else
					return false;
			break;
		
			case 'count':
				return ($total_number_of_rows>0 ? false : true);
			break;
		
			case 'pagination':
				return ($total_number_of_rows>5 and $AllowPagination ? false : true);
			break;
		
			case 'width':
				return false;
			break;
		
			case 'height':
				return false;
			break;
			
			case 'instanceid':
				return false;
			
			case 'videoid':
				return false;
			
			break;
		
		}
		return true;

		
	}
	
	public static function render($htmlresult, &$videolist_row, &$theme_row, $gallery_list, $width, $height, $videoid, $total_number_of_rows,$custom_itemid=0)
	{
		if(!isset($theme_row))
			return 'Theme not selected';
		
		if(!isset($videolist_row))
			return 'Video List not selected';
		
		if(strpos($htmlresult,'[pagination')===false)
			$AllowPagination=false;
		else
			$AllowPagination=true;
		
		$fields_generated=array('link','cols','width','height','video', 'videolist', 'listname','videotitle','videodescription','videoplayer','navigationbar','thumbnails','count','pagination','instanceid','videoid','mediafolder','social');
		$fields_theme=array('bgcolor','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon');
		
		$fields_all=array_merge($fields_generated, $fields_theme);
		

		foreach($fields_all as $fld)
		{
			
			$isEmpty=YoutubeGalleryLayoutRenderer::isEmpty($fld,$videolist_row,$theme_row,$gallery_list,$videoid,$AllowPagination,$total_number_of_rows);
						
			$ValueOptions=array();
			$ValueList=YoutubeGalleryLayoutRenderer::getListToReplace($fld,$ValueOptions,$htmlresult,'[]');
		
			$ifname='[if:'.$fld.']';
			$endifname='[endif:'.$fld.']';
						
			if($isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
				
					if(!($startif_===false))
					{
						
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);
			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
							
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$vlu= YoutubeGalleryLayoutRenderer::getValue($fld,$ValueOption,$videolist_row, $theme_row,$gallery_list,$width,$height,$videoid,$AllowPagination,$total_number_of_rows,$custom_itemid);
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}// IF NOT
					
			$ifname='[ifnot:'.$fld.']';
			$endifname='[endifnot:'.$fld.']';
						
			if(!$isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$htmlresult=str_replace($ValueListItem,'',$htmlresult);
							
				do{
					$textlength=strlen($htmlresult);
						
					$startif_=strpos($htmlresult,$ifname);
					if($startif_===false)
						break;
		
					if(!($startif_===false))
					{
						$endif_=strpos($htmlresult,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
						}	
					}
					
				}while(1==1);

			}
			else
			{
				$htmlresult=str_replace($ifname,'',$htmlresult);
				$htmlresult=str_replace($endifname,'',$htmlresult);
				$vlu='';			
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					
					$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
					$i++;
				}
			}
	
		}//foreach($fields as $fld)
		
		return $htmlresult;
		
	}
	
	public static function getListToReplace($par,&$options,&$text,$qtype)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, $qtype[0].$par.':', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, $qtype[1], $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=trim(substr($text,$ps+$l,$pe-$ps-$l));
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		//for these with no parameters
		$ps=strpos($text, $qtype[0].$par.$qtype[1]);
		if(!($ps===false))
		{
			$options[]='';
			$fList[]=$qtype[0].$par.$qtype[1];
		}
		
		return $fList;
	}
	
	public static function getPagination($num,$limitstart,$limit,&$theme_row)
	{
		
				$AddAnchor=false;
				if($theme_row->openinnewwindow==2 or $theme_row->openinnewwindow==3)
				{
					$AddAnchor=true;
				}
				
					require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'pagination.php');
					
					$thispagination = new YGPagination($num, $limitstart, $limit, '', $AddAnchor );
				
				return $thispagination;
	}
	
	public static function makeLink(&$listitem, $rel, &$aLinkURL, $videolist_row_id, $theme_row_id,$custom_itemid=0)
	{

		
		$videoid=$listitem['videoid'];
	
	
		$theview='youtubegallery';
		
			
		$juri=new JURI();
		$WebsiteRoot=$juri->root();
		
		if($WebsiteRoot[strlen($WebsiteRoot)-1]!='/') //Root must have slash / in the end
			$WebsiteRoot.='/';

		$URLPath=$_SERVER['REQUEST_URI']; // example:  /index.php'
		
		$pattern = '/[^\pL\pN$-_.+!*\'\(\)\,\{\}\|\\\\\^\~\[\]\<\>\#\%\"\;\/\?\:\@\&\=\.]/u';
		$URLPath = preg_replace($pattern, '', $URLPath);
		$URLPath = preg_replace('/"(\n.)+?"/m','', $URLPath);
		$URLPath = str_replace('"','', $URLPath);
		
		
		
		if($URLPath!='')
		{
			$p=strpos($URLPath,'?');
			if(!($p===false))
				$URLPath=substr($URLPath,0,$p);
		}
		
		
		$URLPathSecondPart='';
		
		
		
		
		if($URLPath!='')
		{
			//Path (URI) must be without leadint /
			if($URLPath!='')
			{
				if($URLPath[0]!='/')
					$URLPath=''.$URLPath;
				
			}
	
			
		}//if($URLPath!='')
			
		
		if($custom_itemid!=0)
		{
			//For Shadow/Light Boxes
			$aLink=$WebsiteRoot.'index.php?option=com_youtubegallery&view='.$theview;
			$aLink.='&Itemid='.$custom_itemid;
			$aLink.='&videoid='.$videoid;
			$aLink=JRoute::_($aLink);
			
			return $aLink;
		}
		elseif($rel!='')
		{
			//For Shadow/Light Boxes
			$aLink=$WebsiteRoot.'index.php?option=com_youtubegallery&view='.$theview;
			$aLink.='&listid='.$videolist_row_id;
			$aLink.='&themeid='.$theme_row_id;
			$aLink.='&videoid='.$videoid;
			
			return $aLink;

		}
		/////////////////////////////////		

		
		if(JFactory::getApplication()->input->getCmd('option')=='com_youtubegallery' and JFactory::getApplication()->input->getCmd('view')==$theview )
		{
			//For component only
			
			$aLink='index.php?option=com_youtubegallery&view='.$theview.'&Itemid='.JFactory::getApplication()->input->getInt('Itemid',0);
			
			$aLink.='&videoid='.$videoid;
			
			$aLink=JRoute::_($aLink);
			
			if(strpos($aLink,'ygstart')===false and JFactory::getApplication()->input->getInt('ygstart')!=0)
			{
				if(strpos($aLink,'?')===false)
					$aLink.='?ygstart='.JFactory::getApplication()->input->getInt('ygstart');
				else
					$aLink.='&ygstart='.JFactory::getApplication()->input->getInt('ygstart');
			}

			return $aLink;
		}
		

		/////////////////////////////////
		
			$URLQuery= $_SERVER['QUERY_STRING'];
			$URLQuery= str_replace('"','', $URLQuery);
			
			
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'videoid');
			
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onclick');
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onmouseover');
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onmouseout');
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onmouseeenter');
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onmousemove');
			$URLQuery=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($URLQuery, 'onmouseleave');
				
			$aLink=$URLPath.$URLPathSecondPart;
			

			
			$aLink.=($URLQuery!='' ? '?'.$URLQuery : '' );

			
			
			if(strpos($aLink,'?')===false)
				$aLink.='?';
			else
				$aLink.='&';
					

			$allowsef=YouTubeGalleryMisc::getSettingValue('allowsef');
			if($allowsef==1)
			{
				$aLink=YoutubeGalleryLayoutRenderer::deleteURLQueryOption($aLink, 'video');
				$aLink.='video='.$listitem['alias'];
			}
			else
				$aLink.='videoid='.$videoid;
				
			


			if(strpos($aLink,'ygstart')===false and JFactory::getApplication()->input->getInt('ygstart')!=0)
				$aLink.='&ygstart='.JFactory::getApplication()->input->getInt('ygstart');

			return JRoute::_($aLink);
					
		
	}//function
	
	public static function deleteURLQueryOption($urlstr, $opt)
	{
		$url_first_part='';
		$p=strpos($urlstr,'?');
		if(!($p===false))
		{
			$url_first_part	= substr($urlstr,0,$p);
			$urlstr	= substr($urlstr,$p+1);
		}

		$params = array();
		
		$urlstr=str_replace('&amp;','&',$urlstr);
		
		$query=explode('&',$urlstr);
		
		$newquery=array();					

		for($q=0;$q<count($query);$q++)
		{
			$p=stripos($query[$q],$opt.'=');
			if($p===false or ($p!=0 and $p===false))
				$newquery[]=$query[$q];
		}
		
		if($url_first_part!='' and count($newquery)>0)
			$urlstr=$url_first_part.'?'.implode('&',$newquery);
		elseif($url_first_part!='' and count($newquery)==0)
			$urlstr=$url_first_part;
		else
			$urlstr=implode('&',$newquery);
		
		return $urlstr;
	}
	

	

	
	
	
	
	
	

	
	public static function getDescriptionByVideoID($videoid,&$gallery_list)
	{
		if(isset($gallery_list) and count($gallery_list)>0)
		{
				foreach($gallery_list as $g)
				{
						if($g['videoid']==$videoid)
								return $g['description'];
				}
		}
		
		return '';
	}
	
	

	
	

	public static function curPageURL($add_REQUEST_URI=true)
	{
		$pageURL = '';
		
			$pageURL .= 'http';
			
			if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			
			$pageURL .= "://";
			
			if (isset($_SERVER["HTTPS"]))
			{
				if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
				} else {
					$pageURL .= $_SERVER["SERVER_NAME"];
				}
			}
			else
				$pageURL .= $_SERVER["SERVER_NAME"];
			
			if($add_REQUEST_URI)
			{
				//clean Facebook staff
				$uri=$_SERVER["REQUEST_URI"];
				if(!(strpos($uri,'fb_action_ids=')===false))
				{
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'fb_action_ids');
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'fb_action_types');
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'fb_source');
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'action_object_map');
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'action_type_map');
					$uri= YoutubeGalleryLayoutRenderer::deleteURLQueryOption($uri, 'action_ref_map');
				}
				$pageURL .=$uri;
			}
		
		return $pageURL;
	}
	
	
	public static function Pagination(&$theme_row,$the_gallery_list,$width,$total_number_of_rows)
	{
		$mainframe = JFactory::getApplication();
			
		if(((int)$theme_row->customlimit)==0)
		{
			//$limit=0; // UNLIMITED
			//No pagination - all items shown
			return '';
		}
		else
			$limit = (int)$theme_row->customlimit;
			
		
		
			
		$limitstart = JFactory::getApplication()->input->getInt('ygstart', 0);
				
		$pagination=YoutubeGalleryLayoutRenderer::getPagination($total_number_of_rows,$limitstart,$limit,$theme_row);
			
		$paginationcode='<form action="" method="post">';
		
		if($limit==0)
		{
			$paginationcode.='
				<table cellspacing="0" style="padding:0px;width:'.$width.'px;border-style: none;"  border="0" >
				<tr style="height:30px;border-style: none;border-width:0px;">
				<td style="text-align:left;width:140px;vertical-align:middle;border: none;">'.JText::_( 'SHOW' ).': '.$pagination->getLimitBox("").'</td>
				<td style="text-align:right;vertical-align:middle;border: none;"><div class="pagination">'.$pagination->getPagesLinks().'</div></td>
				</tr>
				</table>
				';
		}
		else
		{
			$paginationcode.='<div class="pagination">'.$pagination->getPagesLinks().'</div>';
			
		}
				
		$paginationcode.='</form>';
		
		return $paginationcode;
		
	}
	
	
	public static function NavigationList($the_gallery_list, &$videolist_row, &$theme_row, $AllowPagination, $videoid,$custom_itemid=0)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		$misc=new YouTubeGalleryMisc;

		$misc->videolist_row =$videolist_row;
		$misc->theme_row =$theme_row;
		
		if($theme_row->prepareheadtags>0)
		{
			$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
			$document = JFactory::getDocument();
			
		}
				
		$catalogresult='';
		$paginationcode='';
	
		$gallery_list=$the_gallery_list;
		
		
		$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
		
		
		$misc->RefreshVideoData($gallery_list,$getinfomethod);
		
	
		$tr=0;
		$count=0;
		
		
		$item_index=1;
	
        foreach($gallery_list as $listitem)	
        {
		if(strpos($listitem['title'],'***Video not found***')===false)
		{
		
				$bgcolor=$theme_row->bgcolor;
				
				$aLinkURL='';
				
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					//$title=str_replace('"','*q*',$listitem['title']);
					//$description=str_replace('"','*q*',$listitem['description']);
					//$title=str_replace('\'','*sq*',$title);
					//$description=str_replace('\'','*sq*',$description);
					
					
					//$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\',\''.$title.'\',\''.$description.'\')';
					$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\','.$listitem['id'].')';
				}
				else
					$aLink=YoutubeGalleryLayoutRenderer::makeLink($listitem, $theme_row->rel, $aLinkURL, $videolist_row->id, $theme_row->id,$custom_itemid);
				


				$isForShadowBox=false;
				
				if(isset($theme_row))
				{
					if($theme_row->rel!='')
						$isForShadowBox=true;
				}
				
				if($isForShadowBox and $theme_row->rel!='' and $theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
						$aLink.='&tmpl=component';

				if($theme_row->hrefaddon!='' and $theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
				{
					$hrefaddon=str_replace('?','',$theme_row->hrefaddon);
					if($hrefaddon[0]=='&')
						$hrefaddon=substr($hrefaddon,1);
					
					if(strpos($aLink,$hrefaddon)===false)
					{
					
						if(strpos($aLink,'?')===false)
							$aLink.='?';
						else
							$aLink.='&';

						
						$aLink.=$hrefaddon;
					}
				}
				

				if($theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
				{
					if(strpos($aLink,'&amp;')===false)
						$aLink=str_replace('&','&amp;',$aLink);
						
					$aLink=$aLink.(($theme_row->openinnewwindow==2 OR $theme_row->openinnewwindow==3) ? '#youtubegallery' : '');
				}
				
					//to apply shadowbox
					//do not route the link
										
					$aHrefLink='<a href="'.$aLink.'"'
						.($theme_row->rel!='' ? ' rel="'.$theme_row->rel.'"' : '')
						.(($theme_row->openinnewwindow==1 OR $theme_row->openinnewwindow==3) ? ' target="_blank"' : '')
						.'>';
				
				$thumbnail_item=YoutubeGalleryLayoutRenderer::renderThumbnailForNavBar($aHrefLink,$aLink,$videolist_row, $theme_row,$listitem, $videoid,$item_index,$gallery_list);
						
				if($thumbnail_item!='')
				{
					$catalogresult.=$thumbnail_item;
					$count++;
				}
			$item_index++;
		}
	}//for

		return $catalogresult;
	}
	
	public static function ClassicNavTable($the_gallery_list,$width,$number_of_columns, &$videolist_row, &$theme_row, $AllowPagination, $videoid,$custom_itemid=0)
	{
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		$misc=new YouTubeGalleryMisc;
		$misc->videolist_row =$videolist_row;
		$misc->theme_row =$theme_row;
		
		if($theme_row->prepareheadtags>0)
		{
			$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
			$document = JFactory::getDocument();
			
		}
				
		$catalogresult='';
		$paginationcode='';
		$w_str='width:'.$width.(strpos($width,'%')===false ? 'px' : '').';';
		$catalogresult.='<table cellspacing="0" '.($theme_row->navbarstyle!='' ? 'style="'.$w_str.'padding:0;border:none;'.$theme_row->navbarstyle.'" ' : 'style="'.$w_str.'padding:0;border:none;margin:0 auto;"').'>
		<tbody>';
		
		$column_width=floor(100/$number_of_columns).'%';

		$gallery_list=$the_gallery_list;
		
		$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
		
		
		$misc->RefreshVideoData($gallery_list,$getinfomethod);
		
	
		$tr=0;
		$count=0;
		$bgcolor=$theme_row->bgcolor;
	
	$item_index=1;
        foreach($gallery_list as $listitem)	
        {
		if(strpos($listitem['title'],'***Video not found***')===false)
		{
		
			if($getinfomethod=='js')
			{
		
				
				$thumbnail_item='updater';
			
				if($tr==0)
					$catalogresult.='<tr style="border:none;" >';
				
				$catalogresult.=
					'<td style="width:'.$column_width.';vertical-align:top;text-align:center;border:none;'.($bgcolor!='' ? ' background-color: #'.$bgcolor.';' : '').'">'
					.$thumbnail_item.'</td>';
				
				
				$tr++;
				if($tr==$number_of_columns)
				{
					$catalogresult.='
							</tr>
				';
					if($count+1<count($gallery_list))
						$catalogresult.='
						<tr style="border:none;"><td colspan="'.$number_of_columns.'" style="border:none;" ><hr'.($theme_row->linestyle!='' ? ' style="'.$theme_row->linestyle.'" ' : '').' /></td></tr>';
						
					$tr	=0;
				}
				$count++;
					
					
			
			}
			else
			{
				$aLinkURL='';
				
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					//$title=str_replace('"','ygdoublequote',$listitem['title']);
					//$description=str_replace('"','ygdoublequote',$listitem['description']);
					//$title=str_replace('\'','ygsinglequote',$title);
					//$description=str_replace('\'','ygsinglequote',$description);
					
					//$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\',\''.$title.'\',\''.$description.'\')';
					$aLink='javascript:YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(\''.$listitem['videoid'].'\',\''.$listitem['videosource'].'\','.$listitem['id'].')';
				}
				else
					$aLink=YoutubeGalleryLayoutRenderer::makeLink($listitem, $theme_row->rel, $aLinkURL, $videolist_row->id, $theme_row->id,$custom_itemid);
				
				$isForShadowBox=false;
				
				if(isset($theme_row))
				{
					if($theme_row->rel!='')
						$isForShadowBox=true;
				}
				
				if($isForShadowBox and $theme_row->rel!='' and $theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
						$aLink.='&tmpl=component';

				if($theme_row->hrefaddon!='' and $theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
				{
					$hrefaddon=str_replace('?','',$theme_row->hrefaddon);
					if($hrefaddon[0]=='&')
						$hrefaddon=substr($hrefaddon,1);
					
					if(strpos($aLink,$hrefaddon)===false)
					{
					
						if(strpos($aLink,'?')===false)
							$aLink.='?';
						else
							$aLink.='&';

						
						$aLink.=$hrefaddon;
					}
				}
				
				

				if($theme_row->openinnewwindow!=4 and $theme_row->openinnewwindow!=5)
				{
					if(strpos($aLink,'&amp;')===false)
						$aLink=str_replace('&','&amp;',$aLink);
						
					$aLink=$aLink.(($theme_row->openinnewwindow==2 OR $theme_row->openinnewwindow==3) ? '#youtubegallery' : '');
				}
				
					//to apply shadowbox
					//do not route the link
										
					$aHrefLink='<a href="'.$aLink.'"'
						.($theme_row->rel!='' ? ' rel="'.$theme_row->rel.'"' : '')
						.(($theme_row->openinnewwindow==1 OR $theme_row->openinnewwindow==3) ? ' target="_blank"' : '')
						.'>';

						
				$thumbnail_item=YoutubeGalleryLayoutRenderer::renderThumbnailForNavBar($aHrefLink,$aLink,$videolist_row, $theme_row,$listitem, $videoid,$item_index,$gallery_list);
				
				
				if($thumbnail_item!='')
				{
					if($tr==0)
						$catalogresult.='<tr style="border:none;" >';
				
					$catalogresult.=
					'<td style="width:'.$column_width.';vertical-align:top;text-align:center;border:none;'.($bgcolor!='' ? ' background-color: #'.$bgcolor.';' : '').'">'
					.$thumbnail_item.'</td>';
				
				
					$tr++;
					if($tr==$number_of_columns)
					{
						$catalogresult.='
							</tr>
						';
						if($count+1<count($gallery_list))
							$catalogresult.='
							<tr style="border:none;"><td colspan="'.$number_of_columns.'" style="border:none;" ><hr'.($theme_row->linestyle!='' ? ' style="'.$theme_row->linestyle.'" ' : '').' /></td></tr>';
						
						$tr	=0;
					}
					$count++;
				}
				
				
			}	
			$item_index++;
		}		
        
		
	}
		
		if($tr>0)
				$catalogresult.='<td style="border:none;" colspan="'.($number_of_columns-$tr).'">&nbsp;</td></tr>';
	  	

       $catalogresult.='</tbody>
	   
    </table>
	
	';
		return $catalogresult;
	}
	
		
	
	public static function renderThumbnailForNavBar($aHrefLink,$aLink,&$videolist_row, &$theme_row,$listitem, $videoid,$item_index, &$gallery_list)
	{
		$result='';
		
		
		$thumbnail_layout='';
		
		
		//------------------------------- title
		$thumbtitle='';
		if($listitem['title']!='')
		{
			$thumbtitle=str_replace('"','',$listitem['title']);
			$thumbtitle=str_replace('\'','&rsquo;',$listitem['title']);
							
			if(strpos($thumbtitle,'&amp;')===false)
				$thumbtitle=str_replace('&','&amp;',$thumbtitle);
		}
	
		//------------------------------- add title and description hidden div containers if needed
		
		
		

		//------------------------------- end of image tag
		
		if($theme_row->customnavlayout!='')
		{
			$result=YoutubeGalleryLayoutRenderer::renderThumbnailLayout($theme_row->customnavlayout,$listitem,$aHrefLink,$aLink, $videoid,$theme_row,$item_index,$gallery_list,$videolist_row);
		}
		else
		{
			$thumbnail_layout='[a][image][/a]'; //with link
			
			if($theme_row->showtitle)
			{
				if($thumbtitle!='')
					$thumbnail_layout.='<br/>'.($theme_row->thumbnailstyle=='' ? '<span style="font-size: 8pt;" >[title]</span>' : '<div style="'.$theme_row->thumbnailstyle.'">[title]</div>');
			}
			$result=YoutubeGalleryLayoutRenderer::renderThumbnailLayout($thumbnail_layout,		$listitem,$aHrefLink,$aLink, $videoid,$theme_row,$item_index,$gallery_list,$videolist_row);
		}
		
		if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
		{
			$result.='<div id="YoutubeGalleryThumbTitle'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['title'].'</div>';
			$result.='<div id="YoutubeGalleryThumbDescription'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['description'].'</div>';
			$result.='<div id="YoutubeGalleryThumbLink'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['link'].'</div>';
			$result.='<div id="YoutubeGalleryThumbStartSecond'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['startsecond'].'</div>';
			$result.='<div id="YoutubeGalleryThumbEndSecond'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['endsecond'].'</div>';
			
			if($listitem['custom_imageurl']!='' and strpos($listitem['custom_imageurl'],'#')===false)
				$result.='<div id="YoutubeGalleryThumbCustomImage'.$videolist_row->id.'_'.$listitem['id'].'" style="display:none;visibility:hidden;">'.$listitem['custom_imageurl'].'</div>';
		}
		
		return $result;
		
	}
	

	
	public static function PrepareImageTag(&$listitem,$options,&$theme_row,$as_tag=true)
	{
		
		$imagetag='';
		
		//image title
		$thumbtitle=$listitem['title'];
		if($thumbtitle=='')
		{
			$mydoc = JFactory::getDocument();
			$thumbtitle=str_replace('"','',$mydoc->getTitle());
		}
		
		$thumbtitle=str_replace('"','',$thumbtitle);
		$thumbtitle=str_replace('\'','&rsquo;',$thumbtitle);
							
		if(strpos($thumbtitle,'&amp;')===false)
			$thumbtitle=str_replace('&','&amp;',$thumbtitle);
		
		//image src
		if($listitem['imageurl']=='')
		{
			if($as_tag)
			{
				$imagetag='<div style="';
					
				if($theme_row->thumbnailstyle!='')
					$imagetag.=$theme_row->thumbnailstyle;
				else
					$imagetag.='border:1px solid red;background-color:white;';
						
				if(strpos($theme_row->thumbnailstyle,'width')===false)
					$imagetag.='width:120px;height:90px;';
			
				$imagetag.='"></div>';
			}
			else
				$imagetag='';
			
		}
		else
		{
			if($listitem['imageurl']=='flvthumbnail' and $listitem['custom_imageurl']=='')
			{
				if($as_tag)
				{
					require_once('flv.php');
					$linkTarget=(($theme_row->openinnewwindow==1 OR $theme_row->openinnewwindow==3) ? '_blank' : '_self');
					$imagetag=VideoSource_FLV::getThumbnailCode($listitem['link'], $theme_row->thumbnailstyle,$aLink,$linkTarget);
				}
				else
					$imagetag='';
			}
			else
			{
				if($listitem['imageurl']=='flvthumbnail' and $listitem['custom_imageurl']!='')
				{
					$imagelink = $listitem['custom_imageurl'];
				}
				else
				{
					$images=explode(',',$listitem['imageurl']);
					$index=0;
					if($options!='')
					{
						$index=(int)$options;
						if($index<0)
							$index=0;
						if($index>=count($images))
							$index=count($images)-1;
						$imagelink= $images[$index];
					}
					else
					{
						
						if(!(strpos($listitem['custom_imageurl'],'#')===false))
						{
							$index=(int)(str_replace('#','',$listitem['custom_imageurl']));
							if($index<0)
								$index=0;
							if($index>=count($images))
								$index=count($images)-1;
						}
						else
							$imagelink = $listitem['custom_imageurl'];
						
					}
					$imagelink= $images[$index];				
				}
				
				if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
						$imagelink=str_replace('http://','https://',$imagelink);
					else
						$imagelink=str_replace('https://','http://',$imagelink);
				
				if($as_tag)
				{
					$imagetag='<img src="'.$imagelink.'"'.($theme_row->thumbnailstyle!='' ? ' style="'.$theme_row->thumbnailstyle.'"' : ' style="border:none;"');
			
			
					if(strpos($theme_row->thumbnailstyle,'width')===false)
						$imagetag.=' width="120" height="90"';
						
					$imagetag.=' alt="'.$thumbtitle.'" title="'.$thumbtitle.'"';

					$imagetag.=' />';
				}
				else
					$imagetag=$imagelink;
				
			
				if($theme_row->prepareheadtags==1 or $theme_row->prepareheadtags==3)//thumbnails or both
				{
					$document = JFactory::getDocument();
					$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
					
					$imagelink=(strpos($imagelink,'http://')===false and strpos($imagelink,'https://')===false  ? $curPageUrl.'/' : '').$imagelink;
					
					$document->addCustomTag('<link rel="image_src" href="'.$imagelink.'" />'); //all thumbnails
				}
				
			}
		}
		
		return $imagetag;
	}
	
	public static function renderThumbnailLayout($thumbnail_layout,$listitem,$aHrefLink,$aLink, $videoid,&$theme_row,$item_index,&$gallery_list,&$videolist_row)
	{
		$fields=array('width','height','image','link','a','/a','link','title','description',
					  'imageurl','videoid','videosource','publisheddate','duration',
					  'rating_average','rating_max','rating_min','rating_numRaters',
					  'statistics_favoriteCount','viewcount','favcount','keywords','isactive','commentcount','likes','dislikes','channel','social',
					  'odd','even','videolist','inwatchgroup'
					  );
		
		
		$tableFields=array('title','description',
					  'imageurl','videoid','videosource','publisheddate','duration',
					  'rating_average','rating_max','rating_min','rating_numRaters',
					  'keywords','commentcount','likes','dislikes');
		
		
		foreach($fields as $fld)
		{
		
			$imageFound=(strlen($listitem['imageurl'])>0);// or strlen($listitem['custom_imageurl'])>0);
			
			$isEmpty=YoutubeGalleryLayoutRenderer::isThumbnailDataEmpty($fld,$listitem,$tableFields,$imageFound, $videoid, $item_index,$videolist_row);
						
			$ValueOptions=array();
			$ValueList=YoutubeGalleryLayoutRenderer::getListToReplace($fld,$ValueOptions,$thumbnail_layout,'[]');
		
			$ifname='[if:'.$fld.']';
			$endifname='[endif:'.$fld.']';
						
			if($isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$thumbnail_layout=str_replace($ValueListItem,'',$thumbnail_layout);
							
				do{
					$textlength=strlen($thumbnail_layout);
						
					$startif_=strpos($thumbnail_layout,$ifname);
					if($startif_===false)
						break;
				
					if(!($startif_===false))
					{
						
						$endif_=strpos($thumbnail_layout,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$thumbnail_layout=substr($thumbnail_layout,0,$startif_).substr($thumbnail_layout,$p);
						}	
					}
					
				}while(1==1);
			}
			else
			{
				$thumbnail_layout=str_replace($ifname,'',$thumbnail_layout);
				$thumbnail_layout=str_replace($endifname,'',$thumbnail_layout);
							
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$options=$ValueOptions[$i];
					$vlu=YoutubeGalleryLayoutRenderer::getTumbnailData($fld, $aHrefLink, $aLink, $listitem, $tableFields,$options,$theme_row,$gallery_list,$videolist_row); //NEW 
					$thumbnail_layout=str_replace($ValueList[$i],$vlu,$thumbnail_layout);
					$i++;
				}
			}// IF NOT
					
			$ifname='[ifnot:'.$fld.']';
			$endifname='[endifnot:'.$fld.']';
						
			if(!$isEmpty)
			{
				foreach($ValueList as $ValueListItem)
					$thumbnail_layout=str_replace($ValueListItem,'',$thumbnail_layout);
							
				do{
					$textlength=strlen($thumbnail_layout);
						
					$startif_=strpos($thumbnail_layout,$ifname);
					if($startif_===false)
						break;
		
					if(!($startif_===false))
					{
						$endif_=strpos($thumbnail_layout,$endifname);
						if(!($endif_===false))
						{
							$p=$endif_+strlen($endifname);	
							$thumbnail_layout=substr($thumbnail_layout,0,$startif_).substr($thumbnail_layout,$p);
						}	
					}
					
				}while(1==1);

			}
			else
			{
				$thumbnail_layout=str_replace($ifname,'',$thumbnail_layout);
				$thumbnail_layout=str_replace($endifname,'',$thumbnail_layout);
				$vlu='';
				$i=0;
				foreach($ValueOptions as $ValueOption)
				{
					$thumbnail_layout=str_replace($ValueList[$i],$vlu,$thumbnail_layout);
					$i++;
				}
			}
	
		}//foreach($fields as $fld)
		
		return $thumbnail_layout;
		
	}
	

	
	public static function getTumbnailData($fld, $aHrefLink, $aLink, $listitem,&$tableFields,$options,&$theme_row,&$gallery_list,&$videolist_row) //NEW
	{
		$vlu='';

		switch($fld)
		{
			case 'width':
				
				$vlu=(int)$theme_row->width;
				if($vlu==0)
					$vlu=400;
			break;
		
			case 'height':
				
				$vlu=(int)$theme_row->height;
				if($vlu==0)
					$vlu=300;
			break;
			
			case 'image':
				$vlu=YoutubeGalleryLayoutRenderer::PrepareImageTag($listitem,$options,$theme_row,true);
			break;
		
			case 'imageurl':
				$vlu=YoutubeGalleryLayoutRenderer::PrepareImageTag($listitem,$options,$theme_row,false);
			break;
					
			case 'title':
				$vlu= str_replace('"','&quot;',$listitem['title']);
				
				
				if($options!='')
				{
					$pair=explode(',',$options);
					$words=(int)$pair[0];
					if(isset($pair[1]))
						$chars=(int)$pair[1];
					else
						$chars=0;
					
					$vlu=YoutubeGalleryLayoutRenderer::PrepareDescription($vlu, $words, $chars);
				}

			break;
		
			case 'description':
				
				
				$vlu= str_replace('"','&quot;',$listitem['description']);
				
				if($options!='')
				{
					$pair=explode(',',$options);
					$words=(int)$pair[0];
					if(isset($pair[1]))
						$chars=(int)$pair[1];
					else
						$chars=0;
					
					$vlu=YoutubeGalleryLayoutRenderer::PrepareDescription($vlu, $words, $chars);
				}
				
			break;

			case 'a':
				$vlu= $aHrefLink;
			break;
				
			case '/a':
				$vlu= '</a>';
			break;
					
			case 'link':
				if($options=='')
					$vlu= $aLink;
				elseif($options=='full')
				{
					if(strpos($aLink,'http://')!==false or strpos($aLink,'https://')!==false or strpos($aLink,'javascript:')!==false)
						$vlu= YoutubeGalleryLayoutRenderer::curPageURL(false).$aLink; //NEW
				}
			break;
		
			case 'viewcount':
				$vlu=(int)$listitem['statistics_viewCount'];
				
				if($options!='')
					$vlu= number_format ( $vlu, 0, '.', $options);

			break;
		
			case 'likes':
				$vlu=(int)$listitem['likes'];
				
				if($options!='')
					$vlu= number_format ( $vlu, 0, '.', $options);

			break;
		
			case 'dislikes':
				$vlu=(int)$listitem['dislikes'];
				
				if($options!='')
					$vlu= number_format ( $vlu, 0, '.', $options);

			break;
		
			case 'channel':
				
				if($options!='')
				{
					$pair=explode(',',$options);
					$f='channel_'.$pair[0];
						
					$vlu=$listitem[$f];
					if(isset($pair[1]))
					{
						if($pair[0]=='subscribers' or $pair[0]=='subscribed' or $pair[0]=='commentcount' or $pair[0]=='viewcount' or $pair[0]=='videocount')
						{
							$vlu= number_format ( $vlu, 0, '.', $pair[1]);
						}
					}
				}
				else
					$vlu='Tag "[channel:<i>parameter</i>]" must have a parameter. Example: [channel:viewcount]';
			break;
		
			case 'commentcount':
				$vlu=(int)$listitem['commentcount'];
				
				if($options!='')
					$vlu= number_format ( $vlu, 0, '.', $options);

			break;
		
			case 'favcount':
				$vlu=$listitem['statistics_favoriteCount'];
			break;
		
			case 'duration':
				
				if($options=='')
					$vlu= $listitem['duration'];
				else
				{
					$secs=(int)$listitem['duration'];
					$vlu=date($options,mktime(0,0,$secs));
				}

			break;
		
			case 'publisheddate':
				
				if($options=='')
					$vlu= $listitem['publisheddate'];
				else
					$vlu=date($options,strtotime($listitem['publisheddate']));

			break;
		
			case 'social':
				$l='';
				if(strpos($aLink,'javascript:')===false)
				{
					$a=YoutubeGalleryLayoutRenderer::curPageURL(false);
					if(strpos($aLink,$a)===false)
						$l='"'.$a.$aLink.'"';
					else
						$l='"'.$aLink.'"';

				}
				else
					$l='(window.location.href.indexOf("?")==-1 ?  window.location.href+"?videoid='.$listitem['videoid'].'" : window.location.href+"&videoid='.$listitem['videoid'].'" )';
				
				
				$vlu= YoutubeGalleryLayoutRenderer::SocialButtons($l,'ygt', $options,$listitem['id'],$listitem['videoid']);
				
			break;
		
			case 'videolist':
				
				if($options!='')
				{
					$pair=explode(',',$options);
					switch($pair[0])
					{
						case 'title':
							return $videolist_row->listname;
							break;
						
						case 'description':
							return $videolist_row->description;
							break;
						
						case 'author':
							return $videolist_row->author;
							break;
						
						case 'playlist':
							$pl=YoutubeGalleryLayoutRenderer::getPlaylistIdsOnly($gallery_list);
							$vlu=implode(',',$pl);
							break;
						
						case 'watchgroup':
							return $videolist_row->watchusergroup ;
							break;
						
						case 'authorurl':
							return $videolist_row->authorurl ;
							break;
						case 'image':
							return $videolist_row->image ;
							break;
						case 'note':
							return $videolist_row->note ;
							break;
					}
				}
				
				
				break;
			
			default:
				if(in_array($fld,$tableFields ))
					$vlu=$listitem[$fld];
			break;
		}
		
		return $vlu; 
	}

	
	public static function isThumbnailDataEmpty($fld,$listitem,&$tableFields,$ImageFound, $videoid, $item_index,&$videolist_row)
	{
		
		foreach($tableFields as $tf)
		{
			if($fld==$tf)
			{
				if($listitem[$tf]=='')
					return true;
				else
					return false;
			}
		}
		
		switch($fld)
		{
			case 'width':
				return false;
			break;
		
			case 'height':
				return false;
			break;
		
			case 'inwatchgroup':
				$u=(int)$videolist_row->watchusergroup;
				
				if($videolist_row->watchusergroup==0 or $videolist_row->watchusergroup==1)
					return false; //public videos
				
				//check is authorized or not
				$user = JFactory::getUser();
				$usergroups = $user->get('groups');
						
				if(in_array($videolist_row->watchusergroup,$usergroups))
				{
					//The user group has access
					//$this->isAutorized=true;
					return false;
				}
				return true;
			
				break;
			case 'odd':
				if ($item_index % 2 == 0)
					return true; //not odd
				else
					return false; //odd
  
				break;
			
			case 'even':
				if ($item_index % 2 == 0)
					return false; //even
				else
					return true; //not even
  
				break;
			
			case 'isactive':
				//$videoid=JFactory::getApplication()->input->getCmd('videoid');
				if($listitem['videoid']==$videoid)
					return false;
				else
					return true;
				break;
			
			case 'image':
				if(!$ImageFound)
					return true;
				else
					return false;
			break;
		
			case 'a':
					return false;
			break;
		
			case '/a':
					return false;
			break;
		
			case 'link':
					return false;
			break;
		
			case 'viewcount':
					return false;
			break;
			
			case 'social':
					return false;
			break;
		
			case 'videolist':
					return false;
			break;
		
			case 'favcount':
				if($listitem['statistics_favoriteCount']==0)
					return true;
				else
					return false;
			break;
		
			case 'channel':
					if($listitem['channel_username']=='')
						return true;
					else
						return false;
			break;
		
		}
		return true;

	}
	
	
	public static function ShowActiveVideo(&$gallery_list,$width,$height,$videoid, &$videolist_row, &$theme_row,$videosource='')
	{
		$VideoRow=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list);
		
		if($theme_row->changepagetitle!=3)
		{

			$mainframe = JFactory::getApplication();
			$sitename =$mainframe->getCfg('sitename');
			if($VideoRow)
				$title=$VideoRow['title'];
			else
				$title='';
				
				
			//$title=YoutubeGalleryLayoutRenderer::getTitleByVideoID($videoid,$gallery_list);
			
			$mydoc = JFactory::getDocument();
			
			
			if($theme_row->changepagetitle==0)
				$mydoc->setTitle($title.' - '.$sitename);
			elseif($theme_row->changepagetitle==1)
				$mydoc->setTitle($sitename.' - '.$title);
			elseif($theme_row->changepagetitle==2)
				$mydoc->setTitle($title);

		}
		
		
		$result='';
		
		$divstyle_player='';

		if($theme_row->playvideo==0)// and $vs=='youtube')
		{
				if($theme_row->openinnewwindow==4 or $theme_row->openinnewwindow==5)
				{
					$vs='youtube';
					$divstyle_player='display:none;';
				}
		}

		if($videoid)
		{
			$vpoptions=array();
			$vpoptions['width']=$width;
			$vpoptions['height']=$height;
			
			$vpoptions['videoid']=$videoid;
			$vpoptions['autoplay']=$theme_row->autoplay;
			$vpoptions['showinfo']=$theme_row->showinfo;
			$vpoptions['relatedvideos']=$theme_row->related;
			$vpoptions['repeat']=$theme_row->repeat;
			$vpoptions['allowplaylist']=$theme_row->allowplaylist;
			$vpoptions['border']=$theme_row->border;
			$vpoptions['color1']=$theme_row->color1;
			$vpoptions['color2']=$theme_row->color2;
		

			$vpoptions['controls']=$theme_row->controls;
			$vpoptions['playertype']=$theme_row->playertype;
			$vpoptions['youtubeparams']=$theme_row->youtubeparams;
		
			$vpoptions['fullscreen']=$theme_row->fullscreen;
				
			$list_index=YoutubeGalleryLayoutRenderer::getListIndexByVideoID($videoid,$gallery_list);

			//----------------------------------------------------------------------------
			$includeallplayers=false;
			$divstyle='';
			$divstyle_player='';

			
			
			//----------------------------------------------------------------------------
			if($videoid=='****youtubegallery-video-id****')
			{
				//Hot Switch
				if($videosource!='')
					$vs=$videosource;
				else
					$vs='';
					
				$image_link='';
				$startsecond='****youtubegallery-video-startsecond****';
				$endsecond='****youtubegallery-video-endsecond****';
			}
			elseif($list_index==-1)
			{
				$row=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list,false);
				if(!$row)
					return '';
				
				if($videosource!='')
					$vs=$videosource;
				else
					$vs=$row['videosource'];

				$image_link=$row['imageurl'];
				$startsecond=$row['startsecond'];
				$endsecond=$row['endsecond'];
			}
			else
			{
				if($videosource!='')
					$vs=$videosource;
				else
					$vs=$gallery_list[$list_index]['videosource'];

				$image_link=$gallery_list[$list_index]['imageurl'];
				$startsecond=$gallery_list[$list_index]['startsecond'];
				$endsecond=$gallery_list[$list_index]['endsecond'];
			}

			
			if($theme_row->prepareheadtags==2 or $theme_row->prepareheadtags==3)
			{
				
				if($image_link!='' and strpos($image_link,'#')===false)
				{

					$curPageUrl=YoutubeGalleryLayoutRenderer::curPageURL();
					$document = JFactory::getDocument();
					
					$image_link_array=explode(',',$image_link);
					if(count($image_link_array)>=3)
						$imagelink=$image_link_array[3];
					else
						$imagelink=$image_link_array[0];
					
					
					$imagelink=(strpos($imagelink,'http://')===false and strpos($image_link,'https://')===false  ? $curPageUrl.'/' : '').$imagelink;
					
					if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
						$imagelink=str_replace('http://','https://',$imagelink);
					
					$document->addCustomTag('<link rel="image_src" href="'.$imagelink.'" /><!-- active -->');
				}
				
				//add meta keywords
				if($vs=='youtube')
				{
					$doc =JFactory::getDocument();
					$doc->setMetaData( 'keywords', $VideoRow['keywords'] );//tags
					$doc->setMetaData( 'description', $VideoRow['description'] );
				}
			}
			
			if((int)$vpoptions['width']==0)
				$width=400;
			else
				$width=(int)$vpoptions['width'];
			
			
			if((int)$vpoptions['height']==0)
				$height=200;
			else
				$height=(int)$vpoptions['height'];

			
			
			
			if($includeallplayers or $vs=='break')
			{
					require_once('break.php');
					$result.='<div id="yg_player_break_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Break::renderBreakPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			
			if($includeallplayers or $vs=='vimeo')
			{
					require_once('vimeo.php');
					$result.='<div id="yg_player_vimeo_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Vimeo::renderVimeoPlayer($vpoptions, $width, $height, $videolist_row,$theme_row).'</div>';
			}	
			
			if($includeallplayers or $vs=='own3dtvlive')
			{	
					require_once('own3dtvlive.php');
					$result.='<div id="yg_player_own3dtvlive_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Own3DTvLive::renderOwn3DTvLivePlayer($vpoptions, $width, $height, $videolist_row,$theme_row).'</div>';
			}	
			
			if($includeallplayers or $vs=='own3dtvvideo')
			{	
				require_once('own3dtvvideo.php');
				$result.='<div id="yg_player_own3dtvvideo_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Own3DTvVideo::renderOwn3DTvVideoPlayer($vpoptions, $width, $height, $videolist_row,$theme_row).'</div>';
			}	
			
			if($includeallplayers or $vs=='youtube')
			{
				$result='<div id="yg_player_youtube_id-'.$videolist_row->id.'" '.$divstyle.'>';
				
						$pl=YoutubeGalleryLayoutRenderer::getPlaylistIdsOnly($gallery_list,$videoid,'youtube');
						$shorten_pl=array();
						$i=0;
						foreach($pl as $p)
						{
							$i++;
							if($i>20)
								break;
							$shorten_pl[]=$p;
						}
						$YoutubeVideoList=implode(',',$shorten_pl);

						$full_pl=YoutubeGalleryLayoutRenderer::getPlaylistIdsOnly($gallery_list,'','youtube',true);						
						$shorten_full_pl=array();
						$i=0;
						foreach($full_pl as $p)
						{
							$i++;
							if($i>20)
								break;
							$shorten_full_pl[]=$p;
						}
						$full_YoutubeVideoList=implode(',',$shorten_full_pl);
					
						if($vpoptions['youtubeparams']=='')
							$vpoptions['youtubeparams']='playlist='.$YoutubeVideoList;
						else
							$vpoptions['youtubeparams'].=';playlist='.$YoutubeVideoList;
							
						if($vpoptions['youtubeparams']=='')
							$vpoptions['youtubeparams']='fullplaylist='.$full_YoutubeVideoList;
						else
							$vpoptions['youtubeparams'].=';fullplaylist='.$full_YoutubeVideoList;
					//}
					
					require_once('youtube.php');

					$temp=VideoSource_Youtube::renderYouTubePlayer($vpoptions, $width, $height, $videolist_row,$theme_row,$startsecond,$endsecond);

					if($temp!='')
					{
						if($theme_row->useglass or $theme_row->logocover)
						{
							//$result.='<div class="YoutubeGalleryLogoCover'.$videolist_row->id.'" style="position: relative;width:'.$width.'px;height:'.$height.'px;padding:0;">';
							$result.='<div class="YoutubeGalleryLogoCover'.$videolist_row->id.'" style="position: relative;width:100%;height:100%;padding:0;border:none;">';
						}
						
						$result.=$temp;
					
						if($theme_row->logocover)
						{
							if($theme_row->controls and ($theme_row->playertype==3 or $theme_row->playertype==4))
								$bottom_px='25';
							else
								$bottom_px='0';
								
							
							$result.='<div style="position: absolute;bottom:'.$bottom_px.'px;right:0px;margin-top:0px;margin-left:0px;">'
							.'<img src="'.$theme_row->logocover.'" style="margin:0px;padding:0px;display:block;border: none;" /></div>';
						}
					
						if($theme_row->useglass)
						{
							//$result.='<div style="position: absolute;background-image: url(\'components/com_youtubegallery/images/dot.png\');'
							//.'top:0px;left:0px;width:'.$width.'px;height:'.($height-25).'px;margin-top:0px;margin-left:0px;padding:0px;"></div>';
							$result.='<div class="YoutubeGalleryGlassCover"></div>';
						}
					
					
					
						if($theme_row->useglass or $theme_row->logocover)
							$result.='</div>';
					
						
						
					}
				
					$result.='</div>';
					
				
			}	
			
			if($includeallplayers or $vs=='google')
			{
					require_once('google.php');
					$result.='<div id="yg_player_google_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Google::renderGooglePlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			if($includeallplayers or $vs=='yahoo')
			{
					require_once('yahoo.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;

					$result.='<div id="yg_player_yahoo_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Yahoo::renderYahooPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			if($includeallplayers or $vs=='collegehumor')
			{
				require_once('collegehumor.php');
				$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
				$result.='<div id="yg_player_collegehumor_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_CollegeHumor::renderCollegeHumorPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			if($includeallplayers or $vs=='dailymotion')
			{
					require_once('dailymotion.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
					$result.='<div id="yg_player_dailymotion_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_DailyMotion::renderDailyMotionPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}
			
			if($includeallplayers or $vs=='presentme')
			{
				require_once('presentme.php');
				$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
				
				$result.='<div id="yg_player_presentme_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_PresentMe::renderPresentMePlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
					
			}	
			
			if($includeallplayers or $vs=='ustream')
			{
				require_once('ustream.php');
				$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
				$result.='<div id="yg_player_ustream_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_Ustream::renderUstreamPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}
			
			if($includeallplayers or $vs=='ustreamlive')
			{
				require_once('ustreamlive.php');
				$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					
				$result.='<div id="yg_player_ustreamlive_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_UstreamLive::renderUstreamLivePlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			if($includeallplayers or $vs=='soundcloud')
			{
					require_once('soundcloud.php');
					$vpoptions['thumbnail']=YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
					$result.='<div id="yg_player_soundcloud_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_SoundCloud::renderPlayer($vpoptions, $width, $height, $videolist_row, $theme_row).'</div>';
			}	
			
			if($includeallplayers or $vs=='.flv')
			{
					if($list_index!=-1)
					{
						//Not Hot Switch
						$vpoptions['thumbnail']=$gallery_list[$list_index]['imageurl'];//YoutubeGalleryLayoutRenderer::getThumbnailByID($videoid,$gallery_list);;
						$videolink=$gallery_list[$list_index]['link'];
					}
					else
						$videolink='****youtubegallery-video-link****'; //For Hot Switch
					
						require_once('flv.php');
						
						$result.='<div id="yg_player_flv_id-'.$videolist_row->id.'" '.$divstyle.'>'.VideoSource_FLV::renderFLVPlayer($vpoptions, $width, $height, $videolist_row, $theme_row, $videolink).'</div>';					
			}
		
		}
		
		$imageurl='';
		$isHot=false;
		if($videoid=='****youtubegallery-video-id****')
		{
			$isHot=true;
			$videoid_d='hot'.$videolist_row->id;
			$imageurl='****youtubegallery-video-customimage****';
		}
		else
		{
			$videoid_d=$videoid;
			if($VideoRow)
				$imageurl=$VideoRow['custom_imageurl'];	
		}

		if($imageurl!='' and $theme_row->rel=='' and strpos($imageurl,'#')===false and strpos($imageurl,'_small')===false)
		{
			//Specific preview image for your YouTube video
			//The idea of Jarrett Gucci (Modified: play button added)
			
			$result=($isHot ? '***code_begin***' : '').'<div onclick="ygimage'.$videoid_d.'=document.getElementById(\'ygvideoplayer'.$videoid_d.'\');ygimage'.$videoid_d.'.style.display=\'block\';this.style.display=\'none\'"'
				.' style="position:relative;width:'.$width.'px;height:'.$height.'px;padding:0;">'
				.'<img src="'.$imageurl.'" style="cursor:pointer;width:'.$width.'px;height:'.$height.'px;padding:0;" />'
				.'<div style="position:absolute;width:100px;height:100px;left:'.floor($width/2-50).'px;top:'.floor($height/2-50).'px;">'
				.'<img src="components/com_youtubegallery/images/play.png" style="border:none!important;cursor:pointer;width:100px;height:100px;padding:0;" />'
				.'</div>'
				.'</div>'
				.'<div id="ygvideoplayer'.$videoid_d.'" style="display:none">'.($isHot ? '***code_end***' : '').$result.($isHot ? '***code_begin***' : '').'</div>'.($isHot ? '***code_end***' : '');
		}
		


		if($videoid!='****youtubegallery-video-id****')
			$result=str_replace('****youtubegallery-video-id****',$videoid,$result);
		else
			$result=str_replace('\'','*quote*',$result);
		
			
		$result='<div id="YoutubeGallerySecondaryContainer'.$videolist_row->id.'" style="'.$divstyle_player.'width:'.$width.'px;height:'.$height.'px;">'.$result.'</div>';
		
		
		
		return $result;
		
	}//function ShowAciveVideo()
	
	
	
	public static function addHotReloadScript(&$gallery_list,$width,$height,&$videolist_row, &$theme_row)
	{
			
			$vs=array();//'youtube','vimeo','break','own3dtvlive','own3dtvvideo','google','yahoo','collegehumor','dailymotion','.flv','presentme');
			foreach($gallery_list as $g)
			{

				$v=$g['videosource'];

				
				if(!in_array($v,$vs))
					$vs[]=$v;
			}
			
			
			$document = JFactory::getDocument();
			//
			$hotrefreshscript='
<!-- Youtube Gallery Hot Video Switch -->
<script type="text/javascript">
//<![CDATA[
	var YoutubeGalleryVideoSources'.$videolist_row->id.' = ["'.implode('", "',$vs).'"];
	var YoutubeGalleryPlayer'.$videolist_row->id.' = new Array;
';

			$i=0;
			
			foreach($vs as $v)
			{
				$player_code='<!-- '.$v.' player -->'.YoutubeGalleryLayoutRenderer::ShowActiveVideo($gallery_list,$width,$height,'****youtubegallery-video-id****', $videolist_row, $theme_row,$v);
				$hotrefreshscript.='
	YoutubeGalleryPlayer'.$videolist_row->id.'['.$i.']=\''.$player_code.'\';';
				$i++;
			}

			$hotrefreshscript.='
	
	for (var i=0;i<YoutubeGalleryPlayer'.$videolist_row->id.'.length;i++)
	{
		var player_code=YoutubeGalleryPlayer'.$videolist_row->id.'[i];
		';
		//player_code=player_code.replace(/\*\/scr/g,\'</scr\');
		//player_code=player_code.replace(/\*quote\*/g,\'\\\'\');
		$hotrefreshscript.='
		player_code=player_code.replace(\'*quote*\',\'\\\'\');
		YoutubeGalleryPlayer'.$videolist_row->id.'[i]=player_code;
	}
	
	function YoutubeGalleryCleanCode'.$videolist_row->id.'(playercode)
	{
		do{
			var b=playercode.indexOf("***code_begin***");
			var e=playercode.indexOf("***code_end***");
			if(b!=-1 && e!=-1)
				playercode=playercode.substr(0,b) + playercode.substr(e+14);
		}while(b!=-1 && e!=-1)
		return playercode;
	}
	
	function YoutubeGalleryHotVideoSwitch'.$videolist_row->id.'(videoid,videosource,id)
	{
		var i=YoutubeGalleryVideoSources'.$videolist_row->id.'.indexOf(videosource);
		if(i==-1)
			playercode="";
		else
			playercode=YoutubeGalleryPlayer'.$videolist_row->id.'[i];
			
		playercode=playercode.replace("****youtubegallery-video-id****",videoid);
		
		var title=document.getElementById("YoutubeGalleryThumbTitle'.$videolist_row->id.'_"+id).innerHTML
		var description=document.getElementById("YoutubeGalleryThumbDescription'.$videolist_row->id.'_"+id).innerHTML
		var link=document.getElementById("YoutubeGalleryThumbLink'.$videolist_row->id.'_"+id).innerHTML
		var startsecond=document.getElementById("YoutubeGalleryThumbStartSecond'.$videolist_row->id.'_"+id).innerHTML
		var endsecond=document.getElementById("YoutubeGalleryThumbEndSecond'.$videolist_row->id.'_"+id).innerHTML
		var customimage_obj=document.getElementById("YoutubeGalleryThumbCustomImage'.$videolist_row->id.'_"+id);
		
		ygApiStart'.$videolist_row->id.'=startsecond;
		ygApiEnd'.$videolist_row->id.'=endsecond;
		
		if(customimage_obj)
		{
			var customimage=customimage_obj.innerHTML;
			var n=customimage.indexOf("_small");
			if(n==-1)
			{
				playercode=playercode.replace("****youtubegallery-video-customimage****",customimage);
				for(i=0;i<2;i++)
				{
					playercode=playercode.replace("***code_begin***","");
					playercode=playercode.replace("***code_end***","");
				}
			}
			else
				playercode=YoutubeGalleryCleanCode'.$videolist_row->id.'(playercode);
		}
		else
			playercode=YoutubeGalleryCleanCode'.$videolist_row->id.'(playercode);
		
		playercode=playercode.replace("****youtubegallery-video-link****",link);
		playercode=playercode.replace("****youtubegallery-video-startsecond****",startsecond);
		playercode=playercode.replace("****youtubegallery-video-endsecond****",endsecond);
		playercode=playercode.replace("autoplay=0","autoplay=1");
		
		var ygsc=document.getElementById("YoutubeGallerySecondaryContainer'.$videolist_row->id.'");
		ygsc.innerHTML=playercode;
		ygsc.style.display="block";
		
		if(playercode.indexOf("<!--DYNAMIC PLAYER-->")!=-1)
			eval("youtubegallery_updateplayer_"+videosource+"_'.$videolist_row->id.'(videoid,true)");
		
		var tObj=document.getElementById("YoutubeGalleryVideoTitle'.$videolist_row->id.'");
		var dObj=document.getElementById("YoutubeGalleryVideoDescription'.$videolist_row->id.'");
		
		if(tObj)
		{
			tObj.innerHTML=title;
		}
		
		if(dObj)
		{
			dObj.innerHTML=description;
		}
		';
		
		
		if($theme_row->openinnewwindow==5)
		{
			//Jump to the player anchor:"youtubegallery"
			$hotrefreshscript.='
		window.location.hash="youtubegallery";
';
		}
		
		
		$hotrefreshscript.='
	}
//]]>	
</script>

';

			$document->addCustomTag($hotrefreshscript);
		
		
	}
	
	
	public static function getPlaylistIdsOnly(&$gallery_list,$current_videoid='',$exclude_source='',$full=false)
	{
		//set $current_videoid to '' to do not rearrange video list
		$theList1=array();
		
		$theList2=array();
		
			
		$current_videoid_found=false;
		
		foreach($gallery_list as $gl_row)	
		{
			if($gl_row['videoid']==$current_videoid)
			{
				$current_videoid_found=true;
			}
			else
			{
					if($exclude_source=='' or $gl_row['videosource']==$exclude_source)
					{
						$a='';
						if($current_videoid_found)
							$a=$gl_row['videoid'];
						else
							$a=$gl_row['videoid'];
							
						if($full)
							$theList2[]=$a.'*'.$gl_row['id'];//.'*'.$gl_row['startsecond'].'*'.$gl_row['endsecond'];
						else
							$theList2[]=$a;
					}
			}
			
			
		}//foreach
		
		return array_merge($theList1,$theList2);
	
	
	}
	
	
	public static function getListIndexByVideoID($videoid,&$gallery_list)
	{
		
		$i=0;
		foreach($gallery_list as $gl_row)	
		{
			if($gl_row['videoid']==$videoid)
				return $i;
			$i++;
		}
		return -1;
	}
	
	
	
	
	
	
	public static function getVideoRowByID($videoid,&$gallery_list,$asArray=false)
	{
		if($videoid=='' or $videoid=='****youtubegallery-video-id****')
		{
			if($asArray)
				return array();
			else
				return false;
		}
		
		if(isset($gallery_list) and count($gallery_list)>0)
		{
			
			foreach($gallery_list as $gl_row)	
			{
				if($gl_row['videoid']==$videoid)
					return $gl_row;
			}
		}
		
		//Check DB
		$db = JFactory::getDBO();
				
		$query = 'SELECT * FROM #__youtubegallery_videos WHERE videoid="'.$videoid.'" LIMIT 1';
					
		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
		$values=$db->loadAssocList();
		

		
		if(count($values)==0)
		{
			if($asArray)
				return array();
			else
				return false;
		}
		else
			return $values[0];
		
		
	}
	
	public static function getTitleByVideoID($videoid,&$gallery_list)
	{
		$gl_row=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list);
		if($gl_row)
			return $gl_row['title'];
		
		/*
		if(isset($gallery_list) and count($gallery_list)>0)
		{
				foreach($gallery_list as $g)
				{
						if($g['videoid']==$videoid)
								return $g['title'];
				}
		}
		*/
		return '';
		
	}
	
	public static function getThumbnailByID($videoid,&$gallery_list)
	{
		$gl_row=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list);
		if($gl_row)
			return $gl_row['imageurl'];

		/*
		foreach($gallery_list as $gl_row)	
		{
			if($gl_row['videoid']==$videoid)
				return $gl_row['imageurl'];
		}
		*/
		
		return '';
	}
	
	public static function getVideoSourceByID($videoid,&$gallery_list)
	{
		$gl_row=YoutubeGalleryLayoutRenderer::getVideoRowByID($videoid,$gallery_list);
		if($gl_row)
			return $gl_row['videosource'];
		/*
		foreach($gallery_list as $gl_row)	
		{
			if($gl_row['videoid']==$videoid)
				return $gl_row['videosource'];
		}
		*/
		return '';
	}
	
	public static function PrepareDescription($desc, $words, $chars)
	{
		if($chars==0 and $words>0)
		{
			preg_match('/([^\\s]*(?>\\s+|$)){0,'.$words.'}/', $desc, $matches);
			$desc=trim($matches[0]);	
		}
		else
		{
			if(strlen($desc)>$chars)
			$desc=substr($desc,0,$chars);
		}

		$desc=str_replace("/n"," ",$desc);
		$desc=str_replace("/r"," ",$desc);
			
		$desc=trim(preg_replace('/\s\s+/', ' ', $desc));

		$desc=trim($desc);
		
		return $desc;
	}


	public static function SocialButtons($link,$prefix,$params,$videolist_row_id,$videoid)
	{
		$pair=explode(',',$params);
		
		$w=80;
		if(isset($pair[2]))
			$w=(int)$pair[2];
				
		switch($pair[0])
		{
			case 'facebook_comments':
						
						$head_result='

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=624599437567869";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));

document.write(\'<div id="fb-root"></div>\');
</script>
';
$document = JFactory::getDocument();
$document->addCustomTag($head_result);


//ini_set('display_startup_errors',1);
//ini_set('display_errors',1);

//error_reporting(E_ALL|E_STRICT);

//jimport( 'joomla.environment.response' );
//prependBody("a");
//jimport( 'joomla.environment.response' );
//JFactory::getApplication()->getBody(true);
//$app                = JFactory::getApplication();
//$app->getBody();
//$body = JResponse::getBody();


//$app->appendBody('test***');

						$numposts='3';
						if(isset($pair[1]))
							$numposts=(int)$pair[1];

						$width='';//style="width:auto !important;"';
						if(isset($pair[2]))
							$width='data-width="'.(int)$pair[2].'px"';
						
						$colorscheme='light';
						if(isset($pair[3]))
							$colorscheme=$pair[3];
							
						if($link=='' or $link='window.location.href')
							$link=YouTubeGalleryMisc::full_url($_SERVER);//$_SERVER['HTTP_REFERER'];
							
						$result='<div class="fb-comments" data-href="'.$link.'" data-num-posts="'.$numposts.'" '.$width.' data-colorscheme="'.$colorscheme.'"></div>';
						
						
						
						return $result;
			break;
			//------------------------------------------------------------------------------------------------------------
			case 'facebook_share':
					
						$bName='Share Link';
						if(isset($pair[1]))
							$bName=$pair[1];
					
					
					
					$dName=$prefix.'fbshare_'.$videolist_row_id.'x'.$videoid;
					$tStyle='width:'.$w.'px;height:20px;border: 1px #29447e solid;background-color:#5972a7;color:white;font-size:12px;font-weight:bold;text-align:center;position:relative;';
					$tStyle2='border-top:#8a9cc2 1px solid;width:'.($w-2).'px;height:18px;padding:0px;font-decoration:none;';
					$result ='
	<div id="'.$dName.'"></div>
	<script>
		var theURL=escape('.$link.');
		
		var fbobj=document.getElementById("'.$dName.'");
		var sBody=\'<a href="https://www.facebook.com/sharer/sharer.php?u=\'+theURL+\'" target="_blank" style="color:white;"><div style="'.$tStyle.'"><div style="'.$tStyle2.'">'.$bName.'</div>\';
		sBody+=\'<div style="position:absolute;bottom:0;left:0;margin-bottom:-2px;width:'.$w.'px;height:1px;border-bottom:1px solid #e5e5e5;"></div>\';
		sBody+=\'</div></a>\';
	        fbobj.innerHTML = sBody;
	</script>
	';
			return $result;
			break;
			//------------------------------------------------------------------------------------------------------------
			case 'facebook_like':
					
						$FBLanguage='';
						if(isset($pair[1]))
							$FBLanguage=$pair[1];
						
					$dName=$prefix.'fblike_'.$videolist_row_id.'x'.$videoid;
					$result ='
	<div id="'.$dName.'" style="width:'.$w.'px;"></div>
	<script>
		var theURL=escape('.$link.');
		var fbobj=document.getElementById("'.$dName.'");
		var sBody=\'<iframe src="http://www.facebook.com/plugins/like.php?href=\';
		sBody+=theURL;
		sBody+=\'&layout=button_count&locale='.$FBLanguage.'&show_faces=false&action=like&font=tahoma&colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; height:20px" ></iframe>\';
	        fbobj.innerHTML = sBody;
	</script>
	';
					return $result;
			break;
			//------------------------------------------------------------------------------------------------------------
			case 'twitter':
					
					$TwitterAccount='';//"YoutubeGallery";
					if(isset($pair[1]))
						$TwitterAccount=$pair[1];
					else
						return '<p style="color:white;background-color:red;">Set Twitter Account.<br/>Example: [social:twitter,JoomlaBoat]</p>';
						
					$dName=$prefix.'witter_'.$videolist_row_id.'x'.$videoid;
					$result ='
	<div id="'.$dName.'" style="width:'.$w.'px;"></div>
	<script>
		var theURL=escape('.$link.');
		var twobj=document.getElementById("'.$dName.'");
		var TwBody=\'<a href="https://twitter.com/share" class="twitter-share-button" data-url="\'+theURL+\'" data-via="'.$TwitterAccount.'" data-hashtags="\'+theURL+\'">Tweet</a>\';
		twobj.innerHTML = TwBody;
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	</script>
	';
					return $result;
			break;
			//------------------------------------------------------------------------------------------------------------
			//case '':
			//break;
		}
				
				
	}
		

}

