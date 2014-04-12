<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_JEXEC') or die('RESTRICTED');

wfimport('admin.classes.view');

class WFViewConfig extends WFView
{
    function display($tpl = null)
    {                
        $language =JFactory::getLanguage();
        $language->load('plg_editors_jce', JPATH_ADMINISTRATOR);
        
        $client = JRequest::getWord('client', 'site');

        $model = $this->getModel();

        $plugin     = WFExtensionHelper::getPlugin();
        $xml        = WF_EDITOR_LIBRARIES.'/xml/config/editor.xml';
        
        $data       = null;

        // get params from editor plugin
        if ($plugin->params && $plugin->params !== "{}") {
            $data = json_decode($plugin->params);
        } else {
            $component  = WFExtensionHelper::getComponent();
            
            // get params from component "params" field (legacy)
            if ($component->params) {
                $data = json_decode($component->params);
            }
        }
        
        // get params definitions
        $params = new WFParameter($data, $xml, 'editor');
             
        $params->addElementPath(JPATH_COMPONENT.'/elements');
        
        $this->assign('model', 	$model);
        $this->assign('params', $params);
        $this->assign('client', $client);

        WFToolbarHelper::apply();
        WFToolbarHelper::save();
        WFToolbarHelper::help('config.about');
        
        parent::display($tpl);
    }
}
