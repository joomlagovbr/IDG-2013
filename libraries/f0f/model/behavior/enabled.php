<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  model
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('F0F_INCLUDED') or die;

/**
 * FrameworkOnFramework model behavior class to filter front-end access to items
 * that are enabled.
 *
 * @package  FrameworkOnFramework
 * @since    2.1
 */
class F0FModelBehaviorEnabled extends F0FModelBehavior
{
	/**
	 * This event runs after we have built the query used to fetch a record
	 * list in a model. It is used to apply automatic query filters.
	 *
	 * @param   F0FModel        &$model  The model which calls this event
	 * @param   JDatabaseQuery  &$query  The model which calls this event
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(&$model, &$query)
	{
		// This behavior only applies to the front-end.
		if (!F0FPlatform::getInstance()->isFrontend())
		{
			return;
		}

		// Get the name of the enabled field
		$table = $model->getTable();
		$enabledField = $table->getColumnAlias('enabled');

		// Make sure the field actually exists
		if (!in_array($enabledField, $table->getKnownFields()))
		{
			return;
		}

		// Filter by enabled fields only
		$db = F0FPlatform::getInstance()->getDbo();
		$query->where($db->qn($enabledField) . ' = ' . $db->q(1));
	}

	/**
	 * The event runs after F0FModel has called F0FTable and retrieved a single
	 * item from the database. It is used to apply automatic filters.
	 *
	 * @param   F0FModel  &$model   The model which was called
	 * @param   F0FTable  &$record  The record loaded from the databae
	 *
	 * @return  void
	 */
	public function onAfterGetItem(&$model, &$record)
	{
		if ($record instanceof F0FTable)
		{
			$fieldName = $record->getColumnAlias('enabled');

			// Make sure the field actually exists
			if (!in_array($fieldName, $record->getKnownFields()))
			{
				return;
			}

			if ($record->$fieldName != 1)
			{
				$record = null;
			}
		}
	}
}
