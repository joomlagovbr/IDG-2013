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

final class WFRequest extends JObject {

    var $request = array();

    /**
     * Constructor activating the default information of the class
     *
     * @access  public
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Returns a reference to a WFRequest object
     *
     * This method must be invoked as:
     *    <pre>  $request = WFRequest::getInstance();</pre>
     *
     * @access  public
     * @return  object WFRequest
     */
    public static function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new WFRequest();
        }

        return $instance;
    }

    /**
     * Set Request function
     *
     * @access 	public
     * @param 	array	$function An array containing the function and object
     */
    public function setRequest($function) {
        $object = new StdClass();

        if (is_array($function)) {
            $name = $function[1];
            $ref = $function[0];

            $object->fn = $name;
            $object->ref = $ref;

            $this->request[$name] = $object;
        } else {
            $object->fn = $function;
            $this->request[$function] = $object;
        }
    }

    /**
     * Get a request function
     * @access 	public
     * @param 	string $function
     */
    public function getRequest($function) {
        return $this->request[$function];
    }

    /**
     * Check a request query for bad stuff
     * @access 	private
     * @param 	array $query
     */
    private function checkQuery($query) {
        if (is_string($query)) {
            $query = array($query);
        }

        // check for null byte
        foreach ($query as $key => $value) {
            if (is_array($value) || is_object($value)) {
                return self::checkQuery($value);
            }

            if (is_array($key)) {
                return self::checkQuery($key);
            }

            if (strpos($key, '\u0000') !== false || strpos($value, '\u0000') !== false) {
                JError::raiseError(403, 'RESTRICTED');
            }
        }
    }

    /**
     * Process an ajax call and return result
     *
     * @access public
     * @return string
     */
    public function process($array = false) {
        // Check for request forgeries
        WFToken::checkToken() or die('Access to this resource is restricted');

        $filter = JFilterInput::getInstance();

        $json   = JRequest::getVar('json', '', 'POST', 'STRING', 2);
        $action = JRequest::getWord('action');

        // set error handling for requests
        JError::setErrorHandling(E_ALL, 'callback', array('WFRequest', 'raiseError'));

        if ($action || $json) {
            // set request flag			
            define('JCE_REQUEST', 1);

            $output = array(
                "result" => null,
                "text" => null,
                "error" => null
            );

            if ($json) {                
                // remove slashes
                $json = stripslashes($json);
                
                // convert to JSON object
                $json = json_decode($json);
                
                // invalid JSON
                if (is_null($json)) {
                    throw new InvalidArgumentException('Invalid JSON');
                }
                
                // no function call
                if (isset($json->fn) === false) {
                    throw new InvalidArgumentException('Invalid Function Call');
                }
                
                // get function call
                $fn = $json->fn;
                
                // get arguments
                $args = isset($json->args) ? $json->args : array();
            } else {
                $fn     = $action;
                $args   = array();
            }
            
            // clean function
            $fn = $filter->clean($fn, 'cmd');

            // check query
            $this->checkQuery($args);

            // call function
            if (array_key_exists($fn, $this->request)) {
                $method = $this->request[$fn];

                // set default function call
                $call = null;

                if (!isset($method->ref)) {
                    $call = $method->fn;
                    if (!function_exists($call)) {
                        throw new InvalidArgumentException('Invalid Function -  "' . $call . '"');
                    }
                } else {
                    if (!method_exists($method->ref, $method->fn)) {
                        throw new InvalidArgumentException('Invalid Method "' . $method->ref . '::' . $method->fn . '"');
                    }
                    $call = array($method->ref, $method->fn);
                }

                if (!$call) {
                    throw new InvalidArgumentException('Invalid Function Call');
                }

                if (!is_array($args)) {
                    $result = call_user_func($call, $args);
                } else {
                    $result = call_user_func_array($call, $args);
                }
            } else {
                if ($fn) {
                    throw new InvalidArgumentException('Unregistered Function - "' . addslashes($fn) . '"');
                } else {
                    throw new InvalidArgumentException('Invalid Function Call');
                }
            }

            $output = array(
                "result" => $result
            );

            ob_start();

            // set output headers
            header('Content-Type: text/json;charset=UTF-8');
            header('Content-Encoding: UTF-8');
            header("Expires: Mon, 4 April 1984 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            echo json_encode($output);

            exit(ob_get_clean());
        }
    }

    /**
     * Format a JError object as a JSON string
     */
    public static function raiseError($error) {
        $data = array();

        $data[] = JError::translateErrorLevel($error->get('level')) . ' ' . $error->get('code') . ': ';

        if ($error->get('message')) {
            $data[] = $error->get('message');
        }

        $output = array(
            'result' => '',
            'error' => true,
            'code' => $error->get('code'),
            'text' => $data
        );

        header('Content-Type: text/json');
        header('Content-Encoding: UTF-8');

        exit(json_encode($output));
    }

}

?>
