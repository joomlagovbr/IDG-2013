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

jimport('joomla.installer.installer');

/**
 * Plugins Component Controller
 *
 * @package		Joomla
 * @subpackage	Plugins
 */
class WFInstaller extends JObject {
    
    protected   $_adapters = null;
    
    public      $installer = null;
    

    public function __construct() {
        $installer = JInstaller::getInstance();
        
        $this->installer = $installer;
    }
    
    /**
     * Returns the global Installer object, only creating it
     * if it doesn't already exist.
     *
     * @return  object  An installer object
     */
    public static function getInstance() {
        static $instance;

        if (!isset($instance)) {
            $instance = new WFInstaller;
        }
        return $instance;
    }

    /**
     * Set an installer adapter by name
     *
     * @access	public
     * @param	string	$name		Adapter name
     * @param	object	$adapter	Installer adapter object
     * @return	boolean True if successful
     */
    public function setAdapter($name, $adapter = null) {
        if (!is_object($adapter)) {
            $adapter = $this->getAdapter($name);
        }
        $this->_adapters[$name] = $adapter;
        return true;
    }

    /**
     * Get a JCE installer adapter
     * @param string $name adapter name eg: plugin.
     * @return $adapter instance
     */
    public function getAdapter($type) {
        // Try to load the adapter object
        require_once(dirname(dirname(__FILE__)) . '/adapters/' . strtolower($type) . '.php');

        $class = 'WFInstaller' . ucfirst($type);

        if (!class_exists($class)) {
            return false;
        }

        $adapter = new $class($this);

        // set parent as JInstaller instance
        $adapter->parent = $this->installer;

        return $adapter;
    }

    public function install($path) {        
        if ($path && JFolder::exists($path)) {
            $this->installer->setPath('source', $path);
        } else {
            $this->installer->abort(JText::_('JLIB_INSTALLER_ABORT_NOINSTALLPATH'));
            return false;
        }

        if (!$this->setupInstall()) {
            $this->installer->abort(JText::_('JLIB_INSTALLER_ABORT_DETECTMANIFEST'));
            return false;
        }

        // Load the adapter(s) for the install manifest
        $type = (string) $this->installer->manifest->attributes()->type;

        if ($type == 'extension') {
            $type = 'plugin';
        }

        if (is_object($this->_adapters[$type])) {
            // Add the languages from the package itself
            if (method_exists($this->_adapters[$type], 'loadLanguage')) {
                $this->_adapters[$type]->loadLanguage($path);
            }

            // Run the install
            $ret = $this->_adapters[$type]->install();
            
            $this->set('name', $this->installer->get('name'));
            $this->set('version', $this->installer->get('version'));
            $this->set('message', $this->installer->get('description'));
            $this->set('extension.message', $this->installer->get('extension.message'));
            
            return $ret;
        }
    }

    /**
     * Package uninstallation method
     *
     * @param   string   $type        Package type
     * @param   mixed    $identifier  Package identifier for adapter
     *
     * @return  boolean  True if successful
     */
    public function uninstall($type, $identifier) {
        if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type])) {
            if (!$this->setAdapter($type)) {
                // We failed to get the right adapter
                return false;
            }
        }

        if (is_object($this->_adapters[$type])) {
            // Run the uninstall
            return $this->_adapters[$type]->uninstall($identifier);
        }

        return false;
    }

    /**
     * Prepare for installation: this method sets the installation directory, finds
     * and checks the installation file and verifies the installation type.
     *
     * @return  boolean  True on success
     */
    public function setupInstall() {
        // We need to find the installation manifest file
        if (!$this->findManifest()) {
            return false;
        }

        // Load the adapter(s) for the install manifest
        $type = (string) $this->installer->manifest->attributes()->type;

        if ($type == 'extension') {
            $type = 'plugin';
        }

        // Lazy load the adapter
        if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type])) {
            $adapter = $this->getAdapter($type);

            if (!$adapter) {
                return false;
            }

            if (!$this->setAdapter($type, $adapter)) {
                return false;
            }
        }

        return true;
    }

    public function getManifest() {        
        if (!is_object($this->installer->manifest)) {
            $this->findManifest();
        }

        return $this->installer->manifest;
    }

    /**
     * Tries to find the package manifest file
     *
     * @return  boolean  True on success, False on error
     */
    public function findManifest() {
        jimport('joomla.filesystem.folder');

        // Get an array of all the XML files from the installation directory
        $xmlfiles = JFolder::files($this->installer->getPath('source'), '.xml$', 1, true);

        // If at least one XML file exists
        if (!empty($xmlfiles)) {

            foreach ($xmlfiles as $file) {
                // Is it a valid Joomla installation manifest file?
                $manifest = $this->isManifest($file);

                if (!is_null($manifest)) {
                    // If the root method attribute is set to upgrade, allow file overwrite
                    if ((string) $manifest->attributes()->method == 'upgrade') {
                        if (method_exists($this->installer, 'setUpgrade')) {
                            $this->installer->setUpgrade(true);
                        }
  
                        $this->installer->setOverwrite(true);
                    }

                    // If the overwrite option is set, allow file overwriting
                    if ((string) $manifest->attributes()->overwrite == 'true') {
                        $this->installer->setOverwrite(true);
                    }

                    // Set the manifest object and path
                    $this->installer->manifest = $manifest;
                    $this->installer->setPath('manifest', $file);

                    // Set the installation source path to that of the manifest file
                    $this->installer->setPath('source', dirname($file));

                    return true;
                }
            }

            // None of the XML files found were valid install files
            JError::raiseWarning(JText::_('WF_INSTALLER_MANIFEST_INVALID'));

            return false;
        } else {
            // No XML files were found in the install folder
            JError::raiseWarning(JText::_('WF_INSTALLER_MANIFEST_LOAD_ERROR'));
            return false;
        }
    }

    /**
     * Is the XML file a valid Joomla installation manifest file.
     *
     * @param   string  $file  An xmlfile path to check
     *
     * @return  mixed  A SimpleXMLElement, or null if the file failed to parse
     */
    public function isManifest($file) {
        $xml = simplexml_load_file($file);

        // If we cannot load the XML file return null
        if (!$xml) {
            return null;
        }

        // Check for a valid XML root tag.
        if ($xml->getName() != 'extension' && $xml->getName() != 'install') {
            return null;
        }

        // Valid manifest file return the object
        return $xml;
    }

}

?>