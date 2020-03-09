<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.ArticlesCategory
 *
 * @author      Rene Bentes Pinto <renebentes@yahoo.com.br>
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2020 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 * @since       3.3.0
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

/**
 * Helper for Birthdays layout
 */
class BirthdaysHelper
{
	/**
	 * Add Joomla Custom fields to elements
	 *
	 * @param   array  $list  list of articles
	 *
	 * @return  void
	 *
	 * @since 3.3.0
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

	/**
	 * Filter items by birthday period
	 *
	 * @param   array  $list       list of items
	 * @param   JDate  $startDate  initial day
	 * @param   JDate  $endDate    last day
	 *
	 * @return  void
	 *
	 * @since   3.3.0
	 */
	public static function filterByBirthday(&$list, $startDate, $endDate)
	{
		foreach ($list as $key => $item) {
			if (isset($item->fields['birthday'])) {
				$birthday = new JDate($item->fields['birthday']->rawvalue);

				if ($birthday < $startDate || $birthday > $endDate) {
					unset($list[$key]);
				}
			}

		}

		self::orderByBirthday($list);
	}

	/**
	 * Prepare name to show
	 *
	 * @param   string $item  The item value
	 *
	 * @return  string
	 *
	 * @since   3.3.0
	 */
	public static function prepareName($item)
	{
		$output = '';
		$found = array();

		if (isset($item->fields['nickname'])) {
			$names = explode(' ', $item->title);
			$nicknames = explode(' ', $item->fields['nickname']->value);

			foreach ($names as $name) {
				if (in_array($name, $nicknames) && !in_array($name, $found)) {
					$output .= '<strong>' . $name . '</strong> ';
					$found[] = $name;
				} elseif (
					in_array(substr($name, 0, 1), $nicknames) &&
					!in_array(substr($name, 0, 1), $found)
				) {
					$output .=
						'<strong>' .
						substr($name, 0, 1) .
						'</strong>' .
						substr($name, 1) .
						' ';
					$found[] = substr($name, 0, 1);
				} else {
					$output .= $name . ' ';
				}
			}
		} else {
			$output = $item->title;
		}

		return $output;
	}

	/**
	 * Sort items by birthday
	 *
	 * @param   array $list  list of items
	 *
	 * @return  void
	 *
	 * @since   3.3.0
	 */
	private static function orderByBirthday(&$list)
	{
		uasort($list, function ($a, $b) {
			return $a->fields['birthday']->value >
				$b->fields['birthday']->value;
		});
	}
}
