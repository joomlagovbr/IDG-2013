<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
// JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
	<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
		<div class="row-fluid row-busca">
			<fieldset class="filters alert alert-info">

				<?php if ($this->params->get('filter_field') != 'hide') :?>
					<div class="row-fluid row-busca-f">
						<input type="text" name="filter-search" id="filter-search" class="span8 input-busca"
						onchange="document.adminForm.submit();" value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
						title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"
						placeholder="<?php echo JText::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>" />
						<button type="submit" class="acao-busca button btn btn-primary"/>Buscar</button>
						<button class="acao-busca button btn btn-warning" onclick="document.getElementById('filter-search').value='';this.form.submit();">
							<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
						</button>
					</div>
				<?php endif; ?>



				<input type="hidden" name="filter_order" value="" />
				<input type="hidden" name="filter_order_Dir" value="" />
				<input type="hidden" name="limitstart" value="" />
			</fieldset>
		</div>
	<?php endif; ?>
	<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>

	<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide') :?>
		<div class="row-fluid row-busca">
			<fieldset class="filters alert alert-info">

				<div class="row-fluid row-busca-f">
					<input type="text" name="filter-search" id="filter-search" class="span8 input-busca"
					onchange="document.adminForm.submit();" value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
					title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"
					placeholder="<?php echo JText::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>" />
					<button type="submit" class="acao-busca button btn btn-primary"/>Buscar</button>
					<button class="acao-busca button btn btn-warning" onclick="document.getElementById('filter-search').value='';this.form.submit();">
						<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
					</button>
				</div>

				<input type="hidden" name="filter_order" value="" />
				<input type="hidden" name="filter_order_Dir" value="" />
				<input type="hidden" name="limitstart" value="" />
			</fieldset>
		</div>
	<?php endif; ?>
	<div class="tile-list-1">
		<?php foreach ($this->items as $i => $article):
			$images = json_decode($article->images);
			// var_dump($article);
			// var_dump($this->category);
			// die;
			?>
			<div class="tileItem">
				<div class="span9 tileContent">
					<?php  if (@isset($images->image_intro) && @!empty($images->image_intro) && @strpos($images->image_intro, 'www.youtube') === false) : ?>
					<div class="tileImage">
						<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
							<img
							<?php if ($images->image_intro_caption):
								echo ' title="' .htmlspecialchars($images->image_intro_caption) .'"';
							endif; ?>
							class="tileImage" src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" height="86" width="128" />
						</a>
					</div>
					<?php endif; ?>

					<span class="subtitle"><?php echo trim($article->xreference); ?></span>
					<h2 class="tileHeadline">
	              		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"><?php echo $article->title ?></a>
	          		</h2>
	          		<span class="description">
	          			<?php echo TemplateContentCategoryHelper::getArticleIntro( $article ); ?>
						<?php if ($article->params->get('access-edit')) : ?>
						<ul class="actions">
							<li class="edit-icon">
								<?php echo JHtml::_('icon.edit', $article, $params); ?>
							</li>
						</ul>
						<?php endif; ?>
	          		</span>
	          		<?php if($article->metakey != ''): ?>
	          		<span class="keywords">
	                	Tags:
	                    <?php TemplateContentCategoryHelper::displayMetakeyLinks($article->metakey); ?>
	                </span>
	              	<?php endif; ?>
				</div>
				<div class="span3 tileInfo">
					<ul>
						<?php
						$author = $article->created_by_alias ? $article->created_by_alias : $article->author;
						?>
						<li class="hide"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></li>
						<li class="hide"><?php echo (($article->state == 1)? 'publicado' : 'n&atilde;o publicado' ) ?></li>

						<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $article->publish_up, 'd/m/y'); ?></li>
						<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $article->publish_up, 'H\hi'); ?></li>
						<li><i class="icon-fixed-width icon-list"></i>&nbsp;<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($article->catid)); ?>"><?php echo $article->category_title ?></a></li>
					</ul>
				</div>
			</div>
			<!-- div.tileItem -->
		<?php endforeach; ?>
	</div>
<?php endif; ?>
