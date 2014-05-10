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
class AkeebaControllerBrowser extends AkeebaControllerDefault
{
	public function display($cachable = false, $urlparams = false)
	{
		$folder = $this->input->get('folder', '', 'string');
		$processfolder = $this->input->get('processfolder', 0, 'int');

		$model = $this->getThisModel();
		$model->setState('folder', $folder);
		$model->setState('processfolder', $processfolder);
		$model->makeListing();

		parent::display();

		/*
		@ob_end_flush();
		flush();
		JFactory::getApplication()->close();
		*/
	}
}