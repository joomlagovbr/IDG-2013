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
phocagalleryimport('phocagallery.render.rendermap');
phocagalleryimport('phocagallery.text.text');
if ($this->tmpl['backbutton'] != '' && $this->tmpl['enable_multibox_iframe'] != 1) {
	echo $this->tmpl['backbutton'];
}

if (empty($this->map) || $this->map->longitude == '' || $this->map->latitude == '') {
	echo '<p>' . JText::_('COM_PHOCAGALLERY_ERROR_MAP_NO_DATA') . '</p>';
} else {

	$text = '<div style="text-align:left"><table style="" border="0" cellspacing="5" cellpadding="5"><tr><td align="left" colspan="2"><b>'. addslashes($this->map->geotitle).'</b></td></tr>';
	$text .='<tr>';
	$text .='<td valign="top" align="left">'.JHtml::_( 'image', $this->map->thumbnail, addslashes($this->map->geotitle)) . '</td>';
	$text .='<td valign="top" align="left">'. PhocaGalleryText::strTrimAll(addslashes($this->map->description)).'</td>';
	$text .='</tr></table></div>';

	$map	= new PhocaGalleryRenderMap();


	echo '<noscript>'.JText::_('COM_PHOCAGALLERY_ERROR_MAP_ENABLE_JAVASCRIPT').'</noscript>';

	$cmw = '';
	if ((int)$this->tmpl['largemapwidth'] > 0) {
		$cmw = 'width:'.$this->tmpl['largemapwidth'].'px;';
	}

	echo '<div align="center" style="margin:0;padding:0;margin-top:10px;text-align: center">';
	echo '<div id="phocaMap" style="margin:0 auto;padding:0;'. $cmw. 'height:'.$this->tmpl['largemapheight'].'px">';
	echo '</div></div>';


	//echo $map->loadApi();
	?><script type='text/javascript'>//<![CDATA[
	<?php

	echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap', $text);
	echo $map->cancelEventF();
	echo $map->checkMapF();
	echo $map->startMapF();
		echo $map->setLatLng( $this->map->latitude, $this->map->longitude );
		echo $map->startOptions();
		echo $map->setZoomOpt($this->map->zoom).','."\n";
		echo $map->setCenterOpt().','."\n";
		echo $map->setTypeControlOpt().','."\n";
		echo $map->setNavigationControlOpt().','."\n";
		echo $map->setScaleControlOpt(1).','."\n";
		echo $map->setScrollWheelOpt(1).','."\n";
		echo $map->setDisableDoubleClickZoomOpt(0).','."\n";
		echo $map->setMapTypeOpt()."\n";
		echo $map->endOptions();
		echo $map->setMap();
		// Markers
		$iconOutput = $map->setMarkerIcon(0);
		echo $iconOutput['js'];
		echo $map->setMarker(1,$this->map->geotitle,$this->map->description,$this->map->latitude, $this->map->longitude, $iconOutput['icon'], $text );
		echo $map->setListener();
	echo $map->endMapF();
	echo $map->setInitializeF();
	?>//]]></script><?php

	echo $map->loadApi();
}
if ($this->tmpl['detailwindow'] == 7) {
	echo '<div>&nbsp;</div>';
    echo PhocaGalleryUtils::getInfo();
}
?>
