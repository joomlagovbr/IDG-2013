/**
 * This file contains modifications to Highslide JS for optimizing the display on mobile user agents.
 * 
 * @author Torstein Hønsi
 */
if (/(Android|BlackBerry|iPhone|iPod|Palm|Symbian)/.test(navigator.userAgent)) {
//if (true) {
	hs.addEventListener(document, 'ready', function() {

		// Add a meta tag to have the iPhone render the page 1:1
		hs.createElement('meta', {
			name: 'viewport',
			content: 'width=device-width; initial-scale=1.0; maximum-scale=1.0;'
		}, null, document.getElementsByTagName('head')[0]);
		
		// Add CSS rules
		/* edit '	width: 50px; '+ ==> '	width: 100%; '+ */
		var stylesheet = document.getElementsByTagName('style')[0];
		stylesheet.appendChild(document.createTextNode(
			'.highslide img {'+
			'	width: 100%; '+
			'}'+
			'.highslide-wrapper div.navbutton {'+
			'	color: white;'+
			'	font-size: 64px;'+
			'}'+
			'.highslide-full-expand {'+
			'	display: none !important;'+
			'}'+
			'.highslide-wrapper {'+
			'	background: none !important;'+
			'}'+
			'.highslide-caption {'+
			'	border: none !important;'+
			'	color: white !important;'+
			'	background: none !important;'+
			'}'
		));

		// add some options that make sense on a small touchscreen
		hs.outlineType = null; // outlines look distorted at normal zoom
		hs.expandDuration = 0; // animation is too slow anyway
		hs.restoreDuration = 0;
		hs.transitionDuration = 0;
		hs.wrapperClassName = 'borderless draggable-header mobile'; // take all the space available for the image
		hs.marginTop = 0;
		hs.marginRight = 0;
		hs.marginBottom = 0;
		hs.marginLeft = 0;
		hs.captionOverlay.fade = false;
		hs.allowHeightReduction = false; // t=10503
		
		// Remove any slideshows with too small controls
		hs.slideshows = [];
		
		// Create custom previous and next overlays
		hs.registerOverlay({
			position: 'middle left',
			width: '20%',
			html: '<div class="navbutton"  onclick="hs.previous()"  title="'+
				hs.lang.previousTitle +'">&lsaquo;</div>',
			hideOnMouseOut: false
		});
		hs.registerOverlay({
			position: 'middle right',
			width: '20%',
			html: '<div class="navbutton" style="text-align: right" onclick="hs.next()" title="'+
				hs.lang.nextTitle +'">&rsaquo;</div>',
			hideOnMouseOut: false
		});
	
	});

}