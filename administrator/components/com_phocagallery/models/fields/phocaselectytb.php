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

class JFormFieldPhocaSelectYtb extends JFormField
{
	public $type = 'PhocaSelectYtb';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		
		
		$suffix	= '';
		$catid	= $this->form->getValue('catid');
		if ((int)$catid > 0) { 
			$suffix .= '&amp;catid='.$catid;
		} else {
			$suffix .= '&amp;catid=0';
		}
		
		$link = 'index.php?option=com_phocagallery&amp;view=phocagalleryytb&amp;tmpl=component&amp;field='.$this->id.$suffix;

		// Initialize some field attributes.		
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$columns	= $this->element['cols'] ? ' cols="'.(int) $this->element['cols'].'"' : '';
		$rows		= $this->element['rows'] ? ' rows="'.(int) $this->element['rows'].'"' : '';
		//$required 	= ($v = $this->element['required']) ? ' required="required"' : '';

		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];

		// Load the modal behavior script.
		//JHtml::_('behavior.modal', 'a.modal_'.$this->id);
		
		JHtml::_('jquery.framework');
		$idA		= 'pgselectytb';

		// Build the script.
		$script = array();
		$script[] = '	function phocaSelectYtb_'.$this->id.'(link, title, desc, filename) {';
		$script[] = '		document.getElementById("'.$this->id.'").value = link;';
		$script[] = '		document.getElementById("jform_title").value = title;';
		$script[] = '		document.getElementById("jform_description").value = desc;';
		$script[] = '		document.getElementById("jform_filename").value = filename;';
		$script[] = '		'.$onchange;
		//$script[] = '		SqueezeBox.close();';
		$script[] = '		jQuery(\'#'.$idA.'\').modal(\'toggle\');';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

/*
		$html[] = '<div class="fltlft">';
		$html[] = '<textarea name="'.$this->name.'" id="'.$this->id.'"' .
				$columns.$rows.$class.$disabled.$onchange.'>' .
				htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
				'</textarea>';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '		<a id="pgselectytb" class="modal_'.$this->id.'" title="'.JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB').'"' .
							' href="'.($this->element['readonly'] ? '' : $link).'"' .
							' rel="{handler: \'iframe\', size: {x: 650, y: 375}}">';
		$html[] = '			'.JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';*/
		
		$html[] = '<div class="input-append">';
		$html[] = '<textarea name="'.$this->name.'" id="'.$this->id.'"' .
				$columns.$rows.$class.$disabled.$onchange.'>' .
				htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
				'</textarea>';
		//$html[] = '<a id="pgselectytb" class="modal_'.$this->id.' btn" title="'.JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB').'"'
		//		.' href="'.($this->element['readonly'] ? '' : $link).'"'
		//		.' rel="{handler: \'iframe\', size: {x: 650, y: 400}}">'
		//		. JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB').'</a>';
				
		//$html[] = '<span class="input-append"><input type="text" ' . $required . ' id="' . $this->id . '" name="' . $this->name . '"'
		//	. ' value="' . $this->value . '"' . $size . $class . ' />';
		$html[] = '<a href="#'.$idA.'" role="button" class="btn " data-toggle="modal" title="' . JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB') . '">'
			. '<span class="icon-list icon-white"></span> '
			. JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB') . '</a></span>';
		
		$html[] = '</div>'. "\n";		
		
		$html[] = JHtml::_(
			'bootstrap.renderModal',
			$idA,
			array(
				'url'    => $link,
				'title'  => JText::_('COM_PHOCAGALLERY_FORM_SELECT_YTB'),
				'width'  => '700px',
				'height' => '400px',
				'modalWidth' => '80',
				'bodyHeight' => '70',
				'footer' => '<button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
					. JText::_('COM_PHOCAGALLERY_CLOSE') . '</button>'
			)
		);		
				
		


		return implode("\n", $html);
	}
}