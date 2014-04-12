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
class WFCodePluginConfig {

    public static function getConfig(&$settings) {
        // Get JContentEditor instance
        wfimport('admin.models.editor');
        $model = new WFModelEditor();
        $wf = WFEditor::getInstance();

        if (!in_array('code', $settings['plugins'])) {
            $settings['plugins'][] = 'code';
        }

        $settings['code_php'] = $wf->getParam('editor.allow_php', 0, 0, 'boolean');
        $settings['code_script'] = $wf->getParam('editor.allow_javascript', 0, 0, 'boolean');
        $settings['code_style'] = $wf->getParam('editor.allow_css', 0, 0, 'boolean');

        $settings['code_cdata'] = $wf->getParam('editor.cdata', 1, 1, 'boolean');

        // Invalid Elements
        if ($settings['code_script']) {
            $model->removeKeys($settings['invalid_elements'], 'script');
        }
        if ($settings['code_style']) {
            $model->removeKeys($settings['invalid_elements'], 'style');
        }
    }

}

?>