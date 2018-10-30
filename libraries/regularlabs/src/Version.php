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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

use JComponentHelper;
use JFactory;
use JFile;
use JHtml;
use JRoute;
use JSession;
use JText;
use JUri;

/**
 * Class Version
 * @package RegularLabs\Library
 */
class Version
{
	/**
	 * Get the version of the given extension
	 *
	 * @param        $alias
	 * @param string $type
	 * @param string $folder
	 *
	 * @return string
	 */
	public static function get($alias, $type = 'component', $folder = 'system')
	{
		return trim(Extension::getXmlValue('version', $alias, $type, $folder));
	}

	/**
	 * Get the version of the given plugin
	 *
	 * @param        $alias
	 * @param string $folder
	 *
	 * @return string
	 */
	public static function getPluginVersion($alias, $folder = 'system')
	{
		return self::get($alias, 'plugin', $folder);
	}

	/**
	 * Get the version of the given component
	 *
	 * @param $alias
	 *
	 * @return string
	 */
	public static function getComponentVersion($alias)
	{
		return self::get($alias, 'component');
	}

	/**
	 * Get the version of the given module
	 *
	 * @param $alias
	 *
	 * @return string
	 */
	public static function getModuleVersion($alias)
	{
		return self::get($alias, 'module');
	}

