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

defined('_JEXEC') or die;

if ( ! class_exists('PlgSystemRegularLabsInstallerScript'))
{
	require_once __DIR__ . '/script.install.helper.php';

	class PlgSystemRegularLabsInstallerScript extends PlgSystemRegularLabsInstallerScriptHelper
	{
		public $name           = 'REGULAR_LABS_LIBRARY';
		public $alias          = 'regularlabs';
		public $extension_type = 'plugin';
		public $show_message   = false;

		public function onBeforeInstall($route)
		{
			if ( ! $this->isNewer())
			{
				$this->softbreak = true;

				return false;
			}

			return true;
		}

		public function uninstall($adapter)
		{
			$this->deleteLibrary();
		}

		private function deleteLibrary()
		{
			$this->delete(
				[
					JPATH_LIBRARIES . '/regularlabs',
				]
			);
		}
	}
}
