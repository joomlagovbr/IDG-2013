<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderAdminView
{
	public function __construct(){}
	
	public function startForm($option, $view, $itemId, $id = 'adminForm', $name = 'adminForm') {
		return '<div id="'.$view.'"><form action="'.JRoute::_('index.php?option='.$option . '&layout=edit&id='.(int) $itemId).'" method="post" name="'.$name.'" id="'.$id.'" class="form-validate">'."\n"
		.'<div class="row-fluid">'."\n";
	}
	
	public function endForm() {
		return '</div>'."\n".'</form>'."\n".'</div>'."\n";
	}
	
	public function formInputs() {
	
		return '<input type="hidden" name="task" value="" />'. "\n"
		. JHtml::_('form.token'). "\n";
	}
	
	public function navigation($tabs) {
		$o = '<ul class="nav nav-tabs">';
		$i = 0;
		foreach($tabs as $k => $v) {
			$cA = 0;
			if ($i == 0) {
				$cA = 'class="active"';
			}
			$o .= '<li '.$cA.'><a href="#'.$k.'" data-toggle="tab">'. $v.'</a></li>'."\n";
			$i++;
		}
		$o .= '</ul>';
		return $o;
	}
	
	public function group($form, $formArray, $clear = 0) {
		$o = '';
		if (!empty($formArray)) {
			if ($clear == 1) {
				foreach ($formArray as $value) {
					$o .= '<div>'. $form->getLabel($value) . '</div>'."\n"
					. '<div class="clearfix"></div>'. "\n"
					. '<div>' . $form->getInput($value). '</div>'."\n";
				} 
			} else {
				foreach ($formArray as $value) {
					$o .= '<div class="control-group">'."\n"
					. '<div class="control-label">'. $form->getLabel($value) . '</div>'."\n"
					. '<div class="controls">' . $form->getInput($value). '</div>'."\n"
					. '</div>' . "\n";
				}
			}
		}
		return $o;
	}
	
	public function item($form, $item, $suffix = '') {
		$value = $o = '';
		if ($suffix != '') {
			$value = $suffix;
		} else {
			$value = $form->getInput($item);
		}
		$o .= '<div class="control-group">'."\n";
		$o .= '<div class="control-label">'. $form->getLabel($item) . '</div>'."\n"
		. '<div class="controls">' . $value.'</div>'."\n"
		. '</div>' . "\n";
		return $o;
	}
}
?>