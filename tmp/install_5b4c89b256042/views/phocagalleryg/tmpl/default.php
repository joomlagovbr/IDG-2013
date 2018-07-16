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


$map	= new PhocaGalleryRenderMap();
echo $map->loadApi();
echo '<noscript>'.JText::_('COM_PHOCAGALLERY_GOOGLE_MAPS_ENABLE_JS').'</noscript>';
echo '<div align="center" style="margin:0;padding:0;text-align: center;">';
echo '<div id="phocaMap" style="margin:0 auto;padding:0;width:520px;height:460px;"></div></div>';

$document					= JFactory::getDocument();
$document->addCustomTag( "<style type=\"text/css\"> \n" 
			. '#phocaMap img {
					max-width: none;
				}'
			." </style> \n");


//echo $map->loadApi();
?><script type='text/javascript'>//<![CDATA[
<?php 

echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap');
echo $map->cancelEventF();
echo $map->checkMapF();


echo $map->startMapF();
	echo $map->setLatLng( $this->latitude, $this->longitude );
	echo $map->startOptions();
	echo $map->setZoomOpt($this->zoom).','."\n";
	echo $map->setCenterOpt().','."\n";
	echo $map->setTypeControlOpt().','."\n";
	echo $map->setNavigationControlOpt().','."\n";
	echo $map->setScaleControlOpt(1).','."\n";
	echo $map->setScrollWheelOpt(1).','."\n";
	echo $map->setDisableDoubleClickZoomOpt(0).','."\n";
	echo $map->setMapTypeOpt()."\n";
	echo $map->endOptions();
	echo $map->setMap();
	echo $map->exportZoom($this->zoom, '', 'phocaSelectMap_jform_zoom');
	echo $map->exportMarker(1, $this->latitude, $this->longitude, '', '', 'phocaSelectMap_jform_latitude', 'phocaSelectMap_jform_longitude');
	echo $map->setListener();
echo $map->endMapF();
echo $map->setInitializeF();
?>//]]></script>
<?php echo $map->loadApi(); ?>
