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
class WFControllerConfig extends WFController {

    /**
     * Custom Constructor
     */
    public function __construct($default = array()) {
        parent::__construct();

        $this->registerTask('apply', 'save');
    }

    public function save() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $db     = JFactory::getDBO();
        $task   = $this->getTask();

        // get plugin
        $plugin = WFExtensionHelper::getPlugin();

        // get params data
        $data   = JRequest::getVar('params', '', 'POST', 'ARRAY');
        // clean input data
        $data   = $this->cleanInput($data);
        
        // store data
        $plugin->params = json_encode($data);
        
        // remove "id"
        if (isset($plugin->extension_id)) {
            unset($plugin->id);
        }

        if (!$plugin->check()) {
            JError::raiseError(500, $plugin->getError());
        }
        if (!$plugin->store()) {
            JError::raiseError(500, $plugin->getError());
        }
        
        $plugin->checkin();

        $msg = JText::sprintf('WF_CONFIG_SAVED');

        switch ($task) {
            case 'apply':
                $this->setRedirect('index.php?option=com_jce&view=config', $msg);
                break;

            case 'save':
            default:
                $this->setRedirect('index.php?option=com_jce&view=cpanel', $msg);
                break;
        }
    }

}

?>