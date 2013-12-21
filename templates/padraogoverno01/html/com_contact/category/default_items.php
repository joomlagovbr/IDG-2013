<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_CONTACT_NO_ARTICLES'); ?>	 </p>
<?php else : ?>
<br />
<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
<?php if ($this->params->get('show_pagination_limit')) : ?>
	<fieldset class="filters">
	<legend class="hide hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>

		<p class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</p>
	</fieldset>
<?php endif; ?>
<div class="row-fluid">
<div class="tile-list-1">
	<?php foreach($this->items as $i => $item) : ?>
		<div class="tileItem">
			<div class="tileContent span12">
				<h3 class="tileHeadline">
					<a href="<?php echo JRoute::_(ContactHelperRoute::getContactRoute($item->slug, $item->catid)); ?>">
						<?php echo $item->name; ?></a>
				</h3>
				<p class="description">
					<?php if ($this->params->get('show_position_headings')) : ?>
						<span class="item-position">
							Cargo: <?php echo $item->con_position; ?> | 
						</span>
					<?php endif; ?>
					<?php if ($this->params->get('show_email_headings')) : ?>
						<span class="item-email">
							E-mail: <?php echo $item->email_to; ?> | 
						</span>
					<?php endif; ?>					

					<?php if ($this->params->get('show_telephone_headings')) : ?>
						<span class="item-phone">
							Telefone: <?php echo $item->telephone; ?> | 
						</span>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile_headings')) : ?>
						<span class="item-phone">
							Celular: <?php echo $item->mobile; ?> | 
						</span>
					<?php endif; ?>

					<?php if ($this->params->get('show_fax_headings')) : ?>
					<span class="item-phone">
						Fax: <?php echo $item->fax; ?> | 
					</span>
					<?php endif; ?>

					<?php if ($this->params->get('show_suburb_headings')) : ?>
					<span class="item-suburb">
						Cidade: <?php echo $item->suburb; ?> | 
					</span>
					<?php endif; ?>

					<?php if ($this->params->get('show_state_headings')) : ?>
					<span class="item-state">
						Estado: <?php echo $item->state; ?> | 
					</span>
					<?php endif; ?>

					<?php if ($this->params->get('show_country_headings')) : ?>
					<span class="item-state">
						<?php echo $item->country; ?>
					</span>
					<?php endif; ?>
				</p>
			</div>
		</div>
	<?php endforeach; ?>
</div>
</div>
	

	<?php if ($this->params->get('show_pagination')) : ?>
	<div class="pagination text-center">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="text-center">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</div>
</form>
<?php endif; ?>
