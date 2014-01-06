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
defined('_JEXEC') or die('RESTRICTED');

abstract class WFToolbarHelper {
    
    public static function createClick($link, $w, $h) {
        return "Joomla.modal(this, '" . $link . "', " . $w . ", " . $h . ");return false;";
    }

    public static function help($type) {
        jimport('joomla.plugin.helper');

        $language = JFactory::getLanguage();
        $tag = $language->getTag();

        $sub = explode('.', $type);
        $category = array_shift($sub);
        $article = implode('.', $sub);

        $link = 'index.php?option=com_jce&amp;view=help&amp;tmpl=component&amp;section=admin&category=' . $category . '&article=' . $article . '&amp;lang=' . substr($tag, 0, strpos($tag, '-'));

        $bar = JToolBar::getInstance('toolbar');
        
        $w = 780; $h = 560;
        
        JHtml::_('behavior.modal');
        
        if (class_exists('JHtmlSidebar')) {            
            $html  = '<button onclick="' . self::createClick($link, $w, $h) . '" class="btn btn-small" title="' . WFText::_('WF_HELP') . '"><i class="icon-help"></i>&nbsp;' . WFText::_('WF_HELP') . '</button>';
        } else {
            $html  = '<a href="' . $link . '" target="_blank" onclick="' . self::createClick($link, $w, $h) . '" class="help" title="' . WFText::_('WF_HELP') . '">';
            $html .= '<span class="icon-32-help" title="' . WFText::_('WF_HELP') . '"></span>' . WFText::_('WF_HELP') . '</a>';
        }

        $bar->appendButton('Custom', $html, 'help');
    }

    /**
     * Writes a configuration button and invokes a cancel operation (eg a checkin)
     * @param	string	The name of the component, eg, com_content
     * @param	int		The height of the popup
     * @param	int		The width of the popup
     * @param	string	The name of the button
     * @param	string	An alternative path for the configuation xml relative to JPATH_SITE
     * @since 1.0
     */
    public static function preferences() {
        if (defined('JPATH_PLATFORM')) {
            JToolbarHelper::preferences('com_jce');
        } else {
            $bar = JToolBar::getInstance('toolbar');

            $w = 780; $h = 560;

            $html  = '<a href="index.php?option=com_jce&amp;view=preferences&amp;tmpl=component" target="_blank" onclick="' . self::createClick($link, $w, $h) . '" class="preferences" title="' . WFText::_('WF_PREFERENCES_TITLE') . '">';
            $html .= '<span class="icon-32-config icon-32-options" title="' . WFText::_('WF_PREFERENCES_TITLE') . '"></span>' . WFText::_('WF_PREFERENCES') . '</a>';

            $bar->appendButton('Custom', $html, 'config');
        }
    }

    /**
     * Writes a configuration button and invokes a cancel operation (eg a checkin)
     * @param	string	The name of the component, eg, com_content
     * @param	int		The height of the popup
     * @param	int		The width of the popup
     * @param	string	The name of the button
     * @param	string	An alternative path for the configuation xml relative to JPATH_SITE
     * @since 1.0
     */
    public static function updates($enabled = false) {
        $bar = JToolBar::getInstance('toolbar');
        // Add a configuration button
        $w = 780; $h = 560;
        
        $link = 'index.php?option=com_jce&amp;view=updates&amp;tmpl=component';

        if ($enabled) {
            JHtml::_('behavior.modal');

            if (class_exists('JHtmlSidebar')) {            
                $html  = '<button onclick="' . self::createClick($link, $w, $h) . '" class="btn btn-small" title="' . WFText::_('WF_UPDATES') . '"><i class="icon-upload"></i>&nbsp;' . WFText::_('WF_UPDATES') . '</button>';
            } else {
                $html  = '<a href="' . $link . '" target="_blank" onclick="' . self::createClick($link, $w, $h) . '" class="updates" title="' . WFText::_('WF_UPDATES') . '">';
                $html .= '<span class="icon-32-default icon-32-update" title="' . WFText::_('WF_HELP') . '"></span>' . WFText::_('WF_UPDATES') . '</a>';
            }
        }    
        $bar->appendButton('Custom', $html, 'updates');
    }

    /*public static function access() {
        $bar = JToolBar::getInstance('toolbar');

        $options = array(
            'width' => 760,
            'height' => 540,
            'modal' => true,
            'buttons' => '{}'
        );

        $html = '<a href="index.php?option=com_config&amp;view=component&amp;component=com_jce&amp;path=&amp;tmpl=component" target="_blank" data-options="' . str_replace('"', "'", json_encode($options)) . '" rel="{handler:iframe,size:{x:760, y:540}}" class="modal preferences" title="' . WFText::_('WF_PREFERENCES_TITLE') . '">';
        $html .= '<span class="icon-32-lock" title="' . WFText::_('WF_ACCESS_TITLE') . '"></span>' . WFText::_('WF_ACCESS') . '</a>';

        $bar->appendButton('Custom', $html, 'access');
    }*/

    public static function export() {
        if (class_exists('JHtmlSidebar')) {
            $icon = 'download';
        } else {
            $icon = defined('JPATH_PLATFORM') ? 'export' : 'unarchive';
        }

        self::custom('export', $icon, $icon . '_f2', 'WF_PROFILES_EXPORT', true);
    }

    public static function save($task = 'save') {
        return JToolBarHelper::save($task);
    }

    public static function apply($task = 'apply') {
        return JToolbarHelper::apply($task);
    }

    public static function cancel($task = 'cancel') {
        return JToolbarHelper::cancel($task);
    }

    public static function editListx($task = 'edit') {
        if (method_exists('JToolbarHelper', 'editListx')) {
            return JToolbarHelper::editListx($task);
        }
        return JToolbarHelper::editList($task);
    }

    public static function addNewx($task = 'add') {
        if (method_exists('JToolbarHelper', 'addNewx')) {
            return JToolbarHelper::addNewx($task);
        }
        return JToolbarHelper::addNew($task);
    }

    public static function custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true, $x = false) {
        return JToolbarHelper::custom($task, $icon, $iconOver, $alt, $listSelect, $x);
    }

    public static function publishList($task = 'publish') {
        return JToolbarHelper::publishList($task);
    }

    public static function unpublishList($task = 'unpublish') {
        return JToolbarHelper::unpublishList($task);
    }

    public static function deleteList($msg = '', $task = 'remove', $alt = '') {
        return JToolbarHelper::deleteList($msg, $task, $alt);
    }

}

?>
