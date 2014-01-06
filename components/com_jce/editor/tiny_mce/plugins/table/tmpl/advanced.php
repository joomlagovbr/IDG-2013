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
<h4><?php echo WFText::_('WF_TABLE_ADVANCED_PROPS'); ?></h4>
<table border="0" cellpadding="0" cellspacing="4">
    <tr>
        <td><label for="classlist" class="hastip" title="<?php echo WFText::_('WF_LABEL_CLASS_LIST_DESC'); ?>"><?php echo WFText::_('WF_LABEL_CLASS_LIST'); ?></label></td>
        <td>
            <select id="classlist" onchange="TableDialog.setClasses(this.value);">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="title" class="hastip" title="<?php echo WFText::_('WF_LABEL_CLASSES_DESC'); ?>"><?php echo WFText::_('WF_LABEL_CLASSES'); ?></label></td>
        <td><input id="classes" type="text" value="" /></td>
    </tr>

    <tr>
        <td class="column1"><label for="id">
                <?php echo WFText::_('WF_TABLE_ID'); ?></label></td>
        <td>
            <input id="id" type="text" value="" class="advfield" />
        </td>
    </tr>
    <tr>
        <td class="column1"><label for="summary">
                <?php echo WFText::_('WF_TABLE_SUMMARY'); ?></label></td>
        <td>
            <input id="summary" type="text" value=""
                   class="advfield" />
        </td>
    </tr>
    <tr>
        <td><label for="style">
                <?php echo WFText::_('WF_TABLE_STYLE'); ?></label></td>
        <td>
            <input type="text" id="style" value=""
                   class="advfield" onchange="TableDialog.changedStyle();" />
        </td>
    </tr>
    <tr>
        <td class="column1"><label id="langlabel" for="lang">
                <?php echo WFText::_('WF_TABLE_LANGCODE'); ?></label></td>
        <td>
            <input id="lang" type="text" value="" class="advfield" />
        </td>
    </tr>
    <tr>
        <td class="column1"><label for="backgroundimage">
                <?php echo WFText::_('WF_TABLE_BGIMAGE'); ?></label></td>
        <td>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <input id="backgroundimage" type="text"
                               value="" class="advfield browser"
                               onchange="TableDialog.changedBackgroundImage();" />
                    </td>
                </tr>
            </table></td>
    </tr>
    <?php if ($this->plugin->getContext() == 'table') :
        ?>
        <tr>
            <td class="column1"><label for="tframe">
                    <?php echo WFText::_('WF_TABLE_FRAME'); ?></label></td>
            <td>
                <select id="tframe" class="advfield">
                    <option value="">{#not_set}</option>
                    <option value="void"><?php echo WFText::_('WF_TABLE_RULES_VOID'); ?></option>
                    <option value="above"><?php echo WFText::_('WF_TABLE_RULES_ABOVE'); ?></option>
                    <option value="below"><?php echo WFText::_('WF_TABLE_RULES_BELOW'); ?></option>
                    <option value="hsides"><?php echo WFText::_('WF_TABLE_RULES_HSIDES'); ?></option>
                    <option value="lhs"><?php echo WFText::_('WF_TABLE_RULES_LHS'); ?></option>
                    <option value="rhs"><?php echo WFText::_('WF_TABLE_RULES_RHS'); ?></option>
                    <option value="vsides"><?php echo WFText::_('WF_TABLE_RULES_VSIDES'); ?></option>
                    <option value="box"><?php echo WFText::_('WF_TABLE_RULES_BOX'); ?></option>
                    <option value="border"><?php echo WFText::_('WF_TABLE_RULES_BORDER'); ?></option>
                </select></td>
        </tr>
        <tr>
            <td class="column1"><label for="rules">
                    <?php echo WFText::_('WF_TABLE_RULES'); ?></label></td>
            <td>
                <select id="rules" class="advfield">
                    <option value="">{#not_set}</option>
                    <option value="none"><?php echo WFText::_('WF_TABLE_FRAME_NONE'); ?></option>
                    <option value="groups"><?php echo WFText::_('WF_TABLE_FRAME_GROUPS'); ?></option>
                    <option value="rows"><?php echo WFText::_('WF_TABLE_FRAME_ROWS'); ?></option>
                    <option value="cols"><?php echo WFText::_('WF_TABLE_FRAME_COLS'); ?></option>
                    <option value="all"><?php echo WFText::_('WF_TABLE_FRAME_ALL'); ?></option>
                </select></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="column1"><label for="dir">
                <?php echo WFText::_('WF_TABLE_LANGDIR'); ?></label></td>
        <td>
            <select id="dir" class="advfield">
                <option value="">{#not_set}</option>
                <option value="ltr"><?php echo WFText::_('WF_TABLE_LTR'); ?></option>
                <option value="rtl"><?php echo WFText::_('WF_TABLE_RTL'); ?></option>
            </select></td>
    </tr>
    <tr>
        <td class="column1"><label for="bordercolor">
                <?php echo WFText::_('WF_TABLE_BORDERCOLOR'); ?></label></td>
        <td>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <input id="bordercolor" type="text" value=""
                               size="9" class="color" onchange="TableDialog.changedColor();"/>
                    </td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td class="column1"><label for="bgcolor">
                <?php echo WFText::_('WF_TABLE_BGCOLOR'); ?></label></td>
        <td>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <input id="bgcolor" type="text" value="" size="9"
                               class="color" onchange="TableDialog.changedColor();" />
                    </td>
                </tr>
            </table></td>
    </tr>
</table>
