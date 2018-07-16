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
// NO htmlspecialchars - it is used in view.html.php
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery-upload">';
echo '<div class="ph-tabs-iefix">&nbsp;</div>';//because of IE bug
echo '<form onsubmit="return OnUploadSubmitCategoryPG(\'loading-label\');" action="'. $this->tmpl['su_url'] .'" id="phocaGalleryUploadFormU" method="post" enctype="multipart/form-data">';
//if ($this->tmpl['ftp']) { echo PhocaGalleryFileUpload::renderFTPaccess();}  
echo '<h4>'; 
echo JText::_( 'COM_PHOCAGALLERY_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]';
echo ' </h4>';

echo $this->tmpl['su_output'];
$this->tmpl['upload_form_id'] = 'phocaGalleryUploadFormU';
echo $this->loadTemplate('uploadform');
echo JHtml::_('form.token');
echo '</form>';	 
echo '</div>';