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
jimport('joomla.application.component.view');

if (!class_exists('WFViewBase')) {
    if (interface_exists('JView')) {
        abstract class WFViewBase extends JViewLegacy {}
    } else {
        abstract class WFViewBase extends JView {}
    }
}

class WFViewPopup extends WFViewBase {

    public function display($tpl = null) {
        $app = JFactory::getApplication();

        JHTML::_('behavior.mootools');

        $this->document->addScript(JURI::root(true) . '/components/com_jce/media/js/popup.js');
        $this->document->addStylesheet(JURI::root(true) . '/components/com_jce/media/css/popup.css');

        // Get variables
        $img = JRequest::getVar('img');
        $title = JRequest::getWord('title');
        $mode = JRequest::getInt('mode', '0');
        $click = JRequest::getInt('click', '0');
        $print = JRequest::getInt('print', '0');

        $dim = array('', '');

        if (strpos('http', $img) === false) {
            $path = JPATH_SITE . '/' . trim(str_replace(JURI::root(), '', $img), '/');
            if (is_file($path)) {
                $dim = @getimagesize($path);
            }
        }

        $width = JRequest::getInt('w', JRequest::getInt('width', ''));
        $height = JRequest::getInt('h', JRequest::getInt('height', ''));

        if (!$width) {
            $width = $dim[0];
        }

        if (!$height) {
            $height = $dim[1];
        }

        // Cleanup img variable
        $img = preg_replace('/[^a-z0-9\.\/_-]/i', '', $img);

        $title = isset($title) ? str_replace('_', ' ', $title) : basename($img);
        // img src must be passed
        if ($img) {
            $features = array(
                'img' => str_replace(JURI::root(), '', $img),
                'title' => $title,
                'alt' => $title,
                'mode' => $mode,
                'click' => $click,
                'print' => $print,
                'width' => $width,
                'height' => $height
            );

            $this->assign('features', $features);
        } else {
            $app->redirect('index.php');
        }

        parent::display($tpl);
    }

}

?>
