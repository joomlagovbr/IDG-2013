<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.5
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaDispatcher extends F0FDispatcher
{
	public $defaultView = 'backup';

	public function onBeforeDispatch() {
		$result = parent::onBeforeDispatch();

		if($result) {
			// Merge the language overrides
			$paths = array(JPATH_ADMINISTRATOR, JPATH_ROOT);
			$jlang = JFactory::getLanguage();
			$jlang->load($this->component, $paths[0], 'en-GB', true);
			$jlang->load($this->component, $paths[0], null, true);
			$jlang->load($this->component, $paths[1], 'en-GB', true);
			$jlang->load($this->component, $paths[1], null, true);

			$jlang->load($this->component.'.override', $paths[0], 'en-GB', true);
			$jlang->load($this->component.'.override', $paths[0], null, true);
			$jlang->load($this->component.'.override', $paths[1], 'en-GB', true);
			$jlang->load($this->component.'.override', $paths[1], null, true);

			// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
			if(function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set')) {
				if(function_exists('error_reporting')) {
					$oldLevel = error_reporting(0);
				}
				$serverTimezone = @date_default_timezone_get();
				if(empty($serverTimezone) || !is_string($serverTimezone)) $serverTimezone = 'UTC';
				if(function_exists('error_reporting')) {
					error_reporting($oldLevel);
				}
				@date_default_timezone_set( $serverTimezone);
			}

			// Necessary defines for Akeeba Engine
			if(!defined('AKEEBAENGINE')) {
				define('AKEEBAENGINE', 1); // Required for accessing Akeeba Engine's factory class
				define('AKEEBAROOT', JPATH_ADMINISTRATOR.'/components/com_akeeba/akeeba');
			}

			// I think I still use that stuff somewhere
			if(!defined('JPATH_COMPONENT_ADMINISTRATOR'))
			{
				define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_akeeba' );
			}

			// Make sure we have a profile set throughout the component's lifetime
			$session = JFactory::getSession();
			$profile_id = $session->get('profile', null, 'akeeba');
			if(is_null($profile_id))
			{
				// No profile is set in the session; use default profile
				$session->set('profile', 1, 'akeeba');
			}

			// Load the factory
			require_once JPATH_ADMINISTRATOR.'/components/com_akeeba/akeeba/factory.php';

			// Load the Akeeba Backup configuration and check user access permission
			$aeconfig = AEFactory::getConfiguration();
			AEPlatform::getInstance()->load_configuration();
			unset($aeconfig);

			// Preload helpers
			require_once JPATH_ADMINISTRATOR.'/components/com_akeeba/helpers/includes.php';
			require_once JPATH_ADMINISTRATOR.'/components/com_akeeba/helpers/escape.php';

			// If JSON functions don't exist, load our compatibility layer
			if( (!function_exists('json_encode')) || (!function_exists('json_decode')) )
			{
				require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/jsonlib.php';
			}

			// Load Akeeba Strapper
			include_once JPATH_ROOT.'/media/akeeba_strapper/strapper.php';
			AkeebaStrapper::bootstrap();
		}

		return $result;
	}

	public function dispatch() {
		// Look for controllers in the plugins folder
		$option = $this->input->get('option','com_foobar', 'cmd');
		$view = $this->input->get('view',$this->defaultView, 'cmd');
		$c = F0FInflector::singularize($view);
		$alt_path = JPATH_SITE.'/components/'.$option.'/plugins/controllers/'.$c.'.php';

		JLoader::import('joomla.filesystem.file');
		if(JFile::exists($alt_path))
		{
			// The requested controller exists and there you load it...
			require_once($alt_path);
		}

		$this->input->set('view', $this->view);

		parent::dispatch();
	}
}