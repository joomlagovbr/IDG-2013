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
	
	// - - - - -
	if ( (int)$this->tmpl['categoriescolumns'] == 1 ) {
		echo '<div>';
	} else {
		$float = 0;
		foreach ($this->tmpl['begin'] as $k => $v) {
			if ($i == $v) {
				$float = 1;
			}
		}
		if ($float == 1) {		
			echo '<div style="'.$this->tmpl['fixedwidthstyle2'].'" class="pg-cats-box-float">';
		}
	}
	// - - - - -

	echo '<div class="pg-field">'."\n"
		.' <div class="pg-legend">'
		.'  <a href="'.$this->categories[$i]->link.'" >'.$this->categories[$i]->title_self.'</a> ';
		
	if ($this->categories[$i]->numlinks > 0) {
		echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';
	}	
		
	echo ' </div>'."\n";
	
	echo '<div class="pg-cat-img-detail '.$this->tmpl['class_suffix'].'">'."\n"
		.'<div class="pg-cat-img-detail-box">'."\n"
		.' <table border="0" cellpadding="0" cellspacing="0">'."\n"
		.'  <tr>'."\n"
		.'   <td style="text-align:center;"><a href="'.$this->categories[$i]->link.'">';
	
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
	if ($this->categories[$i]->description != '') {
	   echo '<div class="pg-field-desc" >'.$this->categories[$i]->description.'</div><p>&nbsp;</p>';
	}
	echo '<table class="pg-field-table" border="0" cellpadding="0" cellspacing="0" >'."\n";
	if ( $this->categories[$i]->username != '') {
		echo '<tr><td>'.JText::_('COM_PHOCAGALLERY_AUTHOR') .': </td>'
			.'<td>'.$this->categories[$i]->username.'</td></tr>'."\n";
	}

	
   echo '<tr><td>'.JText::_('COM_PHOCAGALLERY_NR_IMG_CATEGORY') .': </td>'
	.'<td>'.$this->categories[$i]->numlinks.'</td></tr>'."\n";

	echo '<tr><td>'.JText::_('COM_PHOCAGALLERY_CATEGORY_VIEWED') .': </td>'
	 .'<td>'.$this->categories[$i]->hits.'x</td></tr>'."\n";
	



	// Rating
	if ($this->tmpl['displayrating'] == 1) {
		$votesCount = $votesAverage = $votesWidth = 0;
		if (!empty($this->categories[$i]->ratingcount)) {
			$votesCount = $this->categories[$i]->ratingcount;
		}
		if (!empty($this->categories[$i]->ratingaverage)) {
			$votesAverage = $this->categories[$i]->ratingaverage;
			if ($votesAverage > 0) {
				$votesAverage 	= round(((float)$votesAverage / 0.5)) * 0.5;
				$votesWidth		= 22 * $votesAverage;
			}
			
		}
		if ((int)$votesCount > 1) {
			$votesText = 'COM_PHOCAGALLERY_VOTES';
		} else {
			$votesText = 'COM_PHOCAGALLERY_VOTE';
		}
		
		echo '<tr><td>' . JText::_('COM_PHOCAGALLERY_RATING'). ': </td>'
			.'<td>' . $votesAverage .' / '.$votesCount . ' ' . JText::_($votesText). '</td></tr>'
			.'<tr><td>&nbsp;</td>'
			.'<td>'
			.' <ul class="star-rating">'
			.'  <li class="current-rating" style="width:'.$votesWidth.'px"></li>'
			.'   <li><span class="star1"></span></li>';
		for ($r = 2;$r < 6;$r++) {
			echo '<li><span class="stars'.$r.'"></span></li>';
		}
		echo '</ul>'
			 .'</td>'
			 .'</tr>'."\n";
	}
	
	echo '</table>'."\n"
		 .'</div>'."\n"
		 //.'<div style="clear:both;"></div>'
		 .'</div>'."\n"
		 .'<div style="clear:both;"></div>'
		 .'</div>'."\n";//fieldset

	// - - - - - 
	if ( (int)$this->tmpl['categoriescolumns'] == 1 ) {
		echo '</div>';
	} else {
		if ($i == $this->tmpl['endfloat']) {
			echo '</div><div style="clear:both"></div>'."\n";
		} else {
			$float = 0;
			foreach ($this->tmpl['end'] as $k => $v) {
				if ($i == $v) {
					$float = 1;
				}
			}
			if ($float == 1) {		
				echo '</div>'."\n";
			}
		}
	}
// - - - - -
	
}
echo '</div>'."\n";
echo "\n";
?>