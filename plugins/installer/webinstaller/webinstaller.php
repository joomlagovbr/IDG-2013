<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Installer.webinstaller
 *
 * @copyright   Copyright (C) 2013 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Rule\UrlRule;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

/**
 * Support for the "Install from Web" tab
 *
 * @since  1.0
 */
class PlgInstallerWebinstaller extends CMSPlugin
{
	/**
	 * The URL for the remote server.
	 *
	 * @var    string
	 * @since  2.0
	 */
	const REMOTE_URL = 'https://appscdn.joomla.org/webapps/';

	/**
	 * The application object.
	 *
	 * @var    CMSApplication
	 * @since  2.0
	 */
	protected $app;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  2.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Flag tracking whether the Hathor admin template is in use
	 *
	 * @var    boolean|null
	 * @since  1.0
	 * @deprecated  Removed when the plugin is merged to 4.0
	 */
	private $_hathor = null;

	/**
	 * The URL to install from
	 *
	 * @var    string|null
	 * @since  1.0
	 */
	private $installfrom = null;

	/**
	 * Flag if the document is in a RTL direction
	 *
	 * @var    integer|null
	 * @since  1.0
	 */
	private $rtl = null;

	/**
	 * Event listener for the `onInstallerBeforeDisplay` event.
	 *
	 * @param   boolean  $showJedAndWebInstaller  Flag indicating the install from web prompt should be displayed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @deprecated  Removed when the plugin is merged to 4.0
	 */
	public function onInstallerBeforeDisplay(&$showJedAndWebInstaller)
	{
		$showJedAndWebInstaller = false;
	}

	/**
	 * Event listener for the `onInstallerAddInstallationTab` event.
	 *
	 * @return  array  Returns an array with the tab information
	 *
	 * @since   2.0
	 */
	public function onInstallerAddInstallationTab()
	{
		$tab = array(
			'name'  => 'web',
			'label' => Text::_('COM_INSTALLER_INSTALL_FROM_WEB'),
		);

		// Render the input
		ob_start();
		include PluginHelper::getLayoutPath('installer', 'webinstaller', $this->isHathor() ? 'hathor' : 'default');
		$tab['content'] = ob_get_clean();

		return $tab;
	}

	/**
	 * Event listener for the `onBeforeCompileHead` event.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 * @deprecated  Removed when the plugin is merged to 4.0
	 * @note        This is required to ensure the plugin JS is appended after the tabs are initialized,
	 *              logic would otherwise be in the `onInstallerAddInstallationTab` listener
	 */
	public function onBeforeCompileHead()
	{
		$installfrom = $this->getInstallFrom();

		// Push language strings to the JavaScript store
		Text::script('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL');
		Text::script('COM_INSTALLER_WEBINSTALLER_INSTALL_OBSOLETE');
		Text::script('COM_INSTALLER_WEBINSTALLER_INSTALL_UPDATE_AVAILABLE');
		Text::script('JLIB_INSTALLER_UPDATE');
		Text::script('PLG_INSTALLER_WEBINSTALLER_CANNOT_INSTALL_EXTENSION_IN_PLUGIN');
		Text::script('PLG_INSTALLER_WEBINSTALLER_REDIRECT_TO_EXTERNAL_SITE_TO_INSTALL');

		HTMLHelper::_('bootstrap.framework');
		HTMLHelper::_('script', 'plg_installer_webinstaller/client.min.js', array('version' => 'auto', 'relative' => true));
		HTMLHelper::_('stylesheet', 'plg_installer_webinstaller/client.min.css', array('version' => 'auto', 'relative' => true));

		$devLevel = Version::PATCH_VERSION;
		$extraVer = Version::EXTRA_VERSION;

		if (!empty($extraVer))
		{
			$devLevel .= '-' . $extraVer;
		}

		$installer = new Installer;
		$manifest  = $installer->isManifest(__DIR__ . '/webinstaller.xml');

		$doc = Factory::getDocument();

		$doc->addScriptOptions(
			'plg_installer_webinstaller',
			array(
				'base_url'        => addslashes(self::REMOTE_URL),
				'installat_url'   => base64_encode(Uri::current() . '?option=com_installer&view=install'),
				'installfrom_url' => addslashes($installfrom),
				'product'         => base64_encode(Version::PRODUCT),
				'release'         => base64_encode(Version::MAJOR_VERSION . '.' . Version::MINOR_VERSION),
				'dev_level'       => base64_encode($devLevel),
				'installfromon'   => $installfrom ? 1 : 0,
				'language'        => base64_encode(Factory::getLanguage()->getTag()),
				// The below options are deprecated and removed when the plugin is merged to 4.0
				'is_hathor'       => $this->isHathor() ? 1 : 0,
				'pv'              => base64_encode($manifest->version),
			)
		);

		$javascript = <<<JS
jQuery(document).ready(function () {
    var ifwOptions = Joomla.getOptions('plg_installer_webinstaller', {});
    var ifwLink = jQuery('#myTabTabs').find('li a[href="#web"]');
    var ifwRelativeSelector = 'li';

	if (ifwOptions.is_hathor) {
		jQuery('#mywebinstaller').show();
		ifwLink = jQuery('#mywebinstaller').find('a');
		ifwRelativeSelector = 'a';
	}

	if (ifwOptions.installfromon) {
		ifwLink.click();
	}

	if (!ifwOptions.is_hathor && ifwLink.closest('li').hasClass('active')) {
		if (!Joomla.apps.loaded) {
			Joomla.apps.initialize();
		}
	}

	ifwLink.closest(ifwRelativeSelector).click(function (event) {
		if (!Joomla.apps.loaded) {
			Joomla.apps.initialize();
		}
	});

	if (ifwOptions.installfrom_url !== '') {
	    ifwLink.closest(ifwRelativeSelector).click();
	}

	ifwLink.on('shown', function (e) {
		if (!Joomla.apps.loaded) {
			Joomla.apps.initialize();
		}
	});
});

		
JS;
		$doc->addScriptDeclaration($javascript);
	}

	/**
	 * Internal check to determine if the Hathor admin template is in use
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 * @deprecated  Removed when the plugin is merged to 4.0
	 */
	private function isHathor()
	{
		if (is_null($this->_hathor))
		{
			$this->_hathor = strtolower($this->app->getTemplate()) === 'hathor';
		}

		return $this->_hathor;
	}

	/**
	 * Internal check to determine if the output is in a RTL direction
	 *
	 * @return  integer
	 *
	 * @since   1.0
	 */
	private function isRTL()
	{
		if (is_null($this->rtl))
		{
			$this->rtl = strtolower(Factory::getDocument()->getDirection()) === 'rtl' ? 1 : 0;
		}

		return $this->rtl;
	}

	/**
	 * Get the install from URL
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	private function getInstallFrom()
	{
		if ($this->installfrom === null)
		{
			$installfrom = base64_decode($this->app->input->getBase64('installfrom', ''));

			$field = new SimpleXMLElement('<field></field>');
			$rule  = new UrlRule;

			if ($rule->test($field, $installfrom) && preg_match('/\.xml\s*$/', $installfrom))
			{
				$update = new Update;
				$update->loadFromXML($installfrom);
				$package_url = trim($update->get('downloadurl', false)->_data);

				if ($package_url)
				{
					$installfrom = $package_url;
				}
			}

			$this->installfrom = $installfrom;
		}

		return $this->installfrom;
	}
}
