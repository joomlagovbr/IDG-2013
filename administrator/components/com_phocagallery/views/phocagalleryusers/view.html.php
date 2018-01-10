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
phocagalleryimport('phocagallery.library.library');
phocagalleryimport('phocagallery.render.renderdetailwindow');

jimport( 'joomla.filesystem.file' ); 
class PhocaGalleryCpViewPhocaGalleryUsers extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	
	
	function display($tpl = null) {
		
		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');
		
		foreach ($this->items as &$item) {
			$this->ordering[0][] = $item->id;
		}
	
		$path 							= PhocaGalleryPath::getPath();
		$this->tmpl['avatarpathabs']	= $path->avatar_abs . '/thumbs/phoca_thumb_s_';
		$this->tmpl['avatarpathrel']	= $path->avatar_rel . 'thumbs/phoca_thumb_s_';
		$this->tmpl['avtrpathrel']		= $path->avatar_rel;

		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		$document	= JFactory::getDocument();
		//$document->addCustomTag(PhocaGalleryRenderAdmin::renderIeCssLink(1));
		
		// Button
		/*
		$this->button = new JObject();
		$this->button->set('modal', true);
		$this->button->set('methodname', 'modal-button');
		//$this->button->set('link', $link);
		$this->button->set('text', JText::_('COM_PHOCAGALLERY_DISPLAY_IMAGE_DETAIL'));
		//$this->button->set('name', 'image');
		$this->button->set('modalname', 'modal_phocagalleryusers');
		$this->button->set('options', "{handler: 'image', size: {x: 200, y: 150}}");*/
		
		$library 			= PhocaGalleryLibrary::getLibrary();
		$libraries			= array();
		$btn 				= new PhocaGalleryRenderDetailWindow();
		$btn->popupWidth 	= '640';
		$btn->popupHeight 	= '480';
		$btn->backend		= 1;
		
		$btn->setButtons(12, $libraries, $library);
		$this->button = $btn->getB1();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		

	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocagalleryusers.php';
		$state	= $this->get('State');
		$canDo	= PhocaGalleryUsersHelper::getActions($state->get('filter.category_id'));
		
		JToolbarHelper ::title( JText::_( 'COM_PHOCAGALLERY_USERS' ), 'users' );
		
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper ::custom('phocagalleryusers.publish', 'publish.png', 'publish_f2.png','JToolbar_PUBLISH', true);
			JToolbarHelper ::custom('phocagalleryusers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JToolbar_UNPUBLISH', true);
			JToolbarHelper ::custom( 'phocagalleryusers.approve', 'approve.png', '', 'COM_PHOCAGALLERY_APPROVE' , true);
			JToolbarHelper ::custom( 'phocagalleryusers.disapprove', 'disapprove.png', '', 'COM_PHOCAGALLERY_NOT_APPROVE' , true);
			JToolbarHelper ::divider();
		}
		
		if ($canDo->get('core.admin')) {
			$bar = JToolbar::getInstance('toolbar');
		/*$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(confirm(\''.addslashes(JText::_('COM_PHOCAGALLERY_WARNING_AUTHORIZE_ALL')).'\')){submitbutton(\'phocagalleryusers.approveall\');}" class="toolbar"><span class="icon-32-authorizeall" title="'.JText::_('COM_PHOCAGALLERY_APPROVE_ALL').'" type="Custom"></span>'.JText::_('COM_PHOCAGALLERY_APPROVE_ALL').'</a>');*/
		
			$dhtml = '<button class="btn btn-small" onclick="javascript:if(confirm(\''.addslashes(JText::_('COM_PHOCAGALLERY_WARNING_AUTHORIZE_ALL')).'\')){submitbutton(\'phocagalleryusers.approveall\');}" ><i class="icon-authorizeall" title="'.JText::_('COM_PHOCAGALLERY_APPROVE_ALL').'"></i> '.JText::_('COM_PHOCAGALLERY_APPROVE_ALL').'</button>';
			$bar->appendButton('Custom', $dhtml);
		
		
			JToolbarHelper ::divider();
		}
		
		if ($canDo->get('core.delete')) {
			JToolbarHelper ::deleteList(  'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS_AVATAR', 'phocagalleryusers.delete', 'COM_PHOCAGALLERY_DELETE');
		}
	
		JToolbarHelper ::divider();
		JToolbarHelper ::help( 'screen.phocagallery', true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'ua.username' 	=> JText::_('COM_PHOCAGALLERY_USER'),
			'a.published' 	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'a.approved' 	=> JText::_('COM_PHOCAGALLERY_APPROVED'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>