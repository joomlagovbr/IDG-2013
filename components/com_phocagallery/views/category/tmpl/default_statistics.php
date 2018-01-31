<?php defined('_JEXEC') or die('Restricted access');

echo '<div id="phocagallery-statistics">';
echo '<div class="ph-tabs-iefix">&nbsp;</div>';//because of IE bug
	
	if ($this->tmpl['displaymaincatstat']) {
		echo '<h4>'.JText::_('COM_PHOCAGALLERY_CATEGORY').'</h4>'
		.'<table>'
		.'<tr><td>'.JText::_('COM_PHOCAGALLERY_NR_PUBLISHED_IMG_CAT') .': </td>'
		.'<td>'.$this->tmpl['numberimgpub'].'</td></tr>'
		.'<tr><td>'.JText::_('COM_PHOCAGALLERY_NR_UNPUBLISHED_IMG_CAT') .': </td>'
		.'<td>'.$this->tmpl['numberimgunpub'].'</td></tr>'
		.'<tr><td>'.JText::_('COM_PHOCAGALLERY_CATEGORY_VIEWED') .': </td>'
		.'<td>'.$this->tmpl['categoryviewed'].' x</td></tr>'
		.'</table>';
	}	

// MOST VIEWED			
if ($this->tmpl['displaymostviewedcatstat']) {
	
	echo '<h4>'.JText::_('COM_PHOCAGALLERY_MOST_VIEWED_IMG_CAT').'</h4>';
		
	if (!empty($this->tmpl['mostviewedimg'])) {
		foreach($this->tmpl['mostviewedimg'] as $key => $value) {
			
			$extImage = PhocaGalleryImage::isExtImage($value->extid);
			if ($extImage) {
				$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($value->extw, $value->exth, $this->tmpl['picasa_correct_width_m'], $this->tmpl['picasa_correct_height_m']);
			}
				
			?>
			<div class="pg-cv-box pg-cv-box-stat">
				<div class="pg-cv-box-img pg-box1">
					<div class="pg-box2">
						<div class="pg-box3">
							<a class="<?php echo $value->button->methodname; ?>"<?php
							echo ' href="'. $value->link.'"';
							
							//Correction (to not be in conflict - statistics vs. standard images)
							// e.g. shadowbox shadowbox[PhocaGallery] --> shadowbox[PhocaGallery3]
							$options3 = str_replace('[PhocaGallery]', '[PhocaGallery3]', $value->button->options);
							
							echo PhocaGalleryRenderFront::renderAAttributeStat($this->tmpl['detail_window'], $options3, '',$this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2'], '', $this->category->alias, 'mv');
							echo ' >';
							if ($extImage) {
								echo JHtml::_( 'image', $value->linkthumbnailpath, $value->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-image'));
							} else {
								echo JHtml::_( 'image', $value->linkthumbnailpath, $value->altvalue, array('class' => 'pg-image'));
							}
							?></a>
						</div>
					</div>
				</div><?php
			
			// subfolder
			if ($value->type == 1) {
				if ($value->display_name == 1 || $value->display_name == 2) {
					echo '<div class="pg-name">'.PhocaGalleryText::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
			}
			// image
			if ($value->type == 2) {
				if ($value->display_name == 1) {
					echo '<div class="pg-name">'.PhocaGalleryText::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($value->display_name == 2) {
					echo '<div class="pg-name">&nbsp;</div>';
				}
			}
			echo '<div class="detail" style="margin-top:2px;text-align:left">';
			echo JHtml::_('image', 'media/com_phocagallery/images/icon-viewed.png', JText::_('COM_PHOCAGALLERY_IMAGE_DETAIL'));
			echo '&nbsp;&nbsp; '.$value->hits.' x';
			echo '</div>';
			echo '<div class="ph-cb"></div>';
			echo '</div>';
		}
		echo '<div class="ph-cb"></div>';
	}

} // END MOST VIEWED

// LAST ADDED	
if ($this->tmpl['displaylastaddedcatstat']) {		

	
	echo '<h4>'.JText::_('COM_PHOCAGALLERY_LAST_ADDED_IMG_CAT').'</h4>';
		
	if (!empty($this->tmpl['lastaddedimg'])) {
		
		foreach($this->tmpl['lastaddedimg'] as $key => $value) {
			
			$extImage = PhocaGalleryImage::isExtImage($value->extid);
			if ($extImage) {
				$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($value->extw, $value->exth, $this->tmpl['picasa_correct_width_m'], $this->tmpl['picasa_correct_height_m']);
			}
				
			?><div class="pg-cv-box pg-cv-box-stat">
				<div class="pg-cv-box-img pg-box1">
					<div class="pg-box2">
						<div class="pg-box3">					
							<a class="<?php echo $value->button->methodname; ?>"<?php
							echo ' href="'. $value->link.'"';
							
							//Correction (to not be in conflict - statistics vs. standard images)
							// e.g. shadowbox shadowbox[PhocaGallery] --> shadowbox[PhocaGallery3]
							$options4 = str_replace('[PhocaGallery]', '[PhocaGallery4]', $value->button->options);
							
							echo PhocaGalleryRenderFront::renderAAttributeStat($this->tmpl['detail_window'], $options4, '', $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2'], '', $this->category->alias, 'la');
							
							echo ' >';
							if ($extImage) {
								echo JHtml::_( 'image', $value->linkthumbnailpath, $value->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-image'));
							} else {
								echo JHtml::_( 'image', $value->linkthumbnailpath, $value->altvalue, array('class' => 'pg-image') );
							}
							?></a>
						</div>
					</div>
				</div><?php
			
			// subfolder
			if ($value->type == 1) {
				if ($value->display_name == 1 || $value->display_name == 2) {
					echo '<div class="pg-name">'.PhocaGalleryText::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
			}
			// image
			if ($value->type == 2) {
				if ($value->display_name == 1) {
					echo '<div class="pg-name">'.PhocaGalleryText::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($value->display_name == 2) {
					echo '<div class="pg-name">&nbsp;</div>';
				}
			}

			echo '<div class="detail" style="margin-top:2px;text-align:left">';		
			echo JHtml::_('image', 'media/com_phocagallery/images/icon-date.png', JText::_('COM_PHOCAGALLERY_IMAGE_DETAIL'));
			echo '&nbsp;&nbsp; '.JHtml::Date($value->date, "d. m. Y");
			echo '</div>';
			echo '<div class="ph-cb"></div>';
			echo '</div>';
		}
		echo '<div class="ph-cb"></div>';
	}
}// END MOST VIEWED	
echo '</div>'. "\n";
?>
