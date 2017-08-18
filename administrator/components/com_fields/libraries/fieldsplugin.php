<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Abstract Fields Plugin
 *
 * @since  3.7.0
 */
abstract class FieldsPlugin extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * Returns the custom fields types.
	 *
	 * @return  string[][]
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsGetTypes()
	{
		$types = array();

		// The root of the plugin
		$root = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name;

		foreach (JFolder::files($root . '/tmpl', '.php') as $layout)
		{
			// Strip the extension
			$layout = str_replace('.php', '', $layout);

			// The data array
			$data = array();

			// The language key
			$key = strtoupper($layout);

			if ($key != strtoupper($this->_name))
			{
				$key = strtoupper($this->_name) . '_' . $layout;
			}

			// Needed attributes
			$data['type'] = $layout;

			if (JFactory::getLanguage()->hasKey('PLG_FIELDS_' . $key . '_LABEL'))
			{
				$data['label'] = JText::sprintf('PLG_FIELDS_' . $key . '_LABEL', strtolower($key));

				// Fix wrongly set parentheses in RTL languages
				if (JFactory::getLanguage()->isRTL())
				{
					$data['label'] = $data['label'] . '&#x200E;';
				}
			}
			else
			{
				$data['label'] = $key;
			}

			$path = $root . '/fields';

			// Add the path when it exists
			if (file_exists($path))
			{
				$data['path'] = $path;
			}

			$path = $root . '/rules';

			// Add the path when it exists
			if (file_exists($path))
			{
				$data['rules'] = $path;
			}

			$types[] = $data;
		}

		// Return the data
		return $types;
	}

	/**
	 * Prepares the field value.
	 *
	 * @param   string    $context  The context.
	 * @param   stdclass  $item     The item.
	 * @param   stdclass  $field    The field.
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareField($context, $item, $field)
	{
		// Check if the field should be processed by us
		if (!$this->isTypeSupported($field->type))
		{
			return;
		}

		// Merge the params from the plugin and field which has precedence
		$fieldParams = clone $this->params;
		$fieldParams->merge($field->fieldparams);

		// Get the path for the layout file
		$path = JPluginHelper::getLayoutPath('fields', $field->type, $field->type);

		// Render the layout
		ob_start();
		include $path;
		$output = ob_get_clean();

		// Return the output
		return $output;
	}

	/**
	 * Transforms the field into a DOM XML element and appends it as a child on the given parent.
	 *
	 * @param   stdClass    $field   The field.
	 * @param   DOMElement  $parent  The field node parent.
	 * @param   JForm       $form    The form.
	 *
	 * @return  DOMElement
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		// Check if the field should be processed by us
		if (!$this->isTypeSupported($field->type))
		{
			return null;
		}

		$app = JFactory::getApplication();

		// Detect if the field should be shown at all
		if ($field->params->get('show_on') == 1 && $app->isClient('administrator'))
		{
			return;
		}
		elseif ($field->params->get('show_on') == 2 && $app->isClient('site'))
		{
			return null;
		}

		// Create the node
		$node = $parent->appendChild(new DOMElement('field'));

		// Set the attributes
		$node->setAttribute('name', $field->name);
		$node->setAttribute('type', $field->type);
		$node->setAttribute('label', $field->label);
		$node->setAttribute('description', $field->description);
		$node->setAttribute('class', $field->params->get('class'));
		$node->setAttribute('hint', $field->params->get('hint'));
		$node->setAttribute('required', $field->required ? 'true' : 'false');

		if ($field->default_value)
		{
			$defaultNode = $node->appendChild(new DOMElement('default'));
			$defaultNode->appendChild(new DOMCdataSection($field->default_value));
		}

		// Combine the two params
		$params = clone $this->params;
		$params->merge($field->fieldparams);

		// Set the specific field parameters
		foreach ($params->toArray() as $key => $param)
		{
			if (is_array($param))
			{
				// Multidimensional arrays (eg. list options) can't be transformed properly
				$param = count($param) == count($param, COUNT_RECURSIVE) ? implode(',', $param) : '';
			}

			if ($param === '' || (!is_string($param) && !is_numeric($param)))
			{
				continue;
			}

			$node->setAttribute($key, $param);
		}

		// Check if it is allowed to edit the field
		if (!FieldsHelper::canEditFieldValue($field))
		{
			$node->setAttribute('disabled', 'true');
		}

		// Return the node
		return $node;
	}

	/**
	 * The form event. Load additional parameters when available into the field form.
	 * Only when the type of the form is of interest.
	 *
	 * @param   JForm     $form  The form
	 * @param   stdClass  $data  The data
	 *
	 * @return  void
	 *
	 * @since   3.7.0
	 */
	public function onContentPrepareForm(JForm $form, $data)
	{
		// Check if the field form is calling us
		if (strpos($form->getName(), 'com_fields.field') !== 0)
		{
			return;
		}

		// Ensure it is an object
		$formData = (object) $data;

		// Gather the type
		$type = $form->getValue('type');

		if (!empty($formData->type))
		{
			$type = $formData->type;
		}

		// Not us
		if (!$this->isTypeSupported($type))
		{
			return;
		}

		$path = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/params/' . $type . '.xml';

		// Check if params file exists
		if (!file_exists($path))
		{
			return;
		}

		// Load the specific plugin parameters
		$form->load(file_get_contents($path), true, '/form/*');
	}

	/**
	 * Returns true if the given type is supported by the plugin.
	 *
	 * @param   string  $type  The type
	 *
	 * @return  boolean
	 *
	 * @since   3.7.0
	 */
	protected function isTypeSupported($type)
	{
		foreach ($this->onCustomFieldsGetTypes() as $typeSpecification)
		{
			if ($type == $typeSpecification['type'])
			{
				return true;
			}
		}

		return false;
	}
}
