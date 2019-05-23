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
echo '<div id="phocagallery-javaupload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';

echo '<form action="'. JURI::base().'index.php?option=com_phocagallery" >';
//if ($this->tmpl['ftp']) {echo PhocaGalleryFileUpload::renderFTPaccess();}
echo '<h4>'; 
echo JText::_( 'Upload File' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]';
echo ' </h4>';
if ($this->tmpl['catidimage'] == 0 || $this->tmpl['catidimage'] == '') {
	echo '<div class="alert alert-error">'.JText::_('COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY_TO_BE_ABLE_TO_UPLOAD_IMAGES').'</div>';
}
echo $this->tmpl['ju_output'];
$this->tmpl['upload_form_id'] = 'phocaGalleryUploadFormJU';
echo $this->loadTemplate('uploadform');
echo '</form>';
echo '</div>';