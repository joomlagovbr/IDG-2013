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
<fieldset>
	<legend>
		<?php echo WFText::_('WF_TABLE_MERGE_CELLS_TITLE');?>
	</legend>
	<table border="0" cellpadding="0" cellspacing="3" width="100%">
		<tr>
			<td><?php echo WFText::_('WF_TABLE_COLS');?>:</td>
			<td align="right">
			<input type="text" id="numcols" value="" class="number min1 mceFocus" style="width: 30px" />
			</td>
		</tr>
		<tr>
			<td><?php echo WFText::_('WF_TABLE_ROWS');?>:</td>
			<td align="right">
			<input type="text" id="numrows" value="" class="number min1" style="width: 30px" />
			</td>
		</tr>
	</table>
</fieldset>