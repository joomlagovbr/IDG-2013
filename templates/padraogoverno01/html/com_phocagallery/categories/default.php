<?php
defined('_JEXEC') or die('Restricted access'); 
require JPATH_SITE .'/templates/padraogoverno01/html/com_phocagallery/_helper.php';
//removendo css padrao do componente:
TmplPhocagalleryHelper::removeCss( array('phocagallery.css') );

echo '<div id="phocagallery" class="pg-categories-view'.$this->params->get( 'pageclass_sfx' ).'">';


if ( $this->params->get( 'show_page_heading' ) ) { 
	echo '<h1 class="borderHeading">'. $this->escape($this->params->get('page_heading')) . '</h1>';
}

if ($this->tmpl['categories_description'] != '') {
	echo '<div class="description" >';
	echo JHTML::_('content.prepare', $this->tmpl['categories_description'])
	.'</div>';
}

// echo $this->loadTemplate('categories');
// echo $this->loadTemplate('pagination');

echo '<form action="'.htmlspecialchars($this->tmpl['action']).'" method="post" name="adminForm">';

	echo $this->loadTemplate('categories');

	if (count($this->categories)) {
		
		echo '<div class="pagination row-fluid">';
			echo '<div class="text-center">';
				echo $this->tmpl['pagination']->getPagesLinks();
			
				echo '<p>';
				if ($this->params->get('show_pagination_categories'))
					echo $this->tmpl['pagination']->getPagesCounter();
				if ($this->params->get('show_pagination_categories') || $this->params->get('show_pagination_limit_categories'))
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				if ($this->params->get('show_pagination_limit_categories'))
					echo JText::_('JGLOBAL_DISPLAY_NUM') .'&nbsp;'.$this->tmpl['pagination']->getLimitBox();
				echo '</p>';
			
			echo '</div>';
		echo "</div>";

	}

echo '</form></div>';