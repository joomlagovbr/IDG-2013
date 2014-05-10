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
 * The Configuration Wizard controller class
 */
class AkeebaControllerConfwiz extends AkeebaControllerDefault
{
	public function  __construct($config = array()) {
		parent::__construct($config);

		$this->modelName = 'AkeebaModelConfwiz';
	}

	public function add()
	{
		$this->display(false);
	}

	public function ajax()
	{
		$act = $this->input->get('act', '', 'cmd');
		$model = F0FModel::getAnInstance('Confwiz', 'AkeebaModel');
		$model->setState('act', $act);
		$ret = $model->runAjax();

		@ob_end_clean();
		echo '###' . json_encode( $ret ) . '###';
		flush();
		JFactory::getApplication()->close();
	}
}