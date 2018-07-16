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
	
	
echo '<div class="row">';	
for ($i = 0; $i < $this->tmpl['countcategories']; $i++) {
	
	//echo '<div style="width:'.$this->tmpl['categoriesboxwidth'].';" class="pg-cats-box-float">'."\n";
	
	
	echo '<div class="span4 col-xs-6 col-sm-4 col-md-4">';
	
	echo '<div class="pg-cats-box '.$this->tmpl['class_suffix'].'">'."\n"
		.'<div class="pg-cats-box-img"><a href="'.$this->categories[$i]->link.'">';
	
	if (isset($this->categories[$i]->extpic) && $this->categories[$i]->extpic) {
		$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($this->categories[$i]->extw, $this->categories[$i]->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;', '-',$this->categories[$i]->title), array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-cats-image',  'style' => ''));
	} else {
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;', '-',$this->categories[$i]->title), array('class' => 'pg-cats-image', 'style' => ''));
	}

	echo '</a>'
		.'</div>'."\n"
		.'<div class="pg-cats-name '.$this->tmpl['class_suffix'].'"><a href="'.$this->categories[$i]->link.'">'.$this->categories[$i]->title_self.'</a> ';
		if ($this->categories[$i]->numlinks > 0) {
			echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';
		}
	echo '</div><div style="clear:both"></div>'."\n"
		 //.'<div style="clear:both;"></div>'
		.'</div></div>'."\n";
	
	
	
}
echo '</div>';
//echo '<div style="clear:both"></div>'."\n";
echo '</div>'."\n";
echo "\n";
?>