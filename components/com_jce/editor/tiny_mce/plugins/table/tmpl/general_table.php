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
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <tr>
        <td><label id="colslabel" for="cols">
                <?php echo WFText::_('WF_TABLE_COLS');?></label></td>
        <td>
            <input id="cols" type="text" value="" size="3"
                   maxlength="3" class="required number min1 mceFocus" />
        </td>
        <td><label id="rowslabel" for="rows">
                <?php echo WFText::_('WF_TABLE_ROWS');?></label></td>
        <td>
            <input id="rows" type="text" value="" size="3"
                   maxlength="3" class="required number min1" />
        </td>
    </tr>
    <tr>
        <td><label id="cellpaddinglabel" for="cellpadding">
                <?php echo WFText::_('WF_TABLE_CELLPADDING');?></label></td>
        <td>
            <input id="cellpadding" type="text" value=""
                   size="3" maxlength="3" class="number" />
        </td>
        <td><label id="cellspacinglabel" for="cellspacing">
                <?php echo WFText::_('WF_TABLE_CELLSPACING');?></label></td>
        <td>
            <input id="cellspacing" type="text" value=""
                   size="3" maxlength="3" class="number" />
        </td>
    </tr>
    <tr>
        <td><label id="alignlabel" for="align">
                <?php echo WFText::_('WF_TABLE_ALIGN');?></label></td>
        <td>
            <select id="align" >
                <option value="">{#not_set}</option>
                <option value="center"><?php echo WFText::_('WF_TABLE_ALIGN_MIDDLE');?></option>
                <option value="left"><?php echo WFText::_('WF_TABLE_ALIGN_LEFT');?></option>
                <option value="right"><?php echo WFText::_('WF_TABLE_ALIGN_RIGHT');?></option>
            </select></td>
        <td><label id="borderlabel" for="border">
                <?php echo WFText::_('WF_TABLE_BORDER');?></label></td>
        <td>
            <input id="border" type="text" value="" size="3"
                   maxlength="3" onchange="TableDialog.changedBorder();" class="number" />
        </td>
    </tr>
    <tr id="width_row">
        <td><label id="widthlabel" for="width">
                <?php echo WFText::_('WF_TABLE_WIDTH');?></label></td>
        <td>
            <input type="text" id="width" value="" size="5"
                   onchange="TableDialog.changedSize();" class="size" />
        </td>
        <td><label id="heightlabel" for="height">
                <?php echo WFText::_('WF_TABLE_HEIGHT');?></label></td>
        <td>
            <input type="text" id="height" value="" size="5"
                   onchange="TableDialog.changedSize();" class="size" />
        </td>
    </tr>
    <tr>
        <td class="column1"><label for="caption">
                <?php echo WFText::_('WF_TABLE_CAPTION');?></label></td>
        <td>
            <input id="caption" type="checkbox"
                   class="checkbox" value="true" />
        </td>
    </tr>
</table>
