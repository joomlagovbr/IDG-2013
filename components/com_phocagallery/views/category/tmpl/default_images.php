<?php defined('_JEXEC') or die('Restricted access');
$app	= JFactory::getApplication();
// - - - - - - - - - - 
// Images
// - - - - - - - - - -
if (!empty($this->items)) {
	echo '<div id="pg-msnr-container">';
	foreach($this->items as $ck => $cv) {
	
		if ($this->checkRights == 1) {
			// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
			// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
			$rightDisplay	= 0;
			if (isset($cv->catid) && isset($cv->cataccessuserid) && isset($cv->cataccess)) {
				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $cv->cataccessuserid, $cv->cataccess, $this->tmpl['user']->getAuthorisedViewLevels(), $this->tmpl['user']->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
		} else {
			$rightDisplay = 1;
		}
		
		// Display back button to categories list
		if ($cv->item_type == 'categorieslist'){
			$rightDisplay = 1;
		}
		
		if ($rightDisplay == 1) {
		
			// BOX Start
			echo "\n\n";
			echo '<div class="pg-cv-box item">'."\n";
			echo ' <div class="pg-cv-box-img pg-box1">'. "\n";
			echo '  <div class="pg-box2">'. "\n";
			echo '   <div class="pg-box3">'. "\n";
			
			// A Start
			echo '<a class="'.$cv->button->methodname.'"';
			if ($cv->type == 2) {
				if ($cv->overlib == 0) {
					//echo ' title="'.$cv->odesctitletag.'"';
					echo ' title="'.htmlentities ($cv->odesctitletag, ENT_QUOTES, 'UTF-8').'"';
				}
			}
			echo ' href="'. $cv->link.'"';	
			// Correct size for external Image (Picasa) - subcategory
			$extImage = false;
			if (isset($cv->extid)) {
				$extImage = PhocaGalleryImage::isExtImage($cv->extid);
			}
			if ($extImage && isset($cv->extw) && isset($cv->exth)) {
				$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($cv->extw, $cv->exth, $this->tmpl['picasa_correct_width_m'], $this->tmpl['picasa_correct_height_m'], $this->tmpl['diff_thumb_height']);
			}
			// Image Box (image, category, folder)
			if ($cv->type == 2 ) {
				// Render OnClick, Rel
				echo PhocaGalleryRenderFront::renderAAttribute($this->tmpl['detail_window'], $cv->button->options, $cv->linkorig, $this->tmpl['highslideonclick'], '', $cv->linknr, $cv->catalias);
				
				// SWITCH OR OVERLIB 
				if ($this->tmpl['switch_image'] == 1) {
					echo PhocaGalleryRenderFront::renderASwitch($this->tmpl['switch_width'], $this->tmpl['switch_height'], $this->tmpl['switch_fixed_size'], $cv->extwswitch, $cv->exthswitch, $cv->extl, $cv->linkthumbnailpath);
				} else {
					echo $cv->overlib_value;					
				}
				echo ' >';// A End

				// IMG Start
				if ($extImage) {
					//echo JHtml::_( 'image', $cv->extm, $cv->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-image'));
					
					echo JHtml::_( 'image', $cv->extm, $cv->altvalue, array('style' => 'width:'. $correctImageRes['width'] .'px;height:'.$correctImageRes['height'] .'px;', 'class' => 'pg-image'));
					
				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, $cv->oimgalt, array('class' => $cv->ooverlibclass ));
				}
				
				if ($cv->type == 2 && $cv->enable_cooliris == 1) {
					if ($extImage) {
						echo '<span class="mbf-item">#phocagallerypiclens '.$cv->catid.'-phocagallerypiclenscode-'.$cv->extid.'</span>';
					} else {
						echo '<span class="mbf-item">#phocagallerypiclens '.$cv->catid.'-phocagallerypiclenscode-'.$cv->filename.'</span>';
					}
				}
				// IMG End
			
			} else if ($cv->type == 1) {
				// Other than image
				// A End
				echo ' >';
				// IMG Start
				if ($extImage && isset($cv->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {
					
					echo JHtml::_( 'image', $cv->extm, '', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => PhocaGalleryRenderFront::renderImageClass($cv->extm)));
				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, '', array( 'class' => PhocaGalleryRenderFront::renderImageClass($cv->linkthumbnailpath)) );
				}
				// IMG END
				
			} else {
				// Other than image
				// A End
				echo ' >';
				// IMG Start
				if ($extImage && isset($cv->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {
					echo JHtml::_( 'image', $cv->extm, '', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
				} else {
					echo JHtml::_( 'image', $cv->linkthumbnailpath, '');
				}
				// IMG END
				
			} // if type 2 else type 0, 1 (image, category, folder)
			
			// A CLOSE
			echo '</a>';
			
			// Highslide Caption, Description
			if ( $this->tmpl['detail_window'] == 5) {
				if ($this->tmpl['display_title_description'] == 1) {
					echo '<div class="highslide-heading">';
					echo $cv->title;
					echo '</div>';
				}
				if ($this->tmpl['displaydescriptiondetail'] == 1) {
					echo '<div class="highslide-caption">';
					echo $cv->description;
					echo '</div>';
				}
			}
			
			// Hot, New
			if ($cv->type == 2) {
				echo PhocaGalleryRenderFront::getOverImageIcons($cv->date, $cv->hits);
				
			}
			echo "\n".'</div></div></div>'. "\n";
			// BOX End
				
			
			// Subfolder Name
			if ($cv->type == 1) {
				if ($cv->display_name == 1 || $cv->display_name == 2) {
					echo '<div class="pg-cv-name pg-cv-folder">'.PhocaGalleryText::wordDelete($cv->title, $this->tmpl['char_cat_length_name'], '...').'</div>';
				}
			}
			
			// Image Name
			if ($cv->type == 2) {
				if ($cv->display_name == 1) {
					echo '<div class="pg-cv-name">'.PhocaGalleryText::wordDelete($cv->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($cv->display_name == 2) {
					echo '<div class="pg-cv-name">&nbsp;</div>';
				}
			}
			
			// Rate Image
			if($cv->item_type == 'image') {
				if ($this->tmpl['display_rating_img'] == 2) {
					echo PhocaGalleryRateImage::renderRateImg($cv->id, $this->tmpl['diff_thumb_height'], 1);
				} else if ($this->tmpl['display_rating_img'] == 1) {
					echo '<div><a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_RATE_IMAGE').'"'
						.' href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcom'].'&Itemid='. $this->itemId ).'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->optionsrating, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
					
					echo ' >';
							
					echo '<div><ul class="star-rating-small">'
					.'<li class="current-rating" style="width:'.$cv->voteswidthimg.'px"></li>'
					.'<li><span class="star1"></span></li>';
					for ($iV = 2;$iV < 6;$iV++) {
						echo '<li><span class="stars'.$iV.'"></span></li>';
					}
					echo '</ul></div>'."\n";
					echo '</a></div>'."\n";
				}
			}

			if ($cv->display_icon_detail == 1 	||
			$cv->display_icon_download > 0 		|| 
			$cv->display_icon_vm 				|| 
			$cv->start_cooliris == 1 			|| 
			$cv->trash == 1 					|| 
			$cv->publish_unpublish == 1 		|| 
			$cv->display_icon_geo == 1 			|| 
			$cv->display_icon_commentimg == 1   ||
			$cv->camera_info == 1 				|| 
			$cv->display_icon_extlink1	== 1 	|| 
			$cv->display_icon_extlink2	== 1 	|| 
			$cv->camera_info == 1 ) {
				
				echo '<div class="pg-icon-detail">';
				
				if ($cv->start_cooliris == 1) {							
					echo '<a href="javascript:PicLensLite.start({feedUrl:\''.JURI::base(true) . '/images/phocagallery/'
			. $cv->catid .'.rss'.'\'});" title="Cooliris" >';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-cooliris.png', 'Cooliris');
					echo '</a>';
				}
				
				// ICON DETAIL	
				if ($cv->display_icon_detail == 1) {				
					echo ' <a class="'.$cv->button2->methodname.'" title="'.htmlentities ($cv->oimgtitledetail, ENT_QUOTES, 'UTF-8').'"'
						.' href="'.$cv->link2.'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeTitle($this->tmpl['detail_window'], $cv->button2->options, '', $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2'], $cv->linknr, $cv->catalias);
						
					echo ' >';
						
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-view.png', $cv->oimgaltdetail);
					echo '</a>';
				}
				
				// ICON DOWNLOAD
				if ($cv->display_icon_download > 0) {
					// Direct Download but not if there is a youtube
					if ($cv->display_icon_download == 2 && $cv->videocode == '') {
						echo ' <a title="'. JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'"'
							.' href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$cv->catslug.'&id='.$cv->slug. $this->tmpl['tmplcom'].'&phocadownload='.$cv->display_icon_download.'&Itemid='. $this->itemId ).'"';
					} else { 
						echo ' <a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'"'
							.' href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$cv->catslug.'&id='.$cv->slug. $this->tmpl['tmplcom'].'&phocadownload='.(int)$cv->display_icon_download.'&Itemid='. $this->itemId ).'"';
							
						echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
					}
					echo ' >';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-download.png', JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD'));
					echo '</a>';
				}
				
				// ICON GEO
				if ($cv->display_icon_geo == 1) {
					echo ' <a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_GEOTAGGING').'"'
						.' href="'. JRoute::_('index.php?option=com_phocagallery&view=map&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcom'].'&Itemid='. $this->itemId ).'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
			
					echo ' >';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-geo.png', JText::_('COM_PHOCAGALLERY_GEOTAGGING'));
					echo '</a>';
				}
				
				// ICON EXIF
				if ($cv->camera_info == 1) {
					echo ' <a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_CAMERA_INFO').'"'
						.' href="'.JRoute::_('index.php?option=com_phocagallery&view=info&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcom'].'&Itemid='. $this->itemId ).'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
						
					echo ' >';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-info.png', JText::_('COM_PHOCAGALLERY_CAMERA_INFO'));
					echo '</a>';
				}
				
				// ICON COMMENT
				if ($cv->display_icon_commentimg == 1) {
					if ($this->tmpl['detail_window'] == 7 || $this->tmpl['display_comment_nopup'] == 1) {
						$tmplClass	= '';
					} else {
						$tmplClass 	= 'class="'.$cv->buttonother->methodname.'"';
					}
					echo ' <a '.$tmplClass.' title="'.JText::_('COM_PHOCAGALLERY_COMMENT_IMAGE').'"'
						.' href="'. JRoute::_('index.php?option=com_phocagallery&view=comment&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcomcomments'].'&Itemid='. $this->itemId ).'"';
					
					if ($this->tmpl['display_comment_nopup'] == 1) {
						echo '';
					} else {
						echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
					}
					echo ' >';
					// If you go from RSS or administration (e.g. jcomments) to category view, you will see already commented image (animated icon)
					$cimgid = $app->input->get('cimgid', 0,'int');
					if($cimgid > 0) {
						echo JHtml::_('image', $this->tmpl['icon_path'].'icon-comment-a.gif', JText::_('COM_PHOCAGALLERY_COMMENT_IMAGE'));
					} else {
						$commentImg = ($this->tmpl['externalcommentsystem'] == 2) ? 'icon-comment-fb-small' : 'icon-comment';
						echo JHtml::_('image', $this->tmpl['icon_path'].$commentImg.'.png', JText::_('COM_PHOCAGALLERY_COMMENT_IMAGE'));
					}
					echo '</a>';	
				}
				
				// ICON EXTERNAL LINK 1
				if ($cv->display_icon_extlink1 == 1) {
					echo ' <a title="'.$cv->extlink1[1] .'"'
						.' href="http://'.$cv->extlink1[0] .'" target="'.$cv->extlink1[2] .'" '.$cv->extlink1[5].'>'
						.$cv->extlink1[4].'</a>';
				}
				
				// ICON EXTERNAL LINK 2
				if ($cv->display_icon_extlink2 == 1) {
					echo ' <a title="'.$cv->extlink2[1] .'"'
						.' href="http://'.$cv->extlink2[0] .'" target="'.$cv->extlink2[2] .'" '.$cv->extlink2[5].'>'
						.$cv->extlink2[4].'</a>';
					
				}
				
				// ICON VirtueMart Product
				if ($cv->display_icon_vm == 1) {
					echo ' <a title="'.JText::_('COM_PHOCAGALLERY_ESHOP').'" href="'. JRoute::_($cv->vmlink).'">';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-cart.png', JText::_('COM_PHOCAGALLERY_ESHOP'));
					echo '</a>';
				}
				
				// ICON Trash for private categories
				if ($cv->trash == 1) {
					echo ' <a onclick="return confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_DELETE_ITEMS').'\')" title="'.JText::_('COM_PHOCAGALLERY_DELETE').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=remove'.'&Itemid='. $this->itemId ).$this->tmpl['limitstarturl'].'">';
					echo JHtml::_('image', $this->tmpl['icon_path'].'icon-trash.png', JText::_('COM_PHOCAGALLERY_DELETE'));
					echo '</a>';
				}
				
				// ICON Publish Unpublish for private categories
				if ($cv->publish_unpublish == 1) {
					if ($cv->published == 1) {
						echo ' <a title="'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=unpublish'.'&Itemid='. $this->itemId ). $this->tmpl['limitstarturl'].'">';
						echo JHtml::_('image', $this->tmpl['icon_path'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_UNPUBLISH'));
						echo '</a>';
					}
					if ($cv->published == 0) {
						echo ' <a title="'.JText::_('COM_PHOCAGALLERY_PUBLISH').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=publish'.'&Itemid='. $this->itemId ).$this->tmpl['limitstarturl'].'">';
						echo JHtml::_('image', $this->tmpl['icon_path'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_PUBLISH'));
						echo '</a>';
					
					}
				}
			
				// ICON Approve
				if ($cv->approved_not_approved == 1) {
					// Display the information about Approving too:
					if ($cv->approved == 1) {
						echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_APPROVED').'">'.JHtml::_('image', $this->tmpl['icon_path'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED')).'</a>';
					}
					if ($cv->approved == 0) {
						echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_NOT_APPROVED').'">'.JHtml::_('image', $this->tmpl['icon_path'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED')).'</a>';
					
					}
				}
			
				echo '</div>'. "\n";
				echo '<div class="ph-cb"></div>'. "\n";
			}
			
			// Tags
			if ($cv->type == 2 && isset($cv->otags) && $cv->otags != '') {
				echo '<div class="pg-cv-tags">'.$cv->otags.'</div>'. "\n";
			}
			
			// Description in Box
			if ($this->tmpl['display_img_desc_box'] == 1  && $cv->description != '') {
				echo '<div class="pg-cv-descbox">'. strip_tags($cv->description).'</div>'. "\n";
			} else if ($this->tmpl['display_img_desc_box'] == 2  && $cv->description != '') {	
				echo '<div class="pg-cv-descbox">' .(JHtml::_('content.prepare', $cv->description, 'com_phocagallery.image')).'</div>'. "\n";
			}
			if ($cv->type == 2 && ($this->tmpl['display_comment_img'] == 2 || $this->tmpl['display_comment_img'] == 3)) {
				echo '<div class="pg-cv-comment-img-box">';
				if (isset($cv->comment_items)) {
				
					foreach($cv->comment_items as $cok => $cov) {
						echo '<div class="pg-cv-comment-img-box-item">';
						echo '<div class="pg-cv-comment-img-box-avatar">';
						$img = '<div style="width: 20px; height: 20px;">&nbsp;</div>';
						if (isset($cov->avatar) && $cov->avatar != '') {
							$pathAvatarAbs	= $this->tmpl['path']->avatar_abs  .'thumbs'.DS.'phoca_thumb_s_'. $cov->avatar;
							$pathAvatarRel	= $this->tmpl['path']->avatar_rel . 'thumbs/phoca_thumb_s_'. $cov->avatar;
							if (JFile::exists($pathAvatarAbs)){
								$avSize = getimagesize($pathAvatarAbs);
								$avRatio = $avSize[0]/$avSize[1];
								$avHeight = 20;
								$avWidth = 20 * $avRatio;
								$img = '<img src="'.JURI::base().'/'.$pathAvatarRel.'" width="'.(int)$avWidth.'" height="'.(int)$avHeight.'" alt="" />';
							}
						}
						echo $img;
						echo '</div>';
						echo '<div class="pg-cv-comment-img-box-comment">'.$cov->name.': '.$cov->comment.'</div>';
						echo '<div style="clear:both"></div>';
						echo '</div>';
					}
				}
				echo '<div id="pg-cv-comment-img-box-result'.$cv->id.'"></div>';//AJAX
				//echo '<div id="pg-cv-comment-img-box-newcomment'.$cv->id.'"></div>';//AJAX
				
				// href="javascript:void(0);"
				echo '<div class="pg-tb-m5"><button class="btn btn-mini" onclick="javascript:document.getElementById(\'pg-cv-add-comment-img'.$cv->id.'\').style.display = \'block\';var wall = new Masonry( document.getElementById(\'pg-msnr-container\'), {});">'.JText::_('COM_PHOCAGALLERY_COMMENT').'</button></div>';
				echo '<div id="pg-cv-add-comment-img'.$cv->id.'" class="pg-cv-add-comment-img">';
				
				if (isset($cv->allready_commented)) {
					if ($cv->allready_commented == 1) {
						echo '<p>'.JText::_('COM_PHOCAGALLERY_COMMENT_ALREADY_SUBMITTED').'</p>';
					} else if ($this->tmpl['not_registered']) {
						echo '<p>'.JText::_('COM_PHOCAGALLERY_COMMENT_ONLY_REGISTERED_LOGGED_SUBMIT_COMMENT').'</p>';
							
					} else {
					
						///echo '<form id="pgcvcommentimg'.$cv->id.'" method="post" >';
						echo '<textarea name="pg-cv-comments-editor-img'.(int)$cv->id.'" id="pg-cv-comments-editor-img'.(int)$cv->id.'"  rows="2"  class= "comment-input" ></textarea>';
						
						echo '<button onclick="pgCommentImage('.(int)$cv->id.', '.$this->tmpl['diff_thumb_height'].', \'pg-msnr-container\');document.getElementById(\'pg-cv-add-comment-img'.$cv->id.'\').style.display = \'none\';var wall = new Masonry( document.getElementById(\'pg-msnr-container\'), {});" class="btn btn-small" type="submit" id="phocagallerycommentssubmitimg">'. JText::_('COM_PHOCAGALLERY_SUBMIT_COMMENT').'</button>';
						?>
						<input type="hidden" name="catid" value="<?php echo $cv->catid ?>"/>
						<input type="hidden" name="imgid" value="<?php echo $cv->id ?>"/>
						<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/> <?php
						echo JHtml::_( 'form.token' );
						///echo '</form>';
					}
				}
				
				echo '</div>';
				
				
				echo '</div>';
				
				
			}
			
			
			
			echo '</div>'. "\n";
			
			

		}
	}
	echo '</div>'."\n";

} else {
	// Will be not displayed
	//echo JText::_('COM_PHOCAGALLERY_THERE_IS_NO_IMAGE');
}