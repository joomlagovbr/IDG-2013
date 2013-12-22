<?php defined('_JEXEC') or die('Restricted access'); 

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
	echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->tmpl['pi'].'icon-user.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_USER'), 'user' );
	echo $this->loadTemplate('user');

	echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->tmpl['pi'].'icon-folder-small.png', '') . '&nbsp;'.$this->tmpl['categorycreateoredithead'], 'category' );
	echo $this->loadTemplate('category');

	echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->tmpl['pi'].'icon-subcategories.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_SUBCATEGORIES'), 'subcategories' );
	echo $this->loadTemplate('subcategories');

	echo JHtml::_('tabs.panel', JHtml::_( 'image', $this->tmpl['pi'].'icon-images.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_IMAGES'), 'images' );
	echo $this->loadTemplate('images');

	echo JHtml::_('tabs.end');
	echo '</div>';
}
echo '<div>&nbsp;</div>';
echo PhocaGalleryRenderFront::renderInfo();
echo '</div>';
?>
