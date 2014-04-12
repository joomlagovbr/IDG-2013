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

require_once (WF_EDITOR_LIBRARIES . '/classes/plugin.php');

class WFTablesPlugin extends WFEditorPlugin {
    
    public function __construct() {
        parent::__construct(array('colorpicker' => true));
    }
    
    public function getContext() {
        return JRequest::getWord('context', 'table');
    }

    /**
     * Display the plugin
     */
    public function display() {
        parent::display();

        $document = WFDocument::getInstance();

        $document->addScript(array('table'), 'plugins');
        $document->addStyleSheet(array('table'), 'plugins');

        $settings = $this->getSettings();

        $document->addScriptDeclaration('TableDialog.settings=' . json_encode($settings) . ';');

        $tabs = WFTabs::getInstance(array('base_path' => WF_EDITOR_PLUGIN));

        if ($this->getContext() == 'merge') {
            // Add tabs
            $tabs->addTab('merge');
        } else {
            $tabs->addTab('general', 1, array('plugin' => $this));
            $tabs->addTab('advanced', 1, array('plugin' => $this));
        }
    }

    public function getSettings() {
        $profile = $this->getProfile();

        $settings = array(
            'file_browser' => $this->getParam('file_browser', 1) && in_array('browser', explode(',', $profile->plugins)),
        );

        return parent::getSettings($settings);
    }

    /**
     * Get default parameters
     * @return string parameters
     */
    public function getDefaults() {        
        return parent::getDefaults(array());
    }

}

?>
