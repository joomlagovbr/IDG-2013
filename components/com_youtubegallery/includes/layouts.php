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

class YoutubeGalleryLayouts
{
	public static function getTableClassic(&$theme_row, $shadowbox_activated)
	{
		
		
		$result='';
		
		if($theme_row->showlistname==1)
		{
			if($theme_row->listnamestyle!='')
				$result.='<p style="'.$theme_row->listnamestyle.'">[listname]</p>';
			else
				$result.='<h3>[listname]</h3>';
		}
		
		if($theme_row->description==1 and $theme_row->descr_position==0 )
		{
			
			$result.='[if:videodescription]';
			
				if($theme_row->descr_style!='')
					$result.='<p style="'.$theme_row->descr_style.'">[videodescription]</p>';
				else
					$result.='<h4>[videodescription]</h4>';
			
			$result.='[endif:videodescription]';
		}
		
		$result.='[videoplayer]';
		
		if($theme_row->showactivevideotitle==1)
		{
			$result.='[if:videotitle]';
			
				if($theme_row->activevideotitlestyle!='')
					$result.='<p style="'.$theme_row->activevideotitlestyle.'">[videotitle]</p>';
				else
					$result.='<h3>[videotitle]</h3>';
		
			$result.='[endif:videotitle]';
			
		}	
	
		if($theme_row->description==1 and $theme_row->descr_position==1 )
		{
			$result.='[if:videodescription]';
			
				if($theme_row->descr_style!='')
					$result.='<p style="'.$theme_row->descr_style.'">[videodescription]</p>';
				else
					$result.='<h4>[videodescription]</h4>';
			
			$result.='[endif:videodescription]';
		}

		if(!$shadowbox_activated)
		{
		
			//pagination 1 - on top only
			//pagination 2 - on bottom only
			//pagination 3 - both
		
			if($theme_row->pagination==1 or $theme_row->pagination==3 )
				$result.='[pagination]';

	
			$result.='
				[if:count]
							
							<hr '.($theme_row->linestyle!='' ? ' style="'.$theme_row->linestyle.'" ' : '').' />
							[navigationbar:[cols],[width]]
						
				[endif:count]
			';
		
			if($theme_row->pagination==2 or $theme_row->pagination==3)
				$result.='[pagination]';
		}	
			
		
		return $result;
	}
}