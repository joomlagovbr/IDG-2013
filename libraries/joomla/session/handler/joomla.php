<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Session
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Interface for managing HTTP sessions
 *
 * @since  3.5
 */
class JSessionHandlerJoomla extends JSessionHandlerNative
{
	/**
	 * The input object
	 *
	 * @var    JInput
	 * @since  3.5
	 */
	public $input = null;

	/**
	 * Force cookies to be SSL only
	 *
	 * @var    boolean
	 * @since  3.5
	 */
	protected $force_ssl = false;

	/**
	 * Public constructor
	 *
	 * @param   array  $options  An array of configuration options
	 *
	 * @since   3.5
	 */
	public function __construct($options = array())
	{
		// Disable transparent sid support
		ini_set('session.use_trans_sid', '0');

		// Only allow the session ID to come from cookies and nothing else.
		ini_set('session.use_only_cookies', '1');

		// Set options
		$this->setOptions($options);
		$this->setCookieParams();
	}

	/**
	 * Starts the session
	 *
	 * @return  boolean  True if started
	 *
	 * @since   3.5
	 * @throws  RuntimeException If something goes wrong starting the session.
	 */
	public function start()
	{
		$session_name = $this->getName();

		// Get the JInputCookie object
		$cookie = $this->input->cookie;

		if (is_null($cookie->get($session_name)))
		{
			$session_clean = $this->input->get($session_name, false, 'string');

			if ($session_clean)
			{
				$this->setId($session_clean);
				$cookie->set($session_name, '', time() - 3600);
			}
		}

		return parent::start();
	}

	/**
	 * Clear all session data in memory.
	 *
	 * @return  void
	 *
	 * @since   3.5
	 */
	public function clear()
	{
		$session_name = $this->getName();

		/*
		 * In order to kill the session altogether, such as to log the user out, the session id
		 * must also be unset. If a cookie is used to propagate the session id (default behavior),
		 * then the session cookie must be deleted.
		 */
		if (isset($_COOKIE[$session_name]))
		{
			$config        = JFactory::getConfig();
			$cookie_domain = $config->get('cookie_domain', '');
			$cookie_path   = $config->get('cookie_path', '/');
			setcookie($session_name, '', time() - 42000, $cookie_path, $cookie_domain);
		}

		parent::clear();
	}

	/**
	 * Set session cookie parameters
	 *
	 * @return  void
	 *
	 * @since   3.5
	 */
	protected function setCookieParams()
	{
		$cookie = session_get_cookie_params();

		if ($this->force_ssl)
		{
			$cookie['secure'] = true;
		}

		$config = JFactory::getConfig();

		if ($config->get('cookie_domain', '') != '')
		{
			$cookie['domain'] = $config->get('cookie_domain');
		}

		if ($config->get('cookie_path', '') != '')
		{
			$cookie['path'] = $config->get('cookie_path');
		}

		session_set_cookie_params($cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], true);
	}

	/**
	 * Set additional session options
	 *
	 * @param   array  $options  List of parameter
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.5
	 */
	protected function setOptions(array $options)
	{
		if (isset($options['force_ssl']))
		{
			$this->force_ssl = (bool) $options['force_ssl'];
		}

		return true;
	}
}
