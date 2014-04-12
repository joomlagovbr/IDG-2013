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
class WFElementFonts extends WFElement {

    protected static $fonts = array('Andale Mono' => 'andale mono,times', 'Arial' => 'arial,helvetica,sans-serif', 'Arial Black' => 'arial black,avant garde', 'Book Antiqua' => 'book antiqua,palatino', 'Comic Sans MS' => 'comic sans ms,sans-serif', 'Courier New' => 'courier new,courier', 'Georgia' => 'georgia,palatino', 'Helvetica' => 'helvetica', 'Impact' => 'impact,chicago', 'Symbol' => 'symbol', 'Tahoma' => 'tahoma,arial,helvetica,sans-serif', 'Terminal' => 'terminal,monaco', 'Times New Roman' => 'times new roman,times', 'Trebuchet MS' => 'trebuchet ms,geneva', 'Verdana' => 'verdana,geneva', 'Webdings' => 'webdings', 'Wingdings' => 'wingdings,zapf dingbats');

    /**
     * Element type
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Fonts';

    public function fetchElement($name, $value, &$node, $control_name) {
        $default = self::$fonts;
        
        if (empty($value)) {
            $data = self::$fonts;
        } else {
            $data = json_decode($value, true);
        }

        $output     = array();

        $output[] = '<div class="fontlist">';
        $output[] = '<ul>';

        foreach($data as $title => $fonts) {
            if (in_array($title, array_keys(self::$fonts))) {
                $output[] = '<li><input type="checkbox" value="' . $title . '=' . $fonts . '" checked="checked" /><span style="font-family:'. $fonts .'">' . $title . '</span></li>';
            
                unset($default[$title]);
                
            } else {
                $output[] = '<li class="font-item"><input type="text" value="' . $title . '" placeholder="' . WFText::_('WF_LABEL_NAME') . '"><input type="text" value="' . $fonts . '" placeholder="' . WFText::_('WF_LABEL_FONTS') . ', eg: arial,helvetica,sans-serif" /><a href="#" class="close">&times;</a></li>';
            }
        }
        
        foreach($default as $title => $fonts) {
            $output[] = '<li><input type="checkbox" value="' . $title . '=' . $fonts . '" /><span style="font-family:'. $fonts .'">' . $title . '</span></li>';
        }
        
        $output[] = '<li class="font-item hide"><input type="text" value="" placeholder="' . WFText::_('WF_LABEL_NAME') . '"><input type="text" value="" placeholder="' . WFText::_('WF_LABEL_FONTS') . ', eg: arial,helvetica,sans-serif" /><a href="#" class="close">&times;</a></li>';

        $output[] = '</ul>';
        $output[] = '<a href="#" class="close plus"><span>' . WFText::_('WF_PARAM_FONTS_NEW') . '</span><span>&plus;</span></a>';
        $output[] = '<input type="hidden" name="' . $control_name . '[' . $name . ']" value="" />';
        $output[] = '</div>';

        return implode("\n", $output);
    }

}

?>