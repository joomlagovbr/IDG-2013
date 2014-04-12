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
class WFElementBlockformats extends WFElement {

    protected static $formats = array('p' => 'Paragraph', 'div' => 'Div', 'h1' => 'Heading1', 'h2' => 'Heading2', 'h3' => 'Heading3', 'h4' => 'Heading4', 'h5' => 'Heading5', 'h6' => 'Heading6', 'blockquote' => 'Blockquote', 'address' => 'Address', 'code' => 'Code', 'pre' => 'Preformatted', 'samp' => 'Sample', 'span' => 'Span', 'section' => 'Section', 'article' => 'Article', 'aside' => 'Aside', 'figure' => 'Figure', 'dt' => 'Definition Term', 'dd' => 'Definition List');

    /**
     * Element type
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Blockformats';

    /**
     * array_insert function from http://www.php.net/manual/en/function.array-splice.php#56794
     * @param array $array
     * @param integer $position
     * @param array $insert_array
     */
    protected static function array_insert(&$array, $position, $insert_array) {
        $first_array = array_splice($array, 0, $position);       
        $array = array_merge($first_array, $insert_array, $array);
    }

    public function fetchElement($name, $value, &$node, $control_name) {

        if (empty($value)) {
            $data = array_keys(self::$formats);
            $value = array();
        } else {
            $value  = is_array($value) ? $value : explode(",", $value);
            $data   = array_unique(array_merge($value, array_keys(self::$formats)));
        }

        $output = array();

        $output[] = '<div class="blockformats">';
        $output[] = '<ul>';

        // create default font structure
        foreach ($data as $format) {
            if (empty($value) || in_array($format, $value)) {
                $output[] = '<li><input type="checkbox" value="' . $format . '" checked="checked" /><span class="blockformat-' . $format . '">' . self::$formats[$format] . '</span></li>';
            } else {
                $output[] = '<li><input type="checkbox" value="' . $format . '" /><span class="blockformat-' . $format . '">' . self::$formats[$format] . '</span></li>';
            }
        }

        $output[] = '</ul>';
        $output[] = '<input type="hidden" name="' . $control_name . '[' . $name . ']" value="" />';
        $output[] = '</div>';

        return implode("\n", $output);
    }

}

?>