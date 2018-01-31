<?php
defined('_JEXEC') or die('Restricted access');
phocagalleryimport('phocagallery.render.rendermap');

echo '<script src="http://www.google.com/jsapi" type="text/javascript"></script>';
echo '<noscript>'.JText::_('COM_PHOCAGALLERY_ERROR_MAP_ENABLE_JAVASCRIPT').'</noscript>';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<div align="center" style="margin:0;padding:0;margin-top:10px;">';

$cmw = '';
if ((int)$this->tmpl['categorymapwidth'] > 0) {
	$cmw = 'width:'.$this->tmpl['categorymapwidth'].'px;';
}
echo '<div id="phocaMap" style="margin:0;padding:0;'. $cmw. 'height:'.$this->tmpl['categorymapheight'].'px">';
echo '</div></div>';

?><script type='text/javascript'>//<![CDATA[
google.load("maps", "3.x", {"other_params":"sensor=false"}); <?php 
$map	= new PhocaGalleryRenderMap();
echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap');
echo $map->cancelEventF();
echo $map->checkMapF();
echo $map->startMapF();
	echo $map->setLatLng( $this->map['latitude'], $this->map['longitude'] );
	echo $map->startOptions();
	echo $map->setZoomOpt($this->map['zoom']).','."\n";
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
	echo $map->setMarker(1,$this->map['geotitle'],$this->map['description'],$this->map['latitude'], $this->map['longitude'], $iconOutput['icon'] );
	echo $map->setListener();
echo $map->endMapF();
echo $map->setInitializeF();
?>//]]></script>
