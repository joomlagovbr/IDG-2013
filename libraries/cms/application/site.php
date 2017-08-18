<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

/**
 * Joomla! Site Application class
 *
 * @since  3.2
 */
final class JApplicationSite extends JApplicationCms
{
	/**
	 * Option to filter by language
	 *
	 * @var    boolean
	 * @since  3.2
	 * @deprecated  4.0  Will be renamed $language_filter
	 */
	protected $_language_filter = false;

	/**
	 * Option to detect language by the browser
	 *
	 * @var    boolean
	 * @since  3.2
	 * @deprecated  4.0  Will be renamed $detect_browser
	 */
	protected $_detect_browser = false;

	/**
	 * Class constructor.
	 *
	 * @param   JInput                 $input   An optional argument to provide dependency injection for the application's
	 *                                          input object.  If the argument is a JInput object that object will become
	 *                                          the application's input object, otherwise a default input object is created.
	 * @param   Registry               $config  An optional argument to provide dependency injection for the application's
	 *                                          config object.  If the argument is a Registry object that object will become
	 *                                          the application's config object, otherwise a default config object is created.
	 * @param   JApplicationWebClient  $client  An optional argument to provide dependency injection for the application's
	 *                                          client object.  If the argument is a JApplicationWebClient object that object will become
	 *                                          the application's client object, otherwise a default client object is created.
	 *
	 * @since   3.2
	 */
	public function __construct(JInput $input = null, Registry $config = null, JApplicationWebClient $client = null)
	{
		// Register the application name
		$this->_name = 'site';

		// Register the client ID
		$this->_clientId = 0;

		// Execute the parent constructor
		parent::__construct($input, $config, $client);
	}

