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

class com_jceInstallerScript {

    public function preflight($type, $parent) {
        $requirements = self::checkRequirements();

        if ($requirements !== true) {
            echo $requirements;
            return false;
        }

        return true;
    }

    public function install($parent) {
        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/install.php');

        $installer = method_exists($parent, 'getParent') ? $parent->getParent() : $parent->parent;

        return WFInstall::install($installer);
    }

    public function uninstall() {
        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/install.php');

        return WFInstall::uninstall();
    }

    public function update($parent) {
        return $this->install($parent);
    }

    public function postflight($type, $parent) {
        
    }

    public static function checkRequirements() {
        $requirements = array();

        // check PHP version
        if (version_compare(PHP_VERSION, '5.2.4', '<')) {
            $requirements[] = array(
                'name' => 'PHP Version',
                'info' => 'JCE Requires PHP version 5.2.4 or later. Your version is : ' . PHP_VERSION
            );
        }

        // check JSON is installed
        if (function_exists('json_encode') === false || function_exists('json_decode') === false) {
            $requirements[] = array(
                'name' => 'JSON',
                'info' => 'JCE requires the <a href="http://php.net/manual/en/book.json.php" target="_blank">PHP JSON</a> extension which is not available on this server.'
            );
        }

        // check SimpleXML
        if (function_exists('simplexml_load_string') === false || function_exists('simplexml_load_file') === false || class_exists('SimpleXMLElement') === false) {
            $requirements[] = array(
                'name' => 'SimpleXML',
                'info' => 'JCE requires the <a href="http://php.net/manual/en/book.simplexml.php" target="_blank">PHP SimpleXML</a> library which is not available on this server.'
            );
        }

        if (!empty($requirements)) {
            $message = '<div id="jce"><style type="text/css" scoped="scoped">' . file_get_contents(dirname(__FILE__) . '/media/css/install.css') . '</style>';

            $message .= '<h2>' . JText::_('WF_ADMIN_TITLE') . ' - Install Failed</h2>';
            $message .= '<h3>JCE could not be installed as this site does not meet <a href="http://www.joomlacontenteditor.net/support/documentation/56-editor/106-requirements" target="_blank">technical requirements</a> (see below)</h3>';
            $message .= '<ul class="install">';

            foreach ($requirements as $requirement) {
                $message .= '<li class="error">' . $requirement['name'] . ' : ' . $requirement['info'] . '<li>';
            }

            $message .= '</ul>';
            $message .= '</div>';

            return $message;
        }

        return true;
    }

}

/**
 * Installer function
 * @return
 */
function com_install() {

    if (!defined('JPATH_PLATFORM')) {
        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/install.php');

        $installer      = JInstaller::getInstance();
        $requirements   = com_jceInstallerScript::checkRequirements();
        
        if ($requirements !== true) {
            $installer->set('message', $requirements);

            $installer->abort();

            WFInstall::cleanupInstall();

            return false;
        }


        return WFInstall::install($installer);
    }

    return true;
}

/**
 * Uninstall function
 * @return
 */
function com_uninstall() {

    if (!defined('JPATH_PLATFORM')) {
        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/install.php');

        return WFInstall::uninstall();
    }

    return true;
}

?>
