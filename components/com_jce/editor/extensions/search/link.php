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

wfimport('editor.libraries.classes.extensions');

class WFLinkSearchExtension extends WFSearchExtension {

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    public function __construct() {
        parent::__construct();

        $request = WFRequest::getInstance();
        $request->setRequest(array($this, 'doSearch'));

        $request->setRequest(array($this, 'getAreas'));

        $wf = WFEditorPlugin::getInstance();
        $plugins = $wf->getParam('search.link.plugins');

        // use tested defaults
        if (empty($plugins)) {
            $plugins = array('categories', 'contacts', 'content', 'newsfeeds', 'weblinks');
        }

        foreach ($plugins as $plugin) {
            if (JPluginHelper::isEnabled('search', $plugin)) {
                JPluginHelper::importPlugin('search', $plugin);
            }
        }
    }

    public function getInstance() {
        static $instance;

        if (!isset($instance)) {
            $instance = new WFSearchExtension();
        }

        return $instance;
    }

    public function display() {
        parent::display();

        $document = WFDocument::getInstance();
        $document->addScript(array('link'), 'extensions.search.js');
        $document->addStylesheet(array('link'), 'extensions.search.css');
    }

    public function isEnabled() {
        $wf = WFEditorPlugin::getInstance();
        return (bool) $wf->getParam('search.link.enable', 1);
    }

    /**
     * Method to get the search areas
     */
    public function getAreas() {
        $app = JFactory::getApplication('site');

        $areas = array();
        $results = array();

        $event = WF_JOOMLA15 ? 'onSearchAreas' : 'onContentSearchAreas';

        $searchareas = $app->triggerEvent($event);

        foreach ($searchareas as $area) {
            if (is_array($area)) {
                $areas = array_merge($areas, $area);
            }
        }

        foreach ($areas as $k => $v) {
            $results[$k] = JText::_($v);
        }

        return $results;
    }

    /*
     * Render Search fields
     * This method uses portions of SearchViewSearch::display from components/com_search/views/search/view.html.php
     * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     */

    public function render() {
        // built select lists
        $orders = array();
        $orders[] = JHtml::_('select.option', 'newest', JText::_('WF_SEARCH_NEWEST_FIRST'));
        $orders[] = JHtml::_('select.option', 'oldest', JText::_('WF_SEARCH_OLDEST_FIRST'));
        $orders[] = JHtml::_('select.option', 'popular', JText::_('WF_SEARCH_MOST_POPULAR'));
        $orders[] = JHtml::_('select.option', 'alpha', JText::_('WF_SEARCH_ALPHABETICAL'));
        $orders[] = JHtml::_('select.option', 'category', JText::_('WF_CATEGORY'));

        $lists = array();
        $lists['ordering'] = JHtml::_('select.genericlist', $orders, 'ordering', 'class="inputbox"', 'value', 'text');

        $searchphrases = array();
        $searchphrases[] = JHtml::_('select.option', 'all', JText::_('WF_SEARCH_ALL_WORDS'));
        $searchphrases[] = JHtml::_('select.option', 'any', JText::_('WF_SEARCH_ANY_WORDS'));
        $searchphrases[] = JHtml::_('select.option', 'exact', JText::_('WF_SEARCH_EXACT_PHRASE'));
        $lists['searchphrase'] = JHtml::_('select.radiolist', $searchphrases, 'searchphrase', '', 'value', 'text', 'all');


        $view = $this->getView('search');

        $view->assign('searchareas', self::getAreas());
        $view->assign('lists', $lists);
        $view->display();
    }

