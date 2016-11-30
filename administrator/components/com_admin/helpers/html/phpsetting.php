<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Utility class working with phpsetting
 *
 * @since  1.6
 */
abstract class JHtmlPhpSetting
{
	/**
	 * Method to generate a boolean message for a value
	 *
	 * @param   boolean  $val  is the value set?
	 *
	 * @return  string html code
	 */
	public static function boolean($val)
	{
		return JText::_($val ? 'JON' : 'JOFF');
	}

	/**
	 * Method to generate a boolean message for a value
	 *
	 * @param   boolean  $val  is the value set?
	 *
	 * @return  string html code
	 */
	public static function set($val)
	{
		return JText::_($val ? 'JYES' : 'JNO');
	}

	/**
	 * Method to generate a string message for a value
	 *
	 * @param   string  $val  a php ini value
	 *
	 * @return  string html code
	 */
	public static function string($val)
	{
		return !empty($val) ? $val : JText::_('JNONE');
	}

	/**
	 * Method to generate an integer from a value
	 *
	 * @param   string  $val  a php ini value
	 *
	 * @return  string html code
	 *
	 * @deprecated  4.0  Use intval() or casting instead.
	 */
	public static function integer($val)
	{
		JLog::add(
			'JHtmlPhpSetting::integer() is deprecated. Use intval() or casting instead.',
			JLog::WARNING,
			'deprecated'
		);

		return (int) $val;
	}
}
