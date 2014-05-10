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
class AEFilterPlatformSkipfiles extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'content';
		$this->method	= 'direct';
		$this->filter_name = 'PlatformSkipfiles';

		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		// We take advantage of the filter class magic to inject our custom filters
		$configuration = AEFactory::getConfiguration();

		$jreg = JFactory::getConfig();
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$tmpdir = $jreg->get('tmp_path');
		} else {
			$tmpdir = $jreg->getValue('config.tmp_path');
		}

		// Get the site's root
		if($configuration->get('akeeba.platform.override_root',0)) {
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		} else {
			$root = '[SITEROOT]';
		}

		$this->filter_data[$root] = array (
			// Output & temp directory of the component
			self::treatDirectory($configuration->get('akeeba.basic.output_directory')),
			// Joomla! temporary directory
			self::treatDirectory($tmpdir),
			// default temp directory
			'tmp',
			// Joomla! front- and back-end cache, as reported by Joomla!
			self::treatDirectory(JPATH_CACHE),
			self::treatDirectory(JPATH_ADMINISTRATOR.'/cache'),
			self::treatDirectory(JPATH_ROOT.'/cache'),
			// cache directories fallback
			'cache',
			'administrator/cache',
			// This is not needed except on sites running SVN or beta releases
			self::treatDirectory(JPATH_ROOT.'/installation'),
			// ...and the fallback
			'installation',
			// Joomla! front- and back-end cache, as calculated by us (redundancy, for funky server setups)
			self::treatDirectory( AEPlatform::getInstance()->get_site_root().'/cache' ),
			self::treatDirectory( AEPlatform::getInstance()->get_site_root().'/administrator/cache'),
			// Default backup output (many people change it, forget to remove old backup archives and they end up backing up old backups)
			'administrator/components/com_akeeba/backup',
			// MyBlog's cache
			self::treatDirectory( AEPlatform::getInstance()->get_site_root().'/components/libraries/cmslib/cache' ),
			// ...and fallback
			'components/libraries/cmslib/cache',
			// The logs directory
			'logs'
		);

		parent::__construct();
	}

	private static function treatDirectory($directory)
	{
		// Get the site's root
		$configuration = AEFactory::getConfiguration();
		if($configuration->get('akeeba.platform.override_root',0)) {
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
			if(stristr($root, '[')) {
				$root = AEUtilFilesystem::translateStockDirs($root);
			}
			$site_root = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($root));
		} else {
			$site_root = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath(JPATH_ROOT));
		}

		$directory = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($directory));

		// Trim site root from beginning of directory
		if( substr($directory, 0, strlen($site_root)) == $site_root )
		{
			$directory = substr($directory, strlen($site_root));
			if( substr($directory,0,1) == '/' ) $directory = substr($directory,1);
		}

		return $directory;
	}
}