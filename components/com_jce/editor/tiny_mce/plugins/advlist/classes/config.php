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
class WFAdvlistPluginConfig {

    protected static $defaultNumList    = array('default', 'lower-alpha', 'lower-greek', 'lower-roman', 'upper-alpha', 'upper-roman');
    
    protected static $defaultBulletList = array('default', 'circle', 'disc', 'square');
    
    public static function getConfig(&$settings) {
        $wf = WFEditor::getInstance();

        $number = (array) $wf->getParam('lists.number_styles');
        $bullet = (array) $wf->getParam('lists.bullet_styles');

        if (!empty($number)) {            
            if (count($number) < count(self::$defaultNumList)) {
                $items = array();

                foreach ($number as $item) {
                    $title = $item == 'default' ? 'def' : str_replace('-', '_', $item);
                    $style = $item == 'default' ? '' : $item;

                    $items[] = array('title' => 'advlist.' . $title, 'styles' => array('listStyleType' => $style));
                }

                $settings['advlist_number_styles'] = json_encode($items);
            }
        }

        if (!empty($bullet)) {                        
            if (count($bullet) < count(self::$defaultBulletList)) {
                $items = array();

                foreach ($bullet as $item) {
                    $title = $item == 'default' ? 'def' : str_replace('-', '_', $item);
                    $style = $item == 'default' ? '' : $item;

                    $items[] = array('title' => 'advlist.' . $title, 'styles' => array('listStyleType' => $style));
                }

                $settings['advlist_bullet_styles'] = json_encode($items);
            }
        }
    }

}

?>
