<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldPhocaInfoText extends JFormField
{

	protected $type = 'PhocaInfoText';


	protected function getInput()
	{
		$class = 'inputbox';
		if ((string) $this->element['class'] != '') {
			$class = $this->element['class'];
		}
	
		return  '<div class="'.$class.'" style="padding-top:5px">'.$this->value.'</div>';
	}


	protected function getLabel()
	{
		echo '<div class="clearfix"></div>';
		
			return parent::getLabel();
		
		echo '<div class="clearfix"></div>';
	}

}