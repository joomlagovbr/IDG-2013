<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Fields
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;

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
	 * Normalizes the request data.
	 *
	 * @param   string  $context  The context
	 * @param   object  $data     The object
	 * @param   Form    $form     The form
	 *
	 * @return  void
	 *
	 * @since   3.8.7
	 */
	public function onContentNormaliseRequestData($context, $data, Form $form)
	{
		if (!FieldsHelper::extract($context, $data))
		{
			return true;
		}

		// Loop over all fields
		foreach ($form->getGroup('com_fields') as $field)
		{
			if ($field->disabled === true)
			{
				/**
				 * Disabled fields should NEVER be added to the request as
				 * they should NEVER be added by the browser anyway so nothing to check against
				 * as "disabled" means no interaction at all.
				 */

				// Make sure the data object has an entry before delete it
				if (isset($data->com_fields[$field->fieldname]))
				{
					unset($data->com_fields[$field->fieldname]);
				}

				continue;
			}

			// Make sure the data object has an entry
			if (isset($data->com_fields[$field->fieldname]))
			{
				continue;
			}

			// Set a default value for the field
			$data->com_fields[$field->fieldname] = false;
		}
	}

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
		if (!is_array($data) || empty($item->id) || empty($data['com_fields']))
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

		// Loading the model
		$model = JModelLegacy::getInstance('Field', 'FieldsModel', array('ignore_request' => true));

		// Loop over the fields
		foreach ($fields as $field)
		{
			// Determine the value if it is (un)available from the data
			if (key_exists($field->name, $data['com_fields']))
			{
				$value = $data['com_fields'][$field->name] === false ? null : $data['com_fields'][$field->name];
			}
			// Field not available on form, use stored value
			else
			{
				$value = $field->rawvalue;
			}

			// If no value set (empty) remove value from database
			if (is_array($value) ? !count($value) : !strlen($value))
			{
				$value = null;
			}

			// JSON encode value for complex fields
			if (is_array($value) && (count($value, COUNT_NORMAL) !== count($value, COUNT_RECURSIVE) || !count(array_filter(array_keys($value), 'is_numeric'))))
			{
				$value = json_encode($value);
			}

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

		// Convert tags
		if ($context == 'com_tags.tag' && !empty($item->type_alias))
		{
			// Set the context
			$context = $item->type_alias;

			$item = $this->prepareTagItem($item);
		}

		if (is_string($params) || !$params)
		{
			$params = new Registry($params);
		}

		$fields = FieldsHelper::getFields($context, $item, $displayType);

		if ($fields)
		{
			$app = Factory::getApplication();

			if ($app->isClient('site') && Multilanguage::isEnabled() && isset($item->language) && $item->language == '*')
			{
				$lang = $app->getLanguage()->getTag();

				foreach ($fields as $key => $field)
				{
					if ($field->language == '*' || $field->language == $lang)
					{
						continue;
					}

					unset($fields[$key]);
				}
			}
		}

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
		// Check property exists (avoid costly & useless recreation), if need to recreate them, just unset the property!
		if (isset($item->jcfields))
		{
			return;
		}

		$parts = FieldsHelper::extract($context, $item);

		if (!$parts)
		{
			return;
		}

		$context = $parts[0] . '.' . $parts[1];

		// Convert tags
		if ($context == 'com_tags.tag' && !empty($item->type_alias))
		{
			// Set the context
			$context = $item->type_alias;

			$item = $this->prepareTagItem($item);
		}

		// Get item's fields, also preparing their value property for manual display
		// (calling plugins events and loading layouts to get their HTML display)
		$fields = FieldsHelper::getFields($context, $item, true);

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

	/**
	 * Prepares a tag item to be ready for com_fields.
	 *
	 * @param   stdClass  $item  The item
	 *
	 * @return  object
	 *
	 * @since   3.8.4
	 */
	private function prepareTagItem($item)
	{
		// Map core fields
		$item->id       = $item->content_item_id;
		$item->language = $item->core_language;

		// Also handle the catid
		if (!empty($item->core_catid))
		{
			$item->catid = $item->core_catid;
		}

		return $item;
	}
}
