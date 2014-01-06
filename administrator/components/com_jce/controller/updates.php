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

/**
 * Plugins Component Controller
 *
 * @package		Joomla
 * @subpackage	Plugins
 * @since 1.5
 */
class WFControllerUpdates extends WFController {

    /**
     * Custom Constructor
     */
    function __construct($default = array()) {
        parent::__construct();
    }

    function update() {
        $step = JRequest::getWord('step');
        $model = $this->getModel('updates');

        $result = array();

        switch ($step) {
            case 'check':
                $result = $model->check();
                break;
            case 'download':
                $result = $model->download();
                break;
            case 'install':
                $result = $model->install();
                break;
        }

        ob_start();

        // set output headers
        header('Content-Type: text/json;charset=UTF-8');
        header('Content-Encoding: UTF-8');
        header("Expires: Mon, 4 April 1984 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        echo $result;

        exit(ob_get_clean());
    }

}

?>