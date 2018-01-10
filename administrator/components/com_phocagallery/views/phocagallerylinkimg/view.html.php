<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
use Joomla\String\StringHelper;
 
class phocaGalleryCpViewphocaGalleryLinkImg extends JViewLegacy
{
	var $_context 	= 'com_phocagallery.phocagallerylinkimg';

	function display($tpl = null) {
		$app	= JFactory::getApplication();
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.keepalive');
		JHtml::_('formbehavior.chosen', 'select');
		
		//Frontend Changes
		$tUri = '';
		$jsLink = JURI::base(true);
		if (!$app->isClient('administrator')) {
			$tUri = JURI::base();
			phocagalleryimport('phocagallery.render.renderadmin');
			phocagalleryimport('phocagallery.file.filethumbnail');
			$jsLink = JURI::base(true).'/administrator';
		}
		$document	=JFactory::getDocument();
		$uri		= JFactory::getURI();
		$db		    =JFactory::getDBO();
		JHTML::stylesheet( 'media/com_phocagallery/css/administrator/phocagallery.css' );
		JHTML::stylesheet( 'components/com_phocagallery/assets/jcp/picker.css' );
		$document->addScript(JURI::root(true) .'/components/com_phocagallery/assets/jcp/picker.js');
		
		$eName				= JFactory::getApplication()->input->get('e_name');
		$tmpl['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$tmpl['type']		= JFactory::getApplication()->input->get( 'type', 1, '', 'int' );
		$tmpl['backlink']	= $tUri.'index.php?option=com_phocagallery&amp;view=phocagallerylinks&amp;tmpl=component&amp;e_name='.$tmpl['ename'];
		
		

		
		$params = JComponentHelper::getParams('com_phocagallery') ;

		//Filter
		
		$filter_state		= $app->getUserStateFromRequest( $this->_context.'.filter_state',	'filter_state', '',	'word' );
		$filter_catid		= $app->getUserStateFromRequest( $this->_context.'.filter_catid',	'filter_catid',	0, 'int' );
		$filter_order		= $app->getUserStateFromRequest( $this->_context.'.filter_order',	'filter_order',	'a.ordering', 'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $this->_context.'.filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$search				= $app->getUserStateFromRequest( $this->_context.'.search', 'search', '',	'string' );
		$search				= StringHelper::strtolower( $search );

		// Get data from the model
		$items					=  $this->get( 'Data');
		$total					=  $this->get( 'Total');
		$tmpl['pagination'] 	=  $this->get( 'Pagination' );
		
		// build list of categories
		$javascript 	= 'class="inputbox" size="1" onchange="submitform( );"';
		
		// get list of categories for dropdown filter	
		$filter = '';
		
		// build list of categories
		$javascript 	= 'class="inputbox" size="1" onchange="submitform( );"';
		
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
		. ' WHERE a.published = 1'
		. ' AND a.approved = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocagallerys = $db->loadObjectList();

		$tree = array();
		$text = '';
		$tree = PhocaGalleryCategory::CategoryTreeOption($phocagallerys, $tree, 0, $text, -1);
		array_unshift($tree, JHTML::_('select.option', '0', '- '.JText::_('COM_PHOCAGALLERY_SELECT_CATEGORY').' -', 'value', 'text'));
		$lists['catid'] = JHTML::_( 'select.genericlist', $tree, 'filter_catid',  $javascript , 'value', 'text', $filter_catid );
		//-----------------------------------------------------------------------
	
		// state filter
		$lists['state']		= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;

		// search filter
		$lists['search']	= $search;
		
		$user = JFactory::getUser();
		$uriS = $uri->toString();
		$this->assignRef('tmpl',		$tmpl);
		$this->assignRef('button',		$button);
		$this->assignRef('user',		$user);
		$this->assignRef('items',		$items);
		$this->assignRef('request_url',	$uriS);
		
		switch($tmpl['type']) {
			
			case 2:
			
				$i = 0;
				$itemsCount = $itemsStart = array();
				foreach($items as $key => $value) {
					
					$itemsCount[$i] = new StdClass();
					$itemsCount[$i]->value 	= $key;
					$itemsCount[$i]->text	= $key;
					$itemsStart[$i] = new StdClass();
					$itemsStart[$i]->value 	= $key;
					$itemsStart[$i]->text	= $key;
					$i++;
				}
				
				// Don't display it if no category is selected
				if($i > 0) {
					$itemsCount[$i] = new StdClass();
					$itemsCount[$i]->value 	= (int)$key + 1;
					$itemsCount[$i]->text	= (int)$key + 1;
				}
				$categoryId		= JFactory::getApplication()->input->get( 'filter_catid', 0, '', 'int' );
				$categoryIdList	= $app->getUserStateFromRequest( $this->_context.'.filter_catid',	'filter_catid',	0, 'int' );
				
				if ((int)$categoryId == 0 && $categoryIdList == 0) {
					$itemsCount = $itemsStart = array();
				}
				
				$lists['limitstartparam'] = JHTML::_( 'select.genericlist', $itemsStart, 'limitstartparam',  '' , 'value', 'text', '' );
				$lists['limitcountparam'] = JHTML::_( 'select.genericlist', $itemsCount, 'limitcountparam',  '' , 'value', 'text', '' );
				$this->assignRef('lists',		$lists);
				parent::display('images');
			break;
		
			case 3:
				$this->assignRef('lists',		$lists);
				parent::display('switchimage');
			break;
			
			case 4:
				$this->assignRef('lists',		$lists);
				parent::display('slideshow');
			break;
		
			case 1:
			Default:
				$this->assignRef('lists',		$lists);
				parent::display($tpl);
			break;
		
		}
	}
}
?>