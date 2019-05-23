<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\Folder as JFolder;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Installer\Installer as JInstaller;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Class Extension
 * @package RegularLabs\Library
 */
class Extension
{
	/**
	 * Get the full path to the extension folder
	 *
	 * @param string $extension
	 * @param string $basePath
	 * @param string $check_folder
	 *
	 * @return string
	 */
	public static function getPath($extension = 'plg_system_regularlabs', $basePath = JPATH_ADMINISTRATOR, $check_folder = '')
	{
		$basePath = $basePath ?: JPATH_SITE;

		if ( ! in_array($basePath, [JPATH_ADMINISTRATOR, JPATH_SITE]))
		{
			return $basePath;
		}

		$extension = str_replace('.sys', '', $extension);

		switch (true)
		{
			case (strpos($extension, 'mod_') === 0):
				$path = 'modules/' . $extension;
				break;

			case (strpos($extension, 'plg_') === 0):
				list($prefix, $folder, $name) = explode('_', $extension, 3);
				$path = 'plugins/' . $folder . '/' . $name;
				break;

			case (strpos($extension, 'com_') === 0):
			default:
				$path = 'components/' . $extension;
				break;
		}

		$check_folder = $check_folder ? '/' . $check_folder : '';

		if (is_dir($basePath . '/' . $path . $check_folder))
		{
			return $basePath . '/' . $path;
		}

		if (is_dir(JPATH_ADMINISTRATOR . '/' . $path . $check_folder))
		{
			return JPATH_ADMINISTRATOR . '/' . $path;
		}

		if (is_dir(JPATH_SITE . '/' . $path . $check_folder))
		{
			return JPATH_SITE . '/' . $path;
		}

		return $basePath;
	}

