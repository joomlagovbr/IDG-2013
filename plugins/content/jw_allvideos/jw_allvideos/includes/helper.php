<?php
/**
 * @version    4.8.0
 * @package    AllVideos (plugin)
 * @author     JoomlaWorks - http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2017 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class AllVideosHelper
{
    // Path overrides
    public static function getTemplatePath($pluginName, $file, $tmpl)
    {
        $app = JFactory::getApplication();
        $p = new JObject;
        $pluginGroup = 'content';

        if (file_exists(JPATH_SITE.'/'.'templates'.'/'.$app->getTemplate().'/html/'.$pluginName.'/'.$tmpl.'/'.$file)) {
            $p->file = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/'.$pluginName.'/'.$tmpl.'/'.$file;
            $p->http = JURI::root(true)."/templates/".$app->getTemplate()."/html/{$pluginName}/{$tmpl}/{$file}";
        } else {
            if (version_compare(JVERSION, '2.5.0', 'ge')) {
                // Joomla 2.5 or newer
                $p->file = JPATH_SITE.'/plugins/'.$pluginGroup.'/'.$pluginName.'/'.$pluginName.'/tmpl/'.$tmpl.'/'.$file;
                $p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/{$pluginName}/tmpl/{$tmpl}/{$file}";
            } else {
                // Joomla 1.5
                $p->file = JPATH_SITE.'/plugins/'.$pluginGroup.'/'.$pluginName.'/tmpl/'.$tmpl.'/'.$file;
                $p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/tmpl/{$tmpl}/{$file}";
            }
        }
        return $p;
    }
} // end class
