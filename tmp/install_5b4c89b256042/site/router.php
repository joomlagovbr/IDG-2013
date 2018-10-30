<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

/**
 * Method to build Route
 * @param array $query
 */ 
defined('_JEXEC') or die;
function PhocaGalleryBuildRoute(&$query)
{
	
	
	static $items;
	$segments	= array();
	$itemid		= null;
	

	// Break up the weblink/category id into numeric and alias values.
	if (isset($query['id']) && strpos($query['id'], ':')) {
		list($query['id'], $query['alias']) = explode(':', $query['id'], 2);
	}

	// Break up the category id into numeric and alias values.
	if (isset($query['catid']) && strpos($query['catid'], ':')) {
		list($query['catid'], $query['catalias']) = explode(':', $query['catid'], 2);
	}

	// Get the menu items for this component.
	if (!$items) {
		//$component	= &JComponentHelper::getComponent('com_phocagallery');
		$menu		= JFactory::getApplication()->getMenu();
		$items		= $menu->getItems('component', 'com_phocagallery');
	}

	// Search for an appropriate menu item.
	if (is_array($items))
	{
		// If only the option and itemid are specified in the query, return that item.
		if (!isset($query['view']) && !isset($query['id']) && !isset($query['catid']) && isset($query['Itemid'])) {
			$itemid = (int) $query['Itemid'];
		}

		
		// ------------------------------------------------------
		// Search for a specific link based on the critera given.
		if (!$itemid)
		{
			foreach ($items as $item)
			{
				// Check if this menu item links to this view.
				if (isset($item->query['view']) && ($item->query['view'] == 'detail' || $item->query['view'] == 'map' || $item->query['view'] == 'feed' || $item->query['view'] == 'info' || $item->query['view'] == 'comment' )
					&& isset($query['view']) && $query['view'] != 'category'
					&& isset($item->query['id']) && $item->query['id'] == $query['id']) {
						$itemid	= $item->id;
				}
				else if (isset($item->query['view']) && $item->query['view'] == 'category'
					&& isset($query['view']) && ($query['view'] != 'detail' || $query['view'] != 'map' || $query['view'] != 'feed' || $query['view'] != 'info' || $query['view'] != 'comment')
					/*&& isset($item->query['catid']) && $item->query['catid'] == $query['catid']) {*/
					&& isset($item->query['catid']) && isset($query['catid']) && $item->query['catid'] == $query['catid']) {
						$itemid	= $item->id;
				}
				
			}
		}

		// If no specific link has been found, search for a general one.
		/*if (!$itemid)
		{
			
			foreach ($items as $item)
			{
				 if (isset($query['view']) && ($query['view'] == 'detail' || $query['view'] == 'map')
					&& isset($item->query['view']) && $item->query['view'] == 'category'
					&& isset($item->query['id']) && isset($query['catid'])
					&& $query['catid'] == $item->query['id'])
				{
					// This menu item links to the weblink view but we need to append the weblink id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['catalias']) ? $query['catid'].':'.$query['catalias'] : $query['catid'];
					$segments[]	= isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
					if(isset($query['view'])) { $segments[]	= $query['view'];}
					break;
				}
				
				
				else if (isset($query['view']) && $query['view'] == 'category'
					&& isset($item->query['view']) && $item->query['view'] == 'category'
					&& isset($item->query['id']) && isset($query['id']) && $item->query['id'] != $query['id'])
				{
					// This menu item links to the category view but we need to append the category id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
					if(isset($query['view'])) { $segments[]	= $query['view'];}
					break;
				}

			}
		}

		// Search for an even more general link.
		if (!$itemid)
		{
			echo "4";
			foreach ($items as $item)
			{
				if (isset($query['view']) && ($query['view'] == 'detail' || $query['view'] == 'map') && isset($item->query['view'])
					&& $item->query['view'] == 'categories' && isset($query['catid']) && isset($query['id']))
				{
					// This menu item links to the categories view but we need to append the category and weblink id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['catalias']) ? $query['catid'].':'.$query['catalias'] : $query['catid'];
					$segments[]	= isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
					if(isset($query['view'])) { $segments[]	= $query['view'];}
					break;
				}
				
				else if (isset($query['view']) && $query['view'] == 'category' && isset($item->query['view'])
					&& $item->query['view'] == 'categories' && !isset($query['catid']))
				{
					// This menu item links to the categories view but we need to append the category id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
					if(isset($query['view'])) { $segments[]	= $query['view'];}
					break;
				}
			}
		}*/
		
		
	}

	
	
	
	


	// Check if the router found an appropriate itemid.
	if (!$itemid)
	{
	
	if (isset($query['view']) && $query['view'] == 'feed') {
			if(isset($query['view'])) {$segments[]	= $query['view'];}
			if(isset($query['id'])) {$segments[]	= $query['id'];}

			unset($query['view']);
			unset($query['id']);
			unset($query['alias']);
		
		} else if (isset($query['view']) && $query['view'] == 'category' && isset($query['id'])) {
			if (isset($query['alias'])) {
				$query['id'] .= ':'.$query['alias'];
			}

			// Push the catid onto the stack.
			//$segments[] = $query['id'];
			if(isset($query['view'])) {$segments[]	= $query['view'];}
			$segments[] = $query['id'];
			unset($query['view']);
			unset($query['id']);
			unset($query['alias']);
		} else if (isset($query['id'])) { // Check if a id was specified.
			if (isset($query['catalias'])) {
				$query['catid'] .= ':'.$query['catalias'];
			}

			// Push the catid onto the stack.
			if(isset($query['view']) && $query['view'] != 'user') {
				if (isset($query['catid'])) {
					$segments[] = $query['catid'];
				}
			}
			
			if (isset($query['alias'])) {
				$query['id'] .= ':'.$query['alias'];
			}

			// Push the id onto the stack.
			//$segments[] = $query['id'];
			if(isset($query['view'])) {$segments[]	= $query['view'];}
			$segments[] = $query['id'];
			unset($query['view']);
			unset($query['id']);
			unset($query['alias']);
			unset($query['catid']);
			unset($query['catalias']);
		}else if (isset($query['catid'])) {
			if (isset($query['alias'])) {
				$query['catid'] .= ':'.$query['catalias'];
			}

			// Push the catid onto the stack.
			//$segments[]	= 'category';
			//$segments[] = $query['catid'];
			if(isset($query['view'])) { $segments[]	= $query['view'];}
			$segments[]	= 'category';
			$segments[] = $query['catid'];
			unset($query['view']);
			unset($query['catid']);
			unset($query['catalias']);
			unset($query['alias']);
		} else {
			// Categories view.
			unset($query['view']);
		}
	} else {
		$query['Itemid'] = $itemid;

		// Remove the unnecessary URL segments.
		unset($query['view']);
		unset($query['id']);
		unset($query['alias']);
		unset($query['catid']);
		unset($query['catalias']);
	}
	return $segments;
}

