<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Application;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Input\Input;
use Joomla\CMS\Session\MetadataManager;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

/**
 * Joomla! CMS Application class
 *
 * @since  3.2
 */
class CMSApplication extends WebApplication
{
	/**
	 * Array of options for the \JDocument object
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $docOptions = array();

	/**
	 * Application instances container.
	 *
	 * @var    CMSApplication[]
	 * @since  3.2
	 */
	protected static $instances = array();

	/**
	 * The scope of the application.
	 *
	 * @var    string
	 * @since  3.2
	 */
	public $scope = null;

	/**
	 * The client identifier.
	 *
	 * @var    integer
	 * @since  3.2
	 * @deprecated  4.0  Will be renamed $clientId
	 */
	protected $_clientId = null;

	/**
	 * The application message queue.
	 *
	 * @var    array
	 * @since  3.2
	 * @deprecated  4.0  Will be renamed $messageQueue
	 */
	protected $_messageQueue = array();

	/**
	 * The name of the application.
	 *
	 * @var    array
	 * @since  3.2
	 * @deprecated  4.0  Will be renamed $name
	 */
	protected $_name = null;

	/**
	 * The profiler instance
	 *
	 * @var    \JProfiler
	 * @since  3.2
	 */
	protected $profiler = null;

	/**
	 * Currently active template
	 *
	 * @var    object
	 * @since  3.2
	 */
	protected $template = null;

	/**
	 * Class constructor.
	 *
	 * @param   Input                   $input   An optional argument to provide dependency injection for the application's
	 *                                           input object.  If the argument is a \JInput object that object will become
	 *                                           the application's input object, otherwise a default input object is created.
	 * @param   Registry                $config  An optional argument to provide dependency injection for the application's
	 *                                           config object.  If the argument is a Registry object that object will become
	 *                                           the application's config object, otherwise a default config object is created.
	 * @param   \JApplicationWebClient  $client  An optional argument to provide dependency injection for the application's
	 *                                           client object.  If the argument is a \JApplicationWebClient object that object will become
	 *                                           the application's client object, otherwise a default client object is created.
	 *
	 * @since   3.2
	 */
	public function __construct(Input $input = null, Registry $config = null, \JApplicationWebClient $client = null)
	{
		parent::__construct($input, $config, $client);

		// Load and set the dispatcher
		$this->loadDispatcher();

		// If JDEBUG is defined, load the profiler instance
		if (defined('JDEBUG') && JDEBUG)
		{
			$this->profiler = \JProfiler::getInstance('Application');
		}

		// Enable sessions by default.
		if ($this->config->get('session') === null)
		{
			$this->config->set('session', true);
		}

		// Set the session default name.
		if ($this->config->get('session_name') === null)
		{
			$this->config->set('session_name', $this->getName());
		}

		// Create the session if a session name is passed.
		if ($this->config->get('session') !== false)
		{
			$this->loadSession();
		}
	}

	/**
	 * Checks the user session.
	 *
	 * If the session record doesn't exist, initialise it.
	 * If session is new, create session variables
	 *
	 * @return  void
	 *
	 * @since   3.2
	 * @throws  \RuntimeException
	 */
	public function checkSession()
	{
		$metadataManager = new MetadataManager($this, \JFactory::getDbo());
		$metadataManager->createRecordIfNonExisting(\JFactory::getSession(), \JFactory::getUser());
	}

