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

// load base model
require_once (dirname(__FILE__) . '/model.php');

class WFModelHelp extends WFModel {

    function getLanguage() {
        $language = JFactory::getLanguage();
        $tag = $language->getTag();

        return substr($tag, 0, strpos($tag, '-'));
    }

    function getTopics($file) {

        $result = '';

        if (file_exists($file)) {
            // load xml
            $xml = WFXMLElement::load($file);

            if ($xml) {
                foreach ($xml->help->children() as $topic) {
                    $subtopics = $topic->subtopic;
                    $class = count($subtopics) ? ' class="subtopics"' : '';

                    $key    = (string) $topic->attributes()->key;
                    $title  = (string) $topic->attributes()->title;
                    $file   = (string) $topic->attributes()->file;

                    // if file attribute load file
                    if ($file) {
                        $result .= $this->getTopics(WF_EDITOR . '/' . $file);
                    } else {
                        $result .= '<dd' . $class . ' id="' . $key . '">' . trim(WFText::_($title)) . '</dd>';
                    }

                    if (count($subtopics)) {
                        $result .= '<dl class="hidden">';
                        foreach ($subtopics as $subtopic) {
                            $sub_subtopics = $subtopic->subtopic;

                            // if a file is set load it as sub-subtopics
                            if ($file = (string) $subtopic->attributes()->file) {
                                $result .= '<dd class="subtopics">' . trim(WFText::_((string) $subtopic->attributes()->title)) . '</dd>';
                                $result .= '<dl class="hidden">';
                                $result .= $this->getTopics(WF_EDITOR . '/' . $file);
                                $result .= '</dl>';
                            } else {
                                $id = $subtopic->attributes()->key ? ' id="' . (string) $subtopic->attributes()->key . '"' : '';

                                $class = count($sub_subtopics) ? ' class="subtopics"' : '';
                                $result .= '<dd' . $class . $id . '>' . trim(WFText::_((string) $subtopic->attributes()->title)) . '</dd>';

                                if (count($sub_subtopics)) {
                                    $result .= '<dl class="hidden">';
                                    foreach ($sub_subtopics as $sub_subtopic) {
                                        $result .= '<dd id="' . (string) $sub_subtopic->attributes()->key . '">' . trim(WFText::_((string) $sub_subtopic->attributes()->title)) . '</dd>';
                                    }
                                    $result .= '</dl>';
                                }
                            }
                        }
                        $result .= '</dl>';
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns a formatted list of help topics
     *
     * @access  public
     * @return  String
     * @since 1.5
     */
    function renderTopics() {
        $section = JRequest::getWord('section', 'admin');
        $category = JRequest::getWord('category', 'cpanel');

        $document = JFactory::getDocument();
        $language = JFactory::getLanguage();

        $document->setTitle(WFText::_('WF_HELP') . ' : ' . WFText::_('WF_' . strtoupper($category) . '_TITLE'));

        $file = WF_EDITOR_PLUGINS . '/' . $category . '/' . $category . ".xml";

        switch ($section) {
            case 'admin' :
                $file = JPATH_ADMINISTRATOR . '/components/com_jce/models/' . $category . '.xml';
                break;
            case 'editor' :
                $file = WF_EDITOR_PLUGINS . '/' . $category . '/' . $category . ".xml";
                if (!is_file($file)) {
                    $file = WF_EDITOR_LIBRARIES . '/xml/help/editor.xml';
                } else {
                    $language->load('com_jce_' . $category, JPATH_SITE);
                }
                break;
        }

        $result = '';

        $result .= '<dl><dt><span>' . WFText::_('WF_' . strtoupper($category) . '_TITLE') . '</span></dt>';
        $result .= $this->getTopics($file);
        $result .= '</dl>';

        return $result;
    }

}

?>