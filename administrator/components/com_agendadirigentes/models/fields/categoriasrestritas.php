<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('category');

/**
 * Form Field class for the Joomla Platform.
 * Supports an HTML select list of categories
 *
 * @package     Joomla.Legacy
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldCategoriasrestritas extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Categoriasrestritas';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $this->element['scope'];
		$published = (string) $this->element['published'];

		// Load the category options for a given extension.
		if (!empty($extension))
		{
			// Filter over published state or not depending upon if it is present.
			if ($published)
			{
				$options = JHtml::_('category.options', $extension, array('filter.published' => explode(',', $published)));
			}
			else
			{
				$options = JHtml::_('category.options', $extension);
			}

			$componentParams = AgendaDirigentesHelper::getParams();			
			$restrictionType =  $this->getAttribute('restrictType', 'cargos');
			$restrictedList = $componentParams->get('restricted_list_' . $restrictionType, 0);
			if( $restrictedList == 1 && ! AgendaDirigentesHelper::isSuperUser() )
			{
				$input = JFactory::getApplication()->input;
				$id = $input->getInt('id', 0);

				if(empty($id)) //new
				{
					foreach ($options as $i => $option)
					{
						$canCreate = AgendaDirigentesHelper::getGranularPermissions($restrictionType, $option->value, 'create' );
						if ( ! $canCreate )
						{
							unset($options[$i]);
						}
					}
				}
				else //edit
				{
					foreach ($options as $i => $option)
					{
						list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions($restrictionType, $option->value, 'manage' );
						if ( ! $canManage )
						{
							unset($options[$i]);
						}
					}
				}	

			}
			else if ((string) $this->element['action']) // Verify permissions.  If the action attribute is set, then we scan the options.
			{

				// Get the current user object.
				$user = JFactory::getUser();

				foreach ($options as $i => $option)
				{
					/*
					 * To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					{
						unset($options[$i]);
					}
				}

			}

			if (isset($this->element['show_root']))
			{
				array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
			}
		}
		else
		{
			JLog::add(JText::_('JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY'), JLog::WARNING, 'jerror');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
