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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
phocagalleryimport( 'phocagallery.ordering.ordering');
phocagalleryimport( 'phocagallery.picasa.picasa');
phocagalleryimport( 'phocagallery.facebook.fbsystem');

class PhocaGalleryViewCategory extends JViewLegacy
{

	function display($tpl = null) {
	
		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$userLevels	= implode (',', $user->getAuthorisedViewLevels());
		$db 		= JFactory::getDBO();
		$menu 		= $app->getMenu();
		$document	= JFactory::getDocument();
		$params 	= $app->getParams();
		
		// Specific category
		$id 				= $app->input->get('id', 0, 'int');
		// Params
		$categories 		= $params->get( 'feed_cat_ids', '' );
		$ordering			= $params->get( 'feed_img_ordering', 6 );
		$imgCount			= $params->get( 'feed_img_count', 5 );
		$feedTitle			= $params->get( 'feed_title', JText::_('COM_PHOCAGALLERY_GALLERY') );
		
		$tmpl['picasa_correct_width_m']		= (int)$params->get( 'medium_image_width', 100 );	
		$tmpl['picasa_correct_height_m']	= (int)$params->get( 'medium_image_height', 100 );

		$document->setTitle($this->escape( html_entity_decode($feedTitle)));
		
		if($id > 0) {
			$wheres[]	= ' c.id ='.(int)$id;
		} else {
			if (count($categories) > 1) {
				JArrayHelper::toInteger($categories);
				$categoriesString	= implode(',', $categories);
				$wheres[]	= ' c.id IN ( '.$categoriesString.' ) ';
			} else if ((int)$categories > 0) {
				$wheres[]	= ' c.id IN ( '.$categories.' ) ';
			}
		}
		
		$imageOrdering 		= PhocaGalleryOrdering::getOrderingString($ordering, 6);
		
		$wheres[]	= ' a.published = 1';
		$wheres[]	= ' a.approved = 1';
		$wheres[]	= ' c.published = 1';
		$wheres[]	= ' c.approved = 1';
		$wheres[] 	= ' c.access IN ('.$userLevels.')';
		$u = " (c.accessuserid LIKE '%0%' OR c.accessuserid LIKE '%-1%' OR c.accessuserid LIKE '%,".(int)$user->id."' OR c.accessuserid LIKE '".(int)$user->id.",%' OR c.accessuserid LIKE '%,".(int)$user->id.",%' OR c.accessuserid =".(int)$user->id.") ";
		
		$e = 'c.accessuserid IS NULL';
		
		$wheres[] = ' CASE WHEN c.accessuserid IS NOT NULL THEN '.$u.' ELSE '.$e.' END';
		
		$query = 'SELECT a.*, c.alias as catalias, c.title as categorytitle'
			.' FROM #__phocagallery AS a'
			.' LEFT JOIN #__phocagallery_categories AS c ON a.catid = c.id'
			. ' WHERE ' . implode( ' AND ', $wheres )
			.$imageOrdering['output'];
		

		$db->setQuery( $query , 0, $imgCount );	
		$images = $db->loadObjectList( );


		foreach ($images as $keyI => $value) {
			
			$item = new JFeedItem();
			
			
			$title 				= $this->escape( $value->title );
			$title 				= html_entity_decode( $title );
			$item->title 		= $title;

			$link 				= PhocaGalleryRoute::getCategoryRoute($value->catid, $value->catalias);

			$item->link 		= JRoute::_($link);
			
			
			
			// imgDate
			$imgDate = '';
			$imgDate = JHtml::Date($value->date, "Y-m-d h:m:s");
			
			
			if ($imgDate != '') {
				$item->date			= $imgDate;
			}
			
			$item->description = '';
			if ($value->description != '') {
				$item->description .= '<div>'.$value->description.'</div>';
			}
			$extImage = false;
			if (isset($value->extid)) {
				$extImage = PhocaGalleryImage::isExtImage($value->extid);
			}
			
			// Trying to fix but in Joomla! method $this->_relToAbs - it cannot work with JRoute links :-(
			$itemL = str_replace(JURI::base(true), '', $item->link);
			if (substr($itemL, 0, 1) == '/') {
				$itemL = substr_replace($itemL, '', 0, 1);
			}
			$itemL = JURI::base().$itemL;
			// Should really not happen
			$itemLTmp 	= str_replace('http://', '', $itemL);
			$pos 		= stripos($itemLTmp, '//');
			if ($pos !== false) {
				$itemLTmp = str_replace('//', '/', $itemLTmp);
				$itemL = 'http://'.$itemLTmp;
			}
			// - - - - - - - - - - -
		
			if ($extImage) {
				$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($value->extw, $value->exth, $tmpl['picasa_correct_width_m'], $tmpl['picasa_correct_height_m']);
				$imgLink = $value->extm;
				//$i = '<div><a href="'.JRoute::_($link).'"><img src="'.$imgLink .'" border="0" width="'.$correctImageRes['width'].'" height="'.$correctImageRes['height'].'" /></a></div>';
				$i = '<div><a href="'.$itemL.'"><img src="'.$imgLink .'" border="0" /></a></div>';
			} else {
				$imgLink 	= PhocaGalleryImageFront::displayCategoryImageOrNoImage($value->filename, 'medium');
				$i = '<div><a href="'.$itemL.'"><img src="'. /*JURI::base(true) .*/ $imgLink.'" border="0" /></a></div>';
			}
			
			
			$item->description 	.= $i;
			$item->category   	= $value->categorytitle;
		
			/*if ($value->author != '') {
				$item->author		= $value->author;
			}*/
			$document->addItem( $item );
		}
	}
}
?>