	/**
	 * Check if all extension types of a given extension are installed
	 *
	 * @param string $extension
	 * @param array  $types
	 *
	 * @return bool
	 */
	public static function areInstalled($extension, $types = ['plugin'])
	{
		foreach ($types as $type)
		{
			$folder = 'system';

			if (is_array($type))
			{
				list($type, $folder) = $type;
			}

			if ( ! self::isInstalled($extension, $type, $folder))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if the given extension is installed
	 *
	 * @param string $extension
	 * @param string $type
	 * @param string $folder
	 *
	 * @return bool
	 */
	public static function isInstalled($extension, $type = 'component', $folder = 'system')
	{
		$extension = strtolower($extension);

		switch ($type)
		{
			case 'component':
				if (file_exists(JPATH_ADMINISTRATOR . '/components/com_' . $extension . '/' . $extension . '.php')
					|| file_exists(JPATH_ADMINISTRATOR . '/components/com_' . $extension . '/admin.' . $extension . '.php')
					|| file_exists(JPATH_SITE . '/components/com_' . $extension . '/' . $extension . '.php')
				)
				{
					if ($extension == 'cookieconfirm' && file_exists(JPATH_ADMINISTRATOR . '/components/com_cookieconfirm/version.php'))
					{
						// Only Cookie Confirm 2.0.0.rc1 and above is supported, because
						// previous versions don't have isCookiesAllowed()
						require_once JPATH_ADMINISTRATOR . '/components/com_cookieconfirm/version.php';

						if (version_compare(COOKIECONFIRM_VERSION, '2.2.0.rc1', '<'))
						{
							return false;
						}
					}

					return true;
				}
				break;

			case 'plugin':
				return file_exists(JPATH_PLUGINS . '/' . $folder . '/' . $extension . '/' . $extension . '.php');

			case 'module':
				return (file_exists(JPATH_ADMINISTRATOR . '/modules/mod_' . $extension . '/' . $extension . '.php')
					|| file_exists(JPATH_ADMINISTRATOR . '/modules/mod_' . $extension . '/mod_' . $extension . '.php')
					|| file_exists(JPATH_SITE . '/modules/mod_' . $extension . '/' . $extension . '.php')
					|| file_exists(JPATH_SITE . '/modules/mod_' . $extension . '/mod_' . $extension . '.php')
				);

			case 'library':
				return JFolder::exists(JPATH_LIBRARIES . '/' . $extension);
		}

		return false;
	}

	/**
	 * Check if the Regular Labs Library is enabled
	 *
	 * @return bool
	 */
	public static function isEnabled($extension, $type = 'component', $folder = 'system')
	{
		$extension = strtolower($extension);

		if ( ! self::isInstalled($extension, $type, $folder))
		{
			return false;
		}

		switch ($type)
		{
			case 'component':
				return JComponentHelper::isEnabled($extension);

			case 'plugin':
				return JPluginHelper::isEnabled($folder, $extension);

			case 'module':
				return JModuleHelper::isEnabled($extension);
		}

		return false;
	}

	/**
	 * Check if the Regular Labs Library is enabled
	 *
	 * @return bool
	 */
	public static function isFrameworkEnabled()
	{
		return JPluginHelper::isEnabled('system', 'regularlabs');
	}

	/**
	 * Return an alias and element name based on the given extension name
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	public static function getAliasAndElement(&$name)
	{
		$name    = self::getNameByAlias($name);
		$alias   = self::getAliasByName($name);
		$element = self::getElementByAlias($alias);

		return [$alias, $element];
	}

	/**
	 * Return the name based on the given extension alias
	 *
	 * @param string $alias
	 *
	 * @return string
	 */
	public static function getNameByAlias($alias)
	{
		// Alias is a language string
		if (strpos($alias, ' ') === false && strtoupper($alias) == $alias)
		{
			return JText::_($alias);
		}

		// Alias has a space and/or capitals, so is already a name
		if (strpos($alias, ' ') !== false || $alias !== strtolower($alias))
		{
			return $alias;
		}

		return JText::_(self::getXMLValue('name', $alias));
	}

	/**
	 * Return an alias based on the given extension name
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public static function getAliasByName($name)
	{
		$alias = RegEx::replace('[^a-z0-9]', '', strtolower($name));

		switch ($alias)
		{
			case 'advancedmodules':
				return 'advancedmodulemanager';

			case 'advancedtemplates':
				return 'advancedtemplatemanager';

			case 'nonumbermanager':
				return 'nonumberextensionmanager';

			case 'what-nothing':
				return 'whatnothing';
		}

		return $alias;
	}

	/**
	 * Return an element name based on the given extension alias
	 *
	 * @param string $alias
	 *
	 * @return string
	 */
	public static function getElementByAlias($alias)
	{
		$alias = self::getAliasByName($alias);

		switch ($alias)
		{
			case 'advancedmodulemanager':
				return 'advancedmodules';

			case 'advancedtemplatemanager':
				return 'advancedtemplates';

			case 'nonumberextensionmanager':
				return 'nonumbermanager';
		}

		return $alias;
	}

	/**
	 * Return a value from an extensions main xml file based on the given key
	 *
	 * @param string $key
	 * @param string $alias
	 * @param string $type
	 * @param string $folder
	 *
	 * @return string
	 */
	public static function getXMLValue($key, $alias, $type = '', $folder = '')
	{
		if ( ! $xml = self::getXML($alias, $type, $folder))
		{
			return '';
		}

		if ( ! isset($xml[$key]))
		{
			return '';
		}

		return isset($xml[$key]) ? $xml[$key] : '';
	}

	/**
	 * Return an extensions main xml array
	 *
	 * @param string $alias
	 * @param string $type
	 * @param string $folder
	 *
	 * @return array|bool
	 */
	public static function getXML($alias, $type = '', $folder = '')
	{
		if ( ! $file = self::getXMLFile($alias, $type, $folder))
		{
			return false;
		}

		return JInstaller::parseXMLInstallFile($file);
	}

	/**
	 * Return an extensions main xml file name (including path)
	 *
	 * @param string $alias
	 * @param string $type
	 * @param string $folder
	 *
	 * @return string
	 */
	public static function getXMLFile($alias, $type = '', $folder = '')
	{
		$element = self::getElementByAlias($alias);

		$files = [];

		// Components
		if (empty($type) || $type == 'component')
		{
			$files[] = JPATH_ADMINISTRATOR . '/components/com_' . $element . '/' . $element . '.xml';
			$files[] = JPATH_SITE . '/components/com_' . $element . '/' . $element . '.xml';
			$files[] = JPATH_ADMINISTRATOR . '/components/com_' . $element . '/com_' . $element . '.xml';
			$files[] = JPATH_SITE . '/components/com_' . $element . '/com_' . $element . '.xml';
		}

		// Plugins
		if (empty($type) || $type == 'plugin')
		{
			if ( ! empty($folder))
			{
				$files[] = JPATH_PLUGINS . '/' . $folder . '/' . $element . '/' . $element . '.xml';
				$files[] = JPATH_PLUGINS . '/' . $folder . '/' . $element . '.xml';
			}

			// System Plugins
			$files[] = JPATH_PLUGINS . '/system/' . $element . '/' . $element . '.xml';
			$files[] = JPATH_PLUGINS . '/system/' . $element . '.xml';

			// Editor Button Plugins
			$files[] = JPATH_PLUGINS . '/editors-xtd/' . $element . '/' . $element . '.xml';
			$files[] = JPATH_PLUGINS . '/editors-xtd/' . $element . '.xml';
		}

		// Modules
		if (empty($type) || $type == 'module')
		{
			$files[] = JPATH_ADMINISTRATOR . '/modules/mod_' . $element . '/' . $element . '.xml';
			$files[] = JPATH_SITE . '/modules/mod_' . $element . '/' . $element . '.xml';
			$files[] = JPATH_ADMINISTRATOR . '/modules/mod_' . $element . '/mod_' . $element . '.xml';
			$files[] = JPATH_SITE . '/modules/mod_' . $element . '/mod_' . $element . '.xml';
		}

		foreach ($files as $file)
		{
			if ( ! file_exists($file))
			{
				continue;
			}

			return $file;
		}

		return '';
	}

	public static function isAuthorised($require_core_auth = true)
	{
		$user = JFactory::getUser();

		if ($user->get('guest'))
		{
			return false;
		}

		if ( ! $require_core_auth)
		{
			return true;
		}

		if (
			! $user->authorise('core.edit', 'com_content')
			&& ! $user->authorise('core.edit.own', 'com_content')
			&& ! $user->authorise('core.create', 'com_content')
		)
		{
			return false;
		}

		return true;
	}

	public static function isEnabledInArea($params)
	{
		if ( ! isset($params->enable_frontend))
		{
			return true;
		}

		// Only allow in frontend
		if ($params->enable_frontend == 2 && Document::isClient('administrator'))
		{
			return false;
		}

		// Do not allow in frontend
		if ( ! $params->enable_frontend && Document::isClient('site'))
		{
			return false;
		}

		return true;
	}

	public static function isEnabledInComponent($params)
	{
		if ( ! isset($params->disabled_components))
		{
			return true;
		}

		return ! Protect::isRestrictedComponent($params->disabled_components);
	}

	public static function getById($id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName(['extension_id', 'manifest_cache']))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('extension_id') . ' = ' . (int) $id);
		$db->setQuery($query);

		return $db->loadObject();
	}
}
