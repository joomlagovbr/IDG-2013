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

JHtml::_('jquery.framework', false);
$document	= JFactory::getDocument();
// jQuery(\'input[type=file]\').click(function(){
$document->addScriptDeclaration(
'jQuery(document).ready(function(){
	jQuery(\'.phfileuploadcheckcat\').click(function(){
	if( !jQuery(\'#filter_catid_image\').val() || jQuery(\'#filter_catid_image\').val() == 0) { 
		alert(\''.JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY').'\'); return false;
	} else {
		return true;
	}
})});'
);

echo '<div id="phocagallery-ucp" class="pg-ucp-view'.$this->params->get( 'pageclass_sfx' ).'">'. "\n";

$heading = '';
if ($this->params->get( 'page_title' ) != '') {
	$heading .= $this->params->get( 'page_title' );
}

if ($this->tmpl['showpageheading'] != 0) {
	if ( $heading != '') {
	    echo '<h1>'
	        .$this->escape($heading)
			.'</h1>';
	} 
}
$tab = 0;
switch ($this->tmpl['tab']) {
	case 'up':
		$tab = 1;
	break;
	
	case 'cc':
	default:
		$tab = 0;
	break;
}

echo '<div>&nbsp;</div>';

if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocagallery-pane">';
	echo JHtml::_('tabs.start', 'config-tabs-com_phocagallery-user', array('useCookie'=>1, 'startOffset'=> $this->tmpl['tab']));
	echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('user', $this->tmpl['pi'].'icon-user.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_USER'), 'user' );
	echo $this->loadTemplate('user');

	echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('category', $this->tmpl['pi'].'icon-folder-small.png', '') . '&nbsp;'.$this->tmpl['categorycreateoredithead'], 'category' );
	echo $this->loadTemplate('category');

	echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('subcategory', $this->tmpl['pi'].'icon-subcategories.png', ''). '&nbsp;'.JText::_('COM_PHOCAGALLERY_SUBCATEGORIES'), 'subcategories' );
	echo $this->loadTemplate('subcategories');

	echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('image', $this->tmpl['pi'].'icon-images.png', ''). '&nbsp;'.JText::_('COM_PHOCAGALLERY_IMAGES'), 'images' );
	echo $this->loadTemplate('images');

	echo JHtml::_('tabs.end');
	echo '</div>';
}
echo '<div>&nbsp;</div>';
echo '<div style="text-align:right;color:#ccc;display:block">Powered by <a href="https://www.phoca.cz/phocagallery">Phoca Gallery</a></div>';
echo '</div>';
?>
