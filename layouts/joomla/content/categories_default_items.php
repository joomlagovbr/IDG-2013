<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$class = ' class="first"';
JHtml::_('bootstrap.tooltip');

$item = $displayData->item;
$items = $displayData->get('items');
$params = $displayData->params;
$extension = $displayData->get('extension');
$className = substr($extension, 4);
// This will work for the core components but not necessarily for other components
// that may have different pluralisation rules.
if (substr($className, -1) == 's')
{
	$className = rtrim($className, 's');
}
