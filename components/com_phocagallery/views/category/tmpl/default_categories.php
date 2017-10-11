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
echo '<div id="phocagallery-categories-detail" class="pg-cvcsv">'."\n";

foreach ($this->itemscv as $ck => $cv) {
	
	if ($this->tmpl['bootstrap_icons'] == 0) {
		$cls 	= 'class="'.$cv->cls.'"';
		$icon	= '';
	} else {
		$cls 	= '';
		$icon	= PhocaGalleryRenderFront::renderIcon($cv->iconcls, '', ''). ' ';
	}

	echo '<div '.$cls.'>'.$icon.'<a href="'.$cv->link.'" >'.$cv->title.'</a> ';
	if ($cv->numlinks > 0) {
		echo ' <span class="pg-cvcsv-count">('.$cv->numlinks.')</span>'. "\n";
	}
	echo '</div>'."\n";
}
echo '</div>'."\n";
?>