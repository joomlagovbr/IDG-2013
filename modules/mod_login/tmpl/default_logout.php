<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if ($params->get('name') == 0) : ?>
		<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name'), ENT_COMPAT, 'UTF-8')); ?>
	<?php else : ?>
		<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username'), ENT_COMPAT, 'UTF-8')); ?>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($params->get('profilelink')) : ?>
	<ul class="unstyled">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile'); ?>">
			<?php echo JText::_('MOD_LOGIN_PROFILE'); ?></a>
		</li>
	</ul>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
