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
        <td><label for="text_font"><?php echo WFText::_('WF_STYLES_TEXT_FONT');?></label></td>
        <td colspan="3">
          <select id="text_font" name="text_font" class="mceEditableSelect mceFocus"></select>
        </td>
      </tr>
      <tr>
        <td><label for="text_size"><?php echo WFText::_('WF_STYLES_TEXT_SIZE');?></label></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><select id="text_size" name="text_size" class="mceEditableSelect"></select></td>
              <td>&nbsp;</td>
              <td><select id="text_size_measurement" name="text_size_measurement"></select></td>
            </tr>
          </table>
        </td>
        <td><label for="text_weight"><?php echo WFText::_('WF_STYLES_TEXT_WEIGHT');?></label></td>
        <td>
          <select id="text_weight" name="text_weight"></select>
        </td>
      </tr>
      <tr>
        <td><label for="text_style"><?php echo WFText::_('WF_STYLES_TEXT_STYLE');?></label></td>
        <td>
          <select id="text_style" name="text_style" class="mceEditableSelect"></select>
        </td>
        <td><label for="text_variant"><?php echo WFText::_('WF_STYLES_TEXT_VARIANT');?></label></td>
        <td>
          <select id="text_variant" name="text_variant"></select>
        </td>
      </tr>
      <tr>
        <td><label for="text_lineheight"><?php echo WFText::_('WF_STYLES_TEXT_LINEHEIGHT');?></label></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                <select id="text_lineheight" name="text_lineheight" class="mceEditableSelect"></select>
              </td>
              <td>&nbsp;</td>
              <td><select id="text_lineheight_measurement" name="text_lineheight_measurement"></select></td>
            </tr>
          </table>
        </td>
        <td><label for="text_case"><?php echo WFText::_('WF_STYLES_TEXT_CASE');?></label></td>
        <td>
          <select id="text_case" name="text_case"></select>
        </td>
      </tr>
      <tr>
        <td><label for="text_color"><?php echo WFText::_('WF_STYLES_TEXT_COLOR');?></label></td>
        <td colspan="2">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><input id="text_color" name="text_color" class="color" type="text" value="" size="9" /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td valign="top" style="vertical-align: top; padding-top: 3px;"><?php echo WFText::_('WF_STYLES_TEXT_DECORATION');?></td>
        <td colspan="2">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input id="text_underline" name="text_underline" class="checkbox" type="checkbox" /></td>
              <td><label for="text_underline"><?php echo WFText::_('WF_STYLES_TEXT_UNDERLINE');?></label></td>
            </tr>
            <tr>
              <td><input id="text_overline" name="text_overline" class="checkbox" type="checkbox" /></td>
              <td><label for="text_overline"><?php echo WFText::_('WF_STYLES_TEXT_OVERLINE');?></label></td>
            </tr>
            <tr>
              <td><input id="text_linethrough" name="text_linethrough" class="checkbox" type="checkbox" /></td>
              <td><label for="text_linethrough"><?php echo WFText::_('WF_STYLES_TEXT_STRIKETROUGH');?></label></td>
            </tr>
            <tr>
              <td><input id="text_blink" name="text_blink" class="checkbox" type="checkbox" /></td>
              <td><label for="text_blink"><?php echo WFText::_('WF_STYLES_TEXT_BLINK');?></label></td>
            </tr>
            <tr>
              <td><input id="text_none" name="text_none" class="checkbox" type="checkbox" onclick="StyleDialog.updateTextDecorations();" /></td>
              <td><label for="text_none"><?php echo WFText::_('WF_STYLES_TEXT_NONE');?></label></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>