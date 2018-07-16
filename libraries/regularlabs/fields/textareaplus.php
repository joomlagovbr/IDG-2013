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

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\StringHelper as RL_String;

class JFormFieldRL_TextAreaPlus extends \RegularLabs\Library\Field
{
	public $type = 'TextAreaPlus';

	protected function getLabel()
	{
		$this->params = $this->element->attributes();

		$resize                = $this->get('resize', 0);
		$show_insert_date_name = $this->get('show_insert_date_name', 0);

		$label = RL_String::html_entity_decoder(JText::_($this->get('label')));

		$attribs = 'id="' . $this->id . '-lbl" for="' . $this->id . '"';

		if ($this->description)
		{
			$attribs .= ' class="hasPopover" title="' . $label . '"'
				. ' data-content="' . JText::_($this->description) . '"';
		}

		$html = '<label ' . $attribs . '>' . $label;

		if ($show_insert_date_name)
		{
			JHtml::_('jquery.framework');

			RL_Document::script('regularlabs/script.min.js');

			$date_name = JDate::getInstance()->format('[Y-m-d]') . ' ' . JFactory::getUser()->name . ' : ';
			$onclick   = "RegularLabsScripts.prependTextarea('" . $this->id . "', '" . addslashes($date_name) . "', '---');";

			$html .= '<br><span role="button" class="btn btn-mini rl_insert_date" onclick="' . $onclick . '">'
				. JText::_('RL_INSERT_DATE_NAME')
				. '</span>';
		}

		if ($resize)
		{
			JHtml::_('jquery.framework');

			RL_Document::script('regularlabs/script.min.js');
			RL_Document::stylesheet('regularlabs/style.min.css');

			$html .= '<br><span role="button" class="rl_resize_textarea rl_maximize"'
				. ' data-id="' . $this->id . '"  data-min="' . $this->get('height', 80) . '" data-max="' . $resize . '">'
				. '<span class="rl_resize_textarea_maximize">'
				. '[ + ]'
				. '</span>'
				. '<span class="rl_resize_textarea_minimize">'
				. '[ - ]'
				. '</span>'
				. '</span>';
		}

		$html .= '</label>';

		return $html;
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$width  = $this->get('width', 600);
		$height = $this->get('height', 80);
		$class  = ' class="' . trim('rl_textarea ' . $this->get('class')) . '"';
		$type   = $this->get('texttype');
		$hint   = $this->get('hint');

		if (is_array($this->value))
		{
			$this->value = trim(implode("\n", $this->value));
		}

		if ($type == 'html')
		{
			// Convert <br> tags so they are not visible when editing
			$this->value = str_replace('<br>', "\n", $this->value);
		}
		else if ($type == 'regex')
		{
			// Protects the special characters
			$this->value = str_replace('[:REGEX_ENTER:]', '\n', $this->value);
		}

		if ($this->get('translate') && $this->get('translate') !== 'false')
		{
			$this->value = JText::_($this->value);
			$hint        = JText::_($hint);
		}

		$this->value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		$hint = $hint ? ' placeholder="' . $hint . '"' : '';

		return
			'<textarea name="' . $this->name . '" cols="' . (round($width / 7.5)) . '" rows="' . (round($height / 15)) . '"'
			. ' style="width:' . (($width == '600') ? '100%' : $width . 'px') . ';height:' . $height . 'px"'
			. ' id="' . $this->id . '"' . $class . $hint . '>' . $this->value . '</textarea>';
	}
}
