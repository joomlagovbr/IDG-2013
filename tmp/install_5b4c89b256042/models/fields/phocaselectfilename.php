<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldPhocaSelectFilename extends JFormField
{
	public $type = 'PhocaSelectFilename';

	protected function getInput()
	{
		// Initialize variables.
		$html 		= array();
		$link 		= 'index.php?option=com_phocagallery&amp;view=phocagalleryi&amp;tmpl=component&amp;field='.$this->id;
		$onchange 	= (string) $this->element['onchange'];
		$size     = ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		$class    = ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="text_area"';
		$required = ($v = $this->element['required']) ? ' required="required"' : '';
		$idA		= 'phFileNameModal';

		// If external image, we don't need the filename will be required
		$extId		= (int) $this->form->getValue('extid');
		if ($extId > 0) {
			$readonly	= ' readonly="readonly"';
			$attr		= '';
			return '<input type="text" name="'.$this->name.'" id="'.$this->id.'" value="-" '.$attr.$readonly.' />';
		}
		
		
		/*$script 	= array();
		$script[] 	= '	function phocaSelectFileName_'.$this->id.'(title) {';
		$script[] 	= '		document.getElementById("'.$this->id.'").value = title;';
		$script[] 	= '		'.$onchange;
		$script[]	= '		jQuery(\'#'.$idA.'\').modal(\'toggle\');';
		$script[] 	= '	}';
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));*/
		
		JHtml::_('jquery.framework');
		
		JFactory::getDocument()->addScriptDeclaration('
			function phocaSelectFileName_' . $this->id . '(name) {
				document.getElementById("' . $this->id . '").value = name;
				jQuery(\'#'.$idA.'\').modal(\'toggle\');
			}
		');
		
//readonly="readonly"
		$html[] = '<span class="input-append"><input type="text" ' . $required . ' id="' . $this->id . '" name="' . $this->name . '"'
			. ' value="' . $this->value . '"' . $size . $class . ' />';
		$html[] = '<a href="#'.$idA.'" role="button" class="btn btn-primary" data-toggle="modal" title="' . JText::_('COM_PHOCAGALLERY_FORM_SELECT_FILENAME') . '">'
			. '<span class="icon-list icon-white"></span> '
			. JText::_('COM_PHOCAGALLERY_FORM_SELECT_FILENAME') . '</a></span>';
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			$idA,
			array(
				'url'    => $link,
				'title'  => JText::_('COM_PHOCAGALLERY_FORM_SELECT_FILENAME'),
				'width'  => '700px',
				'height' => '400px',
				'modalWidth' => '80',
				'bodyHeight' => '70',
				'footer' => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
					. JText::_('COM_PHOCAGALLERY_CLOSE') . '</button>'
			)
		);
		
		// We don't use hidden field name, we can edit it the filename form field, there are three ways of adding filename:
		// - manually typed
		// - selected by image select box
		// - added per YouTube import
		//
		// The name="' . $this->name . '" is used above in standard input form
		//
		//$html[] = '<input class="input-small" type="hidden" name="' . $this->name . '" value="'
		//	. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" />';
			

		return implode("\n", $html);
	}
}