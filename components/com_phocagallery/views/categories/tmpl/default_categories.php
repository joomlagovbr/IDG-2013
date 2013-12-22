<?php
defined('_JEXEC') or die('Restricted access');

echo '<div id="pg-msnr-container">';
foreach ($this->categories as $ck => $cv){


	
	echo '<div class="pg-csv-box">'."\n";
	echo ' <div class="pg-csv-box-img pg-box1">'. "\n";
	echo '  <div class="pg-box2">'. "\n";
	echo '   <div class="pg-box3">'. "\n";
	
	echo '<a href="'.$cv->link.'">'. "\n";
	
	if (isset($cv->mosaic) && $cv->mosaic != '') {
		echo $cv->mosaic;
	} else if (isset($cv->extpic) && $cv->extpic != '') {
		$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($cv->extw, $cv->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
		//echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->title, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
		echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->title, array( 'style' => 'width:'. $correctImageRes['width'] .'px;height:'.$correctImageRes['height'] .'px;'));
		
	} else {
		echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->title);
	}
	
	echo '</a>'. "\n";
	
	echo '    </div>'. "\n";
	echo '  </div>'. "\n";
	echo ' </div>'. "\n";
	
	
	echo '<div class="pg-csv-name"><a href="'.$cv->link.'">'.PhocaGalleryText::wordDelete($cv->title_self, $this->tmpl['char_cat_length_name'], '...').'</a>';
	if ($cv->numlinks > 0) {
		echo ' <span class="pg-csv-count">('.$cv->numlinks.')</span>'. "\n";
	}
	echo '</div>'. "\n";
	
	
	if ($this->tmpl['display_cat_desc_box'] == 1  && $cv->description != '') {
		echo '<div class="pg-csv-descbox">'. strip_tags($cv->description).'</div>';
	} else if ($this->tmpl['display_cat_desc_box'] == 2  && $cv->description != '') {	
		echo '<div class="pg-csv-descbox">' .(JHtml::_('content.prepare', $cv->description, 'com_phocagallery.category')).'</div>';
	}
	
	echo $this->loadTemplate('rating');
	
	echo '</div>'. "\n";// End pg-csv-box
}
echo '</div>';
echo '<div class="ph-cb"></div>';
?>