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

/* Windows system detection */
if (!defined('_AKEEBA_IS_WINDOWS'))
{
	if (function_exists('php_uname'))
	{
		define('_AKEEBA_IS_WINDOWS', stristr(php_uname(), 'windows'));
	}
	else
	{
		define('_AKEEBA_IS_WINDOWS', DIRECTORY_SEPARATOR == '\\');
	}
}

/**
 * A filesystem scanner which uses opendir() and is smart enough to make large directories
 * be scanned inside a step of their own.
 *
 * The idea is that if it's not the first operation of this step and the number of contained
 * directories AND files is more than double the number of allowed files per fragment, we should
 * break the step immediately.
 *
 */
class AEScanLarge extends AEAbstractScan
{
	public function &getFiles($folder, &$position)
	{
		$result = $this->scanFolder($folder, $position, false, $threshold_key = 'file', $threshold_default = 100);
		return $result;
	}

	public function &getFolders($folder, &$position)
	{
		$result = $this->scanFolder($folder, $position);
		return $result;
	}

	private function scanFolder($folder, &$position, $forFolders = true, $threshold_key = 'dir', $threshold_default = 50)
	{
		$registry = AEFactory::getConfiguration();

		// Initialize variables
		$arr = array();
		$false = false;

		if (!is_dir($folder) && !is_dir($folder . '/'))
		{
			return $false;
		}

		$di = new DirectoryIterator($folder);

		if (!$di->valid())
		{
			$this->setWarning('Unreadable directory ' . $folder);

			return $false;
		}

		if (!empty($position))
		{
			$di->seek($position);

			if ($di->key() != $position)
			{
				$position = null;

				return $arr;
			}
		}

		$counter = 0;
		$maxCounter = $registry->get("engine.scan.large.{$threshold_key}_threshold", $threshold_default);

		while($di->valid())
		{
			if ($di->isDot())
			{
				$di->next();
				continue;
			}

			if ($di->isDir() != $forFolders)
			{
				$di->next();
				continue;
			}

			$ds = ($folder == '') || ($folder == '/') || (@substr($folder, -1) == '/') || (@substr($folder, -1) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
			$dir = $folder . $ds . $di->getFilename();

			$data = _AKEEBA_IS_WINDOWS ? AEUtilFilesystem::TranslateWinPath($dir) : $dir;

			if ($data)
			{
				$counter++;
				$arr[] = $data;
			}

			if ($counter == $maxCounter)
			{
				break;
			}
			else
			{
				$di->next();
			}
		}

		// Determine the new value for the position
		$di->next();

		if ($di->valid())
		{
			$position = $di->key() - 1;
		}
		else
		{
			$position = null;
		}

		return $arr;
	}
}
