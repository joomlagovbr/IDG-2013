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

class PhocaGalleryRenderFront
{
	// hotnew
	public static function getOverImageIcons($date, $hits) {
		$app	= JFactory::getApplication();
		$params = $app->getParams();
		$new	= $params->get( 'display_new', 0 );
		$hot	= $params->get( 'display_hot', 0 );
		
		
		$output = '';
		if ($new == 0) {
			$output .= '';
		} else {
			$dateAdded 	= strtotime($date, time());
			$dateToday 	= time();
			$dateExists = $dateToday - $dateAdded;
			$dateNew	= (int)$new * 24 * 60 * 60;
			if ($dateExists < $dateNew) {
				$output .= JHTML::_('image', 'media/com_phocagallery/images/icon-new.png', '', array('class' => 'pg-img-ovr1'));
			}
		}
		if ($hot == 0) {
			$output .='';
		} else {
			if ((int)$hot <= $hits) {
				if ($output == '') {
					$output .= JHTML::_('image', 'media/com_phocagallery/images/icon-hot.png', '', array('class' => 'pg-img-ovr1'));
				} else {
					$output .= JHTML::_('image', 'media/com_phocagallery/images/icon-hot.png', '', array('class' => 'pg-img-ovr2'));
				}
			}
		}
		return $output;
	}
	
	public static function renderCommentJS($chars) {
		
		$tag = "<script type=\"text/javascript\">" 
		."function countChars() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfc 			= document.getElementById('phocagallery-comments-form');" . "\n"
		."var charIn		= pfc.phocagallerycommentseditor.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED')."');" . "\n"
		."   pfc.phocagallerycommentseditor.value = pfc.phocagallerycommentseditor.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfc.phocagallerycommentscountin.value	= charIn;" . "\n"
		."pfc.phocagallerycommentscountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		
		."function checkCommentsForm() {" . "\n"
		."   var pfc = document.getElementById('phocagallery-comments-form');" . "\n"
		."   if ( pfc.phocagallerycommentstitle.value == '' ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_TITLE' )."');". "\n"
		."     return false;" . "\n"
		."   } else if ( pfc.phocagallerycommentseditor.value == '' ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_COMMENT' )."');". "\n"
		."     return false;" . "\n"
		."   } else {". "\n"
		."     return true;" . "\n"
		."   }" . "\n"
		."}". "\n"
		."</script>";
		
		return $tag;
	}
	
	public static function renderCategoryCSS($font_color, $background_color, $border_color, $imageBgCSS,$imageBgCSSIE, $border_color_hover, $background_color_hover, $ol_fg_color, $ol_bg_color, $ol_tf_color, $ol_cf_color, $margin_box, $padding_box, $opacity = 0.8) {
		
		$opacityPer = (float)$opacity * 100;
		
		$tag = "<style type=\"text/css\">\n"
		." #phocagallery .pg-name {color: $font_color ;}\n"
		." .phocagallery-box-file {background: $background_color ; border:1px solid $border_color;margin: ".$margin_box."px;padding: ".$padding_box."px; }\n"
		." .phocagallery-box-file-first { $imageBgCSS }\n"
		." .phocagallery-box-file:hover, .phocagallery-box-file.hover {border:1px solid $border_color_hover ; background: $background_color_hover ;}\n"
		/*
		." .ol-foreground { background-color: $ol_fg_color ;}\n"
		." .ol-background { background-color: $ol_bg_color ;}\n"
		." .ol-textfont { font-family: Arial, sans-serif; font-size: 10px; color: $ol_tf_color ;}"
		." .ol-captionfont {font-family: Arial, sans-serif; font-size: 12px; color: $ol_cf_color ; font-weight: bold;}"*/
		
		. ".bgPhocaClass{
			background:".$ol_bg_color.";
			filter:alpha(opacity=".$opacityPer.");
			opacity: ".$opacity.";
			-moz-opacity:".$opacity.";
			z-index:1000;
			}
			.fgPhocaClass{
			background:".$ol_fg_color.";
			filter:alpha(opacity=100);
			opacity: 1;
			-moz-opacity:1;
			z-index:1000;
			}
			.fontPhocaClass{
			color:".$ol_tf_color.";
			z-index:1001;
			}
			.capfontPhocaClass, .capfontclosePhocaClass{
			color:".$ol_cf_color.";
			font-weight:bold;
			z-index:1001;
			}"
		." </style>\n"
		.'<!--[if lt IE 8]>' . "\n" 
		. '<style type="text/css">' . "\n"
		." .phocagallery-box-file-first { $imageBgCSSIE }\n"
		.' </style>'. "\n" .'<![endif]-->';
		
