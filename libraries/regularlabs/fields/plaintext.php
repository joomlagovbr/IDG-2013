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

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_PlainText extends \RegularLabs\Library\Field
{
	public $type = 'PlainText';

	protected function getLabel()
	{
		$label   = $this->prepareText($this->get('label'));
		$tooltip = $this->prepareText($this->get('description'));

		if ( ! $label && ! $tooltip)
		{
			return '';
		}

		if ( ! $label)
		{
			return '<div>' . $tooltip . '</div>';
		}

		if ( ! $tooltip)
		{
			return '<div>' . $label . '</div>';
		}

		return '<label class="hasPopover" title="' . $label . '" data-content="' . htmlentities($tooltip) . '">'
			. $label . '</label>';
	}

	protected function getInput()
	{
		$text = $this->prepareText($this->value);

		if ( ! $text)
		{
			return '';
		}

		return '<fieldset class="rl_plaintext">' . $text . '</fieldset>';
	}
}
