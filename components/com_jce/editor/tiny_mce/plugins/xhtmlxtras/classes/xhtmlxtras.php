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

class WFXHTMLXtrasPlugin extends WFEditorPlugin {

    public function getElementName() {
        return JRequest::getWord('element', 'attributes');
    }

    public function isHTML5() {
        return $this->getParam('editor.schema', 'html4') == 'html5' && (bool) $this->getParam('editor.verify_html', 0) === true;
    }

    /**
     * Display the plugin
     */
    public function display() {
        parent::display();
        
        $document   = WFDocument::getInstance();  
        $element    = $this->getElementName();

        $document->setTitle(WFText::_('WF_' . strtoupper($element) . '_TITLE'));

        $document->addScript(array('xhtmlxtras'), 'plugins');
        $document->addStyleSheet(array('xhtmlxtras'), 'plugins');

        $document->addScriptDeclaration('XHTMLXtrasDialog.settings=' . json_encode($this->getSettings()) . ';');
        
        $tabs = WFTabs::getInstance(array('base_path' => WF_EDITOR_PLUGIN));

        $tabs->addTab('standard', 1, array('plugin' => $this));

        if ($element == 'attributes') {
            $tabs->addTab('events');
        }
    }

    public function getSettings() {
        return parent::getSettings();
    }
}

?>
