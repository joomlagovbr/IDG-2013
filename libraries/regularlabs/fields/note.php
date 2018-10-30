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

class JFormFieldRL_Note extends \RegularLabs\Library\Field
{
	public $type = 'Note';

	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$this->element = $element;

		$element['label']                = $this->prepareText($element['label']);
		$element['description']          = $this->prepareText($element['description']);
		$element['translateDescription'] = false;

		return parent::setup($element, $value, $group);
	}

	protected function getLabel()
	{
		if (empty($this->element['label']) && empty($this->element['description']))
		{
			return '';
		}

		$title       = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
		$heading     = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
		$description = (string) $this->element['description'];
		$class       = ! empty($this->class) ? ' class="' . $this->class . '"' : '';
		$close       = (string) $this->element['close'];

		$html = [];

		if ($close)
		{
			$close  = $close == 'true' ? 'alert' : $close;
			$html[] = '<button type="button" class="close" data-dismiss="' . $close . '">&times;</button>';
		}

		$html[] = ! empty($title) ? '<' . $heading . '>' . JText::_($title) . '</' . $heading . '>' : '';
		$html[] = ! empty($description) ? JText::_($description) : '';

		return '</div><div ' . $class . '>' . implode('', $html);
	}

	protected function getInput()
	{
		return '';
	}
}
