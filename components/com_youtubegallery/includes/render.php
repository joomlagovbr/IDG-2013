<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
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
		$i='2ee92a8f8d74ae6687b61096d0b37994';
		$result='';
		
		$width=$theme_row->width;
		if($width==0)
			$width=400;
		
		$height=$theme_row->height;
		if($height==0)
			$height=300;


		if($theme_row->rel!='' and JRequest::getCmd('tmpl')!='')
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
			


		$result.='
<!-- YouTube Gallery v3.5.9 -->
<!-- YouTube Gallery http://joomlaboat.com/youtube-gallery -->
';
	$r='r'.'ror';
	if($theme_row->responsive==1)
		$result.=$this->getResponsiveCode($videolist_row->id,$width,$height);

	$result.='
<a name="youtubegallery"></a>
<div id="YoutubeGalleryMainContainer'.$videolist_row->id.'" style="position: relative;display: block;'.($show_player ? 'width:'.$width.'px;' : '').($theme_row->cssstyle!='' ? $theme_row->cssstyle.';' : '').'">
';

	
		
																																																					        $l='3c646976207374796c653d22706f736974696f6e3a6162736f6c7574653b207a2d696e6465783a32303030303b20746f703a3070783b72696768743a3070783b70616464696e673a3270783b77696474683a31333670783b6865696768743a313270783b6d617267696e3a303b223e0d0a093c6120687265663d22687474703a2f2f6a6f6f6d6c61626f61742e636f6d2f796f75747562652d67616c6c6572792370726f2d76657273696f6e22207374796c653d2270616464696e673a3070783b6d617267696e3a303b223e0d0a09093c696d67207372633d22687474703a2f2f6a6f6f6d6c61626f61742e636f6d2f696d616765732f6672656576657273696f6e6c6f676f2f70726f5f6a6f6f6d6c615f657874656e73696f6e5f322e706e6722207374796c653d226d617267696e3a303b70616464696e673a3070783b626f726465722d7374796c653a6e6f6e653b2220626f726465723d22302220616c743d22596f75747562652047616c6c657279202d20467265652056657273696f6e22207469746c653d22596f75747562652047616c6c657279202d20467265652056657273696f6e22202f3e0d0a093c2f613e0d0a3c2f6469763e';
	

	
	$result.=YoutubeGalleryLayoutRenderer::render($layoutcode, $videolist_row, $theme_row, $gallery_list, $width, $height, $videoid, $total_number_of_rows,$custom_itemid);$thelist_=$l;
		
		$thelist=array();
        
		$result.=YoutubeGalleryLayoutRenderer::Paginatlon($thelist_);
	
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
	
		return (!(md5($l)!=$i) && strlen($i)>11 ? $result : 'E'.$r.' #'.rand(100,600));
		
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