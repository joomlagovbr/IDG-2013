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
for ($i = 0; $i < $this->tmpl['countcategories']; $i++) {
	if ( (int)$this->tmpl['categoriescolumns'] == 1 ) {
		echo '<table border="0">'."\n";
	} else {
		$float = 0;
		foreach ($this->tmpl['begin'] as $k => $v) {
			if ($i == $v) {
				$float = 1;
			}
		}
		if ($float == 1) {		
			echo '<div style="'.$this->tmpl['fixedwidthstyle1'].'" class="pg-cats-box-float"><table>'."\n";
		}
	}

	echo '<tr>'."\n";		
	echo '<td align="center" valign="middle" style="'.$this->tmpl['imagebg'].';text-align:center;"><div class="pg-imgbg"><a href="'.$this->categories[$i]->link.'">';

	if (isset($this->categories[$i]->extpic) && $this->categories[$i]->extpic) {
		$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($this->categories[$i]->extw, $this->categories[$i]->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;', '-',$this->categories[$i]->title), array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'style' => ''));
	} else {
		echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;','-',$this->categories[$i]->title),array('style' => ''));
	}
	
	echo '</a></div></td>';
	echo '<td><a href="'.$this->categories[$i]->link.'">'.$this->categories[$i]->title.'</a>&nbsp;';
	
	if ($this->categories[$i]->numlinks > 0) {echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';}
	
	echo '</td>';
	echo '</tr>'."\n";
	
	if ( (int)$this->tmpl['categoriescolumns'] == 1 ) {
		echo '</table>'."\n";
	} else {
		if ($i == $this->tmpl['endfloat']) {
			echo '</table></div><div style="clear:both"></div>'."\n";
		} else {
			$float = 0;
			foreach ($this->tmpl['end'] as $k => $v)
			{
				if ($i == $v) {
					$float = 1;
				}
			}
			if ($float == 1) {		
				echo '</table></div>'."\n";
			}
		}
	}
}
echo "\n";
?>