<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/* Google Maps Version 3 */
class PhocaGalleryRenderMap
{
	var $_id		= 'phocaMap';
	var $_map		= 'mapPhocaMap';
	var $_latlng	= 'phocaLatLng';
	var $_options	= 'phocaOptions';
	var $_tst		= 'tstPhocaMap';
	var $_tstint	= 'tstIntPhocaMap';

	public function __construct() {
	}

	public function loadApi() {

		$paramsC 	= JComponentHelper::getParams('com_phocagallery');
		$key 		= $paramsC->get( 'maps_api_key', '' );
		$ssl 		= $paramsC->get( 'maps_api_ssl', 1 );

		if ($ssl) {
			$h = 'https://';
		} else {
			$h = 'http://';
		}
		if ($key) {
			$k = '&key='.PhocaGalleryText::filterValue($key, 'text');
		} else {
			$k = '';
		}

		return '<script async defer src="'.$h.'maps.googleapis.com/maps/api/js?callback=initMap'.$k.'" type="text/javascript"></script>';
   }


	public function createMap($id, $map, $latlng, $options, $tst, $tstint) {
		$this->_id		= $id;
		$this->_map 	= $map;
		$this->_latlng 	= $latlng;
		$this->_options = $options;
		$this->_tst 	= $tst;
		$this->_tstint 	= $tstint;

		$js = "\n" . 'var '.$this->_tst.' = document.getElementById(\''.$this->_id.'\');'."\n"
			 .'var '.$this->_tstint.';'."\n"
			 .'var '.$this->_map.';'."\n";

		return $js;
	}

	public function setMap() {
		return 'var '.$this->_map.' = new google.maps.Map(document.getElementById(\''.$this->_id.'\'), '.$this->_options.');'."\n";
	}

	public function setLatLng($latitude, $longitude) {
		return 'var '.$this->_latlng.' = new google.maps.LatLng('.PhocaGalleryText::filterValue($latitude, 'number2') .', '. PhocaGalleryText::filterValue($longitude, 'number2') .');'."\n";
	}

	public function startOptions() {
		return 'var '.$this->_options.' = {'."\n";
	}

	public function endOptions (){
		return '};'."\n";
	}

	// Options
	public function setZoomOpt($zoom) {
		return 'zoom: '.(int)$zoom;
	}

	public function setCenterOpt() {
		return 'center: '.$this->_latlng;
	}

	public function setTypeControlOpt( $typeControl = 1 ) {
		$output = '';
		if ($typeControl == 0) {
			$output = 'mapTypeControl: false';
		} else {
			switch($typeControl) {
				case 2:
					$type = 'HORIZONTAL_BAR';
				break;
				case 3:
					$type = 'DROPDOWN_MENU';
				break;
				Default:
				case 1:
					$type = 'DEFAULT';
				break;
			}

			$output = 'mapTypeControl: true,'."\n"
					 .'mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.'.$type.'}';
		}
		return $output;
	}

	public function setNavigationControlOpt( $navControl = 1) {
		$output = '';
		if ($navControl == 0) {
			$output = 'navigationControl: false';
		} else {
			switch($navControl) {
				case 2:
					$type = 'SMALL';
				break;
				case 3:
					$type = 'ZOOM_PAN';
				break;
				case 4:
					$type = 'ANDROID';
				break;
				Default:
				case 1:
					$type = 'DEFAULT';
				break;
			}

			$output = 'navigationControl: true,'."\n"
					 .'navigationControlOptions: {style: google.maps.NavigationControlStyle.'.$type.'}';
		}
		return $output;
	}

	public function setScaleControlOpt( $scaleControl = 0) {
		$output = '';
		if ($scaleControl == 0) {
			$output = 'scaleControl: false';
		} else {
			$output = 'scaleControl: true';
		}
		return $output;
	}

	public function setScrollWheelOpt($enable = 1) {
		if ($enable == 1) {
			return 'scrollwheel: true';
		} else {
			return 'scrollwheel: false';
		}
	}

	public function setDisableDoubleClickZoomOpt($disable = 0) {
		if ($disable == 1) {
			return 'disableDoubleClickZoom: true';
		} else {
			return 'disableDoubleClickZoom: false';
		}
	}

	public function setMapTypeOpt( $mapType = 0 ) {
		$output = '';

		switch($mapType) {
			case 1:
				$type = 'SATELLITE';
			break;
			case 2:
				$type = 'HYBRID';
			break;
			case 3:
				$type = 'TERRAIN';
			break;
			Default:
			case 0:
				$type = 'ROADMAP';
			break;
		}

		$output = 'mapTypeId: google.maps.MapTypeId.'.$type;
		return $output;
	}


