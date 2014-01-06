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
require_once (dirname(__FILE__) . '/model.php');

class WFModelUpdates extends WFModel {

    protected static $updateURL = 'https://www.joomlacontenteditor.net/index.php?option=com_updates&format=raw';

    public static function canUpdate() {
        if (!function_exists('curl_init')) {
            return function_exists('file_get_contents') && function_exists('ini_get') && ini_get('allow_url_fopen');
        }

        return true;
    }

    /**
     * Get extension versions
     * @return Array
     */
    public function getVersions() {
        $db = JFactory::getDBO();

        $versions = array('joomla' => array(), 'jce' => array());

        // Get Component xml
        $com_xml = WFXMLHelper::parseInstallManifest(JPATH_ADMINISTRATOR . '/components/com_jce/jce.xml');

        // set component version
        $versions['joomla']['com_jce'] = $com_xml['version'];
        // get mediabox version
        $mediabox_xml_file = WF_JOOMLA15 ? JPATH_PLUGINS . '/system/jcemediabox.xml' : JPATH_PLUGINS . '/system/jcemediabox/jcemediabox.xml';
        // set mediabox version
        if (file_exists($mediabox_xml_file)) {
            $mediabox_xml = WFXMLHelper::parseInstallManifest($mediabox_xml_file);
            $versions['joomla']['plg_jcemediabox'] = $mediabox_xml['version'];
        }

        wfimport('admin.models.plugins');
        $model = new WFModelPlugins();

        // get all plugins
        $plugins = $model->getPlugins();
        // get all extensions
        $extensions = $model->getExtensions();

        foreach ($plugins as $plugin) {
            if ($plugin->core == 0) {

                $file = WF_EDITOR_PLUGINS . '/' . $plugin->name . '/' . $plugin->name . '.xml';

                $xml = WFXMLHelper::parseInstallManifest($file);
                $versions['jce']['jce_' . $plugin->name] = $xml['version'];
            }
        }

        foreach ($extensions as $extension) {
            if ($extension->core == 0) {

                $file = WF_EDITOR_EXTENSIONS . '/' . $extension->folder . '/' . $extension->extension . '.xml';

                $xml = WFXMLHelper::parseInstallManifest($file);
                $versions['jce']['jce_' . $extension->folder . '_' . $extension->extension] = $xml['version'];
            }
        }

        return $versions;
    }

    /**
     * Check for extension updates
     * @return String JSON string of updates
     */
    public function check() {
        $result = false;

        // Get all extensions and version numbers
        $data = array('task' => 'check', 'jversion' => WF_JOOMLA15 ? '1.5' : '2.5');

        wfimport('admin.helpers.extension');

        $component = WFExtensionHelper::getComponent();
        $params = new WFParameter($component->params, '', 'preferences');

        // get update key
        $key = $params->get('updates_key', '');
        $type = $params->get('updates_type', '');

        // encode it
        if (!empty($key)) {
            $data['key'] = urlencode($key);
        }

        if ($type) {
            $data['type'] = $type;
        }

        $req = array();

        // create request data
        foreach ($this->getVersions() as $type => $extension) {
            foreach ($extension as $item => $value) {
                $data[$type . '[' . urlencode($item) . ']'] = urlencode($value);
            }
        }

        foreach ($data as $key => $value) {
            $req[] = $key . '=' . urlencode($value);
        }

        // connect
        $result = $this->connect(self::$updateURL, implode('&', $req));

        return $result;
    }

