<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderDetailWindow
{
	
	public $b1;// Image
	public $b2;// Zoom Icon
	public $b3;// Map, Exif, ...
	
	public $popupHeight;
	public $popupWidth;
	public $mbOverlayOpacity; // Modal Box
	public $sbSlideshowDelay; // Shadowbox
	public $sbSettings;
	public $hsSlideshow;      // Highslide
	public $hsClass;
	public $hsOutlineType;
	public $hsOpacity;
	public $hsCloseButton;
	public $jakDescHeight;	  // JAK
	public $jakDescWidth;
	public $jakOrientation;
	public $jakSlideshowDelay;
	public $bpBautocenter;     // boxplus
	public $bpAutofit;
	public $bpSlideshow;
	public $bpLoop;
	public $bpCaptions;
	public $bpThumbs;
	public $bpDuration;
	public $bpTransition;
	public $bpContextmenu;
	public $extension;
	public $jakRandName;

	
	public function __construct() {}
	
	public function setButtons($method = 0, $libraries = array(), $library = array()) {
	
		$document	= JFactory::getDocument();
		
		// BUTTON (IMAGE - standard, modal, shadowbox)
		$this->b1 = new JObject();
		$this->b1->set('name', 'image');
		$this->b1->set('options', '');//initialize
		
		//BUTTON (ICON - standard, modal, shadowbox)
		$this->b2 = new JObject();
		$this->b2->set('name', 'icon');
		$this->b2->set('options', '');//initialize
		
		//BUTTON OTHER (geotagging, downloadlink, ...)
		$this->b3 = new JObject();
		$this->b3->set('name', 'other');
		$this->b3->set('options', '');//initialize
		$this->b3->set('optionsrating', '');//initialize
		
	
	
		// Modal Box
		switch($method) {
			
			case 1:
			//STANDARD JS POPUP
			$this->b1->set('methodname', 'js-button');
			$this->b1->set('options', "window.open(this.href,'win2','width=".$this->popupWidth.",height=".$this->popupHeight.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
			$this->b1->set('optionsrating', "window.open(this.href,'win2','width=".$this->popupWidth.",height=".$this->popupHeight.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
			
			$this->b2->methodname 		= &$this->b1->methodname;
			$this->b2->options 			= &$this->b1->options;
			$this->b3->methodname  		= &$this->b1->methodname;
			$this->b3->options 			= &$this->b1->options;
			$this->b3->optionsrating 	= &$this->b1->optionsrating;
			break;
			
			case 0:
			case 2:
			
			// MODALBOX
			$this->b1 = new JObject();
			$this->b1->set('name', 'image');
			$this->b1->set('modal', true);
			$this->b1->set('methodname', 'pg-modal-button');
			
			$this->b2->modal 		= &$this->b1->modal;
			$this->b2->methodname 	= &$this->b1->methodname;
			$this->b3->modal 		= &$this->b1->modal;
			$this->b3->methodname  	= &$this->b1->methodname;
		
			// Modal - Image only
			if ($method == 2) {
				$this->b1->set('options', "{handler: 'image', size: {x: 200, y: 150}, overlayOpacity: ". $this->mbOverlayOpacity ."}");
				$this->b2->options 	= &$this->b1->options;
				$this->b3->set('options', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
				$this->b3->set('optionsrating', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}"); 
			// Modal - Iframe 			
			} else {
				$this->b1->set('options', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
				$this->b3->set('optionsrating', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
				
				$this->b2->options 		= &$this->b1->options;
				$this->b3->options  	= &$this->b1->options;
			
			}
			
			break;
			
			case 3:
			
			// SHADOWBOX (Image Only)
			JHtml::_('jquery.framework', true);// Load it here because of own nonConflict method (nonconflict is set below)
			
			$this->b1->set('methodname', 'shadowbox-button');
			$this->b1->set('options', "shadowbox[PhocaGallery".$this->extension."];options={slideshowDelay:".$this->sbSlideshowDelay."}");
			$this->b2->methodname 		= &$this->b1->methodname;
			$this->b2->set('options', "shadowbox[PhocaGallery2".$this->extension."];options={slideshowDelay:".$this->sbSlideshowDelay."}");
			
			/*$this->b3->set('modal', true);
			$this->b3->set('methodname', 'pg-modal-button');
			$this->b3->set('options', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
			$this->b3->set('optionsrating', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");*/
			
			$this->b3->methodname 		= &$this->b1->methodname;
			$this->b3->set('options', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery5".$this->extension."'};");
			$this->b3->set('optionsrating', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery6".$this->extension."'};");

			$sbSettingsO = '';
			if ($this->sbSettings != '') {
				$sbSettingsO = strip_tags($this->sbSettings);
			}
			if ( $libraries['pg-group-shadowbox']->value == 0 ) {
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/shadowbox/shadowbox.css');
				$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/shadowbox/shadowbox.js');
				$document->addCustomTag('<script type="text/javascript">
					Shadowbox.init({
					'.$sbSettingsO.'
					});
				</script>');
			}

			break;
			
			case 13:
			// SHADOWBOX
			JHtml::_('jquery.framework', true);// Load it here because of own nonConflict method (nonconflict is set below)
			
			$this->b1->set('methodname', 'shadowbox-button');
			$this->b1->set('options', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery'}");
			$this->b2->methodname 		= &$this->b1->methodname;
			$this->b2->set('options', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery2".$this->extension."'}");

			$this->b3->methodname 		= &$this->b1->methodname;
			$this->b3->set('options', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery5".$this->extension."'};");
			$this->b3->set('optionsrating', "shadowbox;width=".$this->popupWidth.";height=".$this->popupHeight.";options={gallery:'PhocaGallery6".$this->extension."'};");

			$sbSettingsO = '';
			if ($this->sbSettings != '') {
				$sbSettingsO = strip_tags($this->sbSettings);
			}	
			if ( $libraries['pg-group-shadowbox']->value == 0 ) {
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/shadowbox/shadowbox.css');
				$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/shadowbox/shadowbox.js');
				$document->addCustomTag('<script type="text/javascript">
					Shadowbox.init({
						'.$sbSettingsO.'
					});
				</script>');
			}
			break;
			
			case 4:
			case 5:
			// HIGHSLIDE JS, HIGHSLIDE JS IMAGE ONLY
			$this->b1->set('methodname', 'highslide');
			$this->b2->methodname 	= &$this->b1->methodname;
			$this->b3->methodname 	= &$this->b1->methodname;
			
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/highslide/highslide-full.js');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/highslide/highslide.css');
			
			if ( $libraries['pg-group-highslide']->value == 0 ) {
				$document->addCustomTag( self::renderHighslideJSAll());
				$document->addCustomTag('<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="'.JURI::base(true).'/components/com_phocagallery/assets/highslide/highslide-ie6.css" /><![endif]-->');
				$library->setLibrary('pg-group-highslide', 1);
			}
			
			$document->addCustomTag( self::renderHighslideJS($this->extension, $this->popupWidth, $this->popupHeight, $this->hsSlideshow, $this->hsClass, $this->hsOutlineType, $this->hsOpacity, $this->hsCloseButton));
			
			if ($method == 4) {
				$this->b1->set('highslideonclick', 'return hs.htmlExpand(this, phocaZoom )');
			} else {
				$this->b1->set('highslideonclick2', 'return hs.htmlExpand(this, phocaZoom )');
				$this->b1->set('highslideonclick', self::renderHighslideJSImage($this->extension, $this->hsClass, $this->hsOutlineType, $this->hsOpacity, $this->hsFullImg));
			}
			break;
			
			case 6:
			// JAK
			$this->b1->set('methodname', 'jaklightbox');
			$this->b2->methodname 		= &$this->b1->methodname;
			$this->b3->set('modal', true);
			$this->b3->set('methodname', 'pg-modal-button');
			$this->b3->set('options', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
			$this->b3->set('optionsrating', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
		
		
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jak/jak_compressed.js');
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jak/lightbox_compressed.js');
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jak/jak_slideshow.js');
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jak/window_compressed.js');
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jak/interpolator_compressed.js');		
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/jak/lightbox-slideshow.css');
			
			$lHeight 		= 472 + (int)$this->jakDescHeight;
			$lcHeight		= 10 + (int)$this->jakDescHeight;
			
			$customJakTag	= '';
			if ($this->jakOrientation == 'horizontal') {
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/jak/lightbox-horizontal.css');
			} else if ($this->jakOrientation == 'vertical'){
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/jak/lightbox-vertical.css');
				$customJakTag .= '.lightBox {height: '.$lHeight.'px;}'
							    .'.lightBox .image-browser-caption { height: '.$lcHeight.'px;}';
			} else  {
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/jak/lightbox-vertical.css');
				$customJakTag .= '.lightBox {height: '.$lHeight.'px;width:800px;}'
							.'.lightBox .image-browser-caption { height: '.$lcHeight.'px;}'
							.'.lightBox .image-browser-thumbs { display:none;}'
							.'.lightBox .image-browser-thumbs div.image-browser-thumb-box { display:none;}';
			}
			
			if ($customJakTag != '') {
				$document->addCustomTag("<style type=\"text/css\">\n". $customJakTag. "\n"."</style>");
			}
			// The only way how to work with bootstrap settings for img tag
			// http://htmldog.com/guides/cssadvanced/specificity/
			// (#foo img, .foo img does not help becasue img has higher priority then #foo img :-( )
			$document->addCustomTag("<style type=\"text/css\">\n". 'img {
	max-width: none !important;
}'. "\n"."</style>");
			
			if ( isset($libraries['pg-group-jak']) && $libraries['pg-group-jak']->value == 0 ) {		
				$document->addCustomTag( self::renderJakJs($this->jakSlideshowDelay, $this->jakOrientation));
				$library->setLibrary('pg-group-jak', 1);
			} else if ( isset($libraries['pg-group-jak-mod']) && $libraries['pg-group-jak-mod']->value == 0 ) {		
				$document->addCustomTag( self::renderJakJs($this->jakSlideshowDelay, $this->jakOrientation, $this->jakRandName));
				$library->setLibrary('pg-group-jak-mod', 1);
			} else if ( isset($libraries['pg-group-jak-pl']) && $libraries['pg-group-jak-pl']->value == 0 ) {		
				$document->addCustomTag( self::renderJakJs($this->jakSlideshowDelay, $this->jakOrientation, $this->jakRandName));
				$library->setLibrary('pg-group-jak-pl', 1);
			}
			
			break;
			
			case 7:
			// NO POPUP
			$this->b1->set('methodname', 'no-popup');
			$this->b2->methodname 	= &$this->b1->methodname;
			$this->b3->set('modal', true);
			$this->b3->set('methodname', 'no-popup');
			$this->b3->set('options', "");
			$this->b3->set('optionsrating', "");
			
			break;
			
			case 8:
			
			//First load mootools, then jquery and set noConflict
			//JHtml::_('behavior.framework', false);// Load it here to be sure, it is loaded before jquery
			JHtml::_('jquery.framework', true);// Load it here because of own nonConflict method (nonconflict is set below)
		
			$this->b1->set('methodname', 'slimbox');
			$this->b2->methodname 		= &$this->b1->methodname;
			$this->b2->set('options', "lightbox-images");
			$this->b3->set('modal', true);
			$this->b3->set('methodname', 'pg-modal-button');
			$this->b3->set('options', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
			$this->b3->set('optionsrating', "{handler: 'iframe', size: {x: ".$this->popupWidth.", y: ".$this->popupHeight."}, overlayOpacity: ".$this->mbOverlayOpacity."}");
		
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/slimbox/js/slimbox2.js');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/slimbox/css/slimbox2.css');
			
			break;
			
			case 9:
			case 10:
			// BOXPLUS (BOXPLUS + BOXPLUS (IMAGE ONLY))
			
			//First load mootools, then jquery and set noConflict
			//JHtml::_('behavior.framework', false);// Load it here to be sure, it is loaded before jquery
			//JHtml::_('jquery.framework', false);// Load it here because of own nonConflict method (nonconflict is set below)
			
			$language = JFactory::getLanguage();
			
			$this->b1->set('options', 'phocagallerycboxplus'.$this->extension);
			$this->b1->set('methodname', 'phocagallerycboxplus'.$this->extension);
			$this->b2->set('options', "phocagallerycboxplusi".$this->extension);
			$this->b2->set('methodname', 'phocagallerycboxplusi'.$this->extension);
			$this->b3->set('options', "phocagallerycboxpluso".$this->extension);
			$this->b3->set('methodname', 'phocagallerycboxpluso'.$this->extension);
			$this->b3->set('optionsrating', "phocagallerycboxpluso".$this->extension);
			
			//if ($crossdomain) {
			//	$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/boxplus/jsonp.mootools.js');
			//}
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/boxplus/boxplus.js');
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/boxplus/boxplus.lang.js?lang='.$language->getTag());
			
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.css');
			if ($language->isRTL()) {
				$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.rtl.css');
			}
			
			
			
			$document->addCustomTag('<!--[if lt IE 9]><link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.ie8.css" type="text/css" /><![endif]-->');
			$document->addCustomTag('<!--[if lt IE 8]><link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.ie7.css" type="text/css" /><![endif]-->');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.'.$this->bpTheme.'.css', 'text/css', null, array('title'=>'boxplus-'.$this->bpTheme));
			
			if (file_exists(JPATH_BASE.DS.'components'.DS.'com_phocagallery'.DS.'assets'.DS.'js'.DS.'boxplus'.DS.'css'.DS.'boxplus.'.$this->bpTheme)) {  // use IE-specific stylesheet only if it exists
				$this->addCustomTag('<!--[if lt IE 9]><link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocagallery/assets/boxplus/css/boxplus.'.$this->bpTheme.'.ie8.css" type="text/css" title="boxplus-'.$this->bpTheme.'" /><![endif]-->');
			}
			
			$document->addScriptDeclaration('window.addEvent("domready", function () {');
			
			if ($method == 10) {
				// Image
				$document->addScriptDeclaration('new boxplus($$("a.phocagallerycboxplus'.$this->extension.'"),{"theme":"'.$this->bpTheme.'","autocenter":'.(int)$this->bpBautocenter.',"autofit":'.(int)$this->bpAutofit.',"slideshow":'.(int)$this->bpSlideshow.',"loop":'.(int)$this->bpLoop.',"captions":"'.$this->bpCaptions.'","thumbs":"'.$this->bpThumbs.'","width":'.(int)$this->popupWidth.',"height":'.(int)$this->popupHeight.',"duration":'.(int)$this->bpDuration.',"transition":"'.$this->bpTransition.'","contextmenu":'.(int)$this->bpContextmenu.', phocamethod:1});');
				
				// Icon
				$document->addScriptDeclaration('new boxplus($$("a.phocagallerycboxplusi'.$this->extension.'"),{"theme":"'.$this->bpTheme.'","autocenter":'.(int)$this->bpBautocenter.',"autofit":'.(int)$this->bpAutofit.',"slideshow":'.(int)$this->bpSlideshow.',"loop":'.(int)$this->bpLoop.',"captions":"'.$this->bpCaptions.'","thumbs":"hide","width":'.(int)$this->popupWidth.',"height":'.(int)$this->popupHeight.',"duration":'.(int)$this->bpDuration.',"transition":"'.$this->bpTransition.'","contextmenu":'.(int)$this->bpContextmenu.', phocamethod:1});');
				
			} else {
				// Image
				$document->addScriptDeclaration('new boxplus($$("a.phocagallerycboxplus'.$this->extension.'"),{"theme":"'.$this->bpTheme.'","autocenter":'.(int)$this->bpBautocenter.',"autofit": false,"slideshow": false,"loop":false,"captions":"none","thumbs":"hide","width":'.(int)$this->popupWidth.',"height":'.(int)$this->popupHeight.',"duration":0,"transition":"linear","contextmenu":false, phocamethod:2});');
			
				// Icon
				$document->addScriptDeclaration('new boxplus($$("a.phocagallerycboxplusi'.$this->extension.'"),{"theme":"'.$this->bpTheme.'","autocenter":'.(int)$this->bpBautocenter.',"autofit": false,"slideshow": false,"loop":false,"captions":"none","thumbs":"hide","width":'.(int)$this->popupWidth.',"height":'.(int)$this->popupHeight.',"duration":0,"transition":"linear","contextmenu":false, phocamethod:2});');
			}
			
			// Other (Map, Info, Download)
			$document->addScriptDeclaration('new boxplus($$("a.phocagallerycboxpluso'.$this->extension.'"),{"theme":"'.$this->bpTheme.'","autocenter":'.(int)$this->bpBautocenter.',"autofit": false,"slideshow": false,"loop":false,"captions":"none","thumbs":"hide","width":'.(int)$this->popupWidth.',"height":'.(int)$this->popupHeight.',"duration":0,"transition":"linear","contextmenu":false, phocamethod:2});');
			
			$document->addScriptDeclaration('});');
			
			default:
			break;
		}
	}
	
	
	public function getB1() {
		return $this->b1;
	}
	public function getB2() {
		return $this->b2;
	}
	public function getB3() {
		
		return $this->b3;
	}
	
	public function renderHighslideJSAll() {
		$o = '<script type="text/javascript">'
		.'//<![CDATA[' ."\n"
		.' hs.graphicsDir = \''.JURI::base(true).'/components/com_phocagallery/assets/highslide/graphics/\';'
		.'//]]>'."\n"
		.'</script>'."\n";
		return $o;
	}
	
	/*
	*	@return		code snippet to insert into the onClick javascript routine of an image calling highslide
	*	@author	modified by Kay Messerschmidt
	*	@param	integer		slideShowGroup		if there are several plugin instances creating slideshows at one single web-page this enables the group support of highslide
	*	@see http://highslide.com/ref/hs.slideshowGroup and http://highslide.com/ref/hs.addslideShow
	*/

	public function renderHighslideJSImage($type, $highslide_class = '',$highslide_outline_type = 'rounded-white', $highslide_opacity = 0, $highslide_fullimg = 0, $slideShowGroup = 0) {
	
		if ($type == 'li')  {
			$typeOutput = 'groupLI';
		} else if ($type == 'pm')  {
			$typeOutput = 'groupPM';
		} else if ($type == 'ri' ){
			$typeOutput = 'groupRI';
		} else if ($type == 'pl' ){
			$typeOutput = 'groupPl';
		} else {
			$typeOutput = 'groupC';
		}

		$code = 'return hs.expand(this, {'
		//.'autoplay:\'true\','
		.' slideshowGroup: \''.$typeOutput.$slideShowGroup.'\', ';
		if ($highslide_fullimg  == 1) {
			$code .= ' src: \'[phocahsfullimg]\',';
		}

		$code .= ' wrapperClassName: \''.$highslide_class.'\',';
		if ($highslide_outline_type != 'none') {
			$code .= ' outlineType : \''.$highslide_outline_type.'\',';
		}
		$code .= ' dimmingOpacity: '.$highslide_opacity.', '
		.' align : \'center\', '
		.' transitions : [\'expand\', \'crossfade\'],'
		.' fadeInOut: true'
		.' });';
		return $code;
	}
	
	/*
	*	@author	modified by Kay Messerschmidt
	*	@param	integer		slideShowGroup		if there are several plugin instances creating slideshows at one single web-page this enables the group support of highslide
	*	@see		http://highslide.com/ref/hs.slideshowGroup and http://highslide.com/ref/hs.addslideShow
	*/
	public function renderHighslideJS($type, $front_modal_box_width, $front_modal_box_height, $slideshow = 0, $highslide_class = '',$highslide_outline_type = 'rounded-white', $highslide_opacity = 0, $highslide_close_button = 0, $slideShowGroup = 0) {	
		
		if ($type == 'li')  {
			$typeOutput = 'groupLI';
			$varImage	= 'phocaImageLI';
			$varZoom	= 'phocaZoomLI';
		} else if ($type == 'pm')  {
			$typeOutput = 'groupPM';
			$varImage	= 'phocaImagePM';
			$varZoom	= 'phocaZoomPM';
		} else if ($type == 'ri' ){
			$typeOutput = 'groupRI';
			$varImage	= 'phocaImageRI';
			$varZoom	= 'phocaZoomRI';
		} else if ($type == 'pl' ){
			$typeOutput = 'groupPl';
			$varImage	= 'phocaImagePl';
			$varZoom	= 'phocaZoomPl';
		} else {
			$typeOutput = 'groupC';
			$varImage	= 'phocaImage';
			$varZoom	= 'phocaZoom';
		}
		
		$tag = '<script type="text/javascript">'
		.'//<![CDATA[' ."\n"
		.' var '.$varZoom.' = { '."\n"
		.' objectLoadTime : \'after\',';
		if ($highslide_outline_type != 'none') {
			$tag .= ' outlineType : \''.$highslide_outline_type.'\',';
		}
		$tag .= ' wrapperClassName: \''.$highslide_class.'\','
		.' outlineWhileAnimating : true,'
		.' enableKeyListener : false,'
		.' minWidth : '.$front_modal_box_width.','
		.' minHeight : '.$front_modal_box_height.','
		.' dimmingOpacity: '.$highslide_opacity.', '
		.' fadeInOut : true,'
		.' contentId: \'detail\','
		.' objectType: \'iframe\','
		.' objectWidth: '.$front_modal_box_width.','
		.' objectHeight: '.$front_modal_box_height.''
		.' };';

		if ($highslide_close_button == 1) {
			$tag .= 'hs.registerOverlay({
			html: \'<div class=\u0022closebutton\u0022 onclick=\u0022return hs.close(this)\u0022 title=\u0022'. JText::_( 'COM_PHOCAGALLERY_CLOSE_WINDOW' ).'\u0022></div>\',
			position: \'top right\',
			fade: 2
		});';
		}

		switch ($slideshow) {
			case 1:
				$tag .= ' if (hs.addSlideshow) hs.addSlideshow({ '."\n"
				.'  slideshowGroup: \''.$typeOutput.$slideShowGroup.'\','."\n"
				.'  interval: 5000,'."\n"
				.'  repeat: false,'."\n"
				.'  useControls: true,'."\n"
				.'  fixedControls: true,'."\n"
				.'    overlayOptions: {'."\n"
				.'      opacity: 1,'."\n"
				.'     	position: \'top center\','."\n"
				.'     	hideOnMouseOut: true'."\n"	
				.'	  }'."\n"
				.' });'."\n";
			break;
			
			case 2:
				$tag .=' if (hs.addSlideshow) hs.addSlideshow({'."\n"
				.'slideshowGroup: \''.$typeOutput.$slideShowGroup.'\','."\n"
				.'interval: 5000,'."\n"
				.'repeat: false,'."\n"
				.'useControls: true,'."\n"
				.'fixedControls: \'true\','."\n"
				.'overlayOptions: {'."\n"
				.'  className: \'text-controls\','."\n"
				.'	position: \'bottom center\','."\n"
				.'	relativeTo: \'viewport\','."\n"
				.'	offsetY: -60'."\n"
				.'},'."\n"
				.'thumbstrip: {'."\n"
				.'	position: \'bottom center\','."\n"
				.'	mode: \'horizontal\','."\n"
				.'	relativeTo: \'viewport\''."\n"
				.'}'."\n"
				.'});'."\n";

			case 0:
			Default:
			break;
		}

		$tag .= '//]]>'."\n"
		.'</script>'."\n";
		  
		return $tag;
	}
	
	/* Experimenta settings
	* .'zIndex: 10,'
	* to: .'zIndex: 1000,'
	*/
	public function renderJakJs($slideshowDelay = 5, $orientation = 'None', $name = 'optgjaks') {
		$js  = "\t". '<script language="javascript" type="text/javascript">'."\n"
		.'var '.$name.' = {'
		.'galleryClassName: \'lightBox\','
		.'zIndex: 1000,'
		.'useShadow: true,'
		.'imagePath: \''.JURI::base(true).'/components/com_phocagallery/assets/jak/img/shadow-\','
		.'usePageShader: true,'
		.'components: {';
		
		if ($orientation == 'none') {
			$js .= 'strip: SZN.LightBox.Strip,';
		} else {
			$js .= 'strip: SZN.LightBox.Strip.Scrollable,';
		}
 		$js .=	'navigation: SZN.LightBox.Navigation.Basic,
 			anchorage: SZN.LightBox.Anchorage.Fixed,
 			main: SZN.LightBox.Main.CenteredScaled,
 			description: SZN.LightBox.Description.Basic,
			transition: SZN.LightBox.Transition.Fade,
 			others: [
 				{name: \'slideshow\', part: SZN.SlideShow, setting: {duration: '.(int)$slideshowDelay.', autoplay: false}}
			 ]
		 },';
		
		if ($orientation != 'none') {
			$js .= 'stripOpt : {
				activeBorder : \'outer\',
				orientation : \''.$orientation.'\'
			},';
		}
		
		$js .= 'navigationOpt : {
			continuous: false,
			showDisabled: true
		},'
		
		.'transitionOpt: {
			interval: 500,
			overlap: 0.5
		}'
		.'}'
		. '</script>' . "\n";
		return $js;
	}
}
?>