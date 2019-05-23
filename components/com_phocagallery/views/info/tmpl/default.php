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
if ($this->tmpl['backbutton'] != '') {
	echo $this->tmpl['backbutton'];
}
echo '<div id="phoca-exif" class="pg-info-view'.$this->params->get( 'pageclass_sfx' ).'">'
.'<h1 class="phocaexif">'.JText::_('COM_PHOCAGALLERY_EXIF_INFO').':</h1>'
.'<table style="width:90%">'
.$this->infooutput
.'</table>'
.'</div>';
if ($this->tmpl['detailwindow'] == 7) {
    echo PhocaGalleryUtils::getInfo();
}
