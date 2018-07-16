<?php

/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
require __DIR__.'/_helper.php';

?>
<div class="categories-list<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1 class="borderHeading">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<?php if( $this->params->get('show_author') || $this->params->get('show_modify_date') ) : ?>
	<?php
		$modified = TemplateContentCategoriesHelper::getLastArticleModifiedDate( $this );
		$author = TemplateContentCategoriesHelper::getAuthor( $this ); ?>
	<?php if(!empty($author) || !empty($modified)):
	?>
	<div class="content-header-options-1 row-fluid">
		<div class="documentByLine">
			<?php if( $this->params->get('show_author') && !empty($author) ): ?>
				<span class="documentAuthor"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '<strong>'.$author.'</strong>'); ?></span>
				<?php if( $this->params->get('show_modify_date') && !empty($modified)): ?>
				<span class="separator">|</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php
			if($modified != ''):
			?>
			<span class="documentModified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', $modified); ?></span>
			<?php
			endif;
			?>
		</div>
	</div>
	<?php endif; ?>
<?php endif; ?>

<?php if ($this->params->get('show_base_description')) : ?>
	<div class="subtitle">
		<?php if($this->params->get('categories_description')) : ?>
			<?php echo  JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_content.categories'); ?>
		<?php  else: ?>
			<?php //Otherwise get one from the database if it exists. ?>
			<?php  if ($this->parent->description) : ?>
					<?php  echo JHtml::_('content.prepare', $this->parent->description, '', 'com_content.categories'); ?>
			<?php  endif; ?>
		<?php  endif; ?>
	</div>
<?php endif; ?>

<div class="tile-list-1">
	<div class="items-leading">
<?php
echo $this->loadTemplate('items');
?>
	</div>
</div>

</div>
