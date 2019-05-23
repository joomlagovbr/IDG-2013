<?php defined('_JEXEC') or die('Restricted access'); 

echo '<form action="'.$this->tmpl['action'].'" method="post" name="adminForm">'. "\n";

if (count($this->items)) {
	echo '<div class="pagination row-fluid">';
			echo '<div class="text-center">';

			echo $this->tmpl['pagination']->getPagesLinks();

	echo '<p>';		
	
	if ($this->params->get('show_pagination_category')) {	 		
	 		echo $this->tmpl['pagination']->getPagesCounter() . '&nbsp;&nbsp;';
	}

	if ($this->params->get('show_ordering_images')) {
		
	}
	if ($this->params->get('show_pagination_limit_category')) {
		
			echo " &nbsp; ";
			echo JText::_('JGLOBAL_DISPLAY_NUM') .'&nbsp;'
			.$this->tmpl['pagination']->getLimitBox()
			;
			echo $this->tmpl['ordering'];
	}

	echo '</p>';
	echo '</div></div>'. "\n";

}
echo '<input type="hidden" name="controller" value="category" />';
echo JHtml::_( 'form.token' );
echo '</form>';