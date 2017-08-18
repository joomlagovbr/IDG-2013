<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Component
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Rule to process URLs without a menu item
 *
 * @since  3.4
 */
class JComponentRouterRulesNomenu implements JComponentRouterRulesInterface
{
	/**
	 * Router this rule belongs to
	 *
	 * @var JComponentRouterView
	 * @since 3.4
	 */
	protected $router;

	/**
	 * Class constructor.
	 *
	 * @param   JComponentRouterView  $router  Router this rule belongs to
	 *
	 * @since   3.4
	 */
	public function __construct(JComponentRouterView $router)
	{
		$this->router = $router;
	}

	/**
	 * Dummymethod to fullfill the interface requirements
	 *
	 * @param   array  &$query  The query array to process
	 *
	 * @return  void
	 *
	 * @since   3.4
	 * @codeCoverageIgnore
	 */
	public function preprocess(&$query)
	{
	}

	/**
	 * Parse a menu-less URL
	 *
	 * @param   array  &$segments  The URL segments to parse
	 * @param   array  &$vars      The vars that result from the segments
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function parse(&$segments, &$vars)
	{
		$active = $this->router->menu->getActive();

		if (!is_object($active))
		{
			$views = $this->router->getViews();

			if (isset($views[$segments[0]]))
			{
				$vars['view'] = array_shift($segments);

				if (isset($views[$vars['view']]->key) && isset($segments[0]))
				{
					$vars[$views[$vars['view']]->key] = preg_replace('/-/', ':', array_shift($segments), 1);
				}
			}
		}
	}

	/**
	 * Build a menu-less URL
	 *
	 * @param   array  &$query     The vars that should be converted
	 * @param   array  &$segments  The URL segments to create
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function build(&$query, &$segments)
	{
		$menu_found = false;

		if (isset($query['Itemid']))
		{
			$item = $this->router->menu->getItem($query['Itemid']);

			if (!isset($query['option']) || ($item && $item->query['option'] === $query['option']))
			{
				$menu_found = true;
			}
		}

		if (!$menu_found && isset($query['view']))
		{
			$views = $this->router->getViews();
			if (isset($views[$query['view']]))
			{
				$view = $views[$query['view']];
				$segments[] = $query['view'];

				if ($view->key && isset($query[$view->key]))
				{
					if (is_callable(array($this->router, 'get' . ucfirst($view->name) . 'Segment')))
					{
						$result = call_user_func_array(array($this->router, 'get' . ucfirst($view->name) . 'Segment'), array($query[$view->key], $query));
						$segments[] = str_replace(':', '-', array_shift($result));
					}
					else
					{
						$segments[] = str_replace(':', '-', $query[$view->key]);
					}
					unset($query[$views[$query['view']]->key]);
				}
				unset($query['view']);
			}
		}
	}
}
