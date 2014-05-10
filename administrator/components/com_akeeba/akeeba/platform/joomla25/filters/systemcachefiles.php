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
 * Files exclusion filter based on regular expressions
 */
class AEFilterPlatformSystemcachefiles extends AEAbstractFilter
{
	function __construct()
	{
		$this->object	= 'file';
		$this->subtype	= 'all';
		$this->method	= 'regex';

		if(empty($this->filter_name)) $this->filter_name = strtolower(basename(__FILE__,'.php'));

		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		parent::__construct();

		// Get the site's root
		$configuration = AEFactory::getConfiguration();

		if($configuration->get('akeeba.platform.override_root',0)) {
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		} else {
			$root = '[SITEROOT]';
		}

		$this->filter_data[$root] = array(
			'#/Thumbs.db$#',
			'#^Thumbs.db$#',
			'#/.DS_Store$#i',
			'#^.DS_Store$#i'
		);
	}
}