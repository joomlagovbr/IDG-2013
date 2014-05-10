<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The updates provisioning Controller
 */
class AkeebaControllerUpdate extends F0FController
{
	public function execute($task)
	{
		$task = 'force';

		return parent::execute($task);
	}

	public function force()
	{
		$this->getThisModel()->getUpdates(true);

		$url = 'index.php?option=' . $this->input->getCmd('option', '');
		$msg = JText::_('AKEEBA_COMMON_UPDATE_INFORMATION_RELOADED');
		$this->setRedirect($url, $msg);
	}
}