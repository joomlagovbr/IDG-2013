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
<div class="blog<?php echo $this->pageclass_sfx;?>">
	
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1 class="borderHeading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php else : ?>
	<h1 class="borderHeading">		
		<?php echo $this->category->title; ?>
	</h1>
	<?php endif; ?>

	<?php if( $this->params->get('show_author') || $this->params->get('show_create_date') || $this->params->get('show_modify_date')) : ?>
		<div class="content-header-options-1 row-fluid">
			<div class="documentByLine">
				<?php if( $this->params->get('show_author') ): ?>
					<span class="documentAuthor"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '<strong>'.TemplateContentCategoryHelper::getAuthor( $this->category ).'</strong>'); ?></span>				
					<?php if( $this->params->get('show_create_date') || $this->params->get('show_modify_date')): ?>
					<span class="separator">|</span>
					<?php endif; ?>
				<?php endif; ?>
				<?php if( $this->params->get('show_create_date') ): ?>
					<?php
					$created =  JHtml::_('date', $this->category->created_time, JText::_('DATE_FORMAT_LC2'));
					?>
					<span class="documentCreated"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $created); ?></span>	
					<?php if( $this->params->get('show_modify_date')): ?>
					<span class="separator">|</span>
					<?php endif; ?>
				<?php endif; ?>
				<?php if( $this->params->get('show_modify_date') ): ?>
					<?php 
					$modified = TemplateContentCategoryHelper::getLastArticleModifiedDate( $this->category, $this->children );
					?>
					<span class="documentModified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', $modified); ?></span>
				<?php endif; ?>			
			</div>
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

	<?php foreach ($this->intro_items as $key => &$item) : ?>
		<?php
			$key= ($key-$leadingcount)+1;
			$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
			$row = $counter / $this->columns ;

			if ($rowcount==1) : ?>
			<div class="tile-list-1">
			<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row ; ?> row-fluid">
			<?php endif; ?>

			<div class="tileItem item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?> span<?php echo 12/((int) $this->columns);?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
			</div>
			<?php $counter++; ?>
		
			<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
			</div>
			</div>
			<?php endif; ?>

	<?php endforeach; ?>

<?php endif; ?>

<?php if (!empty($this->link_items) || (!empty($this->children[$this->category->id])&& $this->maxLevel != 0)) :
	
	if (!empty($this->link_items) && !empty($this->children[$this->category->id])&& $this->maxLevel != 0)
		$span = 'span6';
	else
		$span = 'span12';
?>
<div class="row-fluid container-items-more-cat-children">
	<?php if (!empty($this->link_items)) : ?>
		<div class="<?php echo $span ?>">
			<?php echo $this->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
		<div class="cat-children <?php echo $span ?>">
		<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
		<h3>
		<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
		</h3>
		<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>	
</div>
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
