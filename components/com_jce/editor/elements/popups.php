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
class WFElementPopups extends WFElement {

    /**
     * Element type
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Popups';

    public function fetchElement($name, $value, &$node, $control_name) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $language = JFactory::getLanguage();

        // "Default" list
        if ($name == 'default') {
            // path to directory
            $path = WF_EDITOR_EXTENSIONS . '/popups';

            $filter = '\.xml$';
            $files  = JFolder::files($path, '\.xml', false, true);
            
            $options = array();

            $options[] = JHTML::_('select.option', '', WFText::_('WF_OPTION_NOT_SET'));
            
            foreach($files as $file) {
                $extension = basename($file, '.xml');
                $language->load('com_jce_popups_' . trim($extension), JPATH_SITE);
                
                $options[] = JHTML::_('select.option', $extension, WFText::_('WF_POPUPS_' . strtoupper($extension) . '_TITLE'));
            }
            
            return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox plugins-default-select"', 'value', 'text', $value);
        }
    }

}

?>
