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
	
	
	$id		= uniqid();
	$map	= new PhocaGalleryRenderMaposm($id);

	
	$map->loadAPI();
	$map->loadCoordinatesJS();
	

	
	echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
	echo '<div align="center" style="margin:0;padding:0;margin-top:10px;">';

	$cmw = '';
	if ((int)$this->tmplGeo['categoriesmapwidth'] > 0) {
		$cmw = 'width:'.$this->tmplGeo['categoriesmapwidth'].'px;';
	}
	echo '<div id="phocaGalleryMap'.$id.'" style="margin:0;padding:0;'. $cmw. 'height:'.$this->tmplGeo['categoriesmapheight'].'px">';
	echo '</div></div>';

		
	$map->createMap($this->tmplGeo['categorieslat'], $this->tmplGeo['categorieslng'], $this->tmplGeo['categorieszoom']);
	
	$map->setMapType();
	

	// Markers
	jimport('joomla.filter.output');
	if (isset($this->categories) && !empty($this->categories)) {
	
		
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
				
				$minWidth = 110;
				if (file_exists(JPATH_SITE . '/'. $category->linkthumbnailpath)) {
					$size = getimagesize(JPATH_SITE . '/'. $category->linkthumbnailpath);
					$minWidth = $size[0] + 10;
				}
				
				$text = '<div style="text-align:left; min-width: '.$minWidth.'px">'
				.'<table border="0" cellspacing="5" cellpadding="5">'
				.'<tr>'
				.'<td align="left" colspan="2"><b><a href="'.$category->link.'">'. PhocaGalleryText::strTrimAll(addslashes($category->geotitle)).'</a></b></td>'
				.'</tr>'
				.'<tr>'
				.'<td valign="top" align="left"><a href="'.$category->link.'">'. $imgLink . '</a></td>'
				.'<td valign="top" align="left" class="pg-mapbox-description">'. PhocaGalleryText::strTrimAll(addslashes($category->description)).'</td>'
				.'</tr></table></div>';

				// Markers
				//$iconOutput = $map->setMarkerIcon(0);
				//echo $iconOutput['js'];
				 $map->setMarker($category->id, $category->geotitle, $category->description,$category->latitude, $category->longitude, $text );

				
			}
		}
	}


	
	$map->renderFullScreenControl();
	//$map->renderCurrentPosition();
	//$map->renderSearch('', 'topleft');
	
	// Get Lat and Lng TO (first marker)
	$lat = $lng = 0;
	$mId = '';
	$markerIconOptions = array();
	if (isset($firstMarker->latitude)) {
		$lat = $firstMarker->latitude;
	}
	if (isset($firstMarker->longitude)) {
		$lng = $firstMarker->longitude;
	}
	if (isset($firstMarker->id)) {
		$mId = $id . 'm'.$firstMarker->id;
	}
	if (isset($firstMarker->markericonoptions)) {
		$markerIconOptions = $firstMarker->markericonoptions;
	}
	$map->renderRouting(0,0,$lat,$lng, $mId, $markerIconOptions);
	$map->renderEasyPrint();
	$map->renderMap();
		
}


echo '<div>&nbsp;</div>';
echo PhocaGalleryUtils::getInfo();

echo '</div>';
?>
