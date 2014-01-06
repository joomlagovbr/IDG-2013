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

require_once( WF_EDITOR_LIBRARIES . '/classes/plugin.php' );

class WFSearchReplacePlugin extends WFEditorPlugin {

    /**
     * Display the plugin
     */
    public function display() {
        parent::display();

        $document = WFDocument::getInstance();

        $document->addScript(array('searchreplace'), 'plugins');
        $document->addStyleSheet(array('searchreplace'), 'plugins');

        $settings = $this->getSettings();

        $document->addScriptDeclaration('SearchReplaceDialog.settings=' . json_encode($settings) . ';');

        $tabs = WFTabs::getInstance(array(
                    'base_path' => WF_EDITOR_PLUGIN
                ));
        // Add tabs
        $tabs->addTab('find');
        $tabs->addTab('replace');
    }

    public function getSettings() {
        $settings = array();

        return parent::getSettings($settings);
    }
}

?>
