<?php

/**
 * @package    JCE
 * @copyright  Copyright (c) 2010-2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @copyright  Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license    GNU LGPLv3 or later <http://www.gnu.org/copyleft/lesser.html>
 * 
 * Based on the LiveUpdateDownloadHelper class from Akeeba LiveUpdate
 */
defined('_JEXEC') or die();

/**
 * Check for and download updates from a remote server
 */
class UpdatesHelper {

    private static function applyCACert(&$ch) {
        $cacert = JPATH_LIBRARIES . '/joomla/http/transport/cacert.pem';

        if (file_exists($cacert)) {
            @curl_setopt($ch, CURLOPT_CAINFO, $cacert);
        }
    }

    /**
     * Fetches update information from the server using cURL or fopen
     * @param   string $url     The URL to check
     * @param   string $data    Data to send via POST
     * 
     * @return  mixed  Result string on success, false on failure 
     */
    public function check($url, $data) {
        $result = false;

        if (self::hasCURL()) {
            $ch = curl_init($url);

            self::applyCACert($ch);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            // Pretend we are Firefox, so that webservers play nice with us
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.14) Gecko/20110105 Firefox/3.6.14');
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // The @ sign allows the next line to fail if open_basedir is set or if safe mode is enabled
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($ch, CURLOPT_MAXREDIRS, 20);

            // add post data
            if ($data) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }

            $result = curl_exec($ch);

            // file download
            if ($result === false) {
                return array('error' => 'CURL ERROR : ' . curl_error($ch));
            }

