<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

jimport('joomla.application.component.view');

//error_reporting(E_ALL ^ E_N O T I C E);

require_once('layouts.php');
require_once('layoutrenderer.php');


class YouTubeGalleryRenderer
{
	
	var $_pagination;
	
	function __construct() {
 
	}
		
	
	function render(&$gallery_list,	&$videolist_row, &$theme_row, $total_number_of_rows, $videoid,$custom_itemid=0)
	{
		
		$result='';
		
		$width=$theme_row->width;
		if($width==0)
			$width=400;
		
		$height=$theme_row->height;
		if($height==0)
			$height=300;


		if($theme_row->rel!='' and JFactory::getApplication()->input->getCmd('tmpl')!='')
		{
			// Shadow box
			$shadowbox_activated=true;
			$layoutcode=YoutubeGalleryLayouts::getTableClassic($theme_row,$shadowbox_activated);
		}
		else
		{
			$shadowbox_activated=false;
			
			if($theme_row->customlayout!='')
				$layoutcode=$theme_row->customlayout;
			else
				$layoutcode=YoutubeGalleryLayouts::getTableClassic($theme_row,$shadowbox_activated);
		}

		if($theme_row->rel!='')
			$show_player=false; //Thumbnails only, when shadow box enabled
		else
			$show_player=true;


		
		//Head Script
		if($theme_row->headscript!='')
			$this->setHeadScript($theme_row,$videolist_row->id,$width,$height);
			

			
		$glassclass='';
		if($theme_row->useglass)
		{
			$glassclass='
.YoutubeGalleryGlassCover
{
	position: absolute;
	background-image: url("components/com_youtubegallery/images/dot.png");
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	margin-top:0px;
	margin-left:0px;
	padding:0px;
}
';
		}
			

		$result.='
<!-- YouTube Gallery -->
';

	//$r='r'.'ror';
	if($theme_row->responsive==1)
	{
		$result.=$this->getResponsiveCode($videolist_row->id,$width,$height);
		if($theme_row->useglass)
		{
			$headscript='
		<style>
		'.$glassclass.'
		</style>
		';
			$document = JFactory::getDocument();
			$document->addCustomTag($headscript);
		}
	}
	elseif($theme_row->responsive==2)
	{
		$headscript='
		<style>
/*
CSS for making YouTubeGallery player responsive (without javascript):
*/


div#YoutubeGalleryMainContainer'.$videolist_row->id.' {
width: 100% !important;
}
div#YoutubeGallerySecondaryContainer'.$videolist_row->id.', .YoutubeGalleryLogoCover'.$videolist_row->id.'{
position: relative !important;
width: 100% !important;
height: 0 !important; padding-bottom: 56.25% !important; /* 16:9 */ 
}


div#YoutubeGallerySecondaryContainer'.$videolist_row->id.' iframe {
position: absolute !important;
top: 0 !important;
left: 0 !important;
width: 100% !important;
height: 100% !important;
border: 2px solid #000;
background: #000;
-moz-box-shadow: 1px 1px 7px 0px #222;
-webkit-box-shadow: 1px 1px 7px 0px #222;
box-shadow: 1px 1px 7px 0px #222;
}

div#YoutubeGallerySecondaryContainer'.$videolist_row->id.' object{
width: 100% !important;
height: 100% !important;

position: absolute !important;
top: 0 !important;
left: 0 !important;
width: 100% !important;
height: 100% !important;
border: 2px solid #000;
background: #000;
-moz-box-shadow: 1px 1px 7px 0px #222;
-webkit-box-shadow: 1px 1px 7px 0px #222;
box-shadow: 1px 1px 7px 0px #222;

}

'.($theme_row->useglass==1 ? $glassclass : '').'

</style>
		';
		
		$document = JFactory::getDocument();
		$document->addCustomTag($headscript);
		
	}

	$result.='
<a name="youtubegallery"></a>
<div id="YoutubeGalleryMainContainer'.$videolist_row->id.'" style="position: relative;display: block;'.($show_player ? 'width:'.$width.'px;' : '').($theme_row->cssstyle!='' ? $theme_row->cssstyle.';' : '').'">
';
	
	$result.=YoutubeGalleryLayoutRenderer::render($layoutcode, $videolist_row, $theme_row, $gallery_list, $width, $height, $videoid, $total_number_of_rows,$custom_itemid);
		
		$thelist_=array();
        
		$result.='		
	</div>
