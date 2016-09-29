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

class JWElementTemplate extends JWElement
{

	public function fetchElement($name, $value, &$node, $control_name)
	{
		jimport('joomla.filesystem.folder');
		$plgTemplatesPath = version_compare(JVERSION, '2.5.0', 'ge') ? JPATH_SITE.'/plugins/content/jw_allvideos/jw_allvideos/tmpl' : JPATH_SITE.'/plugins/content/jw_allvideos/tmpl';
		$plgTemplatesFolders = JFolder::folders($plgTemplatesPath);
		$db = JFactory::getDBO();
		if (version_compare(JVERSION, '2.5.0', 'ge'))
		{
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		}
		else
		{
			$query = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0";
		}
		$db->setQuery($query);
		$template = $db->loadResult();
		$templatePath = JPATH_SITE.'/templates/'.$template.'/html/jw_allvideos';
		if (JFolder::exists($templatePath))
		{
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $plgTemplatesFolders);
			$folders = @array_unique($folders);
		}
		else
		{
			$folders = $plgTemplatesFolders;
		}
		sort($folders);
		$options = array();
		foreach ($folders as $folder)
		{
			$options[] = JHTML::_('select.option', $folder, $folder);
		}
		$fieldName = version_compare(JVERSION, '2.5.0', 'ge') ? $name : $control_name.'['.$name.']';
		return JHTML::_('select.genericlist', $options, $fieldName, '', 'value', 'text', $value);
	}

}

class JFormFieldTemplate extends JWElementTemplate
{
	var $type = 'template';
}

class JElementTemplate extends JWElementTemplate
{
	var $_name = 'template';
}
