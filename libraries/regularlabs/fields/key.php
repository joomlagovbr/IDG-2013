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

class JFormFieldRL_Key extends \RegularLabs\Library\Field
{
	public $type = 'Key';

	protected function getInput()
	{
		$this->params = $this->element->attributes();
		$action       = $this->get('action', 'Joomla.submitbutton(\'config.save.component.apply\')');

		$key = trim($this->value);

		if ( ! $key)
		{
			return '<div id="' . $this->id . '_field" class="btn-wrapper input-append clearfix">'
				. '<input type="text" class="rl_codefield" name="' . $this->name . '" id="' . $this->id . '" autocomplete="off" value="">'
				. '<button href="#" class="btn btn-success" title="' . JText::_('JAPPLY') . '" onclick="' . $action . '">'
				. '<span class="icon-checkmark"></span>'
				. '</button>'
				. '</div>';
		}

		$cloak_length = max(0, strlen($key) - 4);
		$key          = str_repeat('*', $cloak_length) . substr($this->value, $cloak_length);

		$show = 'jQuery(\'#' . $this->id . '\').attr(\'name\', \'' . $this->name . '\');'
			. 'jQuery(\'#' . $this->id . '_hidden\').attr(\'name\', \'\');'
			. 'jQuery(\'#' . $this->id . '_button\').hide();'
			. 'jQuery(\'#' . $this->id . '_field\').show();';

		$hide = 'jQuery(\'#' . $this->id . '\').attr(\'name\', \'\');'
			. 'jQuery(\'#' . $this->id . '_hidden\').attr(\'name\', \'' . $this->name . '\');'
			. 'jQuery(\'#' . $this->id . '_field\').hide();'
			. 'jQuery(\'#' . $this->id . '_button\').show();';

		return
			'<div class="rl_keycode pull-left">' . $key . '</div>'

			. '<div id="' . $this->id . '_button" class="pull-left">'
			. '<button class="btn btn-default btn-small" onclick="' . $show . ';return false;">'
			. '<span class="icon-edit"></span> '
			. JText::_('JACTION_EDIT')
			. '</button>'
			. '</div>'

			. '<div class="clearfix"></div>'

			. '<div id="' . $this->id . '_field" class="btn-wrapper input-append clearfix" style="display:none;">'
			. '<input type="text" class="rl_codefield" name="" id="' . $this->id . '" autocomplete="off" value="">'
			. '<button href="#" class="btn btn-success btn" title="' . JText::_('JAPPLY') . '" onclick="' . $action . '">'
			. '<span class="icon-checkmark"></span>'
			. '</button>'
			. '<button href="#" class="btn btn-danger btn" title="' . JText::_('JCANCEL') . '" onclick="' . $hide . ';return false;">'
			. '<span class="icon-cancel-2"></span>'
			. '</button>'
			. '</div>'

			. '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '_hidden" value="' . $this->value . '">';
	}
}

