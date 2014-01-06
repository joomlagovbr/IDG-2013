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
defined('JPATH_BASE') or die('RESTRICTED');

/**
 * JCE Plugin installer
 *
 * @package   JCE
 * @subpackage  Installer
 * @since   1.5
 */
class WFInstallerPlugin extends JObject {

    /**
     * Constructor
     *
     * @param object  $parent Parent object [JInstaller instance]
     * @return  void
     */
    public function __construct($parent) {
        $this->parent = $parent;
    }

    private function setManifest() {
        $manifest = $this->parent->getManifest();

        $values = array('name', 'description', 'install.script', 'uninstall.script', 'icon');

        foreach ($values as $value) {
            $this->parent->set($value, WFXMLHelper::getElement($manifest, $value));
        }

        $attributes = array('version', 'plugin', 'group', 'type', 'folder', 'row', 'extension');

        foreach ($attributes as $attribute) {
            $this->set($attribute, WFXMLHelper::getAttribute($manifest, $attribute));
        }

        $elements = array('files', 'languages', 'media');

        foreach ($elements as $element) {
            $this->set($element, WFXMLHelper::getElements($manifest, $element));
        }
    }

    /**
     * Install method
     *
     * @access  public
     * @return  boolean True on success
     */
    public function install() {
        // Get a database connector object
        $db = $this->parent->getDBO();

        $this->setManifest();

        $plugin = $this->get('plugin');
        $group = $this->get('group');
        $type = $this->get('type');
        $folder = $this->get('folder');

        $extension = $this->get('extension');

        // JCE Plugin
        if (!empty($plugin) || !empty($extension)) {
            if (version_compare((string) $this->get('version'), '2.0', '<')) {
                $this->parent->abort(WFText::_('WF_INSTALLER_INCORRECT_VERSION'));
                return false;
            }
            // its an "extension"
            if ($extension) {
                $this->parent->setPath('extension_root', JPATH_COMPONENT_SITE . '/editor/extensions/' . $folder);
            } else {
                $this->parent->setPath('extension_root', JPATH_COMPONENT_SITE . '/editor/tiny_mce/plugins/' . $plugin);
            }
        } else {
            // Non-JCE plugin type, probably JCE MediaBox
            if ($type == 'plugin' && $group == 'system') {
                require_once(JPATH_LIBRARIES . '/joomla/installer/adapters/plugin.php');
                // create adapter
                $adapter = new JInstallerPlugin($this->parent, $db);

                if (method_exists($adapter, 'loadLanguage')) {
                    $adapter->loadLanguage($this->parent->getPath('source'));
                }

                // set adapter
                $this->parent->setAdapter('plugin', $adapter);
                // isntall
                return $adapter->install();
            } else {
                $this->parent->abort(WFText::_('WF_INSTALLER_EXTENSION_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_NO_PLUGIN_FILE'));
                return false;
            }
        }

        /**
         * ---------------------------------------------------------------------------------------------
         * Filesystem Processing Section
         * ---------------------------------------------------------------------------------------------
         */
        // If the extension directory does not exist, lets create it
        $created = false;
        if (!file_exists($this->parent->getPath('extension_root'))) {
            if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
                $this->parent->abort(WFText::_('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_MKDIR_ERROR') . ' : "' . $this->parent->getPath('extension_root') . '"');
                return false;
            }
        }

        // Set overwrite flag if not set by Manifest
        $this->parent->setOverwrite(true);

        /*
         * If we created the extension directory and will want to remove it if we
         * have to roll back the installation, lets add it to the installation
         * step stack
         */
        if ($created) {
            $this->parent->pushStep(array(
                'type' => 'folder',
                'path' => $this->parent->getPath('extension_root')
            ));
        }

        // Copy all necessary files
        if (!$this->parent->parseFiles($this->get('files'), -1)) {
            // Install failed, roll back changes
            $this->parent->abort();
            return false;
        }
        // install languages
        $this->parent->parseLanguages($this->get('languages'), 0);
        // install media
        $this->parent->parseMedia($this->get('media'), 0);

