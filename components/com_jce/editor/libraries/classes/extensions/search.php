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

wfimport('editor.libraries.classes.extensions');

class WFSearchExtension extends WFExtension {

    private static $instances  = array();

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Returns a reference to a plugin object
     *
     * This method must be invoked as:
     * 		<pre>  $advlink =AdvLink::getInstance();</pre>
     *
     * @access	public
     * @return	JCE  The editor object.
     * @since	1.5
     */
    public function getInstance($type, $config = array()) {
        if (!isset(self::$instances)) {
            self::$instances = array();
        }

        if (empty(self::$instances[$type])) {
            require_once(WF_EDITOR . '/extensions/search/' . $type . '.php');

            $classname = 'WF' . ucfirst($type) . 'SearchExtension';

            if (class_exists($classname)) {
                self::$instances[$type] = new $classname($config);
            } else {
                self::$instances[$type] = new WFSearchExtension();
            }
        }

        return self::$instances[$type];
    }

    public function display() {
        parent::display();
    }

    public function getView($layout) {        
        return parent::getView(array('name' => 'search', 'layout' => $layout));
    }
}