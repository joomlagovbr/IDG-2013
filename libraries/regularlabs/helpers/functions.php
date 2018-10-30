<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/* @DEPRECATED */

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Extension as RL_Extension;
use RegularLabs\Library\File as RL_File;
use RegularLabs\Library\Http as RL_Http;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\Xml as RL_Xml;

/**
 * Framework Functions
 */
class RLFunctions
{
	public static function getContents($url, $timeout = 20)
	{
		return ! class_exists('RegularLabs\Library\Http') ? '' : RL_Http::get($url, $timeout);
	}

	public static function getByUrl($url, $timeout = 20)
	{
		return ! class_exists('RegularLabs\Library\Http') ? '' : RL_Http::getFromServer($url, $timeout);
	}

	public static function isFeed()
	{
		return class_exists('RegularLabs\Library\Document') && RL_Document::isFeed();
	}

	public static function script($file, $version = '')
	{
		class_exists('RegularLabs\Library\Document') && RL_Document::script($file, $version);
	}

	public static function stylesheet($file, $version = '')
	{
		class_exists('RegularLabs\Library\Document') && RL_Document::stylesheet($file, $version);
	}

	public static function addScriptVersion($url)
	{
		jimport('joomla.filesystem.file');

		$version = '';

		if (JFile::exists(JPATH_SITE . $url))
		{
			$version = filemtime(JPATH_SITE . $url);
		}

		self::script($url, $version);
	}

	public static function addStyleSheetVersion($url)
	{
		jimport('joomla.filesystem.file');

		$version = '';

		if (JFile::exists(JPATH_SITE . $url))
		{
			$version = filemtime(JPATH_SITE . $url);
		}

		self::stylesheet($url, $version);
	}

	protected static function getFileByFolder($folder, $file)
	{
		return ! class_exists('RegularLabs\Library\File') ? '' : RL_File::getMediaFile($folder, $file);
	}

	public static function getComponentBuffer()
	{
		return ! class_exists('RegularLabs\Library\Document') ? '' : RL_Document::getBuffer();
	}

	public static function getAliasAndElement(&$name)
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getAliasAndElement($name);
	}

	public static function getNameByAlias($alias)
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getNameByAlias($alias);
	}

	public static function getAliasByName($name)
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getAliasByName($name);
	}

	public static function getElementByAlias($alias)
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getElementByAlias($alias);
	}

	public static function getXMLValue($key, $alias, $type = 'component', $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getXMLValue($key, $alias, $type, $folder);
	}

	public static function getXML($alias, $type = 'component', $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getXML($alias, $type, $folder);
	}

	public static function getXMLFile($alias, $type = 'component', $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getXMLFile($alias, $type, $folder);
	}

	public static function extensionInstalled($extension, $type = 'component', $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::isInstalled($extension, $type, $folder);
	}

	public static function getExtensionPath($extension = 'plg_system_regularlabs', $basePath = JPATH_ADMINISTRATOR, $check_folder = '')
	{
		return ! class_exists('RegularLabs\Library\Extension') ? '' : RL_Extension::getPath($extension, $basePath, $check_folder);
	}

	public static function loadLanguage($extension = 'plg_system_regularlabs', $basePath = '', $reload = false)
	{
		return class_exists('RegularLabs\Library\Language') && RL_Language::load($extension, $basePath, $reload);
	}

	public static function xmlToObject($url, $root = '')
	{
		return ! class_exists('RegularLabs\Library\Xml') ? '' : RL_Xml::toObject($url, $root);
	}
}
