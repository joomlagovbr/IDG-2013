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

wfimport('editor.libraries.classes.editor');

wfimport('editor.libraries.classes.language');
wfimport('editor.libraries.classes.utility');
wfimport('editor.libraries.classes.token');
wfimport('editor.libraries.classes.document');
wfimport('editor.libraries.classes.view');
wfimport('editor.libraries.classes.tabs');
wfimport('editor.libraries.classes.request');

/**
 * JCE class
 *
 * @package	JCE Site
 */
class WFEditorPlugin extends JObject {

    private $_alerts = array();

    /**
     * Constructor activating the default information of the class
     *
     * @access	public
     */
    function __construct($config = array()) {
        // Call parent
        parent::__construct();

        // get plugin name
        $plugin = JRequest::getCmd('plugin');

        // check plugin is valid
        //$this->checkPlugin($plugin) or die('RESTRICTED');
        
        // set plugin name
        $this->set('name', $plugin);

        // set config
        if (!array_key_exists('type', $config)) {
            $config['type'] = 'standard';
        }

        if (!array_key_exists('base_path', $config)) {
            $config['base_path'] = WF_EDITOR_PLUGINS . '/' . $plugin;
        }

        if (!defined('WF_EDITOR_PLUGIN')) {
            define('WF_EDITOR_PLUGIN', $config['base_path']);
        }

        if (!array_key_exists('view_path', $config)) {
            $config['view_path'] = WF_EDITOR_PLUGINS . '/' . $plugin;
        }

        if (!array_key_exists('layout', $config)) {
            $config['layout'] = 'default';
        }

        if (!array_key_exists('template_path', $config)) {
            $config['template_path'] = WF_EDITOR_PLUGIN . '/tmpl';
        }

        // backwards compatability
        if (!array_key_exists('colorpicker', $config)) {
            $config['colorpicker'] = in_array($plugin, array('imgmanager_ext', 'caption', 'mediamanager'));
        }
        
        // backwards compatability
        if (!array_key_exists('mediaplayer', $config)) {
            $config['mediaplayer'] = false;
        }

        $this->setProperties($config);
    }

    /**
     * Returns a reference to a editor object
     *
     * This method must be invoked as:
     * 		<pre>  $browser =JCE::getInstance();</pre>
     *
     * @access	public
     * @return	JCE  The editor object.
     * @since	1.5
     */
    public function getInstance($config = array()) {
        static $instance;

        if (!is_object($instance)) {
            $instance = new WFEditorPlugin($config);
        }

        return $instance;
    }

    /**
     * Get plugin View
     * @access public
     * @return WFView
     */
    public function getView() {
        static $view;

        if (!is_object($view)) {
            // create plugin view
            $view = new WFView(array(
                        'view_path' => $this->get('base_path'),
                        'template_path' => $this->get('template_path'),
                        'name' => $this->get('name'),
                        'layout' => $this->get('layout')
                    ));
        }

        $view->assign('plugin', $this);

        return $view;
    }

    protected function getVersion() {
        $wf = WFEditor::getInstance();

        return $wf->getVersion();
    }

    private function isRequest() {
        $format = JRequest::getWord('format');
        return ($format == 'json' || $format == 'raw') && (JRequest::getVar('json') || JRequest::getWord('action'));
    }

    protected function getProfile($plugin = null) {
        $wf = WFEditor::getInstance();

        return $wf->getProfile($plugin);
    }

