<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Template.hathor
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.multiselect');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$canOrder  = $user->authorise('core.edit.state', 'com_banners.category');
$saveOrder = $listOrder == 'ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_banners&view=banners'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Banners_Search_in_title'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<div class="filter-select">
			<label class="selectlabel" for="filter_published">
				<?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?>
			</label>
			<select name="filter_published" id="filter_published">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('COM_BANNERS_SELECT_CLIENT'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id">
				<option value=""><?php echo JText::_('COM_BANNERS_SELECT_CLIENT');?></option>
				<?php echo JHtml::_('select.options', BannersHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>

			<label class="selectlabel" for="filter_category_id">
				<?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?>
			</label>
			<select name="filter_category_id" id="filter_category_id">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_banners'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>

			<label class="selectlabel" for="filter_language">
				<?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?>
			</label>
			<select name="filter_language" id="filter_language">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>

			<button type="submit" id="filter-go">
				<?php echo JText::_('JSUBMIT'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap state-col">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width-5">
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_STICKY', 'sticky', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_CLIENT', 'client_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap title category-col">
					<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap ordering-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) : ?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'banners.saveorder'); ?>
					<?php endif;?>
				</th>
				<th class="nowrap width-5">
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_IMPRESSIONS', 'impmade', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'COM_BANNERS_HEADING_CLICKS', 'clicks', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width-5">
					<?php echo JText::_('COM_BANNERS_HEADING_METAKEYWORDS'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('COM_BANNERS_HEADING_PURCHASETYPE'); ?>
				</th>
				<th class="language-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering   = ($listOrder == 'ordering');
			$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_banners&task=edit&type=other&cid[]=' . $item->catid);
			$canCreate  = $user->authorise('core.create',     'com_banners.category.' . $item->catid);
			$canEdit    = $user->authorise('core.edit',       'com_banners.category.' . $item->catid);
			$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange  = $user->authorise('core.edit.state', 'com_banners.category.' . $item->catid) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'banners.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_banners&task=banner.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
							<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
					<p class="smallsub">
						<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'banners.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('banner.pinned', $item->sticky, $i, $canChange);?>
				</td>
				<td class="center">
					<?php echo $item->client_name;?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->category_title); ?>
				</td>
				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) : ?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i - 1]->catid == $item->catid), 'banners.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i + 1]->catid == $item->catid), 'banners.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i - 1]->catid == $item->catid), 'banners.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i + 1]->catid == $item->catid), 'banners.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" value="<?php echo $item->ordering;?>" <?php echo $disabled; ?> class="text-area-order" title="<?php echo $item->name; ?> order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo JText::sprintf('COM_BANNERS_IMPRESSIONS', $item->impmade, $item->imptotal ? $item->imptotal : JText::_('COM_BANNERS_UNLIMITED'));?>
				</td>
				<td class="center">
					<?php echo $item->clicks;?> -
					<?php echo sprintf('%.2f%%', $item->impmade ? 100 * $item->clicks / $item->impmade : 0);?>
				</td>
				<td>
					<?php echo $item->metakey; ?>
				</td>
				<td class="center">
					<?php if ($item->purchase_type < 0):?>
						<?php echo JText::sprintf('COM_BANNERS_DEFAULT', ($item->client_purchase_type > 0) ? JText::_('COM_BANNERS_FIELD_VALUE_'.$item->client_purchase_type) : JText::_('COM_BANNERS_FIELD_VALUE_'.$this->state->params->get('purchase_type')));?>
					<?php else:?>
						<?php echo JText::_('COM_BANNERS_FIELD_VALUE_'.$item->purchase_type);?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php if ($item->language == '*'):?>
						<?php echo JText::alt('JALL', 'language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->pagination->getListFooter(); ?>
	<div class="clr"> </div>

	<?php //Load the batch processing form. ?>
	<?php if ($user->authorise('core.create', 'com_banners')
		&& $user->authorise('core.edit', 'com_banners')
		&& $user->authorise('core.edit.state', 'com_banners')) : ?>
		<?php echo JHtml::_(
			'bootstrap.renderModal',
			'collapseModal',
			array(
				'title' => JText::_('COM_BANNERS_BATCH_OPTIONS'),
				'footer' => $this->loadTemplate('batch_footer')
			),
			$this->loadTemplate('batch_body')
		); ?>
	<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
