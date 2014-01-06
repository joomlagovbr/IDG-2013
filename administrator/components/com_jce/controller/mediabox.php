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

class WFControllerMediabox extends WFController {

    function __construct($default = array()) {
        parent::__construct();
        
        $this->registerTask('apply', 'save');
    }

    public function save() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');
        
        $row    = WFExtensionHelper::getPlugin(null, 'jcemediabox', 'system');

        $task   = $this->getTask();
        
        // remove id for Joomla! 2.5+
        if ($row->extension_id) {
            unset($row->id);
        }

        if (!$row->bind(JRequest::get('post'))) {
            JError::raiseError(500, $row->getError());
        }

        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();

        $msg = JText::sprintf('WF_MEDIABOX_SAVED');

        switch ($task) {
            case 'apply':
                $this->setRedirect('index.php?option=com_jce&view=mediabox', $msg);
                break;

            case 'save':
            default:
                $this->setRedirect('index.php?option=com_jce&view=cpanel', $msg);
                break;
        }
    }

}

?>