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
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPhocaGalleryOrdering extends JFormField
{

	protected $type = 'PhocaGalleryOrdering';

	protected function getInput() {
		// Initialize variables.
		$html = array();
		$attr = '';

		// Get some field values from the form.
		$id			= (int) $this->form->getValue('id');
		
		$title = 'title';
		if ($this->element['table']) {
			switch (strtolower($this->element['table'])) {
			
				case "category":
					$whereLabel	=	'parent_id';
					$whereValue	=	(int) $this->form->getValue('parent_id');
					$table		=	'#__phocagallery_categories';
				break;
				
				case "commentimage":
					$whereLabel	=	'imgid';
					$whereValue	=	(int) $this->form->getValue('imgid');
					$table		=	'#__phocagallery_img_comments';
				break;
				
				case "comment":
					$whereLabel	=	'catid';
					$whereValue	=	(int) $this->form->getValue('catid');
					$table		=	'#__phocagallery_comments';
				break;
				
				case "fbuser":
					$whereLabel	=	'';
					$whereValue	=	'';
					$table		=	'#__phocagallery_fb_users';
					$title		=	'name';
				break;
				
				case "tag":
					$whereLabel	=	'';
					$whereValue	=	'';
					$table		=	'#__phocagallery_tags';
				break;
				
				case "styles":
					$whereLabel	=	'type';
					$whereValue	=	(int) $this->form->getValue('type');
					$table		=	'#__phocagallery_styles';
				break;
				
				case "image":
				default:
					$whereLabel	=	'catid';
					$whereValue	=	(int) $this->form->getValue('catid');
					$table		=	'#__phocagallery';
				break;
				
			}
		} else {
			$whereLabel	=	'catid';
			$whereValue	=	(int) $this->form->getValue('catid');
			$table		=	'#__phocagallery';
		}
		
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		

		// Build the query for the ordering list.
		$query = 'SELECT ordering AS value, '.$title.' AS text' .
				 ' FROM ' . $table ;
		if ($whereLabel != '') {		
			$query .=	' WHERE '.$whereLabel.' = ' . (int) $whereValue ;
		}
		$query .=' ORDER BY ordering';

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $id ? 0 : 1);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $id ? 0 : 1);
		}

		return implode($html);
	}
}