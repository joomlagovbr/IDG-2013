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

$element = $this->plugin->getElementName();

if ($element == 'del' || $element == 'ins') :
    echo $this->loadTemplate('datetime');
endif;
?>
<h4><?php echo WFText::_('WF_XHTMLXTRAS_FIELDSET_ATTRIB_TAB');?></h4>
<table>
    <tr>
        <td class="label"><label for="title"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_TITLE');?></label></td>
        <td><input id="title" type="text" value=""
                   class="field mceFocus" /></td>
    </tr>
    <tr>
        <td class="label"><label for="id"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_ID');?></label></td>
        <td><input id="id" type="text" value="" class="field" /></td>
    </tr>
    <tr>
        <td class="label"><label for="class"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_CLASS');?></label></td>
        <td><select id="class" class="field mceEditableSelect">
                <option value="">{#not_set}</option>
            </select></td>
    </tr>
    <tr>
        <td class="label"><label for="class"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_STYLE');?></label></td>
        <td><input id="style" type="text" value="" class="field" /></td>
    </tr>
    <tr>
        <td class="label"><label for="dir"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_LANGDIR');?></label></td>
        <td><select id="dir" class="field">
                <option value="">{#not_set}</option>
                <option value="ltr"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_OPTION_LTR');?></option>
                <option value="rtl"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_OPTION_RTL');?></option>
            </select></td>
    </tr>
    <tr>
        <td class="label"><label for="lang"><?php echo WFText::_('WF_XHTMLXTRAS_ATTRIBUTE_LABEL_LANGCODE');?></label></td>
        <td><input id="lang" type="text" value="" class="field" />
        </td>
    </tr>
<?php
if ($this->plugin->isHTML5()) :
    echo $this->loadTemplate('html5');
endif;
?>
</table>