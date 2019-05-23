<?php defined('_JEXEC') or die('Restricted access');
$app	= JFactory::getApplication();
// - - - - - - - - - - 
// Images
// - - - - - - - - - -
$subfolders = array();
if (!empty($this->items)) { ?>
<div class="tile-list-1 row-fluid">
<?php	
		$items_counter = 0;
		foreach($this->items as $ck => $cv) {

		//retirando botao de voltar gigante do phocagallery
		if($ck==0)
			continue;
		//fim retirando botao de voltar gigante...


		if($cv->item_type=='subfolder')
		{
			$subfolders[] = $cv;
			continue;
		}
		else if($cv->item_type=='image')
		{
			$items_counter++;
		}
	
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
			echo '<div class="phocagallery-box-file '.$cv->cls.' span6 '.(($items_counter%2==1)? 'no-margin' : '').'">'. "\n";
			echo '<div class="phocagallery-box-file-first tileItem">'. "\n";
			echo '<div class="phocagallery-box-file-second tileContent">'. "\n";
			echo '<div class="phocagallery-box-file-third">'. "\n";

			echo '<div class="tileHeader">';
					echo '<h3 class="subtitle">';
					echo $cv->title;
					echo '</h3>';
			echo '</div>';
			
			// A Start
			echo '<a class="'.$cv->button->methodname.'"';
			if ($cv->type == 2) {
				if ($cv->overlib == 0) {
					//echo ' title="'.$cv->odesctitletag.'"';
					echo ' title="'.htmlentities ($cv->odesctitletag, ENT_QUOTES, 'UTF-8').'"';
				}
			}
			echo ' href="'. str_replace(array('?tmpl=component', '&tmpl=component', '&amp;tmpl=component'), '', $cv->link).'"';

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
					
					echo JHtml::_( 'image', $cv->extm, $cv->altvalue, array('style' => 'width:'. $correctImageRes['width'] .'px;height:'.$correctImageRes['height'] .'px;', 'class' => 'pg-image tileImage'));
					
				} else {
					echo @JHtml::_( 'image', $cv->linkthumbnailpath, $cv->oimgalt, array('class' => $cv->ooverlibclass.' tileImage' ));
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

			echo '<div class="hide hidden-description">';
			echo PhocaGalleryText::wordDelete($cv->description, 190, '...');
			echo '</div>';
//ate aqui igual			
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
				echo '<div class="detail">';
					if ($cv->start_cooliris == 1) {							
						echo '<a href="javascript:PicLensLite.start({feedUrl:\''.JURI::base(true) . '/images/phocagallery/'
			. $cv->catid .'.rss'.'\'});" title="Cooliris" >';
						echo '<i class="icon-share"><span class="hide">Cooliris</span></i>';
						echo '</a>';
					}
					
					// ICON DETAIL	
					if ($cv->display_icon_detail == 1) {				
						echo ' <a class="'.$cv->button2->methodname.' openBtn" data-toggle="modal" data-target="#myModal" title="'.htmlentities ($cv->oimgtitledetail, ENT_QUOTES, 'UTF-8').'"'
							.' href="'.$cv->link2.'"';
							
						// echo PhocaGalleryRenderFront::renderAAttributeTitle($this->tmpl['detail_window'], $cv->button2->options, '', $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2'], $cv->linknr, $cv->catalias);
							
						echo ' >';							
						echo '<i class="icon-search"><span class="hide">Ampliar</span></i>';
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
						echo '<i class="icon-download-alt"><span class="hide">'.JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'</span></i>';
						echo '</a>';
					}

					// ICON GEO
					if ($cv->display_icon_geo == 1) {
						echo ' <a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_GEOTAGGING').'"'
							.' href="'. JRoute::_('index.php?option=com_phocagallery&view=map&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcom'].'&Itemid='. $this->itemId ).'"';
							
						echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
				
						echo ' >';
						echo '<i class="icon-map-marker"><span class="hide">'.JText::_('COM_PHOCAGALLERY_GEOTAGGING').'</span></i>';
						echo '</a>';
					}

					// ICON EXIF
					if ($cv->camera_info == 1) {
						echo ' <a class="'.$cv->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_CAMERA_INFO').'"'
							.' href="'.JRoute::_('index.php?option=com_phocagallery&view=info&catid='.$cv->catslug.'&id='.$cv->slug.$this->tmpl['tmplcom'].'&Itemid='. $this->itemId ).'"';
							
						echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detail_window'], $cv->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
							
						echo ' >';
						echo '<i class="icon-camera"><span class="hide">'.JText::_('COM_PHOCAGALLERY_CAMERA_INFO').'</span></i>';		
						echo '</a>';
					}

					// ICON EXTERNAL LINK 1
					if ($cv->display_icon_extlink1 == 1) {
						echo ' <a title="'.$cv->extlink1[1] .'"'
							.' href="http://'.$cv->extlink1[0] .'" target="'.$cv->extlink1[2] .'" '.$cv->extlink1[5].'><i class="icon-external-link-sign"><span class="hide">'
							.$cv->extlink1[4].'</span></i></a>';
					}
					
					// ICON EXTERNAL LINK 2
					if ($cv->display_icon_extlink2 == 1) {
						echo ' <a title="'.$cv->extlink2[1] .'"'
							.' href="http://'.$cv->extlink2[0] .'" target="'.$cv->extlink2[2] .'" '.$cv->extlink2[5].'><i class="icon-external-link"><span class="hide">'
							.$cv->extlink2[4].'</span></i></a>';
						
					}

					// ICON Trash for private categories
					if ($cv->trash == 1) {
						echo ' <a onclick="return confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_DELETE_ITEMS').'\')" title="'.JText::_('COM_PHOCAGALLERY_DELETE').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=remove'.'&Itemid='. $this->itemId ).$this->tmpl['limitstarturl'].'">';
						echo '<i class="icon-trash"><span class="hide">'.JText::_('COM_PHOCAGALLERY_DELETE').'</span></i>';
						echo '</a>';
					}

					// ICON Publish Unpublish for private categories
					if ($cv->publish_unpublish == 1) {
						if ($cv->published == 1) {
							echo ' <a title="'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=unpublish'.'&Itemid='. $this->itemId ). $this->tmpl['limitstarturl'].'">';
							echo '<i class="icon-eye-open"><span class="hide">'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'</span></i>';
							echo '</a>';
						}
						if ($cv->published == 0) {
							echo ' <a title="'.JText::_('COM_PHOCAGALLERY_PUBLISH').'" href="'. JRoute::_($this->tmpl['plcat'] . '&catid='.$cv->catslug.'&id='.$cv->slug.'&controller=category&task=publish'.'&Itemid='. $this->itemId ).$this->tmpl['limitstarturl'].'">';
							echo '<i class="icon-eye-close"><span class="hide">'.JText::_('COM_PHOCAGALLERY_PUBLISH').'</span></i>';
							echo '</a>';
						
						}
					}

					// ICON Approve
					if ($cv->approved_not_approved == 1) {
						// Display the information about Approving too:
						if ($cv->approved == 1) {
							echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_APPROVED').'"><i class="icon-ok-sign"><span class="hide">'.JText::_('COM_PHOCAGALLERY_APPROVED').'</span></i></a>';
						}
						if ($cv->approved == 0) {
							echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_NOT_APPROVED').'"><i class="icon-remove-sign"><span class="hide">'.JText::_('COM_PHOCAGALLERY_NOT_APPROVED').'</span></i></a>';
						
						}
					}

				echo '</div>';
			}

			echo "\n".'</div></div></div>'. "\n";
			// BOX End
			
						
			echo '</div>'. "\n";
				

		}
	}
