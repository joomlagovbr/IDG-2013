<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.Menu
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$title      = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ?: '';

$linktype = $item->title;

if ($item->params->get('menu_text', 1))
{  
	$linktype = '<div class="menu-boxed-title">' . $linktype . '</div>';
}

require JModuleHelper::getLayoutPath('mod_menu', 'boxed_image');


echo $linktype;
