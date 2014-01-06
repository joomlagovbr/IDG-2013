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

class WFXMLElement extends SimpleXMLElement {

    public static function getXML($data) {
        return self::load($data);
    }
    
    /**
     * Reads a XML file.
     *
     * @param string  $data   Full path and file name.
     *
     * @return mixed WFXMLElement on success | false on error.
     */
    public static function load($data) {
        // Disable libxml errors and allow to fetch error information as needed
        libxml_use_internal_errors(true);

        if (is_file($data)) {
            // Try to load the xml file
            $xml = simplexml_load_file($data, 'WFXMLElement');
        } else {
            // Try to load the xml string
            $xml = simplexml_load_string($data, 'WFXMLElement');
        }

        if (empty($xml)) {
            // There was an error
            JError::raiseWarning(100, JText::_('ERROR_XML_LOAD'));

            if (is_file($data)) {
                JError::raiseWarning(100, $data);
            }

            foreach (libxml_get_errors() as $error) {
                JError::raiseWarning(100, 'XML: ' . $error->message);
            }
        }

        return $xml;
    }
}