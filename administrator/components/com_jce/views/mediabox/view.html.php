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

class WFViewMediabox extends WFView {

    function getParams($data) {

        jimport('joomla.form.form');

        if (class_exists('JForm')) {
            //JForm::addFormPath(JPATH_PLUGINS . '/system/jcemediabox');

            $xml = JPATH_PLUGINS . '/system/jcemediabox/jcemediabox.xml';

            $params = new WFParameter($data, $xml, '', array('control' => 'config:fields:fieldset'));
            $params->addElementPath(JPATH_PLUGINS . '/system/jcemediabox/elements');
            
            $groups = array();
            $array  = array();

            foreach ($params->getGroups() as $group) {
                $groups[] = $params->getParams('params', $group);
            }

            foreach ($groups as $group) {
                $array = array_merge($array, $group);
            }

            return $array;
            
        } else {
            // get params definitions
            $params = new JParameter($data, JPATH_PLUGINS . '/system/jcemediabox.xml');

            $xml = JPATH_PLUGINS . '/system/jcemediabox.xml';
            $params->loadSetupFile($xml);

            return $params->getParams();
        }
    }

    function display($tpl = null) {
        $db = JFactory::getDBO();

        $lang = JFactory::getLanguage();
        $lang->load('plg_system_jcemediabox');

        $client = JRequest::getWord('client', 'site');
        $model = $this->getModel();

        $plugin = JPluginHelper::getPlugin('system', 'jcemediabox');

        $params = $this->getParams($plugin->params);

        $this->assign('params', $params);
        $this->assign('client', $client);

        $this->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/colorpicker.js');
        $this->addStyleSheet('components/com_jce/media/css/colorpicker.css');

        $options = array(
            'template_colors' => WFToolsHelper::getTemplateColors(),
            'custom_colors' => '',
            'labels' => array(
                'picker' => WFText::_('WF_COLORPICKER_PICKER'),
                'palette' => WFText::_('WF_COLORPICKER_PALETTE'),
                'named' => WFText::_('WF_COLORPICKER_NAMED'),
                'template' => WFText::_('WF_COLORPICKER_TEMPLATE'),
                'custom' => WFText::_('WF_COLORPICKER_CUSTOM'),
                'color' => WFText::_('WF_COLORPICKER_COLOR'),
                'apply' => WFText::_('WF_COLORPICKER_APPLY'),
                'name' => WFText::_('WF_COLORPICKER_NAME')
            )
        );

        $this->addScriptDeclaration('jQuery(document).ready(function($){$("input.color").colorpicker(' . json_encode($options) . ');});');

        WFToolbarHelper::apply();
        WFToolbarHelper::save();
        WFToolbarHelper::help('mediabox.config');

        parent::display($tpl);
    }

}