	/**
	 * Check if the user can access the application
	 *
	 * @param   integer  $itemid  The item ID to check authorisation for
	 *
	 * @return  void
	 *
	 * @since   3.2
	 *
	 * @throws  Exception When you are not authorised to view the home page menu item
	 */
	protected function authorise($itemid)
	{
		$menus = $this->getMenu();
		$user = JFactory::getUser();

		if (!$menus->authorise($itemid))
		{
			if ($user->get('id') == 0)
			{
				// Set the data
				$this->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));

				$url = JRoute::_('index.php?option=com_users&view=login', false);

				$this->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'));
				$this->redirect($url);
			}
			else
			{
				// Get the home page menu item
				$home_item = $menus->getDefault($this->getLanguage()->getTag());

				// If we are already in the homepage raise an exception
				if ($menus->getActive()->id == $home_item->id)
				{
					throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				}

				// Otherwise redirect to the homepage and show an error
				$this->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
				$this->redirect(JRoute::_('index.php?Itemid=' . $home_item->id, false));
			}
		}
	}

	/**
	 * Dispatch the application
	 *
	 * @param   string  $component  The component which is being rendered.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function dispatch($component = null)
	{
		// Get the component if not set.
		if (!$component)
		{
			$component = $this->input->getCmd('option', null);
		}

		// Load the document to the API
		$this->loadDocument();

		// Set up the params
		$document = $this->getDocument();
		$router   = static::getRouter();
		$params   = $this->getParams();

		// Register the document object with JFactory
		JFactory::$document = $document;

		switch ($document->getType())
		{
			case 'html':
				// Get language
				$lang_code = $this->getLanguage()->getTag();
				$languages = JLanguageHelper::getLanguages('lang_code');

				// Set metadata
				if (isset($languages[$lang_code]) && $languages[$lang_code]->metakey)
				{
					$document->setMetaData('keywords', $languages[$lang_code]->metakey);
				}
				else
				{
					$document->setMetaData('keywords', $this->get('MetaKeys'));
				}

				$document->setMetaData('rights', $this->get('MetaRights'));

				if ($router->getMode() == JROUTER_MODE_SEF)
				{
					$document->setBase(htmlspecialchars(JUri::current()));
				}

				// Get the template
				$template = $this->getTemplate(true);

				// Store the template and its params to the config
				$this->set('theme', $template->template);
				$this->set('themeParams', $template->params);

				break;

			case 'feed':
				$document->setBase(htmlspecialchars(JUri::current()));
				break;
		}

		$document->setTitle($params->get('page_title'));
		$document->setDescription($params->get('page_description'));

		// Add version number or not based on global configuration
		if ($this->get('MetaVersion', 0))
		{
			$document->setGenerator('Joomla! - Open Source Content Management - Version ' . JVERSION);
		}
		else
		{
			$document->setGenerator('Joomla! - Open Source Content Management');
		}

		$contents = JComponentHelper::renderComponent($component);
		$document->setBuffer($contents, 'component');

		// Trigger the onAfterDispatch event.
		JPluginHelper::importPlugin('system');
		$this->triggerEvent('onAfterDispatch');
	}

	/**
	 * Method to run the Web application routines.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function doExecute()
	{
		// Initialise the application
		$this->initialiseApp();

		// Mark afterInitialise in the profiler.
		JDEBUG ? $this->profiler->mark('afterInitialise') : null;

		// Route the application
		$this->route();

		// Mark afterRoute in the profiler.
		JDEBUG ? $this->profiler->mark('afterRoute') : null;

		/*
		 * Check if the user is required to reset their password
		 *
		 * Before $this->route(); "option" and "view" can't be safely read using:
		 * $this->input->getCmd('option'); or $this->input->getCmd('view');
		 * ex: due of the sef urls
		 */
		$this->checkUserRequireReset('com_users', 'profile', 'edit', 'com_users/profile.save,com_users/profile.apply,com_users/user.logout');

		// Dispatch the application
		$this->dispatch();

		// Mark afterDispatch in the profiler.
		JDEBUG ? $this->profiler->mark('afterDispatch') : null;
	}

	/**
	 * Return the current state of the detect browser option.
	 *
	 * @return	boolean
	 *
	 * @since	3.2
	 */
	public function getDetectBrowser()
	{
		return $this->_detect_browser;
	}

	/**
	 * Return the current state of the language filter.
	 *
	 * @return	boolean
	 *
	 * @since	3.2
	 */
	public function getLanguageFilter()
	{
		return $this->_language_filter;
	}

	/**
	 * Return a reference to the JMenu object.
	 *
	 * @param   string  $name     The name of the application/client.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  JMenu  JMenu object.
	 *
	 * @since   3.2
	 */
	public function getMenu($name = 'site', $options = array())
	{
		return parent::getMenu($name, $options);
	}

	/**
	 * Get the application parameters
	 *
	 * @param   string  $option  The component option
	 *
	 * @return  Registry  The parameters object
	 *
	 * @since   3.2
	 * @deprecated  4.0  Use getParams() instead
	 */
	public function getPageParameters($option = null)
	{
		return $this->getParams($option);
	}

	/**
	 * Get the application parameters
	 *
	 * @param   string  $option  The component option
	 *
	 * @return  Registry  The parameters object
	 *
	 * @since   3.2
	 */
	public function getParams($option = null)
	{
		static $params = array();

		$hash = '__default';

		if (!empty($option))
		{
			$hash = $option;
		}

		if (!isset($params[$hash]))
		{
			// Get component parameters
			if (!$option)
			{
				$option = $this->input->getCmd('option', null);
			}

			// Get new instance of component global parameters
			$params[$hash] = clone JComponentHelper::getParams($option);

			// Get menu parameters
			$menus = $this->getMenu();
			$menu  = $menus->getActive();

			// Get language
			$lang_code = $this->getLanguage()->getTag();
			$languages = JLanguageHelper::getLanguages('lang_code');

			$title = $this->get('sitename');

			if (isset($languages[$lang_code]) && $languages[$lang_code]->metadesc)
			{
				$description = $languages[$lang_code]->metadesc;
			}
			else
			{
				$description = $this->get('MetaDesc');
			}

			$rights = $this->get('MetaRights');
			$robots = $this->get('robots');

			// Retrieve com_menu global settings
			$temp = clone JComponentHelper::getParams('com_menus');

			// Lets cascade the parameters if we have menu item parameters
			if (is_object($menu))
			{
				// Get show_page_heading from com_menu global settings
				$params[$hash]->def('show_page_heading', $temp->get('show_page_heading'));

				$params[$hash]->merge($menu->params);
				$title = $menu->title;
			}
			else
			{
				// Merge com_menu global settings
				$params[$hash]->merge($temp);

				// If supplied, use page title
				$title = $temp->get('page_title', $title);
			}

			$params[$hash]->def('page_title', $title);
			$params[$hash]->def('page_description', $description);
			$params[$hash]->def('page_rights', $rights);
			$params[$hash]->def('robots', $robots);
		}

		return $params[$hash];
	}

	/**
	 * Return a reference to the JPathway object.
	 *
	 * @param   string  $name     The name of the application.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  JPathway  A JPathway object
	 *
	 * @since   3.2
	 */
	public function getPathway($name = 'site', $options = array())
	{
		return parent::getPathway($name, $options);
	}

	/**
	 * Return a reference to the JRouter object.
	 *
	 * @param   string  $name     The name of the application.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return	JRouter
	 *
	 * @since	3.2
	 */
	public static function getRouter($name = 'site', array $options = array())
	{
		$options['mode'] = JFactory::getConfig()->get('sef');

		return parent::getRouter($name, $options);
	}

	/**
	 * Gets the name of the current template.
	 *
	 * @param   boolean  $params  True to return the template parameters
	 *
	 * @return  string  The name of the template.
	 *
	 * @since   3.2
	 * @throws  InvalidArgumentException
	 */
	public function getTemplate($params = false)
	{
		if (is_object($this->template))
		{
			if (!file_exists(JPATH_THEMES . '/' . $this->template->template . '/index.php'))
			{
				throw new InvalidArgumentException(JText::sprintf('JERROR_COULD_NOT_FIND_TEMPLATE', $this->template->template));
			}

			if ($params)
			{
				return $this->template;
			}

			return $this->template->template;
		}

		// Get the id of the active menu item
		$menu = $this->getMenu();
		$item = $menu->getActive();

		if (!$item)
		{
			$item = $menu->getItem($this->input->getInt('Itemid', null));
		}

		$id = 0;

		if (is_object($item))
		{
			// Valid item retrieved
			$id = $item->template_style_id;
		}

		$tid = $this->input->getUint('templateStyle', 0);

		if (is_numeric($tid) && (int) $tid > 0)
		{
			$id = (int) $tid;
		}

		$cache = JFactory::getCache('com_templates', '');

		if ($this->_language_filter)
		{
			$tag = $this->getLanguage()->getTag();
		}
		else
		{
			$tag = '';
		}

		$cacheId = 'templates0' . $tag;

		if ($cache->contains($cacheId))
		{
			$templates = $cache->get($cacheId);
		}
		else
		{
			// Load styles
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('id, home, template, s.params')
				->from('#__template_styles as s')
				->where('s.client_id = 0')
				->where('e.enabled = 1')
				->join('LEFT', '#__extensions as e ON e.element=s.template AND e.type=' . $db->quote('template') . ' AND e.client_id=s.client_id');

			$db->setQuery($query);
			$templates = $db->loadObjectList('id');

			foreach ($templates as &$template)
			{
				// Create home element
				if ($template->home == 1 && !isset($template_home) || $this->_language_filter && $template->home == $tag)
				{
					$template_home = clone $template;
				}

				$template->params = new Registry($template->params);
			}

			// Unset the $template reference to the last $templates[n] item cycled in the foreach above to avoid editing it later
			unset($template);

			// Add home element, after loop to avoid double execution
			if (isset($template_home))
			{
				$template_home->params = new Registry($template_home->params);
				$templates[0] = $template_home;
			}

			$cache->store($templates, $cacheId);
		}

		if (isset($templates[$id]))
		{
			$template = $templates[$id];
		}
		else
		{
			$template = $templates[0];
		}

		// Allows for overriding the active template from the request
		$template_override = $this->input->getCmd('template', '');

		// Only set template override if it is a valid template (= it exists and is enabled)
		if (!empty($template_override))
		{
			if (file_exists(JPATH_THEMES . '/' . $template_override . '/index.php'))
			{
				foreach ($templates as $tmpl)
				{
					if ($tmpl->template === $template_override)
					{
						$template = $tmpl;
						break;
					}
				}
			}
		}

		// Need to filter the default value as well
		$template->template = JFilterInput::getInstance()->clean($template->template, 'cmd');

		// Fallback template
		if (!file_exists(JPATH_THEMES . '/' . $template->template . '/index.php'))
		{
			$this->enqueueMessage(JText::_('JERROR_ALERTNOTEMPLATE'), 'error');

			// Try to find data for 'beez3' template
			$original_tmpl = $template->template;

			foreach ($templates as $tmpl)
			{
				if ($tmpl->template === 'beez3')
				{
					$template = $tmpl;
					break;
				}
			}

			// Check, the data were found and if template really exists
			if (!file_exists(JPATH_THEMES . '/' . $template->template . '/index.php'))
			{
				throw new InvalidArgumentException(JText::sprintf('JERROR_COULD_NOT_FIND_TEMPLATE', $original_tmpl));
			}
		}

		// Cache the result
		$this->template = $template;

		if ($params)
		{
			return $template;
		}

		return $template->template;
	}

	/**
	 * Initialise the application.
	 *
	 * @param   array  $options  An optional associative array of configuration settings.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function initialiseApp($options = array())
	{
		$user = JFactory::getUser();

		// If the user is a guest we populate it with the guest user group.
		if ($user->guest)
		{
			$guestUsergroup = JComponentHelper::getParams('com_users')->get('guest_usergroup', 1);
			$user->groups = array($guestUsergroup);
		}

		/*
		 * If a language was specified it has priority, otherwise use user or default language settings
		 * Check this only if the languagefilter plugin is enabled
		 *
		 * @TODO - Remove the hardcoded dependency to the languagefilter plugin
		 */
		if (JPluginHelper::isEnabled('system', 'languagefilter'))
		{
			$plugin = JPluginHelper::getPlugin('system', 'languagefilter');

			$pluginParams = new Registry($plugin->params);

			$this->setLanguageFilter(true);
			$this->setDetectBrowser($pluginParams->get('detect_browser', '1') == '1');
		}

		if (empty($options['language']))
		{
			// Detect the specified language
			$lang = $this->input->getString('language', null);

			// Make sure that the user's language exists
			if ($lang && JLanguageHelper::exists($lang))
			{
				$options['language'] = $lang;
			}
		}

		if (empty($options['language']) && $this->getLanguageFilter())
		{
			// Detect cookie language
			$lang = $this->input->cookie->get(md5($this->get('secret') . 'language'), null, 'string');

			// Make sure that the user's language exists
			if ($lang && JLanguageHelper::exists($lang))
			{
				$options['language'] = $lang;
			}
		}

		if (empty($options['language']))
		{
			// Detect user language
			$lang = $user->getParam('language');

			// Make sure that the user's language exists
			if ($lang && JLanguageHelper::exists($lang))
			{
				$options['language'] = $lang;
			}
		}

		if (empty($options['language']) && $this->getDetectBrowser())
		{
			// Detect browser language
			$lang = JLanguageHelper::detectLanguage();

			// Make sure that the user's language exists
			if ($lang && JLanguageHelper::exists($lang))
			{
				$options['language'] = $lang;
			}
		}

		if (empty($options['language']))
		{
			// Detect default language
			$params = JComponentHelper::getParams('com_languages');
			$options['language'] = $params->get('site', $this->get('language', 'en-GB'));
		}

		// One last check to make sure we have something
		if (!JLanguageHelper::exists($options['language']))
		{
			$lang = $this->config->get('language', 'en-GB');

			if (JLanguageHelper::exists($lang))
			{
				$options['language'] = $lang;
			}
			else
			{
				// As a last ditch fail to english
				$options['language'] = 'en-GB';
			}
		}

		// Finish initialisation
		parent::initialiseApp($options);
	}

	/**
	 * Load the library language files for the application
	 *
	 * @return  void
	 *
	 * @since   3.6.3
	 */
	protected function loadLibraryLanguage()
	{
		/*
		 * Try the lib_joomla file in the current language (without allowing the loading of the file in the default language)
		 * Fallback to the default language if necessary
		 */
		$this->getLanguage()->load('lib_joomla', JPATH_SITE, null, false, true)
			|| $this->getLanguage()->load('lib_joomla', JPATH_ADMINISTRATOR, null, false, true);
	}

	/**
	 * Login authentication function
	 *
	 * @param   array  $credentials  Array('username' => string, 'password' => string)
	 * @param   array  $options      Array('remember' => boolean)
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.2
	 */
	public function login($credentials, $options = array())
	{
		// Set the application login entry point
		if (!array_key_exists('entry_url', $options))
		{
			$options['entry_url'] = JUri::base() . 'index.php?option=com_users&task=user.login';
		}

		// Set the access control action to check.
		$options['action'] = 'core.login.site';

		return parent::login($credentials, $options);
	}

	/**
	 * Rendering is the process of pushing the document buffers into the template
	 * placeholders, retrieving data from the document and pushing it into
	 * the application response buffer.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function render()
	{
		switch ($this->document->getType())
		{
			case 'feed':
				// No special processing for feeds
				break;

			case 'html':
			default:
				$template = $this->getTemplate(true);
				$file     = $this->input->get('tmpl', 'index');

				if ($file === 'offline' && !$this->get('offline'))
				{
					$this->set('themeFile', 'index.php');
				}

				if ($this->get('offline') && !JFactory::getUser()->authorise('core.login.offline'))
				{
					$this->setUserState('users.login.form.data', array('return' => JUri::getInstance()->toString()));
					$this->set('themeFile', 'offline.php');
					$this->setHeader('Status', '503 Service Temporarily Unavailable', 'true');
				}

				if (!is_dir(JPATH_THEMES . '/' . $template->template) && !$this->get('offline'))
				{
					$this->set('themeFile', 'component.php');
				}

				// Ensure themeFile is set by now
				if ($this->get('themeFile') == '')
				{
					$this->set('themeFile', $file . '.php');
				}

				break;
		}

		parent::render();
	}

	/**
	 * Route the application.
	 *
	 * Routing is the process of examining the request environment to determine which
	 * component should receive the request. The component optional parameters
	 * are then set in the request object to be processed when the application is being
	 * dispatched.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function route()
	{
		// Execute the parent method
		parent::route();

		$Itemid = $this->input->getInt('Itemid', null);
		$this->authorise($Itemid);
	}

	/**
	 * Set the current state of the detect browser option.
	 *
	 * @param   boolean  $state  The new state of the detect browser option
	 *
	 * @return	boolean	 The previous state
	 *
	 * @since	3.2
	 */
	public function setDetectBrowser($state = false)
	{
		$old = $this->_detect_browser;
		$this->_detect_browser = $state;

		return $old;
	}

	/**
	 * Set the current state of the language filter.
	 *
	 * @param   boolean  $state  The new state of the language filter
	 *
	 * @return	boolean	 The previous state
	 *
	 * @since	3.2
	 */
	public function setLanguageFilter($state = false)
	{
		$old = $this->_language_filter;
		$this->_language_filter = $state;

		return $old;
	}

	/**
	 * Overrides the default template that would be used
	 *
	 * @param   string  $template     The template name
	 * @param   mixed   $styleParams  The template style parameters
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function setTemplate($template, $styleParams = null)
	{
		if (is_dir(JPATH_THEMES . '/' . $template))
		{
			$this->template = new stdClass;
			$this->template->template = $template;

			if ($styleParams instanceof Registry)
			{
				$this->template->params = $styleParams;
			}
			else
			{
				$this->template->params = new Registry($styleParams);
			}

			// Store the template and its params to the config
			$this->set('theme', $this->template->template);
			$this->set('themeParams', $this->template->params);
		}
	}
}
