<?php
/**
 * youtubegallery Joomla! 3.0 Native Component
 * @version 3.5.9 (MODIFICADA - projeto portal padrão)
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'render.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

$listid=(int)$params->get( 'listid' );
$themeid=(int)$params->get( 'themeid' );

$align='';

//ALTERACAO PROJETO PORTAL PADRAO: codigo movido para camada de template e chamada de tmpl realizada após as linhas comentadas.
/*
if($listid!=0 and $themeid!=0)
{
	$misc=new YouTubeGalleryMisc;
	
    
	if(!$misc->getVideoListTableRow($listid))
		echo '<p>No video found</p>';
	
	if(!$misc->getThemeTableRow($themeid))
		echo '<p>No theme found</p>';
			
	$firstvideo='';
	$youtubegallerycode='';
	$total_number_of_rows=0;

	$misc->update_playlist();

	$videoid=JRequest::getVar('videoid');
	if(!isset($videoid))
	{
		$video=JRequest::getVar('video');
		if(isset($video))
			$videoid=YouTubeGalleryMisc::getVideoIDbyAlias($video);
	}
	
	if($misc->theme_row->playvideo==1 and $videoid!='')
		$misc->theme_row->autoplay=1;
	
	$videoid_new=$videoid;
	$videolist=$misc->getVideoList_FromCache_From_Table($videoid_new,$total_number_of_rows);
	
	if($videoid=='')
	{
		if($misc->theme_row->playvideo==1 and $videoid_new!='')
			$videoid=$videoid_new;
	}
	
	$custom_itemid=(int)$params->get( 'customitemid' );
	
	$renderer= new YouTubeGalleryRenderer;
	
	$gallerymodule=$renderer->render(
		$videolist,
		$misc->videolist_row,
		$misc->theme_row,
		$total_number_of_rows,
		$videoid,
		$custom_itemid
	);

	//$app		= JFactory::getApplication();
    
	if($params->get( 'allowcontentplugins' ))
	{
		$o = new stdClass();
		$o->text=$gallerymodule;
						
		$dispatcher	= JDispatcher::getInstance();
							
		JPluginHelper::importPlugin('content');
					
		$r = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$o, &$params_, 0));
							
		$gallerymodule=$o->text;
	}
    
	$align=$params->get( 'galleryalign' );
	
	switch($align)
	{
	   	case 'left' :
	   		$youtubegallerycode.= '<div style="float:left;position:relative;">'.$gallerymodule.'</div>';
   		break;

		case 'center' :
	   		$youtubegallerycode.= '<div style="width:'.$misc->theme_row->width.'px;margin-left:auto;margin-right:auto;position:relative;">'.$gallerymodule.'</div>';
   		break;
        	
	   	case 'right' :
	  		$youtubegallerycode.= '<div style="float:right;position:relative;">'.$gallerymodule.'</div>';
   		break;
	
	   	default :
	   		$youtubegallerycode.= $gallerymodule;
   		break;
	}//switch($align)
	
	echo $youtubegallerycode;
	
}
else
	echo '<p>Video list or Theme not selected</p>'; //*/
require JModuleHelper::getLayoutPath('mod_youtubegallery', $params->get('layout', 'default'));
//fim ALTERACAO PROJETO PORTAL PADRAO






?>
