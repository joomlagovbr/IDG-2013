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
defined('_WF_EXT') or die('RESTRICTED');

class WFPopupsExtension_Window {

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    public function __construct() {
        // only if enabled
        if (self::isEnabled()) {

            $document = WFDocument::getInstance();

            $document->addScript('window', 'extensions/popups/window/js');
            $document->addStyleSheet('window', 'extensions/popups/window/css');
        }
    }

    public function getParams() {
        return array();
    }

    public function isEnabled() {
        $plugin = WFEditorPlugin::getInstance();

        if ($plugin->getParam('popups.window.enable', 1) && ($plugin->getName() == 'link' || $plugin->getName() == 'imgmanager_ext')) {
            return true;
        }

        return false;
    }

}

?>