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

wfimport('editor.libraries.classes.extensions.filesystem');

class WFFileBrowser extends JObject {
    /*
     *  @var array
     */

    private $_buttons = array();
    /*
     *  @var array
     */
    private $_actions = array();
    /*
     *  @var array
     */
    private $_events = array();
    /*
     *  @var array
     */
    private $_result = array('error' => array(), 'files' => array(), 'folders' => array());

    /**
     * @access  protected
     */
    public function __construct($config = array()) {

        $default = array(
            'dir' => '',
            'filesystem' => 'joomla',
            'filetypes' => 'images=jpg,jpeg,png,gif',
            'upload' => array(
                'runtimes' => 'html5,flash,silverlight,html4',
                'chunk_size' => null,
                'max_size' => 1024,
                'validate_mimetype' => 1,
                'add_random' => 0
            ),
            'folder_tree' => 1,
            'list_limit' => 'all',
            'features' => array(
                'upload' => 1,
                'folder' => array(
                    'create' => 1,
                    'delete' => 1,
                    'rename' => 1,
                    'move' => 1
                ),
                'file' => array(
                    'rename' => 1,
                    'delete' => 1,
                    'move' => 1
                )
            ),
            'websafe_mode' => 'utf-8',
            'websafe_spaces' => 0
        );

        $config = array_merge($default, $config);

        $this->setProperties($config);

        // Setup XHR callback funtions
        $this->setRequest(array($this, 'getItems'));
        $this->setRequest(array($this, 'getFileDetails'));
        $this->setRequest(array($this, 'getFolderDetails'));
        $this->setRequest(array($this, 'getTree'));
        $this->setRequest(array($this, 'getTreeItem'));

        // Get actions
        $this->getStdActions();
        // Get buttons
        $this->getStdButtons();
    }

    /**
     * Display the browser
     * @access public
     */
    public function display() {
        //parent::display();
        // Get the Document instance
        $document = WFDocument::getInstance();

        $document->addScript(array(
            'tree',
            'upload',
            'browser',
            'sort',
            'filter',
            'manager'
                ), 'libraries');

        $document->addScript(array(
            'plupload.full',
                ), 'jce.libraries.plupload');

        //$document->addStyleSheet(array('files', 'tree', 'upload'), 'libraries');
        $document->addStyleSheet(array('manager'), 'libraries');
        // custom stylesheet
        //$document->addStyleSheet(array('custom'), 'libraries.css');
        // file browser options
        $document->addScriptDeclaration('WFFileBrowser.settings=' . json_encode($this->getSettings()) . ';');
    }

    /**
     * Render the browser view
     * @access public
     */
    public function render() {
        $session = JFactory::getSession();

        $view = new WFView(array(
                    'name' => 'browser',
                    'layout' => 'file'
                ));

        // assign session data
        $view->assign('session', $session);
        // assign form action
        $view->assign('action', $this->getFormAction());
        // return view output
        $view->display();
    }

    /**
     * Set a WFRequest item
     * @access 	public
     * @param 	array $request
     */
    public function setRequest($request) {
        $xhr = WFRequest::getInstance();
        $xhr->setRequest($request);
    }

    /**
     * Upload form action url
     *
     * @access	public
     * @param	string 	The target action file eg: upload.php
     * @return	Joomla! component url
     * @since	1.5
     */
    protected function getFormAction() {
        $wf = WFEditorPlugin::getInstance();

        $component_id = JRequest::getInt('component_id');

        $query = '';

        $args = array(
            'plugin' => $wf->getName()
        );

        if ($component_id) {
            $args['component_id'] = $component_id;
        }

        foreach ($args as $k => $v) {
            $query .= '&' . $k . '=' . $v;
        }

        return JURI::base(true) . '/index.php?option=com_jce&view=editor&layout=plugin' . $query;
    }

    public function getFileSystem() {
        static $filesystem;

        if (!is_object($filesystem)) {
            $wf = WFEditorPlugin::getInstance();

            $config = array(
                'dir' => $this->get('dir'),
                'upload_conflict' => $wf->getParam('editor.upload_conflict', 'overwrite'),
                'filetypes' => $this->getFileTypes('array')
            );

            $filesystem = WFFileSystem::getInstance($this->get('filesystem'), $config);
        }

        return $filesystem;
    }

    private function getViewable() {
        return 'jpeg,jpg,gif,png,avi,wmv,wm,asf,asx,wmx,wvx,mov,qt,mpg,mp3,mp4,m4v,mpeg,ogg,ogv,webm,swf,flv,f4v,xml,dcr,rm,ra,ram,divx,html,htm,txt,rtf,pdf,doc,docx,xls,xlsx,ppt,pptx';
    }

