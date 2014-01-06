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
class WFElementFolderlist extends WFElement {

    /**
     * Element name
     *
     * @var    string
     */
    protected $_name = 'Folderlist';

    /**
     * Fetch a folderlist element
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

        // Initialise variables.
        $path = JPATH_ROOT . '/' . (string) $node->attributes()->directory;
        $filter = (string) $node->attributes()->filter;
        $exclude = (string) $node->attributes()->exclude;
        $folders = JFolder::folders($path, $filter);

        $options = array();
        foreach ($folders as $folder) {
            if ($exclude) {
                if (preg_match(chr(1) . $exclude . chr(1), $folder)) {
                    continue;
                }
            }
            $options[] = JHtml::_('select.option', $folder, $folder);
        }

        if (!(string) $node->attributes()->hide_none) {
            array_unshift($options, JHtml::_('select.option', '-1', JText::_('JOPTION_DO_NOT_USE')));
        }

        if (!(string) $node->attributes()->hide_default) {
            array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_USE_DEFAULT')));
        }

        return JHtml::_('select.genericlist', $options, $control_name . '[' . $name . ']', array('id' => 'param' . $name, 'list.attr' => 'class="inputbox"', 'list.select' => (string) $value));
    }

}
