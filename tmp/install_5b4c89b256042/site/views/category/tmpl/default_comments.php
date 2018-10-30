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

echo '<div id="phocagallery-comments">'. "\n";
echo '<div class="ph-tabs-iefix">&nbsp;</div>';//because of IE bug

if (!empty($this->commentitem)){
	$userImage	= JHtml::_( 'image', $this->tmpl['icon_path']. 'icon-user.png','');

	$smileys = PhocaGalleryComment::getSmileys();
	
	foreach ($this->commentitem as $itemValue) {
		$date		= JHtml::_('date',  $itemValue->date, JText::_('DATE_FORMAT_LC2') );
		$comment	= $itemValue->comment;
		$comment 	= PhocaGalleryComment::bbCodeReplace($comment);
		foreach ($smileys as $smileyKey => $smileyValue) {
			$comment = str_replace($smileyKey, JHtml::_( 'image', $this->tmpl['icon_path']. ''.$smileyValue .'.png',''), $comment);
		}
		
		echo '<blockquote>'
			.'<h4>'.$userImage.'&nbsp;'.$itemValue->name.'</h4>'
			.'<p><strong>'.PhocaGalleryText::wordDelete($itemValue->title, 50, '...').'</strong></p>'
			.'<p style="overflow:auto;width:'.$this->tmpl['commentwidth'].'px;">'.$comment.'</p>'
			.'<p style="text-align:right"><small>'.$date.'</small></p>'
			.'</blockquote>';
	}
}

echo '<h4>'.JText::_('COM_PHOCAGALLERY_ADD_COMMENT').'</h4>';

if ($this->tmpl['already_commented']) {
	echo '<p>'.JText::_('COM_PHOCAGALLERY_COMMENT_ALREADY_SUBMITTED').'</p>';
} else if ($this->tmpl['not_registered']) {
	echo '<p>'.JText::_('COM_PHOCAGALLERY_COMMENT_ONLY_REGISTERED_LOGGED_SUBMIT_COMMENT').'</p>';
		
} else {
	
	?>		
	<form action="<?php echo htmlspecialchars($this->tmpl['action']);?>" name="phocagallerycommentsform" id="phocagallery-comments-form" method="post" >
	
	
	<table>
		<tr>
			<td><?php echo JText::_('COM_PHOCAGALLERY_NAME');?>:</td>
			<td><?php echo $this->tmpl['name']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo JText::_('COM_PHOCAGALLERY_TITLE');?>:</td>
			<td><input type="text" name="phocagallerycommentstitle" id="phocagallery-comments-title" value="" maxlength="255" class="comment-input" /></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td>
			<a href="#" onclick="pasteTag('b', true); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('bold', $this->tmpl['icon_path'].'icon-b.png', JText::_('COM_PHOCAGALLERY_BOLD')); ?></a>&nbsp;
			<a href="#" onclick="pasteTag('i', true); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('italic', $this->tmpl['icon_path'].'icon-i.png', JText::_('COM_PHOCAGALLERY_ITALIC')); ?></a>&nbsp;
			<a href="#" onclick="pasteTag('u', true); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('underline', $this->tmpl['icon_path'].'icon-u.png', JText::_('COM_PHOCAGALLERY_UNDERLINE')); ?></a>&nbsp;&nbsp;
			
			<a href="#" onclick="pasteSmiley(':)'); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('smile', $this->tmpl['icon_path'].'icon-s-smile.png', JText::_('COM_PHOCAGALLERY_SMILE')); ?></a>&nbsp;
			<a href="#" onclick="pasteSmiley(':lol:'); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('lol', $this->tmpl['icon_path'].'icon-s-lol.png', JText::_('COM_PHOCAGALLERY_LOL')); ?></a>&nbsp;
			<a href="#" onclick="pasteSmiley(':('); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('sad', $this->tmpl['icon_path'].'icon-s-sad.png', JText::_('COM_PHOCAGALLERY_SAD')); ?></a>&nbsp;
			<a href="#" onclick="pasteSmiley(':?'); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('confused', $this->tmpl['icon_path'].'icon-s-confused.png', JText::_('COM_PHOCAGALLERY_CONFUSED')); ?></a>&nbsp;
			<a href="#" onclick="pasteSmiley(':wink:'); return false;"><?php echo PhocaGalleryRenderFront::renderIcon('wink', $this->tmpl['icon_path'].'icon-s-wink.png', JText::_('COM_PHOCAGALLERY_WINK')); ?></a>&nbsp;
						
			
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td>
				<textarea name="phocagallerycommentseditor" id="phocagallery-comments-editor" cols="30" rows="10"  class= "comment-input" onkeyup="countChars();" ></textarea>
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><?php echo JText::_('COM_PHOCAGALLERY_CHARACTERS_WRITTEN');?> <input name="phocagallerycommentscountin" value="0" readonly="readonly" class="comment-input2" /> <?php echo JText::_('COM_PHOCAGALLERY_AND_LEFT_FOR_COMMENT');?> <input name="phocagallerycommentscountleft" value="<?php echo $this->tmpl['maxcommentchar'];?>" readonly="readonly" class="comment-input2" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right">
				<input class="btn" type="submit" id="phocagallerycommentssubmit" onclick="return(checkCommentsForm());" value="<?php echo JText::_('COM_PHOCAGALLERY_SUBMIT_COMMENT'); ?>"/>
			</td>
		</tr>
		
	</table>
	
	<input type="hidden" name="task" value="comment"/>
	<input type="hidden" name="view" value="category"/>
	<input type="hidden" name="controller" value="category"/>
	<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['comment'];?>" />
	<input type="hidden" name="catid" value="<?php echo $this->category->slug ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
	<?php echo JHtml::_( 'form.token' ); ?>
	</form>
	
	<?php
}
echo '</div>'. "\n";
?>
