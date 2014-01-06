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

// load base model
wfimport('admin.models.model');
wfimport('admin.classes.installer');

jimport('joomla.installer.helper');

class WFModelInstaller extends WFModel {

    /** @var object JTable object */
    var $_table = null;

    /** @var object JTable object */
    var $_url = null;
    var $_result = array();

    public function cancel() {
        $this->setRedirect(JRoute::_('index.php?option=com_jce&client=' . $client, false));
    }

    public function install($package = null) {
        $app = JFactory::getApplication();

        if (!$package) {
            $package = $this->getPackage();
        }

        // Was the package unpacked?
        if (!$package) {
            $this->setState('message', 'WF_INSTALLER_NO_PACKAGE');
            return false;
        }

        // Get an installer instance
        $installer = WFInstaller::getInstance();

        // Install the package
        if (!$installer->install($package['dir'])) {
            $result = false;

            $app->enqueueMessage(WFText::sprintf('WF_INSTALLER_INSTALL_ERROR'), 'error');
        } else {
            $result = true;

            $app->enqueueMessage(WFText::sprintf('WF_INSTALLER_INSTALL_SUCCESS'));
        }

        $this->_result[] = array(
            'name' => $installer->get('name'),
            'type' => $package['type'],
            'version' => $installer->get('version'),
            'result' => $result
        );

        $this->setState('install.result', $this->_result);

        $this->setState('name', WFText::_($installer->get('name')));
        $this->setState('message', WFText::_($installer->get('message')));
        $this->setState('extension.message', $installer->get('extension.message'));
        $this->setState('result', $result);

        // Cleanup the install files
        if (!is_file($package['packagefile'])) {
            $package['packagefile'] = $app->getCfg('tmp_path') . '/' . $package['packagefile'];
        }
        if (is_file($package['packagefile'])) {
            JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
        }
        return $result;
    }

    public function remove($id, $type) {
        $app = JFactory::getApplication();

        // Use Joomla! Installer class for related extensions
        if ($type == 'related') {
            jimport('joomla.installer.installer');
            $installer = JInstaller::getInstance();
            $result = $installer->uninstall('plugin', $id);
        } else {
            $installer = WFInstaller::getInstance();

            $installer->setAdapter($type);
            $result = $installer->uninstall($type, $id);
        }

        if (!$result) {
            $app->enqueueMessage(WFText::sprintf('WF_INSTALLER_UNINSTALL_ERROR'), 'error');
        } else {
            $app->enqueueMessage(WFText::sprintf('WF_INSTALLER_UNINSTALL_SUCCESS'));
        }

        $this->_result[] = array(
            'name' => $installer->get('name'),
            'type' => $type,
            'version' => $installer->get('version'),
            'result' => $result
        );

        $this->setState('name', WFText::_($installer->get('name')));
        $this->setState('result', $result);
        $this->setState('install.result', $this->_result);

        return $result;
    }

    /**
     * Get the install package or folder
     * @return Array $package
     */
    private function getPackage() {
        $app = JFactory::getApplication();
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.archive');

        // set standard method
        $upload = true;
        $package = null;

        // Get the uploaded file information
        $file = JRequest::getVar('install', null, 'files', 'array');
        // get the file path information
        $path = JRequest::getString('install_input');

        if (!(bool) ini_get('file_uploads') || !is_array($file)) {
            $upload = false;
            // no path either!
            if (!$path) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_NO_FILE'));
                return false;
            }
        }

