<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 2.2
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Folder bowser controller
 *
 */
class AkeebaControllerFtpbrowser extends AkeebaControllerDefault
{
	public function execute($task)
	{
		$task = 'browse';
		parent::execute($task);
	}

	public function browse($cachable = false, $urlparams = false)
	{
		$model = $this->getThisModel();

		// Grab the data and push them to the model
		$model->host =		$this->input->get('host', '', 'string');
		$model->port =		$this->input->get('port', 21, 'int');
		$model->passive =	$this->input->get('passive', 1, 'int');
		$model->ssl =		$this->input->get('ssl', 0, 'int');
		$model->username =	$this->input->get('username', '', 'none', 2);
		$model->password =	$this->input->get('password', '', 'none', 2);
		$model->directory =	$this->input->get('directory', '', 'none', 2);

		$ret = $model->doBrowse();

		@ob_end_clean();
		echo '###'.json_encode($ret).'###';
		flush();
		JFactory::getApplication()->close();
	}
}