    /**
     * Return a list of allowed file extensions in selected format
     *
     * @access public
     * @return extension list
     */
    public function getFileTypes($format = 'map') {
        $list = $this->get('filetypes');

        // Remove excluded file types (those that have a - prefix character) from the list
        $data = array();

        foreach (explode(';', $list) as $group) {
            if (substr(trim($group), 0, 1) === '-') {
                continue;
            }
            // remove excluded file types (those that have a - prefix character) from the list
            $data[] = preg_replace('#(,)?-([\w]+)#', '', $group);
        }

        $list = implode(';', $data);

        switch ($format) {
            case 'list':
                return strtolower($this->listFileTypes($list));
                break;
            case 'array':
                return explode(',', strtolower($this->listFileTypes($list)));
                break;
            default:
            case 'map':
                return $list;
                break;
        }
    }

    public function setFileTypes($list = 'images=jpg,jpeg,png,gif') {
        $this->set('filetypes', $list);
    }

    /**
     * Converts the extensions map to a list
     * @param string $map The extensions map eg: images=jpg,jpeg,gif,png
     * @return string jpg,jpeg,gif,png
     */
    private function listFileTypes($map) {
        return preg_replace(array('/([\w]+)=([\w]+)/', '/;/'), array('$2', ','), $map);
    }

    public function addFileTypes($types) {
        $list = explode(';', $this->get('filetypes'));

        foreach ($types as $group => $extensions) {
            $list[] = $group . '=' . $extensions;
        }

        $this->set('filetypes', implode(';', $list));
    }

    /**
     * Maps upload file types to an upload dialog list, eg: 'images', 'jpeg,jpg,gif,png'
     * @return json encoded list
     */
    private function mapUploadFileTypes() {
        $map = array();

        // Get the filetype map
        $list = $this->getFileTypes();

        if ($list) {
            $items = explode(';', $list);
            $all = array();

            // [images=jpeg,jpg,gif,png]
            foreach ($items as $item) {
                // ['images', 'jpeg,jpg,gif,png']
                $kv = explode('=', $item);
                $extensions = implode(';', preg_replace('/(\w+)/i', '*.$1', explode(',', $kv[1])));
                $map[WFText::_('WF_FILEGROUP_' . $kv[0], WFText::_($kv[0])) . ' (' . $extensions . ')'] = $kv[1];

                $all[] = $kv[1];
            }

            if (count($items) > 1) {
                // All file types
                $map[WFText::_('WF_FILEGROUP_ALL') . ' (*.*)'] = implode(',', $all);
            }
        }

        return $map;
    }

    /**
     * Returns the result variable
     * @return var $_result
     */
    public function getResult() {
        return $this->_result;
    }

    public function setResult($value, $key = null) {
        if ($key) {
            if (is_array($this->_result[$key])) {
                $this->_result[$key][] = $value;
            } else {
                $this->_result[$key] = $value;
            }
        } else {
            $this->_result = $value;
        }
    }

    function checkFeature($action, $type = null) {
        $features = $this->get('features');

        if ($type) {
            if (isset($features[$type])) {

                $type = $features[$type];

                if (isset($type[$action])) {
                    return (bool) $type[$action];
                }
            }
        } else {
            if (isset($features[$action])) {
                return (bool) $features[$action];
            }
        }

        return false;
    }

    public function getBaseDir() {
        $filesystem = $this->getFileSystem();
        return $filesystem->getBaseDir();
    }

    /**
     * Get the list of files in a given folder
     * @param string $relative The relative path of the folder
     * @param string $filter A regex filter option
     * @return File list array
     */
    private function getFiles($relative, $filter = '.') {
        $filesystem = $this->getFileSystem();
        
        $list = $filesystem->getFiles($relative, $filter);

        return $list;
    }

    /**
     * Get the list of folder in a given folder
     * @param string $relative The relative path of the folder
     * @return Folder list array
     */
    private function getFolders($relative, $filter) {
        $filesystem = $this->getFileSystem();
        $list = $filesystem->getFolders($relative, $filter);

        return $list;
    }

