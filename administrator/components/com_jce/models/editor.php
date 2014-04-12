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

wfimport('admin.classes.model');
wfimport('admin.classes.text');
wfimport('admin.helpers.xml');
wfimport('admin.helpers.extension');
wfimport('editor.libraries.classes.token');
wfimport('editor.libraries.classes.editor');
wfimport('editor.libraries.classes.language');

jimport('joomla.application.component.model');

class WFModelEditor extends WFModelBase {

    /**
     * Editor version
     * @var string
     */
    private static $version = null;

    /**
     * Profile object
     *
     * @var    object
     */
    private $profile = null;

    /**
     * Array of linked scripts
     *
     * @var    array
     */
    protected $scripts = array();

    /**
     * Array of linked style sheets
     *
     * @var    array
     */
    protected $stylesheets = array();

    /**
     * Array of included style declarations
     *
     * @var    array
     */
    protected $styles = array();

    /**
     * Array of scripts placed in the header
     *
     * @var    array
     */
    protected $javascript = array();

    private function addScript($url) {
        $this->scripts[] = $url;
    }

    private function addStyleSheet($url) {
        $this->stylesheets[] = $url;
    }

    private function addScriptDeclaration($text) {
        $this->javascript[] = $text;
    }

    private function addStyleDeclaration($text) {
        $this->styles[] = $text;
    }

    public function __construct() {
        $wf = WFEditor::getInstance();

        // set language
        $this->language = WFLanguage::getCode();
        // set profile
        $this->profile = $wf->getProfile();
    }

