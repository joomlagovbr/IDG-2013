<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery-upload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<form onsubmit="return OnUploadSubmitPG(\'loading-label\');" action="'. $this->tmpl['su_url'] .'" id="phocaGalleryUploadFormU" method="post" enctype="multipart/form-data">';
//if ($this->tmpl['ftp']) { echo PhocaGalleryFileUpload::renderFTPaccess();}  
echo '<h4>'; 
echo JText::_( 'COM_PHOCAGALLERY_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]';
echo ' </h4>';
echo $this->tmpl['su_output'];
$this->tmpl['upload_form_id'] = 'phocaGalleryUploadFormU';
echo $this->loadTemplate('uploadform');

echo '</form>';	 
echo '</div>';