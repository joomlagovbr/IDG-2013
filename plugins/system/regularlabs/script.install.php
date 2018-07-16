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

		private function deleteOldLibraryFiles()
		{
			$this->delete(
				[
					JPATH_LIBRARIES . '/regularlabs/helpers/assignments',
					JPATH_LIBRARIES . '/regularlabs/helpers/assignment.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/assignments.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/cache.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/field.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/functions.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/groupfield.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/helper.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/html.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/htmlfix.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/licenses.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/mobile_detect.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/parameters.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/protect.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/string.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/tags.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/text.php',
					JPATH_LIBRARIES . '/regularlabs/helpers/version.php',
				]
			);
		}
	}
}
