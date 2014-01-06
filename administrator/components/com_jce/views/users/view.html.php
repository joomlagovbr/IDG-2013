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

wfimport('admin.classes.view');

class WFViewUsers extends WFView {

    public function display($tpl = null) {
        $app = JFactory::getApplication();
        $option = JRequest::getCmd('option');

        $client = 'admin';
        $view = JRequest::getWord('view');

        $db = JFactory::getDBO();
        $currentUser = JFactory::getUser();
        $acl = JFactory::getACL();

        $model = $this->getModel();

        $this->addScript('components/com_jce/media/js/users.js');

        $filter_order       = $app->getUserStateFromRequest("$option.$view.filter_order", 'filter_order', 'a.name', 'cmd');
        $filter_order_Dir   = $app->getUserStateFromRequest("$option.$view.filter_order_Dir", 'filter_order_Dir', '', 'word');
        $filter_type        = $app->getUserStateFromRequest("$option.$view.filter_type", 'filter_type', '', 'int');
        $search             = $app->getUserStateFromRequest("$option.$view.search", 'search', '', 'cmd');
        $search             = JString::strtolower($search);

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest("$option.$view.limitstart", 'limitstart', 0, 'int');

        $where = array();

        if (isset($search) && $search != '') {
            $searchEscaped = $db->Quote('%' . $db->getEscaped($search, true) . '%', false);
            $where[] = 'a.username LIKE ' . $searchEscaped . ' OR a.email LIKE ' . $searchEscaped . ' OR a.name LIKE ' . $searchEscaped;
        }

        if (defined('JPATH_PLATFORM')) {            
            if ($filter_type) {
                $where[] = 'map.group_id = LOWER(' . $db->Quote($filter_type) . ') ';
            }
        } else {
            if ($filter_type) {
                $where[] = 'a.gid =' . (int) $filter_type;
            }
            // exclude any child group id's for this user
            $pgids = $acl->get_group_children($currentUser->get('gid'), 'ARO', 'RECURSE');

            if (is_array($pgids) && count($pgids) > 0) {
                JArrayHelper::toInteger($pgids);
                $where[] = 'a.gid NOT IN (' . implode(',', $pgids) . ')';
            }

            // Exclude ROOT, USERS, Super Administrator, Public Frontend, Public Backend
            $where[] = 'a.gid NOT IN (17,28,29,30)';
        }

        // Only unblocked users
        $where[] = 'a.block = 0';

        $orderby = array($filter_order, $filter_order_Dir);

        jimport('joomla.html.pagination');

        if (defined('JPATH_PLATFORM')) {
            $query = $db->getQuery(true);

            $query->select('COUNT(a.id)')->from('#__users AS a')->join('LEFT', '#__user_usergroup_map AS map ON map.user_id = a.id');

            if (count($where)) {
                $query->where($where);
            }

            $db->setQuery($query);
            $total = $db->loadResult();

            $pagination = new JPagination($total, $limitstart, $limit);

            $query = $db->getQuery(true);

            $query->select('a.id, a.name, a.username, g.title AS groupname');
            $query->from('#__users AS a');
            $query->join('LEFT', '#__user_usergroup_map AS map ON map.user_id = a.id');
            $query->join('LEFT', '#__usergroups AS g ON g.id = map.group_id');

            if (count($where)) {
                $query->where($where);
            }

            $query->group('a.id, a.name, a.username, g.title');
            $query->order(trim(implode(' ', $orderby)));
            
        } else {
            $query = 'SELECT COUNT(a.id)'
                    . ' FROM #__users AS a'
                    . $where
            ;
            $db->setQuery($query);
            $total = $db->loadResult();
            $pagination = new JPagination($total, $limitstart, $limit);

            $query = 'SELECT a.id, a.name, a.username, g.name AS groupname'
                    . ' FROM #__users AS a'
                    . ' INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id'
                    . ' INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id'
                    . ' INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id'
                    . ( count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '' )
                    . ' GROUP BY a.id, a.name, a.username, g.name'
                    . ' ORDER BY ' . trim(implode(' ', $orderby))
            ;
        }

        $db->setQuery($query, $pagination->limitstart, $pagination->limit);
        $rows = $db->loadObjectList();

        $options = array(
            JHTML::_('select.option', '', '- ' . WFText::_('WF_USERS_GROUP_SELECT') . ' -')
        );

        if (defined('JPATH_PLATFORM')) {
            $query = $db->getQuery(true);

            $query->select('a.id AS value, a.title AS text')->from('#__usergroups AS a');

            // Add the level in the tree.
            $query->select('COUNT(DISTINCT b.id) AS level');
            $query->join('LEFT OUTER', '#__usergroups AS b ON a.lft > b.lft AND a.rgt < b.rgt');
            $query->group('a.id, a.lft, a.rgt, a.parent_id, a.title');
            $query->order('a.lft ASC');

            // Get the options.
            $db->setQuery($query);
            $items = $db->loadObjectList() or die($db->stdErr());

            // Pad the option text with spaces using depth level as a multiplier.
            for ($i = 0, $n = count($items); $i < $n; $i++) {
                $options[] = JHTML::_('select.option', $items[$i]->value, str_repeat('- ', $items[$i]->level) . $items[$i]->text);
            }
        } else {
            // get list of Groups for dropdown filter
            $query = 'SELECT id AS value, name AS text'
                    . ' FROM #__core_acl_aro_groups'
                    // Exclude ROOT, USERS, Super Administrator, Public Frontend, Public Backend
                    . ' WHERE id NOT IN (17,28,29,30)';
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $i = '-';
            
            //$options[] = JHTML::_('select.option', '0', WFText::_('Guest'));

            foreach ($items as $item) {
                $options[] = JHTML::_('select.option', $item->value, $i . WFText::_($item->text));
                $i .= '-';
            }
        }

        $lists['group'] = JHTML::_('select.genericlist', $options, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $filter_type);

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // search filter
        $lists['search'] = $search;

        $this->assign('user', JFactory::getUser());
        $this->assign('lists', $lists);
        $this->assign('items', $rows);
        $this->assign('pagination', $pagination);
        
        $this->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/users.css');

        parent::display($tpl);
    }

}
