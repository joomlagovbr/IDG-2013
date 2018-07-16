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

use Exception;
use JHtml;
use JText;
use ReflectionClass;

/**
 * Class EditorButtonPopup
 * @package RegularLabs\Library
 */
class EditorButtonPopup
{
	var $extension         = '';
	var $params            = null;
	var $require_core_auth = true;

	public function __construct($extension)
	{
		$this->extension = $extension;
		$this->params    = Parameters::getInstance()->getPluginParams($extension);
	}

	public function render()
	{
		if ( ! Extension::isAuthorised($this->require_core_auth))
		{
			throw new Exception(JText::_("ALERTNOTAUTH"));
		}

		if ( ! Extension::isEnabledInArea($this->params))
		{
			throw new Exception(JText::_("ALERTNOTAUTH"));
		}

		$this->loadLibraryLanguages();
		$this->loadLibraryScriptsStyles();

		$this->loadLanguages();
		$this->loadScripts();
		$this->loadStyles();

		echo $this->renderTemplate();
	}

	public function loadLanguages()
	{
		Language::load('plg_editors-xtd_' . $this->extension);
		Language::load('plg_system_' . $this->extension);
	}

	public function loadScripts()
	{
	}

	public function loadStyles()
	{
	}

	private function loadLibraryLanguages()
	{
		Language::load('plg_system_regularlabs');
	}

	private function loadLibraryScriptsStyles()
	{
		JHtml::_('jquery.framework');

		Document::script('regularlabs/script.min.js');
		Document::style('regularlabs/popup.min.css');
		Document::style('regularlabs/style.min.css');
	}

	private function renderTemplate()
	{
		ob_start();
		include $this->getDir() . '/popup.tmpl.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	private function getDir()
	{
		// use static::class instead of get_class($this) after php 5.4 support is dropped
		$rc = new ReflectionClass(get_class($this));

		return dirname($rc->getFileName());
	}
}
