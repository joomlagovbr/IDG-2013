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

echo '<div id="phocagallery-category-creating">'.$this->tmpl['iepx'];

if ($this->tmpl['categorypublished'] == 0) {
	echo '<p>'.JText::_('COM_PHOCAGALLERY_YOUR_CATEGORY_IS_UNPUBLISHED').'</p>';
} else {

if ($this->tmpl['categorytitle'] != '') {
	?><h4><?php echo JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY'); ?></h4>
	<table>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_CATEGORY');?>:</strong></td>
			<td><?php echo $this->tmpl['categorytitle'] ;?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_DESCRIPTION');?>:</strong></td>
			<td><?php echo $this->tmpl['categorydescription'] ;?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_APPROVED');?>:</strong></td>
			<td><?php

			
			if ($this->tmpl['categoryapproved'] == 1) {
				echo PhocaGalleryRenderFront::renderIcon('publish', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED'));
			} else {	
				echo PhocaGalleryRenderFront::renderIcon('unpublish', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED'));		
			}
			
		?></td>
		</tr>
	</table><?php
}
?><h4><?php echo $this->tmpl['categorycreateoredit']; ?></h4>
	<form action="<?php echo htmlspecialchars($this->tmpl['action']);?>" name="phocagallerycreatecatform" id="phocagallery-create-cat-form" method="post" >
	<table>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_CATEGORY');?>:</strong></td>
			<td><input type="text" id="categoryname" name="categoryname" maxlength="255" class="comment-input" value="<?php echo $this->tmpl['categorytitle'] ;?>" /></td>
		</tr>
		
		<tr>
			<td><strong><?php echo JText::_( 'COM_PHOCAGALLERY_DESCRIPTION' ); ?>:</strong></td>
			<td><textarea id="phocagallery-create-cat-description" name="phocagallerycreatecatdescription" onkeyup="countCharsCreateCat();" cols="30" rows="10" class="comment-input"><?php echo $this->tmpl['categorydescription'] ;?></textarea></td>
		</tr>
				
		<tr>
			<td>&nbsp;</td>
			<td><?php echo JText::_('COM_PHOCAGALLERY_CHARACTERS_WRITTEN');?> <input name="phocagallerycreatecatcountin" value="0" readonly="readonly" class="comment-input2" /> <?php echo JText::_('COM_PHOCAGALLERY_AND_LEFT_FOR_DESCRIPTION');?> <input name="phocagallerycreatecatcountleft" value="<?php echo $this->tmpl['maxcreatecatchar'];?>" readonly="readonly" class="comment-input2" />
			</td>
		</tr>
				
		<tr>
			<td>&nbsp;</td>
			<td align="right"><button class="btn" onclick="return(checkCreateCatForm());" id="phocagallerycreatecatsubmit" ><?php echo $this->tmpl['categorycreateoredit']; ?></button>
			</td>
		</tr>
	</table>

	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="createcategory"/>
	<input type="hidden" name="controller" value="user"/>
	
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['createcategory'];?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
	</form><?php
}
echo '</div>';
?>	
