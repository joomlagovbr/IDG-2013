<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.Menu
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if ($item->menu_image)
{
	if ($item->params->get('menu_text', 1))
	{
    	$linktype .= '<div class="menu-boxed-image">'; 
  	}
	
  	if ($item->menu_image_css)
	{
		$image_attributes['class'] = $item->menu_image_css;
		$linktype .= JHtml::_('image', $item->menu_image, $item->title, $image_attributes);
	}
	else
	{
		$linktype .= JHtml::_('image', $item->menu_image, $item->title);
	}

	if ($item->params->get('menu_text', 1))
	{
    	$linktype .= '</div>'; 
  	}
}