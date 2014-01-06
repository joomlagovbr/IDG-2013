<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined( '_JEXEC' ) or die('RESTRICTED');
$tabs = WFTabs::getInstance();
?>
<form onsubmit="return false;">
	<?php echo $tabs->render(); ?>
	<div class="mceActionPanel">
	<?php if ($this->plugin->getContext() == 'cell') : ?>
	<div>
		<select id="action" name="action">
			<option value="cell"><?php echo WFText::_('WF_TABLE_CELL_CELL');?></option>
			<option value="row"><?php echo WFText::_('WF_TABLE_CELL_ROW');?></option>
			<option value="all"><?php echo WFText::_('WF_TABLE_CELL_ALL');?></option>
		</select>
	</div>
	<?php endif;
	if ($this->plugin->getContext() == 'row') : ?>
	<div>
		<select id="action" name="action">
			<option value="row"><?php echo WFText::_('WF_TABLE_ROW_ROW');?></option>
			<option value="odd"><?php echo WFText::_('WF_TABLE_ROW_ODD');?></option>
			<option value="even"><?php echo WFText::_('WF_TABLE_ROW_EVEN');?></option>
			<option value="all"><?php echo WFText::_('WF_TABLE_ROW_ALL');?></option>
		</select>
	</div>
	<?php endif; ?>
	<button type="submit" id="insert" onclick="TableDialog.insert();"><?php echo WFText::_('WF_LABEL_INSERT');?></button>
	<button type="button" id="cancel"><?php echo WFText::_('WF_LABEL_CANCEL');?></button>
	</div>
</form>
