<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaupdate
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JoomlaupdateViewDefault $this */
?>
<fieldset>
	<legend>
		<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_UPDATEFOUND'); ?>
	</legend>
	<p>
		<?php echo JText::sprintf($this->langKey, $this->updateSourceKey); ?>
	</p>

	<table class="table table-striped">
		<tbody>
		<tr>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_INSTALLED'); ?>
			</td>
			<td>
				<?php echo $this->updateInfo['installed']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_LATEST'); ?>
			</td>
			<td>
				<?php echo $this->updateInfo['latest']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_PACKAGE'); ?>
			</td>
			<td>
				<a href="<?php echo $this->updateInfo['object']->downloadurl->_data; ?>">
					<?php echo $this->updateInfo['object']->downloadurl->_data; ?>
				</a>
			</td>
		</tr>
		<?php if (isset($this->updateInfo['object']->get('infourl')->_data)
			&& isset($this->updateInfo['object']->get('infourl')->title)) : ?>
			<tr>
				<td>
					<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_INFOURL'); ?>
				</td>
				<td>
					<a href="<?php echo $this->updateInfo['object']->get('infourl')->_data; ?>">
						<?php echo $this->updateInfo['object']->get('infourl')->title; ?>
					</a>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_METHOD'); ?>
			</td>
			<td>
				<?php echo $this->methodSelect; ?>
			</td>
		</tr>
		<tr id="row_ftp_hostname" <?php echo $this->ftpFieldsDisplay; ?>>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_FTP_HOSTNAME'); ?>
			</td>
			<td>
				<input type="text" name="ftp_host" value="<?php echo $this->ftp['host']; ?>" />
			</td>
		</tr>
		<tr id="row_ftp_port" <?php echo $this->ftpFieldsDisplay; ?>>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_FTP_PORT'); ?>
			</td>
			<td>
				<input type="text" name="ftp_port" value="<?php echo $this->ftp['port']; ?>" />
			</td>
		</tr>
		<tr id="row_ftp_username" <?php echo $this->ftpFieldsDisplay; ?>>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_FTP_USERNAME'); ?>
			</td>
			<td>
				<input type="text" name="ftp_user" value="<?php echo $this->ftp['username']; ?>" />
			</td>
		</tr>
		<tr id="row_ftp_password" <?php echo $this->ftpFieldsDisplay; ?>>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_FTP_PASSWORD'); ?>
			</td>
			<td>
				<input type="password" name="ftp_pass" value="<?php echo $this->ftp['password']; ?>" />
			</td>
		</tr>
		<tr id="row_ftp_directory" <?php echo $this->ftpFieldsDisplay; ?>>
			<td>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_FTP_DIRECTORY'); ?>
			</td>
			<td>
				<input type="text" name="ftp_root" value="<?php echo $this->ftp['directory']; ?>" />
			</td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td>
				&nbsp;
			</td>
			<td>
				<button class="btn btn-primary" type="submit">
					<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_INSTALLUPDATE'); ?>
				</button>
			</td>
		</tr>
		</tfoot>
	</table>
</fieldset>
