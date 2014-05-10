<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  form
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('F0F_INCLUDED') or die;

/**
 * Generic interface that a F0F form field class must implement
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
interface F0FFormField
{
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @return  string  The field HTML
	 *
	 * @since 2.0
	 */
	public function getStatic();

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @return  string  The field HTML
	 *
	 * @since 2.0
	 */
	public function getRepeatable();
}
