<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="tile-list-1">
	<div class="search-results search-results<?php echo $this->pageclass_sfx; ?> ">
	<?php foreach($this->results as $result) : ?>
		<div class="tileItem">
			<div class="span11 tileContent">
				<h2 class="tileHeadline result-title">
					<span class="hide"><?php echo $this->pagination->limitstart + $result->count.'. ';?></span>
					<?php if ($result->href) :?>
						<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
							<?php echo $this->escape($result->title);?>
						</a>
					<?php else:?>
						<?php echo $this->escape($result->title);?>
					<?php endif; ?>
		  		</h2>
		  		<div class="description result-text">
					<?php echo $result->text; ?>
		  		</div>
		  		<div class="keywords">
		  			<?php if ($result->section && strtolower($result->section) != 'uncategorised') : ?>
		  			<p class="result-category">Registrado em: <?php echo $this->escape($result->section); ?></p>
		  			<?php endif; ?>
		  			<?php if(@$result->metakey != ''): ?>
		  			<p class="result-tags">Assuntos (Tags): <?php TemplateSearchHelper::displayMetakeyLinks( $result->metakey, '', $this->escape($this->origkeyword) ); ?> </p>
		  			<?php endif; ?>
					<?php if ($this->params->get('show_date') && $result->created != '') : ?>
					<p class="result-created<?php echo $this->pageclass_sfx; ?>"><?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?></p>
		  			<?php endif; ?>
		  		</div>
			</div>
			<div class="span1 tileInfo">
				<?php echo $this->pagination->limitstart + $result->count.'. ';?>	
			</div>
		</div>	
	<?php endforeach; ?>
	</div>
</div>