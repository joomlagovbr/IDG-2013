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
<tr>
    <td class="label"><label for="contenteditable"><?php echo WFText::_('WF_LABEL_CONTENTEDITBALE');?></label></td>
    <td><select id="contenteditable" class="field">
            <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
            <option value="true"><?php echo WFText::_('WF_OPTION_YES');?></option>
            <option value="false"><?php echo WFText::_('WF_OPTION_NO');?></option>
            <option value="inherit"><?php echo WFText::_('WF_OPTION_INHERIT');?></option>
        </select>
    </td>
</tr>
<tr>
    <td class="label"><label for="draggable"><?php echo WFText::_('WF_LABEL_DRAGGABLE');?></label></td>
    <td><select id="draggable" class="field">
            <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
            <option value="true"><?php echo WFText::_('WF_OPTION_YES');?></option>
            <option value="false"><?php echo WFText::_('WF_OPTION_NO');?></option>
            <option value="auto"><?php echo WFText::_('WF_OPTION_AUTO');?></option>
        </select>
    </td>
</tr>
<tr>
    <td class="label"><label for="hidden"><?php echo WFText::_('WF_LABEL_HIDDEN');?></label></td>
    <td><select id="hidden" class="field">
            <option value=""><?php echo WFText::_('WF_OPTION_NO');?></option>
            <option value="hidden"><?php echo WFText::_('WF_OPTION_YES');?></option>
        </select>
    </td>
</tr>
<tr>
    <td class="label"><label for="spellcheck"><?php echo WFText::_('WF_LABEL_SPELLCHECK');?></label></td>
    <td><select id="spellcheck" class="field">
            <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
            <option value="true"><?php echo WFText::_('WF_OPTION_YES');?></option>
            <option value="false"><?php echo WFText::_('WF_OPTION_NO');?></option>
        </select>
    </td>
</tr>