	/**
	 * Enqueue a system message.
	 *
	 * @param   string  $msg   The message to enqueue.
	 * @param   string  $type  The message type. Default is message.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function enqueueMessage($msg, $type = 'message')
	{
		// Don't add empty messages.
		if (trim($msg) === '')
		{
			return;
		}

		// For empty queue, if messages exists in the session, enqueue them first.
		$messages = $this->getMessageQueue();

		$message = array('message' => $msg, 'type' => strtolower($type));

		if (!in_array($message, $this->_messageQueue))
		{
			// Enqueue the message.
			$this->_messageQueue[] = $message;
		}
	}

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function execute()
	{
		// Perform application routines.
		$this->doExecute();

		// If we have an application document object, render it.
		if ($this->document instanceof \JDocument)
		{
			// Render the application output.
			$this->render();
		}

		// If gzip compression is enabled in configuration and the server is compliant, compress the output.
		if ($this->get('gzip') && !ini_get('zlib.output_compression') && ini_get('output_handler') !== 'ob_gzhandler')
		{
			$this->compress();

			// Trigger the onAfterCompress event.
			$this->triggerEvent('onAfterCompress');
		}

		// Send the application response.
		$this->respond();

		// Trigger the onAfterRespond event.
		$this->triggerEvent('onAfterRespond');
	}

	/**
	 * Check if the user is required to reset their password.
	 *
	 * If the user is required to reset their password will be redirected to the page that manage the password reset.
	 *
	 * @param   string  $option  The option that manage the password reset
	 * @param   string  $view    The view that manage the password reset
	 * @param   string  $layout  The layout of the view that manage the password reset
	 * @param   string  $tasks   Permitted tasks
	 *
	 * @return  void
	 */
	protected function checkUserRequireReset($option, $view, $layout, $tasks)
	{
		if (\JFactory::getUser()->get('requireReset', 0))
		{
			$redirect = false;

			/*
			 * By default user profile edit page is used.
			 * That page allows you to change more than just the password and might not be the desired behavior.
			 * This allows a developer to override the page that manage the password reset.
			 * (can be configured using the file: configuration.php, or if extended, through the global configuration form)
			 */
			$name = $this->getName();

			if ($this->get($name . '_reset_password_override', 0))
			{
				$option = $this->get($name . '_reset_password_option', '');
				$view = $this->get($name . '_reset_password_view', '');
				$layout = $this->get($name . '_reset_password_layout', '');
				$tasks = $this->get($name . '_reset_password_tasks', '');
			}

			$task = $this->input->getCmd('task', '');

			// Check task or option/view/layout
			if (!empty($task))
			{
				$tasks = explode(',', $tasks);

				// Check full task version "option/task"
				if (array_search($this->input->getCmd('option', '') . '/' . $task, $tasks) === false)
				{
					// Check short task version, must be on the same option of the view
					if ($this->input->getCmd('option', '') !== $option || array_search($task, $tasks) === false)
					{
						// Not permitted task
						$redirect = true;
					}
				}
			}
			else
			{
				if ($this->input->getCmd('option', '') !== $option || $this->input->getCmd('view', '') !== $view
					|| $this->input->getCmd('layout', '') !== $layout)
				{
					// Requested a different option/view/layout
					$redirect = true;
				}
			}

			if ($redirect)
			{
				// Redirect to the profile edit page
				$this->enqueueMessage(\JText::_('JGLOBAL_PASSWORD_RESET_REQUIRED'), 'notice');
				$this->redirect(\JRoute::_('index.php?option=' . $option . '&view=' . $view . '&layout=' . $layout, false));
			}
		}
	}

	/**
	 * Gets a configuration value.
	 *
	 * @param   string  $varname  The name of the value to get.
	 * @param   string  $default  Default value to return
	 *
	 * @return  mixed  The user state.
	 *
	 * @since   3.2
	 * @deprecated  4.0  Use get() instead
	 */
	public function getCfg($varname, $default = null)
	{
		return $this->get($varname, $default);
	}

	/**
	 * Gets the client id of the current running application.
	 *
	 * @return  integer  A client identifier.
	 *
	 * @since   3.2
	 */
	public function getClientId()
	{
		return $this->_clientId;
	}

	/**
	 * Returns a reference to the global CMSApplication object, only creating it if it doesn't already exist.
	 *
	 * This method must be invoked as: $web = CMSApplication::getInstance();
	 *
	 * @param   string  $name  The name (optional) of the CMSApplication class to instantiate.
	 *
	 * @return  CMSApplication
	 *
	 * @since   3.2
	 * @throws  \RuntimeException
	 */
	public static function getInstance($name = null)
	{
		if (empty(static::$instances[$name]))
		{
			// Create a CMSApplication object.
			$classname = '\JApplication' . ucfirst($name);

			if (!class_exists($classname))
			{
				throw new \RuntimeException(\JText::sprintf('JLIB_APPLICATION_ERROR_APPLICATION_LOAD', $name), 500);
			}

			static::$instances[$name] = new $classname;
		}

		return static::$instances[$name];
	}

