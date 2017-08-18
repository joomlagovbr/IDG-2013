<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mock JSite class used to fool the frontend search plugins because they route the results.
 *
 * @since  1.5
 */
class JSite extends JObject
{
	/**
	 * False method to fool the frontend search plugins.
	 *
	 * @return  JSite
	 *
	 * @since  1.5
	 */
	public function getMenu()
	{
		$result = new JSite;

		return $result;
	}

	/**
	 * False method to fool the frontend search plugins.
	 *
	 * @return  array
	 *
	 * @since  1.5
	 */
	public function getItems()
	{
		return array();
	}
}
