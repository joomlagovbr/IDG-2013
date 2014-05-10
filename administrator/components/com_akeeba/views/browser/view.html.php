<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 2.2
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaViewBrowser extends F0FViewHtml
{
	public function onAdd($tpl = null)
	{
		$model = $this->getModel();

		$this->folder =					$model->getState('folder');
		$this->folder_raw =				$model->getState('folder_raw');
		$this->parent =					$model->getState('parent');
		$this->exists =					$model->getState('exists');
		$this->inRoot =					$model->getState('inRoot');
		$this->openbasedirRestricted =	$model->getState('openbasedirRestricted');
		$this->writable =				$model->getState('writable');
		$this->subfolders =				$model->getState('subfolders');
		$this->breadcrumbs =			$model->getState('breadcrumbs');

		return true;
	}
}