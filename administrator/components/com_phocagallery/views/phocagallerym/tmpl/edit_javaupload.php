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
if (!empty($this->tmpl['ju_output'])) {
	echo '<div id="phocagallery-javaupload" class="ph-in">';
	echo '<form action="'. JURI::base().'index.php?option=com_phocagallery" >';
	if ($this->tmpl['ftp']) {echo PhocaGalleryFileUpload::renderFTPaccess();}
	echo '<div class="control-label ph-head-form">' . JText::_( 'COM_PHOCAGALLERY_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
		.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]</div>';
	echo $this->tmpl['ju_output'];
	echo '</form>';
	echo '</div>';
}
?>