    /**
     * Get file and folder lists
     * @return array Array of file and folder list objects
     * @param string $relative Relative or absolute path based either on source url or current directory
     * @param int $limit List limit
     * @param int $start list start point
     */
    public function getItems($path, $limit = 25, $start = 0, $filter = '') {
        $filesystem = $this->getFileSystem();
        
        $files      = array();
        $folders    = array();

        clearstatcache();

        // decode path
        $path = rawurldecode($path);

        WFUtility::checkPath($path);

        // get source dir from path eg: images/stories/fruit.jpg = images/stories
        $dir = $filesystem->getSourceDir($path);

        $filetypes  = explode(',', $this->getFileTypes('list'));
        $name       = '';

        if ($filter) {            
            if ($filter{0} == '.') {
                $ext = WFUtility::makeSafe($filter);
                
                for($i = 0; $i < count($filetypes); $i++) {
                    if (preg_match('#^' . $ext . '#', $filetypes[$i]) === false) {
                        unset($filetypes[$i]);
                    }
                }                
            } else {
                $name = '^(?i)' . WFUtility::makeSafe($filter) . '.*';
            }
        }

        // get file list by filter
        $files = self::getFiles($dir, $name . '\.(?i)(' . implode('|', $filetypes) . ')$');
        
        if (empty($filter) || $filter{0} != '.') {
            // get folder list
            $folders = self::getFolders($dir, '^(?i)' . WFUtility::makeSafe($filter) . '.*');
        }

        $folderArray    = array();
        $fileArray      = array();

        $items = array_merge($folders, $files);

        if ($items) {
            if (is_numeric($limit)) {
                $items = array_slice($items, $start, $limit);
            }

            foreach ($items as $item) {
                $item['classes'] = '';
                if ($item['type'] == 'folders') {
                    $folderArray[] = $item;
                } else {
                    // check for selected item
                    $item['selected'] = $filesystem->isMatch($item['url'], $path);
                    $fileArray[] = $item;
                }
            }
        }

        $result = array(
            'folders' => $folderArray,
            'files' => $fileArray,
            'total' => array(
                'folders' => count($folders),
                'files' => count($files)
            )
        );

        // Fire Event passing result as reference
        $this->fireEvent('onGetItems', array(&$result));

        return $result;
    }

    /**
     * Get a tree node
     * @param string $dir The relative path of the folder to search
     * @return Tree node array
     */
    public function getTreeItem($path) {
        $filesystem = $this->getFileSystem();
        $path = rawurldecode($path);

        WFUtility::checkPath($path);

        // get source dir from path eg: images/stories/fruit.jpg = images/stories
        $dir = $filesystem->getSourceDir($path);

        $folders = $this->getFolders($dir);
        $array = array();
        if (!empty($folders)) {
            foreach ($folders as $folder) {
                $array[] = array(
                    'id' => $folder['id'],
                    'name' => $folder['name'],
                    'class' => 'folder'
                );
            }
        }
        $result = array(
            'folders' => $array
        );
        return $result;
    }

    /**
     * Escape a string
     *
     * @return string Escaped string
     * @param string $string
     */
    private function escape($string) {
        return preg_replace(array(
                    '/%2F/',
                    '/%3F/',
                    '/%40/',
                    '/%2A/',
                    '/%2B/'
                        ), array(
                    '/',
                    '?',
                    '@',
                    '*',
                    '+'
                        ), rawurlencode($string));
    }

    /**
     * Build a tree list
     * @param string $dir The relative path of the folder to search
     * @return Tree html string
     */
    public function getTree($path) {
        $filesystem = $this->getFileSystem();

        // decode path
        $path = rawurldecode($path);

        WFUtility::checkPath($path);

        // get source dir from path eg: images/stories/fruit.jpg = images/stories
        $dir = $filesystem->getSourceDir($path);

        $result = $this->getTreeItems($dir);
        return $result;
    }

    /**
     * Get Tree list items as html list
     *
     * @return Tree list html string
     * @param string $dir Current directory
     * @param boolean $root[optional] Is root directory
     * @param boolean $init[optional] Is tree initialisation
     */
    public function getTreeItems($dir, $root = true, $init = true) {
        $result = '';

        static $treedir = null;

        if ($init) {
            $treedir = $dir;
            if ($root) {
                $result = '<ul><li id="/" class="open"><div class="tree-row"><div class="tree-image"></div><span class="root"><a href="javascript:;">' . WFText::_('WF_LABEL_ROOT') . '</a></span></div>';
                $dir = '/';
            }
        }
        $folders = $this->getFolders($dir);

        if ($folders) {
            $result .= '<ul class="tree-node">';
            foreach ($folders as $folder) {
                $open = strpos($treedir, ltrim($folder['id'], '/')) === 0 ? ' open' : '';
                $result .= '<li id="' . $this->escape($folder['id']) . '" class="' . $open . '"><div class="tree-row"><div class="tree-image"></div><span class="folder"><a href="javascript:;">' . $folder['name'] . '</a></span></div>';

                if ($open) {
                    if ($h = $this->getTreeItems($folder['id'], false, false)) {
                        $result .= $h;
                    }
                }

                $result .= '</li>';
            }
            $result .= '</ul>';
        }
        if ($init && $root) {
            $result .= '</li></ul>';
        }
        $init = false;
        return $result;
    }

    /**
     * Get a folders properties
     *
     * @return array Array of properties
     * @param string $dir Folder relative path
     */
    public function getFolderDetails($dir) {
        WFUtility::checkPath($dir);

        $filesystem = $this->getFileSystem();
        // get array with folder date and content count eg: array('date'=>'00-00-000', 'folders'=>1, 'files'=>2);
        return $filesystem->getFolderDetails($dir);
    }

