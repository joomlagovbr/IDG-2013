<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

defined('_JEXEC') or die();

// Include tooltip support
JLoader::import('joomla.html.html');
JHtml::_('behavior.framework');
JHTML::_('behavior.tooltip');

if( empty($this->item) )
{
	$id = 0;
	$description = '';
}
else
{
	$id = $this->item->id;
	$description = $this->item->description;
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />
	
	<div class="control-group">
		<label class="control-label" for="description">
			<?php echo JHTML::_('tooltip', JText::_('PROFILE_LABEL_DESCRIPTION_TOOLTIP'), '', '', JText::_('PROFILE_LABEL_DESCRIPTION')) ?>
		</label>
		<div class="controls">
			<input type="text" name="description" class="span6" id="description" value="<?php echo $description; ?>" />
		</div>
	</div>
</form>