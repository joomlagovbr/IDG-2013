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

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\RegEx as RL_RegEx;

class JFormFieldRL_Color extends JFormField
{
	public $type = 'Color';

	protected function getInput()
	{
		if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
		{
			return null;
		}

		$field = new RLFieldColor;

		return $field->getInput($this->name, $this->id, $this->value, $this->element->attributes());
	}
}

class RLFieldColor
{
	function getInput($name, $id, $value, $params)
	{
		$this->name   = $name;
		$this->id     = $id;
		$this->value  = $value;
		$this->params = $params;

		$class    = trim('rl_color minicolors ' . $this->get('class'));
		$disabled = $this->get('disabled') ? ' disabled="disabled"' : '';

		RL_Document::script('regularlabs/color.min.js');
		RL_Document::stylesheet('regularlabs/color.min.css');

		$this->value = strtolower(RL_RegEx::replace('[^a-z0-9]', '', $this->value));

		return '<input type="text" name="' . $this->name . '" id="' . $this->id . '" class="' . $class . '" value="' . $this->value . '"' . $disabled . '>';
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