	/**
	 * Returns the application \JMenu object.
	 *
	 * @param   string  $name     The name of the application/client.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  \JMenu|null
	 *
	 * @since   3.2
	 */
	public function getMenu($name = null, $options = array())
	{
		if (!isset($name))
		{
			$name = $this->getName();
		}

		// Inject this application object into the \JMenu tree if one isn't already specified
		if (!isset($options['app']))
		{
			$options['app'] = $this;
		}

		try
		{
			$menu = \JMenu::getInstance($name, $options);
		}
		catch (\Exception $e)
		{
			return;
		}

		return $menu;
	}

	/**
	 * Get the system message queue.
	 *
	 * @param   boolean  $clear  Clear the messages currently attached to the application object
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   3.2
	 */
	public function getMessageQueue($clear = false)
	{
		// For empty queue, if messages exists in the session, enqueue them.
		if (!$this->_messageQueue)
		{
			$session = \JFactory::getSession();
			$sessionQueue = $session->get('application.queue', array());

			if ($sessionQueue)
			{
				$this->_messageQueue = $sessionQueue;
				$session->set('application.queue', array());
			}
		}

		$messageQueue = $this->_messageQueue;

		if ($clear)
		{
			$this->_messageQueue = array();
		}

		return $messageQueue;
	}

	/**
	 * Gets the name of the current running application.
	 *
	 * @return  string  The name of the application.
	 *
	 * @since   3.2
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Returns the application \JPathway object.
	 *
	 * @param   string  $name     The name of the application.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  \JPathway|null
	 *
	 * @since   3.2
	 */
	public function getPathway($name = null, $options = array())
	{
		if (!isset($name))
		{
			$name = $this->getName();
		}
		else
		{
			// Name should not be used
			$this->getLogger()->warning(
				'Name attribute is deprecated, in the future fetch the pathway '
				. 'through the respective application.',
				array('category' => 'deprecated')
			);
		}

		try
		{
			$pathway = \JPathway::getInstance($name, $options);
		}
		catch (\Exception $e)
		{
			return;
		}

		return $pathway;
	}

	/**
	 * Returns the application \JRouter object.
	 *
	 * @param   string  $name     The name of the application.
	 * @param   array   $options  An optional associative array of configuration settings.
	 *
	 * @return  \JRouter|null
	 *
	 * @since   3.2
	 */
	public static function getRouter($name = null, array $options = array())
	{
		if (!isset($name))
		{
			$app = \JFactory::getApplication();
			$name = $app->getName();
		}

		$options['mode'] = \JFactory::getConfig()->get('sef');

		try
		{
			$router = \JRouter::getInstance($name, $options);
		}
		catch (\Exception $e)
		{
			return;
		}

		return $router;
	}

	/**
	 * Gets the name of the current template.
	 *
	 * @param   boolean  $params  An optional associative array of configuration settings
	 *
	 * @return  mixed  System is the fallback.
	 *
	 * @since   3.2
	 */
	public function getTemplate($params = false)
	{
		$template = new \stdClass;

		$template->template = 'system';
		$template->params   = new Registry;

		if ($params)
		{
			return $template;
		}

		return $template->template;
	}

	/**
	 * Gets a user state.
	 *
	 * @param   string  $key      The path of the state.
	 * @param   mixed   $default  Optional default value, returned if the internal value is null.
	 *
	 * @return  mixed  The user state or null.
	 *
	 * @since   3.2
	 */
	public function getUserState($key, $default = null)
	{
		$session = \JFactory::getSession();
		$registry = $session->get('registry');

		if ($registry !== null)
		{
			return $registry->get($key, $default);
		}

		return $default;
	}

