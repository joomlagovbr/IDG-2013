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
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data" class="form-horizontal">
    <div id="jce" class="loading">
<?php
if ($this->showMessage) :
    echo $this->loadTemplate('message');
endif;
?>
        <fieldset>
            <legend><?php echo WFText::_('WF_INSTALLER_INSTALL'); ?></legend>
<?php
if ($this->ftp) :
    echo $this->loadTemplate('ftp');
endif;
echo $this->loadTemplate('install');
?>
        </fieldset>
        <fieldset>
            <legend><?php echo WFText::_('WF_INSTALLER_UNINSTALL'); ?></legend>
<?php echo $this->loadTemplate('uninstall'); ?>
        </fieldset>


    </div>
    <input type="hidden" name="view" value="installer" />
    <input type="hidden" name="option" value="com_jce" />
    <input type="hidden" name="layout" value="install" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>