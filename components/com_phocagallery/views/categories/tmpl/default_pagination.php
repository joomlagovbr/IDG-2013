<?php defined('_JEXEC') or die('Restricted access'); 

if ($this->params->get('show_ordering_categories') || $this->params->get('show_pagination_limit_categories') || $this->params->get('show_pagination_categories')) {
	echo '<form action="'.htmlspecialchars($this->tmpl['action']).'" method="post" name="adminForm">'. "\n";
	if (count($this->categories)) {
		echo '<div class="pagination pagination-centered">';
		if ($this->params->get('show_ordering_categories')) {
			echo JText::_('COM_PHOCAGALLERY_ORDER_FRONT') .'&nbsp;'.$this->tmpl['ordering'];
		}
		if ($this->params->get('show_pagination_limit_categories')) {
			echo JText::_('COM_PHOCAGALLERY_DISPLAY_NUM') .'&nbsp;'.$this->tmpl['pagination']->getLimitBox();
		}
		if ($this->params->get('show_pagination_categories')) {
		
			echo '<div class="counter pull-right">'.$this->tmpl['pagination']->getPagesCounter().'</div>'
				.'<div class="pagination pagination-centered">'.$this->tmpl['pagination']->getPagesLinks().'</div>';
		}
		echo '</div>'. "\n";

	}
	echo '<input type="hidden" name="controller" value="categories" />';
	echo JHtml::_( 'form.token' );
	echo '</form>';
	
	echo '<div class="ph-cb pg-csv-paginaton">&nbsp;</div>';
} else {
	echo '<div class="ph-cb pg-csv-paginaton">&nbsp;</div>';
}
?>