    /**
     * Get a files properties
     *
     * @return array Array of properties
     * @param string $file File relative path
     */
    function getFileDetails($file) {
        WFUtility::checkPath($file);

        $filesystem = $this->getFileSystem();
        // get array with folder date and content count eg: array('date'=>'00-00-000', 'folders'=>1, 'files'=>2);
        return $filesystem->getFileDetails($file);
    }

    /**
     * Create standard actions based on access
     */
    private function getStdActions() {
        $this->addAction('help', '', '', WFText::_('WF_BUTTON_HELP'));

        if ($this->checkFeature('upload')) {
            $this->addAction('upload');
            $this->setRequest(array($this, 'upload'));
        }

        if ($this->checkFeature('create', 'folder')) {
            $this->addAction('folder_new');
            $this->setRequest(array($this, 'folderNew'));
        }
    }

    /**
     * Add an action to the list
     *
     * @param string $name Action name
     * @param array  $options Array of options
     */
    public function addAction($name, $options = array()) {
        /* TODO */
        // backwards compatability (remove in stable)
        $args = func_get_args();

        if (count($args) == 4) {
            $options['icon'] = $args[1];
            $options['action'] = $args[2];
            $options['title'] = $args[3];
        }

        $options = array_merge(array('name' => $name), $options);

        // set some defaults
        if (!array_key_exists('icon', $options)) {
            $options['icon'] = '';
        }

        if (!array_key_exists('action', $options)) {
            $options['action'] = '';
        }

        if (!array_key_exists('title', $options)) {
            $options['title'] = WFText::_('WF_BUTTON_' . strtoupper($name));
        }

        $this->_actions[$name] = $options;
    }

    /**
     * Get all actions
     * @return object
     */
    private function getActions() {
        return array_reverse($this->_actions);
    }

    /**
     * Remove an action from the list by name
     * @param string $name Action name to remove
     */
    public function removeAction($name) {
        if (array_key_exists($this->_actions[$name])) {
            unset($this->_actions[$name]);
        }
    }

    /**
     * Create all standard buttons based on access
     */
    private function getStdButtons() {
        if ($this->checkFeature('delete', 'folder')) {
            $this->addButton('folder', 'delete', array('multiple' => true));

            $this->setRequest(array($this, 'deleteItem'));
        }
        if ($this->checkFeature('rename', 'folder')) {
            $this->addButton('folder', 'rename');

            $this->setRequest(array($this, 'renameItem'));
        }
        if ($this->checkFeature('move', 'folder')) {
            $this->addButton('folder', 'copy', array('multiple' => true));
            $this->addButton('folder', 'cut', array('multiple' => true));

            $this->addButton('folder', 'paste', array('multiple' => true, 'trigger' => true));

            $this->setRequest(array($this, 'copyItem'));
            $this->setRequest(array($this, 'moveItem'));
        }
        if ($this->checkFeature('rename', 'file')) {
            $this->addButton('file', 'rename');

            $this->setRequest(array($this, 'renameItem'));
        }
        if ($this->checkFeature('delete', 'file')) {
            $this->addButton('file', 'delete', array('multiple' => true));

            $this->setRequest(array($this, 'deleteItem'));
        }
        if ($this->checkFeature('move', 'file')) {
            $this->addButton('file', 'copy', array('multiple' => true));
            $this->addButton('file', 'cut', array('multiple' => true));

            $this->addButton('file', 'paste', array('multiple' => true, 'trigger' => true));

            $this->setRequest(array($this, 'copyItem'));
            $this->setRequest(array($this, 'moveItem'));
        }
        $this->addButton('file', 'view', array('restrict' => $this->getViewable()));
        $this->addButton('file', 'insert');
    }

    /**
     * Add a button
     *
     * @param string $type[optional] Button type (file or folder)
     * @param string $name Button name
     * @param string $icon[optional] Button icon
     * @param string $action[optional] Button action / function
     * @param string $title Button title
     * @param boolean $multiple[optional] Supports multiple file selection
     * @param boolean $trigger[optional]
     */
    public function addButton($type = 'file', $name, $options = array()) {
        $options = array_merge(array('name' => $name), $options);

        // set some defaults
        if (!array_key_exists('icon', $options)) {
            $options['icon'] = '';
        }

        if (!array_key_exists('action', $options)) {
            $options['action'] = '';
        }

        if (!array_key_exists('title', $options)) {
            $options['title'] = WFText::_('WF_BUTTON_' . strtoupper($name));
        }

        if (!array_key_exists('multiple', $options)) {
            $options['multiple'] = false;
        }

        if (!array_key_exists('trigger', $options)) {
            $options['trigger'] = false;
        }

        if (!array_key_exists('restrict', $options)) {
            $options['restrict'] = '';
        }

        $this->_buttons[$type][$name] = $options;
    }

    /**
     * Return an object list of all buttons
     * @return object
     */
    private function getButtons() {
        return $this->_buttons;
    }

