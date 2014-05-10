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
 * Scripting helper class
 */
class AEUtilScripting
{
	/**
	 * Loads the scripting.ini and returns an array with the domains, the scripts and
	 * the raw data
	 * @return array
	 */
	public static function loadScripting()
	{
		static $scripting = null;

		if (empty($scripting))
		{
			$ds = DIRECTORY_SEPARATOR;
			$ini_file_name = AEFactory::getAkeebaRoot() . $ds . 'core' . $ds . 'scripting.ini';
			if (@file_exists($ini_file_name))
			{
				$raw_data = AEUtilINI::parse_ini_file($ini_file_name, false);
				$domain_keys = explode('|', $raw_data['volatile.akeebaengine.domains']);
				$domains = array();
				foreach ($domain_keys as $key)
				{
					$record = array(
						'domain' => $raw_data['volatile.domain.' . $key . '.domain'],
						'class'  => $raw_data['volatile.domain.' . $key . '.class'],
						'text'   => $raw_data['volatile.domain.' . $key . '.text']
					);
					$domains[$key] = $record;
				}

				$script_keys = explode('|', $raw_data['volatile.akeebaengine.scripts']);
				$scripts = array();
				foreach ($script_keys as $key)
				{
					$record = array(
						'chain' => explode('|', $raw_data['volatile.scripting.' . $key . '.chain']),
						'text'  => $raw_data['volatile.scripting.' . $key . '.text']
					);
					$scripts[$key] = $record;
				}

				$scripting = array(
					'domains' => $domains,
					'scripts' => $scripts,
					'data'    => $raw_data
				);
			}
			else
			{
				$scripting = array();
			}
		}

		return $scripting;
	}

	/**
	 * Imports the volatile scripting parameters to the registry
	 */
	public static function importScriptingToRegistry()
	{
		$scripting = self::loadScripting();
		$configuration = AEFactory::getConfiguration();
		$configuration->mergeArray($scripting['data'], false);
	}

	/**
	 * Returns a volatile scripting parameter for the active backup type
	 *
	 * @param string $key     The relative key, e.g. core.createarchive
	 * @param mixed  $default Default value
	 *
	 * @return mixed
	 */
	public static function getScriptingParameter($key, $default = null)
	{
		static $script = null;

		$configuration = AEFactory::getConfiguration();

		if (is_null($script))
		{
			$script = $configuration->get('akeeba.basic.backup_type', 'full');
		}

		return $configuration->get('volatile.scripting.' . $script . '.' . $key, $default);
	}

	/**
	 * Returns an array with domain keys and domain class names for the current
	 * backup type. The idea is that shifting this array walks through the backup
	 * process. When the array is empty, the backup is done.
	 * @return array
	 */
	public static function getDomainChain()
	{
		$configuration = AEFactory::getConfiguration();
		$script = $configuration->get('akeeba.basic.backup_type', 'full');

		$scripting = self::loadScripting();
		$domains = $scripting['domains'];
		$keys = $scripting['scripts'][$script]['chain'];

		$result = array();
		foreach ($keys as $domain_key)
		{
			$result[] = array(
				'domain' => $domains[$domain_key]['domain'],
				'class'  => $domains[$domain_key]['class']
			);
		}

		return $result;
	}
}
