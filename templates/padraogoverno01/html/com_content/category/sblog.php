<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
require __DIR__.'/_helper.php';

?>
<?php ?>
<div class="blog<?php echo $this->pageclass_sfx;?> module tit-verde servicos">
	
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1 class="borderHeading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php else : ?>
	<h1 class="borderHeading">		
		<?php echo $this->category->title; ?>
	</h1>
    <div class="row-fluid">
    
    	<?php echo $this->category->description; ?>
       
    </div>
	<?php endif; ?>

	<?php if($this->params->get('page_subheading')): ?>
		<h2 class="secondaryHeading"><?php echo $this->escape($this->params->get('page_subheading')); ?></h2>
	<?php endif; ?>

	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="subtitle">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<?php TemplateContentCategoryHelper::displayCategoryImage( $this->category->getParams()->get('image') ); ?>
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<div class="description">
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
			</div>
		<?php endif; ?>
	<?php endif; ?>

<?php $leadingcount=0 ; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="tile-list-1">
	<div class="items-leading">
		<?php foreach ($this->lead_items as &$item) : ?>
			<div class="tileItem leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('lead');
				?>
			</div>
			<?php
				$leadingcount++;
			?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>
<?php
	$introcount=(count($this->intro_items));
	$counter=0;
?>
<?php if (!empty($this->intro_items)) : ?>

	<?php  foreach ($this->intro_items as $key => &$item) : ?>
		<?php
			$key= ($key-$leadingcount)+1;
			$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
			$row = $counter / $this->columns ;
			

			if ($rowcount==1) : ?>
			<div class="tile-list-1">
            
			<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row ; ?> row-fluid">
			<?php endif; ?>

			<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?> span<?php echo 12/((int) $this->columns);?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
			</div>
			<?php $counter++; ?>
		
			<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
			</div>
			</div>
			<?php endif;  ?>

	<?php endforeach; ?>

<?php endif; ?>


<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
						<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
						<p class="counter">
								<?php echo $this->pagination->getPagesCounter(); ?>
						</p>

				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
<?php  endif; ?>

</div>
