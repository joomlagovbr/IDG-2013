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

jimport('joomla.html.pagination');
class PhocaGalleryPaginationCategories extends JPagination
{
	public function getLimitBox() {
		$app	= JFactory::getApplication();
		
		$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
		$pagination 		= $paramsC->get( 'pagination_categories', '5,10,15,20,50' );
		$paginationArray	= explode( ',', $pagination );
		
		// Initialize variables
		$limits = array ();

		foreach ($paginationArray as $paginationValue) {
			$limits[] = JHTML::_('select.option', $paginationValue);
		}
		$limits[] = JHTML::_('select.option', '0', JText::_('COM_PHOCAGALLERY_ALL'));

		$selected = $this->viewall ? 0 : $this->limit;

		// Build the select list
		if ($app->isClient('administrator')) {
			$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="inputbox input-mini" size="1" onchange="Joomla.submitform();"', 'value', 'text', $selected);
		} else {
			$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="inputbox input-mini" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
	
		return $html;
	}
}
?>