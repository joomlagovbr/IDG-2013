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

class WFDocument extends JObject {

    /**
     * Array of linked scripts
     *
     * @var		array
     * @access   private
     */
    private $_scripts = array();

    /**
     * Array of scripts placed in the header
     *
     * @var  array
     * @access   private
     */
    private $_script = array();

    /**
     * Array of linked style sheets
     *
     * @var	 array
     * @access  private
     */
    private $_styles = array();

    /**
     * Array of head items
     *
     * @var	 array
     * @access  private
     */
    private $_head = array();

    /**
     * Body content
     *
     * @var	 array
     * @access  private
     */
    private $_body = '';

    /**
     * Document title
     *
     * @var	 string
     * @access  public
     */
    public $title = '';

    /**
     * Document version
     *
     * @var	 string
     * @access  public
     */
    public $version = '000000';

    /**
     * Contains the document language setting
     *
     * @var	 string
     * @access  public
     */
    public $language = 'en-gb';

    /**
     * Contains the document direction setting
     *
     * @var	 string
     * @access  public
     */
    public $direction = 'ltr';

    /**
     * Constructor activating the default information of the class
     *
     * @access  protected
     */
    public function __construct($config = array()) {
        parent::__construct();

        // set document title
        if (isset($config['title'])) {
            $this->setTitle($config['title']);
        }

        $this->setProperties($config);
    }

    /**
     * Returns a reference to a WFDocument object
     *
     * This method must be invoked as:
     *    <pre>  $document = WFDocument::getInstance();</pre>
     *
     * @access  public
     * @return  object WFDocument
     */
    public static function getInstance($config = array()) {
        static $instance;

        if (!is_object($instance)) {
            $instance = new WFDocument($config);
        }

        return $instance;
    }

    /**
     * Set the document title
     * @access 	public
     * @param 	string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get the document title
     * @access	public
     * @return	string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set the document name
     * @access	public
     * @param 	string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get the document name
     * @access	public
     * @return 	string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the editor URL
     * @access	private
     * @param 	bool $relative
     * @return 	string
     */
    private function getURL($relative = false) {
        if ($relative) {
            return JURI::root(true) . '/components/com_jce/editor';
        }

        return JURI::root() . 'components/com_jce/editor';
    }

    /**
     * Sets the global document language declaration. Default is English (en-gb).
     * @access 	public
     * @param   string   $lang
     */
    public function setLanguage($lang = "en-gb") {
        $this->language = strtolower($lang);
    }

    /**
     * Returns the document language.
     *
     * @return string
     * @access public
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Sets the global document direction declaration. Default is left-to-right (ltr).
     *
     * @access public
     * @param   string   $lang
     */
    public function setDirection($dir = "ltr") {
        $this->direction = strtolower($dir);
    }

    /**
     * Returns the document language.
     *
     * @return string
     * @access public
     */
    public function getDirection() {
        return $this->direction;
    }

    /**
     * Returns a JCE resource url
     *
     * @access  private
     * @param 	string  The path to resolve eg: libaries
     * @param 	boolean Create a relative url
     * @return  full url
     */
    private function getBaseURL($path, $type = '') {
        static $url;

        if (!isset($url)) {
            $url = array();
        }

        $signature = serialize(array($type, $path));

        // Check if value is already stored
        if (!isset($url[$signature])) {
            // get the plugin name using this document instance
            $plugin = $this->get('name');

            $base = $this->getURL(true) . '/';

            $parts = explode('.', $path);
            $path = array_shift($parts);

            switch ($path) {
                // JCE root folder
                case 'jce':
                    $pre = $base . '';
                    break;
                // JCE libraries resource folder
                default:
                case 'libraries':
                    $pre = $base . 'libraries/' . $type;
                    break;
                case 'jquery':
                    $pre = $base . 'libraries/jquery/' . $type;
                    break;
                case 'mediaelement':
                    $pre = $base . 'libraries/mediaelement/' . $type;
                    break;
                case 'bootstrap':
                    $pre = $base . 'libraries/bootstrap/' . $type;
                    break;
                // TinyMCE folder
                case 'tiny_mce':
                    $pre = $base . 'tiny_mce';
                    break;
                // JCE current plugin folder
                case 'plugins':
                    $pre = $base . 'tiny_mce/plugins/' . $plugin . '/' . $type;
                    break;
                // Extensions folder
                case 'extensions':
                    $pre = $base . 'extensions';
                    break;
                case 'joomla':
                    return JURI::root(true);
                    break;
                case 'media':
                    return JURI::root(true) . '/media/system';
                    break;
                case 'component':
                    $pre = JURI::root(true) . '/administrator/components/com_jce/media/' . $type;
                    break;
                default:
                    $pre = $base . $path;
                    break;
            }

            if (count($parts)) {
                $pre = rtrim($pre, '/') . '/' . implode('/', $parts);
            }

            // Store url
            $url[$signature] = $pre;
        }

        return $url[$signature];
    }

