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

// try to set time limit
@set_time_limit(0);

// try to increase memory limit
if ((int) ini_get('memory_limit') < 32) {
    @ini_set('memory_limit', '32M');
}

abstract class WFInstall {

    private static function cleanupInstall() {
        $path = JPATH_ADMINISTRATOR . '/components/com_jce';

        if (!is_file($path . '/jce.php')) {
            self::removePackages();

            $db = JFactory::getDBO();

            // cleanup menus
            if (defined('JPATH_PLATFORM')) {
                $query = $db->getQuery(true);
                $query->select('id')->from('#__menu')->where(array('alias = ' . $db->Quote('jce'), 'menutype = ' . $db->Quote('main')));

                $db->setQuery($query);
                $id = $db->loadResult();

                $query->clear();

                if ($id) {
                    $table = JTable::getInstance('menu');

                    // delete main item
                    $table->delete((int) $id);

                    // delete children
                    $query->select('id')->from('#__menu')->where('parent_id = ' . $db->Quote($id));

                    $db->setQuery($query);
                    $ids = $db->loadColumn();

                    $query->clear();

                    if (!empty($ids)) {
                        // Iterate the items to delete each one.
                        foreach ($ids as $menuid) {
                            $table->delete((int) $menuid);
                        }
                    }

                    // Rebuild the whole tree
                    $table->rebuild();
                }
            } else {
                $db->setQuery('DELETE FROM #__components WHERE `option` = ' . $db->Quote('com_jce'));
                $db->query();
            }
        }

        if (is_file($path . '/install.script.php')) {
            jimport('joomla.filesystem.folder');
            jimport('joomla.filesystem.file');

            JFile::delete($path . '/install.script.php');
            JFolder::delete($path);
        }
    }

    public static function install($installer) {
        error_reporting(E_ERROR | E_WARNING);

        // load languages
        $language = JFactory::getLanguage();
        $language->load('com_jce', JPATH_ADMINISTRATOR, null, true);
        $language->load('com_jce.sys', JPATH_ADMINISTRATOR, null, true);

        // get manifest
        $manifest = $installer->getManifest();
        $new_version = (string) $manifest->version;

        // Joomla! 1.5
        if (!defined('JPATH_PLATFORM') && !$new_version) {
            $new_version = (string) $manifest->document->getElementByPath('version')->data();
        }

        // get version from xml file
        if (!$manifest) {
            $manifest = JApplicationHelper::parseXMLInstallFile($installer->getPath('manifest'));
            if (is_array($manifest)) {
                $new_version = $manifest['version'];
            }
        }

        $state = false;

        // the current version
        $current_version = $new_version;

        if (defined('JPATH_PLATFORM')) {
            $xml_file = $installer->getPath('extension_administrator') . '/jce.xml';
            // check for an xml file
            if (is_file($xml_file)) {
                if ($xml = JApplicationHelper::parseXMLInstallFile($xml_file)) {
                    $current_version = $xml['version'];
                }
            }
        } else {
            if (basename($installer->getPath('manifest')) == 'legacy.xml') {
                $xml_file = JPATH_PLUGINS . '/editors/jce.xml';

                // check for an xml file
                if ($xml = JApplicationHelper::parseXMLInstallFile($xml_file)) {
                    $current_version = $xml['version'];
                } else {
                    // check for old tables
                    if (self::checkTable('#__jce_groups')) {
                        $current_version = '1.5.0';
                    }

                    // check for old tables
                    if (self::checkTable('#__jce_profiles')) {
                        $current_version = '2.0.0beta1';
                    }
                }
            }
        }
        
        // install profiles etc.
        $state = self::installProfiles();

        // perform upgrade
        if (version_compare($current_version, $new_version, '<')) {
            $state = self::upgrade($current_version);
        }

        // Add device column
        if (self::checkTableColumn('#__wf_profiles', 'device') === false) {
            $db = JFactory::getDBO();

            switch (strtolower($db->name)) {
                case 'mysql':
                case 'mysqli':
                    $query = 'ALTER TABLE #__wf_profiles CHANGE `description` `description` TEXT';
                    $db->setQuery($query);
                    $db->query();

                    // Change types field to TEXT
                    $query = 'ALTER TABLE #__wf_profiles CHANGE `types` `types` TEXT';
                    $db->setQuery($query);
                    $db->query();

                    // Add device field - MySQL
                    $query = 'ALTER TABLE #__wf_profiles ADD `device` VARCHAR(255) AFTER `area`';

                    break;
                case 'sqlsrv':
                case 'sqlazure':
                case 'sqlzure':
                    $query = 'ALTER TABLE #__wf_profiles ADD `device` NVARCHAR(250)';
                    break;
                case 'postgresql':
                    $query = 'ALTER TABLE #__wf_profiles ADD "device" character varying(255) NOT NULL';
                    break;
            }

            $db->setQuery($query);
            $db->query();
        }

        if ($state) {
            // legacy (JCE 1.5) cleanup
            if (!defined('JPATH_PLATFORM')) {
                self::legacyCleanup();
            }

            $message = '<div id="jce"><style type="text/css" scoped="scoped">' . file_get_contents(dirname(__FILE__) . '/media/css/install.css') . '</style>';

            $message .= '<h2>' . JText::_('WF_ADMIN_TITLE') . ' ' . $new_version . '</h2>';
            $message .= '<ul class="install">';
            $message .= '<li class="success">' . JText::_('WF_ADMIN_DESC') . '<li>';

            // install packages (editor plugin, quickicon etc)
            $packages = $installer->getPath('source') . '/packages';

            // install additional packages
            if (is_dir($packages)) {
                $message .= self::installPackages($packages);
            }

            $message .= '</ul>';
            $message .= '</div>';

            $installer->set('message', $message);

            // post-install
            self::addIndexfiles(array(dirname(__FILE__), JPATH_SITE . '/components/com_jce', JPATH_PLUGINS . '/jce'));
        } else {
            $installer->abort();

            return false;
        }
    }

