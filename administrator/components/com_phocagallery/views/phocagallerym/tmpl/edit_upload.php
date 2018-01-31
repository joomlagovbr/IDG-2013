<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery-upload" class="ph-in">';
echo '<div id="upload-noflash" class="actions">';
echo '<form action="'. $this->tmpl['su_url'] .'" id="uploadFormU" method="post" enctype="multipart/form-data">';
if ($this->tmpl['ftp']) { echo PhocaGalleryFileUpload::renderFTPaccess();}  
echo '<div class="control-label ph-head-form">'. JText::_( 'COM_PHOCAGALLERY_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]</div>';
echo $this->tmpl['su_output'];
echo '</form>';
echo '</div>';
echo '</div>';
?>