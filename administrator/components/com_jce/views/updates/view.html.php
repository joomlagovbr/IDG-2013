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

class WFViewUpdates extends WFView {

    function display($tpl = null) {
        $model = $this->getModel();

        $this->addScript('components/com_jce/media/js/update.js');

        $options = array(
            'language' => array(
                'check' => WFText::_('WF_UPDATES_CHECK'),
                'install' => WFText::_('WF_UPDATES_INSTALL'),
                'installed' => WFText::_('WF_UPDATES_INSTALLED'),
                'no_updates' => WFText::_('WF_UPDATES_NONE'),
                'high' => WFText::_('WF_UPDATES_HIGH'),
                'medium' => WFText::_('WF_UPDATES_MEDIUM'),
                'low' => WFText::_('WF_UPDATES_LOW'),
                'full' => WFText::_('WF_UPDATES_FULL'),
                'patch' => WFText::_('WF_UPDATES_PATCH'),
                'auth_failed' => WFText::_('WF_UPDATES_AUTH_FAIL'),
                'update_info' => WFText::_('WF_UPDATES_INFO'),
                'install_info' => WFText::_('WF_UPDATES_INSTALL_INFO'),
                'check_updates' => WFText::_('WF_UPDATES_CHECKING'),
                'read_more'     => WFText::_('WF_UPDATES_READMORE'),
                'read_less'     => WFText::_('WF_UPDATES_READLESS')
            )
        );

        $options = json_encode($options);

        $this->addScriptDeclaration('jQuery(document).ready(function($){$.jce.Update.init(' . $options . ');});');

        // load styles
        $this->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/updates.css');

        parent::display($tpl);
    }

}

?>
