<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * MVC controller class for Database Table filters
 *
 */
class AkeebaControllerDbef extends AkeebaControllerDefault
{
	public function execute($task)
	{
		if($task != 'ajax') {
			$task = 'browse';
		}

		parent::execute($task);
	}

	/**
	 * Handles the "display" task, which displays a folder and file list
	 *
	 */
	public function browse($cachable = false, $urlparams = false)
	{
		$task = $this->input->get('task', 'normal', 'cmd');
		$this->getThisModel()->setState('browse_task', $task);

		parent::display($cachable, $urlparams);
	}

	/**
	 * AJAX proxy.
	 */
	public function ajax()
	{
		// Parse the JSON data and reset the action query param to the resulting array
		$action_json = $this->input->get('action', '', 'none', 2);
		$action = json_decode($action_json);

		$model = $this->getThisModel();
		$model->setState('action', $action);

		$ret = $model->doAjax();

		@ob_end_clean();
		echo '###' . json_encode($ret) . '###';
		flush();
		JFactory::getApplication()->close();
	}
}