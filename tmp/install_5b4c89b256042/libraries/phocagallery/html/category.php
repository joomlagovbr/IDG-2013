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
defined('_JEXEC') or die;
class PhocaGalleryCategory
{
	public static function options($type = 0, $ignorePublished = 0)
	{
		if ($type == 1) {
			$tree[0] 			= new JObject();
			$tree[0]->text 		= JText::_('COM_PHOCAGALLERY_MAIN_CSS');
			$tree[0]->value 	= 1;
			$tree[1] 			= new JObject();
			$tree[1]->text 		= JText::_('COM_PHOCAGALLERY_CUSTOM_CSS');
			$tree[1]->value 	= 2;
			return $tree;
		}
		
		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a';
		if ($ignorePublished == 0) {
			$query .= ' WHERE a.published = 1';
		}
		$query .= ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocagallerys = $db->loadObjectList();
	
		$catId	= -1;
		
		$javascript 	= 'class="inputbox" size="1" onchange="submitform( );"';
		
		$tree = array();
		$text = '';
		$tree = self::CategoryTreeOption($phocagallerys, $tree, 0, $text, $catId);
		
		return $tree;

	}
	
	public static function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId) {		

		foreach ($data as $key) {	
			$show_text =  $text . $key->text;
			
			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new JObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = self::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );	
			}	
		}
		return($tree);
	}
}
