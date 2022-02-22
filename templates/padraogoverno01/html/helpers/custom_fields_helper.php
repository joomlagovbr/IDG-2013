<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules
 *
 * @author      Rene Bentes Pinto <renebentes@yahoo.com.br>
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2021 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 * @since       3.3.1
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

/**
 * Helper type for Custom Fields
 *
 * @since 3.3.1
 */
abstract class CustomFieldsHelper
{
	/**
	 * Add Joomla Custom fields to articles list
	 *
	 * @param   array  $list  list of articles
	 *
	 * @return  void
	 *
	 * @since 3.3.1
	 */
	public static function addCustomFields(&$list)
	{
		foreach ($list as $key => $item) {
			$fields = FieldsHelper::getFields(
				'com_content.article',
				$item,
				true
			);

			foreach ($fields as $field) {
				if ($field->value) {
					$item->fields[$field->name] = $field;
				}
			}
		}
	}
}