	/**
	 * Gets the value of a user state variable.
	 *
	 * @param   string  $key      The key of the user state variable.
	 * @param   string  $request  The name of the variable passed in a request.
	 * @param   string  $default  The default value for the variable if not found. Optional.
	 * @param   string  $type     Filter for the variable, for valid values see {@link \JFilterInput::clean()}. Optional.
	 *
	 * @return  mixed  The request user state.
	 *
	 * @since   3.2
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
	{
		$cur_state = $this->getUserState($key, $default);
		$new_state = $this->input->get($request, null, $type);

		if ($new_state === null)
		{
			return $cur_state;
		}

		// Save the new value only if it was set in this request.
		$this->setUserState($key, $new_state);

		return $new_state;
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
		// Set the configuration in the API.
		$this->config = \JFactory::getConfig();

		// Check that we were given a language in the array (since by default may be blank).
		if (isset($options['language']))
		{
			$this->set('language', $options['language']);
		}

		// Build our language object
		$lang = \JLanguage::getInstance($this->get('language'), $this->get('debug_lang'));

		// Load the language to the API
		$this->loadLanguage($lang);

		// Register the language object with \JFactory
		\JFactory::$language = $this->getLanguage();

		// Load the library language files
		$this->loadLibraryLanguage();

		// Set user specific editor.
		$user = \JFactory::getUser();
		$editor = $user->getParam('editor', $this->get('editor'));

		if (!\JPluginHelper::isEnabled('editors', $editor))
		{
			$editor = $this->get('editor');

			if (!\JPluginHelper::isEnabled('editors', $editor))
			{
				$editor = 'none';
			}
		}

		$this->set('editor', $editor);

		// Trigger the onAfterInitialise event.
		\JPluginHelper::importPlugin('system');
		$this->triggerEvent('onAfterInitialise');
	}

	/**
	 * Is admin interface?
	 *
	 * @return  boolean  True if this application is administrator.
	 *
	 * @since       3.2
	 * @deprecated  4.0 Use isClient('administrator') instead.
	 */
	public function isAdmin()
	{
		return $this->isClient('administrator');
	}

	/**
	 * Is site interface?
	 *
	 * @return  boolean  True if this application is site.
	 *
	 * @since       3.2
	 * @deprecated  4.0 Use isClient('site') instead.
	 */
	public function isSite()
	{
		return $this->isClient('site');
	}

	/**
	 * Checks if HTTPS is forced in the client configuration.
	 *
	 * @param   integer  $clientId  An optional client id (defaults to current application client).
	 *
	 * @return  boolean  True if is forced for the client, false otherwise.
	 *
	 * @since   3.7.3
	 */
	public function isHttpsForced($clientId = null)
	{
		$clientId = (int) ($clientId !== null ? $clientId : $this->getClientId());
		$forceSsl = (int) $this->get('force_ssl');

		if ($clientId === 0 && $forceSsl === 2)
		{
			return true;
		}

		if ($clientId === 1 && $forceSsl >= 1)
		{
			return true;
		}

		return false;
	}

	/**
	 * Check the client interface by name.
	 *
	 * @param   string  $identifier  String identifier for the application interface
	 *
	 * @return  boolean  True if this application is of the given type client interface.
	 *
	 * @since   3.7.0
	 */
	public function isClient($identifier)
	{
		return $this->getName() === $identifier;
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
		$this->getLanguage()->load('lib_joomla', JPATH_ADMINISTRATOR);
	}

