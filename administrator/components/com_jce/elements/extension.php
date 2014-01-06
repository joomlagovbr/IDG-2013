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
 * Renders a text element
 *
 * @package 	JCE
 */
class WFElementExtension extends WFElement {

    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Extension';

    function fetchElement($name, $value, &$node, $control_name) {
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
        $class = ((string) $node->attributes()->class ? 'class="' . (string) $node->attributes()->class . ' text_area"' : 'class="text_area"' );

        $control = $control_name . '[' . $name . ']';

        return '<input type="text" name="' . $control . '" id="' . $control_name . $name . '" value="' . $value . '" ' . $class . ' data-default="' . (string) $node->attributes()->default . '" />';
    }

}

?>