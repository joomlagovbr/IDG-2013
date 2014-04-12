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
<div id="jce">
    <h3><?php echo WFText::_('WF_UPDATES_AVAILABLE'); ?></h3>
    <div id="updates-list">
        <div class="row-fluid header">
            <div class="span1 title">&nbsp;</div>
            <div class="span5 title">
                <?php echo WFText::_('WF_UPDATES_NAME') ?>
            </div>
            <div class="title span3">
                <?php echo WFText::_('WF_UPDATES_VERSION') ?>
            </div>
            <div class="title span3">
                <?php echo WFText::_('WF_UPDATES_PRIORITY') ?>
            </div>
        </div>
        <div class="row-fluid body"></div>
    </div>
    <div class="btn-group pull-right fltrgt">
        <button id="update-button" class="check btn"><i class="icon-search"></i>&nbsp;<?php echo WFText::_('WF_UPDATES_CHECK'); ?></button>
    </div>
</div>