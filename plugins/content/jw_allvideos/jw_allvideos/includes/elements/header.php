<?php
/**
 * @version		4.7.0
 * @package		AllVideos (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/base.php');

class JWElementHeader extends JWElement
{
	public function fetchElement($name, $value, &$node, $control_name)
	{

		$document = JFactory::getDocument();
		$document->addStyleDeclaration('
			.jwHeaderClr {clear:both;height:0;line-height:0;border:none;float:none;background:none;padding:0;margin:0;}
			.jwHeaderContainer {clear:both;font-weight:bold;font-size:12px;color:#369;margin:12px 0 4px;padding:0;background:#d5e7fa;border-bottom:2px solid #96b0cb;width:auto;}
			.jwHeaderContainer15 {clear:both;font-weight:bold;font-size:12px;color:#369;margin:0;padding:0;background:#d5e7fa;border-bottom:2px solid #96b0cb;float:left;width:100%;}
			.jwHeaderContent {padding:6px 8px;}
			@media all and (min-width:771px) {
				.form-horizontal .span9 .control-label {width:30% !important;}
				.form-horizontal .span9 .controls {margin-left:32% !important;}
			}
			@media all and (min-width:481px) and (max-width:770px){
				.form-horizontal .span9 .control-label {width:45% !important;}
				.form-horizontal .span9 .controls {margin-left:47% !important;}
			}
		');
		if (version_compare(JVERSION, '2.5.0', 'ge'))
		{
			return '<div class="jwHeaderContainer"><div class="jwHeaderContent">'.JText::_($value).'</div><div class="jwHeaderClr"></div></div>';
		}
		else
		{
			return '<div class="jwHeaderContainer15"><div class="jwHeaderContent">'.JText::_($value).'</div><div class="jwHeaderClr"></div></div>';
		}

	}

	public function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		return NULL;
	}

}

class JFormFieldHeader extends JWElementHeader
{
	var $type = 'header';
}

class JElementHeader extends JWElementHeader
{
	var $_name = 'header';
}
