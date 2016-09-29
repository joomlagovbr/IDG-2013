<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Crypt
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Joomla Platform Password Hashing Interface
 *
 * @since       12.2
 * @deprecated  4.0  Use PHP 5.5's native password hashing API
 */
interface JCryptPassword
{
	const BLOWFISH = '$2y$';

	const JOOMLA = 'Joomla';

	const PBKDF = '$pbkdf$';

	const MD5 = '$1$';

	/**
	 * Creates a password hash
	 *
	 * @param   string  $password  The password to hash.
	 * @param   string  $type      The type of hash. This determines the prefix of the hashing function.
	 *
	 * @return  string  The hashed password.
	 *
	 * @since   12.2
	 * @deprecated  4.0  Use PHP 5.5's native password hashing API
	 */
	public function create($password, $type = null);

	/**
	 * Verifies a password hash
	 *
	 * @param   string  $password  The password to verify.
	 * @param   string  $hash      The password hash to check.
	 *
	 * @return  boolean  True if the password is valid, false otherwise.
	 *
	 * @since   12.2
	 * @deprecated  4.0  Use PHP 5.5's native password hashing API
	 */
	public function verify($password, $hash);

	/**
	 * Sets a default prefix
	 *
	 * @param   string  $type  The prefix to set as default
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @deprecated  4.0  Use PHP 5.5's native password hashing API
	 */
	public function setDefaultType($type);

	/**
	 * Gets the default type
	 *
	 * @return  void
	 *
	 * @since   12.3
	 * @deprecated  4.0  Use PHP 5.5's native password hashing API
	 */
	public function getDefaultType();
}
