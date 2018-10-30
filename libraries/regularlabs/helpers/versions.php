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

use RegularLabs\Library\Version as RL_Version;

class RLVersions
{
	public static function getXMLVersion($alias, $urlformat = false, $type = 'component', $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Version') ? '' : RL_Version::get($alias, $type, $folder);
	}

	public static function getPluginXMLVersion($alias, $folder = 'system')
	{
		return ! class_exists('RegularLabs\Library\Version') ? '' : RL_Version::getPluginVersion($alias, $folder);
	}

	public static function render($alias)
	{
		return ! class_exists('RegularLabs\Library\Version') ? '' : RL_Version::getMessage($alias);
	}

	public static function getFooter($name, $copyright = 1)
	{
		return ! class_exists('RegularLabs\Library\Version') ? '' : RL_Version::getFooter($name, $copyright);
	}
}
