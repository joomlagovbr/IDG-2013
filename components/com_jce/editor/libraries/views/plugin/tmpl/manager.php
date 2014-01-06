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

if ($this->browser->get('position') == 'top') {
	$this->browser->render();
}
// render tabs and panels
$tabs->render();

if ($this->browser->get('position') == 'bottom') {
	$this->browser->render();
}

?>	

<div class="actionPanel">
	<button class="button" id="refresh"><?php echo WFText::_('WF_LABEL_REFRESH')?></button>
	<button class="button confirm" id="insert"><?php echo WFText::_('WF_LABEL_INSERT')?></button>
	<button class="button cancel" id="cancel"><?php echo WFText::_('WF_LABEL_CANCEL')?></button>
</div>