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

// Load class dependencies
require_once(WF_EDITOR_LIBRARIES . '/classes/plugin.php');

class WFPreviewPlugin extends WFEditorPlugin {

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    function __construct() {
        parent::__construct();

        $request = WFRequest::getInstance();
        // Setup plugin XHR callback functions 
        $request->setRequest(array($this, 'showPreview'));

        $this->execute();
    }

    /**
     * Display Preview content
     * @return void
     */
    public function showPreview() {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $dispatcher = JDispatcher::getInstance();
        $language = JFactory::getLanguage();

        // reset document type
        $document = &JFactory::getDocument();
        $document->setType('html');
        // required by module loadposition
        jimport('joomla.application.module.helper');
        // load paramter class
        jimport('joomla.html.parameter');

        wfimport('admin.helpers.extension');

        // Get variables
        $component_id = JRequest::getInt('component_id');
        // get post data
        $data = JRequest::getVar('data', '', 'POST', 'STRING', JREQUEST_ALLOWRAW);

        // cleanup data
        $data = preg_replace(array('#<!DOCTYPE([^>]+)>#i', '#<(head|title|meta)([^>]*)>([\w\W]+)<\/1>#i', '#<\/?(html|body)([^>]*)>#i'), '', rawurldecode($data));

        $component = WFExtensionHelper::getComponent($component_id);
        
        // create params registry object
        $params = new JRegistry();
        
        // create empty params string
        if (!isset($component->params)) {
            $component->params = '';
        }
        
        // process attribs (com_content etc.)
        if ($component->attribs) {
            $params->loadString($component->attribs); 
        } else {
            if (class_exists('JParameter')) {
                $params = new JParameter($component->params);
            } else {
                $params->loadString($component->params);
            }
        }

        $article = JTable::getInstance('content');

        $article->id = 0;
        $article->created_by = $user->get('id');
        $article->parameters = new JRegistry();
        $article->text = $data;

        $limitstart = 0;
        JPluginHelper::importPlugin('content');

        require_once(JPATH_SITE . '/components/com_content/helpers/route.php');

        // set error reporting to error only
        error_reporting(E_ERROR);

        $dispatcher->trigger('onPrepareContent', array(& $article, & $params, $limitstart));

        $this->processURLS($article);

        return $article->text;
    }

    /**
     * Convert URLs
     * @param object $article Article object
     * @return void
     */
    private function processURLS(&$article) {
        $base = JURI::root(true) . '/';
        $buffer = $article->text;

        $protocols = '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
        $regex = '#(src|href|poster)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
        $buffer = preg_replace($regex, "$1=\"$base\$2\"", $buffer);
        $regex = '#(onclick="window.open\(\')(?!/|' . $protocols . '|\#)([^/]+[^\']*?\')#m';
        $buffer = preg_replace($regex, '$1' . $base . '$2', $buffer);

        // ONMOUSEOVER / ONMOUSEOUT
        $regex = '#(onmouseover|onmouseout)="this.src=([\']+)(?!/|' . $protocols . '|\#|\')([^"]+)"#m';
        $buffer = preg_replace($regex, '$1="this.src=$2' . $base . '$3$4"', $buffer);

        // Background image
        $regex = '#style\s*=\s*[\'\"](.*):\s*url\s*\([\'\"]?(?!/|' . $protocols . '|\#)([^\)\'\"]+)[\'\"]?\)#m';
        $buffer = preg_replace($regex, 'style="$1: url(\'' . $base . '$2$3\')', $buffer);

        // OBJECT <param name="xx", value="yy"> -- fix it only inside the <param> tag
        $regex = '#(<param\s+)name\s*=\s*"(movie|src|url)"[^>]\s*value\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
        $buffer = preg_replace($regex, '$1name="$2" value="' . $base . '$3"', $buffer);

        // OBJECT <param value="xx", name="yy"> -- fix it only inside the <param> tag
        $regex = '#(<param\s+[^>]*)value\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"\s*name\s*=\s*"(movie|src|url)"#m';
        $buffer = preg_replace($regex, '<param value="' . $base . '$2" name="$3"', $buffer);

        // OBJECT data="xx" attribute -- fix it only in the object tag
        $regex = '#(<object\s+[^>]*)data\s*=\s*"(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
        $buffer = preg_replace($regex, '$1data="' . $base . '$2"$3', $buffer);

        $article->text = $buffer;
    }

}