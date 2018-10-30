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
echo '<div id="phocagallery-ytbupload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<form onsubmit="return OnUploadSubmitPG(\'loading-label-ytb\');" action="'. $this->tmpl['syu_url'] .'" id="phocaGalleryUploadFormYU" method="post">';
//if ($this->tmpl['ftp']) { echo PhocaGalleryFileUpload::renderFTPaccess();}  
//echo '<h4>'; 
//echo JText::_('COM_PHOCAGALLERY_YTB_UPLOAD');
//echo ' </h4>';
if ($this->tmpl['catidimage'] == 0 || $this->tmpl['catidimage'] == '') {
	echo '<div class="alert alert-error">'.JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY_TO_BE_ABLE_TO_IMPORT_YOUTUBE_VIDEO').'</div>';
}
echo $this->tmpl['syu_output'];

$this->tmpl['upload_form_id'] = 'phocaGalleryUploadFormYU';
?>

<div><?php echo JText::_( 'COM_PHOCAGALLERY_YTB_LINK' ); ?>:</div>
<div>
<input type="text" id="phocagallery-ytbupload-link" name="phocagalleryytbuploadlink" value=""  maxlength="255" size="48" /></br>
<input type="submit" class="btn btn-primary" id="file-upload-submit" value="<?php echo JText::_('COM_PHOCAGALLERY_START_UPLOAD'); ?>"/>
</div>


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