    public static function uninstall() {
        $db = JFactory::getDBO();

        // remove Profiles table if its empty
        if ((int) self::checkTableContents('#__wf_profiles') == 0) {
            if (method_exists($db, 'dropTable')) {
                $db->dropTable('#__wf_profiles', true);
            } else {
                $query = 'DROP TABLE IF EXISTS #__wf_profiles';
                $db->setQuery($query);
            }

            $db->query();
        }
        // remove packages
        self::removePackages();
    }

    private static function paramsToObject($data) {
        $registry = new JRegistry();
        $registry->loadIni($data);
        return $registry->toObject();
    }

    private static function loadXMLFile($file) {
        $xml = null;

        // Disable libxml errors and allow to fetch error information as needed
        libxml_use_internal_errors(true);

        if (is_file($file)) {
            // Try to load the xml file
            $xml = simplexml_load_file($file);
        }

        return $xml;
    }

    // Upgrade from JCE 1.5.x
    private static function upgradeLegacy() {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();

        $admin = JPATH_ADMINISTRATOR . '/components/com_jce';
        $site = JPATH_SITE . '/components/com_jce';

        //require_once($admin . '/helpers/parameter.php');
        // check for groups table / data
        if (self::checkTable('#__jce_groups') && self::checkTableContents('#__jce_groups')) {
            jimport('joomla.plugin.helper');

            // get plugin
            $plugin = JPluginHelper::getPlugin('editors', 'jce');
            // get JCE component
            $table = JTable::getInstance('component');
            $table->loadByOption('com_jce');
            // process params to JSON string
            $params = self::paramsToObject($table->params);
            // set params
            $table->params = json_encode(array('editor' => $params));
            // store
            $table->store();
            // get all groups data
            $query = 'SELECT * FROM #__jce_groups';
            $db->setQuery($query);
            $groups = $db->loadObjectList();

            // get all plugin data
            $query = 'SELECT id, name, icon FROM #__jce_plugins';
            $db->setQuery($query);
            $plugins = $db->loadAssocList('id');

            $map = array(
                'advlink' => 'link',
                'advcode' => 'source',
                'tablecontrols' => 'table',
                'cut,copy,paste' => 'clipboard',
                'paste' => 'clipboard',
                'search,replace' => 'searchreplace',
                'cite,abbr,acronym,del,ins,attribs' => 'xhtmlxtras',
                'styleprops' => 'style',
                'readmore,pagebreak' => 'article',
                'ltr,rtl' => 'directionality',
                'insertlayer,moveforward,movebackward,absolute' => 'layer'
            );

            if (self::createProfilesTable()) {
                foreach ($groups as $group) {
                    $row = JTable::getInstance('profiles', 'WFTable');

                    $rows = array();

                    // transfer row ids to names
                    foreach (explode(';', $group->rows) as $item) {
                        $icons = array();
                        foreach (explode(',', $item) as $id) {
                            // spacer
                            if ($id == '00') {
                                $icon = 'spacer';
                            } else {
                                if (isset($plugins[$id])) {
                                    $icon = $plugins[$id]['icon'];

                                    // map old icon names to new
                                    if (array_key_exists($icon, $map)) {
                                        $icon = $map[$icon];
                                    }
                                }
                            }
                            $icons[] = $icon;
                        }
                        $rows[] = implode(',', $icons);
                    }
                    // re-assign rows
                    $row->rows = implode(';', $rows);

                    $names = array('anchor');

                    // add lists
                    if (preg_match('#(numlist|bullist)#', $row->rows)) {
                        $names[] = 'lists';
                    }

                    // add charmap
                    if (strpos($row->rows, 'charmap') !== false) {
                        $names[] = 'charmap';
                    }

                    // transfer plugin ids to names
                    foreach (explode(',', $group->plugins) as $id) {
                        if (isset($plugins[$id])) {
                            $name = $plugins[$id]['name'];

                            // map old icon names to new
                            if (array_key_exists($name, $map)) {
                                $name = $map[$name];
                            }

                            $names[] = $name;
                        }
                    }
                    // re-assign plugins
                    $row->plugins = implode(',', $names);

                    // convert params to JSON
                    $params = self::paramsToObject($group->params);
                    $data = new StdClass();

                    // Add lists plugin
                    $buttons = array();

                    if (strpos($row->rows, 'numlist') !== false) {
                        $buttons[] = 'numlist';
                        // replace "numlist" with "lists"
                        $row->rows = str_replace('numlist', 'lists', $row->rows);
                    }

                    if (strpos($row->rows, 'bullist') !== false) {
                        $buttons[] = 'bullist';
                        // replace "bullist" with "lists"
                        if (strpos($row->rows, 'lists') === false) {
                            $row->rows = str_replace('bullist', 'lists', $row->rows);
                        }
                    }
                    // remove bullist and numlist
                    $row->rows = str_replace(array('bullist', 'numlist'), '', $row->rows);

                    // add lists buttons parameter
                    if (!empty($buttons)) {
                        $params->lists_buttons = $buttons;
                    }

                    // convert parameters
                    foreach ($params as $key => $value) {
                        $parts = explode('_', $key);

                        $node = array_shift($parts);

                        // special consideration for imgmanager_ext!!
                        if (strpos($key, 'imgmanager_ext_') !== false) {
                            $node = $node . '_' . array_shift($parts);
                        }

                        // convert some nodes
                        if (isset($map[$node])) {
                            $node = $map[$node];
                        }

                        $key = implode('_', $parts);

                        if ($value !== '') {
                            if (!isset($data->$node) || !is_object($data->$node)) {
                                $data->$node = new StdClass();
                            }
                            // convert Link parameters
                            if ($node == 'link' && $key != 'target') {
                                $sub = $key;
                                $key = 'links';

                                if (!isset($data->$node->$key)) {
                                    $data->$node->$key = new StdClass();
                                }

                                if (preg_match('#^(content|contacts|static|weblinks|menu)$#', $sub)) {
                                    if (!isset($data->$node->$key->joomlalinks)) {
                                        $data->$node->$key->joomlalinks = new StdClass();
                                        $data->$node->$key->joomlalinks->enable = 1;
                                    }
                                    $data->$node->$key->joomlalinks->$sub = $value;
                                } else {
                                    $data->$node->$key->$sub = new StdClass();
                                    $data->$node->$key->$sub->enable = 1;
                                }
                            } else {
                                $data->$node->$key = $value;
                            }
                        }
                    }
                    // re-assign params
                    $row->params = json_encode($data);

                    // re-assign other values
                    $row->name = $group->name;
                    $row->description = $group->description;
                    $row->users = $group->users;
                    $row->types = $group->types;
                    $row->components = $group->components;
                    $row->published = $group->published;
                    $row->ordering = $group->ordering;

                    // add area data
                    if ($row->name == 'Default') {
                        $row->area = 0;
                    }

                    if ($row->name == 'Front End') {
                        $row->area = 1;
                    }

                    if (self::checkTable('#__wf_profiles')) {
                        $name = $row->name;

                        // check for existing profile
                        $query = 'SELECT id FROM #__wf_profiles' . ' WHERE name = ' . $db->Quote($name);
                        $db->setQuery($query);
                        // create name copy if exists
                        while ($db->loadResult()) {
                            $name = JText::sprintf('WF_PROFILES_COPY_OF', $name);

                            $query = 'SELECT id FROM #__wf_profiles' . ' WHERE name = ' . $db->Quote($name);

                            $db->setQuery($query);
                        }
                        // set name
                        $row->name = $name;
                    }

                    if (!$row->store()) {
                        $app->enqueueMessage('Conversion of group data failed : ' . $row->name, 'error');
                    } else {
                        $app->enqueueMessage('Conversion of group data successful : ' . $row->name);
                    }

                    unset($row);
                }

                // If profiles table empty due to error, install profiles data
                if (!self::checkTableContents('#__wf_profiles')) {
                    self::installProfiles();
                } else {
                    // add Blogger profile
                    self::installProfile('Blogger');
                    // add Mobile profile
                    self::installProfile('Mobile');
                }
            } else {
                return false;
            }
            // Install profiles
        } else {
            self::installProfiles();
        }

        // Remove Plugins menu item
        $query = 'DELETE FROM #__components' . ' WHERE admin_menu_link = ' . $db->Quote('option=com_jce&type=plugins');

        $db->setQuery($query);
        $db->query();

        // Update Component Name
        $query = 'UPDATE #__components' . ' SET name = ' . $db->Quote('COM_JCE') . ' WHERE ' . $db->Quote('option') . '=' . $db->Quote('com_jce') . ' AND parent = 0';

        $db->setQuery($query);
        $db->query();

        // Fix links for other views and edit names
        $menus = array('install' => 'installer', 'group' => 'profiles', 'groups' => 'profiles', 'config' => 'config');

        $row = JTable::getInstance('component');

        foreach ($menus as $k => $v) {
            $query = 'SELECT id FROM #__components' . ' WHERE admin_menu_link = ' . $db->Quote('option=com_jce&type=' . $k);
            $db->setQuery($query);
            $id = $db->loadObject();

            if ($id) {
                $row->load($id);
                $row->name = $v;
                $row->admin_menu_link = 'option=com_jce&view=' . $v;

                if (!$row->store()) {
                    $mainframe->enqueueMessage('Unable to update Component Links for view : ' . strtoupper($v), 'error');
                }
            }
        }

        // remove old admin language files
        $folders = JFolder::folders(JPATH_ADMINISTRATOR . '/language', '.', false, true, array('.svn', 'CVS', 'en-GB'));

        foreach ($folders as $folder) {
            $name = basename($folder);
            $files = array($name . '.com_jce.ini', $name . '.com_jce.menu.ini', $name . '.com_jce.xml');
            foreach ($files as $file) {
                if (is_file($folder . '/' . $file)) {
                    @JFile::delete($folder . '/' . $file);
                }
            }
        }

        // remove old site language files
        $folders = JFolder::folders(JPATH_SITE . '/language', '.', false, true, array('.svn', 'CVS', 'en-GB'));

        foreach ($folders as $folder) {
            $files = JFolder::files($folder, '^' . basename($folder) . '\.com_jce([_a-z0-9]+)?\.(ini|xml)$', false, true);
            @JFile::delete($files);
        }

        // remove legacy admin folders
        $folders = array('cpanel', 'config', 'css', 'groups', 'plugins', 'img', 'installer', 'js');
        foreach ($folders as $folder) {
            if (is_dir($admin . '/' . $folder)) {
                @JFolder::delete($admin . '/' . $folder);
            }
        }

        // remove legacy admin files
        $files = array('editor.php', 'helper.php', 'updater.php');

        foreach ($files as $file) {
            if (is_file($admin . '/' . $file)) {
                @JFile::delete($admin . '/' . $file);
            }
        }

        // remove legacy admin folders
        $folders = array('controller', 'css', 'js');
        foreach ($folders as $folder) {
            if (is_dir($site . '/' . $folder)) {
                @JFolder::delete($site . '/' . $folder);
            }
        }

        // remove legacy admin files
        $files = array('popup.php');

        foreach ($files as $file) {
            if (is_file($site . '/' . $file)) {
                @JFile::delete($site . '/' . $file);
            }
        }


        if (!defined('JPATH_PLATFORM')) {
            // remove old plugin folder
            $path = JPATH_PLUGINS . '/editors';

            if (is_dir($path . '/jce')) {
                @JFolder::delete($path . '/jce');
            }
        }

        return true;
    }

