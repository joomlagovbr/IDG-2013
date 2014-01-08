<?php
defined('_JEXEC') or die('Restricted access');
$this->categories = TmplPhocagalleryHelper::getExtrafields($this->categories, array('metakey', 'date'));

?>
<div id="phocagallery-categories-detail" class="tile-list-1">
	<?php for ($i = 0, $limit = count($this->categories); $i < $limit; $i++): ?>	
		<div class="tileItem">
			<div class="span10 tileContent">
				<div class="tileImage">
					<a href="<?php echo $this->categories[$i]->link; ?>">
						<?php
						if (isset($this->categories[$i]->extpic) && $this->categories[$i]->extpic) {
							$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($this->categories[$i]->extw, $this->categories[$i]->exth, $this->tmpl['picasa_correct_width'], $this->tmpl['picasa_correct_height']);
							echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;', '-',$this->categories[$i]->title), array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height'], 'class' => 'tileImage'));
						} else {

							echo JHtml::_( 'image', $this->categories[$i]->linkthumbnailpath, str_replace('&raquo;','-',$this->categories[$i]->title),array('class' => 'tileImage'));
						}
						?>
					</a>						
				</div>
				<h2 class="tileHeadline">
	            	<a href="<?php echo $this->categories[$i]->link; ?>"><?php echo $this->categories[$i]->title ?></a>
	          	</h2>
	          	<div class="description">
	          		<?php					
	   				echo TmplPhocagalleryHelper::getFormatedDescription( $this->categories[$i]->description );
	          		?>
	          	</div>
	          	<?php if(@!empty($this->categories[$i]->metakey)): ?>	          	
	          	<div class="keywords">
	          		Tags:
	          		<?php  TmplPhocagalleryHelper::displayMetakeyLinks($this->categories[$i]->metakey); ?>
	          	</div>
	          	<?php endif; ?>
			</div>
			<div class="span2 tileInfo">
				<ul>
					<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $this->categories[$i]->date, 'd/m/y'); ?></li>
					<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $this->categories[$i]->date, 'H\hi'); ?></li>
				</ul>
			</div>
		</div>
	<?php endfor; ?>
</div>