    /**
     * Download update
     * @return String JSON string
     */
    public function download() {
        $app = JFactory::getApplication();

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $config = JFactory::getConfig();

        $result = array('error' => WFText::_('WF_UPDATES_DOWNLOAD_ERROR'));

        $id = JRequest::getInt('id');

        $data = $this->connect(self::$updateURL, 'task=download&id=' . $id);

        if ($data) {
            $data = json_decode($data);

            if (isset($data->error)) {
                return json_encode(array('error' => $data->error));
            }

            // get update file
            if ($data->name && $data->url && $data->hash) {
                // create path for package file
                $path = $app->getCfg('tmp_path') . '/' . basename($data->name);
                // download file
                if ($this->connect($data->url, null, $path)) {
                    if (JFile::exists($path) && @filesize($path) > 0) {
                        // check hash and file type
                        if ($data->hash == md5(md5_file($path)) && preg_match('/\.(zip|tar|gz)$/', $path)) {
                            $result = array('file' => basename($path), 'hash' => $data->hash, 'installer' => $data->installer, 'type' => isset($data->type) ? $data->type : '');
                        } else {
                            // fail and delete file
                            $result = array('error' => WFText::_('WF_UPDATES_ERROR_FILE_VERIFICATION_FAIL'));
                            if (JFile::exists($path)) {
                                @JFile::delete($path);
                            }
                        }
                    } else {
                        $result = array('error' => WFText::_('WF_UPDATES_ERROR_FILE_MISSING_OR_INVALID'));
                    }
                } else {
                    $result = array('error' => WFText::_('WF_UPDATES_DOWNLOAD_ERROR_DATA_TRANSFER'));
                }
            } else {
                $result = array('error' => WFText::_('WF_UPDATES_DOWNLOAD_ERROR_MISSING_DATA'));
            }
        }

        return json_encode($result);
    }

    /**
     * Method to detect the extension type from a package directory
     *
     * @param   string  $dir  Path to package directory
     * @return  mixed  Extension type string or boolean false on fail
     * 
     * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     */
    public static function detectType($dir) {
        // Search the install dir for an XML file
        $files = JFolder::files($dir, '\.xml$', 1, true);

        if (!count($files)) {
            return false;
        }

        foreach ($files as $file) {
            $xml = simplexml_load_file($file);
            if (!$xml) {
                continue;
            }

            $name = $xml->getName();

            if ($name != 'extension' && $name != 'install') {
                unset($xml);
                continue;
            }

            $type = (string) $xml->attributes()->type;

            // Free up memory
            unset($xml);
            return $type;
        }

        // Free up memory.
        unset($xml);
        return false;
    }

