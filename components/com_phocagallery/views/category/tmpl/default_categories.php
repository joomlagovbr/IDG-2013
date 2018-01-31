<?php
defined('_JEXEC') or die('Restricted access'); 
echo '<div id="phocagallery-categories-detail" class="pg-cvcsv">'."\n";
foreach ($this->itemscv as $ck => $cv) {
	echo '<div class="'.$cv->cls.'"><a href="'.$cv->link.'" >'.$cv->title.'</a> ';
	if ($cv->numlinks > 0) {
		echo ' <span class="pg-cvcsv-count">('.$cv->numlinks.')</span>'. "\n";
	}
	echo '</div>'."\n";
}
echo '</div>'."\n";
?>