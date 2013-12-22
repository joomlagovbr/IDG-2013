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
 
class PhocaGalleryCpViewPhocaGalleryCoImgs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;


	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		
		foreach ($this->items as &$item) {
			$this->ordering[$item->image_id][] = $item->id;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocagallerycoimgs.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaGalleryCoImgsHelper::getActions($state->get('filter.category_id'));
	
		JToolBarHelper::title( JText::_( 'COM_PHOCAGALLERY_IMAGE_COMMENTS' ), 'comment' );
	
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('phocagallerycoimg.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('phocagallerycoimgs.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('phocagallerycoimgs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList(  JText::_( 'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS' ), 'phocagallerycoimgs.delete', 'COM_PHOCAGALLERY_DELETE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('COM_PHOCAGALLERY_ORDERING'),
			'ua.username' 	=> JText::_('COM_PHOCAGALLERY_USER'),
			'a.title'	 	=> JText::_('COM_PHOCAGALLERY_TITLE'),
			'a.date'	 	=> JText::_('COM_PHOCAGALLERY_DATE'),
			'a.published'	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'image_title' 	=> JText::_('COM_PHOCAGALLERY_IMAGE'),
			'category_title' => JText::_('COM_PHOCAGALLERY_CATEGORY'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>