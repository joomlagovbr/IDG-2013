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
<table border="0" cellpadding="2">
	<tr>
		<td>
		<label for="onmouseover" class="hastip" title="<?php echo WFText::_('WF_LABEL_MOUSEOVER_DESC');?>">
			<?php echo WFText::_('WF_LABEL_MOUSEOVER');?>
		</label>
		</td>
		<td>
		<input id="onmouseover" type="text" value="" class="focus" />
		</td>
	</tr>
	<tr>
		<td>
		<label for="onmouseout" class="hastip" title="<?php echo WFText::_('WF_LABEL_MOUSEOUT_DESC');?>">
			<?php echo WFText::_('WF_LABEL_MOUSEOUT');?>
		</label>
		</td>
		<td>
		<input id="onmouseout" type="text" value="" />
		</td>
	</tr>
</table>