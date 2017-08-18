<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Note that this layout opens a div with the page class suffix. If you do not use the category children
 * layout you need to close this div either by overriding this file or in your main layout.
 */
$params    = $displayData->params;
$category  = $displayData->get('category');
$extension = $category->extension;
$canEdit   = $params->get('access-edit');
$className = substr($extension, 4);

$dispatcher = JEventDispatcher::getInstance();

$category->text = $category->description;
$dispatcher->trigger('onContentPrepare', array($extension . '.categories', &$category, &$params, 0));
$category->description = $category->text;

$results = $dispatcher->trigger('onContentAfterTitle', array($extension . '.categories', &$category, &$params, 0));
$afterDisplayTitle = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentBeforeDisplay', array($extension . '.categories', &$category, &$params, 0));
$beforeDisplayContent = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentAfterDisplay', array($extension . '.categories', &$category, &$params, 0));
$afterDisplayContent = trim(implode("\n", $results));

/**
 * This will work for the core components but not necessarily for other components
 * that may have different pluralisation rules.
 */
if (substr($className, -1) === 's')
{
	$className = rtrim($className, 's');
}
$tagsData = $category->tags->itemTags;
?>
<div>
	<div class="<?php echo $className .'-category' . $displayData->pageclass_sfx; ?>">
		<?php if ($params->get('show_page_heading')) : ?>
			<h1>
				<?php echo $displayData->escape($params->get('page_heading')); ?>
			</h1>
		<?php endif; ?>

		<?php if ($params->get('show_category_title', 1)) : ?>
			<h2>
				<?php echo JHtml::_('content.prepare', $category->title, '', $extension . '.category.title'); ?>
			</h2>
		<?php endif; ?>
		<?php echo $afterDisplayTitle; ?>

		<?php if ($params->get('show_cat_tags', 1)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
		<?php endif; ?>

		<?php if ($beforeDisplayContent || $afterDisplayContent || $params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
			<div class="category-desc">
				<?php if ($params->get('show_description_image') && $category->getParams()->get('image')) : ?>
					<img src="<?php echo $category->getParams()->get('image'); ?>" alt="<?php echo htmlspecialchars($category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"/>
				<?php endif; ?>
				<?php echo $beforeDisplayContent; ?>
				<?php if ($params->get('show_description') && $category->description) : ?>
					<?php echo JHtml::_('content.prepare', $category->description, '', $extension . '.category.description'); ?>
				<?php endif; ?>
				<?php echo $afterDisplayContent; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>
		<?php echo $displayData->loadTemplate($displayData->subtemplatename); ?>

		<?php if ($displayData->maxLevel != 0 && $displayData->get('children')) : ?>
			<div class="cat-children">
				<?php if ($params->get('show_category_heading_title_text', 1) == 1) : ?>
					<h3>
						<?php echo JText::_('JGLOBAL_SUBCATEGORIES'); ?>
					</h3>
				<?php endif; ?>
				<?php echo $displayData->loadTemplate('children'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

