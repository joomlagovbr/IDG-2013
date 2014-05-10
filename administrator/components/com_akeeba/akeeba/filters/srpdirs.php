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
 * System Restore Point - Directories
 */
class AEFilterSrpdirs extends AEAbstractFilter
{
	protected $params = array();
	protected $alloweddirs = array();
	protected $strictalloweddirs = array();

	function __construct()
	{
		if (!defined('_JEXEC'))
		{
			$this->enabled = false;

			return;
		}

		$this->object  = 'dir';
		$this->subtype = 'all';
		$this->method  = 'api';

		if (AEFactory::getKettenrad()->getTag() != 'restorepoint')
		{
			$this->enabled = false;
		}
		else
		{
			$this->init();
		}
	}

	protected function init()
	{
		// Fetch the configuration
		$config = AEFactory::getConfiguration();
		$this->params = (object)array(
			'type'        => $config->get('core.filters.srp.type', 'component'),
			'group'       => $config->get('core.filters.srp.group', 'group'),
			'name'        => $config->get('core.filters.srp.name', 'name'),
			'customdirs'  => $config->get('core.filters.srp.customdirs', array()),
			'customfiles' => $config->get('core.filters.srp.customfiles', array()),
			'langfiles'   => $config->get('core.filters.srp.langfiles', array())
		);

		$this->alloweddirs = array();

		// Process custom directories
		if (is_array($this->params->customdirs))
		{
			foreach ($this->params->customdirs as $dir)
			{
				$dir = $this->treatDirectory($dir);
				$this->alloweddirs[] = $dir;
			}
		}

		// Process custom files
		if (is_array($this->params->customfiles))
		{
			foreach ($this->params->customfiles as $file)
			{
				$dir = dirname($file);
				$dir = $this->treatDirectory($dir);

				if (!in_array($dir, $this->strictalloweddirs))
				{
					$this->strictalloweddirs[] = $dir;
				}

				if (!in_array($dir, $this->alloweddirs))
				{
					$this->alloweddirs[] = $dir;
				}
			}
		}

		$this->alloweddirs[] = 'language';
		$this->alloweddirs[] = 'administrator/language';

		// Process core directories
		$this->params->type  = (array) $this->params->type;
		$this->params->name  = (array) $this->params->name;
		$this->params->group = (array) $this->params->group;

		for($i = 0; $i < count($this->params->type); $i++)
		{
			$info['name']  = $this->params->name[$i];
			$info['group'] = $this->params->group[$i];

			$this->addDirs($this->params->type[$i], $info);
		}
	}

	protected function is_excluded_by_api($test, $root)
	{
		// Allow scanning the root
		if (empty($test))
		{
			return false;
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
		{
			$siteRoot = rtrim(AEPlatform::getInstance()->get_site_root(), '/' . DIRECTORY_SEPARATOR);
			$thisTest = rtrim($test, '/' . DIRECTORY_SEPARATOR);

			if ($thisTest == $siteRoot)
			{
				return false;
			}
		}

		if (!empty($this->strictalloweddirs))
		{
			foreach ($this->strictalloweddirs as $dir)
			{
				$len = strlen($dir);

				if (strlen($test) > $len)
				{
					if ($test == $dir)
					{
						return false;
					}

					if (substr($test, 0, $len + 1) == $dir . '/')
					{
                        // Double check that I'm not excluding a folder that I'll need later
                        if(!in_array($test, $this->alloweddirs))
                        {
                            return true;
                        }
					}
				}
			}
		}

		// Look if the directory is within the allowed paths
		foreach ($this->alloweddirs as $dir)
		{
			$len = strlen($dir);
			if (strlen($test) < $len)
			{
				// We have to allow scanning parent directories
				$len = strlen($test);
				if (substr($dir, 0, $len) == $test)
				{
					// We need a different slash count. If the slash count is the same
					// we have a border case, e.g. administrator/com_admin is perceived
					// as the parent to administrator/com_adminTOOLS which is, of course,
					// false!

					$stringStatsTest = count_chars($test, 1);
					$stringStatsDir = count_chars($dir, 1);

					if (!array_key_exists(47, $stringStatsTest) || !array_key_exists(47, $stringStatsDir))
					{
						return false;
					}

					if ($stringStatsTest[47] == $stringStatsDir[47])
					{
						// Border case!
						continue;
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				// We have to fully allow explicitly allowed directories
				if (substr($test, 0, $len) == $dir)
				{
					return false;
				}
			}
		}

		// Exclude directories by default
		return true;
	}

	private function addDirs($type, $info)
	{
		switch ($type)
		{
			case 'component':
				$extension = $info['name'];

				if(strpos($extension, 'com_') === false)
				{
					$extension = 'com_'.$extension;
				}

				$this->alloweddirs[] = 'components/' . $extension;
				$this->alloweddirs[] = 'administrator/components/' . $extension;
				$this->alloweddirs[] = 'media/' . $extension;
				$this->alloweddirs[] = 'media/' . $info['name'];
				break;

			case 'file':
				break;

			case 'library':
				break;

			case 'module':
				$extension = $info['name'];

				if(strpos($extension, 'mod_') === false)
				{
					$extension = 'mod_'.$extension;
				}

				if (strpos($info['group'], 'admin') !== false)
				{
					$this->alloweddirs[] = 'administrator/modules/' . $extension;
				}
				else
				{
					$this->alloweddirs[] = 'modules/' . $extension;
				}

				break;

			case 'plugin':
				// This is required for Joomla! 1.5 compatibility
				// $this->alloweddirs[] = 'plugins/'.$this->params->group;
				// This is required for Joomla! 1.6 compatibility
				$this->alloweddirs[] = 'plugins/' . $info['group'] . '/' . $info['name'];
				break;

			case 'template':
				if (strpos($info['group'], 'admin') !== false)
				{
					$this->alloweddirs[] = 'administrator/templates/' . $info['name'];
				}
				else
				{
					$this->alloweddirs[] = 'templates/' . $info['name'];
				}
				break;

			default:
				$this->alloweddirs = array();
		}
	}

	private static function treatDirectory($directory)
	{
		static $site_root = null;

		if (is_null($site_root))
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