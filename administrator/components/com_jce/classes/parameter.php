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

// Register the element class with the loader.
JLoader::register('WFElement', dirname(__FILE__) . '/element.php');

class WFParameter {

    /**
     * @var    object  The params data object
     */
    protected $data = null;

    /**
     * @var    array  The params keys array
     */
    protected $key = null;

    /**
     * @var    object  The XML params element
     * @since  2.2.5
     */
    protected $xml = null;

    /**
     * @var    array  Loaded elements
     * @since  2.2.5
     */
    protected $elements = array();

    /**
     * @var    string  Parameter control
     * @since  2.2.5
     */
    protected $control = 'params';

    /**
     * @var    array  Directories, where element types can be stored
     * @since  2.2.5
     */
    protected $elementPath = array();

    function __construct($data = null, $path = '', $keys = null, $config = array()) {
        //parent::__construct('_default');

        if (array_key_exists('control', $config)) {
            $this->control = $config['control'];
        }

        // Set base path.
        $this->addElementPath(dirname(dirname(__FILE__)) . '/elements');

        /* if ($data = trim($data)) {
          $this->loadString($data);
          } */

        if ($path) {
            $this->loadSetupFile($path);
        }

        //$this->_raw = $data;

        $this->data = new StdClass();

        if ($data) {
            if (!is_object($data)) {
                $data = json_decode($data);
            }

            if ($keys) {
                if (!is_array($keys)) {
                    $keys = explode('.', $keys);
                }

                $this->key = $keys;

                foreach ($keys as $key) {
                    $data = isset($data->$key) ? $data->$key : $data;
                }
            }

            $this->bindData($this->data, $data);
        }
    }

