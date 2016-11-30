<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_logged
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>
<div class="row-striped">
	<?php foreach ($users as $user) : ?>
		<div class="row-fluid">
			<div class="span7">
				<?php if ($user->client_id == 0) : ?>
					<a class="hasTooltip" title="<?php echo JHtml::tooltipText('MOD_LOGGED_LOGOUT'); ?>" href="<?php echo $user->logoutLink; ?>" class="btn btn-danger btn-mini">
						<span class="icon-remove icon-white" title="<?php echo JText::_('JLOGOUT'); ?>"></span>
					</a>
				<?php endif; ?>

				<strong class="row-title">
					<?php if (isset($user->editLink)) : ?>
						<a href="<?php echo $user->editLink; ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('JGRID_HEADING_ID'); ?> : <?php echo $user->id; ?>">
							<?php echo $user->name; ?></a>
					<?php else : ?>
						<?php echo $user->name; ?>
					<?php endif; ?>
				</strong>

				<small class="small hasTooltip" title="<?php echo JHtml::tooltipText('JCLIENT'); ?>">
					<?php if ($user->client_id) : ?>
						<?php echo JText::_('JADMINISTRATION'); ?>
					<?php else : ?>
						<?php echo JText::_('JSITE'); ?>
					<?php endif; ?>
				</small>
			</div>
			<div class="span5">
				<span class="small hasTooltip" title="<?php echo JHtml::tooltipText('MOD_LOGGED_LAST_ACTIVITY'); ?>">
					<span class="icon-calendar"></span> <?php echo JHtml::_('date', $user->time, JText::_('DATE_FORMAT_LC2')); ?>
				</span>
			</div>
		</div>
	<?php endforeach; ?>
</div>
