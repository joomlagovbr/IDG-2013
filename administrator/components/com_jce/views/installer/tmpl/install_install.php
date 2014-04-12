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
<h4><?php echo WFText::_('WF_INSTALLER_INSTALL_DESC'); ?></h4>
<div class="btn-group input-append">
    <label for="import" class="element-invisible"><?php echo WFText::_('WF_INSTALLER_PACKAGE'); ?></label>
    <input type="file" name="install" id="upload" placeholder="<?php echo $this->state->get('install.directory'); ?>" />
    <button id="upload_button" class="btn"><i class="icon-arrow-up"></i>&nbsp;<?php echo WFText::_('WF_INSTALLER_UPLOAD'); ?></button>
</div>