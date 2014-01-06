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

defined( '_JEXEC' ) or die('RESTRICTED');

//$popups = WFPopupsExtension::getInstance();
?>
<h4><label for="popup_list" class="hastip" title="<?php echo WFText::_('WF_POPUP_TYPE_DESC');?>"><?php echo WFText::_('WF_POPUP_TYPE');?></label><?php echo $this->popups->getPopupList();?></h4>
<table style="display:<?php echo ($this->popups->get('text') === false) ? 'none' : ''?>;">
	<tr>
		<td><label for="popup_text" class="hastip"
			title="<?php echo WFText::_('WF_POPUP_TEXT_DESC');?>"><?php echo WFText::_('WF_POPUP_TEXT');?></label></td>
		<td><input id="popup_text" type="text" value="" /></td>
	</tr>
</table>
<?php echo $this->popups->getPopupTemplates();?>