<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
phocagalleryimport( 'phocagallery.render.renderinfo' );
phocagalleryimport( 'phocagallery.utils.utils' );

class PhocaGalleryCpViewPhocaGalleryIn extends JViewLegacy
{
	public function display($tpl = null) {
		
		$tmpl		= array();
		$params 	= JComponentHelper::getParams('com_phocagallery');
		
		$this->sidebar = JHtmlSidebar::render();
		
		JHTML::stylesheet( 'media/com_phocagallery/css/administrator/phocagallery.css' );
		
		$tmpl['version'] 					= PhocaGalleryRenderInfo::getPhocaVersion();
		$tmpl['enablethumbcreation']		= $params->get('enable_thumb_creation', 1 );
		$tmpl['paginationthumbnailcreation']= $params->get('pagination_thumbnail_creation', 0 );
		$tmpl['cleanthumbnails']			= $params->get('clean_thumbnails', 0 );
		$tmpl['enablethumbcreationstatus'] 	= PhocaGalleryRenderAdmin::renderThumbnailCreationStatus((int)$tmpl['enablethumbcreation'], 1);
		
		//Main Function support
		
	//	echo '<table border="1" cellpadding="5" cellspacing="5" style="border:1px solid #ccc;border-collapse:collapse">';
		
		$function = array('getImageSize','imageCreateFromJPEG', 'imageCreateFromPNG', 'imageCreateFromGIF', 'imageRotate', 'imageCreateTruecolor', 'imageCopyResampled', 'imageFill', 'imageColorTransparent', 'imageColorAllocate', 'exif_read_data');
		$fOutput = '';
		foreach ($function as $key => $value) {
			
			if (function_exists($value)) {
				$bgStyle 	= 'class="alert alert-success"';
				$icon		= 'true';
				$iconText	= JText::_('COM_PHOCAGALLERY_ENABLED');
			} else {
				$bgStyle = 'class="alert alert-error"';
				$icon		= 'false';
				$iconText	= JText::_('COM_PHOCAGALLERY_DISABLED');
			}
			
			$fOutput .= '<tr '.$bgStyle.'><td>'.JText::_('COM_PHOCAGALLERY_FUNCTION') .' '. $value.'</td>';
			$fOutput .=  '<td align="center">'.JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-true.png', JText::_('COM_PHOCAGALLERY_ENABLED') ).'</td>';
			$fOutput .=  '<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-'.$icon.'.png', JText::_($iconText)).'</td></tr>';
			
		}
		
		// PICASA
		$fOutput .= '<tr><td align="left"><b>'. JText::_('COM_PHOCAGALLERY_PICASA_SUPPORT').'</b></td></tr>';
		
		if(!PhocaGalleryUtils::iniGetBool('allow_url_fopen')){
			$bgStyle 	= 'class="alert alert-error"';
			$icon		= 'false';
			$iconText	= JText::_('COM_PHOCAGALLERY_DISABLED');
		} else {
			$bgStyle 	= 'class="alert alert-success"';
			$icon		= 'true';
			$iconText	= JText::_('COM_PHOCAGALLERY_ENABLED');
		}
		
		$fOutput .= '<tr '.$bgStyle.'><td>'.JText::_('COM_PHOCAGALLERY_PHP_SETTINGS_PARAM') .' allow_url_fopen ('.JText::_('COM_PHOCAGALLERY_ENABLED_IF_CURL_DISABLED') .')</td>';
		$fOutput .=  '<td align="center">'.JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-true.png', JText::_('COM_PHOCAGALLERY_ENABLED') ).'</td>';
		$fOutput .=  '<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-'.$icon.'.png', JText::_($iconText)).'</td></tr>';
	
	
		if(function_exists("curl_init")){
			$bgStyle 	= 'class="alert alert-success"';
			$icon		= 'true';
			$iconText	= JText::_('COM_PHOCAGALLERY_ENABLED');
		} else {
			$bgStyle = 'class="alert alert-error"';
			$icon		= 'false';
			$iconText	= JText::_('COM_PHOCAGALLERY_DISABLED');
		}
		
		if(function_exists("json_decode")){
			$bgStylej 	= 'class="alert alert-success"';
			$iconj		= 'true';
			$iconTextj	= JText::_('COM_PHOCAGALLERY_ENABLED');
		} else {
			$bgStylej = 'class="alert alert-error"';
			$iconj		= 'false';
			$iconTextj	= JText::_('COM_PHOCAGALLERY_DISABLED');
		}

		$fOutput .= '<tr '.$bgStyle.'><td>'.JText::_('COM_PHOCAGALLERY_FUNCTION') .' cURL ('.JText::_('COM_PHOCAGALLERY_ENABLED_IF_FOPEN_DISABLED') .')</td>';
		$fOutput .=  '<td align="center">'.JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-true.png', JText::_('COM_PHOCAGALLERY_ENABLED') ).'</td>';
		$fOutput .=  '<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-'.$icon.'.png', JText::_($iconText)).'</td></tr>';
		
		$fOutput .= '<tr '.$bgStylej.'><td>'.JText::_('COM_PHOCAGALLERY_FUNCTION') .' json_decode</td>';
		$fOutput .=  '<td align="center">'.JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-true.png', JText::_('COM_PHOCAGALLERY_ENABLED') ).'</td>';
		$fOutput .=  '<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-'.$iconj.'.png', JText::_($iconTextj)).'</td></tr>';
		

		$this->assignRef('tmpl',	$tmpl);
		$this->assignRef('foutput',	$fOutput);
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	
	protected function addToolBar(){
		require_once JPATH_COMPONENT.'/helpers/phocagallerycp.php';
		$canDo = PhocaGalleryCpHelper::getActions(NULL);
        JToolbarHelper ::title(JText::_('COM_PHOCAGALLERY_PG_INFO'), 'info');
		
		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = JToolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocagallery" class="btn btn-small"><i class="icon-home-2" title="'.JText::_('COM_PHOCAGALLERY_CONTROL_PANEL').'"></i> '.JText::_('COM_PHOCAGALLERY_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);
		
		if ($canDo->get('core.admin')) {
			JToolbarHelper ::preferences('com_phocagallery');
		}
	    JToolbarHelper ::help( 'screen.phocagallery', true );	   
    }
}
?>