    /**
     * Process search
     * @param type $query Search query
     * @return array Rerach Results 
     * 
     * This method uses portions of SearchController::search from components/com_search/controller.php
     * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
     */
    public function doSearch($query) {
        $wf     = WFEditorPlugin::getInstance();
        $filter = JFilterInput::getInstance();
        
        if (!class_exists('JSite')) {
            // Load JSite class
            JLoader::register('JSite', JPATH_SITE . '/includes/application.php');
        }

        $app    = JApplication::getInstance('site');
        $router = $app->getRouter('site');
        
        // get SearchHelper
        require_once(JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php');

        // get router mode
        $sef = (int) $wf->getParam('search.link.sef_url', 0);
        
        // set router off so a raw url is returned by the Search plugin
        if ($router) {
            $router->setMode(0);
        }

        // slashes cause errors, <> get stripped anyway later on. # causes problems.
        $searchword = trim(str_replace(array('#', '>', '<', '\\'), '', $filter->clean($query)));

        $ordering = JRequest::getWord('ordering', null, 'post');
        $searchphrase = JRequest::getWord('searchphrase', 'all', 'post');
        $areas = JRequest::getVar('areas', null, 'post', 'array');

        // if searchword enclosed in double quotes, strip quotes and do exact match
        if (substr($searchword, 0, 1) == '"' && substr($searchword, -1) == '"') {
            $searchword = substr($searchword, 1, -1);
            $searchphrase = 'exact';
        }

        // clean areas
        if (!empty($areas)) {
            foreach ($areas as $area) {
                $areas[] = $filter->clean($area, 'cmd');
            }
        }

        $event = WF_JOOMLA15 ? 'onSearch' : 'onContentSearch';

        // trigger search on loaded plugins
        $searches = $app->triggerEvent($event, array(
            $searchword,
            $searchphrase,
            $ordering,
            $areas
        ));

        $results = array();
        $rows = array();

        foreach ($searches as $search) {
            $rows = array_merge((array) $rows, (array) $search);
        }

        for ($i = 0, $count = count($rows); $i < $count; $i++) {
            $row = &$rows[$i];

            $result = new StdClass();

            if ($searchphrase == 'exact') {
                $searchwords = array($searchword);
                $needle = $searchword;
            } else {
                $searchworda = preg_replace('#\xE3\x80\x80#s', ' ', $searchword);
                $searchwords = preg_split("/\s+/u", $searchworda);
                $needle = $searchwords[0];
            }

            // get anchors
            $anchors = self::getAnchors($row->text);

            if (!empty($anchors)) {
                $row->anchors = $anchors;
            }

            if (method_exists('SearchHelper', 'getActions')) {
                $row->text = SearchHelper::prepareSearchContent($row->text, $needle);
            } else {
                $row->text = SearchHelper::prepareSearchContent($row->text, 200, $needle);
            }

            $searchwords = array_unique($searchwords);
            $searchRegex = '#(';
            $x = 0;

            foreach ($searchwords as $k => $hlword) {
                $searchRegex .= ($x == 0 ? '' : '|');
                $searchRegex .= preg_quote($hlword, '#');
                $x++;
            }
            $searchRegex .= ')#iu';

            $row->text = preg_replace($searchRegex, '<span class="highlight">\0</span>', $row->text);

            // remove base url
            if (strpos($row->href, JURI::base(true)) !== false) {
                $row->href = substr_replace($row->href, '', 0, strlen(JURI::base(true)) + 1);
            }
            
            // convert to SEF
            if ($router && $sef) {
                $router->setMode(1);
                
                $url        = str_replace('&amp;', '&', $row->href);
                
                $uri        = $router->build($url);
                $url        = $uri->toString();
                
                $row->href  = str_replace('/administrator/', '/', $url);
            }

            $result->title  = $row->title;
            $result->text   = $row->text;
            $result->link   = $row->href;

            $results[] = $result;
        }

        return $results;
    }

    private static function getAnchors($content) {
        preg_match_all('#<a([^>]+)(name|id)="([a-z]+[\w\-\:\.]*)"([^>]*)>#i', $content, $matches, PREG_SET_ORDER);

        $anchors = array();

        if (!empty($matches)) {
            foreach ($matches as $match) {
                if (strpos($match[0], 'href') === false) {
                    $anchors[] = $match[3];
                }
            }
        }

        return $anchors;
    }

}