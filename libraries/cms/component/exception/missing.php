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
 * Exception class defining an error for a missing component
 *
 * @since  3.7.0
 */
class JComponentExceptionMissing extends InvalidArgumentException
{
	/**
	 * Constructor
	 *
	 * @param   string     $message   The Exception message to throw.
	 * @param   integer    $code      The Exception code.
	 * @param   Exception  $previous  The previous exception used for the exception chaining.
	 *
	 * @since   3.7.0
	 */
	public function __construct($message = '', $code = 404, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
