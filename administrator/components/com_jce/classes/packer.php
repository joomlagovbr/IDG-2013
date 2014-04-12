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

class WFPacker extends JObject {

    protected $files = array();
    protected $type = 'javascript';
    protected $text = '';
    protected $start = '';
    protected $end = '';

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    function __construct($config = array()) {
        $this->setProperties($config);
    }

    public function setFiles($files = array()) {
        $this->files = $files;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setText($text = '') {
        $this->text = $text;
    }

    public function setContentStart($start = '') {
        $this->start = $start;
    }

    public function getContentStart() {
        return $this->start;
    }

    public function setContentEnd($end = '') {
        $this->end = $end;
    }

    public function getContentEnd() {
        return $this->end;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * Get encoding
     * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
     */
    private static function getEncoding() {
        if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            return false;
        }

        $encoding = false;

        if (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
            $encoding = 'gzip';
        }

        if (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
            $encoding = 'x-gzip';
        }

        return $encoding;
    }

    public function pack($minify = true, $gzip = false) {
        $type = $this->getType();

        /* $encoding = self::getEncoding();

          $zlib = extension_loaded('zlib') && !ini_get('zlib.output_compression');
          $gzip = $gzip && !empty($encoding) && $zlib && function_exists('gzencode'); */

        ob_start();

        // Headers
        if ($type == 'javascript') {
            header("Content-type: application/javascript; charset: UTF-8");
        }

        if ($type == 'css') {
            header("Content-type: text/css; charset: UTF-8");
        }

        header("Vary: Accept-Encoding");

        // expires after 48 hours
        $expires = 60 * 60 * 24 * 2;

        header("Cache-Control: maxage=" . $expires);

        // Handle proxies
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expires) . " GMT");

        $files = $this->getFiles();

        $encoding = self::getEncoding();

        $zlib = extension_loaded('zlib') && ini_get('zlib.output_compression');
        $gzip = $gzip && !empty($encoding) && $zlib && function_exists('gzencode');

        $content = $this->getContentStart();

        if (empty($files)) {
            $content .= $this->getText();
        } else {
            foreach ($files as $file) {
                $content .= $this->getText($file, $minify);
            }
        }

        $content .= $this->getContentEnd();

        // Generate GZIP'd content
        if ($gzip) {
            header("Content-Encoding: " . $encoding);
            $content = gzencode($content, 4, FORCE_GZIP);
        }

        // get content hash
        $hash = hash('md5', $content);

        // set etag header
        header("ETag: \"{$hash}\"");

        // set content length
        header("Content-Length: " . strlen($content));

        // stream to client
        echo $content;

        exit(ob_get_clean());
    }

    protected function jsmin($data) {
        // remove header comments
        return preg_replace('#^\/\*[\s\S]+?\*\/#', '', $data);
    }

    /**
     * Simple CSS Minifier
     * @param $data Data string to minify
     */
    protected function cssmin($data) {
        $data = str_replace('\r\n', '\n', $data);

        $data = preg_replace('#\s+#', ' ', $data);
        $data = preg_replace('#/\*.*?\*/#s', '', $data);
        $data = preg_replace('#\s?([:\{\};,])\s?#', '$1', $data);

        $data = str_replace(';}', '}', $data);

        return trim($data);
    }

    /**
     * Import CSS from a file
     * @param file File path where data comes from
     * @param $data Data from file
     */
    protected function importCss($data) {
        if (preg_match_all('#@import url\([\'"]?([^\'"\)]+)[\'"]?\);#i', $data, $matches)) {

            $data = '';

            foreach ($matches[1] as $match) {
                // url has a query, remove
                if (strpos($match, '?') !== false) {
                    $match = substr($match, 0, strpos($match, '?'));
                }
                
                if (strpos($match, '&') !== false) {
                    $match = substr($match, 0, strpos($match, '&'));
                }

                if ($match) {
                    $data .= $this->getText(realpath($this->get('_cssbase') . '/' . $match));
                }
            }

            return $data;
        }

        return '';
    }

    protected function compileLess($string, $path) {
        require_once(WF_ADMINISTRATOR . '/classes/lessc.inc.php');

        $less = new lessc;
        // add file directory
        $less->addImportDir($path);
        // add joomla media folder
        $less->addImportDir(JPATH_SITE . 'media');

        try {
            return $less->compile($string);
        } catch (Exception $e) {
            return "/* LESS file could not be compiled due to error - " . $e->getMessage() . " */";
        }
    }

    protected function getText($file = null, $minify = true) {

        if ($file && is_file($file)) {

            if ($text = file_get_contents($file)) {
                // process css files
                if ($this->getType() == 'css') {

                    // compile less files
                    if (preg_match('#\.less$#', $file)) {
                        $text = $this->compileLess($text, dirname($file));
                    }

                    if (strpos($text, '@import') !== false) {
                        // store the base path of the current file
                        $this->set('_cssbase', dirname($file));

                        // process import rules
                        $text = $this->importCss($text) . preg_replace('#@import url\([\'"]?([^\'"\)]+)[\'"]?\);#i', '', $text);
                    }

                    // store the base path of the current file
                    $this->set('_imgbase', dirname($file));

                    // process urls
                    $text = preg_replace_callback('#url\s?\([\'"]?([^\'"\))]+)[\'"]?\)#', array('WFPacker', 'processPaths'), $text);

                    if ($minify) {
                        // minify
                        $text = $this->cssmin($text);
                    }
                }
                // make sure text ends in a semi-colon;
                if ($this->getType() == 'javascript') {
                    $text = rtrim(trim($text), ';') . ';';

                    if ($minify) {
                        $text = $this->jsmin($text);
                    }
                }

                return $text;
            }
        }

        return $this->text;
    }

    protected function processPaths($data) {
        $path = str_replace(JPATH_SITE, '', realpath($this->get('_imgbase') . '/' . $data[1]));

        if ($path) {
            return "url('" . JURI::root(true) . str_replace('\\', '/', $path) . "')";
        }

        return "url('" . $data[1] . "')";
    }

}

?>