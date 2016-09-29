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

$input     = JFactory::getApplication()->input;
$field     = $input->getCmd('field');
$function  = 'jSelectUser_'.$field;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=users&layout=modal&tmpl=component&groups=' . $input->get('groups', '', 'BASE64') . '&excluded=' . $input->get('excluded', '', 'BASE64'));?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_USERS_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			<button type="button" data-user-value="" data-user-name="<?php echo $this->escape(JText::_('JLIB_FORM_SELECT_USER')); ?>" data-user-field="<?php echo $this->escape($field);?>"
				onclick="if (window.parent) window.parent.jSelectUser(this);"><?php echo JText::_('JOPTION_NO_USER')?></button>
		</div>

		<div class="filter-select">
			<label for="filter_group_id">
				<?php echo JText::_('COM_USERS_FILTER_USER_GROUP'); ?>
			</label>
			<?php echo JHtml::_('access.usergroup', 'filter_group_id', $this->state->get('filter.group_id')); ?>

			<button type="submit" id="filter-go">
				<?php echo JText::_('JSUBMIT'); ?></button>
		</div>
	</fieldset>

	<table class="adminlist modal">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width=25">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width=25">
					<?php echo JText::_('COM_USERS_HEADING_GROUPS'); ?>
				</th>
			</tr>
		</thead>

		<tbody>
		<?php
			$i = 0;
			foreach ($this->items as $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a class="pointer"
						data-user-value="<?php echo $item->id; ?>" data-user-name="<?php echo $this->escape($item->name); ?>" data-user-field="<?php echo $this->escape($field);?>"
						onclick="if (window.parent) window.parent.jSelectUser(this);">
						<?php echo $item->name; ?></a>
				</td>
				<td class="center">
					<?php echo $item->username; ?>
				</td>
				<td class="title">
					<?php echo nl2br($item->group_names); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
