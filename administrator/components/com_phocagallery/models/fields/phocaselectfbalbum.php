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

class JFormFieldPhocaSelectFbAlbum extends JFormField
{
	public $type = 'phocaSelectFbAlbum';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		
		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		
		
		
			
		$uid	= $this->form->getValue('extfbuid');
		$suffix	= '';
		if ($uid != '') { $suffix .= '&amp;uid='.$uid;}
		
		$link = 'index.php?option=com_phocagallery&amp;view=phocagalleryfba&amp;tmpl=component&amp;field='.$this->id. $suffix;
	
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal_'.$this->id);
		
		
		
		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];

		
	
		// Build the script.
		$script = array();
		$script[] = '	function phocaSelectFbAlbum_'.$this->id.'(title) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = title;';
		$script[] = '		'.$onchange;
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
/*
		$html[] = '<div class="fltlft">';
		$html[] = '	<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$attr.' />';
		$html[] = '</div>';
		
		
		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '		<a id="pglinktoalbum" class="modal_'.$this->id.'" title="'.JText::_('COM_PHOCAGALLERY_FB_SELECT_ALBUM').'"' .
							' href="'.($this->element['readonly'] ? '' : $link).'"' .
							' rel="{handler: \'iframe\', size: {x: 650, y: 375}}">';
		$html[] = '			'.JText::_('COM_PHOCAGALLERY_FB_SELECT_ALBUM').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';*/
		
		$html[] = '<div class="input-append">';
		$html[] = '<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .' '.$attr.' />';
		$html[] = '<a id="pglinktoalbum" class="modal_'.$this->id.' btn" title="'.JText::_('COM_PHOCAGALLERY_FB_SELECT_ALBUM').'"'
				.' href="'.($this->element['readonly'] ? '' : $link).'"'
				.' rel="{handler: \'iframe\', size: {x: 650, y: 400}}">'
				. JText::_('COM_PHOCAGALLERY_FB_SELECT_ALBUM').'</a>';
		$html[] = '</div>'. "\n";
		


		return implode("\n", $html);
	}
}
?>