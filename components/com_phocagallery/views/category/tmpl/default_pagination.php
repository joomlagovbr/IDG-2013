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
defined('_JEXEC') or die('Restricted access'); 
if ($this->params->get('show_ordering_images') || $this->params->get('show_pagination_limit_category') || $this->params->get('show_pagination_category')) {
	echo '<form action="'.htmlspecialchars($this->tmpl['action']).'" method="post" name="adminForm">'. "\n";

	if (count($this->items)) {
		echo '<div class="pagination pagination-centered">';
		if ($this->params->get('show_ordering_images')) {
			echo JText::_('COM_PHOCAGALLERY_ORDER_FRONT') .'&nbsp;'.$this->tmpl['ordering'];
		}
		if ($this->params->get('show_pagination_limit_category')) {
			echo JText::_('COM_PHOCAGALLERY_DISPLAY_NUM') .'&nbsp;'.$this->tmpl['pagination']->getLimitBox();
		}
		if ($this->params->get('show_pagination_category')) {
		
			echo '<div class="counter pull-right">'.$this->tmpl['pagination']->getPagesCounter().'</div>'
				.'<div class="pagination pagination-centered">'.$this->tmpl['pagination']->getPagesLinks().'</div>';
		}
		echo '</div>'. "\n";

	}
	echo '<input type="hidden" name="controller" value="category" />';
	echo JHtml::_( 'form.token' );
	echo '</form>';
	
	echo '<div class="ph-cb pg-cv-paginaton">&nbsp;</div>';
} else {
	echo '<div class="ph-cb pg-csv-paginaton">&nbsp;</div>';
}
?>