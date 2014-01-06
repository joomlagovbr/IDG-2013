<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('JPATH_BASE') or die('RESTRICTED');

/**
 * Renders a text element
 *
 * @package 	JCE
 */
class WFElementText extends WFElement {

    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Text';

    function fetchElement($name, $value, &$node, $control_name) {
        $attributes = array();

        foreach ($node->attributes() as $k => $v) {
            if ($k === 'parent') {
                continue;
            }
            
            if ($v != '') {
                $attributes[$k] = (string) $v;
            }
        }
        
        $class = (string) $node->attributes()->class; 

        if (strpos($name, 'max_size') !== false || strpos($class, 'upload_size') !== false) {
            $uploadsize = intval($this->getUploadValue());
            $attributes['max'] = $uploadsize;
        }

        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
        $attributes['class'] = ($class ? $class . ' text_area' : 'text_area' );

        $control = $control_name . '[' . $name . ']';

        $html = '';

        $attributes['value'] = $value;
        $attributes['type'] = 'text';
        $attributes['name'] = $control;
        $attributes['id'] = preg_replace('#[^a-z0-9_-]#i', '', $control_name . $name);
        
        // pattern data attribute for editable select input box
        if ((string) $node->attributes()->parent) {
            $prefix = preg_replace(array('#^params#', '#([^\w]+)#'), '', $control_name);
            
            $items = array();
            
            foreach(explode(';', (string) $node->attributes()->parent) as $item) {
                $items[] = $prefix . $item;
            }
            
            $attributes['data-parent'] = implode(';', $items);
        }

        $html .= '<input';

        foreach ($attributes as $k => $v) {
            if (!in_array($k, array('default', 'label', 'description'))) {
                $html .= ' ' . $k . ' = "' . $v . '"';
            }
        }

        $html .= ' />';

        if (strpos($name, 'max_size') !== false) {
            $html .= $this->uploadSize();
        }

        return $html;
    }

    function uploadSize() {
        return '&nbsp;' . WFText::_('WF_SERVER_UPLOAD_SIZE') . ' : ' . $this->getUploadValue();
    }

    function getUploadValue() {
        $upload = trim(ini_get('upload_max_filesize'));
        $post = trim(ini_get('post_max_size'));

        $upload = $this->convertValue($upload);
        $post = $this->convertValue($post);

        if (intval($upload) <= intval($post)) {
            return $upload;
        }

        return $post;
    }

    function convertValue($value) {
        $unit = 'KB';

        // GB
        if ($value > 1073741824)
            $unit = 'GB';

        // MB
        if ($value > 1048576)
            $unit = 'MB';

        // Convert to bytes
        switch (strtolower($value{strlen($value) - 1})) {
            case 'g':
                $value *= 1073741824;
                break;
            case 'm':
                $value *= 1048576;
                break;
            case 'k':
                $value *= 1024;
                break;
        }
        // Convert to unit value
        switch (strtolower($unit{0})) {
            case 'g':
                $value /= 1073741824;
                break;
            case 'm':
                $value /= 1048576;
                break;
            case 'k':
                $value /= 1024;
                break;
        }
        return preg_replace('/[^0-9]/', '', $value) . ' ' . $unit;
    }

}

?>