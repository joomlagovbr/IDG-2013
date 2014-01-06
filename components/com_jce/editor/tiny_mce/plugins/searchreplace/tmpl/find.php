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

defined('WF_EDITOR') or die('RESTRICTED');
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td>
			<label for="find_panel_searchstring"><?php echo WFText::_('WF_SEARCHREPLACE_FINDWHAT');?></label>
			<input type="text" id="find_panel_searchstring" style="width: 200px" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label><?php echo WFText::_('WF_SEARCHREPLACE_DIRECTION');?></label>
			<input id="find_panel_backwardsu" name="find_panel_backwards" class="radio" type="radio" />
			<label for="find_panel_backwardsu"><?php echo WFText::_('WF_SEARCHREPLACE_UP');?></label>
			<input id="find_panel_backwardsd" name="find_panel_backwards" class="radio" type="radio" checked="checked" />
			<label for="find_panel_backwardsd"><?php echo WFText::_('WF_SEARCHREPLACE_DOWN');?></label>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input id="find_panel_casesensitivebox" class="checkbox" type="checkbox" />
			<label for="find_panel_casesensitivebox"><?php echo WFText::_('WF_SEARCHREPLACE_MCASE');?></label>
		</td>
	</tr>
</table>
