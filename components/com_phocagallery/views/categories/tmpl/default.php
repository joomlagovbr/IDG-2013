<?php
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery" class="pg-categories-view'.$this->params->get( 'pageclass_sfx' ).' pg-csv">';
if ( $this->params->get( 'show_page_heading' ) ) { 
	echo '<div class="page-header"><h1>'. $this->escape($this->params->get('page_heading')) . '</h1></div>';
}

echo '<div id="pg-icons">';
echo PhocaGalleryRenderFront::renderFeedIcon('categories');
echo '</div>';
echo '<div class="ph-cb"></div>';


if ($this->tmpl['categories_description'] != '') {
	echo '<div class="pg-csv-desc" >'.JHTML::_('content.prepare', $this->tmpl['categories_description']).'</div>';
}
echo $this->loadTemplate('categories');
echo $this->loadTemplate('pagination');
echo $this->tmpl['tl'];
echo '</div>';