        // Install failed
        if (!is_uploaded_file($file['tmp_name']) || !$file['tmp_name'] || !$file['name'] || $file['error']) {
            $upload = false;
            // no path either!
            if (!$path) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_NO_FILE'));
                return false;
            }
        }

        // uploaded file
        if ($upload) {
            // check extension
            if (!preg_match('/\.(zip|tar|gz|gzip|tgz|tbz2|bz2|bzip2)$/i', $file['name'])) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_INVALID_FILE'));
                return false;
            }

            $dest   = JPath::clean($app->getCfg('tmp_path') . '/' . $file['name']);
            $src    = $file['tmp_name'];
            // upload file
            if (!JFile::upload($src, $dest)) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_UPLOAD_FAILED'));
                return false;
            }
            
            if (!is_file($dest)) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_UPLOAD_FAILED'));
                return false;
            }
            
            // path to file
        } else {
            $dest = JPath::clean($path);
        }

        // set install method
        JRequest::setVar('install_method', 'install');

        // Unpack the package file
        if (preg_match('/\.(zip|tar|gz|gzip|tgz|tbz2|bz2|bzip2)/i', $dest)) {
            // Make sure that zlib is loaded so that the package can be unpacked
            if (!extension_loaded('zlib')) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_WARNINSTALLZLIB'));
                return false;
            }

            $package = JPath::clean(dirname($dest) . '/' . uniqid('install_'));

            if (!JArchive::extract($dest, $package)) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_EXTRACT_ERROR'));
                return false;
            }

            if (JFolder::exists($package)) {
                $type = self::detectType($package);
            }

            return array(
                'manifest' => null,
                'packagefile' => $dest,
                'extractdir' => $package,
                'dir' => $package,
                'type' => $type
            );

            // might be a directory
        } else {
            if (!is_dir($dest)) {
                JError::raiseWarning('SOME_ERROR_CODE', WFText::_('WF_INSTALLER_INVALID_SRC'));
                return false;
            }

            // Detect the package type
            $type = self::detectType($dest);

            return array(
                'manifest' => null,
                'packagefile' => null,
                'extractdir' => null,
                'dir' => $dest,
                'type' => $type
            );
        }
    }

    private static function detectType($path) {
        // Search the install dir for an XML file
        $files = JFolder::files($path, '\.xml$', 1, true);

        if (!count($files)) {
            return false;
        }

        foreach ($files as $file) {
            $xml = simplexml_load_file($file);
            if (!$xml) {
                continue;
            }

            $name = (string) $xml->getName();

            if ($name != 'extension' && $name != 'install') {
                unset($xml);
                continue;
            }

            $type = (string) $xml->attributes()->type;

            // Free up memory
            unset($xml);
            return $type;
        }
        return false;
    }

    public function getExtensions() {
        wfimport('admin.models.plugins');
        $model = new WFModelPlugins();

        // get an array of all installed plugins in plugins folder
        $extensions = $model->getExtensions();

        return $extensions;
    }

    public function getPlugins() {
        wfimport('admin.models.plugins');
        $model = new WFModelPlugins();

        // get an array of all installed plugins in plugins folder
        $plugins = $model->getPlugins();

        $rows = array();

        $language = JFactory::getLanguage();

        foreach ($plugins as $plugin) {
            if ($plugin->core == 0) {
                $rows[] = $plugin;
                $language->load('com_jce_' . trim($plugin->name), JPATH_SITE);
            }
        }

        return $rows;
    }

    /**
     * Get additional plugins such as JCE MediaBox etc.
     * @return 
     */
    public function getRelated() {
        // Get a database connector
        $db = JFactory::getDBO();

        $params = JComponentHelper::getParams('com_jce');

        // pre-defined array of other plugins
        $related = preg_replace('#(\w+)#', "'$1'", $params->get('related_extensions', 'jcemediabox,jceutilities,mediaobject,wfmediabox'));
        $query  = $db->getQuery(true);

        // Joomla! 2.5
        if (is_object($query)) {
            $query->select(array('extension_id', 'name', 'element', 'folder'))->from('#__extensions')->where(array('type = ' . $db->Quote('plugin'), 'element IN (' . $related . ')'))->order('name');
            // Joomla! 1.5    
        } else {
            $query = 'SELECT id, name, element, folder FROM #__plugins WHERE element IN (' . $related . ') ORDER BY name';
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $language = JFactory::getLanguage();

        $num = count($rows);
        
        for ($i = 0; $i < $num; $i++) {
            $row = $rows[$i];

            if (defined('JPATH_PLATFORM')) {
                $file = JPATH_PLUGINS . '/' . $row->folder . '/' . $row->element . '/' . $row->element . ".xml";
            } else {
                $file = JPATH_PLUGINS . '/' . $row->folder . '/' . $row->element . ".xml";
            }
            
            if (isset($row->extension_id)) {
                $row->id = $row->extension_id; 
            }

            if (is_file($file)) {
                $xml = WFXMLElement::load($file);

                if ($xml) {
                    $row->title = (string) $xml->name;

                    $row->author = (string) $xml->author;
                    $row->version = (string) $xml->version;
                    $row->creationdate = (string) $xml->creationDate;
                    $row->description = (string) $xml->description;
                    $row->authorUrl = (string) $xml->authorUrl;
                }
            }

            $language->load('plg_' . trim($row->folder) . '_' . trim($row->element), JPATH_ADMINISTRATOR);
            $language->load('plg_' . trim($row->folder) . '_' . trim($row->element), JPATH_SITE);
        }

        //return array_values($rows);
        return $rows;
    }

    public function getLanguages() {
        // Get the site languages
        $base = JLanguage::getLanguagePath(JPATH_SITE);
        $dirs = JFolder::folders($base);

        for ($i = 0; $i < count($dirs); $i++) {
            $lang = new stdClass();
            $lang->folder = $dirs[$i];
            $lang->baseDir = $base;
            $languages[] = $lang;
        }
        $rows = array();
        foreach ($languages as $language) {
            $files = JFolder::files($language->baseDir . '/' . $language->folder, '\.(com_jce)\.xml$');
            foreach ($files as $file) {
                $data = WFXMLHelper::parseInstallManifest($language->baseDir . '/' . $language->folder . '/' . $file);

                $row = new StdClass();
                $row->language = $language->folder;

                if ($row->language == 'en-GB') {
                    $row->cbd = 'disabled="disabled"';
                    $row->style = ' style="color:#999999;"';
                } else {
                    $row->cbd = '';
                    $row->style = '';
                }

                // If we didn't get valid data from the xml file, move on...
                if (!is_array($data)) {
                    continue;
                }

                // Populate the row from the xml meta file
                foreach ($data as $key => $value) {
                    $row->$key = $value;
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

}