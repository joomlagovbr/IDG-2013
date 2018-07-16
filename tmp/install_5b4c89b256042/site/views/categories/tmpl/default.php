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

// Obsolete methods
switch($this->tmpl['display_image_categories']) {

	case 0:
		echo $this->loadTemplate('obs_catimgdetailtitleonly');
	break;
	
	case 2:
		echo $this->loadTemplate('obs_catimgdetail');
	break;
	
	case 3:
		echo $this->loadTemplate('obs_catimgdetailfloat');
	break;
	
	case 4:
		echo $this->loadTemplate('obs_catimgdesc');
	break;
	
	case 5:
		echo $this->loadTemplate('obs_custom');
	break;
	
	case 1:
	default:
		echo $this->loadTemplate('categories');
	break;
}




echo $this->loadTemplate('pagination');
echo $this->tmpl['set'];
echo '</div>';