	/**
	 * Allows the application to load a custom or default session.
	 *
	 * The logic and options for creating this object are adequately generic for default cases
	 * but for many applications it will make sense to override this method and create a session,
	 * if required, based on more specific needs.
	 *
	 * @param   \JSession  $session  An optional session object. If omitted, the session is created.
	 *
	 * @return  CMSApplication  This method is chainable.
	 *
	 * @since   3.2
	 */
	public function loadSession(\JSession $session = null)
	{
		if ($session !== null)
		{
			$this->session = $session;

			return $this;
		}

		$this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart'));

		/*
		 * Note: The below code CANNOT change from instantiating a session via \JFactory until there is a proper dependency injection container supported
		 * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
		 * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
		 * without a proper dependency injection container.
		 */

		$session = \JFactory::getSession(
			array(
				'name'      => \JApplicationHelper::getHash($this->get('session_name', get_class($this))),
				'expire'    => $this->get('lifetime') ? $this->get('lifetime') * 60 : 900,
				'force_ssl' => $this->isHttpsForced(),
			)
		);

		$session->initialise($this->input, $this->dispatcher);

		// Get the session handler from the configuration.
		$handler = $this->get('session_handler', 'none');

		/*
		 * Check for extra session metadata when:
		 *
		 * 1) The database handler is in use and the session is new
		 * 2) The database handler is not in use and the time is an even numbered second or the session is new
		 */
		if (($handler !== 'database' && (time() % 2 || $session->isNew())) || ($handler === 'database' && $session->isNew()))
		{
			$this->checkSession();
		}

		// Set the session object.
		$this->session = $session;

		return $this;
	}

	/**
	 * Login authentication function.
	 *
	 * Username and encoded password are passed the onUserLogin event which
	 * is responsible for the user validation. A successful validation updates
	 * the current session record with the user's details.
	 *
	 * Username and encoded password are sent as credentials (along with other
	 * possibilities) to each observer (authentication plugin) for user
	 * validation.  Successful validation will update the current session with
	 * the user details.
	 *
	 * @param   array  $credentials  Array('username' => string, 'password' => string)
	 * @param   array  $options      Array('remember' => boolean)
	 *
	 * @return  boolean|\JException  True on success, false if failed or silent handling is configured, or a \JException object on authentication error.
	 *
	 * @since   3.2
	 */
	public function login($credentials, $options = array())
	{
		// Get the global \JAuthentication object.
		$authenticate = \JAuthentication::getInstance();
		$response = $authenticate->authenticate($credentials, $options);

		// Import the user plugin group.
		\JPluginHelper::importPlugin('user');

		if ($response->status === \JAuthentication::STATUS_SUCCESS)
		{
			/*
			 * Validate that the user should be able to login (different to being authenticated).
			 * This permits authentication plugins blocking the user.
			 */
			$authorisations = $authenticate->authorise($response, $options);
			$denied_states = \JAuthentication::STATUS_EXPIRED | \JAuthentication::STATUS_DENIED;

			foreach ($authorisations as $authorisation)
			{
				if ((int) $authorisation->status & $denied_states)
				{
					// Trigger onUserAuthorisationFailure Event.
					$this->triggerEvent('onUserAuthorisationFailure', array((array) $authorisation));

					// If silent is set, just return false.
					if (isset($options['silent']) && $options['silent'])
					{
						return false;
					}

					// Return the error.
					switch ($authorisation->status)
					{
						case \JAuthentication::STATUS_EXPIRED:
							return \JError::raiseWarning('102002', \JText::_('JLIB_LOGIN_EXPIRED'));

						case \JAuthentication::STATUS_DENIED:
							return \JError::raiseWarning('102003', \JText::_('JLIB_LOGIN_DENIED'));

						default:
							return \JError::raiseWarning('102004', \JText::_('JLIB_LOGIN_AUTHORISATION'));
					}
				}
			}

			// OK, the credentials are authenticated and user is authorised.  Let's fire the onLogin event.
			$results = $this->triggerEvent('onUserLogin', array((array) $response, $options));

			/*
			 * If any of the user plugins did not successfully complete the login routine
			 * then the whole method fails.
			 *
			 * Any errors raised should be done in the plugin as this provides the ability
			 * to provide much more information about why the routine may have failed.
			 */
			$user = \JFactory::getUser();

			if ($response->type === 'Cookie')
			{
				$user->set('cookieLogin', true);
			}

			if (in_array(false, $results, true) == false)
			{
				$options['user'] = $user;
				$options['responseType'] = $response->type;

				// The user is successfully logged in. Run the after login events
				$this->triggerEvent('onUserAfterLogin', array($options));

				return true;
			}
		}

		// Trigger onUserLoginFailure Event.
		$this->triggerEvent('onUserLoginFailure', array((array) $response));

		// If silent is set, just return false.
		if (isset($options['silent']) && $options['silent'])
		{
			return false;
		}

		// If status is success, any error will have been raised by the user plugin
		if ($response->status !== \JAuthentication::STATUS_SUCCESS)
		{
			$this->getLogger()->warning($response->error_message, array('category' => 'jerror'));
		}

		return false;
	}