            curl_close($ch);
        } else if (self::hasFOPEN()) {
            $options = array('http' => array('method' => 'POST', 'timeout' => 10, 'content' => $data));

            $context = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);

            if ($result === false) {
                return array('error' => WFText::_('Update check failed : Invalid response from update server'));
            }
        }

        return $result;
    }

    /**
     * Downloads from a URL and saves the result as a local file
     *
     * @param   string  $url     The URL to fetch
     * @param   string  $target  Where to save the file
     *
     * @return  boolean  True on success
     */
    public static function download($url, $target) {
        // Import Joomla! libraries
        JLoader::import('joomla.filesystem.file');

        /** @var bool Did we try to force permissions? */
        $hackPermissions = false;

        // Make sure the target does not exist
        if (JFile::exists($target)) {
            if (!@unlink($target)) {
                JFile::delete($target);
            }
        }

        // Try to open the output file for writing
        $fp = @fopen($target, 'wb');

        if ($fp === false) {
            // The file can not be opened for writing. Let's try a hack.
            $empty = '';
            if (JFile::write($target, $empty)) {
                if (self::chmod($target, 511)) {
                    $fp = @fopen($target, 'wb');
                    $hackPermissions = true;
                }
            }
        }

        $result = false;

        if ($fp !== false) {
            // First try to download directly to file if $fp !== false
            $adapters = self::getAdapters();
            $result = false;

            while (!empty($adapters) && ($result === false)) {
                // Run the current download method
                $method = 'get' . strtoupper(array_shift($adapters));
                $result = self::$method($url, $fp);

                // Check if we have a download
                if ($result === true) {
                    // The download is complete, close the file pointer
                    @fclose($fp);

                    // If the filesize is not at least 1 byte, we consider it failed.
                    clearstatcache();
                    $filesize = @filesize($target);

                    if ($filesize <= 0) {
                        $result = false;
                        $fp = @fopen($target, 'wb');
                    }
                }
            }

            // If we have no download, close the file pointer
            if ($result === false) {
                @fclose($fp);
            }
        }

        if ($result === false) {
            // Delete the target file if it exists
            if (file_exists($target)) {
                if (!@unlink($target)) {
                    JFile::delete($target);
                }
            }
            // Download and write using JFile::write();
            $result = JFile::write($target, self::downloadAndReturn($url));
        }

        return $result;
    }

    /**
     * Downloads from a URL and returns the result as a string
     *
     * @param   string  $url  The URL to download from
     *
     * @return  mixed  Result string on success, false on failure
     */
    public static function downloadAndReturn($url) {
        $adapters = self::getAdapters();
        $result = false;

        while (!empty($adapters) && ($result === false)) {
            // Run the current download method
            $method = 'get' . strtoupper(array_shift($adapters));
            $result = self::$method($url, null);
        }

        return $result;
    }

    /**
     * Does the server support PHP's cURL extension?
     *
     * @return   boolean  True if it is supported
     */
    public static function hasCURL() {
        static $result = null;

        if (is_null($result)) {
            $result = function_exists('curl_init');

            if ($result) {
                $cacert = JPATH_LIBRARIES . '/joomla/http/transport/cacert.pem';

                // check for SSL support
                $version = curl_version();
                $ssl_supported = ($version['features'] & CURL_VERSION_SSL);

                $result = (bool) $ssl_supported && file_exists($cacert);
            }
        }

        return $result;
    }

    /**
     * Downloads the contents of a URL and writes them to disk (if $fp is not null)
     * or returns them as a string (if $fp is null) using cURL
     *
     * @param   string    $url  The URL to download from
     * @param   resource  $fp   The file pointer to download to. Omit to return the contents.
     *
     * @return  boolean|string  False on failure, true on success ($fp not null) or the URL contents (if $fp is null)
     */
    private static function &getCURL($url, $fp = null, $nofollow = false) {
        $result = false;

        $ch = curl_init($url);

        self::applyCACert($ch);

        if (!@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1) && !$nofollow) {
            // Safe Mode is enabled. We have to fetch the headers and
            // parse any redirections present in there.
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            // Get the headers
            $data = curl_exec($ch);
            curl_close($ch);

            // Init
            $newURL = $url;

            // Parse the headers
            $lines = explode("\n", $data);

            foreach ($lines as $line) {
                if (substr($line, 0, 9) == "Location:") {
                    $newURL = trim(substr($line, 9));
                }
            }

            // Download from the new URL
            if ($url != $newURL) {
                return self::getCURL($newURL, $fp);
            } else {
                return self::getCURL($newURL, $fp, true);
            }
        } else {
            @curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
        }

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        // Pretend we are IE7, so that webservers play nice with us
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');

        if (is_resource($fp)) {
            curl_setopt($ch, CURLOPT_FILE, $fp);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Does the server support URL fopen() wrappers?
     *
     * @return  boolean
     */
    public static function hasFOPEN() {
        static $result = null;

        if (is_null($result)) {
            // If we are not allowed to use ini_get, we assume that URL fopen is
            // disabled.
            if (!function_exists('ini_get')) {
                $result = false;
            } else {
                // get wrappers
                $wrappers = stream_get_wrappers();

                $result = ini_get('allow_url_fopen') && in_array('https', $wrappers);
            }
        }

        return $result;
    }

    /**
     * Downloads the contents of a URL and writes them to disk (if $fp is not null)
     * or returns them as a string (if $fp is null) using fopen() URL wrappers
     *
     * @param   string    $url  The URL to download from
     * @param   resource  $fp   The file pointer to download to. Omit to return the contents.
     *
     * @return  boolean|string  False on failure, true on success ($fp not null) or the URL contents (if $fp is null)
     */
    private static function &getFOPEN($url, $fp = null) {
        $result = false;

        // Track errors
        if (function_exists('ini_set')) {
            $track_errors = ini_set('track_errors', true);
        }

        // Open the URL for reading
        if (function_exists('stream_context_create')) {
            // PHP 5+ way (best)
            $httpopts = array(
                'user_agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)',
                'timeout' => 10.0,
            );
            $context = stream_context_create(array('http' => $httpopts));
            $ih = @fopen($url, 'r', false, $context);
        } else {
            // PHP 4 way (actually, it's just a fallback as we can't run this code in PHP4)
            if (function_exists('ini_set')) {
                ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)');
            }
            $ih = @fopen($url, 'r');
        }

        // If fopen() fails, abort
        if (!is_resource($ih)) {
            return $result;
        }

        // Try to download
        $bytes = 0;
        $result = true;
        $return = '';
        while (!feof($ih) && $result) {
            $contents = fread($ih, 4096);
            if ($contents === false) {
                @fclose($ih);
                $result = false;
                return $result;
            } else {
                $bytes += strlen($contents);
                if (is_resource($fp)) {
                    $result = @fwrite($fp, $contents);
                } else {
                    $return .= $contents;
                    unset($contents);
                }
            }
        }

        @fclose($ih);

        if (is_resource($fp)) {
            return $result;
        } elseif ($result === true) {
            return $return;
        } else {
            return $result;
        }
    }

    /**
     * Detect and return available download methods
     *
     * @return  array
     */
    private static function getAdapters() {
        // Detect available adapters
        $adapters = array();
        if (self::hasCURL())
            $adapters[] = 'curl';
        if (self::hasFOPEN())
            $adapters[] = 'fopen';
        return $adapters;
    }

    /**
     * Change the permissions of a file, optionally using FTP
     *
     * @param   string  $file  Absolute path to file
     * @param   int     $mode  Permissions, e.g. 0755
     *
     * @return  boolean  Ture if successful
     */
    private static function chmod($path, $mode) {
        if (is_string($mode)) {
            $mode = octdec($mode);
            if (($mode < 0600) || ($mode > 0777))
                $mode = 0755;
        }

        // Initialize variables
        JLoader::import('joomla.client.helper');
        $ftpOptions = JClientHelper::getCredentials('ftp');

        // Check to make sure the path valid and clean
        $path = JPath::clean($path);

        if ($ftpOptions['enabled'] == 1) {
            // Connect the FTP client
            JLoader::import('joomla.client.ftp');
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $ftp = JClientFTP::getInstance(
                                $ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
                );
            } else {
                if (version_compare(JVERSION, '3.0', 'ge')) {
                    $ftp = JClientFTP::getInstance(
                                    $ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
                    );
                } else {
                    $ftp = JFTP::getInstance(
                                    $ftpOptions['host'], $ftpOptions['port'], array(), $ftpOptions['user'], $ftpOptions['pass']
                    );
                }
            }
        }

        if (@chmod($path, $mode)) {
            $ret = true;
        } elseif ($ftpOptions['enabled'] == 1) {
            // Translate path and delete
            JLoader::import('joomla.client.ftp');
            $path = JPath::clean(str_replace(JPATH_ROOT, $ftpOptions['root'], $path), '/');
            // FTP connector throws an error
            $ret = $ftp->chmod($path, $mode);
        } else {
            return false;
        }
    }

}
