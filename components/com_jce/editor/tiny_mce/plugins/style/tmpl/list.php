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
        <td><label for="list_type"><?php echo WFText::_('WF_STYLES_LIST_TYPE');?></label></td>
        <td><select id="list_type" name="list_type" class="mceEditableSelect"></select></td>
      </tr>
  
      <tr>
        <td><label for="list_bullet_image"><?php echo WFText::_('WF_STYLES_BULLET_IMAGE');?></label></td>
        <td><input id="list_bullet_image" name="list_bullet_image" type="text" class="browser image" /></td>
      </tr>
  
      <tr>
        <td><label for="list_position"><?php echo WFText::_('WF_STYLES_POSITION');?></label></td>
        <td><select id="list_position" name="list_position" class="mceEditableSelect"></select></td>
      </tr>
    </table>