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
require_once (WF_EDITOR_LIBRARIES . '/classes/plugin.php');

final class WFSourcePlugin extends WFEditorPlugin {
    public function display() {
        $document = WFDocument::getInstance();

        $view = $this->getView();

        $view->addTemplatePath(WF_EDITOR_PLUGIN . '/tmpl');

        $document->setTitle(WFText::_('WF_' . strtoupper($this->getName() . '_TITLE')));

        $theme  = $this->getParam('source.theme', 'textmate');
        //$editor = 'codemirror';
        
        $document->addScript(array('tiny_mce_popup'), 'tiny_mce');
        $document->addScript(array('editor', 'format'), 'plugins');
        $document->addStyleSheet(array('editor'), 'plugins');
        
        $document->addScript(array('codemirror-compressed'), 'jce.tiny_mce.plugins.source.js.codemirror');
        $document->addStyleSheet(array('codemirror', 'theme/' . $theme), 'jce.tiny_mce.plugins.source.css.codemirror');
        
        /*switch ($editor) {
            case 'ace':
                $document->addScript(array('ace', 'mode-html'), 'jce.tiny_mce.plugins.source.js.ace');
                
                if ($theme != 'textmate') {
                    $document->addScript(array('theme-' . $theme), 'jce.tiny_mce.plugins.source.js.ace');
                }
                break;
            case 'codemirror':
                $document->addScript(array('codemirror-compressed'), 'jce.tiny_mce.plugins.source.js.codemirror');
                $document->addStyleSheet(array('codemirror', 'theme/' . $theme), 'jce.tiny_mce.plugins.source.css.codemirror');

                break;
        }*/
    }
}
