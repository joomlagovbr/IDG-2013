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
class WFControllerInstaller extends WFController {

    /**
     * Custom Constructor
     */
    function __construct($default = array()) {
        parent::__construct();

        $this->registerTask('disable', 'enable');

        $language = JFactory::getLanguage();
        $language->load('com_installer', JPATH_ADMINISTRATOR);
    }

    /**
     * Install an extension
     *
     * @access	public
     * @return	void
     * @since	1.5
     */
    function install() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('RESTRICTED');

        $model = $this->getModel('installer');

        if ($model->install()) {
            $cache = JFactory::getCache('mod_menu');
            $cache->clean();
        }

        $view = $this->getView();
        $view->setModel($model, true);
        $view->display();
    }

    /**
     * Remove (uninstall) an extension
     *
     * @static
     * @param	array	An array of identifiers
     * @return	boolean	True on success
     * @since 1.0
     */
    function remove() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('RESTRICTED');

        $model = $this->getModel('installer');

        $items = array(
            'plugin'    => JRequest::getVar('pid', array(), '', 'array'),
            'language'  => JRequest::getVar('lid', array(), '', 'array'),
            'related'   => JRequest::getVar('rid', array(), '', 'array')
        );

        // Uninstall the chosen extensions
        foreach ($items as $type => $ids) {
            if (count($ids)) {
                foreach ($ids as $id) {
                    if ($id) {
                        if ($model->remove($id, $type)) {
                            $cache = JFactory::getCache('mod_menu');
                            $cache->clean();
                        }
                    }
                }
            }
        }

        $view = $this->getView();
        $view->setModel($model, true);
        $view->display();
    }

}

?>