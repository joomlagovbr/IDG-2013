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

class WFExtension extends JObject {

    /**
     * Constructor activating the default information of the class
     *
     * @access public
     */
    public function __construct($config = array()) {
        parent::__construct();

        // set extension properties
        $this->setProperties($config);
    }

    /**
     * Returns a reference to a WFExtension object
     *
     * This method must be invoked as:
     *    <pre>  $extension = WFExtension::getInstance();</pre>
     *
     * @access  public
     * @return  object WFExtension
     */
    /* public static function getInstance()
      {
      static $instance;

      if (!is_object($instance)) {
      $instance = new WFExtension();
      }
      return $instance;
      } */

    /**
     * Display the extension
     * @access $public
     */
    public function display() {
        $document = WFDocument::getInstance();
        // Load Extensions Object
        $document->addScript(array(
            'extensions'
        ));
    }

    /**
     * Load a plugin extension
     *
     * @access  public
     * @return 	array
     */
    private static function _load($types = array(), $extension = null, $config = array()) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $extensions = array();

        if (!isset($config['base_path'])) {
            $config['base_path'] = WF_EDITOR;
        }

        // core extensions path
        $path = $config['base_path'] . '/extensions';

        // cast as array
        $types = (array) $types;

        // get all types from core
        if (empty($types)) {
            $types = JFolder::folders(WF_EDITOR . '/extensions');
        }

        if (JFolder::exists($path)) {
            foreach ($types as $type) {
                if ($extension) {
                    if (JFile::exists($path . '/' . $type . '/' . $extension . '.xml') && JFile::exists($path . '/' . $type . '/' . $extension . '.php')) {
                        $object = new stdClass();
                        $object->folder = $type;
                        $object->path = $path . '/' . $type;
                        $object->extension = $extension;

                        $extensions[] = $object;
                    }
                } else {
                    $files = JFolder::files($path . '/' . $type, '\.xml$', false, true);

                    foreach ($files as $file) {
                        $object = new stdClass();
                        $object->folder = $type;
                        $object->path = $path . '/' . $type;

                        $name = JFile::stripExt(basename($file));

                        if (JFile::exists(dirname($file) . '/' . $name . '.php')) {
                            $object->extension = $name;
                        }
                        $extensions[] = $object;
                    }
                }
            }
        }

        // set default prefix
        /* if (!array_key_exists('prefix', $config)) {
          $config['prefix'] = 'jce-';
          }

          // get external extensions
          jimport('joomla.plugin.helper');

          foreach ($types as $type) {
          $installed = JPluginHelper::getPlugin($config['prefix'] . $type, $extension);

          foreach ($installed as $item) {
          $object = new stdClass();
          $object->folder = $item->type;
          $object->path = JPATH_PLUGINS . '/' . $item->type;

          $name = $item->element;

          if (JFile::exists(JPATH_PLUGINS . '/' . $item->type . '/' . $item->element . '.php')) {
          $object->extension = $name;
          }

          $extensions[] = $object;
          }
          } */

        return $extensions;
    }

    /**
     * Load & Call an extension
     *
     * @access  public
     * @param	array $config
     * @return 	mixed
     */
    public static function loadExtensions($type, $extension = null, $config = array()) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $language = JFactory::getLanguage();

        if (!isset($config['base_path'])) {
            $config['base_path'] = WF_EDITOR;
        }

        // set default prefix
        /* if (!array_key_exists('prefix', $config)) {
          $config['prefix'] = 'jce-';
          } */

        // sanitize $type
        $type = preg_replace('#[^A-Z0-9\._-]#i', '', $type);

        // sanitize $extension
        if ($extension) {
            $extension = preg_replace('#[^A-Z0-9\._-]#i', '', $extension);
        }

        // Create extensions path
        $base = $config['base_path'] . '/extensions';

        // Get all extensions
        $extensions = self::_load((array) $type, $extension, $config);

        $result = array();

        if (!empty($extensions)) {
            foreach ($extensions as $item) {
                $name = isset($item->extension) ? $item->extension : '';
                $folder = $item->folder;
                $path = $item->path;

                if ($name) {
                    $root = $path . '/' . $name . '.php';

                    if (file_exists($root)) {
                        // Load root extension file
                        require_once($root);

                        // Load Extension language file
                        $language->load('com_jce_' . $type . '_' . $name, JPATH_SITE);

                        // remove prefix
                        //$folder = str_replace($config['prefix'], '', $folder);
                        // Return array of extension names

                        $result[$type][] = $name;

                        // if we only want a named extension
                        if ($extension && $extension == $name) {
                            return $name;
                        }
                    }
                }
            }
        }

        // only return extension types requested
        if ($type && array_key_exists($type, $result)) {
            return $result[$type];
        }

        // Return array or extension name
        return $result;
    }

    /**
     * Return a parameter for the current plugin / group
     * @param 	object $param Parameter name
     * @param 	object $default Default value
     * @return 	string Parameter value
     */
    public function getParam($param, $default = '') {
        $wf = WFEditor::getInstance();

        return $wf->getParam($param, $default);
    }

    public function getView($options = array()) {
        return new WFView($options);
    }
}

?>