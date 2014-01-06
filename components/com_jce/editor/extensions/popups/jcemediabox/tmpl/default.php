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
defined('_WF_EXT') or die('RESTRICTED');

?>
<table border="0" cellpadding="3" cellspacing="0">
    <tr>
        <td><label for="jcemediabox_popup_title" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_OPTION_TITLE_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_OPTION_TITLE'); ?></label></td>
        <td><input id="jcemediabox_popup_title" name="jcemediabox_popup_title[]" type="text" class="text" value="" /></td>
    </tr>
    <tr>
        <td><label for="jcemediabox_popup_caption" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_CAPTION_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_CAPTION'); ?></label></td>
        <td><input id="jcemediabox_popup_caption" name="jcemediabox_popup_caption[]" type="text" class="text" value="" /></td>
    </tr>
    <tr>
        <td><label for="jcemediabox_popup_group" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_GROUP_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_GROUP'); ?></label></td>
        <td><input id="jcemediabox_popup_group" type="text" class="text" value="" /></td>
    </tr>
    <tr>
        <td><label for="jcemediabox_popup_icon" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ICON_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ICON'); ?></label></td>
        <td><select id="jcemediabox_popup_icon">
                <option value="0"><?php echo WFText::_('WF_OPTION_NO'); ?></option>
                <option value="1" selected="selected"><?php echo WFText::_('WF_OPTION_YES'); ?></option>
            </select>
            <label for="jcemediabox_popup_icon_position" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ICON_POSITION_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ICON_POSITION'); ?></label>
            <select id="jcemediabox_popup_icon_position">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="icon-left"><?php echo WFText::_('WF_OPTION_LEFT'); ?></option>
                <option value="icon-right"><?php echo WFText::_('WF_OPTION_RIGHT'); ?></option>
                <option value="icon-top-left"><?php echo WFText::_('WF_OPTION_TOP_LEFT'); ?></option>
                <option value="icon-top-right"><?php echo WFText::_('WF_OPTION_TOP_RIGHT'); ?></option>                                        
                <option value="icon-bottom-left"><?php echo WFText::_('WF_OPTION_BOTTOM_LEFT'); ?></option>
                <option value="icon-bottom-right"><?php echo WFText::_('WF_OPTION_BOTTOM_RIGHT'); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="width" class="hastip" title="<?php echo WFText::_('WF_LABEL_DIMENSIONS_DESC'); ?>"><?php echo WFText::_('WF_LABEL_DIMENSIONS'); ?></label></td>
        <td>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <input type="text" id="jcemediabox_popup_width" value="" onchange="JCEMediaBox.setDimensions('width', 'height');" /> x <input type="text" id="jcemediabox_popup_height" value="" onchange="JCEMediaBox.setDimensions('height', 'width');" />
                        <input type="hidden" id="jcemediabox_popup_tmp_width" value=""  />
                        <input type="hidden" id="jcemediabox_popup_tmp_height" value="" />
                    </td>
                    <td><input id="jcemediabox_popup_constrain" type="checkbox" class="checkbox" checked="checked" /><label for="jcemediabox_popup_constrain"><?php echo WFText::_('WF_LABEL_PROPORTIONAL'); ?></label></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><label for="jcemediabox_popup_autopopup" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUTO_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUTO'); ?></label></td>
        <td>
            <select id="jcemediabox_popup_autopopup">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="autopopup-single"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUTO_SINGLE'); ?></option>
                <option value="autopopup-multiple"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUTO_MULTIPLE'); ?></option>
            </select>
        </td>
    </tr>
    <!--tr>
        <td><label for="jcemediabox_popup_id" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ID_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_ID'); ?></label></td>
        <td>
            <input type="text" class="text" value="" id="jcemediabox_popup_autopopup" />
        </td>
    </tr-->
    <tr>
        <td><label for="jcemediabox_popup_hide" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_HIDE_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_HIDE'); ?></label></td>
        <td><select id="jcemediabox_popup_hide">
                <option value="0"><?php echo WFText::_('WF_OPTION_NO'); ?></option>
                <option value="1"><?php echo WFText::_('WF_OPTION_YES'); ?></option>
            </select></td>
    </tr>
    <tr>
        <td><label for="jcemediabox_popup_mediatype" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_MEDIATYPE_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_MEDIATYPE'); ?></label></td>	
        <td><select id="jcemediabox_popup_mediatype">
                <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET'); ?></option>
                <option value="text/html"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_INTERNAL'); ?></option>
                <option value="iframe"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_EXTERNAL'); ?></option>
                <option value="image"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_IMAGE'); ?></option>
                <option value="application/x-shockwave-flash"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_FLASH'); ?></option>
                <option value="video/quicktime"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_QUICKTIME'); ?></option>
                <option value="application/x-mplayer2"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_WINDOWSMEDIA'); ?></option>
                <option value="video/divx"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_DIVX'); ?></option> 
                <option value="application/x-director"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_DIRECTOR'); ?></option>
                <option value="audio/x-pn-realaudio-plugin"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_REAL'); ?></option>
                <option value="video/mp4"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_VIDEO_MP4'); ?></option>   
                <option value="audio/mp3"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUDIO_MP3'); ?></option>
                <option value="video/webm"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_VIDEO_WEBM'); ?></option>
                <option value="audio/webm"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_AUDIO_WEBM'); ?></option>      
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <label for="jcemediabox_popup_params" class="hastip" title="<?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_PARAMS_DESC'); ?>"><?php echo WFText::_('WF_POPUPS_JCEMEDIABOX_PARAMS'); ?></label>

            <ul id="jcemediabox_popup_params">
                <li>
                    <label><?php echo WFText::_('WF_LABEL_NAME'); ?></label><input type="text" class="name" /><label><?php echo WFText::_('WF_LABEL_VALUE'); ?></label><input type="text" class="value" />
                    <span class="add" role="button" title="<?php echo WFText::_('WF_LABEL_ADD'); ?>"></span>
                    <span class="remove" role="button" title="<?php echo WFText::_('WF_LABEL_REMOVE'); ?>"></span>
                </li>
            </ul>
        </td>
    </tr>
</table>