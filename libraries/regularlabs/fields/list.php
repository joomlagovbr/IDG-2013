<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

if ( ! class_exists('JFormFieldList'))
{
	require_once JPATH_LIBRARIES . '/joomla/form/fields/list.php';
}

class JFormFieldRL_List extends JFormFieldList
{
	protected $type = 'List';

	protected function getInput()
	{
		$html = [];
		$attr = '';

		// Initialize some field attributes.
		$attr .= ! empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= $this->size ? ' style="width:' . $this->size . 'px"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1' || (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true')
		{
			// Create a read-only list (no name) with a hidden input to store the value.
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '">';
		}
		else
		{
			// Create a regular list.
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		return implode($html);
	}
}
