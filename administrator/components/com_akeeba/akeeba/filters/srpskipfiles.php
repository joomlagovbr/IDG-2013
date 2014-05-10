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
 * System Restore Point - Skip files found in site's root
 */
class AEFilterSrpskipfiles extends AEFilterSrpdirs
{

	function __construct()
	{
		$this->object = 'dir';
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

		// Make sure we exclude the current and default backup output directories
		$configuration = AEFactory::getConfiguration();

		if ($configuration->get('akeeba.platform.override_root', 0))
		{
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');
		}
		else
		{
			$root = '[SITEROOT]';
		}

		$this->filter_data[$root] = array(
			// Output & temp directory of the component
			self::treatDirectory($configuration->get('akeeba.basic.output_directory')),
			// Default backup output (many people change it, forget to remove old backup archives and they end up backing up old backups)
			'administrator/components/com_akeeba/backup',
			// MyBlog's cache
			self::treatDirectory(AEPlatform::getInstance()->get_site_root() . '/components/libraries/cmslib/cache'),
			// ...and fallback
			'components/libraries/cmslib/cache',
		);
	}

	protected function is_excluded_by_api($test, $root)
	{
        if(empty($test))
        {
            return false;
        }

		// Is this a directory we're explicitly filtering out (e.g. output dir)?
		if (array_key_exists($root, $this->filter_data))
		{
			// Root found, search in the array
			if (in_array($test, $this->filter_data[$root]))
			{
				// Yes, exlude this directory.
				return true;
			}
		}

		// The following runs only if it's not a hard-coded directory to be
		// excluded.

		// Is the directory in the strictly allowed paths?
		if (count($this->strictalloweddirs))
		{
			foreach ($this->strictalloweddirs as $dir)
			{
				$dirTest = dirname($test);

				if ($dirTest == $dir)
				{
					return false;
				}
			}
		}

		// Is the directory in the allowed paths?
		foreach ($this->alloweddirs as $dir)
		{
			$len = strlen($dir);

			if (strlen($test) < $len)
			{
				continue;
			}
			else
			{
				if ($test == $dir)
				{
					return false;
				}

				if (strpos($test, $dir . '/') === 0)
				{
					return false;
				}
			}
		}

		return true;
	}

	private static function treatDirectory($directory)
	{
		// Get the site's root
		$configuration = AEFactory::getConfiguration();

		if ($configuration->get('akeeba.platform.override_root', 0))
		{
			$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');

			if (stristr($root, '['))
			{
				$root = AEUtilFilesystem::translateStockDirs($root);
			}

			$site_root = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($root));
		}
		else
		{
			$site_root = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath(JPATH_ROOT));
		}

		$directory = AEUtilFilesystem::TrimTrailingSlash(AEUtilFilesystem::TranslateWinPath($directory));

		// Trim site root from beginning of directory
		if (substr($directory, 0, strlen($site_root)) == $site_root)
		{
			$directory = substr($directory, strlen($site_root));

			if (substr($directory, 0, 1) == '/')
			{
				$directory = substr($directory, 1);
			}
		}

		return $directory;
	}

}