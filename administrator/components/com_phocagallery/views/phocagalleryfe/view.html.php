<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
phocagalleryimport( 'phocagallery.render.renderinfo' );
phocagalleryimport( 'phocagallery.utils.utils' );

class PhocaGalleryCpViewPhocaGalleryFe extends JViewLegacy
{
	protected $t;
	public function display($tpl = null) {
		
		$tmpl		= array();
		$params 	= JComponentHelper::getParams('com_phocagallery');
		
		$this->sidebar = JHtmlSidebar::render();
		
		JHTML::stylesheet( 'media/com_phocagallery/css/administrator/phocagallery.css' );
		$app		= JFactory::getApplication();
		
		$this->t['error'] = $app->input->get('error');
		switch ($this->t['error']) {
			case 1:
				$this->t['errormessage'] = JText::_('COM_PHOCAGALLERY_ERROR_1_MEMORY');
			break;
			
			default:
				$this->t['errormessage'] = JText::_('COM_PHOCAGALLERY_ERROR_1_MEMORY');//TO DO
			break;
		}
		$this->addToolbar();
		parent::display($tpl);
	}
	
	
	protected function addToolBar(){
		require_once JPATH_COMPONENT.'/helpers/phocagallerycp.php';
		$canDo = PhocaGalleryCpHelper::getActions(NULL);
        JToolbarHelper ::title(JText::_('COM_PHOCAGALLERY_PG_ERROR'), 'warning');
		
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
