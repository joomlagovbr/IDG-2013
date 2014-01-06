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
<h4><?php echo WFText::_('WF_XHTMLXTRAS_FIELDSET_GENERAL_TAB');?></h4>
<table>
    <tr>
        <td class="label">
        <label for="datetime">
            <?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_DATETIME');?>
        </label>
        </td>
        <td>
        <input id="datetime" type="text" value="" maxlength="19" class="field mceFocus" />
        <a href="javascript:;" onclick="XHTMLXtrasDialog.insertDateTime('datetime');" class="browse">
        <span class="datetime" title="<?php echo WFText::_('WF_XHTMLXTRAS_INSERT_DATE');?>"></span>
        </a>
        </td>
    </tr>
    <tr>
        <td class="label">
        <label for="cite">
            <?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_CITE');?>
        </label>
        </td>
        <td>
        <input id="cite" type="text" value="" class="field" />
        </td>
    </tr>
</table>