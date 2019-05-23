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
phocagalleryimport('phocagallery.render.rendermaposm');



$id		= uniqid();
$map	= new PhocaGalleryRenderMaposm($id);
echo '<noscript>'.JText::_('COM_PHOCAGALLERY_ERROR_MAP_ENABLE_JAVASCRIPT').'</noscript>';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<div align="center" style="margin:0;padding:0;margin-top:10px;">';

$cmw = '';
if ((int)$this->tmpl['categorymapwidth'] > 0) {
	$cmw = 'width:'.$this->tmpl['categorymapwidth'].'px;';
}
echo '<div id="phocaGalleryMap'.$id.'" style="margin:0;padding:0;'. $cmw. 'height:'.$this->tmpl['categorymapheight'].'px">';
echo '</div></div>';



$map->loadAPI();
$map->loadCoordinatesJS();
	
$map->createMap($this->map['latitude'], $this->map['longitude'], $this->map['zoom']);
$map->setMapType();
 $map->setMarker(1, $this->map['geotitle'],$this->map['description'],$this->map['latitude'], $this->map['longitude']);
$map->renderFullScreenControl();
//$map->renderCurrentPosition();
//$map->renderSearch('', 'topleft');
$map->renderMap();
