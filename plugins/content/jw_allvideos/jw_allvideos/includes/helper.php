<?php
/**
 * @version		4.5.0
 * @package		AllVideos (plugin)
 * @author    JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class AllVideosHelper {

	// Path overrides
	public static function getTemplatePath($pluginName, $file, $tmpl)
	{

		$mainframe = JFactory::getApplication();
		$p = new JObject;
		$pluginGroup = 'content';

		if (file_exists(JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$pluginName.DS.$tmpl.DS.str_replace('/', DS, $file)))
		{
			$p->file = JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$pluginName.DS.$tmpl.DS.$file;
			$p->http = JURI::root(true)."/templates/".$mainframe->getTemplate()."/html/{$pluginName}/{$tmpl}/{$file}";
		}
		else
		{
			if (version_compare(JVERSION, '1.6.0', 'ge'))
			{
				// Joomla! 1.6
				$p->file = JPATH_SITE.DS.'plugins'.DS.$pluginGroup.DS.$pluginName.DS.$pluginName.DS.'tmpl'.DS.$tmpl.DS.$file;
				$p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/{$pluginName}/tmpl/{$tmpl}/{$file}";
			}
			else
			{
				// Joomla! 1.5
				$p->file = JPATH_SITE.DS.'plugins'.DS.$pluginGroup.DS.$pluginName.DS.'tmpl'.DS.$tmpl.DS.$file;
				$p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/tmpl/{$tmpl}/{$file}";
			}
		}
		return $p;
	}

} // end class
