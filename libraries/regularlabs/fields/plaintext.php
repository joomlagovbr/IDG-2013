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

class JFormFieldRL_PlainText extends \RegularLabs\Library\Field
{
	public $type = 'PlainText';

	protected function getLabel()
	{
		RL_Document::stylesheet('regularlabs/style.min.css');

		$this->params = $this->element->attributes();

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
		$this->params = $this->element->attributes();

		$text = $this->prepareText($this->value);

		if ( ! $text)
		{
			return '';
		}

		return '<fieldset class="rl_plaintext">' . $text . '</fieldset>';
	}
}
