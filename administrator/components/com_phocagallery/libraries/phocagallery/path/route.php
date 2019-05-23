<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.helper');

class PhocaGalleryRoute
{
	public static function getCategoriesRoute() {
		
		// TEST SOLUTION
		$app 		= JFactory::getApplication();
		$menu 		= $app->getMenu();
		$active 	= $menu->getActive();
		
		
		$activeId 	= 0;
		if (isset($active->id)){
			$activeId    = $active->id;
		}
		
		$itemId 	= 0;
		/* There cannot be $item->id yet
		// 1) get standard item id if exists
		if (isset($item->id)) {
			$itemId = (int)$item->id;
		}*/
		
		$option			= $app->input->get( 'option', '', 'string' );
		$view			= $app->input->get( 'view', '', 'string' );
		if ($option == 'com_phocagallery' && $view == 'category') {
			if ((int)$activeId > 0) {
				// 2) if there are two menu links, try to select the one active
				$itemId = $activeId;
			}
		}
		
		$needles = array(
			'categories' => ''
		);
		
		$link = 'index.php?option=com_phocagallery&view=categories';

		if($item = PhocaGalleryRoute::_findItem($needles, 1)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			
			
			// TEST SOLUTION
			/*if ((int)$itemId > 0) {
				$link .= '&Itemid='.(int)$itemId;
			} else if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}*/
			
			// $item->id should be a "categories view" and it should have preference to category view
			// so first we check item->id then itemId
			
			// 1) there can be two categories view, when yes, first set itemId then item->id
			// 2) but when there is one category view, and one categories view - first select item->id (categories view)
			// 3) then select itemid even we don't know if categories or category view
			
			if ((int)$itemId > 0 && isset($active->query['view']) && $active->query['view'] == 'categories') {
				$link .= '&Itemid='.(int)$itemId;
			} else if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			} else if ((int)$itemId > 0) {
				$link .= '&Itemid='.(int)$itemId;
			}
		};

		
		return $link;
	}
	
	public static function getCategoryRoute($catid, $catidAlias = '') {
		
		// TEST SOLUTION
		$app 		= JFactory::getApplication();
		$menu 		= $app->getMenu();
		$active 	= $menu->getActive();
		$option		= $app->input->get( 'option', '', 'string' );
		
		
		$activeId 	= 0;
		$notCheckId	= 0;
		if (isset($active->id)){
			$activeId    = $active->id;
		}
		if ((int)$activeId > 0 && $option == 'com_phocagallery') {
		
			$needles 	= array(
				'category' => (int)$catid,
				'categories' => (int)$activeId
			);
			$notCheckId = 0;// when categories view, do not check id
			// we need to check the ID - there can be more menu links (to categories, to category)
		} else {
			$needles = array(
				'category' => (int)$catid,
				'categories' => ''
			);
			$notCheckId = 0;
		}

		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		
		//Create the link
		$link = 'index.php?option=com_phocagallery&view=category&id='. $catid;

		if($item = PhocaGalleryRoute::_findItem($needles, $notCheckId)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}
	
	public static function getFeedRoute($view = 'categories', $catid = 0, $catidAlias = '') {
			
		if ($view == 'categories') {
			$needles = array(
				'categories' => ''
			);
			$link = 'index.php?option=com_phocagallery&view=categories&format=feed';
		
		} else if ($view == 'category') {
			if ($catid > 0) {
				$needles = array(
					'category' => (int) $catid,
					'categories' => ''
				);
				if ($catidAlias != '') {
					$catid = (int)$catid . ':' . $catidAlias;
				}
				
				$link = 'index.php?option=com_phocagallery&view=category&format=feed&id='.$catid;
				
			} else {
				$needles = array(
				'categories' => ''
				);
				$link = 'index.php?option=com_phocagallery&view=categories&format=feed';
			}
		} else {
			$needles = array(
				'categories' => ''
			);
			$link = 'index.php?option=com_phocagallery&view=feed&format=feed';
		}

			
		if($item = PhocaGalleryRoute::_findItem($needles, 1)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}
	
	
	

	
	
	
	public static function getCategoryRouteByTag($tagId) {
		$needles = array(
			'category' => '',
			//'section'  => (int) $sectionid,
			'categories' => ''
		);
		
		$db =JFactory::getDBO();
				
		$query = 'SELECT a.id, a.title, a.link_ext, a.link_cat'
		.' FROM #__phocagallery_tags AS a'
		.' WHERE a.id = '.(int)$tagId
		.' ORDER BY a.id';

		$db->setQuery($query, 0, 1);
		$tag = $db->loadObject();
		
		

		//Create the link
		if (isset($tag->id)) {
			$link = 'index.php?option=com_phocagallery&view=category&id=tag&tagid='.(int)$tag->id;
		} else {
			$link = 'index.php?option=com_phocagallery&view=category&id=tag&tagid=0';
		}

		if($item = PhocaGalleryRoute::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
		
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}
	


	public static function getImageRoute($id, $catid = 0, $idAlias = '', $catidAlias = '', $type = 'detail', $suffix = '')
	{
		// TEST SOLUTION
		$app 		= JFactory::getApplication();
		$menu 		= $app->getMenu();
		$active 	= $menu->getActive();
		$option		= $app->input->get( 'option', '', 'string' );
		
		$activeId 	= 0;
		$notCheckId	= 0;
		if (isset($active->id)){
			$activeId    = $active->id;
		}
		
		if ((int)$activeId > 0 && $option == 'com_phocagallery') {

			$needles = array(
				'detail'  => (int) $id,
				'category' => (int) $catid,
				'categories' => (int)$activeId
			);
			$notCheckId	= 1;
		} else {
			$needles = array(
				'detail'  => (int) $id,
				'category' => (int) $catid,
				'categories' => ''
			);
			$notCheckId	= 0;
		}
		
		
		if ($idAlias != '') {
			$id = $id . ':' . $idAlias;
		}
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}
		
		//Create the link
		
		switch ($type)
		{
			case 'detail':
				$link = 'index.php?option=com_phocagallery&view=detail&catid='. $catid .'&id='. $id;
				break;

			default:
				$link = '';
			break;
		}

		if ($item = PhocaGalleryRoute::_findItem($needles, $notCheckId)) {
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		}
		
		if ($suffix != '') {
			$link .= '&'.$suffix;
		}

		return $link;
	}

	protected static function _findItem($needles, $notCheckId = 0) {
		//$component =& JComponentHelper::getComponent('com_phocagallery');

		$app	= JFactory::getApplication();
		$menus	= $app->getMenu('site', array());
		$items	= $menus->getItems('component', 'com_phocagallery');
		
		

		if(!$items) {
			return JFactory::getApplication()->input->get('Itemid', 0, '', 'int');
			//return null;
		}
		
		$match = null;
		
		foreach($needles as $needle => $id) {
			
			if ($notCheckId == 0) {
				foreach($items as $item) {
					if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
						$match = $item;
						break;
					}
				}
			} else {
				foreach($items as $item) {
				
					if (@$item->query['view'] == $needle) {
						$match = $item;
						break;
					}
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}
}
?>
