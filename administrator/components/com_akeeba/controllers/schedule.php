<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.6.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Configuration Editor controller class
 *
 */
class AkeebaControllerSchedule extends AkeebaControllerDefault
{
	public function add()
	{
		$this->layout = 'form';
		$this->display(false);
	}
}