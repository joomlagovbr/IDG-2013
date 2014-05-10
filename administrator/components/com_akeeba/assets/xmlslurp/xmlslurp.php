<?php
/**
 * @package   LiveUpdate
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU GPLv3 or later <https://www.gnu.org/licenses/gpl.html>
 */

defined('_JEXEC') or die();

class LiveUpdateXMLSlurp extends JObject
{
	private $_info = array();

	public function getInfo($extensionName, $xmlName)
	{
		if (!array_key_exists($extensionName, $this->_info))
		{
			$this->_info[$extensionName] = $this->fetchInfo($extensionName, $xmlName);
		}

		return $this->_info[$extensionName];
	}

	/**
	 * Gets the version information of an extension by reading its XML file
	 *
	 * @param string $extensionName The name of the extension, e.g. com_foobar, mod_foobar, plg_foobar or tpl_foobar.
	 * @param string $xmlName       The name of the XML manifest filename. If empty uses $extensionName.xml
	 */
	private function fetchInfo($extensionName, $xmlName)
	{
		$type = strtolower(substr($extensionName, 0, 3));
		switch ($type)
		{
			case 'com':
				return $this->getComponentData($extensionName, $xmlName);
				break;
			case 'mod':
				return $this->getModuleData($extensionName, $xmlName);
				break;
			case 'plg':
				return $this->getPluginData($extensionName, $xmlName);
				break;
			case 'tpl':
				return $this->getTemplateData($extensionName, $xmlName);
				break;
			case 'pkg':
				return $this->getGenericManifest($extensionName, $xmlName, 'packages');
				break;
			case 'lib':
				return $this->getGenericManifest($extensionName, $xmlName, 'libraries');
				break;
			default:

				if (strtolower(substr($extensionName, 0, 4)) == 'file')
				{
					return $this->getGenericManifest($extensionName, $xmlName, 'files');
				}
				else
				{
					return array('version' => '', 'date' => '');
				}
		}
	}

