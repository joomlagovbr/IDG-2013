<?php
/**
 * youtubegallery Joomla! 2.5 Native Component
 * @version 3.5.5 (MODIFICADA - projeto portal padrao)
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.DS.'templates'.DS.'padraogoverno01'.DS.'html'.DS.'mod_youtubegallery'.DS.'_render.php');
 
if($listid!=0 and $themeid!=0)
{
	$misc=new YouTubeGalleryMisc;
	
    
	if(!$misc->getVideoListTableRow($listid))
		echo '<p>Nenhum vídeo encontrado.</p>';
	
	if(!$misc->getThemeTableRow($themeid))
		echo '<p>Nenhum tema encontrado.</p>';
			
	$firstvideo='';
	$youtubegallerycode='';
	$total_number_of_rows=0;
							
	$misc->update_playlist();

	$videoid=JRequest::getVar('videoid');
	
	if($misc->theme_row->playvideo==1 and $videoid!='')
		$misc->theme_row->autoplay=1;
	
	$videoid_new=$videoid;
	$videolist=$misc->getVideoList_FromCache_From_Table($videoid_new,$total_number_of_rows);
	
	if($videoid=='')
	{
		if($misc->theme_row->playvideo==1 and $videoid_new!='')
			$videoid=$videoid_new;
	}
	
	$renderer= new YouTubeGalleryRendererPortal;
	
	$gallerymodule=$renderer->render(
		$videolist,
		$misc->videolist_row,
		$misc->theme_row,
		$total_number_of_rows,
		$videoid
	);


	$youtubegallerycode.= $gallerymodule;

	
	echo $youtubegallerycode;
	
}
else
	echo '<p>Lista de vídeos ou tema não selecionado.</p>';

?>