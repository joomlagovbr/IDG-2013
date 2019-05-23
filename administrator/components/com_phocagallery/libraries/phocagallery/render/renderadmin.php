<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderAdmin
{
	// PHOCAGALLERY SPECIFIC
	public static function renderExternalLink($extLink) {
	
		$extLinkArray	= explode("|", $extLink, 4);
		if (!isset($extLinkArray[0])) {$extLinkArray[0] = '';}
		if (!isset($extLinkArray[1])) {$extLinkArray[1] = '';}
		if (!isset($extLinkArray[2])) {$extLinkArray[2] = '_self';}
		if (!isset($extLinkArray[3])) {$extLinkArray[3] = 1;}
	
		return $extLinkArray;
	}
	
	public static function renderThumbnailCreationStatus($status = 1, $onlyImage = 0) {
		switch ($status) {
			case 0:
				$statusData = array('disabled', 'false');
			break;
			case 1:
			Default:
				$statusData = array('enabled', 'true');
			break;
		}
		
		if ($onlyImage == 1) {
			return JHTML::_('image',  'media/com_phocagallery/images/administrator/icon-16-'.$statusData[1].'.png',  JText::_('COM_PHOCAGALLERY_' . $statusData[0] ) );
		} else {
			return '<span class="hasTip" title="'.JText::_('COM_PHOCAGALLERY_THUMBNAIL_CREATION_STATUS_IS') 
			. ' ' . JText::_('COM_PHOCAGALLERY_' . $statusData[0] ). '::'
			. JText::_('COM_PHOCAGALLERY_THUMBNAIL_CREATION_STATUS_INFO').'">'
			. JText::_('COM_PHOCAGALLERY_THUMBNAIL_CREATION_STATUS') . ': '
			. JHTML::_('image', 'media/com_phocagallery/images/administrator/icon-16-'.$statusData[1].'.png', JText::_('COM_PHOCAGALLERY_' . $statusData[0] ) ) . '</span>';
		}
	}
	
	// ---------------------
	
	
	public static function quickIconButton( $link, $image, $text, $imgUrl ) {
		//$image = str_replace('icon-48-', 'icon-48-phocafont', $image);
		return '<div class="thumbnails ph-icon">'
		.'<a class="thumbnail ph-icon-inside" href="'.$link.'">'
		.JHTML::_('image', $imgUrl . $image, $text )
		.'<br /><span>'.$text.'</span></a></div>'. "\n";
	}
	
	public static function getLinks() {
		$app	= JFactory::getApplication();
		$option = $app->input->get('option');
		$oT		= strtoupper($option);
		
		$links =  array();
		switch ($option) {
			case 'com_phocagallery':
				$links[]	= array('Phoca Gallery site', 'https://www.phoca.cz/phocagallery');
				$links[]	= array('Phoca Gallery documentation site', 'https://www.phoca.cz/documentation/category/2-phoca-gallery-component');
				$links[]	= array('Phoca Gallery download site', 'https://www.phoca.cz/download/category/66-phoca-gallery');
			break;

		
		}
		
		$links[]	= array('Phoca News', 'https://www.phoca.cz/news');
		$links[]	= array('Phoca Forum', 'https://www.phoca.cz/forum');
		
		$components 	= array();
		$components[]	= array('Phoca Gallery','phocagallery', 'pg');
		$components[]	= array('Phoca Guestbook','phocaguestbook', 'pgb');
		$components[]	= array('Phoca Download','phocadownload', 'pd');
		$components[]	= array('Phoca Documentation','phocadocumentation', 'pdc');
		$components[]	= array('Phoca Favicon','phocafavicon', 'pfv');
		$components[]	= array('Phoca SEF','phocasef', 'psef');
		$components[]	= array('Phoca PDF','phocapdf', 'ppdf');
		$components[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		$components[]	= array('Phoca Maps','phocamaps', 'pm');
		$components[]	= array('Phoca Font','phocafont', 'pf');
		$components[]	= array('Phoca Email','phocaemail', 'pe');
		$components[]	= array('Phoca Install','phocainstall', 'pi');
		$components[]	= array('Phoca Template','phocatemplate', 'pt');
		$components[]	= array('Phoca Panorama','phocapanorama', 'pp');
		$components[]	= array('Phoca Commander','phocacommander', 'pcm');
		$components[]	= array('Phoca Photo','phocaphoto', 'ph');
		$components[]	= array('Phoca Cart','phocacart', 'pc');
		
		$banners	= array();
		$banners[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		$banners[]	= array('Phoca Cart','phocacart', 'pc');
		
		$o = '';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_LINKS'). '</h4>';
		$o .= '<ul>';
		foreach ($links as $k => $v) {
			$o .= '<li><a style="text-decoration:underline" href="'.$v[1].'" target="_blank">'.$v[0].'</a></li>';
		}
		$o .= '</ul>';
		
		$o .= '<div>';
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_USEFUL_TIPS'). '</h4>';
		
		$m = mt_rand(0, 10);
		if ((int)$m > 0) {
			$o .= '<div>';
			$num = range(0,(count($components) - 1 )); 
			shuffle($num);
			for ($i = 0; $i<3; $i++) {
				$numO = $num[$i];
				$o .= '<div style="float:left;width:33%;margin:0 auto;">';
				$o .= '<div><a style="text-decoration:underline;" href="https://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/icon-box-'.$components[$numO][2].'.png', ''). '</a></div>';
				$o .= '<div style="margin-top:-10px;"><small><a style="text-decoration:underline;" href="https://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.$components[$numO][0].'</a></small></div>';
				$o .= '</div>';
			}
			$o .= '<div style="clear:both"></div>';
			$o .= '</div>';
		} else {
			$num = range(0,(count($banners) - 1 )); 
			shuffle($num);
			$numO = $num[0];
			$o .= '<div><a href="https://www.phoca.cz/'.$banners[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/b-'.$banners[$numO][2].'.png', ''). '</a></div>';

		}
		
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_PLEASE_READ'). '</h4>';
		$o .= '<div><a style="text-decoration:underline" href="https://www.phoca.cz/phoca-needs-your-help/" target="_blank">'.JText::_($oT.'_PHOCA_NEEDS_YOUR_HELP'). '</a></div>';
		
		$o .= '</div>';
		return $o;
	}
}