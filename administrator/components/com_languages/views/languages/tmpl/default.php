<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_languages
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');

$user      = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_languages&task=languages.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'contentList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_languages&view=languages'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif; ?>
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<div class="clearfix"></div>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="contentList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%"  class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>
						<th class="title nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th class="title nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_TITLE_NATIVE', 'a.title_native', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_LANG_TAG', 'a.lang_code', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_LANG_CODE', 'a.sef', $listDirn, $listOrder); ?>
						</th>
						<th width="8%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_LANG_IMAGE', 'a.image', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_LANGUAGES_HEADING_HOMEPAGE', 'l.home', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.lang_id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="11">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$canCreate = $user->authorise('core.create',     'com_languages');
					$canEdit   = $user->authorise('core.edit',       'com_languages');
					$canChange = $user->authorise('core.edit.state', 'com_languages');
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="order nowrap center hidden-phone">
							<?php if ($canChange) :
								$disableClassName = '';
								$disabledLabel	  = '';

								if (!$saveOrder) :
									$disabledLabel    = JText::_('JORDERINGDISABLED');
									$disableClassName = 'inactive tip-top';
								endif; ?>
								<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
									<span class="icon-menu" aria-hidden="true"></span>
								</span>
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
							<?php else : ?>
								<span class="sortable-handler inactive">
									<span class="icon-menu" aria-hidden="true"></span>
								</span>
							<?php endif; ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $item->lang_id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'languages.', $canChange); ?>
						</td>
						<td>
							<span class="editlinktip hasTooltip" title="<?php echo JHtml::_('tooltipText', JText::_('JGLOBAL_EDIT_ITEM'), $item->title, 0); ?>">
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_languages&task=language.edit&lang_id=' . (int) $item->lang_id); ?>"><?php echo $this->escape($item->title); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->title); ?>
							<?php endif; ?>
							</span>
						</td>
						<td class="hidden-phone hidden-tablet">
							<?php echo $this->escape($item->title_native); ?>
						</td>
						<td>
							<?php echo $this->escape($item->lang_code); ?>
						</td>
						<td>
							<?php echo $this->escape($item->sef); ?>
						</td>
						<td class="hidden-phone">
							<?php if ($item->image) : ?>
								<?php echo JHtml::_('image', 'mod_languages/' . $item->image . '.gif', $item->image, null, true); ?>&nbsp;<?php echo $this->escape($item->image); ?>
							<?php else : ?>
								<?php echo JText::_('JNONE'); ?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone">
							<?php echo $this->escape($item->access_level); ?>
						</td>
						<td class="hidden-phone">
							<?php echo ($item->home == '1') ? JText::_('JYES') : JText::_('JNO'); ?>
						</td>
						<td class="hidden-phone hidden-tablet">
							<?php echo $this->escape($item->lang_id); ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
