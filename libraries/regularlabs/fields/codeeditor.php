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

class JFormFieldRL_CodeEditor extends \RegularLabs\Library\Field
{
	public $type = 'CodeEditor';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$width  = $this->get('width', '100%');
		$height = $this->get('height', 400);

		$this->value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		$editor_plugin = JPluginHelper::getPlugin('editors', 'codemirror');

		if (empty($editor_plugin))
		{
			return
				'<textarea name="' . $this->name . '" style="'
				. 'width:' . (strpos($width, '%') ? $width : $width . 'px') . ';'
				. 'height:' . (strpos($height, '%') ? $height : $height . 'px') . ';'
				. '" id="' . $this->id . '">' . $this->value . '</textarea>';
		}

		RL_Document::script('regularlabs/script.min.js');
		RL_Document::stylesheet('regularlabs/style.min.css');

		$script = "
			jQuery(document).ready(function($) {
				RegularLabsScripts.initResizeCodeMirror('rl_codemirror_" . $this->id . "');
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		return '<div class="rl_codemirror" id="rl_codemirror_' . $this->id . '">'
			. JEditor::getInstance('codemirror')->display(
				$this->name, $this->value,
				$width, $height,
				80, 10,
				false,
				$this->id, null, null,
				['markerGutter' => false, 'activeLine' => true, 'class' => 'xxx']
			)
			. '</div>';
	}
}
