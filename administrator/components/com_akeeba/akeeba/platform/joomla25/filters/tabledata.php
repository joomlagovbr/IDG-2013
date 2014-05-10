<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Subdirectories exclusion filter. Excludes temporary, cache and backup output
 * directories' contents from being backed up.
 */
class AEFilterPlatformTabledata extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'dbobject';
		$this->subtype	= 'content';
		$this->method	= 'direct';
		$this->filter_name = 'PlatformTabledata';

		// We take advantage of the filter class magic to inject our custom filters
		$this->filter_data['[SITEDB]'] = array (
			'#__session',		// Sessions table
			'#__guardxt_runs'	// Guard XT's run log (bloated to the bone)
		);

		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		parent::__construct();
	}

}