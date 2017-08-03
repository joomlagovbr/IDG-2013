<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.5
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class YoutubeGalleryLayoutsPortal
{
	public static function getLayout(&$theme_row, $shadowbox_activated)
	{
		
		
		$result='';
		
		$result.='<div class="span8 video-main player-metadata">';
		
		$result.='[videoplayer]';
		
		if($theme_row->showactivevideotitle==1)
		{
			$result.='[if:videotitle]';
		
			$result.='<h3>[videotitle]</h3>';
		
			$result.='[endif:videotitle]';
			
		}	
	
		if($theme_row->description==1 and $theme_row->descr_position==1 )
		{
			$result.='[if:videodescription]';
			
			$result.='<p class="description">[videodescription]</p>';
			
			$result.='[endif:videodescription]';
		}

		$result.='</div><div class="span4 video-list">';

		if(!$shadowbox_activated)
		{
		
			if($theme_row->pagination==1 or $theme_row->pagination==3 )
				$result.='[pagination]';

	
			$result.='
				[if:count]						
							
							[navigationbar:classictable,simple]
						
				[endif:count]
			';
		
			if($theme_row->pagination==2 or $theme_row->pagination==3)
				$result.='[pagination]';
		}	
		
		$result .= '</div>
		<div class="outstanding-footer">
			<a class="outstanding-link" href="'.JURI::root().'index.php/galeria-de-videos">
				<span class="text">Galeria de vídeos</span>
				<span class="icon-box"><i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i></span>
			</a>	
		</div>';
		
		return $result;
	}
}