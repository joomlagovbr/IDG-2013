<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.ArticlesCategory
 *
 * @author      Rene Bentes Pinto <renebentes@yahoo.com.br>
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2021 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 * @since       3.3.0
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

JLoader::register('CustomFieldsHelper', JPATH_THEMES . '/padraogoverno01/helpers/custom_fields_helper.php');

class PendingHelper extends CustomFieldsHelper
{
	/**
	 * Filter items pending
	 *
	 * @param   array  $list       list of items
	 *
	 * @return  void
	 *
	 * @since   3.3.0
	 */
	public static function filterPending(&$list)
	{
		foreach ($list as $key => $item) {
			if (isset($item->fields['pending'])
				&& $item->fields['pending']->rawvalue != 1 ) {
				unset($list[$key]);
			}
		}

		self::orderByLimitAt($list);
	}

	/**
	 * Sort items by limit at date
	 *
	 * @param   array $list  list of items
	 *
	 * @return  void
	 *
	 * @since   3.3.0
	 */
	private static function orderByLimitAt(&$list)
	{
		uasort($list, function ($a, $b) {
			$limitAtA = new JDate($a->fields['limitat']->rawvalue);
			$limitAtB = new JDate($b->fields['limitat']->rawvalue);

			return $limitAtA->format('md') > $limitAtB->format('md');
		});
	}
}