    /**
     * Unpacks a file and verifies it as a Joomla element package
     * Supports .gz .tar .tar.gz and .zip
     *
     * @param   string  $archive  The uploaded package filename or install directory
     * @return  mixed  Array on success or boolean false on failure
     * 
     * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     */
    private static function unpack($archive) {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.archive');

        // Temporary folder to extract the archive into
        $tmpdir = uniqid('install_');

        // Clean the paths to use for archive extraction
        $extractdir = JPath::clean(dirname($archive) . '/' . $tmpdir);

        // Do the unpacking of the archive
        try {
            JArchive::extract($archive, $extractdir);
        } catch (Exception $e) {
            return false;
        }

        /*
         * Let's set the extraction directory and package file in the result array so we can
         * cleanup everything properly later on.
         */
        $retval['extractdir'] = $extractdir;
        $retval['packagefile'] = $archive;

        /*
         * Try to find the correct install directory.  In case the package is inside a
         * subdirectory detect this and set the install directory to the correct path.
         *
         * List all the items in the installation directory.  If there is only one, and
         * it is a folder, then we will set that folder to be the installation folder.
         */
        $dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));

        if (count($dirList) == 1) {
            if (JFolder::exists($extractdir . '/' . $dirList[0])) {
                $extractdir = JPath::clean($extractdir . '/' . $dirList[0]);
            }
        }

        $retval['dir'] = $extractdir;

        /*
         * Get the extension type and return the directory/type array on success or
         * false on fail.
         */
        $retval['type'] = self::detectType($extractdir);

        if ($retval['type']) {
            return $retval;
        } else {
            return false;
        }
    }

    /**
     * Install extension update
     * @return String JSON string
     */
    public function install() {
        jimport('joomla.installer.installer');
        jimport('joomla.installer.helper');
        jimport('joomla.filesystem.file');

        $app = JFactory::getApplication();
        $result = array('error' => WFText::_('WF_UPDATES_INSTALL_ERROR'));

        // get vars
        $file = JRequest::getCmd('file');
        $hash = JRequest::getVar('hash', '', 'POST', 'alnum');
        $method = JRequest::getWord('installer');
        $type = JRequest::getWord('type');

        // check for vars
        if ($file && $hash && $method) {
            $path = $app->getCfg('tmp_path') . '/' . $file;
            // check if file exists
            if (JFile::exists($path)) {
                // check hash
                if ($hash == md5(md5_file($path))) {
                    if ($package = self::unpack($path)) {

                        // Install a JCE Add-on
                        if ($method == 'jce') {
                            wfimport('admin.classes.installer');

                            $installer = WFInstaller::getInstance();

                            // install
                            if ($installer->install($package['dir'])) {
                                // installer message
                                $result = array('error' => '', 'text' => WFText::_($installer->get('message'), $installer->get('message')));
                            }
                            // Install a Joomla! Extension    
                        } else {
                            jimport('joomla.installer.installer');

                            // get new Installer instance
                            $installer = JInstaller::getInstance();

                            if ($installer->install($package['dir'])) {
                                // installer message
                                $result = array('error' => '', 'text' => WFText::_($installer->get('message'), $installer->get('message')));
                            }
                        }
                        // Cleanup the install files
                        if (!is_file($package['packagefile'])) {
                            $package['packagefile'] = $app->getCfg('tmp_path') . '/' . $package['packagefile'];
                        }
                        if (is_file($package['packagefile'])) {
                            JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
                        }
                    } else {
                        $result = array('error' => WFText::_('WF_UPDATES_ERROR_FILE_EXTRACT_FAIL'));
                    }
                } else {
                    $result = array('error' => WFText::_('WF_UPDATES_ERROR_FILE_VERIFICATION_FAIL'));
                }
            } else {
                $result = array('error' => WFText::_('WF_UPDATES_ERROR_FILE_MISSING_OR_INVALID'));
            }
        }
        return json_encode($result);
    }

    /**
     * @copyright   Copyright (C) 2009 Ryan Demmer. All rights reserved.
     * @copyright   Copyright (C) 2006-2010 Nicholas K. Dionysopoulos
     * @param 	String 	$url URL to resource
     * @param 	Array  	$data [optional] Array of key value pairs
     * @param 	String 	$download [optional] path to file to write to
     * @return 	Mixed 	Boolean or JSON String on error
     */
    function connect($url, $data = '', $download = '') {
        @error_reporting(E_ERROR);

        jimport('joomla.filesystem.file');

        $fp = false;

        // get wrappers
        $wrappers = stream_get_wrappers();

        // check for support
        $fopen = function_exists('file_get_contents') && function_exists('ini_get') && ini_get('allow_url_fopen') && in_array('https', $wrappers);

        // try file_get_contents first (requires allow_url_fopen)
        if ($fopen) {
            if ($download) {
                // use Joomla! installer function
                jimport('joomla.installer.helper');
                return @JInstallerHelper::downloadPackage($url, $download);
            } else {
                $options = array('http' => array('method' => 'POST', 'timeout' => 10, 'content' => $data));

                $context = stream_context_create($options);
                $result = @file_get_contents($url, false, $context);

                if ($result === false) {
                    return array('error' => WFText::_('Update check failed : Invalid response from update server'));
                }

                return $result;
            }
            // Use curl if it exists
        } else if (function_exists('curl_init')) {

            // check for SSL support
            $version = curl_version();
            $ssl_supported = ($version['features'] & CURL_VERSION_SSL);

            if (!$ssl_supported) {
                return array('error' => WFText::_('Update check failed : OpenSSL support for CURL is required'));
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            // Pretend we are IE7, so that webservers play nice with us
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
            //curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // The @ sign allows the next line to fail if open_basedir is set or if safe mode is enabled
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($ch, CURLOPT_MAXREDIRS, 20);

            @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($data && !$download) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }

            // file download
            if ($download) {
                $fp = @fopen($download, 'wb');
                @curl_setopt($ch, CURLOPT_FILE, $fp);
            }

            $result = curl_exec($ch);

            // file download
            if ($download && $result === false) {
                return array('error' => 'TRANSFER ERROR : ' . curl_error($ch));
            }

            curl_close($ch);

            // close fopen handler
            if ($fp) {
                @fclose($fp);
            }

            return $result;

            // error - no update support
        } else {
            return array('error' => WFText::_('WF_UPDATES_DOWNLOAD_ERROR_NO_CONNECT'));
        }

        return array('error' => WFText::_('WF_UPDATES_DOWNLOAD_ERROR_NO_CONNECT'));
    }

    function log($msg) {
        jimport('joomla.error.log');
        $log = JLog::getInstance('updates.txt');
        $log->addEntry(array('comment' => 'LOG: ' . $msg));
    }

}

?>
