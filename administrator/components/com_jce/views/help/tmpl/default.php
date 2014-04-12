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
<div id="jce" class="container-fluid">
    <div class="row-fluid">
	<div class="span4 well">
            <div id="help-menu"><?php echo $this->model->renderTopics();?></div>
	</div>
        <div id="help-menu-toggle"><div class="toggle-handle"></div><div class="resize-handle"></div></div>
        <div class="span8">
            <div id="help-frame"><iframe id="help-iframe" src="javascript:;" scrolling="auto" frameborder="0"></iframe></div>
	</div>
    </div>
</div>