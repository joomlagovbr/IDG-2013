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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;

/**
 * Class Document
 * @package RegularLabs\Library
 */
class Document
{
	/**
	 * Check if page is an admin page
	 *
	 * @param bool $exclude_login
	 *
	 * @return bool
	 */
	public static function isAdmin($exclude_login = false)
	{
		$cache_id = __FUNCTION__ . '_' . $exclude_login;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$input = JFactory::getApplication()->input;

		return Cache::set($cache_id,
			(
				self::isClient('administrator')
				&& ( ! $exclude_login || ! JFactory::getUser()->get('guest'))
				&& $input->get('task') != 'preview'
				&& ! (
					$input->get('option') == 'com_finder'
					&& $input->get('format') == 'json'
				)
			)
		);
	}

	/**
	 * Check if page is an edit page
	 *
	 * @return bool
	 */
	public static function isClient($identifier)
	{
		$identifier = $identifier == 'admin' ? 'administrator' : $identifier;

		$cache_id = __FUNCTION__ . '_' . $identifier;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		return Cache::set($cache_id, JFactory::getApplication()->isClient($identifier));
	}

	/**
	 * Check if page is an edit page
	 *
	 * @return bool
	 */
	public static function isEditPage()
	{
		$cache_id = __FUNCTION__;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$input = JFactory::getApplication()->input;

		$option = $input->get('option');

		// always return false for these components
		if (in_array($option, ['com_rsevents', 'com_rseventspro']))
		{
			return Cache::set($cache_id, false);
		}

		$task = $input->get('task');

		if (strpos($task, '.') !== false)
		{
			$task = explode('.', $task);
			$task = array_pop($task);
		}

		$view = $input->get('view');

		if (strpos($view, '.') !== false)
		{
			$view = explode('.', $view);
			$view = array_pop($view);
		}

		return Cache::set($cache_id,
			(
				in_array($option, ['com_contentsubmit', 'com_cckjseblod'])
				|| ($option == 'com_comprofiler' && in_array($task, ['', 'userdetails']))
				|| in_array($task, ['edit', 'form', 'submission'])
				|| in_array($view, ['edit', 'form'])
				|| in_array($input->get('do'), ['edit', 'form'])
				|| in_array($input->get('layout'), ['edit', 'form', 'write'])
				|| self::isAdmin()
			)
		);
	}

	/**
	 * Checks if current page is a html page
	 *
	 * @return bool
	 */
	public static function isHtml()
	{
		$cache_id = __FUNCTION__;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		return Cache::set($cache_id,
			(JFactory::getDocument()->getType() == 'html')
		);
	}

	/**
	 * Checks if current page is a feed
	 *
	 * @return bool
	 */
	public static function isFeed()
	{
		$cache_id = __FUNCTION__;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$input = JFactory::getApplication()->input;

		return Cache::set($cache_id,
			(
				JFactory::getDocument()->getType() == 'feed'
				|| $input->getWord('format') == 'feed'
				|| $input->getWord('format') == 'xml'
				|| $input->getWord('type') == 'rss'
				|| $input->getWord('type') == 'atom'
			)
		);
	}

	/**
	 * Checks if current page is a pdf
	 *
	 * @return bool
	 */
	public static function isPDF()
	{
		$cache_id = __FUNCTION__;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$input = JFactory::getApplication()->input;

		return Cache::set($cache_id,
			(
				JFactory::getDocument()->getType() == 'pdf'
				|| $input->getWord('format') == 'pdf'
				|| $input->getWord('cAction') == 'pdf'
			)
		);
	}

