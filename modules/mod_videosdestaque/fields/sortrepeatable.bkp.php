<?php
/**
* @package PortalPadrao
* @subpackage sortrepeatable
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('repeatable');
 
class JFormFieldSortrepeatable extends JFormFieldRepeatable
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $type = 'Sortrepeatable';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// Initialize variables.
		$subForm = new JForm($this->name, array('control' => 'jform'));
		$xml = $this->element->children()->asXML();
		$subForm->load($xml);
		
		// Needed for repeating modals in gmaps
		$subForm->repeatCounter = (int) @$this->form->repeatCounter;
		$children = $this->element->children();

		$subForm->setFields($children);

		$modalid = $this->id . '_modal';
		JHtml::_('sortablelist.sortable', $modalid, 'adminForm', 'asc', NULL);

		$str = array();
		$str[] = '<div id="' . $modalid . '"  class="modal hide fade in" style="width:700px !important">';
		$str[] = '<div class="modal-body modal-batch">';
		$str[] = '<table id="' . $modalid . '_table" class="adminlist ' . $this->element['class'] . ' table table-striped">';
		$str[] = '<thead><tr>';
		$str[] = '<th>Ordem</th>';
		$names = array();
		$attributes = $this->element->attributes();

		foreach ($subForm->getFieldset($attributes->name . '_modal') as $field)
		{
			$names[] = (string) $field->element->attributes()->name;
			$str[] = '<th>' . strip_tags($field->getLabel($field->name));
			$str[] = '<br /><small style="font-weight:normal">' . JText::_($field->description) . '</small>';
			$str[] = '</th>';
		}

		$str[] = '<th><a href="#" class="add btn button btn-success"><span class="icon-plus"></span> </a>';
		$str[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn button btn-close closebutton"><span class="icon-cancel"></span> </a></th>';
		$str[] = '</tr></thead>';
		$str[] = '<tbody><tr class="dndlist-sortable">';
		$str[] = '<td class="order nowrap center hidden-phone" style="width: 32px;">';
		$str[] = '<span class="sortable-handler" style="cursor: move;"><i class="icon-menu"></i></span>';
		$str[] = '<input type="text" class="width-20 text-area-order" value="0" size="5" name="order[]" style="display:none"></td>';

		foreach ($subForm->getFieldset($attributes->name . '_modal') as $field)
		{
			$str[] = '<td>' . $field->getInput() . '</td>';
		}

		$str[] = '<td>';
		$str[] = '<div class="btn-group"><a class="add btn button btn-success"><span class="icon-plus"></span> </a>';
		$str[] = '<a class="remove btn button btn-danger"><span class="icon-minus"></span> </a></div>';
		$str[] = '</td>';
		$str[] = '</tr></tbody>';
		$str[] = '</table>';
		$str[] = '</div>';
		$str[] = '</div>';

		$names = json_encode($names);

		JHtml::_('script', 'system/repeatable.js', true, true);

		// If a maximum value isn't set then we'll make the maximum amount of cells a large number
		$maximum = $this->element['maximum'] ? (int) $this->element['maximum'] : '999';

		$script = "(function ($){
			$(document).ready(function (){
				var repeatable = new $.JRepeatable('$modalid', $names, '$this->id', '$maximum');
				$('.closebutton').click(function(){
					// window.alert('teste');
					$('$modalid').modal('hide');
					// return false;
				});
			});
		})(jQuery);";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);

		$select = (string) $this->element['select'] ? JText::_((string) $this->element['select']) : JText::_('JLIB_FORM_BUTTON_SELECT');
		$icon = $this->element['icon'] ? '<i class="icon-' . $this->element['icon'] . '"></i> ' : '';
		$str[] = '<button class="btn" id="' . $modalid . '_button" data-target="#' . $modalid . '" data-toggle="modal">' . $icon . $select . '</button>';

		if (is_array($this->value))
		{
			$this->value = array_shift($this->value);
		}

		$value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$str[] = '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . $value . '" />';

		JText::script('JAPPLY');
		JText::script('JCANCEL');
		return implode("\n", $str);
	}
}