	/**
	 * Gets the version information of a component by reading its XML file
	 *
	 * @param string $extensionName The name of the extension, e.g. com_foobar
	 * @param string $xmlName       The name of the XML manifest filename. If empty uses $extensionName.xml
	 */
	private function getComponentData($extensionName, $xmlName)
	{
		$extensionName = strtolower($extensionName);
		$path = JPATH_ADMINISTRATOR . '/components/' . $extensionName;
		$altExtensionName = substr($extensionName, 4);

		JLoader::import('joomla.filesystem.file');
		if (JFile::exists("$path/$xmlName"))
		{
			$filename = "$path/$xmlName";
		}
		elseif (JFile::exists("$path/$extensionName.xml"))
		{
			$filename = "$path/$extensionName.xml";
		}
		elseif (JFile::exists("$path/$altExtensionName.xml"))
		{
			$filename = "$path/$altExtensionName.xml";
		}
		elseif (JFile::exists("$path/manifest.xml"))
		{
			$filename = "$path/manifest.xml";
		}
		else
		{
			$filename = $this->searchForManifest($path);
			if ($filename === false)
			{
				$filename = null;
			}
		}

		if (empty($filename))
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		try
		{
			$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
		}
		catch (Exception $e)
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		// Need to check for extension (since 1.6) and install (supported through 2.5)
		if ($xml->getName() != 'extension' && $xml->getName() != 'install')
		{
			unset($xml);

			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		$data['version'] = $xml->version ? (string)$xml->version : '';
		$data['date'] = $xml->creationDate ? (string)$xml->creationDate : '';
		$data['xmlfile'] = $filename;

		return $data;
	}

	/**
	 * Gets the version information of a module by reading its XML file
	 *
	 * @param string $extensionName The name of the extension, e.g. mod_foobar
	 * @param string $xmlName       The name of the XML manifest filename. If empty uses $extensionName.xml
	 */
	private function getModuleData($extensionName, $xmlName)
	{
		$extensionName = strtolower($extensionName);
		$altExtensionName = substr($extensionName, 4);

		JLoader::import('joomla.filesystem.folder');
		JLoader::import('joomla.filesystem.file');
		$path = JPATH_SITE . '/modules/' . $extensionName;
		if (!JFolder::exists($path))
		{
			$path = JPATH_ADMINISTRATOR . '/modules/' . $extensionName;
		}
		if (!JFolder::exists($path))
		{
			// Joomla! 1.5
			// 1. Check front-end
			$path = JPATH_ADMINISTRATOR . '/modules';
			$filename = "$path/$xmlName";
			if (!JFile::exists($filename))
			{
				$filename = "$path/$extensionName.xml";
			}
			if (!JFile::exists($filename))
			{
				$filename = "$path/$altExtensionName.xml";
			}
			// 2. Check front-end
			if (!JFile::exists($filename))
			{
				$path = JPATH_SITE . '/modules';
				$filename = "$path/$xmlName";
				if (!JFile::exists($filename))
				{
					$filename = "$path/$extensionName.xml";
				}
				if (!JFile::exists($filename))
				{
					$filename = "$path/$altExtensionName.xml";
				}
				if (!JFile::exists($filename))
				{
					return array('version' => '', 'date' => '');
				}
			}
		}
		else
		{
			// Joomla! 1.6
			$filename = "$path/$xmlName";
			if (!JFile::exists($filename))
			{
				$filename = "$path/$extensionName.xml";
			}
			if (!JFile::exists($filename))
			{
				$filename = "$path/$altExtensionName.xml";
			}
			if (!JFile::exists($filename))
			{
				return array('version' => '', 'date' => '');
			}
		}

		if (empty($filename))
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		try
		{
			$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
		}
		catch (Exception $e)
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		// Need to check for extension (since 1.6) and install (supported through 2.5)
		if ($xml->getName() != 'extension' && $xml->getName() != 'install')
		{
			unset($xml);

			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		$data['version'] = $xml->version ? (string)$xml->version : '';
		$data['date'] = $xml->creationDate ? (string)$xml->creationDate : '';
		$data['xmlfile'] = $filename;

		return $data;
	}

	/**
	 * Gets the version information of a plugin by reading its XML file
	 *
	 * @param string $extensionName The name of the plugin, e.g. plg_foobar
	 * @param string $xmlName       The name of the XML manifest filename. If empty uses $extensionName.xml
	 */
	private function getPluginData($extensionName, $xmlName)
	{
		$extensionName = strtolower($extensionName);
		$altExtensionName = substr($extensionName, 4);

		JLoader::import('joomla.filesystem.folder');
		JLoader::import('joomla.filesystem.file');

		$base = JPATH_PLUGINS;

		// Get a list of directories
		$stack = JFolder::folders($base, '.', true, true);
		foreach ($stack as $path)
		{
			$filename = "$path/$xmlName";
			if (JFile::exists($filename))
			{
				break;
			}
			$filename = "$path/$extensionName.xml";
			if (JFile::exists($filename))
			{
				break;
			}
			$filename = "$path/$altExtensionName.xml";
			if (JFile::exists($filename))
			{
				break;
			}
		}

		if (!JFile::exists($filename))
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		try
		{
			$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
		}
		catch (Exception $e)
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		// Need to check for extension (since 1.6) and install (supported through 2.5)
		if ($xml->getName() != 'extension' && $xml->getName() != 'install')
		{
			unset($xml);

			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		$data['version'] = $xml->version ? (string)$xml->version : '';
		$data['date'] = $xml->creationDate ? (string)$xml->creationDate : '';
		$data['xmlfile'] = $filename;

		return $data;
	}

	/**
	 * Gets the version information of a template by reading its XML file
	 *
	 * @param string $extensionName The name of the template, e.g. tpl_foobar
	 * @param string $xmlName       The name of the XML manifest filename. If empty uses $extensionName.xml or templateDetails.xml
	 */
	private function getTemplateData($extensionName, $xmlName)
	{
		$extensionName = strtolower($extensionName);
		$altExtensionName = substr($extensionName, 4);

		JLoader::import('joomla.filesystem.folder');
		JLoader::import('joomla.filesystem.file');

		// First look for administrator templates
		$path = JPATH_THEMES . '/' . $altExtensionName;
		if (!JFolder::exists($path))
		{
			// Then look for front-end templates
			$path = JPATH_SITE . '/templates/' . $altExtensionName;
			if (!JFolder::exists($path))
			{
				return array('version' => '', 'date' => '');
			}
		}

		$filename = "$path/$xmlName";
		if (!JFile::exists($filename))
		{
			$filename = "$path/templateDetails.xml";
		}
		if (!JFile::exists($filename))
		{
			$filename = "$path/$extensionName.xml";
		}
		if (!JFile::exists($filename))
		{
			$filename = "$path/$altExtensionName.xml";
		}
		if (!JFile::exists($filename))
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		try
		{
			$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
		}
		catch (Exception $e)
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		// Need to check for extension (since 1.6) and install (supported through 2.5)
		if ($xml->getName() != 'extension' && $xml->getName() != 'install')
		{
			unset($xml);

			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		$data['version'] = $xml->version ? (string)$xml->version : '';
		$data['date'] = $xml->creationDate ? (string)$xml->creationDate : '';
		$data['xmlfile'] = $filename;

		return $data;
	}

	/**
	 * Generic function to get a manifest file stored inside the administrator/manifests folder.
	 * At the moment this applies to files, packages and libraries extensions.
	 *
	 * @param   string $extensionName Extension name
	 * @param   string $xmlName       XML filename
	 * @param   string $folder        Folder inside the manifests one
	 *
	 * @return array
	 */
	private function getGenericManifest($extensionName, $xmlName, $folder)
	{
		$extensionName = strtolower($extensionName);
		$altExtensionName = substr($extensionName, 4);

		JLoader::import('joomla.filesystem.folder');
		JLoader::import('joomla.filesystem.file');

		$path = JPATH_ADMINISTRATOR . '/manifests/' . $folder;

		$filename = "$path/$xmlName";

		if (!JFile::exists($filename))
		{
			$filename = "$path/$extensionName.xml";
		}

		if (!JFile::exists($filename))
		{
			$filename = "$path/$altExtensionName.xml";
		}

		if (!JFile::exists($filename))
		{
			return array('version' => '', 'date' => '');
		}

		if (empty($filename))
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		try
		{
			$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
		}
		catch (Exception $e)
		{
			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		if ($xml->getName() != 'extension')
		{
			unset($xml);

			return array('version' => '', 'date' => '', 'xmlfile' => '');
		}

		$data['version'] = $xml->version ? (string)$xml->version : '';
		$data['date'] = $xml->creationDate ? (string)$xml->creationDate : '';
		$data['xmlfile'] = $filename;

		return $data;
	}

	/**
	 * Scans a directory for XML manifest files. The first XML file to be a
	 * manifest wins.
	 *
	 * @var $path string The path to look into
	 *
	 * @return string|bool The full path to a manifest file or false if not found
	 */
	private function searchForManifest($path)
	{
		JLoader::import('joomla.filesystem.folder');
		$files = JFolder::files($path, '\.xml$', false, true);
		if (!empty($files))
		{
			foreach ($files as $filename)
			{
				try
				{
					$xml = new SimpleXMLElement($filename, LIBXML_NONET, true);
				}
				catch (Exception $e)
				{
					continue;
				}

				// Check for extension (since 1.6) and install (supported through 2.5)
				if (($xml->getName() != 'extension' && $xml->getName() != 'install'))
				{
					continue;
				}
				unset($xml);

				return $filename;
			}
		}

		return false;
	}
}
