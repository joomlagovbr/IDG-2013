<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/*
 * This class is used as template (extend) for most Regular Labs plugins
 * This class is not placed in the Regular Labs Library as a re-usable class because
 * it also needs to be working when the Regular Labs Library is not installed
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use JFactory;
use JInstaller;
use JPlugin;
use JPluginHelper;
use JText;
use ReflectionMethod;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\Protect as RL_Protect;

class Plugin extends JPlugin
{
	public $_alias       = '';
	public $_title       = '';
	public $_lang_prefix = '';

	public $_has_tags              = false;
	public $_enable_in_frontend    = true;
	public $_enable_in_admin       = false;
	public $_can_disable_by_url    = true;
	public $_disable_on_components = false;
	public $_protected_formats     = [];
	public $_page_types            = [];

	private $_init   = false;
	private $_pass   = null;
	private $_helper = null;

	protected function run()
	{
		if ( ! $this->passChecks())
		{
			return false;
		}

		if ( ! $this->getHelper())
		{
			return false;
		}

		$caller = debug_backtrace()[1];

		if (empty($caller))
		{
			return false;
		}

		$event = $caller['function'];

		if ( ! method_exists($this->_helper, $event))
		{
			return false;
		}

		$reflect    = new ReflectionMethod($this->_helper, $event);
		$parameters = $reflect->getParameters();

		$arguments = [];

		// Check if arguments should be passed as reference or not
		foreach ($parameters as $count => $parameter)
		{
			if ($parameter->isPassedByReference())
			{
				$arguments[] = &$caller['args'][$count];
				continue;
			}
			$arguments[] = $caller['args'][$count];
		}

		return call_user_func_array([$this->_helper, $event], $arguments);
	}

	/**
	 * Create the helper object
	 *
	 * @return object|null The plugins helper object
	 */
	private function getHelper()
	{
		// Already initialized, so return
		if ($this->_init)
		{
			return $this->_helper;
		}

		$this->_init = true;

		RL_Language::load('plg_' . $this->_type . '_' . $this->_name);

		$this->init();

		$this->_helper = new Helper;

		return $this->_helper;
	}

	private function passChecks()
	{
		if ( ! is_null($this->_pass))
		{
			return $this->_pass;
		}

		$this->_pass = false;

		if ( ! $this->isFrameworkEnabled())
		{
			return false;
		}

		if ( ! self::passPageTypes())
		{
			return false;
		}

		// allow in frontend?
		if ( ! $this->_enable_in_frontend
			&& ! RL_Document::isAdmin())
		{
			return false;
		}

		// allow in admin?
		if ( ! $this->_enable_in_admin
			&& RL_Document::isAdmin()
			&& ( ! isset(Params::get()->enable_admin) || ! Params::get()->enable_admin))
		{
			return false;
		}

		// disabled by url?
		if ($this->_can_disable_by_url
			&& RL_Protect::isDisabledByUrl($this->_alias))
		{
			return false;
		}

		// disabled by component?
		if ($this->_disable_on_components
			&& RL_Protect::isRestrictedComponent(isset(Params::get()->disabled_components) ? Params::get()->disabled_components : [], 'component'))
		{
			return false;
		}

		// restricted page?
		if (RL_Protect::isRestrictedPage($this->_has_tags, $this->_protected_formats))
		{
			return false;
		}

		if ( ! $this->extraChecks())
		{
			return false;
		}

		$this->_pass = true;

		return true;
	}

	public function passPageTypes()
	{
		if (empty($this->_page_types))
		{
			return true;
		}

		if (in_array('*', $this->_page_types))
		{
			return true;
		}

		if (empty(JFactory::$document))
		{
			return true;
		}

		if (RL_Document::isFeed())
		{
			return in_array('feed', $this->_page_types);
		}

		if (RL_Document::isPDF())
		{
			return in_array('pdf', $this->_page_types);
		}

		$page_type = JFactory::getDocument()->getType();

		if (in_array($page_type, $this->_page_types))
		{
			return true;
		}

		return false;
	}

	public function extraChecks()
	{
		return true;
	}

	public function init()
	{
		return;
	}

	/**
	 * Check if the Regular Labs Library is enabled
	 *
	 * @return bool
	 */
	private function isFrameworkEnabled()
	{
		if ( ! defined('REGULAR_LABS_LIBRARY_ENABLED'))
		{
			$this->setIsFrameworkEnabled();
		}

		if ( ! REGULAR_LABS_LIBRARY_ENABLED)
		{
			$this->throwError('REGULAR_LABS_LIBRARY_NOT_ENABLED');
		}

		return REGULAR_LABS_LIBRARY_ENABLED;
	}

	/**
	 * Set the define with whether the Regular Labs Library is enabled
	 */
	private function setIsFrameworkEnabled()
	{
		// Return false if Regular Labs Library is not installed
		if ( ! $this->isFrameworkInstalled())
		{
			define('REGULAR_LABS_LIBRARY_ENABLED', false);

			return;
		}

		if ( ! JPluginHelper::isEnabled('system', 'regularlabs'))
		{
			$this->throwError('REGULAR_LABS_LIBRARY_NOT_ENABLED');

			define('REGULAR_LABS_LIBRARY_ENABLED', false);

			return;
		}

		define('REGULAR_LABS_LIBRARY_ENABLED', true);
	}

	/**
	 * Check if the Regular Labs Library is installed
	 *
	 * @return bool
	 */
	private function isFrameworkInstalled()
	{
		if ( ! defined('REGULAR_LABS_LIBRARY_INSTALLED'))
		{
			$this->setIsFrameworkInstalled();
		}

		switch (REGULAR_LABS_LIBRARY_INSTALLED)
		{
			case 'outdated':
				$this->throwError('REGULAR_LABS_LIBRARY_OUTDATED');

				return false;

			case 'no':
				$this->throwError('REGULAR_LABS_LIBRARY_NOT_INSTALLED');

				return false;

			case 'yes':
			default:
				return true;
		}
	}

	/**
	 * set the define with whether the Regular Labs Library is installed
	 */
	private function setIsFrameworkInstalled()
	{
		if (
			! is_file(JPATH_PLUGINS . '/system/regularlabs/regularlabs.xml')
			|| ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php')
		)
		{
			define('REGULAR_LABS_LIBRARY_INSTALLED', 'no');

			return;
		}

		$plugin  = JInstaller::parseXMLInstallFile(JPATH_PLUGINS . '/system/regularlabs/regularlabs.xml');
		$library = JInstaller::parseXMLInstallFile(JPATH_LIBRARIES . '/regularlabs/regularlabs.xml');

		if (empty($plugin) || empty($library))
		{
			define('REGULAR_LABS_LIBRARY_INSTALLED', 'no');

			return;
		}

		if (version_compare($plugin['version'], '18.7.10792', '<')
			|| version_compare($library['version'], '18.7.10792', '<'))
		{
			define('REGULAR_LABS_LIBRARY_INSTALLED', 'outdated');

			return;
		}

		define('REGULAR_LABS_LIBRARY_INSTALLED', 'yes');
	}

	/**
	 * Place an error in the message queue
	 */
	private function throwError($error)
	{
		// Return if page is not an admin page or the admin login page
		if (
			! JFactory::getApplication()->isClient('administrator')
			|| JFactory::getUser()->get('guest')
		)
		{
			return;
		}

		// load the admin language file
		JFactory::getLanguage()->load('plg_' . $this->_type . '_' . $this->_name, JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name);

		$text = JText::sprintf($this->_lang_prefix . '_' . $error, JText::_($this->_title));
		$text = JText::_($text) . ' ' . JText::sprintf($this->_lang_prefix . '_EXTENSION_CAN_NOT_FUNCTION', JText::_($this->_title));

		// Check if message is not already in queue
		$messagequeue = JFactory::getApplication()->getMessageQueue();
		foreach ($messagequeue as $message)
		{
			if ($message['message'] == $text)
			{
				return;
			}
		}

		JFactory::getApplication()->enqueueMessage($text, 'error');
	}
}

