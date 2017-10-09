<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$object = (object) $this->item;
// Create a shortcut for params.
$params = &$object->params;
$images = json_decode($object->images);
$url = json_decode($object->urls);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
?>
<div class="span11 tileContent">

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
		<div class="outstanding-header">
			<h2 class="outstanding-title">
				<?php if ( $params->get('access-view') && $params->get('show_readmore') && $this->item->readmore) : ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
					<?php echo ($this->item->xreference)? $this->item->xreference :  $this->escape($this->item->title); ?></a>
				<?php else : ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
					<?php echo ($this->item->xreference)? $this->item->xreference :  $this->escape($this->item->title); ?></a>
				<?php endif; ?>
			</h2>
		</div>
	<?php endif; ?>

	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>

	<?php if ($params->get('show_intro')) : ?>
		<?php echo $this->item->event->beforeDisplayContent; ?>
		<div class="description">
			<?php //echo $this->item->introtext; ?>
			<?php echo ($this->item->metadesc)? $this->item->metadesc :  TemplateContentCategoryHelper::getArticleIntro( $this->item ); ?>
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
<?php if ($params->get('show_readmore')) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
	?>
	<div class="outstanding-footer">
		<?php if ($url->urla){ ?>
		<a title="<?php echo ($this->item->xreference)? $this->item->xreference :  $this->escape($this->item->title); ?>" href="<?php echo $url->urla; ?>" target="_blank">
			<?php } else {?>
			<a title="<?php echo ($this->item->xreference)? $this->item->xreference :  $this->escape($this->item->title); ?>" href="<?php echo $link; ?>" class="outstanding-link">
				<?php } ?>
				<span class="text">VEJA MAIS</span>
				<span class="icon-box">                                          
					<i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
				</span>
			</a>	
	</div>
<?php endif; ?>
<?php echo $this->item->event->afterDisplayContent; ?>
