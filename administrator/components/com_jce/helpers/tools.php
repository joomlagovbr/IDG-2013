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

abstract class WFToolsHelper {

    public static function getTemplates() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('template')->from('#__template_styles')->where(array("client_id = 0", "home = '1'"));
        } else {
            $query = 'SELECT template'
                    . ' FROM #__templates_menu'
                    . ' WHERE client_id = 0'
            ;
        }

        $db->setQuery($query);
        if (method_exists($db, 'loadColumn')) {
            return $db->loadColumn();
        }

        return $db->loadResultArray();
    }

    public static function parseColors($file) {
        $data = '';
        $colors = array();
        $file = realpath($file);

        if ($file && is_file($file)) {
            $data = JFile::read($file);
        }

        if ($data) {
            if (preg_match_all('/@import url\(([^\)]+)\)/', $data, $matches)) {
                $templates = self::getTemplates();
                
                foreach ($matches[1] as $match) {
                    $file = JPATH_SITE . '/templates/' . $templates[0] . '/css/' . $match;

                    if ($file) {
                        self::parseColors($file);
                    }
                }
            }
            preg_match_all('/#[0-9a-f]{3,6}/i', $data, $matches);

            $colors = $matches[0];
        }

        return $colors;
    }
    
    /*
     * Sort hex colors from dark to light - https://gist.github.com/2158428
     * @param $colors Array Hex colors to sort
     * @return Array
     */
    protected static function sort_hex_colors($colors) {
        $map = array(
            '0' => 0,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            'a' => 10,
            'b' => 11,
            'c' => 12,
            'd' => 13,
            'e' => 14,
            'f' => 15,
        );
        $c = 0;
        $sorted = array();
        foreach ($colors as $color) {
            $color = strtolower(str_replace('#', '', $color));
            if (strlen($color) == 6) {
                $condensed = '';
                $i = 0;
                foreach (preg_split('//', $color, -1, PREG_SPLIT_NO_EMPTY) as $char) {
                    if ($i % 2 == 0) {
                        $condensed .= $char;
                    }
                    $i++;
                }
                $color_str = $condensed;
            }
            $value = 0;
            foreach (preg_split('//', $color_str, -1, PREG_SPLIT_NO_EMPTY) as $char) {
                $value += intval($map[$char]);
            }
            $value = str_pad($value, 5, '0', STR_PAD_LEFT);
            $sorted['_' . $value . $c] = '#' . $color;
            $c++;
        }
        ksort($sorted);
        return $sorted;
    }
    
    public static function getTemplateColors() {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $colors = array();
        $path = '';

        $templates = self::getTemplates();

        foreach ($templates as $template) {
            // Template CSS
            $path = JPATH_SITE . '/templates/' . $template . '/css';
            // get the first path that exists
            if (is_dir($path)) {
                break;
            }
            // reset path
            $path = '';
        }

        if ($path) {
            $files = JFolder::files($path, '\.css$', false, true);

            foreach ($files as $file) {
                $colors = array_merge($colors, WFToolsHelper::parseColors($file));
            }
        }

        // make all colors 6 character hex, eg: #333 to #333333
        for ($i = 0; $i < count($colors); $i++) {
            if ($colors[$i][0] == '#' && strlen($colors[$i]) == 4) {
                $colors[$i] .= substr($colors[$i], -3);
            }
        }

        // sort
        $colors = self::sort_hex_colors($colors);

        return implode(",", array_unique($colors));
    }

    public static function getOptions($params) {
        $options = array(
            'editableselects' => array('label' => WFText::_('WF_TOOLS_EDITABLESELECT_LABEL')),
            'extensions' => array(
                'labels' => array(
                    'type_new' => WFText::_('WF_EXTENSION_MAPPER_TYPE_NEW'),
                    'group_new' => WFText::_('WF_EXTENSION_MAPPER_GROUP_NEW'),
                    'acrobat' => WFText::_('WF_FILEGROUP_ACROBAT'),
                    'office' => WFText::_('WF_FILEGROUP_OFFICE'),
                    'flash' => WFText::_('WF_FILEGROUP_FLASH'),
                    'shockwave' => WFText::_('WF_FILEGROUP_SHOCKWAVE'),
                    'quicktime' => WFText::_('WF_FILEGROUP_QUICKTIME'),
                    'windowsmedia' => WFText::_('WF_FILEGROUP_WINDOWSMEDIA'),
                    'silverlight' => WFText::_('WF_FILEGROUP_SILVERLIGHT'),
                    'openoffice' => WFText::_('WF_FILEGROUP_OPENOFFICE'),
                    'divx' => WFText::_('WF_FILEGROUP_DIVX'),
                    'real' => WFText::_('WF_FILEGROUP_REAL'),
                    'video' => WFText::_('WF_FILEGROUP_VIDEO'),
                    'audio' => WFText::_('WF_FILEGROUP_AUDIO')
                )
            ),
            'colorpicker' => array(
                'template_colors' => self::getTemplateColors(),
                'custom_colors' => $params->get('editor.custom_colors'),
                'labels' => array(
                    'title' => WFText::_('WF_COLORPICKER_TITLE'),
                    'picker' => WFText::_('WF_COLORPICKER_PICKER'),
                    'palette' => WFText::_('WF_COLORPICKER_PALETTE'),
                    'named' => WFText::_('WF_COLORPICKER_NAMED'),
                    'template' => WFText::_('WF_COLORPICKER_TEMPLATE'),
                    'custom' => WFText::_('WF_COLORPICKER_CUSTOM'),
                    'color' => WFText::_('WF_COLORPICKER_COLOR'),
                    'apply' => WFText::_('WF_COLORPICKER_APPLY'),
                    'name' => WFText::_('WF_COLORPICKER_NAME')
                )
            ),
            'browser' => array(
                'title' => WFText::_('WF_BROWSER_TITLE')
            )
        );

        return $options;
    }

}

?>