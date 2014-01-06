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
<table border="0" width="100%">
  <tr>
    <td class="tdelim">&nbsp;</td>
    <td class="tdelim delim">&nbsp;</td>
    <td class="tdelim"><?php echo WFText::_('WF_STYLES_STYLE');?></td>
    <td class="tdelim delim">&nbsp;</td>
    <td class="tdelim"><?php echo WFText::_('WF_STYLES_WIDTH');?></td>
    <td class="tdelim delim">&nbsp;</td>
    <td class="tdelim"><?php echo WFText::_('WF_STYLES_COLOR');?></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td class="delim">&nbsp;</td>
    <td><input type="checkbox" id="border_style_same" name="border_style_same" class="checkbox" checked="checked" onClick="StyleDialog.toggleSame(this,'border_style');" /> <label for="border_style_same"><?php echo WFText::_('WF_STYLES_SAME');?></label></td>
    <td class="delim">&nbsp;</td>
    <td><input type="checkbox" id="border_width_same" name="border_width_same" class="checkbox" checked="checked" onClick="StyleDialog.toggleSame(this,'border_width');" /> <label for="border_width_same"><?php echo WFText::_('WF_STYLES_SAME');?></label></td>
    <td class="delim">&nbsp;</td>
    <td><input type="checkbox" id="border_color_same" name="border_color_same" class="checkbox" checked="checked" onClick="StyleDialog.toggleSame(this,'border_color');" /> <label for="border_color_same"><?php echo WFText::_('WF_STYLES_SAME');?></label></td>
  </tr>
  
  <tr>
    <td><?php echo WFText::_('WF_STYLES_TOP');?></td>
    <td class="delim">&nbsp;</td>
    <td><select id="border_style_top" name="border_style_top" class="mceEditableSelect"></select></td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><select id="border_width_top" name="border_width_top" class="mceEditableSelect"></select></td>
          <td>&nbsp;</td>
          <td><select id="border_width_top_measurement" name="border_width_top_measurement"></select></td>
        </tr>
      </table>
    </td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><input id="border_color_top" name="border_color_top" class="color" type="text" value="" size="9" /></td>
        </tr>
      </table>
    </td>
  </tr>
  
  <tr>
    <td><?php echo WFText::_('WF_STYLES_RIGHT');?></td>
    <td class="delim">&nbsp;</td>
    <td><select id="border_style_right" name="border_style_right" class="mceEditableSelect" disabled="disabled"></select></td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><select id="border_width_right" name="border_width_right" class="mceEditableSelect" disabled="disabled"></select></td>
          <td>&nbsp;</td>
          <td><select id="border_width_right_measurement" name="border_width_right_measurement" disabled="disabled"></select></td>
        </tr>
      </table>
    </td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><input id="border_color_right" name="border_color_right" class="color" type="text" value="" size="9" disabled="disabled" /></td>
        </tr>
      </table>
    </td>
  </tr>
  
  <tr>
    <td><?php echo WFText::_('WF_STYLES_BOTTOM');?></td>
    <td class="delim">&nbsp;</td>
    <td><select id="border_style_bottom" name="border_style_bottom" class="mceEditableSelect" disabled="disabled"></select></td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><select id="border_width_bottom" name="border_width_bottom" class="mceEditableSelect" disabled="disabled"></select></td>
          <td>&nbsp;</td>
          <td><select id="border_width_bottom_measurement" name="border_width_bottom_measurement" disabled="disabled"></select></td>
        </tr>
      </table>
    </td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><input id="border_color_bottom" name="border_color_bottom" class="color" type="text" value="" size="9" disabled="disabled" /></td>
        </tr>
      </table>
    </td>
  </tr>
  
  <tr>
    <td><?php echo WFText::_('WF_STYLES_LEFT');?></td>
    <td class="delim">&nbsp;</td>
    <td><select id="border_style_left" name="border_style_left" class="mceEditableSelect" disabled="disabled"></select></td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><select id="border_width_left" name="border_width_left" class="mceEditableSelect" disabled="disabled"></select></td>
          <td>&nbsp;</td>
          <td><select id="border_width_left_measurement" name="border_width_left_measurement" disabled="disabled"></select></td>
        </tr>
      </table>
    </td>
    <td class="delim">&nbsp;</td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><input id="border_color_left" name="border_color_left" class="color" type="text" value="" size="9" disabled="disabled" /></td>
        </tr>
      </table>
    </td>
  </tr>
  </table>