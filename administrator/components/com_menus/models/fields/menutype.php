<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  1.6
 */
class JFormFieldMenutype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'menutype';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$html     = array();
		$recordId = (int) $this->form->getValue('id');
		$size     = ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		$class    = ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="text_area"';
		$required = ($v = $this->element['required']) ? ' required="required"' : '';

		// Get a reverse lookup of the base link URL to Title
		$model = JModelLegacy::getInstance('menutypes', 'menusModel');
		$rlu   = $model->getReverseLookup();

		switch ($this->value)
		{
			case 'url':
				$value = JText::_('COM_MENUS_TYPE_EXTERNAL_URL');
				break;

			case 'alias':
				$value = JText::_('COM_MENUS_TYPE_ALIAS');
				break;

			case 'separator':
				$value = JText::_('COM_MENUS_TYPE_SEPARATOR');
				break;

			case 'heading':
				$value = JText::_('COM_MENUS_TYPE_HEADING');
				break;

			default:
				$link = $this->form->getValue('link');

				// Clean the link back to the option, view and layout
				$value = JText::_(JArrayHelper::getValue($rlu, MenusHelper::getLinkKey($link)));
				break;
		}
		// Include jQuery
		JHtml::_('jquery.framework');

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration('
			function jSelectPosition_' . $this->id . '(name) {
				document.getElementById("' . $this->id . '").value = name;
			}
		');

		$link = JRoute::_('index.php?option=com_menus&view=menutypes&tmpl=component&recordId=' . $recordId);
		$html[] = '<span class="input-append"><input type="text" ' . $required . ' readonly="readonly" id="' . $this->id
			. '" value="' . $value . '"' . $size . $class . ' />';
		$html[] = '<a href="#menuTypeModal" role="button" class="btn btn-primary" data-toggle="modal" title="' . JText::_('JSELECT') . '">'
			. '<span class="icon-list icon-white"></span> '
			. JText::_('JSELECT') . '</a></span>';
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'menuTypeModal',
			array(
				'url'        => $link,
				'title'      => JText::_('COM_MENUS_ITEM_FIELD_TYPE_LABEL'),
				'width'      => '800px',
				'height'     => '300px',
				'modalWidth' => '80',
				'bodyHeight' => '70',
				'footer'     => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
						. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>'
			)
		);
		$html[] = '<input class="input-small" type="hidden" name="' . $this->name . '" value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" />';

		return implode("\n", $html);
	}
}