';//</div>
	if($theme_row->responsive==1)
	{
		$result.='
<!-- Make it responsive to window size -->
<script language="JavaScript">
//<![CDATA[
window.onresize = function() { YoutubeGalleryAutoResizePlayer'.$videolist_row->id.'(); } 
//]]>
</script>
';
	
	}
	
$result.='
<!-- end of YouTube Gallery -->
';   
	
		return $result;
		
	}
	
	function setHeadScript($theme_row,$instance_id,$width,$height)
	{
		$headscript=$theme_row->headscript;
		$document = JFactory::getDocument();
		
		$headscript=str_replace('[instanceid]',$instance_id,$headscript);
		$headscript=str_replace('[width]',$width,$headscript);
		$headscript=str_replace('[height]',$height,$headscript);
		$headscript=str_replace('[mediafolder]','images/'.$theme_row->mediafolder,$headscript);
		
		$fields_theme=array('bgcolor','cols','cssstyle','navbarstyle','thumbnailstyle','linestyle','listnamestyle','activevideotitlestyle','color1','color2','descr_style','rel','hrefaddon','mediafolder');
		
		$theme_row_array = get_object_vars($theme_row);
		
		foreach($fields_theme as $fld)
		{
			$headscript=str_replace('['.$fld.']',$theme_row_array[$fld],$headscript);
		}
		
		
		$document->addCustomTag($headscript);
		
	}
	
	function getResponsiveCode($instance_id,$width,$height)
	{
		$result='
<!-- Make it responsive to window size -->
<script type="text/javascript">
//<![CDATA[

function YoutubeGalleryClientWidth'.$instance_id.'() {
	return YoutubeGalleryResults'.$instance_id.' (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function YoutubeGalleryScrollLeft'.$instance_id.'() {
	return YoutubeGalleryResults'.$instance_id.' (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function YoutubeGalleryFindHorizontalOffset'.$instance_id.'(id) {
	var node = document.getElementById(id);     
	var curleft = 0;
	var curleftscroll = 0;
	var scroll_left = YoutubeGalleryScrollLeft'.$instance_id.'();
	if (node.offsetParent) {
	        do {
		        curleft += node.offsetLeft;
		        curleftscroll =0;
		} while (node = node.offsetParent);

		var imaged_x=(curleft - curleftscroll)-scroll_left;
		return imaged_x;
		}
		return 0;
	}
function YoutubeGalleryResults'.$instance_id.'(n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
		return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
	}		
function YoutubeGalleryAutoResizePlayer'.$instance_id.'(){
	var clientWidth=YoutubeGalleryClientWidth'.$instance_id.'();
	
	
	
	var playerObject=document.getElementById("youtubegalleryplayerid_'.$instance_id.'");
	if(playerObject==null) return false;
	var mainObject=document.getElementById("YoutubeGalleryMainContainer'.$instance_id.'");
	
	var parentObject=mainObject.parentNode;
	var parentWidth=parentObject.offsetWidth;
		
	var secondaryObject=document.getElementById("YoutubeGallerySecondaryContainer'.$instance_id.'");
	var playerWidth='.$width.';
	var x=YoutubeGalleryFindHorizontalOffset'.$instance_id.'("YoutubeGalleryMainContainer'.$instance_id.'");
	
	var setWidth=false;
	
	if(playerWidth>parentWidth)
	{
		playerWidth=parentWidth;
		setWidth=true;
	}
	
	
	if(x+playerWidth>clientWidth)
	{
		playerWidth=clientWidth-x;
		setWidth=true;
	}
	
	if(playerObject.width!=playerWidth)
			setWidth=true;
	
	if(setWidth)
	{
		mainObject.style.width= (playerWidth) + "px";
		
		var newH='.$height.'/('.$width.'/playerWidth);
		
		secondaryObject.style.width= (playerWidth) + "px";
		secondaryObject.style.height= (newH) + "px";
		
		playerObject.width= (playerWidth) + "px";
		playerObject.height= (newH) + "px";
	}
}

//]]>
</script>
';

		return $result;

	}


}


?>