        // Load the language file
        $language = JFactory::getLanguage();
        $language->load('com_jce_' . trim($plugin), JPATH_SITE);

        $install = (string) $this->get('install.script');

        if ($install) {
            // Make sure it hasn't already been copied (this would be an error in the xml install file)
            if (!file_exists($this->parent->getPath('extension_root') . '/' . $install)) {
                $path['src']    = $this->parent->getPath('source') . '/' . $install;
                $path['dest']   = $this->parent->getPath('extension_root') . '/' . $install;
                if (!$this->parent->copyFiles(array($path))) {
                    // Install failed, rollback changes
                    $this->parent->abort(WFText::_('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_PHP_INSTALL_FILE_ERROR'));
                    return false;
                }
            }
        }

        $uninstall = $this->get('uninstall.script');

        if ($uninstall) {
            // Make sure it hasn't already been copied (this would be an error in the xml install file)
            if (!file_exists($this->parent->getPath('extension_root') . '/' . $uninstall)) {
                $path['src'] = $this->parent->getPath('source') . '/' . $uninstall;
                $path['dest'] = $this->parent->getPath('extension_root') . '/' . $uninstall;
                if (!$this->parent->copyFiles(array(
                            $path
                        ))) {
                    // Install failed, rollback changes
                    $this->parent->abort(JText('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_PHP_UNINSTALL_FILE_ERROR'));
                    return false;
                }
            }
        }

        /**
         * ---------------------------------------------------------------------------------------------
         * Finalization and Cleanup Section
         * ---------------------------------------------------------------------------------------------
         */
        // Lastly, we will copy the manifest file to its appropriate place.
        if (!$this->parent->copyManifest(-1)) {
            // Install failed, rollback changes
            $this->parent->abort(WFText::_('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_SETUP_COPY_ERROR'));
            return false;
        }

        if ($install) {
            if (file_exists($this->parent->getPath('extension_root') . '/' . $install)) {
                ob_start();
                ob_implicit_flush(false);
                require_once($this->parent->getPath('extension_root') . '/' . $install);
                if (function_exists('jce_install')) {
                    if (jce_install() === false) {
                        $this->parent->abort(WFText::_('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_CUSTOM_INSTALL_ERROR'));
                        return false;
                    }
                } else if (function_exists('com_install')) {
                    if (com_install() === false) {
                        $this->parent->abort(WFText::_('WF_INSTALLER_PLUGIN_INSTALL') . ' : ' . WFText::_('WF_INSTALLER_CUSTOM_INSTALL_ERROR'));
                        return false;
                    }
                }
                $msg = ob_get_contents();
                ob_end_clean();
                if ($msg != '') {
                    $this->parent->set('extension.message', $msg);
                }
            }
        } else {
            $this->parent->set('extension.message', '');
        }

        $plugin = new StdClass();
        $plugin->name = $this->get('plugin');
        $plugin->icon = $this->parent->get('icon');
        $plugin->row = (int) $this->get('row');
        $plugin->path = $this->parent->getPath('extension_root');
        $plugin->type = $type;

        wfimport('admin.models.plugins');
        $model = new WFModelPlugins();
        $model->postInstall('install', $plugin, $this);

