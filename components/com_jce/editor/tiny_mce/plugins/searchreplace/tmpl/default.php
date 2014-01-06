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

defined( 'WF_EDITOR' ) or die('RESTRICTED');

$tabs = WFTabs::getInstance();
?>
<?php echo $tabs->render(); ?>
<div class="mceActionPanel">
	<button type="submit" id="next" name="insert"><?php echo WFText::_('WF_SEARCHREPLACE_FINDNEXT');?></button>
	<button type="button" class="button" id="replaceBtn" name="replaceBtn"><?php echo WFText::_('WF_SEARCHREPLACE_REPLACE');?></button>
	<button type="button" class="button" id="replaceAllBtn" name="replaceAllBtn"><?php echo WFText::_('WF_SEARCHREPLACE_REPLACEALL');?></button>
	<button type="button" id="cancel" name="cancel" onclick="tinyMCEPopup.close();"><?php echo WFText::_('WF_LABEL_CANCEL');?></button>
</div>