	/**
	 * Get the version message
	 *
	 * @param $alias
	 *
	 * @return string
	 */
	public static function getMessage($alias)
	{
		if ( ! $alias)
		{
			return '';
		}

		$name  = Extension::getNameByAlias($alias);
		$alias = Extension::getAliasByName($alias);

		if ( ! $version = self::get($alias))
		{
			return '';
		}

		JHtml::_('jquery.framework');

		Document::script('regularlabs/script.min.js');
		$url    = 'download.regularlabs.com/extensions.xml?j=3&e=' . $alias;
		$script = "
			jQuery(document).ready(function() {
				RegularLabsScripts.loadajax(
					'" . $url . "',
					'RegularLabsScripts.displayVersion( data, \"" . $alias . "\", \"" . str_replace(['FREE', 'PRO'], '', $version) . "\" )',
					'RegularLabsScripts.displayVersion( \"\" )',
					null, null, null, (60 * 60)
				);
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		return '<div class="alert alert-success" style="display:none;" id="regularlabs_version_' . $alias . '">' . self::getMessageText($alias, $name, $version) . '</div>';
	}

	/**
	 * Get the full footer
	 *
	 * @param     $name
	 * @param int $copyright
	 *
	 * @return string
	 */
	public static function getFooter($name, $copyright = true)
	{
		$html = [];

		$html[] = '<div class="rl_footer_extension">' . self::getFooterName($name) . '</div>';

		if ($copyright)
		{
			$html[] = '<div class="rl_footer_review">' . self::getFooterReview($name) . '</div>';
			$html[] = '<div class="rl_footer_logo">' . self::getFooterLogo() . '</div>';
			$html[] = '<div class="rl_footer_copyright">' . self::getFooterCopyright() . '</div>';
		}

		return '<div class="rl_footer">' . implode('', $html) . '</div>';
	}

	/**
	 * Get the version message text
	 *
	 * @param $alias
	 * @param $name
	 * @param $version
	 *
	 * @return array|string
	 */
	private static function getMessageText($alias, $name, $version)
	{
		list($url, $onclick) = self::getUpdateLink($alias, $version);

		$href    = $onclick ? '' : 'href="' . $url . '" target="_blank" ';
		$onclick = $onclick ? 'onclick="' . $onclick . '" ' : '';

		$is_pro  = strpos($version, 'PRO') !== false;
		$version = str_replace(['FREE', 'PRO'], ['', ' <small>[PRO]</small>'], $version);

		$msg = '<div class="text-center">'
			. '<span class="ghosted">'
			. JText::sprintf('RL_NEW_VERSION_OF_AVAILABLE', JText::_($name))
			. '</span>'
			. '<br>'
			. '<a ' . $href . $onclick . ' class="btn btn-large btn-success">'
			. '<span class="icon-upload"></span> '
			. StringHelper::html_entity_decoder(JText::sprintf('RL_UPDATE_TO', '<span id="regularlabs_newversionnumber_' . $alias . '"></span>'))
			. '</a>';

		if ( ! $is_pro)
		{
			$msg .= ' <a href="https://www.regularlabs.com/purchase?ext=' . $alias . '" target="_blank" class="btn btn-large btn-primary">'
				. '<span class="icon-basket"></span> '
				. JText::_('RL_GO_PRO')
				. '</a>';
		}

		$msg .= '<br>'
			. '<span class="ghosted">'
			. '[ <a href="https://www.regularlabs.com/' . $alias . '#changelog" target="_blank">'
			. JText::_('RL_CHANGELOG')
			. '</a> ]'
			. '<br>'
			. JText::sprintf('RL_CURRENT_VERSION', $version)
			. '</span>'
			. '</div>';

		return StringHelper::html_entity_decoder($msg);
	}

	/**
	 * Get the url and onclick function for the update link
	 *
	 * @param $alias
	 * @param $version
	 *
	 * @return array
	 */
	private static function getUpdateLink($alias, $version)
	{
		$is_pro = strpos($version, 'PRO') !== false;

		if (
			! JFile::exists(JPATH_ADMINISTRATOR . '/components/com_regularlabsmanager/regularlabsmanager.xml')
			|| ! JComponentHelper::isInstalled('com_regularlabsmanager')
			|| ! JComponentHelper::isEnabled('com_regularlabsmanager')
		)
		{
			$url = $is_pro
				? 'https://www.regularlabs.com/' . $alias . '#download'
				: JRoute::_('index.php?option=com_installer&view=update');

			return [$url, ''];
		}

		$config = JComponentHelper::getParams('com_regularlabsmanager');

		$key = trim($config->get('key'));

		if ($is_pro && ! $key)
		{
			return ['index.php?option=com_regularlabsmanager', ''];
		}

		JHtml::_('bootstrap.framework');
		JHtml::_('behavior.modal');
		jimport('joomla.filesystem.file');

		JHtml::_('jquery.framework');

		Document::script('regularlabs/script.min.js');
		JFactory::getDocument()->addScriptDeclaration(
			"
			var RLEM_TIMEOUT = " . (int) $config->get('timeout', 5) . ";
			var RLEM_TOKEN = '" . JSession::getFormToken() . "';
		"
		);
		Document::script('regularlabsmanager/script.min.js', '18.7.10792');

		$url = 'https://download.regularlabs.com?ext=' . $alias . '&j=3';

		if ($is_pro)
		{
			$url .= '&k=' . strtolower(substr($key, 0, 8) . md5(substr($key, 8)));
		}

		return ['', 'RegularLabsManager.openModal(\'update\', [\'' . $alias . '\'], [\'' . $url . '\'], true);'];
	}

	/**
	 * Get the extension name and version for the footer
	 *
	 * @param $name
	 *
	 * @return string
	 */
	private static function getFooterName($name)
	{
		$name = JText::_($name);

		if ( ! $version = self::get($name))
		{
			return $name;
		}

		if (strpos($version, 'PRO') !== false)
		{
			return $name . ' v' . str_replace('PRO', '', $version) . ' <small>[PRO]</small>';
		}

		if (strpos($version, 'FREE') !== false)
		{
			return $name . ' v' . str_replace('FREE', '', $version) . ' <small>[FREE]</small>';
		}

		return $name . ' v' . $version;
	}

	/**
	 * Get the review text for the footer
	 *
	 * @param $name
	 *
	 * @return string
	 */
	private static function getFooterReview($name)
	{
		$alias = Extension::getAliasByName($name);

		$jed_url = 'http://regl.io/jed-' . $alias . '#reviews';

		return
			StringHelper::html_entity_decoder(
				JText::sprintf(
					'RL_JED_REVIEW',
					'<a href="' . $jed_url . '" target="_blank">',
					'</a>'
					. ' <a href="' . $jed_url . '" target="_blank" class="stars">'
					. str_repeat('<span class="icon-star"></span>', 5)
					. '</a>'
				)
			);
	}

	/**
	 * Get the Regular Labs logo for the footer
	 *
	 * @return string
	 */
	private static function getFooterLogo()
	{
		return
			JText::sprintf(
				'RL_POWERED_BY',
				'<a href="https://www.regularlabs.com" target="_blank">'
				. '<img src="' . JUri::root() . 'media/regularlabs/images/logo.png" width="135" height="24" alt="Regular Labs">'
				. '</a>'
			);
	}

	/**
	 * Get the copyright text for the footer
	 *
	 * @return string
	 */
	private static function getFooterCopyright()
	{
		return JText::_('RL_COPYRIGHT') . ' &copy; ' . date('Y') . ' Regular Labs - ' . JText::_('RL_ALL_RIGHTS_RESERVED');
	}
}
