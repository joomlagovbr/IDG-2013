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

use JFactory;
use JPlugin;
use ReflectionClass;

/**
 * Class EditorButton
 * @package RegularLabs\Library
 */
class EditorButton
	extends JPlugin
{
	private $_init   = false;
	private $_helper = null;

	var $main_type            = 'plugin'; // The type of extension that holds the parameters
	var $check_installed      = null; // The types of extensions that need to be checked (will default to main_type)
	var $require_core_auth    = true; // Whether or not the core content create/edit permissions are required
	var $folder               = null; // The path to the original caller file
	var $enable_on_acymailing = false; // Whether or not to enable the editor button on AcyMailing

	/**
	 * Display the button
	 *
	 * @param string $editor_name
	 *
	 * @return JObject|null A button object
	 */
	function onDisplay($editor_name)
	{
		if ( ! $this->getHelper())
		{
			return null;
		}

		return $this->_helper->render($editor_name, $this->_subject);
	}

	/*
	 * Below methods are general functions used in most of the Regular Labs extensions
	 * The reason these are not placed in the Regular Labs Library files is that they also
	 * need to be used when the Regular Labs Library is not installed
	 */

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

		if ( ! Extension::isFrameworkEnabled())
		{
			return null;
		}

		if ( ! Extension::isAuthorised($this->require_core_auth))
		{
			return null;
		}

		if ( ! $this->isInstalled())
		{
			return null;
		}

		if ( ! $this->enable_on_acymailing && JFactory::getApplication()->input->get('option') == 'com_acymailing')
		{
			return null;
		}

		$params = $this->getParams();

		if ( ! Extension::isEnabledInComponent($params))
		{
			return null;
		}

		if ( ! Extension::isEnabledInArea($params))
		{
			return null;
		}

		if ( ! $this->extraChecks($params))
		{
			return null;
		}

		require_once $this->getDir() . '/helper.php';
		$class_name    = 'PlgButton' . ucfirst($this->_name) . 'Helper';
		$this->_helper = new $class_name($this->_name, $params);

		return $this->_helper;
	}

	public function extraChecks($params)
	{
		return true;
	}

	private function getDir()
	{
		// use static::class instead of get_class($this) after php 5.4 support is dropped
		$rc = new ReflectionClass(get_class($this));

		return dirname($rc->getFileName());
	}

	private function getParams()
	{
		switch ($this->main_type)
		{
			case 'component':
				if ( ! Protect::isComponentInstalled($this->_name))
				{
					return null;
				}

				// Load component parameters
				return Parameters::getInstance()->getComponentParams($this->_name);

			case 'plugin':
			default:
				if ( ! Protect::isSystemPluginInstalled($this->_name))
				{
					return null;
				}

				// Load plugin parameters
				return Parameters::getInstance()->getPluginParams($this->_name);
		}
	}

	private function isInstalled()
	{
		$extensions = ! is_null($this->check_installed)
			? $this->check_installed
			: [$this->main_type];

		return Extension::areInstalled($this->_name, $extensions);
	}
}
