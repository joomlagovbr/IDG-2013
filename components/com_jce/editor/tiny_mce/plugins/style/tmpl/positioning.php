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
<table border="0">
  <tr>
    <td><label for="positioning_type"><?php echo WFText::_('WF_STYLES_POSITIONING_TYPE');?></label></td>
    <td><select id="positioning_type" name="positioning_type" class="mceEditableSelect"></select></td>
    <td>&nbsp;&nbsp;&nbsp;<label for="positioning_visibility"><?php echo WFText::_('WF_STYLES_VISIBILITY');?></label></td>
    <td><select id="positioning_visibility" name="positioning_visibility" class="mceEditableSelect"></select></td>
  </tr>

  <tr>
    <td><label for="positioning_width"><?php echo WFText::_('WF_STYLES_WIDTH');?></label></td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input type="text" id="positioning_width" name="positioning_width" onchange="StyleDialog.synch('positioning_width','box_width');" /></td>
          <td>&nbsp;</td>
          <td><select id="positioning_width_measurement" name="positioning_width_measurement"></select></td>
        </tr>
      </table>
    </td>
    <td>&nbsp;&nbsp;&nbsp;<label for="positioning_zindex"><?php echo WFText::_('WF_STYLES_ZINDEX');?></label></td>
    <td><input type="text" id="positioning_zindex" name="positioning_zindex" /></td>
  </tr>

  <tr>
    <td><label for="positioning_height"><?php echo WFText::_('WF_STYLES_HEIGHT');?></label></td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input type="text" id="positioning_height" name="positioning_height" onchange="StyleDialog.synch('positioning_height','box_height');" /></td>
          <td>&nbsp;</td>
          <td><select id="positioning_height_measurement" name="positioning_height_measurement"></select></td>
        </tr>
      </table>
    </td>
    <td>&nbsp;&nbsp;&nbsp;<label for="positioning_overflow"><?php echo WFText::_('WF_STYLES_OVERFLOW');?></label></td>
    <td><select id="positioning_overflow" name="positioning_overflow" class="mceEditableSelect"></select></td>
  </tr>
</table>

<div style="float: left; width: 49%">
  <fieldset>
    <legend><?php echo WFText::_('WF_STYLES_PLACEMENT');?></legend>

    <table border="0">
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" id="positioning_placement_same" name="positioning_placement_same" class="checkbox" checked="checked" onclick="StyleDialog.toggleSame(this,'positioning_placement');" /> <label for="positioning_placement_same"><?php echo WFText::_('WF_STYLES_SAME');?></label></td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_TOP');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_placement_top" name="positioning_placement_top" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_placement_top_measurement" name="positioning_placement_top_measurement"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_RIGHT');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_placement_right" name="positioning_placement_right" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_placement_right_measurement" name="positioning_placement_right_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_BOTTOM');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_placement_bottom" name="positioning_placement_bottom" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_placement_bottom_measurement" name="positioning_placement_bottom_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_LEFT');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_placement_left" name="positioning_placement_left" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_placement_left_measurement" name="positioning_placement_left_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </fieldset>
</div>

<div style="float: right; width: 49%">
  <fieldset>
    <legend><?php echo WFText::_('WF_STYLES_CLIP');?></legend>

    <table border="0">
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" id="positioning_clip_same" name="positioning_clip_same" class="checkbox" checked="checked" onclick="StyleDialog.toggleSame(this,'positioning_clip');" /> <label for="positioning_clip_same"><?php echo WFText::_('WF_STYLES_SAME');?></label></td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_TOP');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_clip_top" name="positioning_clip_top" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_clip_top_measurement" name="positioning_clip_top_measurement"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_RIGHT');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_clip_right" name="positioning_clip_right" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_clip_right_measurement" name="positioning_clip_right_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_BOTTOM');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_clip_bottom" name="positioning_clip_bottom" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_clip_bottom_measurement" name="positioning_clip_bottom_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo WFText::_('WF_STYLES_LEFT');?></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="positioning_clip_left" name="positioning_clip_left" disabled="disabled" /></td>
              <td>&nbsp;</td>
              <td><select id="positioning_clip_left_measurement" name="positioning_clip_left_measurement" disabled="disabled"></select></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </fieldset>
</div>