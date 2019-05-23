<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.file' );
phocagalleryimport('phocagallery.access.access');
phocagalleryimport('phocagallery.path.path');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.render.renderinfo');
phocagalleryimport('phocagallery.picasa.picasa');
phocagalleryimport('phocagallery.image.imagefront');
phocagalleryimport('phocagallery.ordering.ordering');
phocagalleryimport('phocagallery.render.rendermaposm');

class PhocaGalleryViewCategories extends JViewLegacy
{
	public 		$tmpl;
	protected 	$params;

	public function display($tpl = null) {

		$app 						= JFactory::getApplication();
		$user 						= JFactory::getUser();
		$uri 						= \Joomla\CMS\Uri\Uri::getInstance();
		$path						= PhocaGalleryPath::getPath();
		$this->params				= $app->getParams();
		$this->tmplGeo				= array();
		$this->tmpl					= array();
		$this->itemId				= $app->input->get('Itemid', 0, 'int');
		$document					= JFactory::getDocument();
		$library 					= PhocaGalleryLibrary::getLibrary();
		$this->tmpl['action']		= $uri->toString();

		// CSS
		PhocaGalleryRenderFront::renderAllCSS();






		// Params
		$this->tmpl['display_name']				= 1;//$this->params->get( 'display_name', 1);
		$this->tmpl['image_categories_size']	= $this->params->get( 'image_categories_size', 1);
		$display_categories_geotagging 			= $this->params->get( 'display_categories_geotagging', 0 );
		$display_access_category 				= $this->params->get( 'display_access_category', 1 );
		$display_empty_categories				= $this->params->get( 'display_empty_categories', 0 );
		$hideCatArray							= explode( ',', trim( $this->params->get( 'hide_categories', '' ) ) );
		$showCatArray    						= explode( ',', trim( $this->params->get( 'show_categories', '' ) ) );
		$showParentCatArray    					= explode( ',', trim( $this->params->get( 'show_parent_categories', '' ) ) );
		$this->tmpl['categoriesimageordering']	= $this->params->get( 'categories_image_ordering', 10 );
		$this->tmpl['categoriesdisplayavatar']	= $this->params->get( 'categories_display_avatar');
		$this->tmpl['categories_description'] 	= $this->params->get( 'categories_description', '' );
		$this->tmpl['phocagallery_width']		= $this->params->get( 'phocagallery_width', '');
		$this->tmpl['phocagallery_center']		= $this->params->get( 'phocagallery_center', 0);
		$this->tmpl['display_rating']			= $this->params->get( 'display_rating', 0 );
		$this->tmpl['categories_box_space']		= $this->params->get( 'categories_box_space', '');
		$this->tmpl['display_cat_desc_box']		= $this->params->get( 'display_cat_desc_box', 0);
		$this->tmpl['char_cat_length_name'] 	= $this->params->get( 'char_cat_length_name', 9);
		$this->tmpl['categories_mosaic_images'] = $this->params->get( 'categories_mosaic_images', 0);
		$this->tmpl['diff_thumb_height']		= $this->params->get( 'diff_thumb_height', 0 );
		$this->tmpl['responsive']				= $this->params->get( 'responsive', 0 );
		$this->tmpl['bootstrap_icons']			= $this->params->get( 'bootstrap_icons', 0 );
		$this->tmpl['equal_heights']			= $this->params->get( 'equal_heights', 0 );
		$this->tmpl['masonry_center']			= $this->params->get( 'masonry_center', 0 );
		$this->tmpl['map_type']					= $this->params->get( 'map_type', 2 );

		// L E G A C Y ===
		$this->tmpl['equalpercentagewidth']		= $this->params->get( 'equal_percentage_width', 1);
		$this->tmpl['categoriesboxwidth']		= $this->params->get( 'categories_box_width','33%');
		$this->tmpl['categoriescolumns'] 		= $this->params->get( 'categories_columns', 1 );
		$this->tmpl['displayrating']			= $this->params->get( 'display_rating', 0 );
		$this->tmpl['display_image_categories']	= $this->params->get( 'display_image_categories', 1 );
		if ($this->tmpl['display_image_categories'] == 1) {

		} else {
			// If legacy no different height, no mosaic
			$this->tmpl['diff_thumb_height'] = 0;
			$this->tmpl['categories_mosaic_images'] = 0;
		}
		// END L E G A C Y ===
		switch($this->tmpl['image_categories_size']) {
			// medium
			case 1:
			case 3:
				$this->tmpl['picasa_correct_width']		= (int)$this->params->get( 'medium_image_width', 100 );
				$this->tmpl['picasa_correct_height']	= (int)$this->params->get( 'medium_image_height', 100 );
				$this->tmpl['imagewidth']				= (int)$this->params->get( 'medium_image_width', 100 );
				$this->tmpl['imageheight']				= (int)$this->params->get( 'medium_image_height', 100 );
				$this->tmpl['class_suffix']				= 'medium';

				if ($this->tmpl['categories_mosaic_images'] == 1) {
					$this->tmpl['imagewidth']				= (int)$this->params->get( 'medium_image_width', 100 ) * 3;
					$this->tmpl['imageheight']				= (int)$this->params->get( 'medium_image_height', 100 ) * 2;
				}
			break;

			// small
			case 0:
			case 2:
			default:
				$this->tmpl['picasa_correct_width']		= (int)$this->params->get( 'small_image_width', 50 );
				$this->tmpl['picasa_correct_height']	= (int)$this->params->get( 'small_image_height', 50 );
				$this->tmpl['imagewidth']				= (int)$this->params->get( 'small_image_width', 50 );
				$this->tmpl['imageheight'] 				= (int)$this->params->get( 'small_image_height', 50 );
				$this->tmpl['class_suffix']				= 'small';

				if ($this->tmpl['categories_mosaic_images'] == 1) {
					$this->tmpl['imagewidth']				= (int)$this->params->get( 'small_image_width', 50 ) * 3;
					$this->tmpl['imageheight']				= (int)$this->params->get( 'small_image_height', 50 ) * 2;
				}
			break;
		}


		$this->tmpl['boxsize'] 		= PhocaGalleryImage::setBoxSize($this->tmpl, 1);

		// Masonry effect
		if ($this->tmpl['diff_thumb_height'] == 2) {
			JHtml::_('jquery.framework', false);
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/masonry/masonry.min.js');

			if ($this->tmpl['masonry_center'] == 1) {
				$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/masonry/masonry.initialize.center.js');
			} else if ($this->tmpl['masonry_center'] == 2) {
				$document->addCustomTag('<script type="text/javascript">
				window.onload = function() {
				  var wall = new Masonry( document.getElementById(\'pg-msnr-container\'), {
					isFitWidth: true
				  });
				};
				</script>');
			} else {
				$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/masonry/masonry.initialize.js');
			}




		}



		$s = '';

		if ($this->tmpl['responsive'] == 0) {
			$wT = 'width';
			$hT = 'height';

			$s .= "\n" . '#phocagallery img {'."\n";
			$s .= '   max-width: none;'."\n";
			$s .= "\n" . '}'."\n";

		} else {

			$wT = 'max-width';
			$hT = 'max-height';
		}
		if ($this->tmpl['equal_heights'] == 1) {
			JHtml::_('jquery.framework', false);
			$document->addScript(JURI::root(true).'/media/com_phocagallery/js/jquery.equalheights.min.js');
			$document->addScriptDeclaration(
			'jQuery(window).load(function(){
				jQuery(\'.pg-csv-box\').equalHeights();
			});');
		}

		$s .= '#phocagallery {'."\n";
		if ($this->tmpl['phocagallery_center'] == 2 || $this->tmpl['phocagallery_center'] == 3) {
			$s .= '   margin: 0 auto; text-align: center;'."\n";
		}
		if ($this->tmpl['phocagallery_width'] != '') {
			$s .= '   width: '.$this->tmpl['phocagallery_width'].'px;'."\n";
		}
		$s .= '}'."\n";

		if ($this->tmpl['phocagallery_center'] == 2 || $this->tmpl['phocagallery_center'] == 3) {
			$s .= "\n" . '#pg-msnr-container {'."\n";
			$s .= '   margin: 0 auto;'."\n";
			$s .= '}'."\n";
		}

		$s .= '.pg-csv-box {'."\n";
		$s .= '   '.$hT.': '.$this->tmpl['boxsize']['height'].'px;'."\n";
		$s .= '   '.$wT.': '.$this->tmpl['boxsize']['width'].'px;'."\n";
		$s .= '}'."\n";

		$s .= '.pg-csv-box-img {'."\n";
		$s .= '   '.$hT.': '.$this->tmpl['imageheight'].'px;'."\n";
		$s .= '   '.$wT.': '.$this->tmpl['imagewidth'].'px;'."\n";
		$s .= '}'."\n";

		$document->addCustomTag('<style type="text/css">'.$s.'</style>');

		// Image next to Category in Categories View is ordered by Random as default
		$categoriesImageOrdering = PhocaGalleryOrdering::getOrderingString($this->tmpl['categoriesimageordering']);

		// MODEL
		$model					= $this->getModel();
		$this->tmpl['ordering']	= $model->getOrdering();
		$this->categories		= $this->get('data');


		// Add link and unset the categories which user cannot see (if it is enabled in params)
		// If it will be unset while access view, we must sort the keys from category array - ACCESS
		$unSet = 0;

		foreach ($this->categories as $key => $item) {

			// Unset empty categories if it is set
			if ($display_empty_categories == 0) {
				if($this->categories[$key]->numlinks < 1) {
					unset($this->categories[$key]);
					$unSet 		= 1;
					continue;
				}
			}

			// Set only selected category ID
			if (!empty($showCatArray[0]) && is_array($showCatArray)) {
				$unSetHCA = 0;

				foreach ($showCatArray as $valueHCA) {

					if((int)trim($valueHCA) == $this->categories[$key]->id) {
						$unSetHCA 	= 0;
						$unSet 		= 0;
						break;
					} else {
						$unSetHCA 	= 1;
						$unSet 		= 1;
                    }
                }
				if ($unSetHCA == 1) {
					unset($this->categories[$key]);
					continue;
				}
			}

			// Unset hidden category
			if (!empty($hideCatArray) && is_array($hideCatArray)) {
				$unSetHCA = 0;
				foreach ($hideCatArray as $valueHCA) {
					if((int)trim($valueHCA) == $this->categories[$key]->id) {
						unset($this->categories[$key]);
						$unSet 		= 1;
						$unSetHCA 	= 1;
						break;
					}
				}
				if ($unSetHCA == 1) {
					continue;
				}
			}

			// Unset not set parent categories - only categories which have specific parent id will be displayed
			if (!empty($showParentCatArray[0]) && is_array($showParentCatArray)) {
				$unSetPHCA = 0;

				foreach ($showParentCatArray as $valuePHCA) {

					if((int)trim($valuePHCA) == $this->categories[$key]->parent_id) {
						$unSetPHCA 	= 0;
						//$unSet  	= 0;
						break;
					} else {
						$unSetPHCA 	= 1;
						$unSet		= 1;
                    }
                }
				if ($unSetPHCA == 1) {
					unset($this->categories[$key]);
					continue;
				}
			}

			// Link
			$this->categories[$key]->link = PhocaGalleryRoute::getCategoryRoute($item->id, $item->alias);

			// USER RIGHT - ACCESS - - - - -
			// First Check - check if we can display category
			$rightDisplay	= 1;
			if (!empty($this->categories[$key])) {

				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $this->categories[$key]->accessuserid, $this->categories[$key]->access, $user->getAuthorisedViewLevels(), $user->get('id', 0), $display_access_category);
			}
			// Second Check - if we can display hidden category, set Key icon for them
			//                if we don't have access right to see them
			// Display Key Icon (in case we want to display unaccessable categories in list view)
			$rightDisplayKey  = 1;

			if ($display_access_category == 1) {
				// we simulate that we want not to display unaccessable categories
				// so if we get rightDisplayKey = 0 then the key will be displayed
				if (!empty($this->categories[$key])) {
					$rightDisplayKey = PhocaGalleryAccess::getUserRight('accessuserid', $this->categories[$key]->accessuserid, $this->categories[$key]->access, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0); // 0 - simulation
				}
			}

			// Is Ext Image Album?
			$extCategory = PhocaGalleryImage::isExtImage($this->categories[$key]->extid, $this->categories[$key]->extfbcatid);

			// DISPLAY AVATAR, IMAGE(ordered), IMAGE(not ordered, not recursive) OR FOLDER ICON
			$displayAvatar = 0;
			if($this->tmpl['categoriesdisplayavatar'] == 1 && isset($this->categories[$key]->avatar) && $this->categories[$key]->avatar !='' && $this->categories[$key]->avatarapproved == 1 && $this->categories[$key]->avatarpublished == 1) {
				$sizeString = PhocaGalleryImageFront::getSizeString($this->tmpl['image_categories_size']);
				$pathAvatarAbs	= $path->avatar_abs  .'thumbs/phoca_thumb_'.$sizeString.'_'. $this->categories[$key]->avatar;
				$pathAvatarRel	= $path->avatar_rel . 'thumbs/phoca_thumb_'.$sizeString.'_'. $this->categories[$key]->avatar;
				if (JFile::exists($pathAvatarAbs)){
					$this->categories[$key]->linkthumbnailpath	=  $pathAvatarRel;
					$displayAvatar = 1;
				}
			}

			if ($displayAvatar == 0) {
				if ($extCategory) {
					if ($this->tmpl['categories_mosaic_images'] == 1) {

						if ($this->tmpl['categoriesimageordering'] == 10) {
							// Special cannot be used in this case:
							$categoriesImageOrdering = 1;// set to default ordering
						}

						$this->categories[$key]->filenames	= PhocaGalleryImageFront::getCategoryImages($this->categories[$key]->id, $categoriesImageOrdering);

						$this->categories[$key]->mosaic = PhocaGalleryImageFront::renderMosaic($this->categories[$key]->filenames, $this->tmpl['image_categories_size'], 1, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);

					} else {

						if ($this->tmpl['categoriesimageordering'] != 10) {
							$imagePic		= PhocaGalleryImageFront::getRandomImageRecursive($this->categories[$key]->id, $categoriesImageOrdering, 1);
							if ($rightDisplayKey == 0) {
								$imagePic = new StdClass();
								$imagePic->exts = '';
								$imagePic->extm = '';
								$imagePic->extw = '';
								$imagePic->exth = '';
							}
							$fileThumbnail	= PhocaGalleryImageFront::displayCategoriesExtImgOrFolder($imagePic->exts,$imagePic->extm, $imagePic->extw,$imagePic->exth, $this->tmpl['image_categories_size'], $rightDisplayKey);

							$this->categories[$key]->linkthumbnailpath	= $fileThumbnail->rel;
							$this->categories[$key]->extw				= $fileThumbnail->extw;
							$this->categories[$key]->exth				= $fileThumbnail->exth;
							$this->categories[$key]->extpic				= $fileThumbnail->extpic;
						} else {
							$fileThumbnail		= PhocaGalleryImageFront::displayCategoriesExtImgOrFolder($this->categories[$key]->exts,$this->categories[$key]->extm, $this->categories[$key]->extw, $this->categories[$key]->exth, $this->tmpl['image_categories_size'], $rightDisplayKey);

							$this->categories[$key]->linkthumbnailpath	= $fileThumbnail->rel;
							$this->categories[$key]->extw				= $fileThumbnail->extw;
							$this->categories[$key]->exth				= $fileThumbnail->exth;
							$this->categories[$key]->extpic				= $fileThumbnail->extpic;
						}
					}



				} else {
					if ($this->tmpl['categories_mosaic_images'] == 1) {

						if ($this->tmpl['categoriesimageordering'] == 10) {
							// Special cannot be used in this case:
							$categoriesImageOrdering = 1;// set to default ordering
						}

						$this->categories[$key]->filenames	= PhocaGalleryImageFront::getCategoryImages($this->categories[$key]->id, $categoriesImageOrdering);
						$this->categories[$key]->mosaic = PhocaGalleryImageFront::renderMosaic($this->categories[$key]->filenames, $this->tmpl['image_categories_size']);
					} else {

						if (isset($item->image_id) && $item->image_id > 0) {
							// User has selected image in category edit
							$selectedImg = PhocaGalleryImageFront::setFileNameByImageId((int)$item->image_id);


							if (isset($selectedImg->filename) && ($selectedImg->filename != '' && $selectedImg->filename != '-')) {
								$fileThumbnail	= PhocaGalleryImageFront::displayCategoriesImageOrFolder($selectedImg->filename, $this->tmpl['image_categories_size'], $rightDisplayKey);
								$this->categories[$key]->filename = $selectedImg->filename;
								$this->categories[$key]->linkthumbnailpath   = $fileThumbnail->rel;
							} else if (isset($selectedImg->exts) && isset($selectedImg->extm) && $selectedImg->exts != '' && $selectedImg->extm != '') {
								$fileThumbnail		= PhocaGalleryImageFront::displayCategoriesExtImgOrFolder($selectedImg->exts, $selectedImg->extm, $selectedImg->extw, $selectedImg->exth, $this->tmpl['image_categories_size'], $rightDisplayKey);

								$this->categories[$key]->linkthumbnailpath	= $fileThumbnail->rel;
								$this->categories[$key]->extw				= $fileThumbnail->extw;
								$this->categories[$key]->exth				= $fileThumbnail->exth;
								$this->categories[$key]->extpic				= $fileThumbnail->extpic;
							}

						} else {
							// Standard Internal Image
							if ($this->tmpl['categoriesimageordering'] != 10) {
								$this->categories[$key]->filename	= PhocaGalleryImageFront::getRandomImageRecursive($this->categories[$key]->id, $categoriesImageOrdering);
							}
							$fileThumbnail	= PhocaGalleryImageFront::displayCategoriesImageOrFolder($this->categories[$key]->filename, $this->tmpl['image_categories_size'], $rightDisplayKey);
							$this->categories[$key]->linkthumbnailpath	= $fileThumbnail->rel;
						}
					}

				}
			}

			if ($rightDisplay == 0) {
				unset($this->categories[$key]);
				$unSet = 1;
			}
			// - - - - - - - - - - - - - - -

		}

		// ACCESS - - - - - -
		// In case we unset some category from the list, we must sort the array new
		if ($unSet == 1) {
			$this->categories = array_values($this->categories);
		}
		// - - - - - - - - - - - - - - - -

		// Do Pagination - we can do it after reducing all unneeded $this->categories, not before
		$totalCount 				= count($this->categories);
		$model->setTotal($totalCount);
		$this->tmpl['pagination']	= $this->get('pagination');
		$this->categories 			= array_slice($this->categories,(int)$this->tmpl['pagination']->limitstart, (int)$this->tmpl['pagination']->limit);
		// - - - - - - - - - - - - - - - -



		// L E G A C Y ===
		$this->tmpl['countcategories'] 	= count($this->categories);
		$this->tmpl['begin']			= array();
		$this->tmpl['end']				= array();
		$this->tmpl['begin'][0]			= 0;// first
		// Prevent from division by zero error message
		if ((int)$this->tmpl['categoriescolumns'] == 0) {
			$this->tmpl['categoriescolumns'] = 1;
		}
		$this->tmpl['begin'][1]			= ceil ($this->tmpl['countcategories'] / (int)$this->tmpl['categoriescolumns']);
		$this->tmpl['end'][0]			= $this->tmpl['begin'][1] -1;


		for ( $j = 2; $j < (int)$this->tmpl['categoriescolumns']; $j++ ) {
			$this->tmpl['begin'][$j]	= ceil(($this->tmpl['countcategories'] / (int)$this->tmpl['categoriescolumns']) * $j);
			$this->tmpl['end'][$j-1]	= $this->tmpl['begin'][$j] - 1;
		}
		$this->tmpl['end'][$j-1]		= $this->tmpl['countcategories'] - 1;// last
		$this->tmpl['endfloat']			= $this->tmpl['countcategories'] - 1;

		if($this->tmpl['equalpercentagewidth'] == 1) {
			$fixedWidth						= 100 / (int)$this->tmpl['categoriescolumns'];
			$this->tmpl['fixedwidthstyle1']	= 'width:'.$fixedWidth.'%;';
			$this->tmpl['fixedwidthstyle2']	= 'width:'.$fixedWidth.'%;';
		} else {
			$this->tmpl['fixedwidthstyle1']	= '';//'margin: 10px;';
			$this->tmpl['fixedwidthstyle2']	= '';//'margin: 0px;';
		}
		// END L E G A C Y ===



		$this->_prepareDocument();


		if ($display_categories_geotagging == 1) {

			// Params
			$this->tmplGeo['categorieslng'] 		= $this->params->get( 'categories_lng', '' );
			$this->tmplGeo['categorieslat'] 		= $this->params->get( 'categories_lat', '' );
			$this->tmplGeo['categorieszoom'] 		= $this->params->get( 'categories_zoom', 2 );
			$this->tmplGeo['googlemapsapikey'] 	= $this->params->get( 'google_maps_api_key', '' );
			$this->tmplGeo['categoriesmapwidth'] 	= $this->params->get( 'categories_map_width', '' );
			$this->tmplGeo['categoriesmapheight'] = $this->params->get( 'categorires_map_height', 500 );

			// If no lng and lat will be added, Phoca Gallery will try to find it in categories
			if ($this->tmplGeo['categorieslat'] == '' || $this->tmplGeo['categorieslng'] == '') {
				phocagalleryimport('phocagallery.geo.geo');
				$latLng = PhocaGalleryGeo::findLatLngFromCategory($this->categories);
				$this->tmplGeo['categorieslng'] = $latLng['lng'];
				$this->tmplGeo['categorieslat'] = $latLng['lat'];
			}
			$this->assignRef('tmplGeo',	$this->tmplGeo);
			
			if ($this->tmpl['map_type'] == 2) {
				parent::display('map_osm');
			} else {
				parent::display('map');
			}
			
		} else {
			parent::display($tpl);
		}
	}

	protected function _prepareDocument() {

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$title 		= null;

		$this->tmpl['gallerymetakey'] 		= $this->params->get( 'gallery_metakey', '' );
		$this->tmpl['gallerymetadesc'] 		= $this->params->get( 'gallery_metadesc', '' );

		$menu = $menus->getActive();
		/*if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}*/

		if ($menu && $this->params->get('display_menu_link_title', 1) == 1) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, htmlspecialchars_decode($app->get('sitename')));
		}

		$this->document->setTitle($title);
		if ($this->tmpl['gallerymetadesc'] != '') {
			$this->document->setDescription($this->tmpl['gallerymetadesc']);
		} else if ($this->params->get('menu-meta_description', '')) {
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		}
		if ($this->tmpl['gallerymetakey'] != '') {
			$this->document->setMetadata('keywords', $this->tmpl['gallerymetakey']);
		} else if ($this->params->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->params->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}

		// Features added by Bernard Gilly - alphaplug.com
		// load external plugins
		//$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('phocagallery');
		$results = \JFactory::getApplication()->triggerEvent('onViewCategories', array() );
	}
}
?>