		return $tag;
	}
	
	public static function renderIeHover() {
		
		$tag = '<!--[if lt IE 7]>' . "\n" . '<style type="text/css">' . "\n"
		.'.phocagallery-box-file{' . "\n"
		.' background-color: expression(isNaN(this.js)?(this.js=1, '
		.'this.onmouseover=new Function("this.className+=\' hover\';"), ' ."\n"
		.'this.onmouseout=new Function("this.className=this.className.replace(\' hover\',\'\');")):false););
}' . "\n"
		.' </style>'. "\n" .'<![endif]-->';
		
		return $tag;
		
	}
	
	public static function renderPicLens($categoryId) {
		$tag ="<link id=\"phocagallerypiclens\" rel=\"alternate\" href=\""
		.JURI::base(true)."/images/phocagallery/"
		.$categoryId.".rss\" type=\"application/rss+xml\" title=\"\" />"
	    ."<script type=\"text/javascript\" src=\"http://lite.piclens.com/current/piclens.js\"></script>"
		
		."<style type=\"text/css\">\n"
		." .mbf-item { display: none; }\n"
		." #phocagallery .mbf-item { display: none; }\n"
		." </style>\n";
		return $tag;
	
	}
	
	public static function renderOnUploadJS() {
		
		$tag = "<script type=\"text/javascript\"> \n"
		. "function OnUploadSubmitUserPG() { \n"
		. "document.getElementById('loading-label-user').style.display='block'; \n" 
		. "return true; \n"
		. "} \n"
		. "function OnUploadSubmitPG(idLoad) { \n"
		."  if ( document.getElementById('filter_catid_image').value < 1 ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY' )."');". "\n"
		."     return false;" . "\n"
		. "} \n"
		. "document.getElementById(idLoad).style.display='block'; \n" 
		. "return true; \n"
		. "} \n"
		. "</script>";
		return $tag;
	}
	
	public static function renderOnUploadCategoryJS() {
		
		$tag = "<script type=\"text/javascript\"> \n"
		. "function OnUploadSubmitCategoryPG(idLoad) { \n"
		. "document.getElementById(idLoad).style.display='block'; \n" 
		. "return true; \n"
		. "} \n"
		. "</script>";
		return $tag;
	}
	
	public static function renderDescriptionUploadJS($chars) {
		
		$tag = "<script type=\"text/javascript\"> \n"
		//. "function OnUploadSubmit() { \n"
		//. "document.getElementById('loading-label').style.display='block'; \n" 
		//. "return true; \n"
		//. "} \n"
		."function countCharsUpload(id) {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfu 			= document.getElementById(id);" . "\n"
		."var charIn		= pfu.phocagalleryuploaddescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED')."');" . "\n"
		."   pfu.phocagalleryuploaddescription.value = pfu.phocagalleryuploaddescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfu.phocagalleryuploadcountin.value	= charIn;" . "\n"
		."pfu.phocagalleryuploadcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		. "</script>";
		
		return $tag;
	}
	
	public static function renderDescriptionCreateCatJS($chars) {
		
		$tag = "<script type=\"text/javascript\"> \n"
		."function countCharsCreateCat() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfcc 			= document.getElementById('phocagallery-create-cat-form');" . "\n"
		."var charIn		= pfcc.phocagallerycreatecatdescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED')."');" . "\n"
		."   pfcc.phocagallerycreatecatdescription.value = pfcc.phocagallerycreatecatdescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfcc.phocagallerycreatecatcountin.value	= charIn;" . "\n"
		."pfcc.phocagallerycreatecatcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		
		."function checkCreateCatForm() {" . "\n"
		."   var pfcc = document.getElementById('phocagallery-create-cat-form');" . "\n"
		."   if ( pfcc.categoryname.value == '' ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_TITLE' )."');". "\n"
		."     return false;" . "\n"
		//."   } else if ( pfcc.phocagallerycreatecatdescription.value == '' ) {". "\n"
		//."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_DESCRIPTION' )."');". "\n"
		//."     return false;" . "\n"
		."   } else {". "\n"
		."     return true;" . "\n"
		."   }" . "\n"
		."}". "\n"
		. "</script>";
		
		return $tag;
	}
	
	public static function renderDescriptionCreateSubCatJS($chars) {
		
		$tag = "<script type=\"text/javascript\"> \n"
		."function countCharsCreateSubCat() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfcc 			= document.getElementById('phocagallery-create-subcat-form');" . "\n"
		."var charIn		= pfcc.phocagallerycreatesubcatdescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('COM_PHOCAGALLERY_MAX_LIMIT_CHARS_REACHED')."');" . "\n"
		."   pfcc.phocagallerycreatesubcatdescription.value = pfcc.phocagallerycreatesubcatdescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfcc.phocagallerycreatesubcatcountin.value	= charIn;" . "\n"
		."pfcc.phocagallerycreatesubcatcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		
		."function checkCreateSubCatForm() {" . "\n"
		."   var pfcc = document.getElementById('phocagallery-create-subcat-form');" . "\n"
		."   if ( pfcc.subcategoryname.value == '' ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_TITLE' )."');". "\n"
		."     return false;" . "\n"
		//."   } else if ( pfcc.phocagallerycreatecatdescription.value == '' ) {". "\n"
		//."	   alert('". JText::_( 'COM_PHOCAGALLERY_ENTER_DESCRIPTION' )."');". "\n"
		//."     return false;" . "\n"
		."   } else if ( document.getElementById('filter_catid_subcat').value < 1 ) {". "\n"
		."	   alert('". JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY' )."');". "\n"
		."     return false;" . "\n"
		
		
		."   } else {". "\n"
		."     return true;" . "\n"
		."   }" . "\n"
		."}". "\n"
		. "</script>";
		
		return $tag;
	}
	
	public static function renderHighslideJSAll() {
		
		$tag = '<script type="text/javascript">'
		.'//<![CDATA[' ."\n"
		.' hs.graphicsDir = \''.JURI::base(true).'/components/com_phocagallery/assets/highslide/graphics/\';'
		.'//]]>'."\n"
		.'</script>'."\n";
		
		return $tag;
	}
	
	
	
	
	/**
	 * Method to get the Javascript for switching images
	 * @param string $waitImage Image which will be displayed as while loading
	 * @return string Switch image javascript
	 */
	public static function switchImage($waitImage) {	
		$js  = "\t". '<script language="javascript" type="text/javascript">'."\n".'var pcid = 0;'."\n"
		     . 'var waitImage = new Image();' . "\n"
			 . 'waitImage.src = \''.$waitImage.'\';' . "\n";
			/*
			if ((int)$customWidth > 0) {
				$js .= 'waitImage.width = '.$customWidth.';' . "\n";
			}
			if ((int)$customHeight > 0) {
				$js .= 'waitImage.height = '.$customHeight.';' . "\n";
			}*/
			 $js.= 'function PhocaGallerySwitchImage(imageElementId, imageSrcUrl, width, height)' . "\n"
			 . '{ ' . "\n"
			 . "\t".'var imageElement 	= document.getElementById(imageElementId);'
			 . "\t".'var imageElement2 	= document.getElementById(imageElementId);'
			 . "\t".'if (imageElement && imageElement.src)' . "\n"
			 . "\t".'{' . "\n"
			 . "\t"."\t".'imageElement.src = \'\';' . "\n"
			 . "\t".'}'. "\n"
			 . "\t".'if (imageElement2 && imageElement2.src)' . "\n"
			 
			 . "\t"."\t".'imageElement2.src = imageSrcUrl;' . "\n"
			 . "\t"."\t".'if (width > 0) {imageElement2.width = width;}' . "\n"
			 . "\t"."\t".'if (height > 0) {imageElement2.height = height;}' . "\n"
			 
			 . '}'. "\n"
			 . 'function _PhocaGalleryVoid(){}'. "\n"
			 . '</script>' . "\n";
			
		return $js;
	}
	
	
	public static function userTabOrdering() {	
		$js  = "\t". '<script language="javascript" type="text/javascript">'."\n"
			 . 'function tableOrdering( order, dir, task )' . "\n"
			 . '{ ' . "\n"
			 . "\t".'if (task == "subcategory") {'. "\n"
			 . "\t"."\t".'var form = document.phocagallerysubcatform;' . "\n"
			 . "\t".'form.filter_order_subcat.value 	= order;' . "\n"
			 . "\t".'form.filter_order_Dir_subcat.value	= dir;' . "\n"
			 . "\t".'document.phocagallerysubcatform.submit();' . "\n"
			 . "\t".'} else {'. "\n"
			 . "\t"."\t".'var form = document.phocagalleryimageform;' . "\n"
			 . "\t".'form.filter_order_image.value 		= order;' . "\n"
			 . "\t".'form.filter_order_Dir_image.value	= dir;' . "\n"
			 . "\t".'document.phocagalleryimageform.submit();' . "\n"
			 . "\t".'}'. "\n"
			 . '}'. "\n"
			 . '</script>' . "\n";
			
		return $js;
	}
	
	public static function saveOrderUserJS() {
		$js  = '<script language="javascript" type="text/javascript">'."\n"
			.'function saveordersubcat(task){'. "\n"
			."\t".'document.phocagallerysubcatform.task.value=\'saveordersubcat\';'. "\n"
			."\t".'document.phocagallerysubcatform.submit();'. "\n"
			.'}'
			.'function saveorderimage(task){'. "\n"
			."\t".'document.phocagalleryimageform.task.value=\'saveorderimage\';'. "\n"
			."\t".'document.phocagalleryimageform.submit();'. "\n"
			.'}'
			.'</script>' . "\n";
		return $js;
	}
	
	public static function getAltValue($altValue = 0, $title = '', $description = '', $metaDesc = '') {
		$output = '';
		switch ($altValue) {
			case 1:
				$output = $title;
			break;
			case 2:
				$output = strip_tags($description);
			break;
			case 3: 
				$output = $title;
				if ($description != '') {
					$output .= ' - '. strip_tags($description);
				}
			break;
			case 4:
				$output = strip_tags($metaDesc);
			break;
			case 5:
				if ($title != '') {
					$output = $title;
				} else if ($description != '') {
					$output = strip_tags($description);
				} else {
					$output = strip_tags($metaDesc);
				}
			break;
			case 6:
				if ($description != '') {
					$output = strip_tags($description);
				} else if ($title != '') {
					$output = $title;
				} else {
					$output = strip_tags($metaDesc);
				}
			break;
			case 7:
				if ($description != '') {
					$output = strip_tags($description);
				} else if ($metaDesc != '') {
					$output = strip_tags($metaDesc);
				} else {
					$output = $title;
				}
			break;
			case 8:
				if ($metaDesc != '') {
					$output = strip_tags($metaDesc);
				} else if ($title != '') {
					$output = $title;
				} else {
					$output = strip_tags($description);
				}
			break;
			case 9:
				if ($metaDesc != '') {
					$output = strip_tags($metaDesc);
				} else if ($description != '') { 
					$output = strip_tags($description);
				} else {
					$output = $title;
				}
			break;
			
			case 0:
			Default:
				$output = '';
			break;
		}
		//return htmlspecialchars( addslashes($output));
		return htmlspecialchars( $output);
	}
	
	public static function renderCloseReloadDetail($detailWindow, $type = 0) {
		$o = array();
		
		if ($detailWindow == 1) {
			$output['detailwindowclose']	= 'window.close();';
			$output['detailwindowreload']	= 'window.location.reload(true);';
			$closeLink = '<div><a href="javascript:void(0);" onclick="'.$output['detailwindowclose'].'" >'.JText::_('COM_PHOCAGALLERY_CLOSE_WINDOW').'</a></div>';
		// Highslide
		} else if ($detailWindow == 4 || $detailWindow == 5) {
			$output['detailwindowclose']	= 'return false;';
			$output['detailwindowreload']	= 'window.location.reload(true);';
			$closeLink = '<div><a href="javascript:void(0);" onclick="'.$output['detailwindowclose'].'" >'.JText::_('COM_PHOCAGALLERY_CLOSE_WINDOW').'</a></div>';
		// No Popup
		} else if ($detailWindow == 7) {
			$output['detailwindowclose']	= '';
			$output['detailwindowreload']	= '';
			// Should not happen as it should be reloaded to login page
			$closeLink = '<div><a href="'.JURI::base(true).'" >'.JText::_('COM_PHOCAGALLERY_MAIN_SITE').'</a></div>';
		// Magnific iframe
		} else if ($detailWindow == 11) {
			$output['detailwindowclose']	= '';
			$output['detailwindowreload']	= '';
			$closeLink = '<div><a href="javascript:void(0);" class="mfp-close" >'.JText::_('COM_PHOCAGALLERY_CLOSE_WINDOW').'</a></div>';
		// Modal Box
		} else {
			//$this->tmpl['detailwindowclose']	= 'window.parent.document.getElementById(\'sbox-window\').close();';
			$output['detailwindowclose']	= 'window.parent.SqueezeBox.close();';
			$output['detailwindowreload']	= 'window.location.reload(true);';
			$closeLink = '<div><a href="javascript:void(0);" onclick="'.$output['detailwindowclose'].'" >'.JText::_('COM_PHOCAGALLERY_CLOSE_WINDOW').'</a></div>';
		} 
		
		$o[] = '<div style="font-family:sans-serif">';
		if ($type == 0) {
			$o[] = '<div>'.JText::_('COM_PHOCAGALLERY_NOT_AUTHORISED_ACTION').' '.JText::_('COM_PHOCAGALLERY_PLEASE_LOGIN').'.</div>';
			$o[] = '<div>&nbsp;</div>';
		}
		$o[] = $closeLink;
		$o[] = '</div>';
		
		$output['html'] = implode('', $o);
		
		return $output;
		
	}
	
	public static function renderInfo() {
		return '<div style="text-align: center; color: rgb(211, 211, 211);">Powe'
				. 'red by <a href="http://www.ph'
				. 'oca.cz" style="text-decoration: none;" target="_blank" title="Phoc'
				. 'a.cz">Phoca</a> <a href="https://www.phoca.cz/phocaga'
				. 'llery" style="text-decoration: none;" target="_blank" title="Phoca Gal'
				. 'lery">Gall'
				. 'ery</a></div>';
	}
	
	public static function renderFeedIcon($type = 'categories', $paramsIcons = true, $catid = 0, $catidAlias = '') {
		
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		$df 		= $paramsC->get('display_feed', 1);

		if ($type == 'categories' && $df != 1 && $df != 2) {
			return '';
		}
		if ($type == 'category' && $df != 1 && $df != 3) {
			return '';
		}
	
		$url	= PhocaGalleryRoute::getFeedRoute($type, $catid, $catidAlias);
		if ($paramsIcons) {
			$text = JHTML::_('image', 'media/com_phocagallery/images/icon-feed.png', JText::_('COM_PHOCAGALLERY_RSS'));
		} else {
			$text = JText::_('COM_PHOCAGALLERY_RSS');
		}
		
		$output = '<a href="'.JRoute::_($url).'" title="'.JText::_('COM_PHOCAGALLERY_RSS').'">'. $text . '</a>';
		
		
		return $output;	
	}
	/*
	function correctRender() {
		if (class_exists('plgSystemRedact')) {
			echo "Phoca Gallery doesn't work in case Redact plugin is enabled. Please disable this plugin to run Phoca Gallery";exit;
			$db =JFactory::getDBO();
			$query = 'SELECT a.params AS params'
					.' FROM #__plugins AS a'
					.' WHERE a.element = \'redact\''
					.' AND a.folder = \'system\''
					.' AND a.published = 1';
			$db->setQuery($query, 0, 1);
			$params = $db->loadObject();
			if(isset($params->params) && $params->params != '') {
				$params->params = str_replace('phoca.cz', 'phoca_cz', $params->params);
				$params->params = str_replace('phoca\.cz', 'phoca_cz', $params->params);
				if ($params->params != '') {
					$query = 'UPDATE #__plugins'
							.' SET params = \''.$params->params.'\''
							.' WHERE element = \'redact\''
							.' AND folder = \'system\'';
					$db->setQuery($query);
					$db->query();
				}
			}
		
		}
		
		if (class_exists('plgSystemReReplacer')) {
			echo "Phoca Gallery doesn't work in case ReReplacer plugin is enabled. Please disable this plugin to run Phoca Gallery";exit;
			/*$db =JFactory::getDBO();
			$query = 'SELECT a.id, a.search'
					.' FROM #__rereplacer AS a'
					.' WHERE (a.search LIKE \'%phoca.cz%\''
					.' OR a.search LIKE \'%phoca\\\\\\\\.cz%\')'
					.' AND a.published = 1';
			$db->setQuery($query);
			$search = $db->loadObjectList();
			
			if(isset($search) && count($search)) {
				
				foreach ($search as $value) {
					if (isset($value->search) && $value->search != '' && isset($value->id) && $value->id > 0) {
						$value->search = str_replace('phoca.cz', 'phoca_cz', $value->search);
						$value->search = str_replace('phoca\.cz', 'phoca_cz', $value->search);
						if ($value->search != '') {
							$query = 'UPDATE #__rereplacer'
							.' SET search = \''.$value->search.'\''
							.' WHERE id = '.(int)$value->id;
							$db->setQuery($query);
							$db->query();
						}
					}
				}
			}
		}
	
	}*/
	
	public static function renderAAttribute($detailWindow, $buttonOptions, $lingOrig, $hSOnClick, $hsOnClick2, $linkNr, $catAlias) {
	
		if ($detailWindow == 1) {
			return ' onclick="'. $buttonOptions.'"';
		} else if ($detailWindow == 4 || $detailWindow == 5) {
			$hSOC = str_replace('[phocahsfullimg]', $lingOrig, $hSOnClick);
			return ' onclick="'. $hSOC.'"';
		} else if ($detailWindow == 6 ) {
			return ' onclick="gjaks.show('.$linkNr.'); return false;"';
		} else if ($detailWindow == 7 ) {
			return '';
		} else if ($detailWindow == 8) {
			return ' rel="lightbox-'.$catAlias.'" ';
		} else if ($detailWindow == 14) {
			return $buttonOptions;
		} else {
			return ' rel="'.$buttonOptions.'"';
		}
		
		return '';
	}
	
	public static function renderAAttributeTitle($detailWindow, $buttonOptions, $lingOrig, $hsOnClick, $hsOnClick2, $linkNr, $catAlias) {
			
		if ($detailWindow == 1) {
			return ' onclick="'. $buttonOptions.'"';
		} else if ($detailWindow == 2) {
			return ' rel="'. $buttonOptions.'"';
		} else if ($detailWindow == 4 ) {
			return ' onclick="'. $hsOnClick.'"';
		} else if ($detailWindow == 5 ) {
			return ' onclick="'. $hsOnClick2.'"';
		} else if ($detailWindow == 6) {
			return ' onclick="gjaks.show('.$linkNr.'); return false;"';
		} else if ($detailWindow == 7 ) {
			return '';
		} else if ($detailWindow == 8) {
			return ' rel="lightbox-'.$catAlias.'2" ';
		} else if ($detailWindow == 14) {
			return ' rel="'.$buttonOptions.'"';
		} else {
			return ' rel="'.$buttonOptions.'"';
		}
		
		return '';
	}
	
	public static function renderAAttributeStat($detailWindow, $buttonOptions, $lingOrig, $hSOnClick, $hsOnClick2, $linkNr, $catAlias, $suffix) {
			
		if ($detailWindow == 1) {
			return ' onclick="'. $buttonOptions.'"';
		} else if ($detailWindow == 2) {
			return ' rel="'. $buttonOptions.'"';
		} else if ($detailWindow == 4) {
			return ' onclick="'. $hSOnClick.'"';
		} else if ($detailWindow == 5) {
			return ' onclick="'. $hSOnClick2.'"';
		} else if ($detailWindow == 8) {
			return ' rel="lightbox-'.$catAlias.'-'.$suffix.'" ';
		} else if ($detailWindow == 14) {
			return ' '.$buttonOptions;
		} else {
			return ' rel="'.$buttonOptions.'"';
		}
		
		return '';
	}
	
	public static function renderAAttributeOther($detailWindow, $buttonOptionsOther, $hSOnClick, $hSOnClick2) {
	
		if ($detailWindow == 1) {
			return ' onclick="'. $buttonOptionsOther.'"';
		} else if ($detailWindow == 4) {
			return ' onclick="'. $hSOnClick.'"';
		} else if ($detailWindow == 5 ) {
			return ' onclick="'. $hSOnClick2.'"';
		} else if ($detailWindow == 7 ) {
			return '';
		} else if ($detailWindow == 14) {
			return ' rel="'.$buttonOptionsOther.'"';
		} else {
			return ' rel="'.$buttonOptionsOther.'"';
		}
		return '';
	}
	
	public static function renderASwitch($switchW, $switchH, $switchFixedSize, $extWSwitch, $extHSwitch, $extL, $linkThumbPath) {
	
		if ($extL != '') {
			// Picasa
			if ((int)$switchW > 0 && (int)$switchH > 0 && $switchFixedSize == 1) {
				// Custom Size
				return ' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. $extL.'\', '.$switchW.', '.$switchH.');" ';
			} else {
				// Picasa Size
				$correctImageResL = PhocaGalleryPicasa::correctSizeWithRate($extWSwitch, $extHSwitch, $switchW, $switchH);
				return ' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. $extL.'\', '.$correctImageResL['width'].', '.$correctImageResL['height'].');" '; 
				// onmouseout="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''.$extL.'\');"
			}
		} else {
			$switchImg = str_replace('phoca_thumb_m_','phoca_thumb_l_',JURI::base(true).'/'. $linkThumbPath);
			if ((int)$switchW > 0 && (int)$switchH > 0 && $switchFixedSize == 1) {
				return ' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. $switchImg.'\', '.$switchW.', '.$switchH.');" ';
			} else {
				return ' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. $switchImg.'\');" ';
				// onmouseout="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''.$switchImg.'\');"
			}
		}
		
		return '';
	}
	
	public static function renderImageClass($image) {
	
		$isFolder 	= strpos($image, 'com_phocagallery/assets/images/icon-folder');
		$isUp 		= strpos($image, 'com_phocagallery/assets/images/icon-up');
		if ($isFolder !== false) {
			return 'pg-cat-folder';
		} else if ($isUp !== false) {
			return 'pg-cat-up';
		} else {
			return 'pg-cat-image';
		}
	}
	
	public static function displayCustomCSS($customCss) {
		if ($customCss != ''){
			$customCss = str_replace('background: url(images/', 'background: url('.JURI::base(true).'/media/com_phocagallery/images/', $customCss);
			$document	= JFactory::getDocument();
			$document->addCustomTag( "\n <style type=\"text/css\"> \n" 
				.strip_tags($customCss)
				."\n </style> \n");
		}
	}
	
	public static function renderAllCSS( $noBootStrap = 0) {
		$app	= JFactory::getApplication();
		$itemid	= $app->input->get('Itemid', 0, 'int');
		$db 	= JFactory::getDBO();
		$query = 'SELECT a.filename as filename, a.type as type, a.menulink as menulink'
				.' FROM #__phocagallery_styles AS a'
				.' WHERE a.published = 1'
			    .' ORDER BY a.type, a.ordering ASC';
		$db->setQuery($query);
		$filenames = $db->loadObjectList();
		if (!empty($filenames)) {
			foreach ($filenames as $fk => $fv) {
				
				if($noBootStrap == 1) {
					$pos = strpos($fv->filename, 'bootstrap');
					if ($pos === false) {} else {
						continue;
					}
				}
				$path = PhocaGalleryFile::getCSSPath($fv->type, 1);
			
				if ($fv->menulink != '' && (int)$fv->menulink > 1) {
					$menuLinks 	= explode(',', $fv->menulink);
					$isIncluded	= in_array((int)$itemid, $menuLinks);
					if ($isIncluded) {
						JHtml::stylesheet($path . $fv->filename );
					} 
				} else {
					JHtml::stylesheet($path . $fv->filename );
				}
			}
		}
	}
	public static function renderIcon($type, $img, $alt, $class = '', $attributes = '') {
		
		//return JHtml::_('image', $img, $alt);
		
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		$bootstrap_icons = $paramsC->get( 'bootstrap_icons', 0 );
		
		if ($bootstrap_icons == 0) {
			return JHtml::_('image', $img, $alt, $attributes);
		}
		
		$i = '';
		switch($type) {
			
			case 'view':			$i = 'zoom-in';break;
			case 'download':		$i = 'download-alt';break;
			case 'geo':				$i = 'globe';break;
			case 'bold':			$i = 'bold';break;
			case 'italic':			$i = 'italic';break;
			case 'underline':		$i = 'text-color';break;
			case 'camera':			$i = 'camera';break;
			case 'comment':			$i = 'comment';break;
			case 'comment-a':		$i = 'comment';break; //ph-icon-animated
			case 'comment-fb':		$i = 'comment';break; //ph-icon-fb
			case 'cart':			$i = 'shopping-cart';break;
			case 'extlink1':		$i = 'share';break;
			case 'extlinkk2':		$i = 'share';break;
			case 'trash':			$i = 'trash';break;
			case 'publish':			$i = 'ok';break;
			case 'unpublish':		$i = 'remove';break;
			case 'viewed':			$i = 'modal-window';break;
			case 'calendar':		$i = 'calendar';break;
			case 'vote':			$i = 'star';break;
			case 'statistics':		$i = 'stats';break;
			case 'category':		$i = 'folder-close';break;
			case 'subcategory':		$i = 'folder-open';break;
			case 'upload':			$i = 'upload';break;
			case 'upload-ytb':		$i = 'upload';break;
			case 'upload-multiple':	$i = 'upload';break;
			case 'upload-java':		$i = 'upload';break;
			case 'user':			$i = 'user';break;
			case 'icon-up-images':	$i = 'arrow-left';break;
			case 'icon-up':			$i = 'arrow-up';break;
			case 'minus-sign':		$i = 'minus-sign';break;
			case 'next':			$i = 'forward';break;
			case 'prev':			$i = 'backward';break;
			case 'reload':			$i = 'repeat';break;
			case 'play':			$i = 'play';break;
			case 'stop':			$i = 'stop';break;
			case 'pause':			$i = 'pause';break;
			case 'off':				$i = 'off';break;
			case 'image':			$i = 'picture';break;
			case 'save':			$i = 'floppy-disk';break;
		
		
			
			
			// NOT glyphicon
			// smile, sad, lol, confused, wink, cooliris
			
			// Classes
			// ph-icon-animated, ph-icon-fb, icon-up-images, ph-icon-disabled
			
			default:
				if ($img != '') {
					return JHtml::_('image', $img, $alt, $attributes);
				}
			break;
		}
		
		return '<span class="glyphicon glyphicon-'.$i.' '.$class.'"></span>';
	}
	
}
?>