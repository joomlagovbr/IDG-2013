<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

defined('_JEXEC') or die();
if(empty($this->tag)) $this->tag = null;

JHtml::_('behavior.framework');
?>
<?php if(count($this->logs)): ?>
<form name="adminForm" id="adminForm" action="index.php" method="post" class="form-inline">
	<input name="option" value="com_akeeba" type="hidden" />
	<input name="view" value="log" type="hidden" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />
	<fieldset>
		<label for="tag"><?php echo JText::_('LOG_CHOOSE_FILE_TITLE'); ?></label>
		<?php echo JHTML::_('select.genericlist', $this->logs, 'tag', 'onchange=submitform()', 'value', 'text', $this->tag, 'tag') ?>

		<?php if(!empty($this->tag)): ?>
		<button class="btn btn-primary" onclick="window.location='<?php echo JURI::base(); ?>index.php?option=com_akeeba&view=log&task=download&tag=<?php echo urlencode($this->tag); ?>'; return false;">
			<i class="icon-download-alt icon-white"></i>
			<?php echo JText::_('LOG_LABEL_DOWNLOAD'); ?>
		</button>
		<?php endif; ?>

		<?php if(!empty($this->tag)): ?>
		<br/>
		<hr/>
		<iframe
			src="<?php echo JURI::base(); ?>index.php?option=com_akeeba&view=log&task=iframe&layout=raw&tag=<?php echo urlencode($this->tag); ?>"
			width="99%" height="400px">
		</iframe>
		<?php endif; ?>

	</fieldset>
</form>
<?php else: ?>
<div class="alert alert-error alert-block">
	<?php echo JText::_('LOG_NONE_FOUND') ?>
</div>
<?php endif; ?>