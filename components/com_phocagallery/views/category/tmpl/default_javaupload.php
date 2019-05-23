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
echo '<div class="ph-tabs-iefix">&nbsp;</div>';//because of IE bug
echo '<form action="'. JURI::base().'index.php?option=com_phocagallery" >';
//if ($this->tmpl['ftp']) {echo PhocaGalleryFileUpload::renderFTPaccess();}
echo '<h4>'; 
echo JText::_( 'Upload File' ).' [ '. JText::_( 'COM_PHOCAGALLERY_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAGALLERY_MAX_RESOLUTION').':&nbsp;'. $this->tmpl['uploadmaxreswidth'].' x '.$this->tmpl['uploadmaxresheight'].' px ]';
echo ' </h4>';
echo $this->tmpl['ju_output'];
echo '</form>';
echo '</div>';