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

class JFormFieldRL_MultiSelect extends \RegularLabs\Library\Field
{
	public $type = 'MultiSelect';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		if ( ! is_array($this->value))
		{
			$this->value = explode(',', $this->value);
		}

		foreach ($this->element->children() as $item)
		{
			$item_value    = (string) $item['value'];
			$item_name     = JText::_(trim((string) $item));
			$item_disabled = (int) $item['disabled'];
			$options[]     = JHtml::_('select.option', $item_value, $item_name, 'value', 'text', $item_disabled);
		}

		$size = (int) $this->get('size');

		return $this->selectList($options, $this->name, $this->value, $this->id, $size, true);
	}
}
