<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery-multipleupload" class="ph-in">';
echo $this->tmpl['mu_response_msg'] ;
echo '<form action="'. JURI::base().'index.php?option=com_phocagallery" >';
if ($this->tmpl['ftp']) {echo PhocaGalleryFileUpload::renderFTPaccess();}
echo '<div class="control-label ph-head-form-small">' . JText::_( 'COM_PHOCAGALLERY_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]</div>';
echo '<small>'.JText::_('COM_PHOCAGALLERY_SELECT_IMAGES').'. '.JText::_('COM_PHOCAGALLERY_ADD_IMAGES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON').'</small>';
echo $this->tmpl['mu_output'];
echo '</form>';
echo '</div>';
?>