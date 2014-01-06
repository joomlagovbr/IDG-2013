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

class WFControllerProfiles extends WFController {

    /**
     * Custom Constructor
     */
    public function __construct($default = array()) {
        parent::__construct();

        $this->registerTask('apply', 'save');
        $this->registerTask('unpublish', 'publish');
        $this->registerTask('enable', 'publish');
        $this->registerTask('disable', 'publish');
        $this->registerTask('orderup', 'order');
        $this->registerTask('orderdown', 'order');
    }

    public function remove() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        JArrayHelper::toInteger($cid, array(0));

        if (count($cid) < 1) {
            JError::raiseError(500, WFText::_('WF_PROFILES_SELECT_ERROR'));
        }

        $cids = implode(',', $cid);

        $query = 'DELETE FROM #__wf_profiles'
                . ' WHERE id IN ( ' . $cids . ' )'
        ;
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        $msg = JText::sprintf('WF_PROFILES_DELETED', count($cid));
        $this->setRedirect('index.php?option=com_jce&view=profiles', $msg);
    }

    public function copy() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        JArrayHelper::toInteger($cid, array(0));

        $n = count($cid);
        if ($n == 0) {
            return JError::raiseWarning(500, WFText::_('WF_PROFILES_SELECT_ERROR'));
        }

        $row = JTable::getInstance('profiles', 'WFTable');

        foreach ($cid as $id) {
            // load the row from the db table
            $row->load((int) $id);
            $row->name = JText::sprintf('WF_PROFILES_COPY_OF', $row->name);
            $row->id = 0;
            $row->published = 0;

            if (!$row->check()) {
                return JError::raiseWarning(500, $row->getError());
            }
            if (!$row->store()) {
                return JError::raiseWarning(500, $row->getError());
            }
            $row->checkin();
            $row->reorder('ordering=' . $db->Quote($row->ordering));
        }
        $msg = JText::sprintf('WF_PROFILES_COPIED', $n);
        $this->setRedirect('index.php?option=com_jce&view=profiles', $msg);
    }

    public function save() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $db         = JFactory::getDBO();
        $filter     = JFilterInput::getInstance();
        $row        = JTable::getInstance('profiles', 'WFTable');
        $task       = $this->getTask();

        $result     = array('error' => false);

        if (!$row->bind(JRequest::get('post'))) {
            JError::raiseError(500, $db->getErrorMsg());
        }
        
        // add types from usergroups
        $row->types = JRequest::getVar('usergroups', array(), 'post', 'array');

        foreach (get_object_vars($row) as $key => $value) {
            switch ($key) {
                case 'name':
                case 'description':
                    $value = $filter->clean($value);
                    break;
                case 'components':
                case 'device':
                    $value = implode(',', $this->cleanInput($value));
                    break;
                case 'types':
                case 'users':
                    $value = implode(',', $this->cleanInput($value, 'int'));
                    break;
                case 'area':
                    if (empty($value) || count($value) == 2) {
                        $value = 0;
                    } else {
                        $value = $value[0];
                    }
                    break;
                case 'plugins':
                    $value = preg_replace('#[^\w,]+#', '', $value);
                    break;
                case 'rows':
                    $value = preg_replace('#[^\w,;]+#', '', $value);
                    break;
                case 'params':
                    $json = array();

                    // suhosin - params submitted as string
                    if (is_string($value)) {
                        $value = trim($value);
                        // base64 decode
                        //$value = base64_decode($value);
                        parse_str(rawurldecode($value), $json);
                    } else {
                        if (array_key_exists('editor', $value)) {
                            $json['editor'] = $value['editor'];
                        }
                        // get plugins
                        $plugins = explode(',', $row->plugins);

                        foreach ($plugins as $plugin) {
                            // add plugin params to array
                            if (array_key_exists($plugin, $value)) {
                                $json[$plugin] = $value[$plugin];
                            }
                        }
                    }
                    // clean data
                    $json = $this->cleanInput($json);
                    
                    // encode as json string
                    $value = json_encode($json);

                    break;
                case 'params-string':
                    $value = trim($value);

                    parse_str(rawurldecode($value), $json);

                    $key = 'params';
                    $value = json_encode($json);

                    break;
            }

            $row->$key = $value;
        }

        if (!$row->check()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        if (!$row->store()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        $row->checkin();

        switch ($task) {
            case 'apply':
                $msg = JText::sprintf('WF_PROFILES_SAVED_CHANGES', $row->name);
                $this->setRedirect('index.php?option=com_jce&view=profiles&task=edit&cid[]=' . $row->id, $msg);
                break;

            case 'save':
            default:
                $msg = JText::sprintf('WF_PROFILES_SAVED', $row->name);
                $this->setRedirect('index.php?option=com_jce&view=profiles', $msg);
                break;
        }
    }

    /**
     * Generic publish method
     * @return
     */
    public function publish() {
        // Check for request forgeries
        JRequest::checkToken() or die('Invalid Token');

        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $cid = JRequest::getVar('cid', array(0), 'post', 'array');

        JArrayHelper::toInteger($cid, array(0));

        switch ($this->getTask()) {
            case 'publish':
            case 'enable':
                $publish = 1;
                break;
            case 'unpublish':
            case 'disable':
                $publish = 0;
                break;
        }

        $view = JRequest::getCmd('view');

        if (count($cid) < 1) {
            $action = $publish ? WFText::_('WF_LABEL_PUBLISH') : WFText::_('WF_LABEL_UNPUBLISH');
            JError::raiseError(500, JText::sprintf('WF_PROFILES_VIEW_SELECT', $view, $action));
        }

        $cids = implode(',', $cid);

        $query = 'UPDATE #__wf_profiles SET published = ' . (int) $publish
                . ' WHERE id IN ( ' . $cids . ' )'
                . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id') . ' ))'
        ;
        $db->setQuery($query);

        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        if (count($cid) == 1) {
            $row = JTable::getInstance('profiles', 'WFTable');
            $row->checkin($cid[0]);
        }
        $this->setRedirect('index.php?option=com_jce&view=profiles');
    }

    public function order() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        $db = JFactory::getDBO();

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        JArrayHelper::toInteger($cid, array(0));

        $uid = $cid[0];
        $inc = ( $this->getTask() == 'orderup' ? -1 : 1 );

        $row = JTable::getInstance('profiles', 'WFTable');
        $row->load($uid);
        $row->move($inc);

        $this->setRedirect('index.php?option=com_jce&view=profiles');
    }

    public function saveorder() {
        // Check for request forgeries
        JRequest::checkToken() or jexit('RESTRICTED');

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        $order = JRequest::getVar('order', array(0), 'post', 'array');

        if (!empty($cid)) {
            $model = $this->getModel('profiles', 'WFModel');
            $result = $model->saveOrder($cid, $order);
        }

        // ajax request
        if (JRequest::getWord('tmpl') === 'component') {
            echo (int) $result;
            JFactory::getApplication()->close();
        }

        $msg = WFText::_('WF_PROFILES_ORDERING_SAVED');
        $this->setRedirect('index.php?option=com_jce&view=profiles', $msg);
    }

    public function cancelEdit() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $view = JRequest::getCmd('view');

        $db = JFactory::getDBO();
        $row = JTable::getInstance($view, 'WFTable');
        $row->bind(JRequest::get('post'));
        $row->checkin();

        $this->setRedirect(JRoute::_('index.php?option=com_jce&view=' . $view, false));
    }

    public function export() {
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $tmp = $mainframe->getCfg('tmp_path');

        $buffer = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $buffer .= "\n" . '<export type="profiles">';
        $buffer .= "\n\t" . '<profiles>';

        $cid = JRequest::getVar('cid', array(0), 'post', 'array');
        JArrayHelper::toInteger($cid, array(0));

        if (count($cid) < 1) {
            JError::raiseError(500, WFText::_('WF_PROFILES_SELECT_ERROR'));
        }

        $cids = implode(',', $cid);

        // get froup data
        $query = 'SELECT * FROM #__wf_profiles'
                . ' WHERE id IN (' . $cids . ')'
        ;

        $db->setQuery($query);
        $profiles = $db->loadObjectList();

        foreach ($profiles as $profile) {
            // remove some stuff
            unset($profile->id);
            unset($profile->checked_out);
            unset($profile->checked_out_time);
            // set published to 0
            $profile->published = 0;

            $buffer .= "\n\t\t";
            $buffer .= '<profile>';

            foreach ($profile as $key => $value) {
                if ($key == 'params') {
                    $buffer .= "\n\t\t\t" . '<' . $key . '>';
                    if ($value) {
                        $params = explode("\n", $value);
                        foreach ($params as $param) {
                            if ($param !== '') {
                                $buffer .= "\n\t\t\t\t" . '<param>' . $param . '</param>';
                            }
                        }
                        $buffer .= "\n\t\t\t\t";
                    }
                    $buffer .= '</' . $key . '>';
                } else {
                    $buffer .= "\n\t\t\t" . '<' . $key . '>' . $this->encodeData($value) . '</' . $key . '>';
                }
            }
            $buffer .= "\n\t\t</profile>";
        }
        $buffer .= "\n\t</profiles>";
        $buffer .= "\n</export>";

        // set_time_limit doesn't work in safe mode
        if (!ini_get('safe_mode')) {
            @set_time_limit(0);
        }

        $name = 'jce_profile_' . date('Y_m_d') . '.xml';

        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: text/xml");
        header('Content-Disposition: attachment;'
                . ' filename="' . $name . '";'
        );

        echo $buffer;

        exit();
    }

    /**
     * Process XML restore file
     * @param object $xml
     * @return boolean
     */
    public function import() {
        // Check for request forgeries
        JRequest::checkToken() or die('RESTRICTED');

        $app = JFactory::getApplication();
        $file = JRequest::getVar('import', '', 'files', 'array');
        $input = JRequest::getVar('import_input');
        $tmp = $app->getCfg('tmp_path');
        $model = $this->getModel('profiles', 'WFModel');

        $filter = JFilterInput::getInstance();

        jimport('joomla.filesystem.file');

        if (!is_array($file)) {
            $app->enqueueMessage(WFText::_('WF_PROFILES_UPLOAD_NOFILE'), 'error');
        } else {
            // check for valid uploaded file
            if (is_uploaded_file($file['tmp_name']) && $file['name']) {
                // create destination path
                $destination = $tmp . '/' . $file['name'];
                if (JFile::upload($file['tmp_name'], $destination)) {
                    // check it exists, was uploaded properly
                    if (JFile::exists($destination)) {
                        // process import
                        $model->processImport($destination);
                    } else {
                        $app->enqueueMessage(WFText::_('WF_PROFILES_UPLOAD_FAILED'), 'error');
                    }
                } else {
                    $app->enqueueMessage(WFText::_('WF_PROFILES_UPLOAD_FAILED'), 'error');
                }
            } else {
                // clean input
                $input = $filter->clean($input, 'path');

                // check for file input value instead
                if ($input) {
                    // check file exists
                    if (JFile::exists($input)) {
                        // process import
                        $model->processImport($input);
                    } else {
                        $app->enqueueMessage(WFText::_('WF_PROFILES_IMPORT_NOFILE'), 'error');
                    }
                } else {
                    $app->enqueueMessage(WFText::_('WF_PROFILES_UPLOAD_FAILED'), 'error');
                }
            }
        }

        $this->setRedirect('index.php?option=com_jce&view=profiles');
    }

    /**
     * CDATA encode a parameter if it contains & < > characters, eg: <![CDATA[index.php?option=com_content&view=article&id=1]]>
     * @param object $param
     * @return CDATA encoded parameter or parameter
     */
    private function encodeData($data) {
        if (preg_match('/[<>&]/', $data)) {
            $data = '<![CDATA[' . $data . ']]>';
        }

        $data = preg_replace('/"/', '\"', $data);

        return $data;
    }

}

?>