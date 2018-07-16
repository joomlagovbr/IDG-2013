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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JHtml;
use JObject;
use JText;

/**
 * Class EditorButtonHelper
 * @package RegularLabs\Library
 */
class EditorButtonHelper
{
	var $_name  = '';
	var $params = null;

	public function __construct($name, &$params)
	{
		$this->_name  = $name;
		$this->params = $params;

		Language::load('plg_editors-xtd_' . $name);

		JHtml::_('jquery.framework');

		Document::script('regularlabs/script.min.js');
		Document::style('regularlabs/style.min.css');
	}

	public function getButtonText()
	{
		$text_ini = strtoupper(str_replace(' ', '_', $this->params->button_text));
		$text     = JText::_($text_ini);

		if ($text == $text_ini)
		{
			$text = JText::_($this->params->button_text);
		}

		return trim($text);
	}

	public function getIcon($icon = '')
	{
		$icon = $icon ?: $this->_name;

		return 'reglab icon-' . $icon;
	}

	public function renderPopupButton($editor_name, $width = 0, $height = 0)
	{
		$button = new JObject;

		$button->modal   = true;
		$button->class   = 'btn';
		$button->link    = $this->getPopupLink($editor_name);
		$button->text    = $this->getButtonText();
		$button->name    = $this->getIcon();
		$button->options = $this->getPopupOptions($width, $height);

		return $button;
	}

	public function getPopupLink($editor_name)
	{
		return 'index.php?rl_qp=1'
			. '&folder=plugins.editors-xtd.' . $this->_name
			. '&file=popup.php'
			. '&name=' . $editor_name;
	}

	public function getPopupOptions($width = 0, $height = 0)
	{
		$width  = $width ?: 1600;
		$height = $height ?: 1200;

		$width  = 'Math.min(window.getSize().x-100, ' . $width . ')';
		$height = 'Math.min(window.getSize().y-100, ' . $height . ')';

		return '{'
			. 'handler: \'iframe\','
			. 'size: {'
			. 'x:' . $width . ','
			. 'y:' . $height
			. '}'
			. '}';
	}
}
