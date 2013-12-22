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
 
class PhocaGalleryCpViewPhocaGalleryEfs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;


	function display($tpl = null) {
		
		$model				= $this->getModel();
		$model->checkItems();
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		
		foreach ($this->items as &$item) {
			$this->ordering[$item->type][] = $item->id;
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
	
		require_once JPATH_COMPONENT.'/helpers/phocagalleryefs.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaGalleryEfsHelper::getActions($state->get('filter.category_id'));
	
		JToolBarHelper::title( JText::_( 'COM_PHOCAGALLERY_STYLES' ), 'eye' );
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew( 'phocagalleryef.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('phocagalleryef.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('phocagalleryefs.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('phocagalleryefs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList(  JText::_( 'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS' ), 'phocagalleryefs.delete', 'COM_PHOCAGALLERY_DELETE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('COM_PHOCAGALLERY_ORDERING'),
			'a.title'	 	=> JText::_('COM_PHOCAGALLERY_TITLE'),
			'a.filename'	=> JText::_('COM_PHOCAGALLERY_FILENAME'),
			'a.published'	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'a.type'	 	=> JText::_('COM_PHOCAGALLERY_TYPE'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>