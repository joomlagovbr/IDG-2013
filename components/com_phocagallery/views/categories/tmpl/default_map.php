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


echo '<div id="phocagallery" class="pg-categories-view'.$this->params->get( 'pageclass_sfx' ).'">';

if ( $this->params->get( 'show_page_heading' ) ) {
	echo '<h1>'. $this->escape($this->params->get('page_heading')) . '</h1>';
}

echo '<div id="pg-icons">';
echo PhocaGalleryRenderFront::renderFeedIcon('categories');
echo '</div>';

if ($this->tmplGeo['categorieslng'] == '' || $this->tmplGeo['categorieslat'] == '') {
	echo '<p>' . JText::_('COM_PHOCAGALLERY_ERROR_MAP_NO_DATA') . '</p>';
} else {
	//echo '<script src="http://www.google.com/js api" type="text/javascript"></script>';
	$map	= new PhocaGalleryRenderMap();
	//echo $map->loadApi();

	echo '<noscript>'.JText::_('COM_PHOCAGALLERY_ERROR_MAP_ENABLE_JAVASCRIPT').'</noscript>';
	echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
	echo '<div align="center" style="margin:0;padding:0;margin-top:10px;">';

	$cmw = '';
	if ((int)$this->tmplGeo['categoriesmapwidth'] > 0) {
		$cmw = 'width:'.$this->tmplGeo['categoriesmapwidth'].'px;';
	}
	echo '<div id="phocaMap" style="margin:0;padding:0;'. $cmw. 'height:'.$this->tmplGeo['categoriesmapheight'].'px">';
	echo '</div></div>';

	?><script type='text/javascript'>//<![CDATA[
	<?php

	echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap');
	echo $map->cancelEventF();
	echo $map->checkMapF();
	echo $map->startMapF();
		echo $map->setLatLng( $this->tmplGeo['categorieslat'], $this->tmplGeo['categorieslng'] );
		echo $map->startOptions();
		echo $map->setZoomOpt($this->tmplGeo['categorieszoom']).','."\n";
		echo $map->setCenterOpt().','."\n";
		echo $map->setTypeControlOpt().','."\n";
		echo $map->setNavigationControlOpt().','."\n";
		echo $map->setScaleControlOpt(1).','."\n";
		echo $map->setScrollWheelOpt(1).','."\n";
		echo $map->setDisableDoubleClickZoomOpt(0).','."\n";
		echo $map->setMapTypeOpt()."\n";
		echo $map->endOptions();
		echo $map->setMap();

		foreach ($this->categories as $category) {

			if ((isset($category->longitude) && $category->longitude != '' && $category->longitude != 0)
				&& (isset($category->latitude) && $category->latitude != '' && $category->latitude != 0)) {

				if ($category->geotitle == '') {
					$category->geotitle = $category->title;
				}
				$extCategory = PhocaGalleryImage::isExtImage($category->extid);
				if ($extCategory) {
					$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($category->extw, $category->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
					$imgLink = JHtml::_( 'image', $category->linkthumbnailpath, str_replace('&raquo;', '-',$category->title), array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
				} else {
					$imgLink = JHtml::_( 'image', $category->linkthumbnailpath, PhocaGalleryText::strTrimAll(addslashes($category->geotitle )));
				}
				$text = '<div style="text-align:left">'
				.'<table border="0" cellspacing="5" cellpadding="5">'
				.'<tr>'
				.'<td align="left" colspan="2"><b><a href="'.$category->link.'">'. PhocaGalleryText::strTrimAll(addslashes($category->geotitle)).'</a></b></td>'
				.'</tr>'
				.'<tr>'
				.'<td valign="top" align="left"><a href="'.$category->link.'">'. $imgLink . '</a></td>'
				.'<td valign="top" align="left">'. PhocaGalleryText::strTrimAll(addslashes($category->description)).'</td>'
				.'</tr></table></div>';

				// Markers
				$iconOutput = $map->setMarkerIcon(0);
				echo $iconOutput['js'];
				echo $map->setMarker($category->id, $category->geotitle, $category->description,$category->latitude, $category->longitude, $iconOutput['icon'], $text );

				echo $map->setListener();
			}
		}

	echo $map->endMapF();
	echo $map->setInitializeF();
	?>//]]></script><?php
	echo $map->loadApi();
}


echo '<div>&nbsp;</div>';
echo PhocaGalleryUtils::getInfo();

echo '</div>';
?>
