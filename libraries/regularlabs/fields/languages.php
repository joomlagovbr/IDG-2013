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

use Joomla\Registry\Registry;

class JFormFieldRL_Languages extends \RegularLabs\Library\Field
{
	public $type = 'Languages';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$size     = (int) $this->get('size');
		$multiple = $this->get('multiple');

		return $this->selectListSimpleAjax(
			$this->type, $this->name, $this->value, $this->id,
			compact('size', 'multiple')
		);
	}

	function getAjaxRaw(Registry $attributes)
	{
		$name     = $attributes->get('name', $this->type);
		$id       = $attributes->get('id', strtolower($name));
		$value    = $attributes->get('value', []);
		$size     = $attributes->get('size');
		$multiple = $attributes->get('multiple');

		$options = $this->getLanguages($value);

		return $this->selectListSimple($options, $name, $value, $id, $size, $multiple);
	}

	function getLanguages($value)
	{
		$langs = JHtml::_('contentlanguage.existing');

		if ( ! is_array($value))
		{
			$value = [$value];
		}

		$options = [];

		foreach ($langs as $lang)
		{
			if (empty($lang->value))
			{
				continue;
			}

			$options[] = (object) [
				'value'    => $lang->value,
				'text'     => $lang->text . ' [' . $lang->value . ']',
				'selected' => in_array($lang->value, $value),
			];
		}

		return $options;
	}
}
