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

class WFControllerPreferences extends WFController {

    /**
     * Custom Constructor
     */
    function __construct($default = array()) {
        parent::__construct();

        $this->registerTask('apply', 'save');
    }

    protected function filter($data) {
        $model = $this->getModel('preferences');
        $form = $model->getForm();

        if (is_a($form, 'JForm')) {
            return $form->filter($data);
        }

        return $data;
    }

    public function save() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $db = JFactory::getDBO();

        $post = JRequest::getVar('params', '', 'POST', 'ARRAY');
        $registry = new JRegistry();
        $registry->loadArray($post);

        // get params
        $component = WFExtensionHelper::getComponent();

        // set preferences object
        $preferences = $registry->toObject();

        if (isset($preferences->rules)) {
            $preferences->access = $preferences->rules;
            
            unset($preferences->rules);
        }

        // set params as JSON string
        $component->params = json_encode($preferences);

        if (!$component->check()) {
            JError::raiseError(500, $row->getError());
        }
        if (!$component->store()) {
            JError::raiseError(500, $row->getError());
        }
        $component->checkin();

        $close = 0;

        if ($this->getTask() == 'save') {
            $close = 1;
        }

        $this->setRedirect('index.php?option=com_jce&view=preferences&tmpl=component&close=' . $close, WFText::_('WF_PREFERENCES_SAVED'));
    }

}

?>