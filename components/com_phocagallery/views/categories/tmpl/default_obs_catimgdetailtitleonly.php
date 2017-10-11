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
	

	
	echo '<div style="clear:both;"></div>'
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