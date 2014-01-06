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
<div id="mediaplayer_options">
    <table border="0" cellpadding="4" cellspacing="0" width="100%">
        <tr>
            <td><label for="mediaplayer_controlBarMode" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_MODE_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_MODE') ?></label></td>
            <td>
                <select id="mediaplayer_controlBarMode">
                    <option value="docked"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_DOCKED') ?></option>
                    <option value="floating"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_FLOATING') ?></option>
                    <option value="none"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_NONE') ?></option>
                </select>

                <input type="checkbox" id="mediaplayer_controlBarAutoHide" checked="checked" />
                <label for="mediaplayer_controlBarAutoHide" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_AUTOHIDE_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_AUTOHIDE') ?></label>
            </td>
        </tr>
        <tr>
            <td><label for="mediaplayer_controlBarAutoHideTimeout" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_TIMEOUT_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_CONTROLBAR_TIMEOUT') ?></label></td>
            <td><input type="text" id="mediaplayer_controlBarAutoHideTimeout" value="" pattern="[0-9]*" /></td>
        </tr>
        <tr>
            <td><label for="mediaplayer_poster" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_POSTER_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_POSTER') ?></label></td>
            <td><input type="text" id="mediaplayer_poster" value="" class="browser image" /></td>
        </tr>
        <tr>	
            <td><label for="mediaplayer_endOfVideoOverlay" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_VIDEOOVERLAY_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_VIDEOOVERLAY') ?></label></td>
            <td><input type="text" id="mediaplayer_endOfVideoOverlay" value="" class="browser image" /></td>
        </tr>
        <tr>	
            <td><label for="mediaplayer_backgroundColor" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_BACKGROUNDCOLOR_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_BACKGROUNDCOLOR') ?></label></td>
            <td><input type="text" id="mediaplayer_backgroundColor" value="" class="color" size="9" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" id="mediaplayer_loop" />
                <label for="mediaplayer_loop" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_LOOP_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_LOOP') ?></label>

                <input type="checkbox" id="mediaplayer_autoPlay" />
                <label for="mediaplayer_autoPlay" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_AUTOPLAY_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_AUTOPLAY') ?></label>

                <input type="checkbox" id="mediaplayer_muted" />
                <label for="mediaplayer_muted" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_MUTED_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_MUTED') ?></label>

                <input type="checkbox" id="mediaplayer_playButtonOverlay" checked="checked" />
                <label for="mediaplayer_playButtonOverlay" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_PLAYBUTTONOVERLAY_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_PLAYBUTTONOVERLAY') ?></label>

                <input type="checkbox" id="mediaplayer_bufferingOverlay" checked="checked" />
                <label for="mediaplayer_bufferingOverlay" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_BUFFERINGOVERLAY_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_BUFFERINGOVERLAY') ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <label for="mediaplayer_volume" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_VOLUME_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_VOLUME') ?></label>
                <input type="text" id="mediaplayer_volume" value="100" class="slider" pattern="[0-9]*" min="0" max="100" />
            </td>
            <td>
                <label for="mediaplayer_audioPan" title="<?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_AUDIOPAN_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_MEDIAPLAYER_JCEPLAYER_AUDIOPAN') ?></label>
                <input type="text" id="mediaplayer_audioPan" value="0" class="slider" pattern="[\-0-9]*" min="-1" max="1" />
            </td>
        </tr>
    </table>
</div>