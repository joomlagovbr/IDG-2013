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
<table width="100%">
    <tr>
        <td nowrap="nowrap"><label for="href" class="hastip" title="<?php echo WFText::_('WF_LABEL_URL_DESC'); ?>"><?php echo WFText::_('WF_LABEL_URL'); ?></label></td>
        <td><input id="href" type="text" value="" size="150" class="required browser" /> <!--td id="hrefbrowsercontainer"></td-->
            <span class="email" title="<?php echo WFText::_('WF_LABEL_EMAIL'); ?>"></span></td>
    </tr>
    <tr>
        <td><label for="text" class="hastip" title="<?php echo WFText::_('WF_LINK_LINK_TEXT_DESC'); ?>"><?php echo WFText::_('WF_LINK_LINK_TEXT'); ?></label></td>
        <td><input id="text" type="text" value="" class="required" /></td>
    </tr>
</table>
<fieldset>
    <legend><?php echo WFText::_('WF_LABEL_LINKS'); ?></legend>
    <div id="link-options">
        <?php 
            if ($this->plugin->getSearch('link')->isEnabled()) :
                echo $this->plugin->getSearch('link')->render();
            endif;
        ?>
        <?php echo $this->plugin->getLinks()->render(); ?>
    </div>
</fieldset>
<h4><?php echo WFText::_('WF_LABEL_ATTRIBUTES'); ?></h4>
<table>
    <tr id="attributes-anchor">
        <td><label for="anchor" class="hastip" title="<?php echo WFText::_('WF_LABEL_ANCHORS_DESC'); ?>"><?php echo WFText::_('WF_LABEL_ANCHORS'); ?></label></td>
        <td id="anchor_container">&nbsp;</td>
    </tr>
    <tr id="attributes-target">
        <td><label for="target" class="hastip" title="<?php echo WFText::_('WF_LABEL_TARGET_DESC'); ?>"><?php echo WFText::_('WF_LABEL_TARGET'); ?></label></td>
        <td><select id="target">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="_self"><?php echo WFText::_('WF_OPTION_TARGET_SELF'); ?></option>
                <option value="_blank"><?php echo WFText::_('WF_OPTION_TARGET_BLANK'); ?></option>
                <option value="_parent"><?php echo WFText::_('WF_OPTION_TARGET_PARENT'); ?></option>
                <option value="_top"><?php echo WFText::_('WF_OPTION_TARGET_TOP'); ?></option>
            </select></td>
    </tr>
    <tr>
        <td nowrap="nowrap"><label for="title" class="hastip" title="<?php echo WFText::_('WF_LABEL_TITLE_DESC'); ?>"><?php echo WFText::_('WF_LABEL_TITLE'); ?></label></td>
        <td><input id="title" type="text" value="" size="150" /></td>
    </tr>
</table>
