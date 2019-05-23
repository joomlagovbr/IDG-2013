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
<div class="category-list<?php echo $this->pageclass_sfx;?>">

	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1 class="borderHeading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php else : ?>
	<h1 class="borderHeading">		
		<?php echo $this->category->title; ?>
	</h1>
	<?php endif; ?>

	<?php if( $this->params->get('list_show_author') || $this->params->get('list_show_date')) : ?>
		<div class="content-header-options-1 row-fluid">
			<div class="documentByLine">
				<?php if( $this->params->get('list_show_author') ): ?>
				<span class="documentAuthor">
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '<strong>'.TemplateContentCategoryHelper::getAuthor( $this->category ).'</strong>'); ?>
				</span>
				<?php endif; ?>
				<?php if( $this->params->get('list_show_date') && $this->params->get('list_show_author')): ?>
				<span class="separator">|</span>
				<?php endif; ?>
				<?php if( $this->params->get('list_show_date') ): ?>
				<?php
				$created =  JHtml::_('date', $this->category->created_time, JText::_('DATE_FORMAT_LC2'));
				?>
				<span class="documentCreated"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', $created); ?></span>
				<span class="separator">|</span>
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

	<div class="cat-items">
		<?php echo $this->loadTemplate('articles'); ?>
	</div>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="row-fluid container-items-more-cat-children">
		<div class="cat-children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
			<h3>
				<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
			</h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php // Add pagination links ?>
	<?php if (!empty($this->items)) : ?>
		<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">

			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			 	<p class="counter pull-left">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php endif; ?>
	<?php //mantenha a tag de fechamento de formulario abaixo: a chamada inicial encontra-se na default_articles, no caso de itens serem exibidos. ?>
	</form>
	<?php //fim mantenha a tag de fechamento ?>
	<?php  endif; //*/ ?>
</div>
