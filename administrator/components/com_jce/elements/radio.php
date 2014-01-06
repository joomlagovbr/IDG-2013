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
defined('_JEXEC') or die;

/**
 * Renders a radio element
 */
class WFElementRadio extends WFElement {

    /**
     * Element name
     *
     * @var    string
     */
    protected $_name = 'Radio';

    /**
     * Fetch a html for a radio button
     *
     * @param   string       $name          Element name
     * @param   string       $value         Element value
     * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
     * @param   string       $control_name  Control name
     *
     * @return  string
     */
    public function fetchElement($name, $value, &$node, $control_name) {
        $options = array();
        foreach ($node->children() as $option) {
            $val = (string) $option->attributes()->value;
            $text = (string) $option;
            $options[] = JHtml::_('select.option', $val, $text);
        }
        
        $attribs = array();

        // pattern data attribute for editable select input box
        if ((string) $node->attributes()->parent) {
            $prefix = preg_replace(array('#^params#', '#([^\w]+)#'), '', $control_name);
            
            $items = array();
            
            foreach(explode(';', (string) $node->attributes()->parent) as $item) {
                $items[] = $prefix . $item;
            }
            
            $attribs[] =  'data-parent="' . implode(';', $items) . '"';
        }

        return JHtml::_('select.radiolist', $options, $control_name . '[' . $name . ']', implode(' ', $attribs), 'value', 'text', $value, $control_name . $name, true);
    }

}
