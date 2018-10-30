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

class PhocaGalleryOrdering
{
	/*
	 * Set Ordering String if Ordering is defined in Parameters
	 * 2 ... category
	 * 1 ... image
	 */
	public static function getOrderingString ($ordering, $type = 1) {
		
		$oO = array();
		// Default
		$oO['column'] 	= 'ordering';
		$oO['sort']		= 'ASC';

		switch($type) {
			case 2:		$oO['pref'] = $prefId = 'cc';	break;
			default:	$oO['pref'] = $prefId = 'a';	break;
		}
		
		switch ((int)$ordering) {
			case 2:
				$oO['column'] 	= 'ordering';
				$oO['sort']		= 'DESC';
			break;
			
			case 3:
				$oO['column'] 	= 'title';
				$oO['sort']		= 'ASC';
			break;
			
			case 4:
				$oO['column'] 	= 'title';
				$oO['sort']		= 'DESC';
			break;
			
			case 5:
				$oO['column'] 	= 'date';
				$oO['sort']		= 'ASC';
			break;
			
			case 6:
				$oO['column'] 	= 'date';
				$oO['sort']		= 'DESC';
			break;
			
			case 7:
				$oO['column'] 	= 'id';
				$oO['sort']		= 'ASC';
			break;
			
			case 8:
				$oO['column'] 	= 'id';
				$oO['sort']		= 'DESC';
			break;
			
			// Random will be used e.g. ORDER BY RAND()
			/* if ($imageOrdering == 9) {
					$imageOrdering = ' ORDER BY RAND()'; 
				} else {
					$imageOrdering = ' ORDER BY '.PhocaGalleryOrdering::getOrderingString($image_ordering);
				}
			*/
			case 9:
				$oO['column'] 	= '';
				$oO['sort']		= '';
				$oO['output']	= ' ORDER BY RAND()';
				return $oO;
				//$orderingOutput = '';
			break;
			
			// Is not ordered by recursive function needs not to be used
			case 10:
				$oO['column'] 	= '';
				$oO['sort']		= '';
				$oO['output']	= '';
				return $oO;
			break;
			
			case 11:
				$oO['column'] 	= 'count';
				$oO['sort']		= 'ASC';
				$oO['pref']		= 'r';
			break;
			case 12:
				$oO['column'] 	= 'count';
				$oO['sort']		= 'DESC';
				$oO['pref']		= 'r';
			break;
			 
			case 13:
				$oO['column'] 	= 'average';
				$oO['sort']		= 'ASC';
				$oO['pref']		= 'r';
			break;
			case 14:
				$oO['column'] 	= 'average';
				$oO['sort']		= 'DESC';
				$oO['pref']		= 'r';
			break;
			
			case 15:
				$oO['column'] 	= 'hits';
				$oO['sort']		= 'ASC';
			break;
			case 16:
				$oO['column'] 	= 'hits';
				$oO['sort']		= 'DESC';
			break;
		
			case 1:
			default:
				$oO['column'] 	= 'ordering';
				$oO['sort']		= 'ASC';
			break;
		}
		if ($oO['pref']	== 'r') {
			$oO['output']	= ' ORDER BY ' . $oO['pref'] . '.' . $oO['column'] . ' ' . $oO['sort'] . ', '.$prefId.'.id '.$oO['sort'];
		} else {
			$oO['output']	= ' ORDER BY ' . $oO['pref'] . '.' . $oO['column'] . ' ' . $oO['sort'];
		}
		
		return $oO;
	}
	
	public static function renderOrderingFront( $selected, $type = 1) {
		
		switch($type) {
			case 2:
				$typeOrdering 	= PhocaGalleryOrdering::getOrderingCategoryArray();
				$ordering		= 'catordering';
			break;
			
			default:
				$typeOrdering 	= PhocaGalleryOrdering::getOrderingImageArray();
				$ordering		= 'imgordering';
			break;
		}

		$html 	= JHTML::_('select.genericlist',  $typeOrdering, $ordering, 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		
		return $html;
	}
		
	public static function getOrderingImageArray() {
		$imgOrdering	= array(
				1 => JText::_('COM_PHOCAGALLERY_ORDERING_ASC'),
				2 => JText::_('COM_PHOCAGALLERY_ORDERING_DESC'),
				3 => JText::_('COM_PHOCAGALLERY_TITLE_ASC'),
				4 => JText::_('COM_PHOCAGALLERY_TITLE_DESC'),
				5 => JText::_('COM_PHOCAGALLERY_DATE_ASC'),
				6 => JText::_('COM_PHOCAGALLERY_DATE_DESC'),
				//7 => JText::_('COM_PHOCAGALLERY_ID_ASC'),
				//8 => JText::_('COM_PHOCAGALLERY_ID_DESC'),
				11 => JText::_('COM_PHOCAGALLERY_COUNT_ASC'),
				12 => JText::_('COM_PHOCAGALLERY_COUNT_DESC'),
				13 => JText::_('COM_PHOCAGALLERY_AVERAGE_ASC'),
				14 => JText::_('COM_PHOCAGALLERY_AVERAGE_DESC'),
				15 => JText::_('COM_PHOCAGALLERY_HITS_ASC'),
				16 => JText::_('COM_PHOCAGALLERY_HITS_DESC'));
		return $imgOrdering;
	}
	
	public static function getOrderingCategoryArray() {
		$imgOrdering	= array(
				1 => JText::_('COM_PHOCAGALLERY_ORDERING_ASC'),
				2 => JText::_('COM_PHOCAGALLERY_ORDERING_DESC'),
				3 => JText::_('COM_PHOCAGALLERY_TITLE_ASC'),
				4 => JText::_('COM_PHOCAGALLERY_TITLE_DESC'),
				5 => JText::_('COM_PHOCAGALLERY_DATE_ASC'),
				6 => JText::_('COM_PHOCAGALLERY_DATE_DESC'),
				//7 => JText::_('COM_PHOCAGALLERY_ID_ASC'),
				//8 => JText::_('COM_PHOCAGALLERY_ID_DESC'),
				11 => JText::_('COM_PHOCAGALLERY_COUNT_ASC'),
				12 => JText::_('COM_PHOCAGALLERY_COUNT_DESC'),
				13 => JText::_('COM_PHOCAGALLERY_AVERAGE_ASC'),
				14 => JText::_('COM_PHOCAGALLERY_AVERAGE_DESC'),
				15 => JText::_('COM_PHOCAGALLERY_HITS_ASC'),
				16 => JText::_('COM_PHOCAGALLERY_HITS_DESC'));
		return $imgOrdering;
	}
}
?>