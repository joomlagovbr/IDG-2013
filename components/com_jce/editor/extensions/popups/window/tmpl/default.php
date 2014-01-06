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

defined( '_WF_EXT' ) or die('RESTRICTED');
?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td><label for="window_popup_title" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_OPTION_TITLE_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_OPTION_TITLE');?></label></td>
		<td><input id="window_popup_title" type="text" value="" /></td>
	</tr>
	<tr>
		<td><label for="width" class="hastip" title="<?php echo WFText::_('WF_LABEL_DIMENSIONS_DESC');?>"><?php echo WFText::_('WF_LABEL_DIMENSIONS');?></label></td>
		<td>
			<table border="0" cellpadding="2" cellspacing="0">
	            <tr>
	                <td>
	                	<input type="text" id="window_popup_width" value="" onchange="JCEWindowPopup.setDimensions('width', 'height');" /> x <input type="text" id="window_popup_height" value="" onchange="JCEWindowPopup.setDimensions('height', 'width');" />
	               	 	<input type="hidden" id="window_popup_tmp_width" value=""  />
	                	<input type="hidden" id="window_popup_tmp_height" value="" />
	                </td>
	                <td><input id="window_popup_constrain" type="checkbox" class="checkbox" checked="checked" /><label for="window_popup_constrain"><?php echo WFText::_('WF_LABEL_PROPORTIONAL');?></label></td>
	            </tr>
			</table>
		</td>
	</tr>
    <tr>	
        			<td><label for="window_popup_position" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_POSITION_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_POSITION');?></label></td>
        			<td>
        				<select id="window_popup_position_top" class="editable">
			                <option value="top"><?php echo WFText::_('WF_OPTION_TOP');?></option>
			                <option value="center" selected="selected"><?php echo WFText::_('WF_OPTION_CENTER');?></option>
			                <option value="bottom"><?php echo WFText::_('WF_OPTION_BOTTOM');?></option>
			            </select>
			            <select id="window_popup_position_left" class="editable">
			                <option value="left"><?php echo WFText::_('WF_OPTION_LEFT');?></option>
			                <option value="center" selected="selected"><?php echo WFText::_('WF_OPTION_CENTER');?></option>
			                <option value="right"><?php echo WFText::_('WF_OPTION_RIGHT');?></option>
			            </select>
			        </td>
    			</tr>
  </table>
  <fieldset>
  	<legend><?php echo WFText::_('WF_POPUPS_WINDOW_OPTIONS');?></legend>
  	<table border="0" cellpadding="4" cellspacing="0" width="100%">
        		<tr>	
        			<td><input type="checkbox" id="window_popup_scrollbars" class="checkbox" checked="checked" /><label for="window_popup_scrollbars" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_SCROLLBARS_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_SCROLLBARS');?></label></td>
        			<td><input type="checkbox" id="window_popup_resizable" class="checkbox" checked="checked" /><label for="window_popup_resizable" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_RESIZABLE_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_RESIZABLE');?></label></td>
    			</tr>
    			<tr>	
        			<td><input type="checkbox" id="window_popup_location" class="checkbox" checked="checked" /><label for="window_popup_location" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_LOCATION_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_LOCATION');?></label></td>
        			<td><input type="checkbox" id="window_popup_toolbar" class="checkbox" checked="checked" /><label for="window_popup_toolbar" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_TOOLBAR_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_TOOLBAR');?></label></td>
    			</tr>
    			<tr>	
        			<td><input type="checkbox" id="window_popup_status" class="checkbox" checked="checked" /><label for="window_popup_status" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_STATUS_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_STATUS');?></label></td>
        			<td><input type="checkbox" id="window_popup_menubar" class="checkbox" checked="checked" /><label for="window_popup_menubar" class="hastip" title="<?php echo WFText::_('WF_POPUPS_WINDOW_MENUBAR_DESC');?>"><?php echo WFText::_('WF_POPUPS_WINDOW_MENUBAR');?></label></td>
    			</tr>
    			
   </table>
   </fieldset>
</table>