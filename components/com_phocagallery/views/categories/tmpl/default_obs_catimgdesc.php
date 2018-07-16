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
echo "\n\n";
echo '<div id="phocagallery-categories-detail">'."\n";
	
for ($i = 0; $i < $this->tmpl['countcategories']; $i++) {
	
	echo '<div style="width:'.$this->tmpl['categoriesboxwidth'].';" class="pg-cats-box-float2">'."\n";

	/*echo '<fieldset>'
		.' <legend>'
		.'  <a href="'.$this->categories[$i]->link.'" class="category'.$this->params->get( 'pageclass_sfx' ).'">'.$this->categories[$i]->title_self.'</a> ';
		
	if ($this->categories[$i]->numlinks > 0) {
		echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';
	}	
		
	echo ' </legend>';*/
	
	echo '<div class="pg-cat-img-detail '.$this->tmpl['class_suffix'].'">'."\n"
		.'<div class="pg-cat-img-detail-box">'."\n"
		.' <table border="0" cellpadding="0" cellspacing="0">'."\n"
		.'  <tr>'
		.'   <td style="text-align:center;vertical-align:middle;"><a href="'.$this->categories[$i]->link.'">';
	
	if (isset($this->categories[$i]->extpic) && $this->categories[$i]->extpic) {
		$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($this->categories[$i]->extw, $this->categories[$i]->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;', '-',$this->categories[$i]->title), array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'style' => ''));
	} else {
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;','-',$this->categories[$i]->title),array('style' => ''));
	}

	echo '</a></td>'
		.'  </tr>'."\n"
		.' </table>'."\n"
		.'</div>'."\n";
	
	
	echo '<div style="margin-right:5px;margin-left:'.$this->tmpl['imagewidth'].'px;">'."\n";
	
	echo '<div class="pg-field-table2"><a href="'.$this->categories[$i]->link.'" class="category'.$this->params->get( 'pageclass_sfx' ).'">'.$this->categories[$i]->title_self.'</a> ';
		
	if ($this->categories[$i]->numlinks > 0) {
		echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';
	}
	echo '</div>';
	
	if ($this->categories[$i]->description != '') {
	   echo '<div class="pg-field-desc2">'.$this->categories[$i]->description.'</div>'."\n";
	}
	
	echo '</div>'."\n"
		 //.'<div style="clear:both;"></div>'
		 .'</div>'."\n";

	
	echo '</div>'."\n";
	
}
echo '<div style="clear:both"></div>'."\n";
echo '</div>'."\n";
echo "\n";
?>