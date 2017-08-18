<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Fields
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

/**
 * Fields Plugin
 *
 * @since  3.7
 */
class PlgSystemFields extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.7.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * The save event.
	 *
	 * @param   string   $context  The context
	 * @param   JTable   $item     The table
	 * @param   boolean  $isNew    Is new item
	 * @param   array    $data     The validated data
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterSave($context, $item, $isNew, $data = array())
	{
		// Check if data is an array and the item has an id
		if (!is_array($data) || empty($item->id))
		{
			return true;
		}

		// Create correct context for category
		if ($context == 'com_categories.category')
		{
			$context = $item->extension . '.categories';

			// Set the catid on the category to get only the fields which belong to this category
			$item->catid = $item->id;
		}

		// Check the context
		$parts = FieldsHelper::extract($context, $item);

		if (!$parts)
		{
			return true;
		}

		// Compile the right context for the fields
		$context = $parts[0] . '.' . $parts[1];

		// Loading the fields
		$fields = FieldsHelper::getFields($context, $item);

		if (!$fields)
		{
			return true;
		}

		// Get the fields data
		$fieldsData = !empty($data['com_fields']) ? $data['com_fields'] : array();

		// Loading the model
		$model = JModelLegacy::getInstance('Field', 'FieldsModel', array('ignore_request' => true));

		// Loop over the fields
		foreach ($fields as $field)
		{
			// Determine the value if it is available from the data
			$value = key_exists($field->name, $fieldsData) ? $fieldsData[$field->name] : null;

			// Setting the value for the field and the item
			$model->setFieldValue($field->id, $item->id, $value);
		}

		return true;
	}

	/**
	 * The save event.
	 *
	 * @param   array    $userData  The date
	 * @param   boolean  $isNew     Is new
	 * @param   boolean  $success   Is success
	 * @param   string   $msg       The message
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onUserAfterSave($userData, $isNew, $success, $msg)
	{
		// It is not possible to manipulate the user during save events
		// Check if data is valid or we are in a recursion
		if (!$userData['id'] || !$success)
		{
			return true;
		}

		$user = JFactory::getUser($userData['id']);

		$task = JFactory::getApplication()->input->getCmd('task');

		// Skip fields save when we activate a user, because we will lose the saved data
		if (in_array($task, array('activate', 'block', 'unblock')))
		{
			return true;
		}

		// Trigger the events with a real user
		$this->onContentAfterSave('com_users.user', $user, false, $userData);

		return true;
	}

	/**
	 * The delete event.
	 *
	 * @param   string    $context  The context
	 * @param   stdClass  $item     The item
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterDelete($context, $item)
	{
		$parts = FieldsHelper::extract($context, $item);

		if (!$parts || empty($item->id))
		{
			return true;
		}

		$context = $parts[0] . '.' . $parts[1];

		JLoader::import('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models', 'FieldsModel');

		$model = JModelLegacy::getInstance('Field', 'FieldsModel', array('ignore_request' => true));
		$model->cleanupValues($context, $item->id);

		return true;
	}

	/**
	 * The user delete event.
	 *
	 * @param   stdClass  $user    The context
	 * @param   boolean   $succes  Is success
	 * @param   string    $msg     The message
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onUserAfterDelete($user, $succes, $msg)
	{
		$item     = new stdClass;
		$item->id = $user['id'];

		return $this->onContentAfterDelete('com_users.user', $item);
	}

	/**
	 * The form event.
	 *
	 * @param   JForm     $form  The form
	 * @param   stdClass  $data  The data
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onContentPrepareForm(JForm $form, $data)
	{
		$context = $form->getName();

		// When a category is edited, the context is com_categories.categorycom_content
		if (strpos($context, 'com_categories.category') === 0)
		{
			$context = str_replace('com_categories.category', '', $context) . '.categories';

			// Set the catid on the category to get only the fields which belong to this category
			if (is_array($data) && key_exists('id', $data))
			{
				$data['catid'] = $data['id'];
			}
			if (is_object($data) && isset($data->id))
			{
				$data->catid = $data->id;
			}
		}

		$parts = FieldsHelper::extract($context, $form);

		if (!$parts)
		{
			return true;
		}

		$input = JFactory::getApplication()->input;

		// If we are on the save command we need the actual data
		$jformData = $input->get('jform', array(), 'array');

		if ($jformData && !$data)
		{
			$data = $jformData;
		}

		if (is_array($data))
		{
			$data = (object) $data;
		}

		FieldsHelper::prepareForm($parts[0] . '.' . $parts[1], $form, $data);

		return true;
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterTitle($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 1);
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentBeforeDisplay($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 2);
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterDisplay($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 3);
	}

	/**
	 * Performs the display event.
	 *
	 * @param   string    $context      The context
	 * @param   stdClass  $item         The item
	 * @param   Registry  $params       The params
	 * @param   integer   $displayType  The type
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	private function display($context, $item, $params, $displayType)
	{
		$parts = FieldsHelper::extract($context, $item);

		if (!$parts)
		{
			return '';
		}

		// If we have a category, set the catid field to fetch only the fields which belong to it
		if ($parts[1] == 'categories' && !isset($item->catid))
		{
			$item->catid = $item->id;
		}

		$context = $parts[0] . '.' . $parts[1];

		if (is_string($params) || !$params)
		{
			$params = new Registry($params);
		}

		$fields = FieldsHelper::getFields($context, $item, true);

		if ($fields)
		{
			foreach ($fields as $key => $field)
			{
				$fieldDisplayType = $field->params->get('display', '2');

				if ($fieldDisplayType == $displayType)
				{
					continue;
				}

				unset($fields[$key]);
			}
		}

		if ($fields)
		{
			return FieldsHelper::render(
				$context,
				'fields.render',
				array(
					'item'            => $item,
					'context'         => $context,
					'fields'          => $fields
				)
			);
		}

		return '';
	}

	/**
	 * Performs the display event.
	 *
	 * @param   string    $context  The context
	 * @param   stdClass  $item     The item
	 *
	 * @return  void
	 *
	 * @since   3.7.0
	 */
	public function onContentPrepare($context, $item)
	{
		$parts = FieldsHelper::extract($context, $item);

		if (!$parts)
		{
			return;
		}

		$fields = FieldsHelper::getFields($parts[0] . '.' . $parts[1], $item, true);

		// Adding the fields to the object
		$item->jcfields = array();

		foreach ($fields as $key => $field)
		{
			$item->jcfields[$field->id] = $field;
		}
	}

	/**
	 * The finder event.
	 *
	 * @param   stdClass  $item  The item
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	public function onPrepareFinderContent($item)
	{
		$section = strtolower($item->layout);
		$tax     = $item->getTaxonomy('Type');

		if ($tax)
		{
			foreach ($tax as $context => $value)
			{
				// This is only a guess, needs to be improved
				$component = strtolower($context);

				if (strpos($context, 'com_') !== 0)
				{
					$component = 'com_' . $component;
				}

				// Transform com_article to com_content
				if ($component === 'com_article')
				{
					$component = 'com_content';
				}

				// Create a dummy object with the required fields
				$tmp     = new stdClass;
				$tmp->id = $item->__get('id');

				if ($item->__get('catid'))
				{
					$tmp->catid = $item->__get('catid');
				}

				// Getting the fields for the constructed context
				$fields = FieldsHelper::getFields($component . '.' . $section, $tmp, true);

				if (is_array($fields))
				{
					foreach ($fields as $field)
					{
						// Adding the instructions how to handle the text
						$item->addInstruction(FinderIndexer::TEXT_CONTEXT, $field->name);

						// Adding the field value as a field
						$item->{$field->name} = $field->value;
					}
				}
			}
		}

		return true;
	}
}
