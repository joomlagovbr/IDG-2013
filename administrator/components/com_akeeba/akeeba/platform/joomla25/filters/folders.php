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
 * Folder exclusion filter. Excludes certain hosting directories.
 */
class AEFilterPlatformFolders extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'all';
		$this->method	= 'direct';
		$this->filter_name = 'PlatformFolders';

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
			'awstats',
			'cgi-bin'
		);

		parent::__construct();
	}

}