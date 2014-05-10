<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  query
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('F0F_INCLUDED') or die;

/**
 * FrameworkOnFramework query base class; for compatibility purposes
 *
 * @package     FrameworkOnFramework
 * @since       2.1
 * @deprecated  2.1
 */
abstract class F0FQueryAbstract
{
	/**
	 * Returns a new database query class
	 *
	 * @param   JDatabaseDriver  $db  The DB driver which will provide us with a query object
	 *
	 * @return F0FQueryAbstract
	 */
	public static function &getNew($db = null)
	{
		F0FPlatform::getInstance()->logDeprecated('F0FQueryAbstract is deprecated. Use JDatabaseQuery instead.');

		if (is_null($db))
		{
			$ret = F0FPlatform::getInstance()->getDbo()->getQuery(true);
		}
		else
		{
			$ret = $db->getQuery(true);
		}

		return $ret;
	}
}
