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

class PhocaGalleryPhocaCart
{
	public static function getPcLink($id, &$errorMsg) {
		
		$link = '';
		if (JComponentHelper::isEnabled('com_phocacart', true)) {
			if ((int)$id < 1) {
				return "";
			}
		} else {
			return "";
		}
		
		if (is_file( JPATH_ADMINISTRATOR .  '/components/com_phocacart/libraries/phocacart/product/product.php')) {
			require_once( JPATH_ADMINISTRATOR .  '/components/com_phocacart/libraries/phocacart/product/product.php' );
			
			$v = PhocacartProduct::getProduct($id);
			
			if(isset($v->id) && $v->id > 0 && isset($v->catid) && $v->catid > 0 && isset($v->alias) && isset($v->catalias)) {
				$link = JRoute::_(PhocacartRoute::getItemRoute($v->id, $v->catid, $v->alias, $v->catalias));
			}
		}
		
		return $link;	
	}
}