    /**
     * Loads an XML setup file and parses it.
     *
     * @param   string  $path  A path to the XML setup file.
     *
     * @return  object
     * @since   2.2.5
     */
    public function loadSetupFile($path) {
        $result = false;

        if ($path) {

            $controls = explode(':', $this->control);

            if ($xml = WFXMLElement::load($path)) {
                $params = $xml;

                // move through tree
                foreach ($controls as $control) {
                    $params = $params->$control;
                }

                foreach ($params as $param) {
                    $this->setXML($param);
                    $result = true;
                }
            }
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Sets the XML object from custom XML files.
     *
     * @param   JSimpleXMLElement  &$xml  An XML object.
     *
     * @return  void

     * @since   2.2.5
     */
    public function setXML(&$xml) {
        if (is_object($xml)) {
            if ($group = (string) $xml->attributes()->group) {
                $this->xml[$group] = $xml;
            } else {
                $this->xml['_default'] = $xml;
            }

            if ($dir = (string) $xml->attributes()->addpath) {
                $this->addElementPath(JPATH_ROOT . $dir);
            }
        }
    }

    /**
     * Add a directory where JParameter should search for element types.
     *
     * You may either pass a string or an array of directories.
     *
     * JParameter will be searching for a element type in the same
     * order you added them. If the parameter type cannot be found in
     * the custom folders, it will look in
     * JParameter/types.
     *
     * @param   mixed  $path  Directory (string) or directories (array) to search.
     *
     * @return  void

     * @since   2.2.5
     */
    public function addElementPath($paths) {
        // Just force path to array.
        settype($paths, 'array');

        // Loop through the path directories.
        foreach ($paths as $dir) {
            // No surrounding spaces allowed!
            $dir = trim($dir);

            // Add trailing separators as needed.
            if (substr($dir, -1) != DIRECTORY_SEPARATOR) {
                // Directory
                $dir .= DIRECTORY_SEPARATOR;
            }

            // Add to the top of the search dirs.
            array_unshift($this->elementPath, $dir);
        }
    }

    /**
     * Loads an element type.
     *
     * @param   string   $type  The element type.
     * @param   boolean  $new   False (default) to reuse parameter elements; true to load the parameter element type again.
     *
     * @return  object
     * @since   2.2.5
     */
    public function loadElement($type, $new = false) {
        $signature = md5($type);

        if ((isset($this->elements[$signature]) && !($this->elements[$signature] instanceof __PHP_Incomplete_Class)) && $new === false) {
            return $this->elements[$signature];
        }

        $elementClass = 'WFElement' . $type;

        if (!class_exists($elementClass)) {
            if (isset($this->elementPath)) {
                $dirs = $this->elementPath;
            } else {
                $dirs = array();
            }

            $file = JFilterInput::getInstance()->clean(str_replace('_', '/', $type) . '.php', 'path');

            jimport('joomla.filesystem.path');
            if ($elementFile = JPath::find($dirs, $file)) {
                include_once $elementFile;
            } else {
                $false = false;
                return $false;
            }
        }

        if (!class_exists($elementClass)) {
            $false = false;
            return $false;
        }

        $this->elements[$signature] = new $elementClass($this);

        return $this->elements[$signature];
    }

    /**
     * Bind data to the parameter.
     *
     * @param   mixed   $data   An array or object.
     * @param   string  $group  An optional group that the data should bind to. The default group is used if not supplied.
     *
     * @return  boolean  True if the data was successfully bound, false otherwise.
     */
    public function bind($data) {
        if (is_array($data)) {
            return $this->bindData($this->data, $data);
        } else if (is_object($data)) {
            return $this->bindData($this->data, $data);
        } else {
            return $this->bindData($this->data, json_decode($data));
        }
    }

    /**
     * Method to recursively bind data to a parent object.
     *
     * @param	object	$parent	The parent object on which to attach the data values.
     * @param	mixed	$data	An array or object of data to bind to the parent object.
     *
     * @return	void
     * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
     */
    protected function bindData(&$parent, $data) {
        // Ensure the input data is an array.
        if (is_object($data)) {
            $data = get_object_vars($data);
        } else {
            $data = (array) $data;
        }

        foreach ($data as $k => $v) {
            if (self::is_assoc($v) || is_object($v)) {
                $parent->$k = new stdClass();
                $this->bindData($parent->$k, $v);
            } else {
                $parent->$k = $v;
            }
        }
    }

    /**
     * Return the number of parameters in a group.
     *
     * @param   string  $group  An optional group. The default group is used if not supplied.
     *
     * @return  mixed  False if no params exist or integer number of parameters that exist.

     * @since   2.2.5
     */
    public function getNumParams($group = '_default') {
        if (!isset($this->xml[$group]) || !count($this->xml[$group]->children())) {
            return false;
        } else {
            return count($this->xml[$group]->children());
        }
    }

    /**
     * Get the number of params in each group.
     *
     * @return  array  Array of all group names as key and parameters count as value.

     * @since   2.2.5
     */
    public function getGroups() {
        if (!is_array($this->xml)) {

            return false;
        }

        $results = array();
        
        foreach ($this->xml as $name => $group) {
            $results[] = $name;//$this->getNumParams($name);
        }
        
        return $results;
    }

    public function getAll($name = '') {
        $results = array();

        if ($name) {
            $groups = (array) $name;
        } else {
            $groups = $this->getGroups();
        }

        foreach ($groups as $group) {
            if (!isset($this->xml[$group])) {
                return null;
            }

            $data = new StdClass();

            foreach ($this->xml[$group]->children() as $param) {
                $key = (string) $param->attributes()->name;
                $value = $this->get($key, (string) $param->attributes()->default);

                $data->$key = $value;
            }

            $results[$group] = $data;
        }

        if ($name) {
            return $results[$name];
        }

        return $results;
    }

    private function isEmpty($value) {
        return (is_string($value) && $value == "") || (is_array($value) && empty($value));
    }

    /**
     * Get a parameter value.
     *
     * @param	string	Registry path (e.g. editor.width)
     * @param   string	Optional default value, returned if the internal value is null.
     * @return	mixed	Value of entry or null
     * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
     */
    public function get($path, $default = '', $allowempty = true) {
        // set default value as result	
        $result = $default;

        // Explode the registry path into an array
        $nodes = is_array($path) ? $path : explode('.', $path);

        // Initialize the current node to be the registry root.
        $node = $this->data;
        $found = false;
        // Traverse the registry to find the correct node for the result.
        foreach ($nodes as $n) {
            if (isset($node->$n)) {
                $node = $node->$n;
                $found = true;
            } else {
                $found = false;
                break;
            }
        }

        if ($found) {
            $result = $node;
            if ($allowempty === false) {
                if (self::isEmpty($result)) {
                    $result = $default;
                }
            }
        }
        // convert to float if numeric
        if (is_numeric($result)) {
            $result = (float) $result;
        }

        return $result;
    }

    /**
     * Render all parameters
     *
     * @access	public
     * @param	string	The name of the control, or the default text area if a setup file is not found
     * @return	array	Array of all parameters, each as array Any array of the label, the form element and the tooltip
     * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
     */
    public function getParams($name = 'params', $group = '_default', $exclude = array()) {
        if (!isset($this->xml[$group])) {
            return false;
        }

        $results = array();
        $parent = (string) $this->xml[$group]->attributes()->parent;

        foreach ($this->xml[$group]->children() as $param) {

            if (!empty($exclude) && in_array((string) $param->attributes()->name, $exclude)) {
                continue;
            }

            $results[] = $this->getParam($param, $name, $group, $parent);

            $parameters = (string) $param->attributes()->parameters;
            // get sub-parameters
            if ($parameters) {
                jimport('joomla.filesystem.folder');

                // load manifest files for extensions
                $files = JFolder::files(JPATH_SITE . '/' . $parameters, '\.xml$', false, true);

                // get the base key for the parameter
                $keys = explode('.', (string) $param->attributes()->name);

                foreach ($files as $file) {
                    $key = $keys[0] . '.' . basename($file, '.xml');
                    $results[] = new WFParameter($this->data, $file, $key);
                }
            }
        }

        return $results;
    }

    /**
     * Render a parameter type
     *
     * @param	object	A param tag node
     * @param	string	The control name
     * @return	array	Any array of the label, the form element and the tooltip
     * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
     */
    public function getParam(&$node, $control_name = 'params', $group = '_default', $parent = '') {
        //get the type of the parameter
        $type = (string) $node->attributes()->type;

        $element = $this->loadElement($type);

        // error happened
        if ($element === false) {
            $result = array();
            $result[0] = (string) $node->attributes()->name;
            $result[1] = WFText::_('Element not defined for type') . ' = ' . $type;
            $result[5] = $result[0];
            return $result;
        }

        $key = (string) $node->attributes()->name;

        if ((string) $node->attributes()->group) {
            $key = (string) $node->attributes()->group . '.' . $key;
        }

        // get value
        $value = $this->get($key, (string) $node->attributes()->default);

        // get value if value is object or has parent
        if (is_object($value) || $parent) {
            $group = $parent ? $parent . '.' . $group : $group;
            $value = $this->get($group . '.' . (string) $node->attributes()->name, (string) $node->attributes()->default);
        }

        return $element->render($node, $value, $control_name);
    }

    private function _cleanAttribute($matches) {
        return $matches[1] . '="' . preg_replace('#([^\w]+)#i', '', $matches[2]) . '"';
    }

    public function render($name = 'params', $group = '_default', $exclude = array()) {
        $params = $this->getParams($name, $group, $exclude);
        $html   = '';
        
        if (!empty($params)) {
            $html .= '<ul class="adminformlist">';

            foreach ($params as $item) {
                //if (is_a($item, 'WFParameter')) {
                if ($item instanceof WFParameter) {
                    foreach ($item->getGroups() as $group) {
                        $label = $group;
                        $class = '';
                        $parent = '';

                        $xml = $item->xml[$group];

                        if ((string) $xml->attributes()->parent) {
                            $parent = '[' . (string) $xml->attributes()->parent . '][' . $group . ']';
                            $class = ' class="' . (string) $xml->attributes()->parent . '"';
                            $label = (string) $xml->attributes()->parent . '_' . $group;
                        }

                        $html .= '<div data-type="' . $group . '"' . $class . '>';
                        $html .= '<h4>' . WFText::_('WF_' . strtoupper($label) . '_TITLE') . '</h4>';
                        //$html .= $item->render($name . '[' . $parent . '][' . $group . ']', $group);
                        $html .= $item->render($name . $parent, $group);
                        $html .= '</div>';
                    }
                } else {
                    $label = preg_replace_callback('#(for|id)="([^"]+)"#', array($this, '_cleanAttribute'), $item[0]);
                    $element = preg_replace_callback('#(id)="([^"]+)"#', array($this, '_cleanAttribute'), $item[1]);

                    $html .= '<li>' . $label . $element;
                }
            }

            $html .= '</li></ul>';
        }

        return $html;
    }

    /**
     * Check if a parent attribute is set. If it is, this parameter groups is included by the parent
     */
    public function hasParent() {
        foreach ($this->xml as $name => $group) {
            if ((string) $group->attributes()->parent) {
                return true;
            }
        }

        return false;
    }

    public static function mergeParams($params1, $params2, $toObject = true) {
        $merged = $params1;

        foreach ($params2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::mergeParams($merged[$key], $value);
            } else {
                if ($value !== '') {
                    $merged[$key] = $value;
                }
            }
        }

        if ($toObject) {
            return self::array_to_object($merged);
        }

        return $merged;
    }

    /**
     * Method to determine if an array is an associative array.
     *
     * @param	array		An array to test.
     * @return	boolean		True if the array is an associative array.
     * @link	http://www.php.net/manual/en/function.is-array.php#98305
     */
    private static function is_assoc($array) {
        return (is_array($array) && (count($array) == 0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))))));
    }

    /**
     * Convert an associate array to an object
     * @param array Associative array
     */
    private static function array_to_object($array) {
        $object = new StdClass();

        foreach ($array as $key => $value) {
            $object->$key = is_array($value) ? self::array_to_object($value) : $value;
        }

        return $object;
    }

    /**
     * Get Parameter data
     * @param   boolean $toString Return as JSON string
     * @return  object or string
     */
    public function getData($toString = false) {
        if ($toString) {
            return json_encode($this->data);
        }

        return $this->data;
    }

}
