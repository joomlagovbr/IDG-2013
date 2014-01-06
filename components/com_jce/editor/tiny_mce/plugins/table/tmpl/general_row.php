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

defined('_JEXEC') or die('RESTRICTED');
?>
<h4><?php echo WFText::_('WF_TABLE_GENERAL_PROPS');?></h4>
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td><label for="rowtype">
			<?php echo WFText::_('WF_TABLE_ROWTYPE');?></label></td>
		<td class="col2">
		<select id="rowtype" class="mceFocus">
			<option value="thead"><?php echo WFText::_('WF_TABLE_THEAD');?></option>
			<option value="tbody"><?php echo WFText::_('WF_TABLE_TBODY');?></option>
			<option value="tfoot"><?php echo WFText::_('WF_TABLE_TFOOT');?></option>
		</select></td>
	</tr>
	<tr>
		<td><label for="align">
			<?php echo WFText::_('WF_TABLE_ALIGN');?></label></td>
		<td class="col2">
		<select id="align" >
			<option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
			<option value="center"><?php echo WFText::_('WF_TABLE_ALIGN_MIDDLE');?></option>
			<option value="left"><?php echo WFText::_('WF_TABLE_ALIGN_LEFT');?></option>
			<option value="right"><?php echo WFText::_('WF_TABLE_ALIGN_RIGHT');?></option>
		</select></td>
	</tr>
	<tr>
		<td><label for="valign">
			<?php echo WFText::_('WF_TABLE_VALIGN');?></label></td>
		<td class="col2">
		<select id="valign" >
			<option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
			<option value="top"><?php echo WFText::_('WF_TABLE_ALIGN_TOP');?></option>
			<option value="middle"><?php echo WFText::_('WF_TABLE_ALIGN_MIDDLE');?></option>
			<option value="bottom"><?php echo WFText::_('WF_TABLE_ALIGN_BOTTOM');?></option>
		</select></td>
	</tr>
	<tr>
		<td><label for="height">
			<?php echo WFText::_('WF_TABLE_HEIGHT');?></label></td>
		<td class="col2">
		<input type="text" id="height" value=""
		size="4" maxlength="4" onchange="TableDialog.changedSize();" />
		</td>
	</tr>
</table>