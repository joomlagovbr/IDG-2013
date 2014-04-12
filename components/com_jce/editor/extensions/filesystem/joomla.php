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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class WFJoomlaFileSystem extends WFFileSystem {

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    function __construct($config = array()) {
        parent::__construct($config);

        $safe_mode = false;

        // check for safe mode
        if (function_exists('ini_get')) {
            $safe_mode = ini_get('safe_mode');
            // assume safe mode if can't check ini
        } else {
            $safe_mode = true;
        }

        $this->setProperties(array(
            'local' => true,
            'upload' => array(
                'stream' => false,
                //'chunking' => $chunking,
                'unique_filenames' => true
            )
        ));
    }

    /**
     * Get the base directory.
     * @return string base dir
     */
    function getBaseDir() {
        return WFUtility::makePath(JPATH_SITE, $this->getRootDir());
    }

    /**
     * Get the full base url
     * @return string base url
     */
    function getBaseURL() {
        return WFUtility::makePath(JURI::root(true), $this->getRootDir());
    }

    /**
     * Return the full user directory path. Create if required
     *
     * @param string	The base path
     * @access public
     * @return Full path to folder
     */
    function getRootDir() {
        static $root;

        if (!isset($root)) {

            $root = parent::getRootDir();
            $wf = WFEditorPlugin::getInstance();

            // Restricted Joomla! folders
            $restricted = explode(',', $wf->getParam('editor.filesystem.joomla.restrict_dir', 'administrator,cache,components,includes,language,libraries,logs,media,modules,plugins,templates,xmlrpc'));
            $allowroot = $wf->getParam('editor.filesystem.joomla.allow_root', 0);

            // Revert to default if empty
            if (empty($root) && !$allowroot) {
                $root = 'images';
            }
            // Force default if directory is a joomla directory
            $parts = explode('/', $root);

            if (in_array(strtolower($parts[0]), $restricted) && !$allowroot) {
                $root = 'images';
            }

            if (!empty($root)) {
                // Create the folder
                $full = WFUtility::makePath(JPATH_SITE, $root);

                if (!JFolder::exists($full)) {
                    $this->folderCreate($full);
                }

                // Fallback
                $root = JFolder::exists($full) ? $root : 'images';
            }
        }

        return $root;
    }

    function toAbsolute($path) {
        return WFUtility::makePath($this->getBaseDir(), $path);
    }

    function toRelative($path, $isabsolute = true) {
        // path is relative to Joomla! root, eg: images/folder
        if ($isabsolute === false) {
            return rtrim($path, $this->getRootDir());
        }

        // path is absolute
        return rtrim($path, $this->getBaseDir());
    }

    /**
     * Determine whether FTP mode is enabled
     * @return boolean
     */
    function isFtp() {
        // Initialize variables
        jimport('joomla.client.helper');
        $FTPOptions = JClientHelper::getCredentials('ftp');

        return $FTPOptions['enabled'] == 1;
    }

    /**
     * Count the number of folders in a given folder
     * @return integer Total number of folders
     * @param string $path Absolute path to folder
     */
    public function countFolders($path) {
        jimport('joomla.filesystem.folder');
        $total = 0;

        if (strpos($path, $this->getBaseDir()) === false) {
            $path = WFUtility::makePath($this->getBaseDir(), $path);
        }

        if (JFolder::exists($path)) {
            $folders = JFolder::folders($path);
            return count($folders);
        }

        return 0;
    }

    /**
     * Count the number of files in a folder
     * @return integer File total
     * @param string $path Absolute path to folder
     */
    public function countFiles($path) {
        jimport('joomla.filesystem.file');

        if (strpos($path, $this->getBaseDir()) === false) {
            $path = WFUtility::makePath($this->getBaseDir(), $path);
        }

        if (JFolder::exists($path)) {
            $files = JFolder::files($path, '.', false, false, array('index.html', 'thumbs.db'));
            return count($files);
        }

        return 0;
    }

    function getFolders($relative, $filter = '') {
        $path = WFUtility::makePath($this->getBaseDir(), $relative);
        $path = WFUtility::fixPath($path);

        if (!JFolder::exists($path)) {
            $relative = '/';
            $path = $this->getBaseDir();
        }

        $list = JFolder::folders($path, $filter);

        $folders = array();

        if (!empty($list)) {
            // Sort alphabetically
            natcasesort($list);
            foreach ($list as $item) {
                $item = WFUtility::isUTF8($item) ? $item : utf8_encode($item);

                $data = array(
                    'id' => WFUtility::makePath($relative, $item, '/'),
                    'name' => $item,
                    'writable' => is_writable(WFUtility::makePath($path, $item)) || $this->isFtp(),
                    'type' => 'folders'
                );

                $properties = self::getFolderDetails($data['id']);
                $folders[] = array_merge($data, array('properties' => $properties));
            }
        }
        return $folders;
    }

    function getFiles($relative, $filter = '') {
        $path = WFUtility::makePath($this->getBaseDir(), $relative);
        $path = WFUtility::fixPath($path);

        if (!JFolder::exists($path)) {
            $relative = '/';
            $path = $this->getBaseDir();
        }

        $list = JFolder::files($path, $filter);

        $files = array();

        $x = 1;

        if (!empty($list)) {
            // Sort alphabetically
            natcasesort($list);
            foreach ($list as $item) {
                $item = WFUtility::isUTF8($item) ? $item : utf8_encode($item);

                // create relative file
                $id = WFUtility::makePath($relative, $item, '/');

                // create url
                $url = WFUtility::makePath($this->getRootDir(), $id, '/');

                // remove leading slash
                $url = ltrim($url, '/');

                $data = array(
                    'id' => $id,
                    'url' => $url,
                    'name' => $item,
                    'writable' => is_writable(WFUtility::makePath($path, $item)) || $this->isFtp(),
                    'type' => 'files'
                );

                $properties = self::getFileDetails($data['id'], $x);

                $files[] = array_merge($data, array('properties' => $properties));

                $x++;
            }
        }

        return $files;
    }

    /**
     * Get a folders properties
     * 
     * @return array Array of properties
     * @param string $dir Folder relative path
     * @param string $types File Types
     */
    function getFolderDetails($dir) {
        clearstatcache();

        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($dir));
        $date = @filemtime($path);

        return array('modified' => $date);
    }

    /**
     * Get the source directory of a file path
     */
    function getSourceDir($path) {
        // return nothing if absolute $path	
        if (preg_match('#^(file|http(s)?):\/\/#', $path)) {
            return '';
        }

        // remove leading / trailing slash
        //$path = trim($path, '/');
        // directory path relative to base dir
        if (is_dir(WFUtility::makePath($this->getBaseDir(), $path))) {
            return $path;
        }

        // file url relative to site root
        if (is_file(WFUtility::makePath(JPATH_SITE, $path))) {
            return substr(dirname($path), strlen($this->getRootDir()));
        }

        return '';
    }

    function isMatch($needle, $haystack) {
        return $needle == $haystack;
    }

    /**
     * Return constituent parts of a file path eg: base directory, file name
     * @param $path Relative or absolute path
     */
    public function pathinfo($path) {
        return pathinfo($path);
    }

    /**
     * Get a files properties
     * 
     * @return array Array of properties
     * @param string $file File relative path
     */
    public function getFileDetails($file, $count = 1) {
        clearstatcache();

        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($file));
        $url = WFUtility::makePath($this->getBaseUrl(), rawurldecode($file));

        $date = @filemtime($path);
        $size = @filesize($path);

        $data = array(
            'size' => $size,
            'modified' => $date
        );

        if (preg_match('#\.(jpg|jpeg|bmp|gif|tiff|png)#i', $file) && $count <= 100) {
            $props = @getimagesize($path);

            /* if (preg_match('#\.(jpg|jpeg|tiff)#i', $file)) {
              $data = exif_read_data($path, 'IDF0', true, false);

              if ($data !== false) {
              $idf 	= isset($data['IDF0']) ? $data['IDF0'] : array();
              $exif 	= isset($data['EXIF']) ? $data['EXIF'] : array();
              $data 	= array_merge($idf, $exif);
              }
              } */

            $width = $props[0];
            $height = $props[1];

            $image = array(
                'width' => $width,
                'height' => $height,
                'preview' => WFUtility::cleanPath($url, '/')
            );

            return array_merge_recursive($data, $image);
        }

        return $data;
    }

    /**
     * Delete the relative file(s).
     * @param $files the relative path to the file name or comma seperated list of multiple paths.
     * @return string $error on failure.
     */
    public function delete($src) {
        $path = WFUtility::makePath($this->getBaseDir(), $src);

        // get error class
        $result = new WFFileSystemResult();

        $path = WFUtility::makePath($this->getBaseDir(), $src);

        if (is_file($path)) {
            $result->type = 'files';
            $result->state = JFile::delete($path);
        } else if (is_dir($path)) {
            $result->type = 'folders';

            if ($this->countFiles($path) > 0 || $this->countFolders($path) > 0) {
                $result->message = JText::sprintf('WF_MANAGER_FOLDER_NOT_EMPTY', basename($path));
            } else {
                $result->state = JFolder::delete($path);
            }
        }

        return $result;
    }

    /**
     * Rename a file.
     * @param string $src The relative path of the source file
     * @param string $dest The name of the new file
     * @return string $error
     */
    public function rename($src, $dest) {

        $src = WFUtility::makePath($this->getBaseDir(), rawurldecode($src));
        $dir = dirname($src);

        $result = new WFFileSystemResult();

        if (is_file($src)) {
            $ext = JFile::getExt($src);
            $file = $dest . '.' . $ext;
            $path = WFUtility::makePath($dir, $file);

            if (is_file($path)) {
                return $result;
            }

            $result->type = 'files';
            $result->state = JFile::move($src, $path);
            $result->path = $path;
        } else if (is_dir($src)) {
            $path = WFUtility::makePath($dir, $dest);
            
            if (is_dir($path)) {
                return $result;
            }

            $result->type = 'folders';
            $result->state = JFolder::move($src, $path);
            $result->path = $path;
        }

        return $result;
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function copy($file, $destination) {
        $result = new WFFileSystemResult();

        $src = WFUtility::makePath($this->getBaseDir(), $file);
        $dest = WFUtility::makePath($this->getBaseDir(), WFUtility::makePath($destination, basename($file)));

        // src is a file
        if (is_file($src)) {
            $result->type = 'files';
            $result->state = JFile::copy($src, $dest);
        } else if (is_dir($src)) {
            // Folders cannot be copied into themselves as this creates an infinite copy / paste loop	
            if ($file === $destination) {
                $result->state = false;
                $result->message = WFText::_('WF_MANAGER_COPY_INTO_ERROR');
            }

            $result->type = 'folders';
            $result->state = JFolder::copy($src, $dest);
            $result->path = $dest;
        }

        return $result;
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function move($file, $destination) {
        $result = new WFFileSystemResult();

        $src = WFUtility::makePath($this->getBaseDir(), $file);
        $dest = WFUtility::makePath($this->getBaseDir(), WFUtility::makePath($destination, basename($file)));

        if ($src != $dest) {
            // src is a file
            if (is_file($src)) {
                $result->type = 'files';
                $result->state = JFile::move($src, $dest);
            } else if (is_dir($src)) {
                $result->type = 'folders';
                $result->state = JFolder::move($src, $dest);
                $result->path = $dest;
            }
        }

        return $result;
    }

    /**
     * New folder base function. A wrapper for the JFolder::create function
     * @param string $folder The folder to create
     * @return boolean true on success
     */
    public function folderCreate($folder) {
        if (@JFolder::create($folder)) {
            $buffer = '<html><body bgcolor="#FFFFFF"></body></html>';
            JFile::write($folder . '/index.html', $buffer);
        } else {
            return false;
        }
        return true;
    }

    /**
     * New folder
     * @param string $dir The base dir
     * @param string $new_dir The folder to be created
     * @return string $error on failure
     */
    public function createFolder($dir, $new) {
        $dir = WFUtility::makePath(rawurldecode($dir), $new);
        $path = WFUtility::makePath($this->getBaseDir(), $dir);
        $result = new WFFileSystemResult();

        $result->state = $this->folderCreate($path);

        return $result;
    }

    public function getDimensions($file) {
        $path = WFUtility::makePath($this->getBaseDir(), utf8_decode(rawurldecode($file)));
        $data = array(
            'width' => '',
            'height' => ''
        );
        if (file_exists($path)) {
            $dim = @getimagesize($path);
            $data = array(
                'width' => $dim[0],
                'height' => $dim[1]
            );
        }
        return $data;
    }

    public function upload($method = 'multipart', $src, $dir, $name, $chunks = 1, $chunk = 0) {
        jimport('joomla.filesystem.file');

        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($dir));
        $dest = WFUtility::makePath($path, $name);

        // check for safe mode
        $safe_mode = false;

        if (function_exists('ini_get')) {
            $safe_mode = ini_get('safe_mode');
        } else {
            $safe_mode = true;
        }

        $result = new WFFileSystemResult();

        // get overwrite state
        $conflict = $this->get('upload_conflict', 'overwrite');
        // get suffix
        $suffix = WFFileBrowser::getFileSuffix();

        switch ($method) {
            case 'multipart' :
                if ($conflict == 'unique') {
                    // get extension
                    $extension = JFile::getExt($name);
                    // get name without extension
                    $name = JFile::stripExt($name);

                    while (JFile::exists($dest)) {
                        $name .= $suffix;
                        $dest = WFUtility::makePath($path, $name . '.' . $extension);
                    }
                }

                if (JFile::upload($src, $dest)) {
                    $result->state = true;
                    $result->path = $dest;
                }

                break;
            case 'multipart-chunking' :
                if ($safe_mode || !is_writable(dirname($dest))) {
                    $result->message = WFText::_('WF_MANAGER_UPLOAD_NOSUPPORT');
                    $result->code = 103;
                } else {
                    if ($chunk == 0 && $overwrite) {
                        // get extension
                        $extension = JFile::getExt($name);
                        // get name without extension
                        $name = JFile::stripExt($name);

                        // make unique file name
                        while (JFile::exists($dest)) {
                            $name .= $suffix;
                            $dest = WFUtility::makePath($path, $name . '.' . $extension);
                        }
                    }

                    $out = fopen($dest, $chunk == 0 ? "wb" : "ab");

                    if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen($src, "rb");

                        if ($in) {
                            while ($buff = fread($in, 4096)) {
                                fwrite($out, $buff);
                            }

                            fclose($in);
                            fclose($out);
                            @unlink($src);

                            $result->state = true;

                            if ($chunk == $chunks - 1) {
                                if (is_file($dest)) {
                                    $result->path = $dest;
                                }
                            }
                        } else {
                            $result->code = 102;
                            $result->message = 'UPLOAD_INPUT_STREAM_ERROR';
                        }
                    } else {
                        $result->code = 102;
                        $result->message = 'UPLOAD_OUTPUT_STREAM_ERROR';
                    }
                }
                break;
            case 'stream' :
                if ($safe_mode || !is_writable(dirname($dest))) {
                    $result->message = WFText::_('WF_MANAGER_UPLOAD_NOSUPPORT');
                } else {
                    // Open destination file
                    $out = fopen($dest, $chunk == 0 ? "wb" : "ab");

                    if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen("php://input", "rb");

                        if ($in) {
                            while ($buff = fread($in, 4096)) {
                                fwrite($out, $buff);
                            }

                            if (fclose($out) && is_file($dest)) {
                                $result->state = true;
                                $result->path = $dest;
                            }
                        }
                    }
                }
                break;
        }

        return $result;
    }

    public function exists($path) {
        $path = JPath::clean(WFUtility::makePath($this->getBaseDir(), rawurldecode($path)));

        return is_dir($path) || is_file($path);
    }

    public function read($file) {
        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($file));

        return JFile::read($path);
    }

    public function write($file, $content) {
        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($file));

        return JFile::write($path, $content);
    }

    public function is_file($path) {
        $path = WFUtility::makePath($this->getBaseDir(), $path);

        return is_file($path);
    }

    public function is_dir($path) {
        $path = WFUtility::makePath($this->getBaseDir(), $path);

        return is_dir($path);
    }

}
