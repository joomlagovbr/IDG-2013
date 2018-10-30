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

namespace RegularLabs\Plugin\System\RegularLabs;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\RegEx as RL_RegEx;

class AdminMenu
{
	public static function combine()
	{
		$params = Params::get();

		if ( ! $params->combine_admin_menu)
		{
			return;
		}

		$html = JFactory::getApplication()->getBody();

		if ($html == '')
		{
			return;
		}

		if (strpos($html, '<ul id="menu"') === false
			|| (strpos($html, '">Regular Labs ') === false
				&& strpos($html, '" >Regular Labs ') === false)
		)
		{
			return;
		}

		if ( ! RL_RegEx::matchAll(
			'<li><a class="(?:no-dropdown )?menu-[^>]*>Regular Labs [^<]*</a></li>',
			$html,
			$matches,
			null,
			PREG_PATTERN_ORDER
		)
		)
		{
			return;
		}

		$menu_items = $matches[0];

		if (count($menu_items) < 2)
		{
			return;
		}

		$manager = null;

		foreach ($menu_items as $i => &$menu_item)
		{
			RL_RegEx::match('class="(?:no-dropdown )?menu-(.*?)"', $menu_item, $icon);

			$icon = str_replace('icon-icon-', 'icon-', 'icon-' . $icon[1]);

			$menu_item = str_replace(
				['>Regular Labs - ', '>Regular Labs '],
				'><span class="icon-reglab ' . $icon . '"></span> ',
				$menu_item
			);

			if ($icon != 'icon-regularlabsmanager')
			{
				continue;
			}

			$manager = $menu_item;
			unset($menu_items[$i]);
		}

		$main_link = "";

		if ( ! is_null($manager))
		{
			array_unshift($menu_items, $manager);
			$main_link = 'href="index.php?option=com_regularlabsmanager"';
		}

		$new_menu_item =
			'<li class="dropdown-submenu">'
			. '<a class="dropdown-toggle menu-regularlabs" data-toggle="dropdown" ' . $main_link . '>Regular Labs</a>'
			. "\n" . '<ul id="menu-cregularlabs" class="dropdown-menu menu-scrollable menu-component">'
			. "\n" . implode("\n", $menu_items)
			. "\n" . '</ul>'
			. '</li>';

		$first = array_shift($matches[0]);

		$html = str_replace($first, $new_menu_item, $html);
		$html = str_replace($matches[0], '', $html);

		JFactory::getApplication()->setBody($html);
	}

	public static function addHelpItem()
	{
		$params = Params::get();

		if ( ! $params->show_help_menu)
		{
			return;
		}

		$html = JFactory::getApplication()->getBody();

		if ($html == '')
		{
			return;
		}

		$pos_1 = strpos($html, '<!-- Top Navigation -->');
		$pos_2 = strpos($html, '<!-- Header -->');

		if ( ! $pos_1 || ! $pos_2)
		{
			return;
		}

		$nav = substr($html, $pos_1, $pos_2 - $pos_1);

		$shop_item = '(\s*<li>\s*<a [^>]*class="[^"]*menu-help-)shop("\s[^>]*)href="[^"]+\.joomla\.org[^"]*"([^>]*>)[^<]*(</a>s*</li>)';

		$nav = RL_RegEx::replace(
			$shop_item,
			'\0<li class="divider"><span></span></li>\1dev\2href="https://www.regularlabs.com"\3Regular Labs Extensions\4',
			$nav
		);

		// Just in case something fails
		if (empty($nav))
		{
			return;
		}

		$html = substr_replace($html, $nav, $pos_1, $pos_2 - $pos_1);

		JFactory::getApplication()->setBody($html);
	}
}
