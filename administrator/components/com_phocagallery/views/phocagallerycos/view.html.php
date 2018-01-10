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
 
class PhocaGalleryCpViewPhocaGalleryCos extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;


	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		
		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}

	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocagallerycos.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaGalleryCosHelper::getActions($state->get('filter.category_id'));
	
		JToolbarHelper ::title( JText::_( 'COM_PHOCAGALLERY_CATEGORY_COMMENTS' ), 'comment' );
	
		if ($canDo->get('core.edit')) {
			JToolbarHelper ::editList('phocagalleryco.edit','JToolbar_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper ::divider();
			JToolbarHelper ::custom('phocagallerycos.publish', 'publish.png', 'publish_f2.png','JToolbar_PUBLISH', true);
			JToolbarHelper ::custom('phocagallerycos.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JToolbar_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolbarHelper ::deleteList(  JText::_( 'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS' ), 'phocagallerycos.delete', 'COM_PHOCAGALLERY_DELETE');
		}
		JToolbarHelper ::divider();
		JToolbarHelper ::help( 'screen.phocagallery', true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('COM_PHOCAGALLERY_ORDERING'),
			'ua.username' 	=> JText::_('COM_PHOCAGALLERY_USER'),
			'a.title'	 	=> JText::_('COM_PHOCAGALLERY_TITLE'),
			'a.date'	 	=> JText::_('COM_PHOCAGALLERY_DATE'),
			'a.published'	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'category_title' => JText::_('COM_PHOCAGALLERY_CATEGORY'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>