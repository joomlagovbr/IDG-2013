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

class JoomlalinksMenu extends JObject {

    var $_option = 'com_menu';

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
            $instance = new JoomlalinksMenu();
        }
        return $instance;
    }

    function getOption() {
        return $this->_option;
    }

    function getList() {
        $wf = WFEditorPlugin::getInstance();

        if ($wf->checkAccess('links.joomlalinks.menu', 1)) {
            return '<li id="index.php?option=com_menu"><div class="tree-row"><div class="tree-image"></div><span class="folder menu nolink"><a href="javascript:;">' . WFText::_('WF_LINKS_JOOMLALINKS_MENU') . '</a></span></div></li>';
        }
    }

    function getLinks($args) {
        $items = array();
        $view = isset($args->view) ? $args->view : '';
        switch ($view) {
            // create top-level (non-linkable) menu types
            default:
                $types = self::_types();
                foreach ($types as $type) {
                    $items[] = array(
                        'id' => 'index.php?option=com_menu&view=menu&type=' . $type->id,
                        'name' => $type->title,
                        'class' => 'folder menu nolink'
                    );
                }
                break;
            // get menus and sub-menus
            case 'menu':
                $type = isset($args->type) ? $args->type : 0;
                $id = $type ? 0 : $args->id;

                $menus = self::_menu($id, $type);

                foreach ($menus as $menu) {

                    $class = array();

                    $params = defined('JPATH_PLATFORM') ? new JRegistry($menu->params) : new JParameter($menu->params);

                    switch ($menu->type) {
                        case 'separator':
                            if (!$menu->link) {
                                $class[] = 'nolink';
                            }

                            $link = '';
                            break;

                        case 'alias':
                            // If this is an alias use the item id stored in the parameters to make the link.
                            $link = 'index.php?Itemid=' . $params->get('aliasoptions');
                            break;

                        default:
                            // resolve link
                            $link = self::_resolveLink($menu);
                            break;
                    }

                    $children = (int) self::_children($menu->id);
                    $title = isset($menu->name) ? $menu->name : $menu->title;

                    if ($children) {
                        $class = array_merge($class, array('folder', 'menu'));
                    } else {
                        $class[] = 'file';
                    }
                    
                    if ($params->get('secure')) {
                        $link = self::toSSL($link);
                    }

                    $items[] = array(
                        'id' => $children ? 'index.php?option=com_menu&view=menu&id=' . $menu->id : $link,
                        'url' => self::route($link),
                        'name' => $title . ' / ' . $menu->alias,
                        'class' => implode(' ', $class)
                    );
                }
                break;
            // get menu items
            case 'submenu':
                $menus = self::_menu($args->id);
                foreach ($menus as $menu) {
                    if ($menu->type == 'menulink') {
                        //$menu = AdvlinkMenu::_alias($menu->id);
                    }

                    $title = isset($menu->name) ? $menu->name : $menu->title;

                    // get params
                    $params = defined('JPATH_PLATFORM') ? new JRegistry($menu->params) : new JParameter($menu->params);

                    // resolve link
                    $link = self::_resolveLink($menu);
                    
                    if ($params->get('secure')) {
                        $link = self::toSSL($link);
                    }

                    $items[] = array(
                        'id' => self::route($link),
                        'name' => $title . ' / ' . $menu->alias,
                        'class' => $children ? 'folder menu' : 'file'
                    );
                }
                break;
        }
        return $items;
    }
    
    /**
     * Convert link to SSL
     * @param type $link
     * @return string
     */
    private static function toSSL($link) {
        if (strcasecmp(substr($link, 0, 4), 'http') && (strpos($link, 'index.php?') !== false)) {
            $uri = JURI::getInstance();

            // Get prefix
            $prefix = $uri->toString(array('host', 'port'));

            // trim slashes
            $link = trim($link, '/');

            // Build the URL.
            $link = 'https://' . $prefix . '/' . $link;
        }
        
        return $link;
    }

    private static function _resolveLink($menu, $secure) {
        $wf = WFEditorPlugin::getInstance();

        // get link from menu object
        $link = $menu->link;

        // internal link 
        if ($link && strpos($link, 'index.php') === 0) {
            if ($wf->getParam('links.joomlalinks.menu_resolve_alias', 1) == 1) {
                // no Itemid
                if (strpos($link, 'Itemid=') === false) {
                    $link .= '&Itemid=' . $menu->id;
                }
                // short link
            } else {
                $link = 'index.php?Itemid=' . $menu->id;
            }
        }

        return $link;
    }

    private function _types() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('*')->from('#__menu_types');
        } else {
            $query = 'SELECT * FROM #__menu_types';
        }

        $db->setQuery($query, 0);
        return $db->loadObjectList();
    }

    private function _alias($id) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('params')->from('#__menu')->where('id = ' . (int) $id);
        } else {
            $query = 'SELECT params FROM #__menu WHERE id = ' . (int) $id;
        }

        $db->setQuery($query, 0);
        $params = new JRegistry($db->loadResult());

        $query->clear();

        if (is_object($query)) {
            $query->select('id, name, link, alias')->from('#__menu')->where(array('published = 1', 'id = ' . (int) $params->get('menu_item'), 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')'))->order('name');
        } else {
            $query = 'SELECT id, name, link, alias'
                    . ' FROM #__menu'
                    . ' WHERE published = 1'
                    . ' AND id = ' . (int) $params->get('menu_item')
                    . ' AND access <= ' . (int) $user->get('aid')
                    . ' ORDER BY name';
        }

        $db->setQuery($query, 0);
        return $db->loadObject();
    }

    private function _children($id) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('COUNT(id)')->from('#__menu')->where(array('published = 1', 'client_id = 0', 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')'));

            if ($id) {
                $query->where('parent_id = ' . (int) $id);
            }
        } else {
            if ($id) {
                $where = ' AND parent = ' . (int) $id;
            }

            $query = 'SELECT COUNT(id)'
                    . ' FROM #__menu'
                    . ' WHERE published = 1'
                    . ' AND access <= ' . (int) $user->get('aid')
                    . $where;
        }

        $db->setQuery($query, 0);
        return $db->loadResult();
    }

    private function _menu($parent = 0, $type = 0) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select('m.*')->from('#__menu AS m');

            if ($type) {
                $query->innerJoin('#__menu_types AS s ON s.id = ' . (int) $type);
                $query->where('m.menutype = s.menutype');
            }

            if ($parent == 0) {
                $parent = 1;
            }

            $query->where(array('m.published = 1', 'm.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')', 'm.parent_id = ' . (int) $parent));
            $query->order('m.lft ASC');
        } else {
            $where = '';
            $join = '';

            if ($type) {
                $join = ' INNER JOIN #__menu_types AS s ON s.id = ' . intval($type);
                $where = ' AND m.menutype = s.menutype';
            }
            $query = 'SELECT m.* FROM #__menu AS m'
                    . $join
                    . ' WHERE m.published = 1'
                    . ' AND m.access <= ' . (int) $user->get('aid')
                    . ' AND m.parent = ' . (int) $parent
                    . $where
                    . ' ORDER BY m.id'
            ;
        }

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