    /**
     * Remove a button
     * @param string $type Button type
     * @param string $name Button name
     */
    public function removeButton($type, $name) {
        if (array_key_exists($name, $this->_buttons[$type])) {
            unset($this->_buttons[$type][$name]);
        }
    }

    /**
     * Change a buttons properties
     * @param string $type Button type
     * @param string $name Button name
     * @param string $keys Button keys
     */
    public function changeButton($type, $name, $keys) {
        foreach ($keys as $key => $value) {
            if (isset($this->_buttons[$type][$name][$key])) {
                $this->_buttons[$type][$name][$key] = $value;
            }
        }
    }

    /**
     * Add an event
     * @param string $name Event name
     * @param string $function Event function name
     */
    public function addEvent($name, $function) {
        $this->_events[$name] = $function;
    }

    /**
     * Execute an event
     * @return Evenet result
     * @param object $name Event name
     * @param array $args[optional] Optional arguments
     */
    protected function fireEvent($name, $args = null) {
        if (array_key_exists($name, $this->_events)) {
            $event = $this->_events[$name];

            if (is_array($event)) {
                return call_user_func_array($event, $args);
            } else {
                return call_user_func($event, $args);
            }
        }
        return $this->_result;
    }

    /**
     * Get a file icon based on extension
     * @return string Path to file icon
     * @param string $ext File extension
     */
    public function getFileIcon($ext) {
        if (JFile::exists(WF_EDITOR_LIBRARIES . '/img/icons/' . $ext . '.gif')) {
            return $this->image('libraries.icons/' . $ext . '.gif');
        } elseif (JFile::exists($this->getPluginPath() . '/img/icons/' . $ext . '.gif')) {
            return $this->image('plugins.icons/' . $ext . '.gif');
        } else {
            return $this->image('libraries.icons/def.gif');
        }
    }

    public function getFileSuffix() {
        $suffix = WFText::_('WF_MANAGER_FILE_SUFFIX');
        return str_replace('WF_MANAGER_FILE_SUFFIX', '_copy', $suffix);
    }

    private function validateUploadedFile($file) {
        // check the POST data array
        if (empty($file)) {
            @unlink($file['tmp_name']);

            throw new InvalidArgumentException('INVALID UPLOAD DATA');
        }

        // check for tmp_name and is valid uploaded file
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            @unlink($file['tmp_name']);

            throw new InvalidArgumentException('INVALID UPLOAD DATA');
        }

        // Null byte check
        if (strstr($file['name'], "\u0000")) {
            @unlink($file['tmp_name']);

            throw new InvalidArgumentException('INVALID UPLOAD DATA');
        }

        // check for invalid extension in file name
        if (preg_match('#\.(php|php(3|4|5)|phtml|pl|py|jsp|asp|htm|html|shtml|sh|cgi)\.#i', $file['name'])) {
            @unlink($file['tmp_name']);

            throw new InvalidArgumentException('INVALID FILE NAME');
        }

        //clearstatcache();
        // check the file sizes match
        /* if ((int) @filesize($file['tmp_name']) !== (int) $file['size']) {
          @unlink($file['tmp_name']);

          throw new InvalidArgumentException('INVALID FILE SIZE');
          } */

        // get extension
        $ext = WFUtility::getExtension($file['name']);

        // check extension is allowed
        $allowed = $this->getFileTypes('array');

        if (is_array($allowed) && !empty($allowed) && in_array(strtolower($ext), $allowed) === false) {
            @unlink($file['tmp_name']);
            throw new InvalidArgumentException(WFText::_('WF_MANAGER_UPLOAD_INVALID_EXT_ERROR'));
        }

        // validate image
        if (preg_match('#\.(jpeg|jpg|jpe|png|gif|wbmp|bmp|tiff|tif)$#i', $file['name'])) {
            if (@getimagesize($file['tmp_name']) === false) {
                @unlink($file['tmp_name']);

                throw new InvalidArgumentException('INVALID IMAGE FILE');
            }
        }

        $upload = $this->get('upload');

        // validate mimetype
        if ($upload['validate_mimetype']) {
            wfimport('editor.libraries.classes.mime');

            if (WFMimeType::check($file['name'], $file['tmp_name']) === false) {
                @unlink($file['tmp_name']);

                throw new InvalidArgumentException('INVALID MIME TYPE');
            }
        }

        // xss check
        $xss_check = JFile::read($file['tmp_name'], false, 256);

        // check for hidden php tags
        if (stripos($xss_check, '<?php') !== false) {
            @unlink($file['tmp_name']);

            throw new InvalidArgumentException('INVALID CODE IN FILE');
        }

        // check for hidden short php tags
        if (preg_match('#\.(inc|phps|class|php|php(3|4)|txt|dat|tpl|tmpl)$#i', $file['name'])) {

            if (stripos($xss_check, '<?') !== false) {
                @unlink($file['tmp_name']);

                throw new InvalidArgumentException('INVALID CODE IN FILE');
            }
        }