    /**
     * Convert a url to path
     *
     * @param 	string $url
     * @return  string 
     */
    private function urlToPath($url) {
        jimport('joomla.filesystem.path');

        $root = JURI::root(true);
        
        // remove root from url
        if (!empty($root)) {            
            $url = substr($url, strlen($root));
        }
        
        return WFUtility::makePath(JPATH_SITE, JPath::clean($url));
    }

    /**
     * Returns an image url
     *
     * @access  public
     * @param string  The file to load including path and extension eg: libaries.image.gif
     * @return  Image url
     * @since 1.5
     */
    public function image($image, $root = 'libraries') {
        $parts = explode('.', $image);
        $parts = preg_replace('#[^A-Z0-9-_]#i', '', $parts);

        $ext = array_pop($parts);
        $name = trim(array_pop($parts), '/');

        $parts[] = 'img';
        $parts[] = $name . "." . $ext;

        return $this->getBaseURL($root) . implode('/', $parts);
    }

    public function removeScript($file, $root = 'libraries') {
        $file = $this->buildScriptPath($file, $root);
        unset($this->_scripts[$file]);
    }

    public function removeCss($file, $root = 'libraries') {
        $file = $this->buildStylePath($file, $root);
        unset($this->_styles[$file]);
    }

    public function buildScriptPath($file, $root) {
        $file = preg_replace('#[^A-Z0-9-_\/\.]#i', '', $file);
        // get base dir
        $base = dirname($file);
        // remove extension if present
        $file = basename($file, '.js');
        // strip . and trailing /
        $file = trim(trim($base, '.'), '/') . '/' . $file . '.js';
        // remove leading and trailing slashes
        $file = trim($file, '/');
        // create path
        $file = $this->getBaseURL($root, 'js') . '/' . $file;

        return $file;
    }

    public function buildStylePath($file, $root) {
        $file = preg_replace('#[^A-Z0-9-_\/\.]#i', '', $file);
        // get base dir
        $base = dirname($file);
        // remove extension if present
        $file = basename($file, '.css');
        // strip . and trailing /
        $file = trim(trim($base, '.'), '/') . '/' . $file . '.css';
        // remove leading and trailing slashes
        $file = trim($file, '/');
        // create path
        $file = $this->getBaseURL($root, 'css') . '/' . $file;

        return $file;
    }

    /**
     * Loads a javascript file
     *
     * @access  public
     * @param string  The file to load including path eg: libaries.manager
     * @param boolean Debug mode load src file
     * @return  echo script html
     * @since 1.5
     */
    public function addScript($files, $root = 'libraries', $type = 'text/javascript') {
        $files = (array) $files;

        foreach ($files as $file) {
            // external link
            if (strpos($file, '://') !== false || strpos($file, 'index.php?option=com_jce') !== false) {
                $this->_scripts[$file] = $type;
            } else {
                $file = $this->buildScriptPath($file, $root);
                // store path
                $this->_scripts[$file] = $type;
            }
        }
    }

    /**
     * Loads a css file
     *
     * @access  public
     * @param string The file to load including path eg: libaries.manager
     * @param string Root folder
     * @return  echo css html
     * @since 1.5
     */
    public function addStyleSheet($files, $root = 'libraries', $type = 'text/css') {
        $files = (array) $files;

        jimport('joomla.environment.browser');
        $browser = JBrowser::getInstance();

        foreach ($files as $file) {
            $url = $this->buildStylePath($file, $root);
            // store path
            $this->_styles[$url] = $type;

            if ($browser->getBrowser() == 'msie') {
                // All versions
                $file = $file . '_ie.css';
                $path = $this->urlToPath($url);

                if (file_exists(dirname($path) . '/' . $file)) {
                    $this->_styles[dirname($url) . '/' . $file] = $type;
                }
            }
        }
    }

    public function addScriptDeclaration($content, $type = 'text/javascript') {
        if (!isset($this->_script[strtolower($type)])) {
            $this->_script[strtolower($type)] = $content;
        } else {
            $this->_script[strtolower($type)] .= chr(13) . $content;
        }
    }

    private function getScriptDeclarations() {
        return $this->_script;
    }

    private function getScripts() {
        return $this->_scripts;
    }

    private function getStyleSheets() {
        return $this->_styles;
    }

    /**
     * Setup head data
     */
    private function setHead($data) {
        if (is_array($data)) {
            $this->_head = array_merge($this->_head, $data);
        } else {
            $this->_head[] = $data;
        }
    }

    public function getQueryString($query = array()) {
        // get version
        //$version = $this->get('version', '000000');
        // get layout
        $layout = JRequest::getWord('layout');

        // set layout and item, eg: &layout=plugin&plugin=link
        $query['layout'] = $layout;
        $query[$layout] = JRequest::getWord($layout);

        // set dialog
        if (JRequest::getWord('dialog')) {
            $query['dialog'] = JRequest::getWord('dialog');
        }

        // set standalone mode (for File Browser etc)
        if ($this->get('standalone') == 1) {
            $query['standalone'] = 1;
        }

        // get component id
        $component_id = JRequest::getInt('component_id');
        // set component id
        if ($component_id) {
            $query['component_id'] = $component_id;
        }

        // get token
        $token = WFToken::getToken();
        // set token
        $query[$token] = 1;

        /*if (preg_match('/\d+/', $version)) {
            // set version
            $query['v'] = preg_replace('#[^a-z0-9]#i', '', $version);
        }*/

        $output = array();

        foreach ($query as $key => $value) {
            $output[] = $key . '=' . $value;
        }

        return implode('&', $output);
    }

