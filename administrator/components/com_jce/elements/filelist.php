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
defined('_JEXEC') or die;

/**
 * Renders a filelist element
 */
class WFElementFilelist extends WFElement {

    /**
     * Element name
     *
     * @var    string
     */
    protected $_name = 'Filelist';

    /**
     * Fetch a filelist element
     *
     * @param   string       $name          Element name
     * @param   string       $value         Element value
     * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
     * @param   string       $control_name  Control name
     *
     * @return  string
     */
    public function fetchElement($name, $value, &$node, $control_name) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        // path to images directory
        $path = JPATH_ROOT . '/' . (string) $node->attributes()->directory;
        $filter = (string) $node->attributes()->filter;
        $exclude = (string) $node->attributes()->exclude;
        $stripExt = (string) $node->attributes()->stripext;
        $files = JFolder::files($path, $filter);

        $options = array();

        if (!(string) $node->attributes()->hide_none) {
            $options[] = JHtml::_('select.option', '-1', JText::_('JOPTION_DO_NOT_USE'));
        }

        if (!(string) $node->attributes()->hide_default) {
            $options[] = JHtml::_('select.option', '', JText::_('JOPTION_USE_DEFAULT'));
        }

        if (is_array($files)) {
            foreach ($files as $file) {
                if ($exclude) {
                    if (preg_match(chr(1) . $exclude . chr(1), $file)) {
                        continue;
                    }
                }
                if ($stripExt) {
                    $file = JFile::stripExt($file);
                }
                $options[] = JHtml::_('select.option', $file, $file);
            }
        }

        return JHtml::_('select.genericlist', $options, $control_name . '[' . $name . ']', array('id' => 'param' . $name, 'list.attr' => 'class="inputbox"', 'list.select' => (string)$value));
    }

}
