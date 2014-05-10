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
 * based on the viewing access levels.
 *
 * @package  FrameworkOnFramework
 * @since    2.1
 */
class F0FModelBehaviorAccess extends F0FModelBehavior
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

		// Get the name of the access field
		$table       = $model->getTable();
		$accessField = $table->getColumnAlias('access');

		// Make sure the field actually exists
		if (!in_array($accessField, $table->getKnownFields()))
		{
			return;
		}

		$model->applyAccessFiltering(null);
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
			$fieldName = $record->getColumnAlias('access');

			// Make sure the field actually exists
			if (!in_array($fieldName, $record->getKnownFields()))
			{
				return;
			}

			// Get the user
			$user = F0FPlatform::getInstance()->getUser();

			// Filter by authorised access levels
			if (!in_array($record->$fieldName, $user->getAuthorisedViewLevels()))
			{
				$record = null;
			}
		}
	}
}
