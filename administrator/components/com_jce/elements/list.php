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
defined('JPATH_BASE') or die('RESTRICTED');

/**
 * Renders a select element
 */
class WFElementList extends WFElement {

    /**
     * Element type
     *
     * @access	protected
     * @var	string
     */
    var $_name = 'List';

    function fetchElement($name, $value, &$node, $control_name) {
        $ctrl = $control_name . '[' . $name . ']';
        $attribs = array();
        $new = '';
        $class = '';

        $options = array();
        $values = array();

        if ($class = (string) $node->attributes()->class) {
            $attribs[] = 'class="' . $class . '"';
        } else {
            $attribs[] = 'class="inputbox"';
        }

        foreach ($node->children() as $option) {
            $val        = (string) $option->attributes()->value;
            $text       = (string) $option;
            $disabled   = (string) $option->attributes()->disabled ? true : false;

            $text = is_numeric($text) ? $text : WFText::_($text);

            if (is_array($value)) {
                $key = array_search($val, $value);
                if ($key !== false) {
                    $options[$key] = JHTML::_('select.option', $val,  $text, 'value', 'text', $disabled);
                }
            } else {
                $options[] = JHTML::_('select.option', $val, $text, 'value', 'text', $disabled);
            }

            // create temp values
            $values[] = $val;
        }

        // re-sort options by key
        ksort($options);

        // method to append additional values to options array
        if (is_array($value)) {
            $diff = array_diff($values, $value);
            foreach ($node->children() as $option) {
                $val    = (string) $option->attributes()->value;                
                $text   = (string) $option;

                $text = strpos($text, 'WF_') === false ? $text : WFText::_($text);

                if (in_array($val, $diff)) {
                    $options[] = JHTML::_('select.option', $val, $text);
                }
            }
        }

        // revert to default values
        if ($value === '') {
            $value = (string) $node->attributes()->defaults;
        }

        // editable lists
        if (strpos($class, 'editable') !== false) {
            // pattern data attribute for editable select input box
            if ((string) $node->attributes()->pattern) {
                $attribs[] = 'data-pattern="' . (string) $node->attributes()->pattern . '"';
            }

            $value = strpos($value, 'WF_') === false ? $value : WFText::_($value);

            // editable lists - add value to list
            if (!in_array($value, $values) && !(string) $node->attributes()->multiple) {
                $options[] = JHTML::_('select.option', $value, $value);
            }
        }
        
        // pattern data attribute for editable select input box
        if ((string) $node->attributes()->parent) {
            $prefix = preg_replace(array('#^params#', '#([^\w]+)#'), '', $control_name);
            
            $items = array();
            
            foreach(explode(';', (string) $node->attributes()->parent) as $item) {
                $items[] = $prefix . $item;
            }
            
            $attribs[] =  'data-parent="' . implode(';', $items) . '"';
        }

        // multiple lists
        if ((string) $node->attributes()->multiple) {
            $attribs[] = 'multiple="multiple"';
            $ctrl .= '[]';

            $value = !is_array($value) ? preg_split('#[|,]#', $value) : $value;
        }

        return JHTML::_('select.genericlist', $options, $ctrl, implode(' ', $attribs), 'value', 'text', $value, $control_name . $name);
    }

}

?>