    /**
     * Render document head data
     */
    private function getHead() {
        // create version / etag hash
        $version = $this->get('version', '000000');
        // set title		
        $output = '<title>' . $this->getTitle() . '</title>' . "\n";

        // render stylesheets
        if ($this->get('compress_css', 0)) {
            $file = JURI::base(true) . '/index.php?option=com_jce&view=editor&' . $this->getQueryString(array('task' => 'pack', 'type' => 'css'));

            $output .= "\t\t<link href=\"" . $file . "\" rel=\"stylesheet\" type=\"text/css\" />\n";
        } else {
            foreach ($this->_styles as $src => $type) {
                
                $stamp = '';
                // only add stamp to static stylesheets
                if (strpos($src, '://') === false && strpos($src, 'index.php?option=com_jce') === false) {
                    $version = md5(basename($src) . $version);
                    $stamp = strpos($src, '?') === false ? '?' . $version : '&' . $version;
                }
                
                $output .= "\t\t<link href=\"" . $src . $stamp . "\" rel=\"stylesheet\" type=\"" . $type . "\" />\n";
            }
        }

        // Render scripts
        if ($this->get('compress_javascript', 0)) {
            $script = JURI::base(true) . '/index.php?option=com_jce&view=editor&' . $this->getQueryString(array('task' => 'pack'));
            $output .= "\t\t<script data-cfasync=\"false\" type=\"text/javascript\" src=\"" . $script . "\"></script>\n";
        } else {
            foreach ($this->_scripts as $src => $type) {
                $stamp = '';
                // only add stamp to static scripts
                if (strpos($src, '://') === false && strpos($src, 'index.php?option=com_jce') === false) {
                    $version = md5(basename($src) . $version);
                    $stamp = strpos($src, '?') === false ? '?' . $version : '&' . $version;
                }
                
                $output .= "\t\t<script data-cfasync=\"false\" type=\"" . $type . "\" src=\"" . $src . $stamp . "\"></script>\n";
            }

            // Script declarations
            foreach ($this->_script as $type => $content) {
                $output .= "\t\t<script data-cfasync=\"false\" type=\"" . $type . "\">" . $content . "</script>";
            }
        }

        // Other head data
        foreach ($this->_head as $head) {
            $output .= "\t" . $head . "\n";
        }

        return $output;
    }

    public function setBody($data = '') {
        $this->_body = $data;
    }

    private function getBody() {
        return $this->_body;
    }

    private function loadData() {
        //get the file content
        ob_start();
        require_once(WF_EDITOR_LIBRARIES . '/views/plugin/index.php');
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    /**
     * Render the document
     */
    public function render() {
        // assign language
        $this->language = $this->getLanguage();
        $this->direction = $this->getDirection();

        // load template data
        $output = $this->loadData();
        $output = $this->parseData($output);

        exit($output);
    }

    private function parseData($data) {
        $data = preg_replace_callback('#<!-- \[head\] -->#', array($this, 'getHead'), $data);
        $data = preg_replace_callback('#<!-- \[body\] -->#', array($this, 'getBody'), $data);

        return $data;
    }

    /**
     * pack function for plugins
     */
    public function pack($minify = true, $gzip = false) {
        if (JRequest::getCmd('task') == 'pack') {

            // check token
            WFToken::checkToken('GET') or die('RESTRICTED');

            wfimport('admin.classes.packer');
            wfimport('admin.classes.language');

            $component = WFExtensionHelper::getComponent();
            $params = new WFParameter($component->params);

            $type = JRequest::getWord('type', 'javascript');

            // create packer
            $packer = new WFPacker(array('type' => $type));

            $files  = array();

            switch ($type) {
                case 'javascript':
                    $data = '';

                    foreach ($this->getScripts() as $src => $type) {                        
                        if (strpos($src, '://') === false && strpos($src, 'index.php') === false) {
                            $src .= preg_match('/\.js$/', $src) ? '' : '.js';
                            
                            $files[] = $this->urlToPath($src);
                        }
                    }

                    // parse ini language files
                    $parser = new WFLanguageParser(array(
                                'plugins' => array($this->getName()),
                                'sections' => array('dlg', $this->getName() . '_dlg'),
                                'mode' => 'plugin'
                            ));
                    $data .= $parser->load();

                    // add script declarations
                    foreach ($this->getScriptDeclarations() as $script) {
                        $data .= $script;
                    }

                    $packer->setContentEnd($data);

                    break;
                case 'css':
                    foreach ($this->getStyleSheets() as $style => $type) {
                        if (strpos($style, '://') === false && strpos($style, 'index.php') === false) {
                            $style .= preg_match('/\.css$/', $style) ? '' : '.css';
                            
                            $files[] = $this->urlToPath($style);
                        }
                    }

                    break;
            }

            $packer->setFiles($files);
            $packer->pack($minify, $gzip);
        }
    }

}

?>