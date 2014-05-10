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
 * Date conditional filter
 * 
 * It will only backup files modified after a specific date and time
 */
class AEFilterStackPlatformFinder extends AEAbstractFilter
{	
	function __construct()
	{
		$this->object	= 'dbobject';
		$this->subtype	= 'content';
		$this->method	= 'api';
		
		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;
	}

	protected function is_excluded_by_api($test, $root)
	{
		static $finderTables = array(
			'#__finder_links', '#__finder_links_terms0', '#__finder_links_terms1',
			'#__finder_links_terms2', '#__finder_links_terms3', '#__finder_links_terms4',
			'#__finder_links_terms5', '#__finder_links_terms6', '#__finder_links_terms7',
			'#__finder_links_terms8', '#__finder_links_terms9', '#__finder_links_termsa',
			'#__finder_links_termsb', '#__finder_links_termsc', '#__finder_links_termsd',
			'#__finder_links_termse', '#__finder_links_termsf', '#__finder_taxonomy',
			'#__finder_taxonomy_map', '#__finder_terms'
		);
		
		// Not the site's database? Include the tables
		if($root != '[SITEDB]') return false;
		
		// Is it one of the blacklisted tables?
		if(in_array($test, $finderTables)) return true;

		// No match? Just include the file!
		return false;
	}

}