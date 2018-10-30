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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use JFactory;
use JLanguageHelper;
use JUri;
use RegularLabs\Library\RegEx;
use RegularLabs\Library\StringHelper;

/**
 * Class HomePage
 * @package RegularLabs\Library\Condition
 */
class HomePage
	extends \RegularLabs\Library\Condition
{
	public function pass()
	{
		$home = JFactory::getApplication()->getMenu('site')->getDefault(JFactory::getLanguage()->getTag());

		// return if option or other set values do not match the homepage menu item values
		if ($this->request->option)
		{
			// check if option is different to home menu
			if ( ! $home || ! isset($home->query['option']) || $home->query['option'] != $this->request->option)
			{
				return $this->_(false);
			}

			if ( ! $this->request->option)
			{
				// set the view/task/layout in the menu item to empty if not set
				$home->query['view']   = isset($home->query['view']) ? $home->query['view'] : '';
				$home->query['task']   = isset($home->query['task']) ? $home->query['task'] : '';
				$home->query['layout'] = isset($home->query['layout']) ? $home->query['layout'] : '';
			}

			// check set values against home menu query items
			foreach ($home->query as $k => $v)
			{
				if ((isset($this->request->{$k}) && $this->request->{$k} != $v)
					|| (
						( ! isset($this->request->{$k}) || in_array($v, ['virtuemart', 'mijoshop']))
						&& JFactory::getApplication()->input->get($k) != $v
					)
				)
				{
					return $this->_(false);
				}
			}

			// check post values against home menu params
			foreach ($home->params->toObject() as $k => $v)
			{
				if (($v && isset($_POST[$k]) && $_POST[$k] != $v)
					|| ( ! $v && isset($_POST[$k]) && $_POST[$k])
				)
				{
					return $this->_(false);
				}
			}
		}

		$pass = $this->checkPass($home);

		if ( ! $pass)
		{
			$pass = $this->checkPass($home, true);
		}

		return $this->_($pass);
	}

	private function checkPass(&$home, $addlang = false)
	{
		$uri = JUri::getInstance();

		if ($addlang)
		{
			$sef = $uri->getVar('lang');
			if (empty($sef))
			{
				$langs = array_keys(JLanguageHelper::getLanguages('sef'));
				$path  = StringHelper::substr(
					$uri->toString(['scheme', 'user', 'pass', 'host', 'port', 'path']),
					StringHelper::strlen($uri->base())
				);
				$path  = RegEx::replace('^index\.php/?', '', $path);
				$parts = explode('/', $path);
				$part  = reset($parts);
				if (in_array($part, $langs))
				{
					$sef = $part;
				}
			}

			if (empty($sef))
			{
				return false;
			}
		}

		$query = $uri->toString(['query']);
		if (strpos($query, 'option=') === false && strpos($query, 'Itemid=') === false)
		{
			$url = $uri->toString(['host', 'path']);
		}
		else
		{
			$url = $uri->toString(['host', 'path', 'query']);
		}

		// remove the www.
		$url = RegEx::replace('^www\.', '', $url);
		// replace ampersand chars
		$url = str_replace('&amp;', '&', $url);
		// remove any language vars
		$url = RegEx::replace('((\?)lang=[a-z-_]*(&|$)|&lang=[a-z-_]*)', '\2', $url);
		// remove trailing nonsense
		$url = trim(RegEx::replace('/?\??&?$', '', $url));
		// remove the index.php/
		$url = RegEx::replace('/index\.php(/|$)', '/', $url);
		// remove trailing /
		$url = trim(RegEx::replace('/$', '', $url));

		$root = JUri::root();

		// remove the http(s)
		$root = RegEx::replace('^.*?://', '', $root);
		// remove the www.
		$root = RegEx::replace('^www\.', '', $root);
		//remove the port
		$root = RegEx::replace(':[0-9]+', '', $root);
		// so also passes on urls with trailing /, ?, &, /?, etc...
		$root = RegEx::replace('(Itemid=[0-9]*).*^', '\1', $root);
		// remove trailing /
		$root = trim(RegEx::replace('/$', '', $root));

		if ($addlang)
		{
			$root .= '/' . $sef;
		}

		/* Pass urls:
		 * [root]
		 */
		$regex = '^' . $root . '$';

		if (RegEx::match($regex, $url))
		{
			return true;
		}

		/* Pass urls:
		 * [root]?Itemid=[menu-id]
		 * [root]/?Itemid=[menu-id]
		 * [root]/index.php?Itemid=[menu-id]
		 * [root]/[menu-alias]
		 * [root]/[menu-alias]?Itemid=[menu-id]
		 * [root]/index.php?[menu-alias]
		 * [root]/index.php?[menu-alias]?Itemid=[menu-id]
		 * [root]/[menu-link]
		 * [root]/[menu-link]&Itemid=[menu-id]
		 */
		$regex = '^' . $root
			. '(/('
			. 'index\.php'
			. '|'
			. '(index\.php\?)?' . RegEx::quote($home->alias)
			. '|'
			. RegEx::quote($home->link)
			. ')?)?'
			. '(/?[\?&]Itemid=' . (int) $home->id . ')?'
			. '$';

		return RegEx::match($regex, $url);
	}
}
