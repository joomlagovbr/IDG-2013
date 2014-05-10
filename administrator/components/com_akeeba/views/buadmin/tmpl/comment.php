<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.framework');

$editor = JFactory::getEditor();
$getText = $editor->getContent('comment');

?>
<form name="adminForm" id="adminForm" action="index.php" method="post" class="form-horizontal">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="buadmin" />
	<input type="hidden" name="id" value="<?php echo $this->record['id'] ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />

	<div class="control-group">
		<label class="control-label" for="description">
			<?php echo JText::_('STATS_LABEL_DESCRIPTION'); ?>
		</label>
		<div class="controls">
			<input type="text" name="description" maxlength="255" size="50"
				value="<?php echo $this->record['description'] ?>" class="input-xxlarge" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="comment">
			<?php echo JText::_('STATS_LABEL_DESCRIPTION'); ?>
		</label>
		<div class="controls">
			<?php echo $editor->display( 'comment',  $this->record['comment'], '550', '400', '60', '20', array() ) ; ?>
		</div>
	</div>
</form>