/**
 * Method to parse Route
 * @param array $segments
 */ 
function PhocaGalleryParseRoute($segments) {
	$vars = array();
	$menu = JFactory::getApplication()->getMenu();
	$item = $menu->getActive();

	// Count route segments
	$count = count($segments);
	
	//Standard routing
	if(!isset($item))  {
		if($count == 4 ) {
			$vars['view']  = $segments[$count - 3];
		
		// UNDER TESTING
		} else if($count == 3) {
            $vars['view']    = $segments[$count - 2];
            $segments[$count - 2] = $segments[$count - 3];
			// UNDER TESTING
        } else {
			$vars['view'] = 'category';
		}
		$vars['catid']	= $segments[$count - 2];//-4
		$vars['id']    	= $segments[$count - 1];
		
		
	} else {
		switch($item->query['view']) {
			case 'categories' :
				
				if ($count == 1 && isset($segments[0]) && $segments[0] == 'feed') {
					$vars['view'] = 'feed';
				} else if($count == 1) {
					$vars['view'] = 'category';
				}

				if ($count == 2 && isset($segments[0]) && $segments[0] == 'feed') {
					$vars['view'] = 'feed';
				} else if($count == 2) {
					$vars['view'] = 'category';
					$vars['id'] 	= $segments[$count-1];
				}
				
				if($count == 3) {
					$vars['catid']	= $segments[$count-3];
					$vars['view']	= $segments[$count-2];
					$vars['id']		= $segments[$count-1];
				}
			break;

			case 'category'   :
				if($count == 1) {
					$vars['view'] 	= 'category';
				}

				if($count == 2) {
					$vars['view'] 	= 'category';
					$vars['id'] 	= $segments[$count-1];
				}
				
				if($count == 3) {
					$vars['catid']	= $segments[$count-3];
					$vars['view']	= $segments[$count-2];
					$vars['id']		= $segments[$count-1];
				}
			break;
			
			case 'user'   :
				if($count == 1) {
					$vars['view'] 	= 'user';
				}

				if($count == 2) {
					$vars['view'] 	= 'user';
					$vars['id'] 	= $segments[$count-1];
				}
				
				if($count == 3) {
					$vars['catid']	= $segments[$count-3];
					$vars['view']	= $segments[$count-2];
					$vars['id']		= $segments[$count-1];
				}
			break;
			
			case 'detail'   :
				if($count == 1) {
					$vars['view'] 	= 'detail';
				}

				if($count == 2) {
					$vars['view'] 	= 'detail';
					$vars['id'] 	= $segments[$count-1];
				}
				
				if($count == 3) {
					$vars['catid']	= $segments[$count-3];
					$vars['view']	= $segments[$count-2];
					$vars['id']		= $segments[$count-1];
				}
			break;
			
			case 'feed'   :
				if($count == 1) {
					$vars['view'] 	= 'feed';
				}

				if($count == 2) {
					$vars['view'] 	= 'feed';
					$vars['id'] 	= $segments[$count-1];
				}
				
				if($count == 3) {
					$vars['catid']	= $segments[$count-3];
					$vars['view']	= $segments[$count-2];
					$vars['id']		= $segments[$count-1];
				}
			break;
		
		}
	}


	return $vars;
}
?>