    public function buildEditor() {
        // get document
        $document = JFactory::getDocument();

        // get an editor instance
        $wf = WFEditor::getInstance();

        // create token
        $token = WFToken::getToken();

        // get current component
        $option = JRequest::getCmd('option');
        $component = WFExtensionHelper::getComponent(null, $option);

        // get default settings
        $settings = $this->getEditorSettings();

        // set default component id
        $component_id = 0;
        $component_id = isset($component->extension_id) ? $component->extension_id : ($component->id ? $component->id : 0);

        $version = self::getVersion();

        // settings array for jce, tinymce etc
        $init = array();

        // if a profile is set
        if (is_object($this->profile)) {
            jimport('joomla.filesystem.folder');

            $settings = array_merge($settings, array('theme' => 'advanced', 'component_id' => $component_id, 'plugins' => $this->getPlugins()), $this->getToolbar());

            // Theme and skins
            $theme = array(
                'toolbar_location' => array('top', 'top', 'string'),
                'toolbar_align' => array('left', 'left', 'string'),
                'statusbar_location' => array('bottom', 'bottom', 'string'),
                'path' => array(1, 1, 'boolean'),
                'resizing' => array(1, 0, 'boolean'),
                'resize_horizontal' => array(1, 1, 'boolean'),
                'resizing_use_cookie' => array(1, 1, 'boolean')
            );

            // set rows key to pass to plugin config
            $settings['rows'] = $this->profile->rows;

            foreach ($theme as $k => $v) {
                $settings['theme_advanced_' . $k] = $wf->getParam('editor.' . $k, $v[0], $v[1], $v[2]);
            }

            if (!$wf->getParam('editor.use_cookies', 1)) {
                $settings['theme_advanced_resizing_use_cookie'] = false;
            }

            $settings['width'] = $wf->getParam('editor.width');
            $settings['height'] = $wf->getParam('editor.height');

            // 'Look & Feel'

            $skin = explode('.', $wf->getParam('editor.toolbar_theme', 'default', 'default'));
            $settings['skin'] = $skin[0];
            $settings['skin_variant'] = isset($skin[1]) ? $skin[1] : '';

            // get body class if any
            $body_class = $wf->getParam('editor.body_class', '');
            // check for editor reset
            $content_reset = $wf->getParam('editor.content_style_reset', $wf->getParam('editor.highcontrast', 0)) == 1 ? 'mceContentReset' : '';
            // combine body class and reset
            $settings['body_class'] = trim($body_class . ' ' . $content_reset);
            // set body id
            $settings['body_id'] = $wf->getParam('editor.body_id', '');

            // get stylesheets
            $stylesheets = (array) self::getStyleSheets();
            // set stylesheets as string
            $settings['content_css'] = implode(',', $stylesheets);

            // Editor Toggle
            $settings['toggle'] = $wf->getParam('editor.toggle', 1, 1);
            $settings['toggle_label'] = htmlspecialchars($wf->getParam('editor.toggle_label', '[Toggle Editor]', '[Toggle Editor]'));
            $settings['toggle_state'] = $wf->getParam('editor.toggle_state', 1, 1);
        }// end profile
        // set compression states
        $compress = array('javascript' => intval($wf->getParam('editor.compress_javascript', 0)), 'css' => intval($wf->getParam('editor.compress_css', 0)));

        // set compression
        if ($compress['css']) {
            $this->addStyleSheet(JURI::base(true) . '/index.php?option=com_jce&view=editor&layout=editor&task=pack&type=css&component_id=' . $component_id . '&' . $token . '=1');
        } else {
            // CSS
            $this->addStyleSheet($this->getURL(true) . '/libraries/css/editor.css');

            //$this->addStyleSheet($this->getURL(true) . '/libraries/bootstrap/css/bootstrap.css?version=' . $version);
            // get plugin styles
            $this->getPluginStyles($settings);
        }

        // set compression
        if ($compress['javascript']) {
            $this->addScript(JURI::base(true) . '/index.php?option=com_jce&view=editor&layout=editor&task=pack&component_id=' . $component_id . '&' . $token . '=1');
        } else {
            $this->addScript($this->getURL(true) . '/tiny_mce/tiny_mce.js');
            // Editor
            $this->addScript($this->getURL(true) . '/libraries/js/editor.js');

            if (array_key_exists('language_load', $settings)) {
                // language
                $this->addScript(JURI::base(true) . '/index.php?option=com_jce&view=editor&layout=editor&task=loadlanguages&lang=' . $this->language . '&component_id=' . $component_id . '&' . $token . '=1');
            }
        }

        // Get all optional plugin configuration options
        $this->getPluginConfig($settings);

        // remove 'rows' key from $settings
        unset($settings['rows']);

        // pass compresison states to settings
        $settings['compress'] = json_encode($compress);

        //Other - user specified
        $userParams = $wf->getParam('editor.custom_config', '');
        $baseParams = array('mode', 'cleanup_callback', 'save_callback', 'file_browser_callback', 'urlconverter_callback', 'onpageload', 'oninit', 'editor_selector');

        if ($userParams) {
            $userParams = explode(';', $userParams);
            foreach ($userParams as $userParam) {
                $keys = explode(':', $userParam);
                if (!in_array(trim($keys[0]), $baseParams)) {
                    $settings[trim($keys[0])] = count($keys) > 1 ? trim($keys[1]) : '';
                }
            }
        }

        // check for language files
        $this->checkLanguages($settings);

        $output = "";
        $i = 1;

        foreach ($settings as $k => $v) {
            // If the value is an array, implode!
            if (is_array($v)) {
                $v = ltrim(implode(',', $v), ',');
            }
            // Value must be set
            if ($v !== '') {
                // objects or arrays or functions or regular expression
                if (preg_match('/(\[[^\]*]\]|\{[^\}]*\}|function\([^\}]*\}|^#(.*)#$)/', $v)) {
                    // replace hash delimiters with / for javascript regular expression
                    $v = preg_replace('@^#(.*)#$@', '/$1/', $v);
                }
                // boolean
                else if (is_bool($v) === true) {
                    $v = $v ? 'true' : 'false';
                }
                // stringified booleans
                else if ($v === "true" || $v === "false") {
                    $v = $v === "true" ? 'true' : 'false';
                }
                // anything that is not solely an integer
                else if (!is_numeric($v)) {
                    if (strpos($v, '"') === 0) {
                        $v = '"' . trim($v, '"') . '"';
                    } else {
                        $v = '"' . str_replace('"', '\"', $v) . '"';
                    }
                }

                $output .= "\t\t\t" . $k . ": " . $v . "";
                if ($i < count($settings)) {
                    $output .= ",\n";
                }
            }
            // Must have 3 rows, even if 2 are blank!
            if (preg_match('/theme_advanced_buttons([1-3])/', $k) && $v == '') {
                $output .= "\t\t\t" . $k . ": \"\"";
                if ($i < count($settings)) {
                    $output .= ",\n";
                }
            }
            $i++;
        }

        $tinymce = "{\n";
        $tinymce .= preg_replace('/,?\n?$/', '', $output) . "
        }";