        // check for html tags in some files (IE XSS bug)
        if (!preg_match('#\.(txt|htm|html)$#i', $file['name'])) {

            $tags = 'a,abbr,acronym,address,area,b,base,bdo,big,blockquote,body,br,button,caption,cite,code,col,colgroup,dd,del,dfn,div,dl,dt,em,fieldset,form,h1,h2,h3,h4,h5,h6,head,hr,html,i,img,input,ins,kbd,label,legend,li,link,map,meta,noscript,object,ol,optgroup,option,p,param,pre,q,samp,script,select,small,span,strong,style,sub,sup,table,tbody,td,textarea,tfoot,th,thead,title,tr,tt,ul,var';

            foreach (explode(',', $tags) as $tag) {
                // check for tag eg: <body> or <body
                if (stripos($xss_check, '<' . $tag . '>') !== false || stripos($xss_check, '<' . $tag . ' ') !== false) {
                    @unlink($file['tmp_name']);

                    throw new InvalidArgumentException('INVALID TAG IN FILE');
                }
            }
        }
    }

    /**
     * Upload a file.
     * @return array $error on failure or uploaded file name on success
     */
    public function upload() {
        // Check for request forgeries
        WFToken::checkToken() or die();

        //JError::setErrorHandling(E_ALL, 'callback', array('WFError', 'raiseError'));
        // check for feature access	
        if (!$this->checkFeature('upload')) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        jimport('joomla.filesystem.file');

        // get uploaded file
        $file = JRequest::getVar('file', '', 'files', 'array');

        // validate file data
        $this->validateUploadedFile($file);

        $wf = WFEditor::getInstance();

        // HTTP headers for no cache etc
        //header('Content-type: text/plain; charset=UTF-8');
        header("Expires: Wed, 4 Apr 1984 13:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M_Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // get file name
        $name = JRequest::getVar('name', $file['name']);

        // target directory
        $dir = JRequest::getVar('upload-dir');
        // deocode directory
        $dir = rawurldecode($dir);
        // check destination path
        WFUtility::checkPath($dir);
        // decode name
        $name = rawurldecode($name);
        // check file name
        WFUtility::checkPath($name);

        // check for invalid extensions
        if (preg_match('#\.(php|phtml|pl|py|jsp|asp|shtml|sh|cgi)$#i', $name)) {
            throw new InvalidArgumentException('INVALID FILE NAME');
        }

        // get extension
        $ext = WFUtility::getExtension($name);

        // strip extension
        $name = WFUtility::stripExtension($name);
        // make file name 'web safe'
        $name = WFUtility::makeSafe($name, $this->get('websafe_mode', 'utf-8'), $this->get('websafe_spaces'));

        // empty name
        if ($name == '') {
            throw new InvalidArgumentException('INVALID FILE NAME');
        }

        // check for extension in file name
        if (preg_match('#\.(php|php(3|4|5)|phtml|pl|py|jsp|asp|htm|html|shtml|sh|cgi)\b#i', $name)) {
            throw new InvalidArgumentException('INVALID FILE NAME');
        }

        $upload = $this->get('upload');

        // add random string
        if ($upload['add_random']) {
            $name = $name . '_' . substr(md5(uniqid(rand(), 1)), 0, 5);
        }

        // rebuild file name - name + extension
        $name = $name . '.' . $ext;

        // create a filesystem result object
        $result = new WFFileSystemResult();

        $filesystem = $this->getFileSystem();
        $complete = false;
        $contentType = JRequest::getVar('CONTENT_TYPE', '', 'SERVER');

        // Only multipart uploading is supported for now
        if ($contentType && strpos($contentType, "multipart") !== false) {
            $result = $filesystem->upload('multipart', trim($file['tmp_name']), $dir, $name);

            if (!$result->state) {
                $result->message = WFText::_('WF_MANAGER_UPLOAD_ERROR');
                $result->code = 103;
            }

            @unlink($file['tmp_name']);

            $complete = true;
        } else {
            $result->state = false;
            $result->code = 103;
            $result->message = WFText::_('WF_MANAGER_UPLOAD_ERROR');

            $complete = true;
        }
        // upload finished
        if ($complete) {

            if ($result instanceof WFFileSystemResult) {
                if ($result->state === true) {

                    $path       = $result->path;
                    // get root dir eg: JPATH_SITE
                    $root       = substr($filesystem->getBaseDir(), 0, -(strlen($filesystem->getRootDir())));
                    
                    // get relative path
                    $relative   = substr($path, strlen($root)); 
                    // clean
                    $relative   = WFUtility::cleanPath($relative, '/');

                    $this->setResult($this->fireEvent('onUpload', array($result->path, $relative)));
                    $this->setResult(basename($result->path), 'files');
                } else {
                    $this->setResult($result->message, 'error');
                }
            }

            die(json_encode($this->getResult()));
        }
    }

    /**
     * Delete the relative file(s).
     * @param $files the relative path to the file name or comma seperated list of multiple paths.
     * @return string $error on failure.
     */
    public function deleteItem($items) {
        // check for feature access	
        if (!$this->checkFeature('delete', 'folder') && !$this->checkFeature('delete', 'file')) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        $filesystem = $this->getFileSystem();
        $items = explode(",", rawurldecode($items));

        foreach ($items as $item) {
            // decode
            $item = rawurldecode($item);

            // check path	
            WFUtility::checkPath($item);

            if ($filesystem->is_file($item)) {
                if ($this->checkFeature('delete', 'file') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            } elseif ($filesystem->is_dir($item)) {
                if ($this->checkFeature('delete', 'folder') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            }

            $result = $filesystem->delete($item);

            if ($result instanceof WFFileSystemResult) {
                if (!$result->state) {
                    if ($result->message) {
                        $this->setResult($result->message, 'error');
                    } else {
                        $this->setResult(JText::sprintf('WF_MANAGER_DELETE_' . strtoupper($result->type) . '_ERROR', basename($item)), 'error');
                    }
                } else {
                    $this->setResult($this->fireEvent('on' . ucfirst($result->type) . 'Delete', array($item)));
                    $this->setResult($item, $result->type);
                }
            }
        }

        return $this->getResult();
    }

    /**
     * Rename a file.
     * @param string $src The relative path of the source file
     * @param string $dest The name of the new file
     * @return string $error
     */
    public function renameItem() {
        // check for feature access	
        if (!$this->checkFeature('rename', 'folder') && !$this->checkFeature('rename', 'file')) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        $args = func_get_args();

        $source = array_shift($args);
        $destination = array_shift($args);

        $source = rawurldecode($source);
        $destination = rawurldecode($destination);

        WFUtility::checkPath($source);
        WFUtility::checkPath($destination);

        // check for extension in destination name
        if (preg_match('#\.(php|php(3|4|5)|phtml|pl|py|jsp|asp|htm|html|shtml|sh|cgi)\b#i', $destination)) {
            JError::raiseError(403, 'INVALID FILE NAME');
        }

        $filesystem = $this->getFileSystem();

        if ($filesystem->is_file($source)) {
            if ($this->checkFeature('rename', 'file') === false) {
                JError::raiseError(403, 'Access to this resource is restricted');
            }
        } elseif ($filesystem->is_dir($source)) {
            if ($this->checkFeature('rename', 'folder') === false) {
                JError::raiseError(403, 'Access to this resource is restricted');
            }
        }

        $result = $filesystem->rename($source, WFUtility::makeSafe($destination, $this->get('websafe_mode'), $this->get('websafe_spaces')), $args);

        if ($result instanceof WFFileSystemResult) {
            if (!$result->state) {
                $this->setResult(WFText::sprintf('WF_MANAGER_RENAME_' . strtoupper($result->type) . '_ERROR', basename($source)), 'error');
                if ($result->message) {
                    $this->setResult($result->message, 'error');
                }
            } else {
                $this->setResult($this->fireEvent('on' . ucfirst($result->type) . 'Rename', array($destination)));
                $this->setResult($destination, $result->type);
            }
        }

        return $this->getResult();
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function copyItem($items, $destination) {
        // check for feature access	
        if (!$this->checkFeature('move', 'folder') && !$this->checkFeature('move', 'file')) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        $filesystem = $this->getFileSystem();

        $items = explode(",", rawurldecode($items));

        // decode
        $destination = rawurldecode($destination);

        // check destination path
        WFUtility::checkPath($destination);

        foreach ($items as $item) {
            // decode
            $item = rawurldecode($item);

            // check source path
            WFUtility::checkPath($item);

            if ($filesystem->is_file($item)) {
                if ($this->checkFeature('move', 'file') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            } elseif ($filesystem->is_dir($item)) {
                if ($this->checkFeature('move', 'folder') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            }

            $result = $filesystem->copy($item, $destination);

            if ($result instanceof WFFileSystemResult) {
                if (!$result->state) {
                    if ($result->message) {
                        $this->setResult($result->message, 'error');
                    } else {
                        $this->setResult(JText::sprintf('WF_MANAGER_COPY_' . strtoupper($result->type) . '_ERROR', basename($item)), 'error');
                    }
                } else {
                    $this->setResult($this->fireEvent('on' . ucfirst($result->type) . 'Copy', array($item)));
                    $this->setResult($destination, $result->type);
                }
            }
        }
        return $this->getResult();
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function moveItem($items, $destination) {
        // check for feature access	
        if (!$this->checkFeature('move', 'folder') && !$this->checkFeature('move', 'file')) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        $filesystem = $this->getFileSystem();

        $items = explode(",", rawurldecode($items));

        // decode
        $destination = rawurldecode($destination);

        // check destination path
        WFUtility::checkPath($destination);

        foreach ($items as $item) {
            // decode
            $item = rawurldecode($item);
            // check source path
            WFUtility::checkPath($item);

            if ($filesystem->is_file($item)) {
                if ($this->checkFeature('move', 'file') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            } elseif ($filesystem->is_dir($item)) {
                if ($this->checkFeature('move', 'folder') === false) {
                    JError::raiseError(403, 'Access to this resource is restricted');
                }
            }

            $result = $filesystem->move($item, $destination);

            if ($result instanceof WFFileSystemResult) {
                if (!$result->state) {
                    if ($result->message) {
                        $this->setResult($result->message, 'error');
                    } else {
                        $this->setResult(JText::sprintf('WF_MANAGER_MOVE_' . strtoupper($result->type) . '_ERROR', basename($item)), 'error');
                    }
                } else {
                    $this->setResult($this->fireEvent('on' . ucfirst($result->type) . 'Move', array($item)));
                    $this->setResult($destination, $result->type);
                }
            }
        }
        return $this->getResult();
    }

    /**
     * New folder
     * @param string $dir The base dir
     * @param string $new_dir The folder to be created
     * @return string $error on failure
     */
    public function folderNew() {
        if ($this->checkFeature('create', 'folder') === false) {
            JError::raiseError(403, 'Access to this resource is restricted');
        }

        $args = func_get_args();

        $dir = array_shift($args);
        $new = array_shift($args);

        // decode
        $dir = rawurldecode($dir);
        $new = rawurldecode($new);

        $filesystem = $this->getFileSystem();

        $result = $filesystem->createFolder($dir, WFUtility::makeSafe($new, $this->get('websafe_mode'), $this->get('websafe_spaces')), $args);

        if ($result instanceof WFFileSystemResult) {
            if (!$result->state) {
                if ($result->message) {
                    $this->setResult($result->message, 'error');
                } else {
                    $this->setResult(JText::sprintf('WF_MANAGER_NEW_FOLDER_ERROR', basename($new)), 'error');
                }
            } else {
                $this->setResult($this->fireEvent('onFolderNew', array($new)));
            }
        }

        return $this->getResult();
    }

    private function getUploadValue() {
        $upload = trim(ini_get('upload_max_filesize'));
        $post = trim(ini_get('post_max_size'));

        $upload = WFUtility::convertSize($upload);
        $post = WFUtility::convertSize($post);

        if (intval($upload) <= intval($post)) {
            return $upload;
        }

        return $post;
    }

    private function getUploadDefaults() {
        $filesystem = $this->getFileSystem();
        $features = $filesystem->get('upload');
        $elements = isset($features['elements']) ? $features['elements'] : array();

        $upload_max = $this->getUploadValue();

        $upload = $this->get('upload');

        if (empty($upload['max_size'])) {
            $upload['max_size'] = 1024;
        }
        
        // get upload size
        $size = intval(preg_replace('/[^0-9]/', '', $upload['max_size'])) . 'kb';

        // must not exceed server maximum
        if ((int) $size * 1024 > (int) $upload_max) {
            $size = $upload_max / 1024 . 'kb';
        }

        $runtimes = array();

        if (is_string($upload['runtimes'])) {
            $runtimes = explode(',', $upload['runtimes']);
        } else {
            foreach ($upload['runtimes'] as $k => $v) {
                $runtimes[] = $v;
            }
        }

        // remove flash runtime if $chunk_size is 0 (no chunking)
        /* if (!$chunk_size) {
          unset($runtimes[array_search('flash', $runtimes)]);
          } */

        $defaults = array(
            'runtimes' => implode(',', $runtimes),
            'size' => $size,
            'filter' => $this->mapUploadFileTypes(true),
            'elements' => $elements
        );

        // only add chunk size if it has a value
        /* if ($chunk_size) {
          $defaults['chunk_size'] = $chunk_size;
          } */

        if (isset($features['dialog'])) {
            $defaults['dialog'] = $features['dialog'];
        }

        return $defaults;
    }

    public function getDimensions($file) {
        $filesystem = $this->getFileSystem();
        return $filesystem->getDimensions($file);
    }

    protected function getSettings($settings = array()) {
        $filesystem = $this->getFileSystem();

        $default = array(
            'dir' => $filesystem->getRootDir(),
            'actions' => $this->getActions(),
            'buttons' => $this->getButtons(),
            'upload' => $this->getUploadDefaults(),
            'folder_tree' => $this->get('folder_tree'),
            'listlimit' => $this->get('list_limit'),
            'websafe_mode' => $this->get('websafe_mode'),
            'websafe_spaces' => $this->get('websafe_spaces')
        );

        $properties = array('base', 'delete', 'rename', 'folder_new', 'copy', 'move');

        foreach ($properties as $property) {
            if ($filesystem->get($property)) {
                $default[$property] = $filesystem->get($property);
            }
        }

        $settings = array_merge_recursive($default, $settings);

        return $settings;
    }

}

?>
