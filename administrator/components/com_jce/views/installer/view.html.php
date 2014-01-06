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
jimport('joomla.client.helper');

/**
 * Installer View
 *
 * @package		JCE
 * @since		1.6
 */
class WFViewInstaller extends WFView {

    function display($tpl = null) {
        wfimport('admin.models.updates');

        $app = JFactory::getApplication();

        $model = $this->getModel();
        $state = $model->getState();

        $layout = JRequest::getWord('layout', 'install');

        $plugins = '';
        $extensions = '';
        $languages = '';
        
        JHtml::_('behavior.modal');

        if (WFModel::authorize('uninstall')) {
            WFToolbarHelper::deleteList('', 'remove', 'WF_INSTALLER_UNINSTALL');
        }
        WFToolbarHelper::updates(WFModelUpdates::canUpdate());
        WFToolbarHelper::help('installer.about');

        $options = array(
            'extensions' => array('zip', 'tar', 'gz', 'gzip', 'tgz', 'tbz2', 'bz2', 'bzip2'),
            'width' => 300,
            'button' => 'install_button',
            'task' => 'install',
            'iframe' => false,
            'labels' => array(
                'browse' => WFText::_('WF_LABEL_BROWSE'),
                'alert' => WFText::_('WF_INSTALLER_FILETYPE_ERROR')
            )
        );
        $this->addScript('components/com_jce/media/js/installer.js');
        $this->addScript('components/com_jce/media/js/uploads.js');
        $this->addScriptDeclaration('jQuery(document).ready(function($){$.jce.Installer.init(' . json_encode($options) . ');});');

        // load styles
        $this->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/installer.css');


        $state->set('install.directory', $app->getCfg('tmp_path'));

        $plugins = $model->getPlugins();
        $extensions = $model->getExtensions();
        $languages = $model->getLanguages();
        $related = $model->getRelated();

        $this->assign('plugins', $plugins);
        $this->assign('extensions', $extensions);
        $this->assign('languages', $languages);
        $this->assign('related', $related);

        $result = $state->get('install.result');

        $this->assign('showMessage', count($result));
        $this->assign('model', $model);
        $this->assign('state', $state);

        $ftp = JClientHelper::setCredentialsFromRequest('ftp');

        $this->assign('ftp', $ftp);

        $this->setLayout($layout);

        parent::display($tpl);
    }

    function loadItem($index = 0) {
        $item = $this->items[$index];
        $item->index = $index;

        $item->cbd = null;
        $item->style = null;

        $item->author_info = @$item->authorEmail . '<br />' . @$item->authorUrl;

        $this->assignRef('item', $item);
    }

}