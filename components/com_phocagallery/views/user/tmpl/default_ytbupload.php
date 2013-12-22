<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery-ytbupload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<form onsubmit="return OnUploadSubmitPG(\'loading-label-ytb\');" action="'. $this->tmpl['syu_url'] .'" id="phocaGalleryUploadFormYU" method="post">';
//if ($this->tmpl['ftp']) { echo PhocaGalleryFileUpload::renderFTPaccess();}  
echo '<h4>'; 
echo JText::_('COM_PHOCAGALLERY_YTB_UPLOAD');
echo ' </h4>';

echo $this->tmpl['syu_output'];

$this->tmpl['upload_form_id'] = 'phocaGalleryUploadFormYU';
?>

<table>
	<tr>
		<td><?php echo JText::_( 'COM_PHOCAGALLERY_YTB_LINK' ); ?>:</td>
			<td>
				<input type="text" id="phocagallery-ytbupload-link" name="phocagalleryytbuploadlink" value=""  maxlength="255" size="48" /></td>

		<td>
		<input type="submit" id="file-upload-submit" value="<?php echo JText::_('COM_PHOCAGALLERY_START_UPLOAD'); ?>"/>
		</td></tr>
</table>

<input type="hidden" name="controller" value="user" />
<input type="hidden" name="viewback" value="user" />
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['images'];?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
<input type="hidden" name="filter_order_image" value="<?php echo $this->listsimage['order']; ?>" />
<input type="hidden" name="filter_order_Dir_image" value="" />
<input type="hidden" name="catid" value="<?php echo $this->tmpl['catidimage'] ?>"/>

<?php
if ($this->tmpl['upload_form_id'] == 'phocaGalleryUploadFormYU') {
	echo '<div id="loading-label-ytb" style="text-align:center">'
	. JHtml::_('image', 'media/com_phocagallery/images/icon-switch.gif', '') 
	. '  '.JText::_('COM_PHOCAGALLERY_LOADING').'</div>';
}

echo '</form>';	 
echo '</div>';