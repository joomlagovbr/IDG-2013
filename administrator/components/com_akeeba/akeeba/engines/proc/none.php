<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

class AEPostprocNone extends AEAbstractPostproc
{

	public function __construct()
	{
		// No point in breaking the step; we simply do nothing :)
		$this->break_after = false;
		$this->break_before = false;
		$this->allow_deletes = false;
	}

	public function processPart($absolute_filename, $upload_as = null)
	{
		// Really nothing to do!!
		return true;
	}
}