	public function setMarker($id, $title, $description, $latitude, $longitude, $icon = 0, $text = '' ) {
		jimport('joomla.filter.output');
		phocagalleryimport('phocagallery.text.text');
		$output = '';
		if ($text == '') {
			if ($title != ''){
				$text .=  '<h1>' . PhocaGalleryText::filterValue($title, 'text') . '</h1>';
			}
			if ($description != '') {
				$text .= '<div>'. PhocaGalleryText::strTrimAll(PhocaGalleryText::filterValue($description, 'text')).'</div>';
			}
		}

		$output .= 'var phocaPoint'.$id.' = new google.maps.LatLng('. PhocaGalleryText::filterValue($latitude, 'number2').', ' .PhocaGalleryText::filterValue($longitude, 'number2').');'."\n";
		$output .= 'var markerPhocaMarker'.$id.' = new google.maps.Marker({title:"'.PhocaGalleryText::filterValue($title, 'text').'"';

		if ($icon == 1) {
			$output .= ', icon:phocaImage';
			$output .= ', shadow:phocaImageShadow';
			$output .= ', shape:phocaImageShape';
		}

		$output .= ', position: phocaPoint'.$id;
		$output .= ', map: '.$this->_map;
		$output .= '});'."\n";

		$output .= 'var infoPhocaWindow'.$id.' = new google.maps.InfoWindow({'."\n"
				 .' content: \''.$text.'\''."\n"
				 .'});'."\n";

		$output .= 'google.maps.event.addListener(markerPhocaMarker'.$id.', \'click\', function() {'."\n"
			.' infoPhocaWindow'.$id.'.open('.$this->_map.', markerPhocaMarker'.$id.' );'."\n"
			.' });'."\n";
		return $output;
	}

	public function setMarkerIcon($icon) {

		$output['icon']	= 0;
		$output['js'] 	= '';
		switch ($icon) {

			case 1:
				$imagePath = JURI::base(true).'/media/com_phocagallery/images/mapicons/yellow/';
				$js ='var phocaImage = new google.maps.MarkerImage(\''.$imagePath.'image.png\','."\n";
				$js.='new google.maps.Size(26,30),'."\n";
				$js.='new google.maps.Point(0,0),'."\n";
				$js.='new google.maps.Point(0,30));'."\n";

				$js.='var phocaImageShadow = new google.maps.MarkerImage(\''.$imagePath.'shadow.png\','."\n";
				$js.='new google.maps.Size(41,30),'."\n";
				$js.='new google.maps.Point(0,0),'."\n";
				$js.='new google.maps.Point(0,30));'."\n";

				$js.='var phocaImageShape = {'."\n";
				$js.='coord: [18,1,19,2,21,3,23,4,24,5,24,6,24,7,24,8,23,9,23,10,22,11,22,12,21,13,20,14,20,15,19,16,19,17,18,18,17,19,18,20,20,21,22,22,22,23,22,24,22,25,18,26,15,27,12,28,8,29,4,29,4,28,3,27,3,26,3,25,3,24,3,23,2,22,2,21,2,20,2,19,2,18,1,17,1,16,1,15,1,14,1,13,1,12,1,11,9,10,10,9,10,8,11,7,11,6,12,5,12,4,13,3,14,2,14,1],'."\n";
				$js.='type: \'poly\''."\n";
				$js.=' };'."\n";
				$output['icon']	= 1;
				$output['js'] 	= $js;
			break;

			Default:
				$output['icon']	= 0;
				$output['js'] 	= '';// if Default Icon should be displayed, no Icon should be created
			break;
		}
		return $output;
	}

	public function setInitializeF() {

		/* google.load("maps", "3.x", {"other_params":"sensor=false"}); */
		$js = 'function initMap() {'."\n"
			 .'   '.$this->_tst.'.setAttribute("oldValue",0);'."\n"
		     .'   '.$this->_tst.'.setAttribute("refreshMap",0);'."\n"
		     .'   '.$this->_tstint.' = setInterval("CheckPhocaMap()",500);'."\n"
			.'}'."\n";
			//.'google.setOnLoadCallback(initMap);'."\n";
		return $js;
	}

	public function setListener() {
		$js = 'google.maps.event.addDomListener('.$this->_tst.', \'DOMMouseScroll\', CancelEventPhocaMap);'."\n"
		     .'google.maps.event.addDomListener('.$this->_tst.', \'mousewheel\', CancelEventPhocaMap);';
		return $js;
	}

	public function checkMapF() {
		$js ='function CheckPhocaMap() {'."\n"
			.'   if ('.$this->_tst.') {'."\n"
			.'      if ('.$this->_tst.'.offsetWidth != '.$this->_tst.'.getAttribute("oldValue")) {'."\n"
			.'         '.$this->_tst.'.setAttribute("oldValue",'.$this->_tst.'.offsetWidth);'."\n"
			.'		   if ('.$this->_tst.'.getAttribute("refreshMap")==0) {'."\n"
			.'		      if ('.$this->_tst.'.offsetWidth > 0) {'."\n"
			.'			     clearInterval('.$this->_tstint.');'."\n"
			.'				 getPhocaMap();'."\n"
			.'				 '.$this->_tst.'.setAttribute("refreshMap", 1);'."\n"
			.'			  } '."\n"
			.'		   }'."\n"
			.'		}'."\n"
			.'   }'."\n"
			.'}'."\n";
		return $js;
	}


