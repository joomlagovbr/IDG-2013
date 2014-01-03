<?php defined('_JEXEC') or die('Restricted access');
// - - - - - - - - - - 
// Images
// - - - - - - - - - -
$subfolders = array();
if (!empty($this->items)) { ?>
<div class="tile-list-1 row-fluid">
<?php
	$items_counter = 0;
	foreach($this->items as $key => $value) {
		
		//retirando botao de voltar gigante do phocagallery
		if($key==0)
			continue;
		//fim retirando botao de voltar gigante...


		if($value->item_type=='subfolder')
		{
			$subfolders[] = $value;
			continue;
		}
		else if($value->item_type=='image')
		{
			$items_counter++;
		}

		if ($this->checkRights == 1) {
			// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
			// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
			$rightDisplay	= 0;
			if (isset($value->catid) && isset($value->cataccessuserid) && isset($value->cataccess)) {
				$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $value->cataccessuserid, $value->cataccess, $this->tmpl['user']->authorisedLevels(), $this->tmpl['user']->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
		} else {
			$rightDisplay = 1;
		}
		
		// Display back button to categories list
		if ($value->item_type == 'categorieslist'){
			$rightDisplay = 1;
		}
		$rightDisplay = 1;
		if ($rightDisplay == 1) {
		
			// BOX Start
			echo "\n\n";
			echo '<div class="phocagallery-box-file '.$value->cls.' span6 '.(($items_counter%2==1)? 'no-margin' : '').'">'. "\n";
			echo '<div class="phocagallery-box-file-first tileItem">'. "\n";
			echo '<div class="phocagallery-box-file-second tileContent">'. "\n";
			echo '<div class="phocagallery-box-file-third">'. "\n";

			echo '<div class="tileHeader">';
					echo '<h3 class="subtitle">';
					echo $value->title;
					echo '</h3>';
			echo '</div>';

			// A Start
			echo '<a class="'.$value->button->methodname.'"';
			
			if ($value->type == 2) {
				if ($value->overlib == 0) {
					echo ' title="'.htmlentities ($value->odesctitletag, ENT_QUOTES, 'UTF-8').'"';
				}
			}
			echo ' href="'. str_replace(array('?tmpl=component', '&tmpl=component', '&amp;tmpl=component'), '', $value->link).'"';
								
			// Correct size for external Image (Picasa) - subcategory
			$extImage = false;
			if (isset($value->extid)) {
				$extImage = PhocaGalleryImage::isExtImage($value->extid);
			}
			if ($extImage && isset($value->extw) && isset($value->exth)) {
				$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($value->extw, $value->exth, $this->tmpl['picasa_correct_width_m'], $this->tmpl['picasa_correct_height_m']);
			}
				
			// Image Box (image, category, folder)
			if ($value->type == 2 ) {
				
				// Render OnClick, Rel
				echo PhocaGalleryRenderFront::renderAAttribute($this->tmpl['detailwindow'], $value->button->options, $value->linkorig, $this->tmpl['highslideonclick'], '', $value->linknr, $value->catalias);
				
				// SWITCH OR OVERLIB 
				if ($this->tmpl['switchimage'] == 1) {
					echo PhocaGalleryRenderFront::renderASwitch($this->tmpl['switchwidth'], $this->tmpl['switchheight'], $this->tmpl['switchfixedsize'], $value->extwswitch, $value->exthswitch, $value->extl, $value->linkthumbnailpath);
				} else {
					echo $value->overlib_value;					
				}
				echo ' >';// A End

				// IMG Start
				if ($extImage) {
					echo JHtml::_( 'image', $value->extm, $value->altvalue, array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'pg-image tileImage'));
				} else {
					echo JHtml::_( 'image', $value->linkthumbnailpath, $value->oimgalt, array('class' => $value->ooverlibclass.' tileImage' ));
				}
				
				if ($value->type == 2 && $value->enable_cooliris == 1) {
					if ($extImage) {
						echo '<span class="mbf-item">#phocagallerypiclens '.$value->catid.'-phocagallerypiclenscode-'.$value->extid.'</span>';
					} else {
						echo '<span class="mbf-item">#phocagallerypiclens '.$value->catid.'-phocagallerypiclenscode-'.$value->filename.'</span>';
					}
				}
				// IMG End
			
			} else if ($value->type == 1) {
				// Other than image
				// A End
				echo ' >';
				// IMG Start
				if ($extImage && isset($value->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {
					
					echo JHtml::_( 'image', $value->extm, '', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => PhocaGalleryRenderFront::renderImageClass($value->extm)));
				} else {
					echo JHtml::_( 'image', $value->linkthumbnailpath, '', array( 'class' => PhocaGalleryRenderFront::renderImageClass($value->linkthumbnailpath)) );
				}
				// IMG END
				
			} else {
				// Other than image
				// A End
				echo ' >';
				// IMG Start
				if ($extImage && isset($value->extm) && isset($correctImageRes['width']) && isset($correctImageRes['width'])) {
					echo JHtml::_( 'image', $value->extm, '', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
				} else {
					echo JHtml::_( 'image', $value->linkthumbnailpath, '');
				}
				// IMG END
				
			} // if type 2 else type 0, 1 (image, category, folder)
			
			// A CLOSE
			echo '</a>';
			
			echo '<div class="hide hidden-description">';
			echo PhocaGalleryText::wordDelete($value->description, 190, '...');
			echo '</div>';

			// Highslide Caption, Description
			if ( $this->tmpl['detailwindow'] == 5) {
				if ($this->tmpl['displaytitleindescription'] == 1) {
					echo '<div class="highslide-heading">';
					echo $value->title;
					echo '</div>';
				}
				if ($this->tmpl['displaydescriptiondetail'] == 1) {
					echo '<div class="highslide-caption">';
					echo $value->description;
					echo '</div>';
				}
			}

			if ($value->displayicondetail == 1 ||
			$value->displayicondownload > 0 || 
			$value->displayiconfolder == 1 || 
			$value->displayiconvm || 
			$value->startpiclens == 1 || 
			$value->trash == 1 || 
			$value->publishunpublish == 1 || 
			$value->displayicongeo == 1 || 
			$value->camerainfo == 1 || 
			$value->displayiconextlink1	== 1 || 
			$value->displayiconextlink2	== 1 || 
			$value->camerainfo == 1 ) {
				
				echo '<div class="detail">';
				
				if ($value->startpiclens == 1) {							
					echo '<a href="javascript:PicLensLite.start({feedUrl:\''.JURI::base(true) . '/images/phocagallery/'
			. $value->catid .'.rss'.'\'});" title="Cooliris" >';
					echo '<i class="icon-share"><span class="hide">Cooliris</span></i>';				
					echo '</a>';
				}
				
				// ICON DETAIL	
				if ($value->displayicondetail == 1) {
						
					echo ' <a class="'.$value->button2->methodname.' openBtn" data-toggle="modal" data-target="#myModal" title="'.htmlentities ($value->oimgtitledetail, ENT_QUOTES, 'UTF-8').'"'
						.' href="'.$value->link2.'"';
						
					// echo PhocaGalleryRenderFront::renderAAttributeTitle($this->tmpl['detailwindow'], $value->button2->options, '', $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2'], $value->linknr, $value->catalias);
						
					echo ' >';
					echo '<i class="icon-search"><span class="hide">Ampliar</span></i>';
					
					echo '</a>';
				}
				
				// ICON FOLDER
				if ($value->displayiconfolder == 1) {
					echo ' <a title="'.JText::_('COM_PHOCAGALLERY_SUBCATEGORY').'"'.' href="'.$value->link.'">';
					echo '<i class="icon-folder-open"><span class="hide">Pasta</span></i>';
					echo '</a>';
				}
				
				// ICON DOWNLOAD
				if ($value->displayicondownload > 0) {
					// Direct Download but not if there is a youtube
					if ($value->displayicondownload == 2 && $value->videocode == '') {
						echo ' <a title="'. JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'"'
							.' href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$value->catslug.'&id='.$value->slug. $this->tmpl['tmplcom'].'&phocadownload='.$value->displayicondownload.'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).'"';
					} else { 
						echo ' <a class="'.$value->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'"'
							.' href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$value->catslug.'&id='.$value->slug. $this->tmpl['tmplcom'].'&phocadownload='.(int)$value->displayicondownload.'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).'"';
							
						echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detailwindow'], $value->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
					}
					echo ' >';
					echo '<i class="icon-download-alt"><span class="hide">'.JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'</span></i>';				
					echo '</a>';
				}
				
				// ICON GEO
				if ($value->displayicongeo == 1) {
					echo ' <a class="'.$value->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_GEOTAGGING').'"'
						.' href="'. JRoute::_('index.php?option=com_phocagallery&view=map&catid='.$value->catslug.'&id='.$value->slug.$this->tmpl['tmplcom'].'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detailwindow'], $value->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
			
					echo ' >';
					echo '<i class="icon-map-marker"><span class="hide">'.JText::_('COM_PHOCAGALLERY_GEOTAGGING').'</span></i>';			
					echo '</a>';
				}
				
				// ICON EXIF
				if ($value->camerainfo == 1) {
					echo ' <a class="'.$value->buttonother->methodname.'" title="'.JText::_('COM_PHOCAGALLERY_CAMERA_INFO').'"'
						.' href="'.JRoute::_('index.php?option=com_phocagallery&view=info&catid='.$value->catslug.'&id='.$value->slug.$this->tmpl['tmplcom'].'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).'"';
						
					echo PhocaGalleryRenderFront::renderAAttributeOther($this->tmpl['detailwindow'], $value->buttonother->options, $this->tmpl['highslideonclick'], $this->tmpl['highslideonclick2']);
						
					echo ' >';

					echo '<i class="icon-camera"><span class="hide">'.JText::_('COM_PHOCAGALLERY_CAMERA_INFO').'</span></i>';	
					
					echo '</a>';
				}
				
				
				// ICON EXTERNAL LINK 1
				if ($value->displayiconextlink1 == 1) {
					echo ' <a title="'.$value->extlink1[1] .'"'
						.' href="http://'.$value->extlink1[0] .'" target="'.$value->extlink1[2] .'" '.$value->extlink1[5].'><i class="icon-external-link-sign"><span class="hide">'
						.$value->extlink1[1].'</span></i></a>';
				}
				
				// ICON EXTERNAL LINK 2
				if ($value->displayiconextlink2 == 1) {
					echo ' <a title="'.$value->extlink2[1] .'"'
						.' href="http://'.$value->extlink2[0] .'" target="'.$value->extlink2[2] .'" '.$value->extlink2[5].'><i class="icon-external-link"><span class="hide">'
						.$value->extlink2[1].'</span></i></a>';
					
				}
				
				
				// ICON Trash for private categories
				if ($value->trash == 1) {
					echo ' <a onclick="return confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_DELETE_ITEMS').'\')" title="'.JText::_('COM_PHOCAGALLERY_DELETE').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$value->catslug.'&id='.$value->slug.'&controller=category&task=remove'.'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).$this->tmpl['limitstarturl'].'">';
					echo '<i class="icon-trash"><span class="hide">'.JText::_('COM_PHOCAGALLERY_DELETE').'</span></i>';	
					echo '</a>';
				}
				
				// ICON Publish Unpublish for private categories
				if ($value->publishunpublish == 1) {
					if ($value->published == 1) {
						echo ' <a title="'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$value->catslug.'&id='.$value->slug.'&&controller=category&task=unpublish'.'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).$this->tmpl['limitstarturl'].'">';
						echo '<i class="icon-eye-open"><span class="hide">'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'</span></i>';				
						echo '</a>';
					}
					elseif ($value->published == 0) {
						echo ' <a title="'.JText::_('COM_PHOCAGALLERY_PUBLISH').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$value->catslug.'&id='.$value->slug.'&controller=category&task=publish'.'&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') ).$this->tmpl['limitstarturl'].'">';
						echo '<i class="icon-eye-close"><span class="hide">'.JText::_('COM_PHOCAGALLERY_PUBLISH').'</span></i>';							
						echo '</a>';
					
					}
				}
			
				// ICON APPROVE
				if ($value->approvednotapproved == 1) {
					// Display the information about Approving too:
					if ($value->approved == 1) {						
						echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_APPROVED').'"><i class="icon-ok-sign"><span class="hide">'.JText::_('COM_PHOCAGALLERY_IMAGE_APPROVED').'</span></i></a>';
					}
					elseif ($value->approved == 0) {				
						echo ' <a href="#" title="'.JText::_('COM_PHOCAGALLERY_IMAGE_NOT_APPROVED').'"><i class="icon-remove-sign"><span class="hide">'.JText::_('COM_PHOCAGALLERY_IMAGE_NOT_APPROVED').'</span></i></a>';
					
					}
				}
			
				echo '</div>'. "\n";				
			}
			
			if(@isset($value->metadesc))
			{
				if(!empty($value->metadesc))
					echo '<span class="metadesc">'.$value->metadesc.'</a>';
			}
			
			echo "\n".'</div></div></div>'. "\n";
			// BOX End
				
			
			echo '</div>';

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
