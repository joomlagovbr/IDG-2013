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

class PhocaGalleryVirtueMart
{
	public static function getVmLink($id, &$errorMsg) {
		
		if (JComponentHelper::isEnabled('com_virtuemart', true)) {
			if ((int)$id < 1) {
				return "";
			}
		} else {
			return "";
		}
		
		$db =JFactory::getDBO();
				
		$query = 'SELECT c.virtuemart_category_id AS catid, a.virtuemart_product_id AS id, a.published AS published, a.product_in_stock AS product_in_stock'
		.' FROM #__virtuemart_product_categories AS c'
		.' LEFT JOIN #__virtuemart_products AS a ON a.virtuemart_product_id = c.virtuemart_product_id'
		.' WHERE c.virtuemart_product_id = '.(int)$id;

		$db->setQuery($query, 0, 1);
		$product = $db->loadObject();
		
		
		
		$catPart = '';
		if (!empty($product->catid)) {
			$catPart = '&virtuemart_category_id='.$product->catid;
		}
		
		$itemId		= PhocaGalleryVirtueMart::_getVmItemid();
		
		$link = 'index.php?option=com_virtuemart&view=productdetails'
				.'&virtuemart_product_id='.(int)$id
				.$catPart
				.'&itemId='.(int)$itemId;
				
				
		// Check PUBLISHED		
		if (isset($product->published) && $product->published == 0) {
			$errorMsg = 'VirtueMart Product Not Published';
			return '';//don't display cart icon for unpublished product
		}
		
		// Check Stock if check stock feature is enabled
		//$component			=	'com_virtuemart';
		//$paramsC			= JComponentHelper::getParams($component) ;
		if (is_file( JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php')) {
			 require_once( JPATH_ADMINISTRATOR  . '/components/com_virtuemart/helpers/config.php' );
			 
			 VmConfig::loadConfig();
			 if (VmConfig::get('check_stock',0) == 1) {
				// Check STOCK		
				if (isset($product->product_in_stock) && $product->product_in_stock == 0) {
					$errorMsg = 'VirtueMart Product Not On Stock';
					return '';//don't display cart icon for non stock products
				}
			 }
		} else {
			$errorMsg = 'VirtueMart Config Not Found';
			return false;
		}
		return $link;
	}

	
	protected static function _getVmItemid() {
		
		
		
		$db =JFactory::getDBO();		
		$query = 'SELECT a.id AS id, a.link as link'
		.' FROM #__menu AS a'
		.' WHERE a.link LIKE '.$db->Quote('%index.php?option=com_virtuemart%')
		.' AND published = 1';

		$db->setQuery($query);
		$vmLinks = $db->loadObjectList();
		
		
		//$vmLinks[0]->link
		$itemId = 0;
		if (!empty($vmLinks)) {
			foreach($vmLinks as $k => $v) {
				if(isset($v->link) && $v->link == 'index.php?option=com_virtuemart&view=virtuemart') {
					//Found
					$itemId = $v->id;
					break;
				}
			}
			
			if ($itemId < 1) {
				//Not found - try to find next possible itemid
				foreach($vmLinks as $k => $v) {
					if(isset($v->link) && $v->link == 'index.php?option=com_virtuemart&view=categories') {
						//Found
						$itemId = $v->id;
						break;
					}
				}
			}
			
			if ($itemId < 1) {
				//Still Not found - try to find next possible itemid
				foreach($vmLinks as $k => $v) {
					if(isset($v->link) && strpos($v->link, 'index.php?option=com_virtuemart&view=category') !== false) {
						//Found
						$itemId = $v->id;
						break;
					}
				}
			}
			
			if ($itemId < 1) {
				//Still Not found - try to find next possible itemid
				foreach($vmLinks as $k => $v) {
					if(isset($v->link) && strpos($v->link, 'index.php?option=com_virtuemart&view=productdetails') !== false) {
						//Found
						$itemId = $v->id;
						break;
					}
				}
			}
		}
		return $itemId;
	}
}