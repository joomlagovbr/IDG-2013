<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<div id="installer-manage" class="clearfix">
	<form action="<?php echo JRoute::_('index.php?option=com_installer&view=manage'); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
		<?php endif;?>
			<?php if ($this->showMessage) : ?>
				<?php echo $this->loadTemplate('message'); ?>
			<?php endif; ?>
			<?php if ($this->ftp) : ?>
				<?php echo $this->loadTemplate('ftp'); ?>
			<?php endif; ?>
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<div class="clearfix"></div>
			<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_INSTALLER_MSG_MANAGE_NOEXTENSION'); ?>
			</div>
			<?php else : ?>
			<table class="table table-striped" id="manageList">
				<thead>
					<tr>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'status', $listDirn, $listOrder); ?>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_LOCATION', 'client_translated', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_TYPE', 'type_translated', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="hidden-phone">
							<?php echo JText::_('JVERSION'); ?>
						</th>
						<th width="10%" class="hidden-phone hidden-tablet">
							<?php echo JText::_('JDATE'); ?>
						</th>
						<th width="15%" class="hidden-phone hidden-tablet">
							<?php echo JText::_('JAUTHOR'); ?>
						</th>
						<th class="hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_FOLDER', 'folder_translated', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_ID', 'extension_id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; if ($item->status == 2) echo ' protected'; ?>">
						<td>
							<?php echo JHtml::_('grid.id', $i, $item->extension_id); ?>
						</td>
						<td class="center">
							<?php if (!$item->element) : ?>
							<strong>X</strong>
							<?php else : ?>
								<?php echo JHtml::_('InstallerHtml.Manage.state', $item->status, $i, $item->status < 2, 'cb'); ?>
							<?php endif; ?>
						</td>
						<td>
							<label for="cb<?php echo $i; ?>">
								<span class="bold hasTooltip" title="<?php echo JHtml::tooltipText($item->name, $item->description, 0); ?>">
									<?php echo $item->name; ?>
								</span>
							</label>
						</td>
						<td>
							<?php echo $item->client_translated; ?>
						</td>
						<td>
							<?php echo $item->type_translated; ?>
						</td>
						<td class="hidden-phone">
							<?php echo @$item->version != '' ? $item->version : '&#160;'; ?>
						</td>
						<td class="hidden-phone hidden-tablet">
							<?php echo @$item->creationDate != '' ? $item->creationDate : '&#160;'; ?>
						</td>
						<td class="hidden-phone hidden-tablet">
							<span class="editlinktip hasTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_INSTALLER_AUTHOR_INFORMATION'), $item->author_info, 0); ?>">
								<?php echo @$item->author != '' ? $item->author : '&#160;'; ?>
							</span>
						</td>
						<td class="hidden-phone">
							<?php echo $item->folder_translated; ?>
						</td>
						<td class="hidden-phone">
							<?php echo $item->extension_id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHtml::_('form.token'); ?>
		<!-- End Content -->
		</div>
	</form>
</div>