        $init[] = $tinymce;

        $this->addScriptDeclaration("\n\t\ttry{WFEditor.init(" . implode(',', $init) . ");}catch(e){console.debug(e);}\n");

        if (is_object($this->profile)) {
            if ($wf->getParam('editor.callback_file')) {
                $this->addScript(JURI::root(true) . '/' . $wf->getParam('editor.callback_file'));
            }
            // add callback file if exists
            if (is_file(JPATH_SITE . '/media/jce/js/editor.js')) {
                $this->addScript(JURI::root(true) . '/media/jce/js/editor.js');
            }

            // add custom editor.css if exists
            if (is_file(JPATH_SITE . '/media/jce/css/editor.css')) {
                $this->addStyleSheet(JURI::root(true) . '/media/jce/css/editor.css');
            }
        }

        return $this->getOutput();
    }

    private function getOutput() {
        $document = JFactory::getDocument();

        $end = $document->_getLineEnd();
        $tab = $document->_getTab();

        $version = self::getVersion();

        $output = '';

        foreach ($this->stylesheets as $stylesheet) {

            // don't add hash to dynamic php url
            if (strpos($stylesheet, 'index.php') === false) {
                $version = md5(basename($stylesheet) . $version);

                if (strpos($stylesheet, '?') === false) {
                    $stylesheet .= '?' . $version;
                } else {
                    $stylesheet .= '&' . $version;
                }
            }

            $output .= $tab . '<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" />' . $end;
        }

        foreach ($this->scripts as $script) {

            // don't add hash to dynamic php url
            if (strpos($script, 'index.php') === false) {

                $version = md5(basename($script) . $version);

                if (strpos($script, '?') === false) {
                    $script .= '?' . $version;
                } else {
                    $script .= '&' . $version;
                }
            }
            $output .= $tab . '<script data-cfasync="false" type="text/javascript" src="' . $script . '"></script>' . $end;
        }

        foreach ($this->javascript as $script) {
            $output .= $tab . '<script data-cfasync="false" type="text/javascript">' . $script . '</script>' . $end;
        }

        foreach ($this->styles as $style) {
            $output .= $tab . '<style type="text/css">' . $style . '</style>' . $end;
        }

        return $output;
    }

    /**
     * Check the current language pack exists and is complete
     * @param array $settings Settings array
     * @return void
     */
    private function checkLanguages(&$settings) {
        $plugins = array();
        $language = $settings['language'];

        // only if languages are loaded and not english
        if (array_key_exists('language_load', $settings) === false && $language != 'en') {
            jimport('joomla.filesystem.file');

            // check main languages and reset to english
            if (!JFile::exists(WF_EDITOR . '/tiny_mce/langs/' . $language . '.js') || !JFile::exists(WF_EDITOR_THEMES . '/advanced/langs/' . $language . '.js')) {
                $settings['language'] = 'en';

                return;
            }

            foreach ((array) $settings['plugins'] as $plugin) {
                $path = WF_EDITOR_PLUGINS . '/' . $plugin;

                // if english file exists then the installed language file should too 
                if (JFile::exists($path . '/langs/en.js') && !JFile::exists($path . '/langs/' . $language . '.js')) {
                    $plugins[] = $plugin;
                }
            }
        }

        $settings['skip_plugin_languages'] = $plugins;
    }

    /**
     * Get the current version from the editor manifest
     * @return Version
     */
    private static function getVersion() {
        if (!isset(self::$version)) {
            $xml = WFXMLHelper::parseInstallManifest(JPATH_ADMINISTRATOR . '/components/com_jce/jce.xml');

            // return cleaned version number or date
            self::$version = preg_replace('/[^0-9a-z]/i', '', $xml['version']);

            if (!self::$version) {
                self::$version = date('Y-m-d', strtotime('today'));
            }
        }

        return self::$version;
    }

    /**
     * Get default settings array
     * @return array
     */
    public function getEditorSettings() {
        wfimport('editor.libraries.classes.token');

        $wf = WFEditor::getInstance();

        $language = JFactory::getLanguage();

        $settings = array(
            'token' => WFToken::getToken(),
            'etag' => md5($this->getVersion()),
            'base_url' => JURI::root(),
            'language' => $this->language,
            //'language_load'		=> false,
            'directionality' => $language->isRTL() ? 'rtl' : 'ltr',
            'theme' => 'none',
            'plugins' => ''
        );

        $settings['language_load'] = false;

        return $settings;
    }

    /**
     * Return a list of icons for each JCE editor row
     *
     * @access public
     * @param string  The number of rows
     * @return The row array
     */
    private function getToolbar() {
        wfimport('admin.models.plugins');
        $model = new WFModelPlugins();

        $wf = WFEditor::getInstance();
        $rows = array('theme_advanced_buttons1' => '', 'theme_advanced_buttons2' => '', 'theme_advanced_buttons3' => '');

        // we need a profile object and some defined rows
        if (!is_object($this->profile) || empty($this->profile->rows)) {
            return $rows;
        }

        // get plugins
        $plugins = $model->getPlugins();
        // get core commands
        $commands = $model->getCommands();

        // merge plugins and commands
        $icons = array_merge($commands, $plugins);
        // create an array of rows
        $lists = explode(';', $this->profile->rows);

        // backwards compatability map
        $map = array(
            'paste'     => 'clipboard',
            'spacer'    => '|'
        );

        $x = 0;
        for ($i = 1; $i <= count($lists); $i++) {
            $buttons = array();
            $items = explode(',', $lists[$x]);

            foreach ($items as $item) {
                // set the plugin/command name
                $name = $item;

                // map legacy values etc.
                if (array_key_exists($item, $map)) {
                    $item = $map[$item];
                }

                // check if button should be in toolbar
                if ($item !== "|") {
                    if (array_key_exists($item, $icons) === false) {
                        continue;
                    }
                    
                    // assign icon
                    $item = $icons[$item]->icon;
                }

                // check for custom plugin buttons
                if (array_key_exists($name, $plugins)) {
                    $custom = $wf->getParam($name . '.buttons');

                    if (!empty($custom)) {
                        $custom = array_filter((array) $custom);

                        if (empty($custom)) {
                            $item = "";
                        } else {
                            $a = array();

                            foreach (explode(',', $item) as $s) {
                                if (in_array($s, $custom) || $s == "|") {
                                    $a[] = $s;
                                }
                            }
                            $item = implode(',', $a);
                            // remove leading or trailing |
                            $item = trim($item, '|');
                        }
                    }
                }

                if (!empty($item)) {
                    // remove double |
                    $item = preg_replace('#(\|,)+#', '|,', $item);

                    $buttons[] = $item;
                }
            }

            if (!empty($buttons)) {
                $rows['theme_advanced_buttons' . $i] = implode(',', $buttons);
            }

            $x++;
        }

        return $rows;
    }

    /**
     * Return a list of published JCE plugins
     *
     * @access public
     * @return string list
     */
    public function getPlugins() {
        jimport('joomla.filesystem.file');

        static $plugins;

        if (is_object($this->profile)) {
            if (!is_array($plugins)) {
                $wf = WFEditor::getInstance();

                $plugins = explode(',', $this->profile->plugins);
                $plugins = array_unique(array_merge(array('autolink', 'cleanup', 'core', 'code', 'colorpicker', 'upload', 'format'), $plugins));

                // add advlists plugin if lists are loaded
                if (in_array('lists', $plugins)) {
                    $plugins[] = 'advlist';
                }

                // Load wordcount if path is enabled
                if ($wf->getParam('editor.path', 1)) {
                    $plugins[] = 'wordcount';
                }

                // add legacy "charmap"
                if (in_array('charmap', $plugins) === false && strpos($this->profile->rows, 'charmap') !== true) {
                    $plugins[] = 'charmap';
                }

                foreach ($plugins as $k => $v) {
                    // check plugin is correctly installed and is a tinymce plugin, ie: it has an editor_plugin.js file
                    if (!JFile::exists(WF_EDITOR_PLUGINS . '/' . $v . '/editor_plugin.js')) {
                        unset($plugins[$k]);
                    }
                }

                // remove empty values
                $plugins = array_filter($plugins);
            }
        }

        return $plugins;
    }

    /**
     * Get all loaded plugins config options
     *
     * @access      public
     * @param array   $settings passed by reference
     */
    private function getPluginConfig(&$settings) {
        $plugins = $settings['plugins'];

        if ($plugins && is_array($plugins)) {
            foreach ($plugins as $plugin) {
                $file = WF_EDITOR_PLUGINS . '/' . $plugin . '/classes/config.php';

                if (file_exists($file)) {
                    require_once ($file);
                    // Create class name
                    $classname = 'WF' . ucfirst($plugin) . 'PluginConfig';

                    // Check class and method
                    if (class_exists($classname) && method_exists($classname, 'getConfig')) {
                        call_user_func_array(array($classname, 'getConfig'), array(&$settings));
                    }
                }
            }
        }
    }

    /**
     * Get all loaded plugins styles
     *
     * @access      public
     * @param array   $settings passed by reference
     */
    private function getPluginStyles($settings) {
        $plugins = $settings['plugins'];

        if ($plugins && is_array($plugins)) {
            foreach ($plugins as $plugin) {
                $file = WF_EDITOR_PLUGINS . '/' . $plugin . '/classes/config.php';

                if (file_exists($file)) {
                    require_once ($file);
                    // Create class name
                    $classname = 'WF' . ucfirst($plugin) . 'PluginConfig';

                    // Check class and method
                    if (class_exists($classname) && method_exists($classname, 'getStyles')) {
                        call_user_func(array($classname, 'getStyles'));
                    }
                }
            }
        }
    }

    /**
     * Remove keys from an array
     *
     * @return $array by reference
     * @param arrau $array Array to edit
     * @param array $keys Keys to remove
     */
    public function removeKeys(&$array, $keys) {
        if (!is_array($keys)) {
            $keys = array($keys);
        }

        $array = array_diff($array, $keys);
    }

    /**
     * Add keys to an array
     *
     * @return The string list with added key or the key
     * @param string  The array
     * @param string  The keys to add
     */
    public function addKeys(&$array, $keys) {
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        $array = array_unique(array_merge($array, $keys));
    }

    /**
     * Get a list of editor font families
     *
     * @return string font family list
     * @param string $add Font family to add
     * @param string $remove Font family to remove
     * 
     * Deprecated in 2.3.4
     */
    public function getEditorFonts() {
        return "";
    }

    /**
     * Return the current site template name
     *
     * @access public
     */
    private static function getSiteTemplates() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $id = 0;

        if ($app->isSite()) {
            $menus = $app->getMenu();
            $menu = $menus->getActive();

            if ($menu) {
                $id = isset($menu->template_style_id) ? $menu->template_style_id : $menu->id;
            }
        }

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('id, template')->from('#__template_styles')->where(array("client_id = 0", "home = '1'"));
        } else {
            $query = 'SELECT menuid as id, template'
                    . ' FROM #__templates_menu'
                    . ' WHERE client_id = 0';
        }

        $db->setQuery($query);
        $templates = $db->loadObjectList();

        $assigned = array();

        foreach ($templates as $template) {
            if ($id == $template->id) {
                array_unshift($assigned, $template->template);
            } else {
                $assigned[] = $template->template;
            }
        }

        // return templates
        return $assigned;
    }

    private static function getStyleSheetsList($absolute = false) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        // use system template as default
        $url = 'templates/system/css';
        // use 'system' as default
        $template = 'system';
        // use system editor.css as default
        $styles = 'templates/system/css/editor.css';
        // stylesheets
        $stylesheets = array();
        // files
        $files = array();

        // get templates
        $templates = self::getSiteTemplates();

        foreach ($templates as $item) {
            // Template CSS
            $path = JPATH_SITE . '/templates/' . $item;

            // get the first path that exists
            if (is_dir($path)) {
                // assign template
                $template = $item;
                // assign url
                $url = "templates/" . $template . "/css";
                break;
            }
        }
        wfimport('editor.libraries.classes.editor');
        $wf = WFEditor::getInstance();

        $global = intval($wf->getParam('editor.content_css', 1));
        $profile = intval($wf->getParam('editor.profile_content_css', 2));

        switch ($global) {
            // Custom template css files
            case 0 :
                // use getParam so result is cleaned
                $global_custom = $wf->getParam('editor.content_css_custom', '');
                // Replace $template variable with site template name
                $global_custom = str_replace('$template', $template, $global_custom);
                // explode to array
                $files = explode(',', $global_custom);
                break;
            // Template css (template.css or template_css.css)
            case 1 :
                // Joomla! 1.5 standard
                $file = 'template.css';
                $css = array();

                if (JFolder::exists($path)) {
                    $css = JFolder::files($path, '(base|core|template|template_css)\.(css|less)$', false, true);
                }

                if (!empty($css)) {
                    // use the first result
                    $file = $css[0];
                }

                // check for php version
                if (JFile::exists($file . '.php')) {
                    $file = $file . '.php';
                }

                $files[] = $url . '/' . basename($file);
                break;
            // Nothing, use system default
            case 2 :
                $files[] = 'templates/system/css/editor.css';
                break;
        }

        switch ($profile) {
            // add to global config value
            case 0 :
            case 1 :
                $profile_custom = $wf->getParam('editor.profile_content_css_custom', '');
                // Replace $template variable with site template name (defaults to 'system')
                $profile_custom = str_replace('$template', $template, $profile_custom);
                // explode to array
                $profile_custom = explode(',', $profile_custom);
                // add to existing list
                if ($profile == 0) {
                    $files = array_merge($files, $profile_custom);
                    // overwrite global config value	
                } else {
                    $files = (array) $profile_custom;
                }
                break;
            // inherit global config value
            case 2 :
                break;
        }
        // remove duplicates
        $files = array_unique($files);

        // get the root directory
        $root = $absolute ? JPATH_SITE : JURI::root(true);

        // check for existence of each file and make array of stylesheets
        foreach ($files as $file) {
            // remove leading slash
            $file = ltrim($file, '/');

            if ($file && JFile::exists(JPATH_SITE . '/' . $file)) {

                $etag = "";

                // add etag
                if ($absolute === false) {
                    // create hash
                    $etag = '?' . md5_file(JPATH_SITE . '/' . $file);
                }

                $stylesheets[] = $root . '/' . $file . $etag;
            }
        }

        // remove duplicates
        $stylesheets = array_unique($stylesheets);

        return $stylesheets;
    }

    /**
     * Get an array of stylesheets used by the editor.
     * References the WFEditor class.
     * If the list contains any LESS stylesheets, the list is returned as a URL to compile
     * @return string
     */
    public static function getStyleSheets() {
        $stylesheets = self::getStyleSheetsList();

        // check for less files in the array
        $less = preg_grep('#\.less$#', $stylesheets);

        // process less files etc.
        if (!empty($less)) {
            // create token
            $token = WFToken::getToken();
            $version = self::getVersion();

            return JURI::base(true) . '/index.php?option=com_jce&view=editor&layout=editor&task=compileless&' . $token . '=1';
        }

        return $stylesheets;
    }

    /**
     * Get the URL of the editor
     * @param boolean $relative
     * @return string
     */
    private function getURL($relative = false) {
        if ($relative) {
            return JURI::root(true) . '/components/com_jce/editor';
        }

        return JURI::root() . 'components/com_jce/editor';
    }

    /**
     * Pack / compress editor files
     */
    public function pack() {
        // check token
        WFToken::checkToken('GET') or die('RESTRICTED');

        wfimport('admin.classes.packer');
        wfimport('admin.classes.language');

        $wf = WFEditor::getInstance();
        $type = JRequest::getWord('type', 'javascript');

        // javascript
        $packer = new WFPacker(array('type' => $type));

        $themes = 'none';
        $plugins = array();

        $suffix = JRequest::getWord('suffix', '');
        $component_id = JRequest::getInt('component_id', 0);

        // if a profile is set
        if ($this->profile) {
            $themes = 'advanced';
            $plugins = $this->getPlugins();
        }

        $themes = explode(',', $themes);

        // toolbar theme
        $toolbar = explode('.', $wf->getParam('editor.toolbar_theme', 'default'));

        switch ($type) {
            case 'language' :
                $files = array();

                $data = $this->loadLanguages(array(), array(), '(^dlg$|_dlg$)', true);
                $packer->setText($data);

                break;
            case 'javascript' :
                $files = array();

                // add core file
                $files[] = WF_EDITOR . "/tiny_mce/tiny_mce" . $suffix . ".js";

                // Add themes
                foreach ($themes as $theme) {
                    $files[] = WF_EDITOR . "/tiny_mce/themes/" . $theme . "/editor_template" . $suffix . ".js";
                }

                // Add plugins
                foreach ($plugins as $plugin) {
                    $files[] = WF_EDITOR . "/tiny_mce/plugins/" . $plugin . "/editor_plugin" . $suffix . ".js";
                }

                // add Editor file
                $files[] = WF_EDITOR . '/libraries/js/editor.js';

                // parse ini language files
                $parser = new WFLanguageParser();
                $data = $parser->load();

                // add to packer
                $packer->setContentEnd($data);

                break;
            case 'css' :
                $context = JRequest::getWord('context', 'editor');

                if ($context == 'content') {
                    $files = array();

                    $files[] = WF_EDITOR_THEMES . '/' . $themes[0] . '/skins/' . $toolbar[0] . '/content.css';

                    // get template stylesheets
                    $styles = self::getStyleSheetsList(true);

                    foreach ($styles as $style) {
                        if (JFile::exists($style)) {
                            $files[] = $style;
                        }
                    }

                    // load content styles dor each plugin if they exist
                    foreach ($plugins as $plugin) {
                        $content = WF_EDITOR_PLUGINS . '/' . $plugin . '/css/content.css';
                        if (JFile::exists($content)) {
                            $files[] = $content;
                        }
                    }
                } else {
                    $files = array();

                    $files[] = WF_EDITOR_LIBRARIES . '/css/editor.css';
                    $dialog = $wf->getParam('editor.dialog_theme', 'jce');

                    $files[] = WF_EDITOR_THEMES . '/' . $themes[0] . '/skins/' . $toolbar[0] . '/ui.css';

                    if (isset($toolbar[1])) {
                        $files[] = WF_EDITOR_THEMES . '/' . $themes[0] . '/skins/' . $toolbar[0] . '/ui_' . $toolbar[1] . '.css';
                    }

                    // get external styles from config class for each plugin
                    foreach ($plugins as $plugin) {
                        $class = WF_EDITOR_PLUGINS . '/' . $plugin . '/classes/config.php';
                        if (JFile::exists($class)) {
                            require_once ($class);
                            $classname = 'WF' . ucfirst($plugin) . 'PluginConfig';
                            if (class_exists($classname) && method_exists(new $classname, 'getStyles')) {
                                $files = array_merge($files, (array) call_user_func(array($classname, 'getStyles')));
                            }
                        }
                    }
                }

                break;
        }

        $packer->setFiles($files);
        $packer->pack();
    }

    public function loadLanguages() {
        // check token
        WFToken::checkToken('GET') or die('RESTRICTED');

        wfimport('admin.classes.language');

        $parser = new WFLanguageParser(array('plugins' => $this->getPlugins()));
        $data = $parser->load();
        $parser->output($data);
    }

    public function compileLess() {
        // check token
        WFToken::checkToken('GET') or die('RESTRICTED');

        wfimport('admin.classes.packer');

        $wf = WFEditor::getInstance();
        $files = self::getStyleSheetsList(true);

        if (!empty($files)) {
            $packer = new WFPacker(array('files' => $files, 'type' => 'css'));
            $packer->pack(false);
        }
    }

    public function getToken($id) {
        return '<input type="hidden" id="wf_' . $id . '_token" name="' . WFToken::getToken() . '" value="1" />';
    }

}

?>
