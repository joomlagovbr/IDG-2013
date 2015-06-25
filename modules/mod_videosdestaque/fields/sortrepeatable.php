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
		JHtml::_('sortablelist.sortable', $modalid.'_table', 'adminForm', 'asc', '');

		$str = array();
		$names = array();
		$attributes = $this->element->attributes();

		$str_cols = array();
		$counter_cols = 1;
		foreach ($subForm->getFieldset($attributes->name . '_modal') as $field)
		{
			$names[] = (string) $field->element->attributes()->name;
			$str_cols[] = '<th>' . strip_tags($field->getLabel($field->name));
			$str_cols[] = '<br /><small style="font-weight:normal">' . JText::_($field->description) . '</small>';
			$str_cols[] = '</th>';
			$counter_cols++;
		}

		$str[] = '<div id="' . $modalid . '" style="display:none">';		
		$str[] = '<table id="' . $modalid . '_table" class="adminlist ' . $this->element['class'] . ' table table-striped">';
		$str[] = '<thead><tr><td colspan="'.($counter_cols+1).'">Edição de itens de vídeo (até 3 colunas)';
		$str[] = '<button class="close closebutton" style="float:right" type="button">×</button></td></tr><tr>';
		$str[] = '<th>Ordem</th>';
		$str = array_merge($str, $str_cols);

		$str[] = '<th><a href="#" class="add btn button btn-success"><span class="icon-plus"></span> </a></th>';
		$str[] = '</tr></thead>';
		$str[] = '<tbody><tr class="dndlist-sortable">';
		$str[] = '<td class="order nowrap center hidden-phone" style="width: 32px;">';
		$str[] = '<span class="sortable-handler" style="cursor: move;"><i class="icon-menu"></i></span>';
		$str[] = '<input type="text" class="width-20 text-area-order" value="0" size="5" name="order[]" style="display:none"></td>';
		// $str[] = '<td><input type="text" class="width-20 text-area-order" value="0" size="5"></td>';

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

		$names = json_encode($names);

		//codigo replicado para o modulo por nao ser compativel com nova versao de repeatable da versao 3.4.1
		JHTML::script('modules/mod_videosdestaque/js/repeatable.js'); //

		// If a maximum value isn't set then we'll make the maximum amount of cells a large number
		$maximum = $this->element['maximum'] ? (int) $this->element['maximum'] : '999';

		$script = "(function ($){
			$(document).ready(function (){
				var repeatable = new $.JRepeatable('$modalid', $names, '$this->id', '$maximum');
				$('.closebutton').click(function(){
					// console.log($('#".$modalid."_table').parent().html());
					// console.log($('#".$modalid."_table').parent().prev().parent().html());
					$('#".$modalid."_table').parent().hide();
					$('#".$modalid."_table').parent().prev().hide();
				});
				$('#".$modalid."_table').on('change', '*[name=\"jform[params][url]\"]', function (e, target)
				{
					completarValores( $(this), true );
				});
				$('#".$modalid."_table').on('click', '*[name=\"jform[params][url]\"]', function (e, target)
				{
					completarValores( $(this), false );
				});
				/*$('#".$modalid."_table').on('click', '#".$modalid."_button', function (e, target)
				{
					$('#".$modalid."_table *[name=\"jform[params][url]\"]').each(function(){
						completarValores( $(this), false );
					});
				});*/
			});
		})(jQuery);";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);
		JHTML::script('modules/mod_videosdestaque/js/backend.js');

		$select = (string) $this->element['select'] ? JText::_((string) $this->element['select']) : JText::_('JLIB_FORM_BUTTON_SELECT');
		$icon = $this->element['icon'] ? '<i class="icon-' . $this->element['icon'] . '"></i> ' : '';
		$str[] = '<button class="btn" id="' . $modalid . '_button" data-modal="' . $modalid . '">' . $icon . $select . '</button>';

		if (is_array($this->value))
		{
			$this->value = array_shift($this->value);
		}

		$value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$str[] = '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . $value . '" />';

		JText::script('JAPPLY');
		// JText::script('JCANCEL');
		return implode("\n", $str);
	}
}