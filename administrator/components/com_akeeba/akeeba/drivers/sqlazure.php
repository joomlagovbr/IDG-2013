<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * SQL Azure database driver
 *
 * Based on Joomla! Platform 11.2
 */
class AEDriverSqlazure extends AEDriverSqlsrv
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 */
	public $name = 'sqlazure';

}
