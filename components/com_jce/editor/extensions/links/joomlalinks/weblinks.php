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
defined('_WF_EXT') or die('RESTRICTED');

class JoomlalinksWeblinks extends JObject {

    var $_option = 'com_weblinks';

    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    function __construct($options = array()) {
        
    }

    /**
     * Returns a reference to a editor object
     *
     * This method must be invoked as:
     * 		<pre>  $browser =JContentEditor::getInstance();</pre>
     *
     * @access	public
     * @return	JCE  The editor object.
     * @since	1.5
     */
    function getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new JoomlalinksWeblinks();
        }
        return $instance;
    }

    function getOption() {
        return $this->_option;
    }

    function getList() {
        $wf = WFEditorPlugin::getInstance();

        if ($wf->checkAccess('links.joomlalinks.weblinks', 1)) {
            return '<li id="index.php?option=com_weblinks&view=categories"><div class="tree-row"><div class="tree-image"></div><span class="folder weblink nolink"><a href="javascript:;">' . WFText::_('WF_LINKS_JOOMLALINKS_WEBLINKS') . '</a></span></div></li>';
        }
    }

    public function getLinks($args) {
        $wf = WFEditorPlugin::getInstance();

        $items = array();

        if (!defined('JPATH_PLATFORM')) {
            require_once(JPATH_SITE . '/includes/application.php');
        }
        require_once(JPATH_SITE . '/components/com_weblinks/helpers/route.php');

        switch ($args->view) {
            // Get all WebLink categories
            default:
            case 'categories':
                $categories = WFLinkBrowser::getCategory('com_weblinks');

                foreach ($categories as $category) {

                    $url = '';

                    if (method_exists('WeblinksHelperRoute', 'getCategoryRoute')) {
                        $id = WeblinksHelperRoute::getCategoryRoute($category->id);

                        if (strpos($id, 'index.php?Itemid=') !== false) {
                            $url = $id;
                            $id = 'index.php?option=com_weblinks&view=category&id=' . $category->id;
                        }
                    } else {
                        $itemid = WFLinkBrowser::getItemId('com_weblinks', array('categories' => null, 'category' => $category->id));
                        $id = 'index.php?option=com_weblinks&view=category&id=' . $category->id . $itemid;
                    }

                    $items[] = array(
                        'url' => self::route($url),
                        'id' => $id,
                        'name' => $category->title . ' / ' . $category->alias,
                        'class' => 'folder weblink'
                    );
                }
                break;
            // Get all links in the category
            case 'category':
                if (defined('JPATH_PLATFORM')) {
                    $categories = WFLinkBrowser::getCategory('com_weblinks', $args->id);

                    if (count($categories)) {
                        foreach ($categories as $category) {
                            $children = WFLinkBrowser::getCategory('com_weblinks', $category->id);

                            $url = '';

                            if ($children) {
                                $id = 'index.php?option=com_weblinks&view=category&id=' . $category->id;
                            } else {
                                if (method_exists('WeblinksHelperRoute', 'getCategoryRoute')) {
                                    $id = WeblinksHelperRoute::getCategoryRoute($category->id);

                                    if (strpos($id, 'index.php?Itemid=') !== false) {
                                        $url = $id;
                                        $id = 'index.php?option=com_weblinks&view=category&id=' . $category->id;
                                    }
                                } else {
                                    $itemid = WFLinkBrowser::getItemId('com_weblinks', array('categories' => null, 'category' => $category->id));
                                    $id = 'index.php?option=com_weblinks&view=category&id=' . $category->id . $itemid;
                                }
                            }

                            $items[] = array(
                                'url' => self::route($url),
                                'id' => $id,
                                'name' => $category->title . ' / ' . $category->alias,
                                'class' => 'folder weblink'
                            );
                        }
                    }
                }

                $weblinks = self::_weblinks($args->id);

                foreach ($weblinks as $weblink) {
                    $id = WeblinksHelperRoute::getWeblinkRoute($weblink->slug, $weblink->catslug);

                    if (defined('JPATH_PLATFORM')) {
                        $id .= '&task=weblink.go';
                    }

                    $items[] = array(
                        'id' => self::route($id),
                        'name' => $weblink->title . ' / ' . $weblink->alias,
                        'class' => 'file'
                    );
                }
                break;
        }
        return $items;
    }

    function _weblinks($id) {
        $wf = WFEditorPlugin::getInstance();
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $dbquery = $db->getQuery(true);

        $section = JText::_('Web Links');

        $query = 'SELECT a.id AS slug, b.id AS catslug, a.title AS title, a.description AS text, a.url, a.alias';

        if ($wf->getParam('links.joomlalinks.weblinks_alias', 1) == 1) {
            if (is_object($dbquery) && method_exists($dbquery, 'charLength')) {
                //sqlsrv changes
                $case_when1 = ' CASE WHEN ';
                $case_when1 .= $dbquery->charLength('a.alias', '!=', '0');
                $case_when1 .= ' THEN ';
                $a_id = $dbquery->castAsChar('a.id');
                $case_when1 .= $dbquery->concatenate(array($a_id, 'a.alias'), ':');
                $case_when1 .= ' ELSE ';
                $case_when1 .= $a_id . ' END as slug';

                $case_when2 = ' CASE WHEN ';
                $case_when2 .= $dbquery->charLength('b.alias', '!=', '0');
                $case_when2 .= ' THEN ';
                $c_id = $dbquery->castAsChar('b.id');
                $case_when2 .= $dbquery->concatenate(array($c_id, 'b.alias'), ':');
                $case_when2 .= ' ELSE ';
                $case_when2 .= $c_id . ' END as catslug';
            } else {
                $case_when1 = ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug';
                $case_when2 = ' CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(\':\', b.id, b.alias) ELSE b.id END as catslug';
            }

            $query .= ',' . $case_when1 . ',' . $case_when2;
        }

        if (method_exists('JUser', 'getAuthorisedViewLevels')) {
            $where = ' AND a.state = 1';
            $where .= ' AND b.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';
        } else {
            $where = ' AND a.published = 1';
            $where .= ' AND b.access <= ' . (int) $user->get('aid');
        }

        $query .= ' FROM #__weblinks AS a'
                . ' INNER JOIN #__categories AS b ON b.id = ' . (int) $id
                . ' WHERE a.catid = ' . (int) $id
                . $where
                . ' AND b.published = 1'
                . ' ORDER BY a.title'
        ;

        $db->setQuery($query, 0);
        return $db->loadObjectList();
    }

    private static function route($url) {
        $wf = WFEditorPlugin::getInstance();

        if ($wf->getParam('links.joomlalinks.sef_url', 0)) {
            $url = WFLinkExtension::route($url);
        }

        return $url;
    }

}

?>
