<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
phocagalleryimport( 'phocagallery.rate.ratecategory' );

class PhocaGalleryCpViewPhocaGalleryCs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	//protected $_context 	= 'com_phocagallery.phocagalleryc';

	function display($tpl = null) {
	
	
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->parent_id][] = $item->id;
		}
		
		/*
		 * We need to load all items because of creating tree
		 * After creating tree we get info from pagination
		 * and will set displaying of categories for current pagination
		 * E.g. pagination is limitstart 5, limit 5 - so only categories from 5 to 10 will be displayed
		 */
		
		if (!empty($this->items)) {
			$text = ''; // text is tree name e.g. Category >> Subcategory
			$tree = array();
			$this->items = $this->processTree($this->items, $tree, 0, $text, -1, 0);
		}
		
		//$mainframe	= JFactory::getApplication();
		//$document	= JFactory::getDocument();
		//$uri		= JFactory::getURI();

		
		
		$this->tmpl['notapproved'] 	= $this->get( 'NotApprovedCategory' );
	

		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		$document	= & JFactory::getDocument();
		//$document->addCustomTag(PhocaGalleryRenderAdmin::renderIeCssLink(1));
		
		
		$params 	= JComponentHelper::getParams('com_phocagallery');

		
		$this->tmpl['enablethumbcreation']			= $params->get('enable_thumb_creation', 1 );
		$this->tmpl['enablethumbcreationstatus'] 	= PhocaGalleryRenderAdmin::renderThumbnailCreationStatus((int)$this->tmpl['enablethumbcreation']);


	


		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocagallerycs.php';

		$state	= $this->get('State');
		$canDo	= PhocaGalleryCsHelper::getActions($state->get('filter.category_id'));
		$user  = JFactory::getUser();
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title( JText::_( 'COM_PHOCAGALLERY_CATEGORIES' ), 'folder' );
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('phocagalleryc.add','JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('phocagalleryc.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('phocagallerycs.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('phocagallerycs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom( 'phocagallerycs.approve', 'approve.png', '', 'COM_PHOCAGALLERY_APPROVE' , true);
			JToolBarHelper::custom( 'phocagallerycs.disapprove', 'disapprove.png', '',  'COM_PHOCAGALLERY_NOT_APPROVE' , true);
			JToolBarHelper::custom('phocagallerycs.cooliris', 'cooliris.png', '',  'COM_PHOCAGALLERY_COOLIRIS' , true);
		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList( JText::_( 'COM_PHOCAGALLERY_WARNING_DELETE_ITEMS' ), 'phocagallerycs.delete', 'COM_PHOCAGALLERY_DELETE');
		}
		// Add a batch button
		if ($user->authorise('core.edit'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}
	
	/*TODO - change it to php 5 rules */
	protected function processTree( $data, $tree, $id = 0, $text='', $currentId, $level, $parentsTreeString = '') {
	
		$countItemsInCat 	= 0;// Ordering
		$level 				= $level + 1;
		$parentsTreeString	= $id . ' '. $parentsTreeString;
		foreach ($data as $key) {	
			$show_text 	= $text . $key->title;
			
			static $iCT = 0;// All displayed items
			
			if ($key->parent_id == $id && $currentId != $id && $currentId != $key->id ) {	

				
				$tree[$iCT] 					= new JObject();
				
							
				
				// Ordering MUST be solved here
				if ($countItemsInCat > 0) {
					$tree[$iCT]->orderup				= 1;
				} else {
					$tree[$iCT]->orderup 				= 0;
				}
				
				if ($countItemsInCat < ($key->countid - 1)) {
					$tree[$iCT]->orderdown 				= 1;
				} else {
					$tree[$iCT]->orderdown 				= 0;
				}
				
				$tree[$iCT]->level				= $level;		
				$tree[$iCT]->parentstree		= $parentsTreeString;
				
				$tree[$iCT]->id 				= $key->id;
				$tree[$iCT]->title 				= $show_text;
				$tree[$iCT]->title_self 		= $key->title;
				$tree[$iCT]->parent_id			= $key->parent_id;
				$tree[$iCT]->owner_id			= $key->owner_id;
				$tree[$iCT]->name				= $key->name;
				$tree[$iCT]->alias				= $key->alias;
				$tree[$iCT]->image				= $key->image;
				$tree[$iCT]->section			= $key->section;
				$tree[$iCT]->image_position		= $key->image_position;
				$tree[$iCT]->description		= $key->description;
				$tree[$iCT]->published			= $key->published;
				$tree[$iCT]->editor				= $key->editor;
				$tree[$iCT]->ordering			= $key->ordering;
				$tree[$iCT]->access				= $key->access;
				$tree[$iCT]->access_level		= $key->access_level;
				$tree[$iCT]->count				= $key->count;
				$tree[$iCT]->params				= $key->params;
				$tree[$iCT]->checked_out		= $key->checked_out;
				$tree[$iCT]->checked_out_time	= $key->checked_out_time;
				$tree[$iCT]->groupname			= 0;
				$tree[$iCT]->username			= $key->username;
				$tree[$iCT]->usernameno			= $key->usernameno;
				$tree[$iCT]->parentcat_title	= $key->parentcat_title;
				$tree[$iCT]->parentcat_id		= $key->parentcat_id;
				$tree[$iCT]->hits				= $key->hits;
				$tree[$iCT]->ratingavg			= $key->ratingavg;
				$tree[$iCT]->accessuserid		= $key->accessuserid;
				$tree[$iCT]->uploaduserid		= $key->uploaduserid;
				$tree[$iCT]->deleteuserid		= $key->deleteuserid;
				$tree[$iCT]->userfolder			= $key->userfolder;
				$tree[$iCT]->latitude			= $key->latitude;
				$tree[$iCT]->longitude			= $key->longitude;
				$tree[$iCT]->zoom				= $key->zoom;
				$tree[$iCT]->geotitle			= $key->geotitle;
				$tree[$iCT]->approved			= $key->approved;
				$tree[$iCT]->language			= $key->language;
				$tree[$iCT]->language_title		= $key->language_title;
				$tree[$iCT]->link				= '';
				$tree[$iCT]->filename			= '';// Will be added in View (after items will be reduced)
				$tree[$iCT]->linkthumbnailpath	= '';

				$iCT++;
				
				$tree = $this->processTree($data, $tree, $key->id, $show_text . " - ", $currentId, $level, $parentsTreeString);
				
				$countItemsInCat++;
			}
			
		}
		
		return($tree);
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_('COM_PHOCAGALLERY_TITLE'),
			'a.published' 	=> JText::_('COM_PHOCAGALLERY_PUBLISHED'),
			'a.approved' 	=> JText::_('COM_PHOCAGALLERY_APPROVED'),
			'parent_title' 	=> JText::_('COM_PHOCAGALLERY_PARENT_CATEGORY'),
			'a.owner' 		=> JText::_('COM_PHOCAGALLERY_OWNER'),
			'ratingavg' 	=> JText::_('COM_PHOCAGALLERY_RATING'),
			'a.hits' 		=> JText::_('COM_PHOCAGALLERY_HITS'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>