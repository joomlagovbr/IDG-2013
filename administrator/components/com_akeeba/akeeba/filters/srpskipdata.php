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

/**
 * System Restore Point - Skip Database Data
 */
class AEFilterSrpskipdata extends AEAbstractFilter
{
	private $params = array();

	function __construct()
	{
		$this->object = 'dbobject';
		$this->subtype = 'content';
		$this->method = 'api';

		if (AEFactory::getKettenrad()->getTag() != 'restorepoint')
		{
			$this->enabled = false;
		}
		else
		{
			$this->init();
		}
	}

	private function init()
	{
		// Fetch the configuration
		$config = AEFactory::getConfiguration();
		$this->params = (object)array(
			'skiptables' => $config->get('core.filters.srp.skiptables', array())
		);

	}

	protected function is_excluded_by_api($test, $root)
	{
		$barename = (substr($test, 0, 3) == '#__') ? substr($test, 3) : $test;

		// Is it one of our skiptables?
		if (in_array($barename, $this->params->skiptables))
		{
			return true;
		}

		// All other tables should be backed up in full
		return false;
	}
}