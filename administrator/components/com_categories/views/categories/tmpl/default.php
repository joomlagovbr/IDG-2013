<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\String\Inflector;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.lft' && strtolower($listDirn) == 'asc');
$parts     = explode('.', $extension);
$component = $parts[0];
$section   = null;
$columns   = 7;

if (count($parts) > 1)
{
	$section = $parts[1];

	$inflector = Inflector::getInstance();

	if (!$inflector->isPlural($section))
	{
		$section = $inflector->toPlural($section);
	}
}

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_categories&task=categories.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_categories&view=categories'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="categoryList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_published')) :
							$columns++; ?>
							<th width="1%" class="nowrap center hidden-phone hidden-tablet">
								<i class="icon-publish hasTooltip" title="<?php echo JText::_('COM_CATEGORY_COUNT_PUBLISHED_ITEMS'); ?>"></i>
							</th>
						<?php endif;?>
						<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_unpublished')) :
							$columns++; ?>
							<th width="1%" class="nowrap center hidden-phone hidden-tablet">
								<i class="icon-unpublish hasTooltip" title="<?php echo JText::_('COM_CATEGORY_COUNT_UNPUBLISHED_ITEMS'); ?>"></i>
							</th>
						<?php endif;?>
						<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_archived')) :
							$columns++; ?>
							<th width="1%" class="nowrap center hidden-phone hidden-tablet">
								<i class="icon-archive hasTooltip" title="<?php echo JText::_('COM_CATEGORY_COUNT_ARCHIVED_ITEMS'); ?>"></i>
							</th>
						<?php endif;?>
						<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_trashed')) :
							$columns++; ?>
							<th width="1%" class="nowrap center hidden-phone hidden-tablet">
								<i class="icon-trash hasTooltip" title="<?php echo JText::_('COM_CATEGORY_COUNT_TRASHED_ITEMS'); ?>"></i>
							</th>
						<?php endif;?>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
						</th>
						<?php if ($this->assoc) :
							$columns++; ?>
							<th width="5%" class="nowrap hidden-phone hidden-tablet">
								<?php echo JHtml::_('searchtools.sort', 'COM_CATEGORY_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="<?php echo $columns; ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<?php
						$orderkey   = array_search($item->id, $this->ordering[$item->parent_id]);
						$canEdit    = $user->authorise('core.edit',       $extension . '.category.' . $item->id);
						$canCheckin = $user->authorise('core.admin',      'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
						$canEditOwn = $user->authorise('core.edit.own',   $extension . '.category.' . $item->id) && $item->created_user_id == $userId;
						$canChange  = $user->authorise('core.edit.state', $extension . '.category.' . $item->id) && $canCheckin;

						// Get the parents of item for sorting
						if ($item->level > 1)
						{
							$parentsStr = "";
							$_currentParentId = $item->parent_id;
							$parentsStr = " " . $_currentParentId;
							for ($i2 = 0; $i2 < $item->level; $i2++)
							{
								foreach ($this->ordering as $k => $v)
								{
									$v = implode("-", $v);
									$v = "-" . $v . "-";
									if (strpos($v, "-" . $_currentParentId . "-") !== false)
									{
										$parentsStr .= " " . $k;
										$_currentParentId = $k;
										break;
									}
								}
							}
						}
						else
						{
							$parentsStr = "";
						}
						?>
						<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->parent_id; ?>" item-id="<?php echo $item->id ?>" parents="<?php echo $parentsStr ?>" level="<?php echo $item->level ?>">
							<td class="order nowrap center hidden-phone">
								<?php
								$iconClass = '';
								if (!$canChange)
								{
									$iconClass = ' inactive';
								}
								elseif (!$saveOrder)
								{
									$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?php echo $iconClass ?>">
									<span class="icon-menu"></span>
								</span>
								<?php if ($canChange && $saveOrder) : ?>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey + 1; ?>" />
								<?php endif; ?>
							</td>
							<td class="center">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
							<td class="center">
								<div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->published, $i, 'categories.', $canChange); ?>
									<?php
									if ($canChange)
									{
										// Create dropdown items
										JHtml::_('actionsdropdown.' . ((int) $item->published === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'categories');
										JHtml::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'categories');

										// Render dropdown list
										echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
									}
									?>
								</div>
							</td>
							<td>
								<?php echo JLayoutHelper::render('joomla.html.treeprefix', array('level' => $item->level)); ?>
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_categories&task=category.edit&id=' . $item->id . '&extension=' . $extension); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>
								<span class="small" title="<?php echo $this->escape($item->path); ?>">
									<?php if (empty($item->note)) : ?>
										<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
									<?php else : ?>
										<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
									<?php endif; ?>
								</span>
							</td>
							<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_published')) : ?>
								<td class="center btns hidden-phone hidden-tablet">
									<a class="badge <?php if ($item->count_published > 0) echo "badge-success"; ?>" title="<?php echo JText::_('COM_CATEGORY_COUNT_PUBLISHED_ITEMS');?>" href="<?php echo JRoute::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[category_id]=' . (int) $item->id . '&filter[published]=1' . '&filter[level]=' . (int) $item->level);?>">
										<?php echo $item->count_published; ?></a>
								</td>
							<?php endif;?>
							<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_unpublished')) : ?>
								<td class="center btns hidden-phone hidden-tablet">
									<a class="badge <?php if ($item->count_unpublished > 0) echo "badge-important"; ?>" title="<?php echo JText::_('COM_CATEGORY_COUNT_UNPUBLISHED_ITEMS');?>" href="<?php echo JRoute::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[category_id]=' . (int) $item->id . '&filter[published]=0' . '&filter[level]=' . (int) $item->level);?>">
										<?php echo $item->count_unpublished; ?></a>
								</td>
							<?php endif;?>
							<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_archived')) : ?>
								<td class="center btns hidden-phone hidden-tablet">
									<a class="badge <?php if ($item->count_archived > 0) echo "badge-info"; ?>" title="<?php echo JText::_('COM_CATEGORY_COUNT_ARCHIVED_ITEMS');?>" href="<?php echo JRoute::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[category_id]=' . (int) $item->id . '&filter[published]=2' . '&filter[level]=' . (int) $item->level);?>">
										<?php echo $item->count_archived; ?></a>
								</td>
							<?php endif;?>
							<?php if (isset($this->items[0]) && property_exists($this->items[0], 'count_trashed')) : ?>
								<td class="center btns hidden-phone hidden-tablet">
									<a class="badge <?php if ($item->count_trashed > 0) echo "badge-inverse"; ?>" title="<?php echo JText::_('COM_CATEGORY_COUNT_TRASHED_ITEMS');?>" href="<?php echo JRoute::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[category_id]=' . (int) $item->id . '&filter[published]=-2' . '&filter[level]=' . (int) $item->level);?>">
										<?php echo $item->count_trashed; ?></a>
								</td>
							<?php endif;?>

							<td class="small hidden-phone">
								<?php echo $this->escape($item->access_level); ?>
							</td>
							<?php if ($this->assoc) : ?>
								<td class="hidden-phone hidden-tablet">
									<?php if ($item->association): ?>
										<?php echo JHtml::_('CategoriesAdministrator.association', $item->id, $extension); ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>
							<td class="small nowrap hidden-phone">
								<?php if ($item->language == '*') : ?>
									<?php echo JText::alt('JALL', 'language'); ?>
								<?php else: ?>
									<?php echo $item->language_title ? JHtml::_('image', 'mod_languages/' . $item->language_image . '.gif', $item->language_title, array('title' => $item->language_title), true) . '&nbsp;' . $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
								<?php endif; ?>
							</td>
							<td class="hidden-phone">
								<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt); ?>">
									<?php echo (int) $item->id; ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php // Load the batch processing form. ?>
			<?php if ($user->authorise('core.create', $extension)
				&& $user->authorise('core.edit', $extension)
				&& $user->authorise('core.edit.state', $extension)) : ?>
				<?php echo JHtml::_(
						'bootstrap.renderModal',
						'collapseModal',
						array(
							'title' => JText::_('COM_CATEGORIES_BATCH_OPTIONS'),
							'footer' => $this->loadTemplate('batch_footer')
						),
						$this->loadTemplate('batch_body')
					); ?>
			<?php endif; ?>
		<?php endif; ?>

		<input type="hidden" name="extension" value="<?php echo $extension; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
