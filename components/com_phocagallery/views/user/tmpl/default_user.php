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
echo '<div id="phocagallery-user">'.$this->tmpl['iepx'];
?><h4><?php echo JText::_( 'COM_PHOCAGALLERY_USER' ); ?></h4>
<table>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_USER');?>:</strong></td>
		<td><?php echo $this->tmpl['user']?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_USERNAME');?>:</strong></td>
		<td><?php echo $this->tmpl['username']?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_MAIN_CATEGORY');?>:</strong></td>
		<td><?php echo $this->tmpl['usermaincategory']?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_NUMBER_OF_SUBCATEGORIES');?>:</strong></td>
		<td><?php echo $this->tmpl['usersubcategory'] . ' ('.JText::_('COM_PHOCAGALLERY_MAX').': '.$this->tmpl['usersubcatcount'].', '.JText::_('COM_PHOCAGALLERY_SPACE_LEFT').': '.$this->tmpl['usersubcategoryleft'].')'; ?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_NUMBER_OF_IMAGES');?>:</strong></td>
		<td><?php echo $this->tmpl['userimages']; ?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_PHOCAGALLERY_USED_SPACE');?>:</strong></td>
		<td><?php echo $this->tmpl['userimagesspace']. ' ('.JText::_('COM_PHOCAGALLERY_MAX').': '.$this->tmpl['userimagesmaxspace'].', '.JText::_('COM_PHOCAGALLERY_SPACE_LEFT').': '.$this->tmpl['userimagesspaceleft'].')'; ?></td>
	</tr>
</table><?php 

if ($this->tmpl['enableuploadavatar'] == 1) {
	?><p>&nbsp;</p>
<h4><?php 
	echo JText::_( 'COM_PHOCAGALLERY_UPLOAD_AVATAR' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]';
?></h4>			
				
<form onsubmit="return OnUploadSubmitUserPG();" action="<?php echo htmlspecialchars($this->tmpl['actionamp']) . $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JSession::getFormToken();?>=1&amp;viewback=user" id="uploadForm" method="post" enctype="multipart/form-data">
		
	<table>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_AVATAR');?>:</strong></td>
			<td><?php echo $this->tmpl['useravatarimg']?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_APPROVED');?>:</strong></td>
			<td><?php
			if ($this->tmpl['useravatarapproved'] == 1) {
				//echo JHtml::_('image', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED'));
				echo PhocaGalleryRenderFront::renderIcon('publish', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED'));
			} else {	
				//echo JHtml::_('image', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED'));
				echo PhocaGalleryRenderFront::renderIcon('unpublish', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED'));
			}
		?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COM_PHOCAGALLERY_FILENAME');?>:</strong></td>
			<td>
			<input type="file" id="file-upload" name="Filedata" />
			<button class="btn btn-primary" id="file-upload-submit"><i class="icon-upload icon-white"></i> <?php echo JText::_('COM_PHOCAGALLERY_START_UPLOAD') ?></button>
			<span id="upload-clear"></span>
			</td>
		</tr>
	</table>	
			
	<ul class="upload-queue" id="upload-queue">
		<li style="display: none" ></li>
	</ul>

	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="uploadavatar"/>
	<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['user'];?>" />
	<input type="hidden" name="controller" value="user" />
	<input type="hidden" name="viewback" value="user" />
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
</form>
<div id="loading-label-user" style="text-align:center"><?php echo JHtml::_('image', $this->tmpl['pi'].'icon-switch.gif', '') . '  '. JText::_('COM_PHOCAGALLERY_LOADING'); ?></div><?php
}
echo '</div>'; 	
?>