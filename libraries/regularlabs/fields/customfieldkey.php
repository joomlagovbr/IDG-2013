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

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

use RegularLabs\Library\StringHelper as RL_String;

class JFormFieldRL_CustomFieldKey extends \RegularLabs\Library\Field
{
	public $type = 'CustomFieldKey';

	protected function getLabel()
	{
		$this->params = $this->element->attributes();

		$label       = $this->get('label') ? $this->get('label') : '';
		$size        = $this->get('size') ? 'style="width:' . $this->get('size') . 'px"' : '';
		$class       = 'class="' . ($this->get('class') ? $this->get('class') : 'text_area') . '"';
		$this->value = htmlspecialchars(RL_String::html_entity_decoder($this->value), ENT_QUOTES);

		return
			'<label for="' . $this->id . '" style="margin-top: -5px;">'
			. '<input type="text" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value
			. '" placeholder="' . JText::_($label) . '" title="' . JText::_($label) . '" ' . $class . ' ' . $size . '>'
			. '</label>';
	}

	protected function getInput()
	{
		return '<div style="display:none;"><div><div>';
	}
}
