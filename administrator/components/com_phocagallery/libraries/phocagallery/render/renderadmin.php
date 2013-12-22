<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderAdmin
{
	// PHOCAGALLERY SPECIFIC
	function renderExternalLink($extLink) {
	
		$extLinkArray	= explode("|", $extLink, 4);
		if (!isset($extLinkArray[0])) {$extLinkArray[0] = '';}
		if (!isset($extLinkArray[1])) {$extLinkArray[1] = '';}
		if (!isset($extLinkArray[2])) {$extLinkArray[2] = '_self';}
		if (!isset($extLinkArray[3])) {$extLinkArray[3] = 1;}
	
		return $extLinkArray;
	}
	
	function renderThumbnailCreationStatus($status = 1, $onlyImage = 0) {
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
	
	
	function quickIconButton( $link, $image, $text, $imgUrl ) {
		//$image = str_replace('icon-48-', 'icon-48-phocafont', $image);
		return '<div class="thumbnails ph-icon">'
		.'<a class="thumbnail ph-icon-inside" href="'.$link.'">'
		.JHTML::_('image', $imgUrl . $image, $text )
		.'<br /><span>'.$text.'</span></a></div>'. "\n";
	}
	
	public function getLinks() {
		$app	= JFactory::getApplication();
		$option = $app->input->get('option');
		$oT		= strtoupper($option);
		
		$links =  array();
		switch ($option) {
			case 'com_phocagallery':
				$links[]	= array('Phoca Gallery site', 'http://www.phoca.cz/phocagallery');
				$links[]	= array('Phoca Gallery documentation site', 'http://www.phoca.cz/documentation/category/2-phoca-gallery-component');
				$links[]	= array('Phoca Gallery download site', 'http://www.phoca.cz/download/category/66-phoca-gallery');
			break;
			
			case 'com_phocaguestbook':
				$links[]	= array('Phoca Guestbook site', 'http://www.phoca.cz/phocaguestbook');
				$links[]	= array('Phoca Guestbook documentation site', 'http://www.phoca.cz/documentation/category/3-phoca-guestbook-component');
				$links[]	= array('Phoca Guestbook download site', 'http://www.phoca.cz/download/category/69-phoca-guestbook');
			break;
			
			case 'com_phocadownload':
				$links[]	= array('Phoca Download site', 'http://www.phoca.cz/phocadownload');
				$links[]	= array('Phoca Download documentation site', 'http://www.phoca.cz/documentation/category/17-phoca-download-component');
				$links[]	= array('Phoca Download download site', 'http://www.phoca.cz/download/category/68-phoca-download');
			break;
			
			case 'com_phocamaps':
				$links[]	= array('Phoca Maps site', 'http://www.phoca.cz/phocamaps');
				$links[]	= array('Phoca Maps documentation site', 'http://www.phoca.cz/documentation/category/53-phoca-maps-component');
				$links[]	= array('Phoca Maps download site', 'http://www.phoca.cz/download/category/81-phoca-maps');
			break;
			
			case 'com_phocafavicon':
				$links[]	= array('Phoca Favicon site', 'http://www.phoca.cz/phocafavicon');
				$links[]	= array('Phoca Favicon documentation site', 'http://www.phoca.cz/documentation/category/4-phoca-favicon-component');
				$links[]	= array('Phoca Favicon download site', 'http://www.phoca.cz/download/category/70-phoca-favicon');
			break;
			
			case 'com_phocafont':
				$links[]	= array('Phoca Font site', 'http://www.phoca.cz/phocafont');
				$links[]	= array('Phoca Font documentation site', 'http://www.phoca.cz/documentation/category/55-phoca-font-component');
				$links[]	= array('Phoca Font download site', 'http://www.phoca.cz/download/category/82-phoca-font');
			break;
			
			case 'com_phocapdf':
				$links[]	= array('Phoca PDF site', 'http://www.phoca.cz/phocapdf');
				$links[]	= array('Phoca PDF documentation site', 'http://www.phoca.cz/documentation/category/47-phoca-pdf-component');
				$links[]	= array('Phoca PDF download site', 'http://www.phoca.cz/download/category/79-phocapdf');
			break;
		
		}
		
		$links[]	= array('Phoca News', 'http://www.phoca.cz/news');
		$links[]	= array('Phoca Forum', 'http://www.phoca.cz/forum');
		
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
		
		$banners	= array();
		$banners[]	= array('Phoca Restaurant Menu','phocamenu', 'prm');
		
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
				$o .= '<div><a style="text-decoration:underline;" href="http://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/icon-box-'.$components[$numO][2].'.png', ''). '</a></div>';
				$o .= '<div style="margin-top:-10px;"><small><a style="text-decoration:underline;" href="http://www.phoca.cz/'.$components[$numO][1].'" target="_blank">'.$components[$numO][0].'</a></small></div>';
				$o .= '</div>';
			}
			$o .= '<div style="clear:both"></div>';
			$o .= '</div>';
		} else {
			$num = range(0,(count($banners) - 1 )); 
			shuffle($num);
			$numO = $num[0];
			$o .= '<div><a href="http://www.phoca.cz/'.$banners[$numO][1].'" target="_blank">'.JHTML::_('image',  'media/'.$option.'/images/administrator/b-'.$banners[$numO][2].'.png', ''). '</a></div>';

		}
		
		$o .= '<p>&nbsp;</p>';
		$o .= '<h4 style="margin-bottom:5px;">'.JText::_($oT.'_PLEASE_READ'). '</h4>';
		$o .= '<div><a style="text-decoration:underline" href="http://www.phoca.cz/phoca-needs-your-help/" target="_blank">'.JText::_($oT.'_PHOCA_NEEDS_YOUR_HELP'). '</a></div>';
		
		$o .= '</div>';
		return $o;
	}
}