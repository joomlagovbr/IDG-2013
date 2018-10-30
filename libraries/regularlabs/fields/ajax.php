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

class JFormFieldRL_Ajax extends \RegularLabs\Library\Field
{
	public $type = 'Ajax';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		JHtml::_('jquery.framework');

		RL_Document::script('regularlabs/script.min.js');

		$loading = "jQuery(\"#" . $this->id . "\").find(\"span\").attr(\"class\", \"icon-refresh icon-spin\");";
		$success = "jQuery(\"#" . $this->id . "\").find(\"span\").attr(\"class\", \"icon-ok\");"
			. "if(data){jQuery(\"#message_" . $this->id . "\").addClass(\"alert alert-success alert-inline\").html(data);}";
		$error   = "jQuery(\"#" . $this->id . "\").find(\"span\").attr(\"class\", \"icon-warning\");"
			. "if(data){jQuery(\"#message_" . $this->id . "\").addClass(\"alert alert-danger alert-inline\").html(data);}";

		$script = "function loadAjax" . $this->id . "() {"
			. $loading
			. "jQuery(\"#message_" . $this->id . "\").attr(\"class\", \"\").html(\"\");"
			. "RegularLabsScripts.loadajax("
			. "'" . addslashes($this->get('url')) . "',"
			. "'var data = data.trim();"
			. "if(data == \"\" || data.substring(0,1) == \"+\") {"
			. "data = data.replace(/^\\\\+/, \\'\\');"
			. $success
			. "} else {"
			. $error
			. "}',"
			. "'" . $error . "'"
			. ");"
			. "}";
		JFactory::getDocument()->addScriptDeclaration($script);

		return
			'<button id="' . $this->id . '" class="' . $this->get('class', 'btn') . '" title="' . JText::_($this->get('description')) . '" onclick="loadAjax' . $this->id . '();return false;">'
			. '<span class="' . $this->get('icon', '') . '"></span> '
			. JText::_($this->get('text', $this->get('label')))
			. '</button>'
			. '<div id="message_' . $this->id . '"></div>';
	}
}