	/**
	 * Logout authentication function.
	 *
	 * Passed the current user information to the onUserLogout event and reverts the current
	 * session record back to 'anonymous' parameters.
	 * If any of the authentication plugins did not successfully complete
	 * the logout routine then the whole method fails. Any errors raised
	 * should be done in the plugin as this provides the ability to give
	 * much more information about why the routine may have failed.
	 *
	 * @param   integer  $userid   The user to load - Can be an integer or string - If string, it is converted to ID automatically
	 * @param   array    $options  Array('clientid' => array of client id's)
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.2
	 */
	public function logout($userid = null, $options = array())
	{
		// Get a user object from the \JApplication.
		$user = \JFactory::getUser($userid);

		// Build the credentials array.
		$parameters['username'] = $user->get('username');
		$parameters['id'] = $user->get('id');

		// Set clientid in the options array if it hasn't been set already and shared sessions are not enabled.
		if (!$this->get('shared_session', '0') && !isset($options['clientid']))
		{
			$options['clientid'] = $this->getClientId();
		}

		// Import the user plugin group.
		\JPluginHelper::importPlugin('user');

		// OK, the credentials are built. Lets fire the onLogout event.
		$results = $this->triggerEvent('onUserLogout', array($parameters, $options));

		// Check if any of the plugins failed. If none did, success.
		if (!in_array(false, $results, true))
		{
			$options['username'] = $user->get('username');
			$this->triggerEvent('onUserAfterLogout', array($options));

			return true;
		}

		// Trigger onUserLoginFailure Event.
		$this->triggerEvent('onUserLogoutFailure', array($parameters));

		return false;
	}