    private static function installProfile($name) {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('COUNT(id)')->from('#__wf_profiles')->where('name = ' . $db->Quote($name));
        } else {
            $query = 'SELECT COUNT(id) FROM #__wf_profiles WHERE name = ' . $db->Quote($name);
        }

        $db->setQuery($query);
        $id = $db->loadResult();

        if (!$id) {
            // Blogger
            $file = JPATH_ADMINISTRATOR . '/components/com_jce/models/profiles.xml';

            $xml = self::loadXMLFile($file);

            if ($xml) {
                foreach ($xml->profiles->children() as $profile) {
                    if ((string) $profile->attributes()->name == $name) {
                        $row = JTable::getInstance('profiles', 'WFTable');

                        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/models/profiles.php');
                        $groups = WFModelProfiles::getUserGroups((int) $profile->children('area'));

                        foreach ($profile->children() as $item) {
                            switch ((string) $item->getName()) {
                                case 'types':
                                    $row->types = implode(',', $groups);
                                    break;
                                case 'area':
                                    $row->area = (int) $item;
                                    break;
                                case 'rows':
                                    $row->rows = (string) $item;
                                    break;
                                case 'plugins':
                                    $row->plugins = (string) $item;
                                    break;
                                default:
                                    $key = $item->getName();
                                    $row->$key = (string) $item;

                                    break;
                            }
                        }
                        $row->store();
                    }
                }
            }
        }
    }

    /**
     * Upgrade database tables and remove legacy folders
     * @return Boolean
     */
    private static function upgrade($version) {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $admin = JPATH_ADMINISTRATOR . '/components/com_jce';
        $site = JPATH_SITE . '/components/com_jce';

        // add tables path
        JTable::addIncludePath($admin . '/tables');

        // upgrade from 1.5.x to 2.0.0 (only in Joomla! 1.5)
        if (version_compare($version, '2.0.0', '<') && !defined('JPATH_PLATFORM')) {
            return self::upgradeLegacy();
        }// end JCE 1.5 upgrade
        // Remove folders
        $folders = array(
            // Remove JQuery folders from admin
            $admin . '/media/css/jquery',
            $admin . '/media/js/jquery',
            // remove plugin package folder
            $admin . '/plugin',
            // remove legend view
            $admin . '/views/legend',
            // remove controller from site
            $site . '/controller',
            // Remove plugin language files (incorporated into main language file)
            $site . '/editor/tiny_mce/plugins/article/langs',
            $site . '/editor/tiny_mce/plugins/imgmanager/langs',
            $site . '/editor/tiny_mce/plugins/link/langs',
            $site . '/editor/tiny_mce/plugins/searchreplace/langs',
            $site . '/editor/tiny_mce/plugins/style/langs',
            $site . '/editor/tiny_mce/plugins/table/langs',
            $site . '/editor/tiny_mce/plugins/xhtmlxtras/langs',
            // remove paste folder
            $site . '/editor/tiny_mce/plugins/paste',
            // remove jquery
            $site . '/editor/libraries/js/jquery',
            // remove browser extension
            $site . '/editor/extensions/browser',
            // remove browser langs
            $site . '/editor/tiny_mce/plugins/browser/langs',
            // remove packages
            $admin . '/packages',
            // remove tinymce langs
            $site . '/editor/tiny_mce/langs',
        );

        foreach ($folders as $folder) {
            if (JFolder::exists($folder)) {
                @JFolder::delete($folder);
            }
        }

        // Remove files
        $files = array(
            // remove javascript files from admin (moved to site)
            $admin . '/media/js/colorpicker.js',
            $admin . '/media/js/help.js',
            $admin . '/media/js/html5.js',
            $admin . '/media/js/select.js',
            $admin . '/media/js/tips.js',
            // remove legend.js
            $admin . '/media/js/legend.js',
            // remove css files from admin (moved to site)
            $admin . '/media/css/help.css',
            $admin . '/media/css/select.css',
            $admin . '/media/css/tips.css',
            // remove legend model
            $admin . '/models/legend.php',
            // remove extension adapter
            $admin . '/adapters/extension.php',
            // remove error class from site (moved to admin)
            $site . '/editor/libraries/classes/error.php',
            // remove popup file
            $site . '/popup.php',
            // remove anchor from theme (moved to plugins)
            $site . '/editor/tiny_mce/themes/advanced/css/anchor.css',
            $site . '/editor/tiny_mce/themes/advanced/css/js/anchor.js',
            $site . '/editor/tiny_mce/themes/advanced/css/tmpl/anchor.php',
            // remove redundant file
            $site . '/editor/tiny_mce/themes/advanced/css/skins/default/img/items.gif',
            // remove search files from file browser (renamed to filter)
            $site . '/editor/extensions/browser/css/search.css',
            $site . '/editor/extensions/browser/js/search.js',
            $site . '/editor/extensions/browser/search.php',
            // remove dilg language file from theme (incorporated into main dlg file)
            $site . '/editor/tiny_mce/themes/advanced/langs/en_dlg.js',
            // remove old jquery UI
            $site . '/editor/libraries/jquery/js/jquery-ui-1.9.0.custom.min.js',
            // remove "theme" files
            $site . '/editor/libraries/classes/theme.php',
            $site . '/editor/tiny_mce/themes/advanced/theme.php',
            // remove system helper
            $admin . '/helpers/system.php'
        );

        foreach ($files as $file) {
            if (JFile::exists($file)) {
                @JFile::delete($file);
            }
        }

        // 2.1 - Add visualblocks plugin
        if (version_compare($version, '2.1', '<')) {
            $profiles = self::getProfiles();
            $profile = JTable::getInstance('Profiles', 'WFTable');

            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    $profile->load($item->id);

                    if (strpos($profile->rows, 'visualblocks') === false) {
                        $profile->rows = str_replace('visualchars', 'visualchars,visualblocks', $profile->rows);
                    }
                    if (strpos($profile->plugins, 'visualblocks') === false) {
                        $profile->plugins = str_replace('visualchars', 'visualchars,visualblocks', $profile->plugins);
                    }

                    $profile->store();
                }
            }
        }

        // 2.1.1 - Add anchor plugin
        if (version_compare($version, '2.1.1', '<')) {
            $profiles = self::getProfiles();
            $profile = JTable::getInstance('Profiles', 'WFTable');

            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    $profile->load($item->id);

                    // add anchor to end of plugins list
                    if (strpos($profile->rows, 'anchor') !== false) {
                        $profile->plugins .= ',anchor';
                    }

                    $profile->store();
                }
            }
        }

        // 2.2.1 - Add "Blogger" profile
        if (version_compare($version, '2.2.1', '<')) {
            self::installProfile('Blogger');
        }

        // 2.2.1 to 2.2.5 - Remove K2Links partial install
        if (version_compare($version, '2.2.1', '>') && version_compare($version, '2.2.5', '<')) {
            $path = $site . '/editor/extensions/links';

            if (is_file($path . '/k2links.php') && is_file($path . '/k2links.xml') && !is_dir($path . '/k2links')) {
                @JFile::delete($path . '/k2links.php');
                @JFile::delete($path . '/k2links.xml');
            }
        }

        // replace some profile row items
        if (version_compare($version, '2.2.8', '<')) {
            $profiles = self::getProfiles();
            $profile = JTable::getInstance('Profiles', 'WFTable');

            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    $profile->load($item->id);

                    $profile->rows = str_replace('paste', 'clipboard', $profile->rows);
                    $profile->plugins = str_replace('paste', 'clipboard', $profile->plugins);

                    $data = json_decode($profile->params, true);

                    // swap paste data to 'clipboard'
                    if ($data && array_key_exists('paste', $data)) {
                        $params = array();

                        // add 'paste_' prefix
                        foreach ($data['paste'] as $k => $v) {
                            $params['paste_' . $k] = $v;
                        }

                        // remove paste parameters
                        unset($data['paste']);

                        // assign new params to clipboard
                        $data['clipboard'] = $params;
                    }

                    $profile->params = json_encode($data);

                    $profile->store();
                }
            }
        }

        if (version_compare($version, '2.3.0beta', '<')) {
            // add Mobile profile
            self::installProfile('Mobile');
        }

        if (version_compare($version, '2.2.9', '<') || version_compare($version, '2.3.0beta3', '<')) {
            $profiles = self::getProfiles();
            $profile = JTable::getInstance('Profiles', 'WFTable');

            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    $profile->load($item->id);

                    $buttons = array('buttons' => array());

                    if (strpos($profile->rows, 'numlist') !== false) {
                        $buttons['buttons'][] = 'numlist';

                        $profile->rows = str_replace('numlist', 'lists', $profile->rows);
                    }

                    if (strpos($profile->rows, 'bullist') !== false) {
                        $buttons['buttons'][] = 'bullist';

                        if (strpos($profile->rows, 'lists') === false) {
                            $profile->rows = str_replace('bullist', 'lists', $profile->rows);
                        }
                    }
                    // remove bullist and numlist
                    $profile->rows = str_replace(array('bullist', 'numlist'), '', $profile->rows);
                    // replace multiple commas with a single one
                    $profile->rows = preg_replace('#,+#', ',', $profile->rows);
                    // fix rows
                    $profile->rows = str_replace(',;', ';', $profile->rows);

                    if (!empty($buttons['buttons'])) {
                        $profile->plugins .= ',lists';

                        $data = json_decode($profile->params, true);
                        $data['lists'] = $buttons;

                        $profile->params = json_encode($data);

                        $profile->store();
                    }
                }
            }
        }
        // transfer charmap to a plugin
        if (version_compare($version, '2.3.2', '<')) {
            $profiles = self::getProfiles();
            $table = JTable::getInstance('Profiles', 'WFTable');

            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    $table->load($item->id);

                    if (strpos($table->rows, 'charmap') !== false) {
                        $table->plugins .= ',charmap';
                        $table->store();
                    }
                }
            }
        }

        return true;
    }

    private static function getProfiles() {
        $db = JFactory::getDBO();

        if (is_object($query)) {
            $query->select('id')->from('#__wf_profiles');
        } else {
            $query = 'SELECT id FROM #__wf_profiles';
        }

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    private static function createProfilesTable() {
        include_once (dirname(__FILE__) . '/includes/base.php');
        include_once (dirname(__FILE__) . '/models/profiles.php');

        $profiles = new WFModelProfiles();

        if (method_exists($profiles, 'createProfilesTable')) {
            return $profiles->createProfilesTable();
        }

        return false;
    }

    private static function installProfiles() {
        include_once (dirname(__FILE__) . '/includes/base.php');
        include_once (dirname(__FILE__) . '/models/profiles.php');

        $profiles = new WFModelProfiles();

        if (method_exists($profiles, 'installProfiles')) {
            return $profiles->installProfiles();
        }

        return false;
    }

    /**
     * Install additional packages
     * @return Array or false
     * @param object $path[optional] Path to package folder
     */
    private static function installPackages($source) {
        jimport('joomla.installer.installer');

        $db = JFactory::getDBO();

        $result = '';

        JTable::addIncludePath(JPATH_LIBRARIES . '/joomla/database/table');

        $packages = array(
            'editor' => array('jce'),
            'quickicon' => array('jcefilebrowser'),
            'module' => array('mod_jcefilebrowser')
        );

        foreach ($packages as $folder => $element) {
            // Joomla! 2.5
            if (defined('JPATH_PLATFORM')) {
                if ($folder == 'module') {
                    continue;
                }
                // Joomla! 1.5  
            } else {
                if ($folder == 'quickicon') {
                    continue;
                }
            }

            $installer = new JInstaller();
            $installer->setOverwrite(true);

            if ($installer->install($source . '/' . $folder)) {

                if (method_exists($installer, 'loadLanguage')) {
                    $installer->loadLanguage();
                }

                if ($installer->message) {
                    $result .= '<li class="success">' . JText::_($installer->message, $installer->message) . '</li>';
                }

                // enable quickicon
                if ($folder == 'quickicon') {
                    $plugin = JTable::getInstance('extension');

                    foreach ($element as $item) {
                        $id = $plugin->find(array('type' => 'plugin', 'folder' => $folder, 'element' => $item));

                        $plugin->load($id);
                        $plugin->publish();
                    }
                }

                // enable module
                if ($folder == 'module') {
                    $module = JTable::getInstance('module');

                    $id = self::getModule('mod_jcefilebrowser');

                    $module->load($id);
                    $module->position = 'icon';
                    $module->ordering = 100;
                    $module->published = 1;
                    $module->store();
                }

                // rename editor manifest
                if ($folder == 'editor') {
                    $manifest = $installer->getPath('manifest');

                    if (basename($manifest) == 'legacy.xml') {
                        // rename legacy.xml to jce.xml
                        JFile::move($installer->getPath('extension_root') . '/' . basename($manifest), $installer->getPath('extension_root') . '/jce.xml');
                    }
                }

                // add index files
                self::addIndexfiles(array($installer->getPath('extension_root')));
            } else {
                $result .= '<li class="error">' . JText::_($installer->message, $installer->message) . '</li>';
            }
        }

        return $result;
    }

    private static function getModule($name) {
        // Joomla! 2.5
        if (defined('JPATH_PLATFORM')) {
            $module = JTable::getInstance('extension');
            return $module->find(array('type' => 'module', 'element' => $name));

            // Joomla! 1.5    
        } else {
            $db = JFactory::getDBO();
            $query = 'SELECT id FROM #__modules' . ' WHERE module = ' . $db->Quote($name);

            $db->setQuery($query);
            return $db->loadResult();
        }
    }

    private static function getPlugin($folder, $element) {
        // Joomla! 2.5
        if (defined('JPATH_PLATFORM')) {
            $plugin = JTable::getInstance('extension');
            return $plugin->find(array('type' => 'plugin', 'folder' => $folder, 'element' => $element));
            // Joomla! 1.5    
        } else {
            $plugin = JTable::getInstance('plugin');

            $db = JFactory::getDBO();
            $query = 'SELECT id FROM #__plugins' . ' WHERE folder = ' . $db->Quote($folder) . ' AND element = ' . $db->Quote($element);

            $db->setQuery($query);
            return $db->loadResult();
        }
    }

    /**
     * Uninstall the editor
     * @return boolean
     */
    private static function removePackages() {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();

        jimport('joomla.module.helper');
        jimport('joomla.installer.installer');

        $plugins = array(
            'editors' => array('jce'),
            'quickicon' => array('jcefilebrowser')
        );

        $modules = array('mod_jcefilebrowser');

        // items to remove
        $items = array(
            'plugin' => array(),
            'module' => array()
        );

        foreach ($plugins as $folder => $elements) {
            foreach ($elements as $element) {
                $item = self::getPlugin($folder, $element);

                if ($item) {
                    $items['plugin'][] = $item;
                }
            }
        }

        foreach ($modules as $module) {
            $item = self::getModule($module);

            if ($item) {
                $items['module'][] = $item;
            }
        }

        foreach ($items as $type => $extensions) {
            if ($extensions) {
                foreach ($extensions as $id) {
                    $installer = new JInstaller();
                    $installer->uninstall($type, $id);
                    $app->enqueueMessage($installer->message);
                }
            }
        }
    }

    private static function addIndexfiles($paths) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        // get the base file
        $file = JPATH_ADMINISTRATOR . '/components/com_jce/index.html';

        if (is_file($file)) {

            foreach ((array) $paths as $path) {
                if (is_dir($path)) {
                    // admin component
                    $folders = JFolder::folders($path, '.', true, true);

                    foreach ($folders as $folder) {
                        JFile::copy($file, $folder . '/' . basename($file));
                    }
                }
            }
        }
    }

    private static function legacyCleanup() {
        $db = JFactory::getDBO();

        $query = 'DROP TABLE IF EXISTS #__jce_groups';
        $db->setQuery($query);
        $db->query();

        $query = 'DROP TABLE IF EXISTS #__jce_plugins';
        $db->setQuery($query);
        $db->query();

        $query = 'DROP TABLE IF EXISTS #__jce_extensions';
        $db->setQuery($query);
        $db->query();
    }

    private static function checkTable($table) {
        $db = JFactory::getDBO();

        $tables = $db->getTableList();

        if (!empty($tables)) {
            // swap array values with keys, convert to lowercase and return array keys as values
            $tables = array_keys(array_change_key_case(array_flip($tables)));
            $app = JFactory::getApplication();
            $match = str_replace('#__', strtolower($app->getCfg('dbprefix', '')), $table);

            return in_array($match, $tables);
        }

        // try with query
        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('COUNT(id)')->from($table);
        } else {
            $query = 'SELECT COUNT(id) FROM ' . $table;
        }

        $db->setQuery($query);

        return $db->query();
    }

    /**
     * Check table contents
     * @return integer
     * @param string $table Table name
     */
    private static function checkTableContents($table) {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('COUNT(id)')->from($table);
        } else {
            $query = 'SELECT COUNT(id) FROM ' . $table;
        }

        $db->setQuery($query);

        return $db->loadResult();
    }

    private static function checkTableColumn($table, $column) {
        $db = JFactory::getDBO();

        // use built in function
        if (method_exists($db, 'getTableColumns')) {
            $fields = $db->getTableColumns($table);
        } else {
            $db->setQuery('DESCRIBE ' . $table);
            $fields = $db->loadResultArray();

            // we need to check keys not values
            $fields = array_flip($fields);
        }

        return array_key_exists($column, $fields);
    }

}

?>