?>
</div>
<?php
} else {
	?>
	<p class="description">N&atilde;o h&aacute; imagens dispon&iacute;veis.</p>
	<?php
}
?>
<div id="fulltext-modal" class="modal hide fade" tabindex="-1" role="dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<strong>Modal header</strong>	
	</div>
	<div class="modal-body">
      <iframe src="<?php echo JURI::root(); ?>images/index.html" frameborder="0" height="100%" width="100%"></iframe>
	</div>
	<div class="modal-footer">
		<div class="text"></div>
    	<!-- <button class="btn pull-right" data-dismiss="modal" aria-hidden="true">Fechar</button>     -->	
  	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
	// jQuery('.openBtn').each(function(){ jQuery(this).hide(); })	;	
		jQuery('.openBtn').click(function(){
			// alert('teste');
			// return false;
			frameSrc = jQuery(this).attr('href');
			title = jQuery(this).parents('.phocagallery-box-file-third').children('.tileHeader').children('h3').text();
			desc = jQuery(this).parents('.phocagallery-box-file-third').children('.hidden-description').html();			
		    jQuery('#fulltext-modal').on('show', function () {
		    	jQuery('#fulltext-modal .modal-header strong').html( title );			
   				jQuery('#fulltext-modal iframe').attr("src",frameSrc);
		      	jQuery('#fulltext-modal .modal-footer .text').html( desc );
			});
			jQuery('#fulltext-modal').on('hide', function () {// 				
   				jQuery('#fulltext-modal iframe').attr("src", '<?php echo JURI::root(); ?>images/index.html');
		      	// jQuery('#fulltext-modal .modal-footer').html( title+desc );
			});
		    jQuery('#fulltext-modal').modal({show:true});
		    return false;
		});		
	});
</script><noscript>Aten&ccedil;&atilde;o: a exibi&ccedil;&atilde;o da amplia&ccedil;&atilde;o da foto de depende de javascript.</noscript>
<?php if(count($subfolders)>0): ?>
<div class="row-fluid container-items-more-cat-children">
	<div class="cat-children">
		<h3>
		Sub-categorias deste &aacute;lbum
		</h3>
		<?php 
		for ($i=0, $limit=count($subfolders); $i < $limit; $i++) { 
			?>
			<div class="span3 no-margin">
				<ul>
					<li><span class="item-title"><a href="<?php echo $subfolders[$i]->link ?>"><?php echo $subfolders[$i]->title ?></a></span></li>
				</ul>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php endif; ?>