    public function execute() {
        WFToken::checkToken() or die('Access to this resource is restricted');

        // JSON request or upload action
        if ($this->isRequest()) {
            $request = WFRequest::getInstance();
            $request->process();
        } else {
            $wf = WFEditor::getInstance();

            $version = $this->getVersion();
            $name = $this->getName();

            // process javascript languages
            if (JRequest::getWord('task') == 'loadlanguages') {
                wfimport('admin.classes.language');

                $parser = new WFLanguageParser(array(
                            'plugins' => array($name),
                            'sections' => array('dlg', $name . '_dlg', 'colorpicker'),
                            'mode' => 'plugin'
                        ));

                $data = $parser->load();
                $parser->output($data);
            }

            // load core language
            WFLanguage::load('com_jce', JPATH_ADMINISTRATOR);
            // Load Plugin language
            WFLanguage::load('com_jce_' . trim($this->getName()));
            
            // set default plugin version
            $plugin_version = '';
            
            $manifest = WF_EDITOR_PLUGIN . '/' . $name . '.xml';
            
            if (is_file($manifest)) {
                $xml = WFXMLHelper::parseInstallManifest($manifest);
                
                if ($xml && isset($xml['version'])) {
                    $plugin_version = $xml['version'];
                }
            }

            // add plugin version
            if ($plugin_version) {
                $version .= '-' . preg_replace('#[^a-z0-9]+#i', '', $plugin_version);
            }

            // create the document
            $document = WFDocument::getInstance(array(
                'version'   => $version,
                'title'     => WFText::_('WF_' . strtoupper($this->getName() . '_TITLE')),
                'name'      => $name,
                'language'  => WFLanguage::getTag(),
                'direction' => WFLanguage::getDir(),
                'compress_javascript' => $this->getParam('editor.compress_javascript', 0),
                'compress_css' => $this->getParam('editor.compress_css', 0)
            ));

            // set standalone mode
            $document->set('standalone', JRequest::getInt('standalone', 0));

            // create display
            $this->display();

            // ini language
            $document->addScript(array('index.php?option=com_jce&view=editor&' . $document->getQueryString(array('task' => 'loadlanguages', 'lang' => WFLanguage::getCode()))), 'joomla');

            // pack assets if required
            $document->pack(true, $this->getParam('editor.compress_gzip', 0));

            // get the view
            $view = $this->getView();

            // set body output
            $document->setBody($view->loadTemplate());

            // render document		
            $document->render();
        }
    }

    /**
     * Display plugin
     * @access private
     */
    public function display() {
        jimport('joomla.filesystem.folder');
        $document = WFDocument::getInstance();

        if ($document->get('standalone') == 0) {
            $document->addScript(array('tiny_mce_popup'), 'tiny_mce');
            $document->addScript(array('tiny_mce_utils'), 'libraries');
        }

        $document->addScript(array('jquery-' . WF_JQUERY . '.min', 'jquery-ui-' . WF_JQUERYUI . '.custom.min', 'jquery.ui.touch-punch.min'), 'jquery');

        // add colorpicker
        if ($this->get('colorpicker')) {
            wfimport('admin.helpers.tools');

            $document->addScript(array('colorpicker'), 'libraries');
            $document->addScriptDeclaration('ColorPicker.settings=' . json_encode(array('template_colors' => WFToolsHelper::getTemplateColors(), 'custom_colors' => $this->getParam('editor.custom_colors', ''))) . ';');
        }

        $document->addScript(array(
            'html5',
            'select',
            'tips',
            'plugin'
        ), 'libraries');

        // load plugin dialog language file if necessary
        if ($this->getParam('editor.compress_javascript', 0)) {
            $file = "/langs/" . WFLanguage::getCode() . "_dlg.js";

            if (!JFile::exists(WF_EDITOR_PLUGIN . $file)) {
                $file = "/langs/en_dlg.js";
            }

            if (JFile::exists(WF_EDITOR_PLUGIN . $file)) {
                $document->addScript(array('plugins/' . $this->getName() . $file), 'tiny_mce');
            }
        }

        $document->addStyleSheet(array('plugin'), 'libraries');
        
        // MediaElement in the future perhaps?
        
        /*if ($this->get('mediaplayer')) {
            $document->addScript(array('mediaelement-and-player.min'), 'mediaelement');
            $document->addStyleSheet(array('mediaelementplayer.min'), 'mediaelement');
        }*/

        // add custom plugin.css if exists
        if (is_file(JPATH_SITE . '/media/jce/css/plugin.css')) {
            $document->addStyleSheet(array('media/jce/css/plugin.css'), 'joomla');
        }
    }

    /**
     * Return the plugin name
     * @access public
     * @return string
     */
    public function getName() {
        return $this->get('name');
    }

