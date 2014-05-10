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
class AEFilterPlatformFiles extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'file';
		$this->subtype	= 'all';
		$this->method	= 'direct';
		$this->filter_name = 'PlatformFiles';

		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		// Get the site's root
		$configuration = AEFactory::getConfiguration();

		if($configuration->get('akeeba.platform.override_root',0)) {
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		} else {
			$root = '[SITEROOT]';
		}

		// We take advantage of the filter class magic to inject our custom filters
		$this->filter_data[$root] = array (
			'kickstart.php',
			'error_log',
			'administrator/error_log'
		);

		parent::__construct();
	}

}