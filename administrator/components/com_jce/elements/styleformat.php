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
class WFElementStyleFormat extends WFElement {

    protected $wrapper = array();
    protected $merge = array();
    
    protected $sections     = array('section','nav','article','aside','h1', 'h2', 'h3', 'h4', 'h5', 'h6','header','footer','address','main');
    protected $grouping     = array('p','pre','blockquote','figure','figcaption','div');
    protected $textlevel    = array('em','strong','small','s','cite','q','dfn','abbr','data','time','code','var','samp','kbd','sub','i','b','u','mark','ruby','rt','rp','bdi','bdo','span','wbr');
    
    /**
     * Element type
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'StyleFormat';

    public function fetchElement($name, $value, &$node, $control_name) {
        $output = array();
        
        // default item list (remove "attributes" for now)
        $default = array('title' => '', 'element' => '', 'selector' => '', 'classes' => '', 'styles' => '');
        
        // pass to items
        $items = json_decode($value, true);
        
        if (empty($items)) {
            $items = array($default);
            $value = array();
        }

        // store element options
        $this->elements = $this->getElementOptions();

        $html = '<div class="styleformat-list"';
        
        // pattern data attribute for editable select input box
        if ((string) $node->attributes()->parent) {
            $prefix = preg_replace(array('#^params#', '#([^\w]+)#'), '', $control_name);
            
            $parents = array();
            
            foreach(explode(';', (string) $node->attributes()->parent) as $item) {
                $parents[] = $prefix . $item;
            }
            
            $html .= ' data-parent="' . implode(';', $parents) . '"';
        }
        
        $html .= '>';
        
        $output[] = $html;

        foreach ($items as $item) {            
            $elements = array('<div class="styleformat">');

            foreach ($default as $k => $v) {
                
                if (array_key_exists($k, $item)) {
                    $v = $item[$k];
                }
                
                $elements[] = '<div class="styleformat-item-' . $k . '">' . $this->getField($k, $v) . '</div>';
            }
            // handle
            $elements[] = '<a href="#" class="close handle">&nbsp;</a>';
            // delete button
            $elements[] = '<a href="#" class="close">&times;</a>';
            // collapse
            $elements[] = '<a href="#" class="close collapse icon-chevron-up"></a>';
            
            $elements[] = '</div>';

            $output[] = implode('', $elements);
        }
        
        $output[] = '<a href="#" class="close plus"><span>' . WFText::_('WF_STYLEFORMAT_NEW') . '</span><span>&plus;</span></a>';
        
        // hidden field
        $output[] = '<input type="hidden" name="' . $control_name . '[' . $name . ']" value="" />';
        $output[] = '</div>';
        return implode("\n", $output);
    }

    protected function getElementOptions() {
        // create elements list
        $options = array(
            JHTML::_('select.option', '', WFText::_('WF_OPTION_NOT_SET'))
        );
        
        $options[] = JHTML::_('select.option',  '<OPTGROUP>', WFText::_('WF_OPTION_SECTION_ELEMENTS'));

        foreach ($this->sections as $item) {
            $options[] = JHTML::_('select.option', $item, $item);
        }
        
        $options[] = JHTML::_('select.option',  '</OPTGROUP>');
        
        $options[] = JHTML::_('select.option',  '<OPTGROUP>', WFText::_('WF_OPTION_GROUPING_ELEMENTS'));

        foreach ($this->grouping as $item) {
            $options[] = JHTML::_('select.option', $item, $item);
        }
        
        $options[] = JHTML::_('select.option',  '</OPTGROUP>');
        
        $options[] = JHTML::_('select.option',  '<OPTGROUP>', WFText::_('WF_OPTION_TEXT_LEVEL_ELEMENTS'));

        foreach ($this->textlevel as $item) {
            $options[] = JHTML::_('select.option', $item, $item);
        }
        
        $options[] = JHTML::_('select.option',  '</OPTGROUP>');
        
        return $options;
    }

    protected function getField($key, $value) {
        $item = array();
        
        if ($key !== "title") {
            $item[] = '<label for="' . $key . '">' . WFText::_('WF_STYLEFORMAT_' . strtoupper($key)) . '</label>';
        }

        switch ($key) {
            case 'inline':
            case 'block':
            case 'element':
                
                $class = "";
                
                // make element editable
                /*if ($key === "element") {
                    $class = ' class="editable"';
                }*/

                $item[] = JHTML::_('select.genericlist', $this->elements, null, 'data-key="' . $key . '"' . $class, 'value', 'text', $value);

                break;
            case 'title':
                $item[] = '<input type="text" placeholder="' . WFText::_('WF_STYLEFORMAT_' . strtoupper($key)) . '" data-key="' . $key . '" value="' . $value . '" />';
                break;
            case 'styles':
            case 'attributes':
            case 'selector':
            case 'classes':

                $item[] = '<input type="text" data-key="' . $key . '" value="' . $value . '" />';

                break;
        }
        if ($key !== "title") {
            $item[] = '<span class="help-block">' . WFText::_('WF_STYLEFORMAT_' . strtoupper($key) . '_DESC') . '</span>';
        }
        return implode('', $item);
    }

}

?>