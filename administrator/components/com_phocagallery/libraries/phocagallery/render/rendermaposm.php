<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderMaposm
{

	protected $name					= 'phocaGalleryMap';
	protected $id					= '';
	private	$output					= array();

	public $router					= '';
	public $maprouterapikey 		= '';
	public $routerserviceurl 		= '';
	public $routerprofile 			= '';
	public $thunderforestmaptype	= '';
	public $osmmaptype				= '';
	public $currentposition			= '';
	public $fullscreen				= '';
	public $search					= '';
	public $zoomwheel				= '';
	public $zoomcontrol				= '';
	public $easyprint				= '';

	/*var $_map			= 'mapPhocaMap';
	var $_latlng		= 'phocaLatLng';
	var $_options		= 'phocaOptions';
	var $_tst			= 'tstPhocaMap';
	var $_tstint		= 'tstIntPhocaMap';
	var $_marker		= FALSE;
	var $_window		= FALSE;
	var $_dirdisplay	= FALSE;
	var $_dirservice	= FALSE;
	var $_geocoder		= FALSE;
	var $_iconArray		= array();*/

	function __construct($id = '') {


		$app 						= JFactory::getApplication();
		$paramsC 					= JComponentHelper::getParams('com_phocagallery');
		$this->router 				= $paramsC->get( 'osm_router', 0 );//
		$this->maprouterapikey 		= $paramsC->get( 'osm_map_router_api_key', '' );
		$this->routerserviceurl 	= $paramsC->get( 'osm_router_routerserviceurl', '' );//
		$this->routerprofile 		= $paramsC->get( 'osm_router_profile', '' );//
		$this->thunderforestmaptype	= $paramsC->get( 'thunderforest_map_type', '' );
		$this->osmmaptype			= $paramsC->get( 'osm_map_type', '' );

		$this->currentposition		= $paramsC->get( 'osm_current_position', 1 ); //
		$this->fullscreen			= $paramsC->get( 'osm_full_screen',1 );//
		$this->search				= $paramsC->get( 'osm_search', 0 );//
		$this->zoomwheel			= $paramsC->get( 'osm_zoom_wheel', 1);//
		$this->zoomcontrol			= $paramsC->get( 'osm_zoom_control', 1 );//
		$this->easyprint			= $paramsC->get( 'osm_easyprint', 0 );//



		$this->id	= $id;




	//	if ($app->isClient('administrator')) {
			$this->fullscreen 		= 1;
			$this->search			= 1;
			$this->zoomwheel		= 1;
			$this->zoomcontrol		= 1;
			$this->currentposition 	= 1;
	//	}
	}



	function loadAPI() {
		$document	= JFactory::getDocument();


		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet/leaflet.js');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet/leaflet.css');

		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-awesome/leaflet.awesome-markers.js');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-awesome/leaflet.awesome-markers.css');

		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-fullscreen/Leaflet.fullscreen.js');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-fullscreen/leaflet.fullscreen.css');


		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-control-locate/L.Control.Locate.min.js');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-control-locate/L.Control.Locate.css');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-control-locate/font-awesome.min.css');

		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-omnivore/leaflet-omnivore.js');

		$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-search/leaflet-search.min.js');
		$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-search/leaflet-search.css');

		if ($this->router == 1) {
			$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-routing-machine/leaflet-routing-machine.min.js');
			$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-routing-machine/leaflet-routing-machine.css');

			$document->addStyleSheet(JURI::root(true) . '/media/com_phocagallery/js/leaflet-geocoder/Control.Geocoder.css');
			$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-geocoder/Control.Geocoder.js');
		}

		if ($this->easyprint == 1) {
			$document->addScript(JURI::root(true) . '/media/com_phocagallery/js/leaflet-easyprint/bundle.js');

		}

	}

	function loadCoordinatesJS() {
		$document	= JFactory::getDocument();
		$document->addScript(JURI::root(true).'/media/com_phocagallery/js/administrator/coordinates.js');
	}

	function createMap($lat, $lng, $zoom) {

		$app = JFactory::getApplication();

		$opt = array();
		if ($this->zoomwheel == 0) {
			$opt[] = 'scrollWheelZoom: false,';
		}
		if ($this->zoomcontrol == 0) {
			$opt[] = 'zoomControl: false,';
		}

		$options = '{' . implode("\n", $opt) . '}';

		$o 	= array();

		$o[]= 'var map'.$this->name.$this->id.' = L.map("'.$this->name.$this->id.'", '.$options.').setView(['.PhocaGalleryText::filterValue($lat, 'number2').', '.PhocaGalleryText::filterValue($lng, 'number2').'], '.(int)$zoom.');';


		$this->output[] = implode("\n", $o);
		return true;
	}

	function setMapType() {

		$app = JFactory::getApplication();

		// Possible new parameters
		$thunderForestMapType = $this->thunderforestmaptype;
		$thunderForestKey	= $this->maprouterapikey;
		$mapBoxKey	= $this->maprouterapikey;
		$type = $this->osmmaptype;

		$o = array();
		if ($type === "osm_de") {

			$o[] = 'L.tileLayer(\'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 18,';
			$o[] = '	attribution: \'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type === "osm_bw") {

			//$o[] = 'L.tileLayer(\'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png\', {';
			$o[] = 'L.tileLayer(\'https://tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png\', {';

			$o[] = '	maxZoom: 18,';
			$o[] = '	attribution: \'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type === 'thunderforest') {

			if ($thunderForestKey == '') {
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_ERROR_API_KEY_NOT_SET'));
				return false;
			}
			if ($thunderForestMapType == '') {
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_ERROR_MAP_TYPE_NOT_SET'));
				return false;
			}
			$o[] = 'L.tileLayer(\'https://{s}.tile.thunderforest.com/'.PhocaGalleryText::filterValue($thunderForestMapType, 'url').'/{z}/{x}/{y}.png?apikey={apikey}\', {';
			$o[] = '	maxZoom: 22,';
			$o[] = '	apikey: '.PhocaGalleryText::filterValue($thunderForestKey).',';
			$o[] = '	attribution: \'&copy; <a href="https://www.thunderforest.com/" target="_blank">Thunderforest</a>, &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type === 'mapbox') {

			if ($mapBoxKey == '') {
				$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_ERROR_API_KEY_NOT_SET'));
				return false;
			}


			$o[] = 'L.tileLayer(\'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='.PhocaGalleryText::filterValue($mapBoxKey, 'url').'\', {';
			$o[] = '	maxZoom: 18,';
			$o[] = '	attribution: \'Map data &copy; <a href="https://openstreetmap.org" target="_blank">OpenStreetMap</a> contributors, \' + ';
			$o[] = '		\'<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank" target="_blank">CC-BY-SA</a>, \' + ';
			$o[] = '		\'Imagery © <a href="https://mapbox.com" target="_blank">Mapbox</a>\',';
			$o[] = '	id: \'mapbox.streets\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type === 'opentopomap') {

			$o[] = 'L.tileLayer(\'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 17,';
			$o[] = '	attribution: \'Map data: &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>, <a href="https://viewfinderpanoramas.org" target="_blank">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org" target="_blank">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">CC-BY-SA</a>)\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type === 'google') {
			/*
			$o[] = 'L.gridLayer.googleMutant({';
			$o[] = '	type: googlemapstype,';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';
			*/
		} else if ($type === 'wikimedia') {
			$o[] = 'L.tileLayer(\'https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 18,';
			$o[] = '	attribution: \'&copy; <a href="https://wikimediafoundation.org/wiki/Maps_Terms_of_Use" target="_blank">Wikimedia maps</a> | Map data © <a href="https://openstreetmap.org/copyright" target="_blank">OpenStreetMap contributors</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type == 'osm_fr') {

			$o[] = 'L.tileLayer(\'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 20,';
			$o[] = '	attribution: \'&copy; <a href="https://www.openstreetmap.fr" target="_blank">Openstreetmap France</a> & <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else if ($type == 'osm_hot') {

			$o[] = 'L.tileLayer(\'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 20,';
			$o[] = '	attribution: \'&copy; <a href="https://hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> & <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		} else {


			$o[] = 'L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {';
			$o[] = '	maxZoom: 18,';
			$o[] = '	attribution: \'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>\'';
			$o[] = '}).addTo(map'.$this->name.$this->id.');';

		}

		$this->output[] = implode("\n", $o);
		return true;
	}

	public function setMarker($markerId, $title, $description, $lat, $lng, $text = '', $width = '', $height = '', $open = 0, $closeOpenedWindow = 0) {


		$o = array();


		if($open != 2){
			$o[]= 'var marker'.$markerId.' = L.marker(['.PhocaGalleryText::filterValue($lat, 'number2').', '.PhocaGalleryText::filterValue($lng, 'number2').']).addTo(map'.$this->name.$this->id.');';
		}

		jimport('joomla.filter.output');

		$style = '';
		if ($width != '') {
			$style .= 'width: '.(int)$width.'px;';
		}
		if ($height != '') {
			$style .= 'height: '.(int)$height.'px;';
		}

		if ($text == '') {
			if ($title != ''){
				$hStyle = 'font-size:120%;margin: 5px 0px;font-weight:bold;';
				$text .= '<div style="'.$hStyle.'">' . addslashes($title) . '</div>';
			}
			if ($description != '') {
				$text .=  '<div>'.PhocaGalleryText::strTrimAll(addslashes($description)).'</div>';
			}
		}



		if ($text != '') {
			if ($style != '') {
				$text = '<div style="'.$style.'">' . $text . '</div>';
			}

			$openO = '';
			if ($open == 1) {
				$openO = '.openPopup()';
			}
			$o[]= 'marker'.$markerId.'.bindPopup(\''.$text.'\')'.$openO.';';
		}




		$this->output[] = implode("\n", $o);
		return true;

	}

	public function setMarkerIcon($markerId, $icon = 'circle', $markerColor = 'blue', $iconColor = '#ffffff', $prefix = 'fa', $spin = 'false', $extraClasses = '' ) {

		$o = $o2 = array();

		$o[]= 'var icon'.$markerId.' = new L.AwesomeMarkers.icon({';

		$o[]= $o2[] = '   icon: "'.PhocaGalleryText::filterValue($icon).'",';
		$o[]= $o2[] = '   markerColor: "'.PhocaGalleryText::filterValue($markerColor).'",';
		$o[]= $o2[] = '   iconColor: "'.PhocaGalleryText::filterValue($iconColor).'",';
		$o[]= $o2[] = '   prefix: "'.PhocaGalleryText::filterValue($prefix).'",';
		$o[]= $o2[] = '   spin: '.PhocaGalleryText::filterValue($spin).',';
		$o[]= $o2[] = '   extraClasses: "'.PhocaGalleryText::filterValue($extraClasses).'",';

		$o[]= '})';
		$o[]= ' marker'.$markerId.'.setIcon(icon'.$markerId.');';

		$this->output[] = implode("\n", $o);
		return $o2;//return only options;
	}


	public function inputMarker($latInput, $longInput, $zoomInput = '', $setGPS = 0) {

		$o = array();
		$o[]= 'function phmInputMarker(lat, lng) {';
		$o[]= 'var phLat = jQuery(\'#jform_latitude\', window.parent.document);';
		$o[]= 'var phLng = jQuery(\'#jform_longitude\', window.parent.document);';

		$o[]= 'phLat.val(lat);';
		$o[]= 'phLng.val(lng);';

		if ( $zoomInput != '') {
			$o[]= 'var phZoom = jQuery(\'#jform_zoom\', window.parent.document);';
			$o[]= 'phZoom.val(map'.$this->name.$this->id.'.getZoom());';
			$o[]= 'var phmMsg = \'<span class="ph-msg-success">'.JText::_('COM_PHOCAGALLERY_LAT_LNG_ZOOM_SET').'</span>\';';
		} else {
			$o[]= 'var phmMsg = \'<span class="ph-msg-success">'.JText::_('COM_PHOCAGALLERY_LAT_LNG_SET').'</span>\';';
		}

		$o[]= 'jQuery(\'#phmPopupInfo\', window.parent.document).html(phmMsg);';

		if ($setGPS == 1) {
			$o[]= '   if (window.parent) setPMGPSLatitudeJForm(lat);';
			$o[]= '   if (window.parent) setPMGPSLongitudeJForm(lng);';
		}
		$o[]= '}';
		$this->output[] = implode("\n", $o);
		return true;
	}


	public function moveMarker() {

		$o = array();
		$o[]= 'function phmMoveMarker(marker, lat, lng) {';
		$o[]= '   var newLatLng = new L.LatLng(lat, lng);';
		$o[]= '   marker.setLatLng(newLatLng);';
		$o[]= '}';
		$this->output[] = implode("\n", $o);
		return true;
	}

	public function exportMarker($markerId) {

		$o 	= array();
		$o[] = 'map'.$this->name.$this->id.'.on(\'click\', onMapClick);';

		$o[] = 'function onMapClick(e) {';
		$o[] = '	phmInputMarker(e.latlng.lat, e.latlng.lng);';
		$o[] = '	phmMoveMarker(marker'.$markerId.', e.latlng.lat, e.latlng.lng);';
		$o[] = '}';
		$this->output[] = implode("\n", $o);
		return true;
	}


	public function renderSearch($markerId = '', $position = '') {



		$position = $position != '' ? $position : 'topright';
		$o 	= array();
		$o[] = 'map'.$this->name.$this->id.'.addControl(new L.Control.Search({';

		$o[] = '	url: \'https://nominatim.openstreetmap.org/search?format=json&q={s}\',';
		$o[] = '	jsonpParam: \'json_callback\',';
		$o[] = '	propertyName: \'display_name\',';
		$o[] = '	propertyLoc: [\'lat\',\'lon\'],';
		$o[] = '	marker: L.circleMarker([0,0],{radius:30}),';
		$o[] = '	autoCollapse: true,';
		$o[] = '	autoType: false,';
		$o[] = '	minLength: 3,';
		$o[] = '	position: \''.$position.'\',';
		if ($markerId != '') {
			$o[] = '	moveToLocation: function(latlng, title, map) {';
			$o[] = '		phmInputMarker(latlng.lat, latlng.lng);';
			$o[] = '		phmMoveMarker(marker'.$markerId.', latlng.lat, latlng.lng);';
			$o[] = '		map'.$this->name.$this->id.'.setView(latlng, 7);';// set the zoom
			$o[] = '	}';
		}
		$o[] = '}));';

		$this->output[] = implode("\n", $o);
		return true;
	}

	public function renderFullScreenControl() {


		if ($this->fullscreen == 0) {
			return false;
		}

		$o 	= array();
		$o[] = 'map'.$this->name.$this->id.'.addControl(';

		$o[] = '	new L.Control.Fullscreen({';
		$o[] = '		position: \'topright\',';
		$o[] = '		title: {';
		$o[] = '			\'false\': \''.JText::_('COM_PHOCAGALLERY_VIEW_FULLSCREEN').'\',';
		$o[] = '			\'true\': \''.JText::_('COM_PHOCAGALLERY_EXIT_FULLSCREEN').'\'';
		$o[] = '		}';
		$o[] = '	})';

		$o[] = ')';

		$this->output[] = implode("\n", $o);
		return true;

	}

	public function renderCurrentPosition() {


		if ($this->currentposition == 0) {
			return false;
		}

		$o 	= array();

		$o[] = 'L.control.locate({';
		$o[] = '	position: \'topright\',';
		$o[] = '	strings: {';
		$o[] = '		\'title\': \''.JText::_('COM_PHOCAGALLERY_CURRENT_POSITION').'\'';
		$o[] = '	},';
		$o[] = '	locateOptions: {';
		$o[] = '		enableHighAccuracy: true,';
		$o[] = '		watch: true,';
		$o[] = '	}';
		$o[] = '}).addTo(map'.$this->name.$this->id.');';


		$this->output[] = implode("\n", $o);
		return true;

	}

	public function renderEasyPrint() {


		if ($this->easyprint == 0) {
			return false;
		}

		$o 	= array();

		$o[] = 'map'.$this->name.$this->id.'.addControl(';
		$o[] = '	new L.easyPrint({';
		$o[] = '	   hideControlContainer: true,';
		$o[] = '	   sizeModes: [\'Current\', \'A4Portrait\', \'A4Landscape\'],';
		$o[] = '	   position: \'topleft\',';
		$o[] = '	   exportOnly: true';
		$o[] = '	})';
		$o[] = ');';


		$this->output[] = implode("\n", $o);
		return true;

	}


	public function renderRouting($latFrom = 0, $lngFrom = 0, $latTo = 0, $lngTo = 0, $markerId = '', $markerIconOptions = array(), $language = '') {

		if ($this->router == 0) {
			return false;
		}


		$o 	= array();
		if ($this->routerserviceurl == '' && $this->maprouterapikey == '') {
			$o[] = 'console.log(\'Routing Error: No router or service url set\')';
			$this->output[] = implode("\n", $o);
			return true;
		}

		$o[] = 'var routingControl = L.Routing.control({';
		$o[] = '   waypoints: [';


		if ($latFrom == 0 && $lngFrom == 0 && $latTo != 0 && $lngTo != 0) {
			$o[] = '      L.latLng(\'\'),';
		} else if ($latFrom == 0 && $lngFrom == 0) {
			$o[] = '      L.latLng(\'\'),';
		} else {
			$o[] = '      L.latLng('.PhocaGalleryText::filterValue($latFrom, 'number2').', '.PhocaGalleryText::filterValue($lngFrom, 'number2').'),';
		}
	    if ($latTo == 0 && $lngTo == 0) {
	    	$o[] = '      L.latLng(\'\'),';
	    } else {
	    	$o[] = '      L.latLng('.PhocaGalleryText::filterValue($latTo, 'number2').', '.PhocaGalleryText::filterValue($lngTo, 'number2').')';
	    }
	    $o[] = '   ],';
	    if ($language != '') {
	     	$o[] = '   language: \''.PhocaGalleryText::filterValue($language, 'text').'\',';
	    }

	    if ($markerId != '') {

	    	//$o[] = '   marker: marker'.$markerId.',';

	    	// Don't create new marker for routing (so if we have "TO" address with marker created in map
	    	// don't display any marker
	    	//if (!empty($markerIconOptions)) {
	    	if ($latTo != 0 && $lngTo != 0) {
	    		$o[] = '   createMarker: function(i,wp, n) {';

	    		$o[] = '      var latToMarker = '.PhocaGalleryText::filterValue($latTo, 'number2').';';
	    		$o[] = '      var lngToMarker = '.PhocaGalleryText::filterValue($lngTo, 'number2').';';

	    		$o[] = '      if (wp.latLng.lat == latToMarker && wp.latLng.lng == lngToMarker) {';
	    		$o[] = '         return false;';
	    		$o[] = '      } else {';

	    		// Get the same icon as the "To" (End) has
	    		if (!empty($markerIconOptions)) {

	    			$o[] = '       var ma = L.marker(wp.latLng);';
		    		$o[] = '       var ic = new L.AwesomeMarkers.icon({';
		    		foreach($markerIconOptions as $k => $v) {

		    			// Change the icon to circle (e.g. the "To" (End) is set to home, so don't render the same icon for "From" (start) address
		    			if (strpos($v, 'icon:') !== false) {
		    				$v = 'icon: "circle",';
		    			}

		    			$o[] = '          '.$v. "\n";
		    		}
		    		$o[] = '       });';
		    		$o[] = '       ma.setIcon(ic);';
		    		$o[] = '       return ma;';

	    		} else {
	    			$o[] = '         return L.marker(wp.latLng);';
	    		}

	    		$o[] = '      }';
	    		$o[] = '   },';
	    	}

	    }


	    $o[] = '   routeWhileDragging: true,';
	    $o[] = '   geocoder: L.Control.Geocoder.nominatim(),';
	    $o[] = '   reverseWaypoints: true,';
	    $o[] = '   showAlternatives: true,';
	    $o[] = '   collapsible: true,';
	    $o[] = '   show: false,';


	    if ($this->routerserviceurl == 'https://api.mapbox.com/directions/v5') {
	    	// DEBUG DEMO - default address of leaflet-routing-machine to debug

	    } else if ($this->routerserviceurl != '') {
	    	$o[] = '   routerserviceurl: \''.$this->routerserviceurl.'\',';
	    } else if ($this->osm_map_type == 'mapbox' && $this->maprouterapikey != '') {
	    	$o[] = '   router: L.Routing.mapbox(\''.PhocaGalleryText::filterValue($this->maprouterapikey).'\'),';
	    } else {
			$o[] = array();
			$o[] = 'console.log(\'Routing Error: No router or service url set\')';
			$this->output[] = implode("\n", $o);
			return true;

		}


	    if ($this->routerprofile != '') {
	    	$o[] = '   profile: \''.PhocaGalleryText::filterValue($this->routerprofile).'\',';
	    }
	    $o[] = '})';

	   // $o[] = '.on(\'routingstart\', showSpinner)';
	    //$o[] = '.on(\'routesfound routingerror\', hideSpinner)';
	    $o[] = '.addTo(map'.$this->name.$this->id.');';

	    //$o[] = 'routingControl.hide();';

	    $this->output[] = implode("\n", $o);
		return true;
	}

	public function renderMap() {
		$o = array();
		$o[] = 'jQuery(document).ready(function() {';
		$o[] = implode("\n", $this->output);
		$o[] = '})';
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $o));
	}
}
?>
