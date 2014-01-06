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
class WFCleanupPluginConfig {

    private static $invalid_elements = array('iframe', 'object', 'param', 'embed', 'audio', 'video', 'source', 'script', 'style', 'applet', 'body', 'bgsound', 'base', 'basefont', 'frame', 'frameset', 'head', 'html', 'id', 'ilayer', 'layer', 'link', 'meta', 'name', 'title', 'xml');

    public static function getConfig(&$settings) {
        $wf = WFEditor::getInstance();
        wfimport('admin.models.editor');
        $model = new WFModelEditor();
        
        // Encoding
        $settings['entity_encoding'] = $wf->getParam('editor.entity_encoding', 'raw', 'named');
        
        // keep &nbsp;
        $nbsp = (bool) $wf->getParam('editor.keep_nbsp', 1);
        
        // use named encoding with limited entities set if raw/utf-8 and keep_nbsp === true
        if ($settings['entity_encoding'] == 'raw' && $nbsp) {
            $settings['entity_encoding'] = '';
            $settings['entities'] = '160,nbsp';
        }

        // set "plugin mode"
        $settings['cleanup_pluginmode'] = $wf->getParam('cleanup.pluginmode', 0, 0);
        
        // get verify html (default is true)
        $settings['verify_html'] = $wf->getParam('editor.verify_html', 1, 1, 'boolean');

        // set schema
        $settings['schema'] = $wf->getParam('editor.schema', 'html4', 'html4');

        // Get Extended elements
        $settings['extended_valid_elements'] = $wf->getParam('editor.extended_elements', '', '');
        // Configuration list of invalid elements as array
        $settings['invalid_elements'] = explode(',', $wf->getParam('editor.invalid_elements', '', ''));

        // Add elements to invalid list (removed by plugin)
        $model->addKeys($settings['invalid_elements'], self::$invalid_elements);

        // remove extended_valid_elements
        if ($settings['extended_valid_elements']) {
            preg_match_all('#(\w+)(\[([^\]]+)\])?#', $settings['extended_valid_elements'], $extended);

            if ($extended && count($extended) > 1) {
                $settings['invalid_elements'] = array_diff($settings['invalid_elements'], $extended[1]);
            }
        }

        // remove it if it is the same as the default
        if ($settings['invalid_elements'] === self::$invalid_elements) {
            $settings['invalid_elements'] = array();
        }

        $settings['invalid_attributes'] = $wf->getParam('editor.invalid_attributes', 'dynsrc,lowsrc', 'dynsrc,lowsrc', 'string', true);
        $settings['invalid_attribute_values'] = $wf->getParam('editor.invalid_attribute_values', '', '', 'string', true);
    }

}

?>