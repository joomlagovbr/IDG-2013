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

/**
 * Extension Manager Default View
 *
 * @package		JCE
 * @since		1.5
 */
class WFViewPreferences extends WFView {

    function display($tpl = null) {
        $db = JFactory::getDBO();

        $client = JRequest::getWord('client', 'admin');
        $model = $this->getModel();

        $this->document->setTitle(WFText::_('WF_PREFERENCES_TITLE'));
        $this->document->addStyleSheet('templates/system/css/system.css');

        $component = WFExtensionHelper::getComponent();
        $xml = JPATH_COMPONENT . '/models/preferences.xml';

        // get params definitions
        $params = new WFParameter($component->params, $xml, 'preferences');
        $params->addElementPath(JPATH_COMPONENT . '/elements');

        if (WFModel::authorize('admin')) {
            $form = $model->getForm('permissions');
        } else {
            $form = null;
        }

        $this->assign('params', $params);
        $this->assign('permissons', $form);
        
        $this->addStyleSheet('components/com_jce/media/css/preferences.css');

        $this->addScript('components/com_jce/media/js/preferences.js');

        if (JRequest::getInt('close') == 1) {
            $this->addScriptDeclaration('jQuery(document).ready(function($){$.jce.Preferences.close();});');
        } else {
            $this->addScriptDeclaration('jQuery(document).ready(function($){$.jce.Preferences.init();});');
        }

        parent::display($tpl);
    }

}