	public function cancelEventF() {
		$js ='function CancelEventPhocaMap(event) { '."\n"
			.'   var e = event; '."\n"
			.'   if (typeof e.preventDefault == \'function\') e.preventDefault(); '."\n"
			.'   if (typeof e.stopPropagation == \'function\') e.stopPropagation(); '."\n"
			.'   if (window.event) { '."\n"
			.'       window.event.cancelBubble = true; /* for IE */'."\n"
			.'	     window.event.returnValue = false; /* for IE */'."\n"
			.'   } '."\n"
			.'}'."\n";
		return $js;
	}

	public function startMapF() {
		$js = 'function getPhocaMap(){'."\n"
			 .'   if ('.$this->_tst.'.offsetWidth > 0) {'."\n";
		return $js;
	}

	public function endMapF() {
		$js = '   }'."\n"
			 .'}'."\n";
		return $js;
	}

	public function exportZoom($zoom, $value = '', $jForm = '') {
		$js ='var phocaStartZoom 	= '.(int)$zoom.';'."\n"
			.'var phocaZoom 		= null;'."\n"
			.'google.maps.event.addListener('.$this->_map.', "zoom_changed", function(phocaStartZoom, phocaZoom) {'."\n"
			.'phocaZoom = '.$this->_map.'.getZoom();'."\n";
			if ($value != '') {
				$js .= '   '.$value.'.value = phocaZoom;'."\n";
			} else if ($jForm != '') {
				$js .= '   if (window.parent) window.parent.'.$jForm.'(phocaZoom);'."\n";
			}
			$js .= '});'."\n";
		return $js;
	}


	public function exportMarker($id, $latitude, $longitude, $valueLat = '', $valueLng = '', $jFormLat = '', $jFormLng = '') {

		$js = 'var phocaPoint'.$id.' = new google.maps.LatLng('. PhocaGalleryText::filterValue($latitude, 'number2').', ' .PhocaGalleryText::filterValue($longitude, 'number2').');'."\n";
		$js .= 'var markerPhocaMarker'.$id.' = new google.maps.Marker({'."\n"
			 .'   position: phocaPoint'.$id.','."\n"
			 .'   map: '.$this->_map.','."\n"
			 .'   draggable: true'."\n"
		     .'});'."\n";

		$js .= 'var infoPhocaWindow'.$id.' = new google.maps.InfoWindow({'."\n"
			  .'	content: markerPhocaMarker'.$id.'.getPosition().toUrlValue(6)'."\n"
			  .'});'."\n";

		// Events
		$js .= 'google.maps.event.addListener(markerPhocaMarker'.$id.', \'dragend\', function() {'."\n"
			.'var phocaPointTmp = markerPhocaMarker'.$id.'.getPosition();'."\n"
			.'markerPhocaMarker'.$id.'.setPosition(phocaPointTmp);'."\n"
			.'closeMarkerInfo'.$id.'();'."\n"
			.'exportPoint'.$id.'(phocaPointTmp);'."\n"
			.'});'."\n";

		// The only one place which needs to be edited to work with more markers
		// Comment it for working with more markers
		// Or add new behaviour to work with adding new marker to the map
		$js .= 'google.maps.event.addListener('.$this->_map.', \'click\', function(event) {'."\n"
			.'var phocaPointTmp2 = event.latLng;'."\n"
			.'markerPhocaMarker'.$id.'.setPosition(phocaPointTmp2);'."\n"
			.'closeMarkerInfo'.$id.'();'."\n"
			.'exportPoint'.$id.'(phocaPointTmp2);'."\n"
		   .'});'."\n";

		$js .= 'google.maps.event.addListener(markerPhocaMarker'.$id.', \'click\', function(event) {'."\n"
				.'openMarkerInfo'.$id.'();'."\n"
				.'});'."\n";

		$js .= 'function openMarkerInfo'.$id.'() {'."\n"
				.'infoPhocaWindow'.$id.'.content = markerPhocaMarker'.$id.'.getPosition().toUrlValue(6);'."\n"
				.'infoPhocaWindow'.$id.'.open('.$this->_map.', markerPhocaMarker'.$id.' );'."\n"
				.'} '."\n";
		 $js .= 'function closeMarkerInfo'.$id.'() {'."\n"
				.'infoPhocaWindow'.$id.'.close('.$this->_map.', markerPhocaMarker'.$id.' );'."\n"
				.'} '."\n";

		$js .= 'function exportPoint'.$id.'(phocaPointTmp3) {'."\n";
			if ($valueLat != '') {
				$js .= '   '.PhocaGalleryText::filterValue($valueLat).'.value = phocaPointTmp3.lat();'."\n";
			}
			if ($valueLng != '') {
				$js .= '   '.PhocaGalleryText::filterValue($valueLng).'.value = phocaPointTmp3.lng();'."\n";
			}

			if ($jFormLat != '') {
				$js .= '   if (window.parent) window.parent.'.PhocaGalleryText::filterValue($jFormLat).'(phocaPointTmp3.lat());'."\n";
			}
			if ($jFormLng != '') {
				$js .= '   if (window.parent) window.parent.'.PhocaGalleryText::filterValue($jFormLng).'(phocaPointTmp3.lng());'."\n";
			}
		$js .= '}'."\n";

		return $js;
	}
}
?>