    /**
     * Get default values for a plugin.
     * Key / Value pairs will be retrieved from the profile or plugin manifest
     * @access 	public
     * @param 	array $defaults
     * @return 	array
     */
    public function getDefaults($defaults = array()) {
        $name = $this->getName();
        
        // get manifest path
        $manifest = WF_EDITOR_PLUGIN . '/' . $name . '.xml';
        
        // get parameter defaults
        if (is_file($manifest)) {
            $params = $this->getParams(array(
                'key'   => $name,
                'path'  => $manifest
            ));
            
            return array_merge($defaults, (array) $params->getAll('defaults'));
        }

        return $defaults;
    }

    /**
     * Check the user is in an authorized group
     * Check the users group is authorized to use the plugin
     *
     * @access 			public
     * @return 			boolean
     */
    public function checkPlugin($plugin = null) {
        if ($plugin) {
            // check existence of plugin directory
            if (is_dir(WF_EDITOR_PLUGINS . '/' . $plugin)) {
                // get profile	
                $profile = $this->getProfile($plugin);
                // check for valid object and profile id
                return is_object($profile) && isset($profile->id);
            }
        }

        return false;
    }

    /**
     * Add an alert array to the stack
     * 
     * @access private
     * @param object $class Alert classname
     * @param object $title Alert title
     * @param object $text 	Alert text
     */
    protected function addAlert($class = 'info', $title = '', $text = '') {
        $alerts = $this->getAlerts();

        $alerts[] = array(
            'class' => $class,
            'title' => $title,
            'text' => $text
        );

        $this->set('_alerts', $alerts);
    }

    /**
     * Get current alerts
     * @access private
     * @return array Alerts
     */
    private function getAlerts() {
        return $this->get('_alerts');
    }

    /**
     * Convert a url to path
     *
     * @access	public
     * @param	string 	The url to convert
     * @return	string 	Full path to file
     */
    public function urlToPath($url) {
        $document = WFDocument::getInstance();
        return $document->urlToPath($url);
    }

    /**
     * Returns an image url
     *
     * @access	public
     * @param	string 	The file to load including path and extension eg: libaries.image.gif
     * @return	string 	Image url
     */
    public function image($image, $root = 'libraries') {
        $document = WFDocument::getInstance();

        return $document->image($image, $root);
    }

    /**
     * Load & Call an extension
     *
     * @access	protected
     * @param 	array $config
     * @return 	array
     */
    protected function loadExtensions($type, $extension = null, $config = array()) {
        return WFExtension::loadExtensions($type, $extension, $config);
    }

    /**
     * Compile plugin settings from defaults and alerts
     * 
     * @access  public
     * @param 	array $settings
     * @return 	array
     */
    public function getSettings($settings = array()) {
        $default = array(
            'alerts' => $this->getAlerts(),
            'defaults' => $this->getDefaults()
        );

        $settings = array_merge($default, $settings);

        return $settings;
    }

    public function getParams($options = array()) {
        $wf = WFEditor::getInstance();

        return $wf->getParams($options);
    }

    /**
     * Get a parameter by key
     * 
     * @access 	public
     * @param 	string $key Parameter key eg: editor.width
     * @param 	mixed $fallback Fallback value
     * @param 	mixed $default Default value
     * @param 	string $type Variable type eg: string, boolean, integer, array
     * @param 	bool $allowempty
     * @return 	mixed
     */
    public function getParam($key, $fallback = '', $default = '', $type = 'string', $allowempty = true) {
        // get plugin name
        $name = $this->getName();
        // get all keys
        $keys = explode('.', $key);

        $wf = WFEditor::getInstance();

        // root key set
        if ($keys[0] == 'editor' || $keys[0] == $name) {
            return $wf->getParam($key, $fallback, $default, $type, $allowempty);
            // no root key set, treat as shared param
        } else {
            // get fallback
            $fallback = $wf->getParam('editor.' . $key, $fallback, $allowempty);
            // get param for plugin
            return $wf->getParam($name . '.' . $key, $fallback, $default, $type, $allowempty);
        }
    }

    /**
     * Named wrapper to check access to a feature
     *
     * @access 			public
     * @param string	The feature to check, eg: upload
     * @param mixed		The defalt value
     * @return 			Boolean
     */
    public function checkAccess($option, $default = 0) {
        return (bool) $this->getParam($option, $default);
    }

}

?>