        return true;
    }

    /**
     * Uninstall method
     *
     * @access  public
     * @param 	string   $name  The name of the plugin to uninstall
     * @return  boolean True on success
     */
    public function uninstall($name) {
        // Initialize variables
        $row = null;
        $retval = true;
        $db = $this->parent->getDBO();

        $parts = explode('.', $name);
        // get name
        $name = array_pop($parts);
        // get type eg: plugin or extension
        $type = array_shift($parts);

        $this->parent->set('name', $name);

        // Load the language file
        $language = JFactory::getLanguage();

        switch ($type) {
            case 'plugin':
                // create $path
                $path = JPATH_COMPONENT_SITE . '/editor/tiny_mce/plugins/' . $name;
                // load language file
                $language->load('com_jce_' . $name, JPATH_SITE);
                break;
            case 'extension':
                $parts[] = $name;
                $path = dirname(JPATH_COMPONENT_SITE . '/editor/extensions/' . implode('/', $parts));
                // load language file
                $language->load('com_jce_' . trim(implode('_', $parts)), JPATH_SITE);
                break;
        }

        // Set the plugin root path
        $this->parent->setPath('extension_root', $path);

        // set manifest path
        $manifest = $this->parent->getPath('extension_root') . '/' . $name . '.xml';

        if (file_exists($manifest)) {
            $xml = WFXMLHelper::getXML($manifest);

            if (!$xml) {
                JError::raiseWarning(100, WFText::_('WF_INSTALLER_PLUGIN_UNINSTALL') . ' : ' . WFText::_('WF_INSTALLER_MANIFEST_INVALID'));
            }

            $this->parent->set('name', (string) $xml->name);
            $this->parent->set('version', (string) $xml->version);
            $this->parent->set('message', (string) $xml->description);

            // can't remove a core plugin
            if ((int) $xml->attributes()->core == 1) {
                JError::raiseWarning(100, WFText::_('WF_INSTALLER_PLUGIN_UNINSTALL') . ' : ' . JText::sprintf('WF_INSTALLER_WARNCOREPLUGIN', WFText::_((string) $xml->name)));
                return false;
            }

            if ($type == 'extension') {
                $this->parent->removeFiles($xml->files, -1);
                JFile::delete($manifest);
            }

            // Remove all media and languages as well
            $this->parent->removeFiles($xml->languages, 0);
            $this->parent->removeFiles($xml->media, 0);

            /**
             * ---------------------------------------------------------------------------------------------
             * Custom Uninstallation Script Section
             * ---------------------------------------------------------------------------------------------
             */
            // Now lets load the uninstall file if there is one and execute the uninstall function if it exists.
            $uninstall = (string) $xml->children('uninstall.script');

            if ($uninstall) {
                // Element exists, does the file exist?
                if (is_file($this->parent->getPath('extension_root') . '/' . $uninstall)) {
                    ob_start();
                    ob_implicit_flush(false);
                    require_once($this->parent->getPath('extension_root') . '/' . $uninstall);
                    if (function_exists('com_uninstall')) {
                        if (com_uninstall() === false) {
                            JError::raiseWarning(100, WFText::_('WF_INSTALLER_PLUGIN_UNINSTALL') . ' : ' . WFText::_('WF_INSTALLER_CUSTOM_UNINSTALL_ERROR'));
                            $retval = false;
                        }
                    }
                    $msg = ob_get_contents();
                    ob_end_clean();
                    if ($msg != '') {
                        $this->parent->set('extension.message', $msg);
                    }
                }
            }

            // remove form profile
            if ($xml->icon) {
                $plugin = new StdClass();
                $plugin->name = (string) $xml->plugin;
                $plugin->icon = (string) $xml->icon;
                $plugin->path = $this->parent->getPath('extension_root');

                wfimport('admin.models.plugins');
                $model = new WFModelPlugins();
                $model->postInstall('uninstall', $plugin, $this);
            }
        } else {
            JError::raiseWarning(100, WFText::_('WF_INSTALLER_PLUGIN_UNINSTALL') . ' : ' . WFText::_('WF_INSTALLER_MANIFEST_ERROR'));
            $retval = false;
        }
        // set plugin path
        $path = $this->parent->getPath('extension_root');
        
        // set extension path
        if ($type == 'extension') {
            $path = $this->parent->getPath('extension_root') . '/' . $name;
        }

        if (JFolder::exists($path)) {
            // remove the plugin folder
            if (!JFolder::delete($path)) {
                JError::raiseWarning(100, WFText::_('WF_INSTALLER_PLUGIN_UNINSTALL') . ' : ' . WFText::_('WF_INSTALLER_PLUGIN_FOLDER_ERROR'));
                $retval = false;
            }
        }

        return $retval;
    }

}