	/**
	 * Checks if current page is a https (ssl) page
	 *
	 * @return bool
	 */
	public static function isHttps()
	{
		$cache_id = __FUNCTION__;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		return Cache::set($cache_id,
			(
				( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off')
				|| (isset($_SERVER['SSL_PROTOCOL']))
				|| (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
				|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')
			)
		);
	}

	/**
	 * Checks if context/page is a category list
	 *
	 * @param string $context
	 *
	 * @return bool
	 */
	public static function isCategoryList($context)
	{
		$cache_id = __FUNCTION__ . '_' . $context;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$app   = JFactory::getApplication();
		$input = $app->input;

		// Return false if it is not a category page
		if ($context != 'com_content.category' || $input->get('view') != 'category')
		{
			return Cache::set($cache_id, false);
		}

		// Return false if layout is set and it is not a list layout
		if ($input->get('layout') && $input->get('layout') != 'list')
		{
			return Cache::set($cache_id, false);
		}

		// Return false if default layout is set to blog
		if ($app->getParams()->get('category_layout') == '_:blog')
		{
			return Cache::set($cache_id, false);
		}

		// Return true if it IS a list layout
		return Cache::set($cache_id, true);
	}

	/**
	 * Adds a script file to the page (with optional versioning)
	 *
	 * @param string $file
	 * @param string $version
	 */
	public static function script($file, $version = '')
	{
		if ( ! $url = File::getMediaFile('js', $file))
		{
			return;
		}

		JHtml::_('jquery.framework');

		if (strpos($file, 'regularlabs/') !== false)
		{
			JHtml::_('behavior.core');
			JHtml::_('script', 'jui/cms.js', ['version' => 'auto', 'relative' => true]);
			$version = '19.5.762';
		}

		if ( ! empty($version))
		{
			$url .= '?v=' . $version;
		}

		JFactory::getDocument()->addScript($url);
	}

	/**
	 * Adds a stylesheet file to the page(with optional versioning)
	 *
	 * @param string $file
	 * @param string $version
	 */
	public static function style($file, $version = '')
	{
		if (strpos($file, 'regularlabs/') === 0)
		{
			$version = '19.5.762';
		}

		if ( ! $file = File::getMediaFile('css', $file))
		{
			return;
		}

		if ( ! empty($version))
		{
			$file .= '?v=' . $version;
		}

		JFactory::getDocument()->addStylesheet($file);
	}

	/**
	 * Alias of \RegularLabs\Library\Document::style()
	 *
	 * @param string $file
	 * @param string $version
	 */
	public static function stylesheet($file, $version = '')
	{
		self::style($file, $version);
	}

	/**
	 * Adds extension options to the page
	 *
	 * @param array  $options
	 * @param string $name
	 */
	public static function scriptOptions($options = [], $name = '')
	{
		$key = 'rl_' . Extension::getAliasByName($name);
		JHtml::_('behavior.core');

		JFactory::getDocument()->addScriptOptions($key, $options);
	}

	/**
	 * Loads the required scripts and styles used in forms
	 */
	public static function loadMainDependencies()
	{
		JHtml::_('jquery.framework');

		self::script('regularlabs/script.min.js');
		self::style('regularlabs/style.min.css');
	}

	/**
	 * Loads the required scripts and styles used in forms
	 */
	public static function loadFormDependencies()
	{
		JHtml::_('jquery.framework');
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidator');
		JHtml::_('behavior.combobox');
		JHtml::_('behavior.keepalive');
		JHtml::_('behavior.tabstate');

		JHtml::_('formbehavior.chosen', '#jform_position', null, ['disable_search_threshold' => 0]);
		JHtml::_('formbehavior.chosen', '.multipleCategories', null, ['placeholder_text_multiple' => JText::_('JOPTION_SELECT_CATEGORY')]);
		JHtml::_('formbehavior.chosen', '.multipleTags', null, ['placeholder_text_multiple' => JText::_('JOPTION_SELECT_TAG')]);
		JHtml::_('formbehavior.chosen', 'select');

		self::script('regularlabs/form.min.js');
		self::style('regularlabs/form.min.css');
	}

	/**
	 * Loads the required scripts and styles used in forms
	 */
	public static function loadEditorButtonDependencies()
	{
		self::loadMainDependencies();

		JHtml::_('bootstrap.popover');
	}

	public static function loadPopupDependencies()
	{
		self::loadMainDependencies();
		self::loadFormDependencies();

		self::style('regularlabs/popup.min.css');
	}

	/**
	 * Adds a javascript declaration to the page
	 *
	 * @param string $content
	 * @param string $name
	 * @param bool   $minify
	 * @param string $type
	 */
	public static function scriptDeclaration($content = '', $name = '', $minify = true, $type = 'text/javascript')
	{
		if ($minify)
		{
			$content = self::minify($content);
		}

		if ( ! empty($name))
		{
			$content = Protect::wrapScriptDeclaration($content, $name, $minify);
		}

		JFactory::getDocument()->addScriptDeclaration($content, $type);
	}

	/**
	 * Adds a stylesheet declaration to the page
	 *
	 * @param string $content
	 * @param string $name
	 * @param bool   $minify
	 * @param string $type
	 */
	public static function styleDeclaration($content = '', $name = '', $minify = true, $type = 'text/css')
	{
		if ($minify)
		{
			$content = self::minify($content);
		}

		if ( ! empty($name))
		{
			$content = Protect::wrapStyleDeclaration($content, $name, $minify);
		}

		JFactory::getDocument()->addStyleDeclaration($content, $type);
	}

	/**
	 * Remove style/css blocks from html string
	 *
	 * @param string $string
	 * @param string $name
	 * @param string $alias
	 */
	public static function removeScriptsStyles(&$string, $name, $alias = '')
	{
		list($start, $end) = Protect::getInlineCommentTags($name, null, true);
		$alias = $alias ?: Extension::getAliasByName($name);

		$string = RegEx::replace('((?:;\s*)?)(;?)' . $start . '.*?' . $end . '\s*', '\1', $string);
		$string = RegEx::replace('\s*<link [^>]*href="[^"]*/(' . $alias . '/css|css/' . $alias . ')/[^"]*\.css[^"]*"[^>]*( /)?>', '', $string);
		$string = RegEx::replace('\s*<script [^>]*src="[^"]*/(' . $alias . '/js|js/' . $alias . ')/[^"]*\.js[^"]*"[^>]*></script>', '', $string);
	}

	/**
	 * Remove joomla script options
	 *
	 * @param string $string
	 * @param string $name
	 * @param string $alias
	 */
	public static function removeScriptsOptions(&$string, $name, $alias = '')
	{
		RegEx::match(
			'(<script type="application/json" class="joomla-script-options new">)(.*?)(</script>)',
			$string,
			$match
		);

		if (empty($match))
		{
			return;
		}

		$alias = $alias ?: Extension::getAliasByName($name);

		$scripts = json_decode($match[2]);

		if ( ! isset($scripts->{'rl_' . $alias}))
		{
			return;
		}

		unset($scripts->{'rl_' . $alias});

		$string = str_replace(
			$match[0],
			$match[1] . json_encode($scripts) . $match[3],
			$string
		);
	}

	/**
	 * Returns the document buffer
	 *
	 * @return null|string
	 */
	public static function getBuffer()
	{
		$buffer = JFactory::getDocument()->getBuffer('component');

		if (empty($buffer) || ! is_string($buffer))
		{
			return null;
		}

		$buffer = trim($buffer);

		if (empty($buffer))
		{
			return null;
		}

		return $buffer;
	}

	/**
	 * Set the document buffer
	 *
	 * @param string $buffer
	 */
	public static function setBuffer($buffer = '')
	{
		JFactory::getDocument()->setBuffer($buffer, 'component');
	}

	/**
	 * Minify the given string
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function minify($string)
	{
		// place new lines around string to make regex searching easier
		$string = "\n" . $string . "\n";

		// Remove comment lines
		$string = RegEx::replace('\n\s*//.*?\n', '', $string);
		// Remove comment blocks
		$string = RegEx::replace('/\*.*?\*/', '', $string);
		// Remove enters
		$string = RegEx::replace('\n\s*', ' ', $string);

		// Remove surrounding whitespace
		$string = trim($string);

		return $string;
	}
}
