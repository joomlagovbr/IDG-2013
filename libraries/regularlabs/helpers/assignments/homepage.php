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

/* @DEPRECATED */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/text.php';
require_once dirname(__DIR__) . '/string.php';
require_once dirname(__DIR__) . '/assignment.php';

class RLAssignmentsHomePage extends RLAssignment
{
	public function passHomePage()
	{
		$home = JFactory::getApplication()->getMenu('site')->getDefault(JFactory::getLanguage()->getTag());

		// return if option or other set values do not match the homepage menu item values
		if ($this->request->option)
		{
			// check if option is different to home menu
			if ( ! $home || ! isset($home->query['option']) || $home->query['option'] != $this->request->option)
			{
				return $this->pass(false);
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
					return $this->pass(false);
				}
			}

			// check post values against home menu params
			foreach ($home->params->toObject() as $k => $v)
			{
				if (($v && isset($_POST[$k]) && $_POST[$k] != $v)
					|| ( ! $v && isset($_POST[$k]) && $_POST[$k])
				)
				{
					return $this->pass(false);
				}
			}
		}

		$pass = $this->checkPass($home);

		if ( ! $pass)
		{
			$pass = $this->checkPass($home, 1);
		}

		return $this->pass($pass);
	}

	private function checkPass(&$home, $addlang = 0)
	{
		$uri = JUri::getInstance();

		if ($addlang)
		{
			$sef = $uri->getVar('lang');
			if (empty($sef))
			{
				$langs = array_keys(JLanguageHelper::getLanguages('sef'));
				$path  = RLString::substr(
					$uri->toString(['scheme', 'user', 'pass', 'host', 'port', 'path']),
					RLString::strlen($uri->base())
				);
				$path  = preg_replace('#^index\.php/?#', '', $path);
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
		$url = preg_replace('#^www\.#', '', $url);
		// replace ampersand chars
		$url = str_replace('&amp;', '&', $url);
		// remove any language vars
		$url = preg_replace('#((\?)lang=[a-z-_]*(&|$)|&lang=[a-z-_]*)#', '\2', $url);
		// remove trailing nonsense
		$url = trim(preg_replace('#/?\??&?$#', '', $url));
		// remove the index.php/
		$url = preg_replace('#/index\.php(/|$)#', '/', $url);
		// remove trailing /
		$url = trim(preg_replace('#/$#', '', $url));

		$root = JUri::root();

		// remove the http(s)
		$root = preg_replace('#^.*?://#', '', $root);
		// remove the www.
		$root = preg_replace('#^www\.#', '', $root);
		//remove the port
		$root = preg_replace('#:[0-9]+#', '', $root);
		// so also passes on urls with trailing /, ?, &, /?, etc...
		$root = preg_replace('#(Itemid=[0-9]*).*^#', '\1', $root);
		// remove trailing /
		$root = trim(preg_replace('#/$#', '', $root));

		if ($addlang)
		{
			$root .= '/' . $sef;
		}

		/* Pass urls:
		 * [root]
		 */
		$regex = '#^' . $root . '$#i';

		if (preg_match($regex, $url))
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
		$regex = '#^' . $root
			. '(/('
			. 'index\.php'
			. '|'
			. '(index\.php\?)?' . RLText::pregQuote($home->alias)
			. '|'
			. RLText::pregQuote($home->link)
			. ')?)?'
			. '(/?[\?&]Itemid=' . (int) $home->id . ')?'
			. '$#i';

		return preg_match($regex, $url);
	}
}