	/**
	 * Redirect to another URL.
	 *
	 * If the headers have not been sent the redirect will be accomplished using a "301 Moved Permanently"
	 * or "303 See Other" code in the header pointing to the new location. If the headers have already been
	 * sent this will be accomplished using a JavaScript statement.
	 *
	 * @param   string   $url     The URL to redirect to. Can only be http/https URL
	 * @param   integer  $status  The HTTP 1.1 status code to be provided. 303 is assumed by default.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function redirect($url, $status = 303)
	{
		// Handle B/C by checking if a message was passed to the method, will be removed at 4.0
		if (func_num_args() > 1)
		{
			$args = func_get_args();

			/*
			 * Do some checks on the $args array, values below correspond to legacy redirect() method
			 *
			 * $args[0] = $url
			 * $args[1] = Message to enqueue
			 * $args[2] = Message type
			 * $args[3] = $status (previously moved)
			 */
			if (isset($args[1]) && !empty($args[1]) && (!is_bool($args[1]) && !is_int($args[1])))
			{
				$this->getLogger()->warning(
					'Passing a message and message type to ' . __METHOD__ . '() is deprecated. '
					. 'Please set your message via ' . __CLASS__ . '::enqueueMessage() prior to calling ' . __CLASS__
					. '::redirect().',
					array('category' => 'deprecated')
				);

				$message = $args[1];

				// Set the message type if present
				if (isset($args[2]) && !empty($args[2]))
				{
					$type = $args[2];
				}
				else
				{
					$type = 'message';
				}

				// Enqueue the message
				$this->enqueueMessage($message, $type);

				// Reset the $moved variable
				$status = isset($args[3]) ? (boolean) $args[3] : false;
			}
		}

		// Persist messages if they exist.
		if ($this->_messageQueue)
		{
			$session = \JFactory::getSession();
			$session->set('application.queue', $this->_messageQueue);
		}

		// Hand over processing to the parent now
		parent::redirect($url, $status);
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
		// Setup the document options.
		$this->docOptions['template'] = $this->get('theme');
		$this->docOptions['file']     = $this->get('themeFile', 'index.php');
		$this->docOptions['params']   = $this->get('themeParams');

		if ($this->get('themes.base'))
		{
			$this->docOptions['directory'] = $this->get('themes.base');
		}
		// Fall back to constants.
		else
		{
			$this->docOptions['directory'] = defined('JPATH_THEMES') ? JPATH_THEMES : (defined('JPATH_BASE') ? JPATH_BASE : __DIR__) . '/themes';
		}

		// Parse the document.
		$this->document->parse($this->docOptions);

		// Trigger the onBeforeRender event.
		\JPluginHelper::importPlugin('system');
		$this->triggerEvent('onBeforeRender');

		$caching = false;

		if ($this->isClient('site') && $this->get('caching') && $this->get('caching', 2) == 2 && !\JFactory::getUser()->get('id'))
		{
			$caching = true;
		}

		// Render the document.
		$data = $this->document->render($caching, $this->docOptions);

		// Set the application output data.
		$this->setBody($data);

		// Trigger the onAfterRender event.
		$this->triggerEvent('onAfterRender');

		// Mark afterRender in the profiler.
		JDEBUG ? $this->profiler->mark('afterRender') : null;
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
		// Get the full request URI.
		$uri = clone \JUri::getInstance();

		$router = static::getRouter();
		$result = $router->parse($uri);

		$active = $this->getMenu()->getActive();

		if ($active !== null
			&& $active->type === 'alias'
			&& $active->params->get('alias_redirect')
			&& in_array($this->input->getMethod(), array('GET', 'HEAD'), true))
		{
			$item = $this->getMenu()->getItem($active->params->get('aliasoptions'));

			if ($item !== null)
			{
				$oldUri = clone \JUri::getInstance();

				if ($oldUri->getVar('Itemid') == $active->id)
				{
					$oldUri->setVar('Itemid', $item->id);
				}

				$base = \JUri::base(true);
				$oldPath = StringHelper::strtolower(substr($oldUri->getPath(), strlen($base) + 1));
				$activePathPrefix = StringHelper::strtolower($active->route);

				$position = strpos($oldPath, $activePathPrefix);

				if ($position !== false)
				{
					$oldUri->setPath($base . '/' . substr_replace($oldPath, $item->route, $position, strlen($activePathPrefix)));

					$this->setHeader('Expires', 'Wed, 17 Aug 2005 00:00:00 GMT', true);
					$this->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT', true);
					$this->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', false);
					$this->setHeader('Pragma', 'no-cache');
					$this->sendHeaders();

					$this->redirect((string) $oldUri, 301);
				}
			}
		}

		foreach ($result as $key => $value)
		{
			$this->input->def($key, $value);
		}

		// Trigger the onAfterRoute event.
		\JPluginHelper::importPlugin('system');
		$this->triggerEvent('onAfterRoute');
	}

	/**
	 * Sets the value of a user state variable.
	 *
	 * @param   string  $key    The path of the state.
	 * @param   mixed   $value  The value of the variable.
	 *
	 * @return  mixed  The previous state, if one existed.
	 *
	 * @since   3.2
	 */
	public function setUserState($key, $value)
	{
		$session = \JFactory::getSession();
		$registry = $session->get('registry');

		if ($registry !== null)
		{
			return $registry->set($key, $value);
		}

		return;
	}

	/**
	 * Sends all headers prior to returning the string
	 *
	 * @param   boolean  $compress  If true, compress the data
	 *
	 * @return  string
	 *
	 * @since   3.2
	 */
	public function toString($compress = false)
	{
		// Don't compress something if the server is going to do it anyway. Waste of time.
		if ($compress && !ini_get('zlib.output_compression') && ini_get('output_handler') !== 'ob_gzhandler')
		{
			$this->compress();
		}

		if ($this->allowCache() === false)
		{
			$this->setHeader('Cache-Control', 'no-cache', false);

			// HTTP 1.0
			$this->setHeader('Pragma', 'no-cache');
		}

		$this->sendHeaders();

		return $this->getBody();
	}
}
