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
<h4><?php echo WFText::_('WF_TABLE_GENERAL_PROPS'); ?></h4>
<table border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td><label for="align">
                <?php echo WFText::_('WF_TABLE_ALIGN'); ?></label></td>
        <td>
            <select id="align" class="mceFocus">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="center"><?php echo WFText::_('WF_TABLE_ALIGN_MIDDLE'); ?></option>
                <option value="left"><?php echo WFText::_('WF_TABLE_ALIGN_LEFT'); ?></option>
                <option value="right"><?php echo WFText::_('WF_TABLE_ALIGN_RIGHT'); ?></option>
            </select></td>
        <td><label for="celltype">
                <?php echo WFText::_('WF_TABLE_CELL_TYPE'); ?></label></td>
        <td>
            <select id="celltype" >
                <option value="td"><?php echo WFText::_('WF_TABLE_TD'); ?></option>
                <option value="th"><?php echo WFText::_('WF_TABLE_TH'); ?></option>
            </select></td>
    </tr>
    <tr>
        <td><label for="valign">
                <?php echo WFText::_('WF_TABLE_VALIGN'); ?></label></td>
        <td>
            <select id="valign" >
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="top"><?php echo WFText::_('WF_TABLE_ALIGN_TOP'); ?></option>
                <option value="middle"><?php echo WFText::_('WF_TABLE_ALIGN_MIDDLE'); ?></option>
                <option value="bottom"><?php echo WFText::_('WF_TABLE_ALIGN_BOTTOM'); ?></option>
            </select></td>
        <td><label for="scope">
                <?php echo WFText::_('WF_TABLE_SCOPE'); ?></label></td>
        <td>
            <select id="scope" >
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="col"><?php echo WFText::_('WF_TABEL_COL'); ?></option>
                <option value="row"><?php echo WFText::_('WF_TABEL_ROW'); ?></option>
                <option value="rowgroup"><?php echo WFText::_('WF_TABLE_ROWGROUP'); ?></option>
                <option value="colgroup"><?php echo WFText::_('WF_TABLE_COLGROUP'); ?></option>
            </select></td>
    </tr>
    <tr>
        <td><label for="width">
                <?php echo WFText::_('WF_TABLE_WIDTH'); ?></label></td>
        <td>
            <input id="width" type="text" value="" size="4"
                   maxlength="4" onchange="TableDialog.changedSize();" />
        </td>
        <td><label for="height">
                <?php echo WFText::_('WF_TABLE_HEIGHT'); ?></label></td>
        <td>
            <input id="height" type="text" value="" size="4"
                   maxlength="4" onchange="TableDialog.changedSize();" />
        </td>
    </tr>
</table>