<?php
/**
 * @version		
 * @package		
 * @author		
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
require_once __DIR__.'/../_helper.php';
TmplK2Helper::removeCss(array('com_k2/css/k2.css'));
TmplK2Helper::removeJs(array('com_k2/js/k2.js', 'js/mootools-core-uncompressed.js', 'js/core-uncompressed.js'));
?>
<!-- Start K2 Tag Layout -->
<div id="k2Container" class="tagView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
	
	<!-- Page title -->
	<h1>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>

	<?php if(count($this->items)): ?>
		<div class="tagItemList tile-list-1">
			<?php foreach($this->items as $item): ?>

			<div class="tileItem">
				<div class="tileContent span10">
					<div class="tileHeader">
						<h2>
							<a href="<?php echo $item->link; ?>">
							<?php echo $item->title; ?>
							</a>
						</h2>
					</div>
					<?php if($item->params->get('tagItemIntroText',1)): ?>
					<div class="description">			  
					  	<?php echo $item->introtext; ?>			  
					</div>
					<?php endif; ?>
					<div class="keywords">
						<p><?php echo JText::_('K2_PUBLISHED_IN'); ?> <a href="<?php echo $item->category->link; ?>"><?php echo $item->category->name; ?></a></p>
					</div>
					<p class="pull-right">
						<a class="k2ReadMore" href="<?php echo $item->link; ?>">
							<?php echo JText::_('K2_READ_MORE'); ?>
						</a>
					</p>
				</div>
				<div class="tileInfo span2">
					<ul>
						<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $item->created, 'd/m/y'); ?></li>
						<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $item->created, 'H\hi'); ?></li>
					</ul>
				</div>			
			</div>
			
			<?php endforeach; ?>
		</div>

		<!-- Pagination -->
		<?php if($this->pagination->getPagesLinks()): ?>
		<div class="pagination row-fluid text-center">
			<?php echo $this->pagination->getPagesLinks(); ?>		
			<?php echo '<p>'.$this->pagination->getPagesCounter().'</p>'; ?>
		</div>
		<?php endif; ?>

	<?php endif; ?>
	
</div>
<!-- End K2 Tag Layout -->
