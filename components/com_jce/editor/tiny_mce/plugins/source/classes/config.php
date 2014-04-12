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
class WFSourcePluginConfig {

    public static function getConfig(&$settings) {
        $wf = WFEditor::getInstance();

        $settings['source_higlight'] = $wf->getParam('source.highlight', 1, 1, 'boolean');
        $settings['source_numbers'] = $wf->getParam('source.numbers', 1, 1, 'boolean');
        $settings['source_wrap'] = $wf->getParam('source.wrap', 1, 1, 'boolean');
        $settings['source_format'] = $wf->getParam('source.foramt', 1, 1, 'boolean');
        $settings['source_tag_closing'] = $wf->getParam('source.tag_closing', 1, 1, 'boolean');
        $settings['source_selection_match'] = $wf->getParam('source.selection_match', 1, 1, 'boolean');
        
        $settings['source_font_size'] = $wf->getParam('source.font_size', '', '');

        $theme = $wf->getParam('source.theme', 'textmate', 'textmate');

        $settings['source_theme'] = $theme;
    }

    public static function getStyles() {
        $wf = WFEditor::getInstance();

        if (JRequest::getWord('layout') === 'plugin') {
            // return file(s) array
            if ($wf->getParam('editor.compress_css', 0)) {
                return array(dirname(dirname(__FILE__)) . '/css/editor.css');
            }

            // use document instance	
            $document = JFactory::getDocument();
            $document->addStyleSheet(JURI::root(true) . '/components/com_jce/editor/tiny_mce/plugins/source/css/editor.css?version=' . $wf->getVersion());
        }
    }

}

?>