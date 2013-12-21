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
<!-- Start K2 Category Layout -->
<div id="k2Container" class="itemListView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

	<?php if($this->params->get('show_page_title') && $this->escape($this->params->get('page_title')) != $this->category->name): ?>	
	<h1 class="borderHeading">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
	<?php else: ?>
	<h1 class="borderHeading">
		<?php echo $this->category->name; ?>
	</h1>
	<?php endif; ?>
	
	<?php if($this->params->get('catDescription')): ?>
	<div class="description">
	<?php echo $this->category->description; ?>
	</div>
	<?php endif; ?>

	<!-- K2 Plugins: K2CategoryDisplay -->
	<?php echo $this->category->event->K2CategoryDisplay; ?>
	
	<?php /***********************************/ ?>
	<?php if((isset($this->secondary) || isset($this->links)) && (count($this->secondary) || count($this->links))): ?>
		<!-- Item list -->
		<div class="itemList tile-list-1">		

			<?php if(isset($this->secondary) && count($this->secondary)): ?>
			<!-- Secondary items -->		
				<?php foreach($this->secondary as $key=>$item): ?>
				<div class="tileItem">
					<?php
						// Load category_item.php by default
						$this->item=$item;
						echo $this->loadTemplate('item');
					?>
				</div>
				<?php endforeach; ?>
		
			<?php endif; ?>		

		</div>

		<?php if($this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories)): ?>
		<!-- Blocks for current category and subcategories -->
		<div class="itemListCategoriesBlock row-fluid container-items-more-cat-children">

			<?php if($this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories)): ?>
			<!-- Subcategories -->
			<div class="itemListSubCategories">
				<h3><?php echo JText::_('K2_CHILDREN_CATEGORIES'); ?></h3>

				<?php foreach($this->subCategories as $key=>$subCategory): ?>
					<div class="span4 no-margin">
						<ul>
							<li>
								<a href="<?php echo $subCategory->link; ?>">
									<?php echo $subCategory->name; ?><?php if($this->params->get('subCatTitleItemCounter')) echo ' ('.$subCategory->numOfItems.')'; ?>
								</a><br>

							</li>
						</ul>
					</div>					
				<?php endforeach; ?>

			</div>
			<?php endif; ?>

		</div>
		<?php endif; ?>

		<!-- Pagination -->
		<?php if(count($this->pagination->getPagesLinks())): ?>
		<div class="pagination row-fluid text-center">
			<?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
			
			<?php if($this->params->get('catPaginationResults')) echo '<p>'.$this->pagination->getPagesCounter().'</p>'; ?>
		</div>
		<?php endif; ?>

	<?php endif; ?>
	<?php /***********************************/ ?>

</div>
<!-- End K2 Category Layout -->
