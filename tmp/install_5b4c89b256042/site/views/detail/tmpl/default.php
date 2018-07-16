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
echo '<div id="phocagallery" class="pg-detail-view'.$this->params->get( 'pageclass_sfx' ).'">';
if ($this->tmpl['backbutton'] != '') {
	echo $this->tmpl['backbutton'];
}

if($this->tmpl['responsive'] == 1) {
	$iW = '';
	$iH = '';
} else {
	$iW = 'width:'.$this->item->realimagewidth. 'px;';
	$iH = 'height:'.$this->tmpl['largeheight'].'px;';
}

switch ($this->tmpl['detailwindow']) {
	case 4:
	case 7:
	case 9:
	case 10:
	case 11:
		$closeImage 	= $this->item->linkimage;
		$closeButton 	= '';
	break;

	
	default:
		$closeButton 	= '<td align="center">' . str_replace("%onclickclose%", $this->tmpl['detailwindowclose'], $this->item->closebutton). '</td>';
		$closeImage 	= '<a href="#" onclick="'.$this->tmpl['detailwindowclose'].'" style="margin:auto;padding:0">'.$this->item->linkimage.'</a>';
	break;

}

echo '<div class="ph-mc" style="padding-top:10px">'
	.'<table border="0" class="ph-w100 ph-mc" cellpadding="0" cellspacing="0">'
	.'<tr>'
	.'<td colspan="6" align="center" valign="middle"'
	.' style="'.$iH.'vertical-align: middle;" >'
	.'<div id="phocaGalleryImageBox" style="'.$iW.'margin: auto;padding: 0;">'
	.$closeImage;
			
$titleDesc = '';
if ($this->tmpl['display_title_description'] == 1) {
	$titleDesc .= $this->item->title;
	if ($this->item->description != '' && $titleDesc != '') {
		$titleDesc .= ' - ';
	}
}
			
// Lightbox Description
if ($this->tmpl['displaydescriptiondetail'] == 2 && (!empty($this->item->description) || !empty($titleDesc))){

	echo '<div id="description-msg"  class="pg-dv-desc-lightbox">'
    .'<div id="description-text"  class="pg-dv-desc-lightbox">'
	//. $titleDesc . $this->item->description.'</div></div>';
	.(JHtml::_('content.prepare', $titleDesc . $this->item->description, 'com_phocagallery.item')).'</div></div>';
}

echo '</div>'
	 .'</td>'
	 .'</tr>';
	
echo '<tr><td colspan="6"><div style="padding:0;margin:0;height:3px;font-size:0px;">&nbsp;</div></td></tr>';
// Standard Description
if ($this->tmpl['displaydescriptiondetail'] == 1) {
	echo '<tr>'
	.'<td colspan="6" align="left" valign="top" class="pg-dv-desc">'
	.'<div class="pg-dv-desc">'
	//. $titleDesc . $this->item->description . '</div>'
	.(JHtml::_('content.prepare', $titleDesc . $this->item->description, 'com_phocagallery.item')). '</div>'
	.'</td>'
	.'</tr>';
}

if ($this->tmpl['detailbuttons'] == 1){
	echo '<tr>'
	.'<td align="left" width="30%" style="padding-left:48px">'.$this->item->prevbutton.'</td>'
	.'<td align="center">'.$this->item->slideshowbutton.'</td>'
	.'<td align="center">'.str_replace("%onclickreload%", $this->tmpl['detailwindowreload'], $this->item->reloadbutton).'</td>'
	. $closeButton
	.'<td align="right" width="30%" style="padding-right:48px">'.$this->item->nextbutton.'</td>'
	.'</tr>';
}

echo '</table></div>';
echo $this->loadTemplate('rating');
// Tags
if ($this->tmpl['displaying_tags_output'] != '') {
	echo '<div class="pg-detail-tags">'.$this->tmpl['displaying_tags_output'].'</div>';
}
if ($this->tmpl['detailwindow'] == 7) {
	
	
	
	if ($this->tmpl['externalcommentsystem'] == 1) {
		if (JComponentHelper::isEnabled('com_jcomments', true)) {
			include_once(JPATH_BASE.'/components/com_jcomments/jcomments.php');
			echo JComments::showComments($this->item->id, 'com_phocagallery_images', JText::_('COM_PHOCAGALLERY_IMAGE') .' '. $this->item->title);
		}
	} else if ($this->tmpl['externalcommentsystem'] == 2) {
		echo $this->loadTemplate('comments-fb');	
	}
	echo '<div style="text-align:right;color:#ccc;display:block">Powered by <a href="https://www.phoca.cz/phocagallery">Phoca Gallery</a></div>';
}
echo '</div>';
echo '<div id="phocaGallerySlideshowC" style="display:none"></div>';
?>