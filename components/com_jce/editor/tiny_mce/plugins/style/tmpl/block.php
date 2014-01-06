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
        <td><label for="block_wordspacing"><?php echo WFText::_('WF_STYLES_BLOCK_WORDSPACING');?></label></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><select id="block_wordspacing" name="block_wordspacing" class="mceEditableSelect"></select></td>
              <td>&nbsp;</td>
              <td><select id="block_wordspacing_measurement" name="block_wordspacing_measurement"></select></td>
            </tr>
          </table>
        </td>
      </tr>
  
      <tr>
        <td><label for="block_letterspacing"><?php echo WFText::_('WF_STYLES_BLOCK_LETTERSPACING');?></label></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><select id="block_letterspacing" name="block_letterspacing" class="mceEditableSelect"></select></td>
              <td>&nbsp;</td>
              <td><select id="block_letterspacing_measurement" name="block_letterspacing_measurement"></select></td>
            </tr>
          </table>
        </td>
      </tr>
  
      <tr>
        <td><label for="block_vertical_alignment"><?php echo WFText::_('WF_STYLES_BLOCK_VERTICAL_ALIGNMENT');?></label></td>
        <td><select id="block_vertical_alignment" name="block_vertical_alignment" class="mceEditableSelect"></select></td>
      </tr>
  
      <tr>
        <td><label for="block_text_align"><?php echo WFText::_('WF_STYLES_BLOCK_TEXT_ALIGN');?></label></td>
        <td><select id="block_text_align" name="block_text_align" class="mceEditableSelect"></select></td>
      </tr>
  
      <tr>
        <td><label for="block_text_indent"><?php echo WFText::_('WF_STYLES_BLOCK_TEXT_INDENT');?></label></td>
        <td>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><input type="text" id="block_text_indent" name="block_text_indent" /></td>
              <td>&nbsp;</td>
              <td><select id="block_text_indent_measurement" name="block_text_indent_measurement"></select></td>
            </tr>
          </table>
        </td>
      </tr>
  
      <tr>
        <td><label for="block_whitespace"><?php echo WFText::_('WF_STYLES_BLOCK_WHITESPACE');?></label></td>
        <td><select id="block_whitespace" name="block_whitespace" class="mceEditableSelect"></select></td>
      </tr>
  
      <tr>
        <td><label for="block_display"><?php echo WFText::_('WF_STYLES_BLOCK_DISPLAY');?></label></td>
        <td><select id="block_display" name="block_display" class="mceEditableSelect"></select></td>
      </tr>
    </table>