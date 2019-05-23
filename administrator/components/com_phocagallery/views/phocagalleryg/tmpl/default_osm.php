<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

$id		= uniqid();
$map	= new PhocaGalleryRenderMaposm($id);
$map->loadAPI();
if ($this->type == 'marker') {
	$map->loadCoordinatesJS();
}


$map->createMap($this->latitude, $this->longitude, $this->zoom);
$map->setMapType();
$map->setMarker($id, '', '', $this->latitude, $this->longitude);

// Export, Move, Input, renderSearch are dependent
$map->moveMarker();
if ($this->type == 'marker') {
	$map->inputMarker('jform_latitude_id', 'jform_longitude_id', '', 1);
} else {
	$map->inputMarker('jform_latitude_id', 'jform_longitude_id', 'jform_zoom_id', 0);
}
$map->exportMarker($id);
$map->renderSearch($id);

$map->renderFullScreenControl();
$map->renderCurrentPosition();

$map->renderMap();

echo '<div id="phocamaps" style="margin:0;padding:0;">';
echo '<div align="center" style="margin:0;padding:0">';
echo '<div id="phocaGalleryMap'.$id.'" style="margin:0;padding:0;width:100%;height:97vh"></div></div>';



echo '</div>';
?>
