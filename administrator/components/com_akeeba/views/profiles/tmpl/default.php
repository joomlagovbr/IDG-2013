<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.multiselect');
if (version_compare(JVERSION, '3.0', 'gt'))
{
	JHtml::_('dropdown.init');
	JHtml::_('formbehavior.chosen', 'select');
}

$configurl = base64_encode(JURI::base().'index.php?option=com_akeeba&view=config');
$token = JFactory::getSession()->getFormToken();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />

	<div class="alert alert-info">
		<strong><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?></strong>:
		#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
	</div>

	<?php if(version_compare(JVERSION, '3.0', 'gt')): ?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ?></label>
				<?php echo $this->getModel()->getPagination()->getLimitBox(); ?>
			</div>
		</div>
	<?php endif; ?>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="20px">&nbsp;</th>
				<th width="20px">#</th>
				<th><?php JText::_('PROFILE_COLLABEL_DESCRIPTION'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$i = 1;
		foreach( $this->items as $profile ):
		$id = JHTML::_('grid.id', ++$i, $profile->id);
		$link = 'index.php?option=com_akeeba&amp;view=profiles&amp;task=edit&amp;id='.$profile->id;
		$i = 1 - $i;
		$exportBaseName = F0FStringUtils::toSlug($profile->description);
		?>
			<tr class="row<?php echo $i; ?>">
				<td><?php echo $id; ?></td>
				<td><?php echo $profile->id ?></td>
				<td>
					<button class="btn btn-mini btn-primary" onclick="window.location='index.php?option=com_akeeba&task=switchprofile&profileid=<?php echo $profile->id ?>&returnurl=<?php echo $configurl ?>&<?php echo $token ?>=1'; return false;">
						<i class="icon-cog icon-white"></i>
						<?php echo JText::_('CONFIG_UI_CONFIG'); ?>
					</button>
					&nbsp;
					<button class="btn btn-mini" onclick="window.location='index.php?option=com_akeeba&view=profile&task=read&id=<?php echo $profile->id ?>&basename=<?php echo $exportBaseName?>&format=json&<?php echo $token ?>=1'; return false;">
						<i class="icon-download"></i>
						<?php echo JText::_('COM_AKEEBA_PROFILES_BTN_EXPORT'); ?>
					</button>
					&nbsp;
					<a href="<?php echo $link; ?>">
						<?php echo $profile->description; ?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</form>

<form action="index.php" method="post" name="importForm" enctype="multipart/form-data" id="importForm" class="form form-inline well">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="import" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />

	<input type="file" name="importfile" class="input-medium" />
	<button class="btn btn-success">
		<i class="icon-upload icon-white"></i>
		<?php echo JText::_('COM_AKEEBA_PROFILES_HEADER_IMPORT');?>
	</button>
	<span class="help-inline">
		<?php echo JText::_('COM_AKEEBA_PROFILES_LBL_IMPORT_HELP');?>
	</span>
</form>