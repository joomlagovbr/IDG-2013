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
 * Renders a textarea element
 *
 * @package 	JCE
 */
class WFElementTextarea extends WFElement {

    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Textarea';

    function fetchElement($name, $value, &$node, $control_name) {
        $attribs = ' ';

        $attributes = array(
            'placeholder' => ''
        );

        foreach ($attributes as $k => $v) {
            $av = (string) $node->attributes()->$k;
            if ($av || $v) {
                $v = !$av ? $v : $av;
                $attribs .= ' ' . $k . '="' . $v . '"';
            }
        }
        
        // pattern data attribute for editable select input box
        if ((string) $node->attributes()->parent) {
            $attribs .= 'data-parent="' . preg_replace(array('#^params#', '#([^\w]+)#'), '', $control_name) . (string) $node->attributes()->parent . '"';
        }

        $rows = (string) $node->attributes()->rows;
        $cols = (string) $node->attributes()->cols;
        
        $class = ((string) $node->attributes()->class ? 'class="' . (string) $node->attributes()->class . '"' : 'class="text_area"' );
        // convert <br /> tags so they are not visible when editing
        $value = str_replace('<br />', "\n", $value);

        return '<textarea name="' . $control_name . '[' . $name . ']" cols="' . $cols . '" rows="' . $rows . '" ' . $class . ' id="' . $control_name . $name . '"' . $attribs . '>' . $value . '</textarea>';
    }

}

?>