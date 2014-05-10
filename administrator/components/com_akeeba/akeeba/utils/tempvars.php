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
 * Temporary variables management class. Everything is stored serialized in an INI
 * file on the temporary directory.
 */
class AEUtilTempvars
{
	static $storageEngine = '';

	static public function getStorageEngine()
	{
		if (empty(self::$storageEngine))
		{
			self::setStorageEngine();
		}

		return self::$storageEngine;
	}

	static public function setStorageEngine($engine = null)
	{
		if (empty($engine))
		{
			$config = AEFactory::getConfiguration();
			$usedb = $config->get('akeeba.core.usedbstorage', 0);
			$engine = $usedb ? 'db' : 'file';
		}
		self::$storageEngine = $engine;
	}

	/**
	 * Returns the fully qualified path to the storage file
	 * @return unknown_type
	 */
	static public function get_storage_filename($tag = null)
	{
		static $basepath = null;

		if (self::getStorageEngine() == 'db')
		{
			return empty($tag) ? 'storage' : $tag;
		}
		else
		{
			if (is_null($basepath))
			{
				$registry = AEFactory::getConfiguration();
				$basepath = $registry->get('akeeba.basic.output_directory') . DIRECTORY_SEPARATOR;
			}

			if (empty($tag))
			{
				$tag = 'storage';
			}

			return $basepath . 'akeeba_' . $tag . '.php';
		}
	}

	/**
	 * Resets the storage. This method removes all stored values.
	 * @return    bool    True on success
	 */
	public static function reset($tag = null)
	{
		switch (self::getStorageEngine())
		{
			case 'file':
				$filename = self::get_storage_filename($tag);
				if (!is_file($filename) && !is_link($filename))
				{
					return false;
				}

				return @unlink(self::get_storage_filename($tag));
				break;
			case 'db':
				$dbtag = self::get_storage_filename($tag);
				$db = AEFactory::getDatabase();
				$sql = $db->getQuery(true)
					->delete($db->qn('#__ak_storage'))
					->where($db->qn('tag') . ' = ' . $db->q($dbtag));
				$db->setQuery($sql);

				try
				{
					$db->query();
				}
				catch (Exception $exc)
				{
					return false;
				}

				return true;

				break;
		}

	}

	public static function set(&$value, $tag = null)
	{
		$storage_filename = self::get_storage_filename($tag);

		switch (self::getStorageEngine())
		{
			case 'file':
				// Remove old file (if exists)
				if (file_exists($storage_filename))
				{
					@unlink($storage_filename);
				}

				// Open the new file
				$fp = @fopen($storage_filename, 'wb');
				if ($fp === false)
				{
					return false;
				}

				// Add a header
				fputs($fp, "<?php die('Access denied'); ?>\n");
				fwrite($fp, self::encode($value));
				fclose($fp);

				return true;
				break;

			case 'db':
				$db = AEFactory::getDatabase();

				// Delete any old records
				$sql = $db->getQuery(true)
					->delete($db->qn('#__ak_storage'))
					->where($db->qn('tag') . ' = ' . $db->q($storage_filename));
				$db->setQuery($sql);
				try
				{
					$db->query();
				}
				catch (Exception $exc)
				{
					return false;
				}

				// Add the new record
				$sql = $db->getQuery(true)
					->insert($db->qn('#__ak_storage'))
					->columns(array(
								   $db->qn('tag'),
								   $db->qn('data'),
							  ))->values($db->q($storage_filename) . ',' . $db->q(self::encode($value)));

				$db->setQuery($sql);

				try
				{
					$db->query();
				}
				catch (Exception $exc)
				{
					return false;
				}

				return true;

				break;
		}
	}

	public static function &get($tag = null)
	{
		$storage_filename = self::get_storage_filename($tag);

		$ret = false;

		switch (self::getStorageEngine())
		{
			case 'file':
				$rawdata = @file_get_contents($storage_filename);
				if ($rawdata === false)
				{
					return $ret;
				}
				if (strpos($rawdata, "\n") === false)
				{
					return $ret;
				}
				list($header, $data) = explode("\n", $rawdata);
				unset($rawdata);
				unset($header);
				break;

			case 'db':
				$db = AEFactory::getDatabase();
				$sql = $db->getQuery(true)
					->select($db->qn('data'))
					->from($db->qn('#__ak_storage'))
					->where($db->qn('tag') . ' = ' . $db->q($storage_filename));
				$db->setQuery($sql);
				$data = $db->loadResult();
				break;
		}

		$ret = self::decode($data);
		unset($data);

		return $ret;
	}

	public static function encode(&$data)
	{
		// Should I base64-encode?
		if (function_exists('base64_encode') && function_exists('base64_decode'))
		{
			return base64_encode($data);
		}
		elseif (function_exists('convert_uuencode') && function_exists('convert_uudecode'))
		{
			return convert_uuencode($data);
		}
		else
		{
			return $data;
		}
	}

	public static function decode(&$data)
	{
		if (function_exists('base64_encode') && function_exists('base64_decode'))
		{
			return base64_decode($data);
		}
		elseif (function_exists('convert_uuencode') && function_exists('convert_uudecode'))
		{
			return convert_uudecode($data);
		}
		else
		{
			return $data;
		}
	}
}