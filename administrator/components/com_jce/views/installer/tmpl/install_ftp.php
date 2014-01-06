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
?>	
	<dl class="adminformlist">	
		<dt><?php echo WFText::_('WF_INSTALLER_FTP');?></dt>
		<dt><?php echo WFText::_('WF_INSTALLER_FTP_DESC'); ?></dt>
		<?php if(JError::isError($this->ftp)): ?>
		<dd><?php echo WFText::_($this->ftp->message); ?></dd>
		<?php endif; ?>
		<dd>
			<label for="username" class="tooltip" title="<?php echo WFText::_('WF_LABEL_USERNAME'); ?>::<?php echo WFText::_('WF_LABEL_USERNAME_DESC'); ?>"><?php echo WFText::_('WF_LABEL_USERNAME'); ?></label>
			<span>
				<input type="text" id="username" name="username" class="input_box" size="70" value="" />
			</span>
		</dd>
		<dd>
			<label for="username" class="tooltip" title="<?php echo WFText::_('WF_LABEL_PASSWORD'); ?>::<?php echo WFText::_('WF_LABEL_PASSWORD_DESC'); ?>"><?php echo WFText::_('WF_LABEL_PASSWORD'); ?></label>
			<span>
				<input type="password" id="password" name="password" class="input_box" size="70" value="" />
			</span>
		</dd>
	</dl>