<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
?>
<div class="span10 tileContent">

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>

	<?php  if (@isset($images->image_intro) && @!empty($images->image_intro) && @strpos($images->image_intro, 'www.youtube') === false) : ?>
	<div class="tileImage">
			
		<?php if($params->get('access-view')): ?>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
		<?php endif; ?>
		
		<img
		<?php if ($images->image_intro_caption):
			echo ' title="' .htmlspecialchars($images->image_intro_caption) .'"';
		endif; ?>
		class="tileImage" src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" height="86" width="128" />							
		
		<?php if($params->get('access-view')): ?>
		</a>
		<?php endif; ?>
		
	</div>
	<?php endif; ?>

	<?php if(@isset($this->item->xreference) && @$this->item->xreference != ''): ?>
	<span class="subtitle"><?php echo trim($this->item->xreference); ?></span>
	<?php endif; ?>

	<?php if ($params->get('show_title')) : ?>
		<h2 class="tileHeadline">
			<?php if ( $params->get('access-view') && $params->get('show_readmore') && $this->item->readmore) : ?>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
				<?php echo $this->escape($this->item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>

	<?php if ($params->get('show_intro')) : ?>
		<?php echo $this->item->event->beforeDisplayContent; ?>
		<div class="description">
			<?php //echo $this->item->introtext; ?>
			<?php echo TemplateContentCategoryHelper::getArticleIntro( $this->item ); ?>
			<?php if ($canEdit) : ?>
			<ul class="actions">
				<?php if ($canEdit) : ?>
				<li class="edit-icon">
					<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
				</li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>		
		</div>
	<?php endif; ?>

	<?php if($this->item->metakey != ''): ?>
		<span class="keywords">
		Tags:
		<?php TemplateContentCategoryHelper::displayMetakeyLinks($this->item->metakey); ?>
		</span>
	<?php endif; ?>

	<?php if ($this->item->state == 0) : ?>
	</div>
	<?php endif; ?>
</div>
<div class="span2 tileInfo">
	<ul>
		<?php
		// var_dump($this->item);
		$author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
		?>
		<?php if ($params->get('show_author') && !empty($this->item->author )): ?>
		<li class="hide"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></li>
		<?php endif; ?>
		<li class="hide"><?php echo (($author->state == 1)? 'publicado' : 'n&atilde;o publicado' ) ?></li>

		<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'd/m/y'); ?></li>
		<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'H\hi'); ?></li>
	</ul>							            								
</div>
<?php echo $this->item->event->afterDisplayContent; ?>
