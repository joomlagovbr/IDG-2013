<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use RegularLabs\Library\Extension as RL_Extension;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_OnlyPro extends \RegularLabs\Library\Field
{
	public $type = 'OnlyPro';

	protected function getLabel()
	{
		$label   = $this->prepareText($this->get('label'));
		$tooltip = $this->prepareText($this->get('description'));

		if ( ! $label && ! $tooltip)
		{
			return '</div><div>' . $this->getText();
		}

		if ( ! $label)
		{
			return $tooltip;
		}

		if ( ! $tooltip)
		{
			return $label;
		}

		return '<label class="hasPopover" title="' . $label . '" data-content="' . htmlentities($tooltip) . '">'
			. $label
			. '</label>';
	}

	protected function getInput()
	{
		$label   = $this->prepareText($this->get('label'));
		$tooltip = $this->prepareText($this->get('description'));

		if ( ! $label && ! $tooltip)
		{
			return '';
		}

		return $this->getText();
	}

	protected function getText()
	{
		$text = JText::_('RL_ONLY_AVAILABLE_IN_PRO');
		$text = '<em>' . $text . '</em>';

		$extension = $this->getExtensionName();

		$alias = RL_Extension::getAliasByName($extension);

		if ($alias)
		{
			$text = '<a href="https://www.regularlabs.com/extensions/' . $extension . '/features" target="_blank">'
				. $text
				. '</a>';
		}

		$class = $this->get('class');
		$class = $class ? ' class="' . $class . '"' : '';

		return '<div' . $class . '>' . $text . '</div>';
	}

	protected function getExtensionName()
	{
		if ($extension = $this->form->getValue('element'))
		{
			return $extension;
		}

		if ($extension = JFactory::getApplication()->input->get('component'))
		{
			return str_replace('com_', '', $extension);
		}

		if ($extension = JFactory::getApplication()->input->get('folder'))
		{
			$extension = explode('.', $extension);

			return array_pop($extension);
		}

		return false;
	}
}
