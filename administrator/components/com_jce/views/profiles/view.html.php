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

class WFViewProfiles extends WFView {
    
    private function getOptions($params) {
        wfimport('admin.models.editor');
        
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
                'stylesheets' => (array) WFModelEditor::getStyleSheets(),
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

    public function display($tpl = null) {
        $app = JFactory::getApplication();

        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $acl = JFactory::getACL();

        $client = 'admin';

        $view = JRequest::getWord('view');
        $task = JRequest::getWord('task');
        $option = JRequest::getWord('option');

        $lists = array();

        $model = $this->getModel();

        switch ($task) {
            default:
            case 'publish':
            case 'unpublish':
            case 'remove':
            case 'save':
            case 'copy':
                $filter_order = $app->getUserStateFromRequest("$option.$view.filter_order", 'filter_order', 'p.ordering', 'cmd');
                $filter_order_Dir = $app->getUserStateFromRequest("$option.$view.filter_order_Dir", 'filter_order_Dir', '', 'word');
                $filter_state = $app->getUserStateFromRequest("$option.$view.filter_state", 'filter_state', '', 'word');
                $search = $app->getUserStateFromRequest("$option.$view.search", 'search', '', 'cmd');
                $search = JString::strtolower($search);

                $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
                $limitstart = $app->getUserStateFromRequest("$option.$view.limitstart", 'limitstart', 0, 'int');

                $where = array();

                if ($search) {
                    if (method_exists($db, 'escape')) {
                        $search = $db->escape($search, true);
                    } else {
                        $search = $db->getEscaped($search, true);
                    }
                    
                    $where[] = 'LOWER( p.name ) LIKE ' . $db->Quote('%' . $search . '%', false);
                }
                if ($filter_state) {
                    if ($filter_state == 'P') {
                        $where[] = 'p.published = 1';
                    } else if ($filter_state == 'U') {
                        $where[] = 'p.published = 0';
                    }
                }
                $order = array($filter_order, $filter_order_Dir);
                
                // get the total number of records
                $query = $db->getQuery(true);
                if (is_object($query)) {
                    $query->select('COUNT(p.id)')->from('#__wf_profiles AS p');
                    
                    if (count($where)) {
                        $query->where($where);
                    }
                    
                } else {
                    $query = 'SELECT COUNT(p.id)' 
                    . ' FROM #__wf_profiles AS p' 
                    . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
                }

                $db->setQuery($query);
                $total = $db->loadResult();

                jimport('joomla.html.pagination');
                $pagination = new JPagination($total, $limitstart, $limit);

                $query = $db->getQuery(true);
                if (is_object($query)) {
                    $query->select('p.*, u.name AS editor')->from('#__wf_profiles AS p')->join('LEFT', '#__users AS u ON u.id = p.checked_out');
                    
                    if (count($where)) {
                        $query->where($where);
                    }
                    
                    $query->order(trim(implode(' ', $order)));
                    
                } else {
                    $query = 'SELECT p.*, u.name AS editor'
                    . ' FROM #__wf_profiles AS p'
                    . ' LEFT JOIN #__users AS u ON u.id = p.checked_out'
                    . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '')
                    . ' ORDER BY ' . trim(implode(' ', $order));
                }

                $db->setQuery($query, $pagination->limitstart, $pagination->limit);
                $rows = $db->loadObjectList();
                
                if ($db->getErrorNum()) {
                    echo $db->stderr();
                    return false;
                }

                // table ordering
                $lists['order_Dir'] = $filter_order_Dir;
                $lists['order'] = $filter_order;

                // search filter
                $lists['search'] = $search;

                $this->assignRef('user', $user);
                $this->assignRef('lists', $lists);
                $this->assignRef('rows', $rows);
                $this->assignRef('pagination', $pagination);

                //JToolBarHelper::title(WFText::_('WF_PROFILES_TITLE').' : '.WFText::_('WF_PROFILES_LIST'), 'profiles.png' );

                WFToolbarHelper::addNewX();
                WFToolbarHelper::editListX();
                WFToolbarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'WF_PROFILES_COPY', true);
                WFToolbarHelper::export();

                if (count($rows) > 1) {
                    WFToolbarHelper::publishList();
                    WFToolbarHelper::unpublishList();
                    WFToolbarHelper::deleteList('', 'remove', 'WF_PROFILES_DELETE');
                }
                WFToolbarHelper::help('profiles.about');

                $options = array(
                    'button' => '#upload_button',
                    'task' => 'import',
                    'labels' => array(
                        'browse' => WFText::_('WF_LABEL_BROWSE'),
                        'alert' => WFText::_('WF_PROFILES_IMPORT_BROWSE_ERROR')
                    )
                );

                $this->addScript(JURI::root(true) . '/administrator/components/com_jce/media/js/uploads.js');
                $this->addScriptDeclaration('jQuery(document).ready(function($){$(\'input[type="file"]\').upload(' . json_encode($options) . ')});');
                
                // load styles
                $this->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/upload.css');

                $this->setLayout('default');
                break;
            case 'apply':
            case 'add':
            case 'edit':
                JHtml::_('behavior.modal');

                // Load media   
                $scripts = array(
                    'profiles.js',
                    'extensions.js',
                    'checklist.js',
                    'styleformat.js',
                    'fonts.js',
                    'blockformats.js'
                );
                // Load scripts
                foreach ($scripts as $script) {
                    $this->addScript(JURI::root(true) . '/administrator/components/com_jce/media/js/' . $script);
                }
                
                $this->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/colorpicker.js');
                $this->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/select.js');
                
                // load styles
                $this->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/profiles.css');

                $cid = JRequest::getVar('cid', array(0), '', 'array');
                JArrayHelper::toInteger($cid, array(0));

                $lists = array();
                $row = JTable::getInstance('profiles', 'WFTable');

                // load the row from the db table
                $row->load($cid[0]);

                // fail if checked out not by 'me'

                if ($row->isCheckedOut($user->get('id'))) {
                    $msg = JText::sprintf('WF_PROFILES_CHECKED_OUT', $row->name);
                    $this->setRedirect('index.php?option=' . $option . '&view=profiles', $msg, 'error');
                    return false;
                }
                // Load editor params
                $component = JComponentHelper::getComponent('com_jce');

                // Load Language
                $language = JFactory::getLanguage();
                $language->load('com_jce', JPATH_ADMINISTRATOR);
                $language->load('com_jce', JPATH_SITE);

                $language->load('plg_editors_jce', JPATH_ADMINISTRATOR);
                $plugins = $model->getPlugins();

                // load plugin languages
                foreach ($plugins as $plugin) {
                    if ($plugin->core == 0) {
                        // Load Language for plugin
                        $language->load('com_jce_' . $plugin->name, JPATH_SITE);
                    }
                }

                // load the row from the db table
                if ($cid[0]) {
                    $row->checkout($user->get('id'));
                } else {
                    $query = $db->getQuery(true);
                    
                    if (is_object($query)) {
                        $query->select('COUNT(id)')->from('#__wf_profiles');
                    } else {
                        $query = 'SELECT COUNT(id)' . ' FROM #__wf_profiles';
                    }

                    $db->setQuery($query);
                    $total = $db->loadResult();

                    // get the defaults from xml
                    $row = $model->getDefaultProfile();

                    if (!is_object($row)) {
                        $row->name = '';
                        $row->description = '';
                        $row->types = '';
                        $row->components = '';
                        $row->area = 0;
                        $row->types = '';
                        $row->rows = '';
                        $row->plugins = '';
                        $row->published = 1;
                        $row->ordering = 0;
                        $row->params = '{}';
                    }

                    $row->params = json_decode($row->params . ',' . $component->params);
                }

                $row->area = (isset($row->area)) ? $row->area : 0;
                
                $query = $db->getQuery(true);
                
                if (is_object($query)) {
                    $query->select('ordering AS value, name AS text')->from('#__wf_profiles')->where(array('published = 1', 'ordering > -10000', 'ordering < 10000'))->order('ordering');
                } else {
                    // build the html select list for ordering
                    $query = 'SELECT ordering AS value, name AS text'
                        . ' FROM #__wf_profiles'
                        . ' WHERE published = 1'
                        . ' AND ordering > -10000'
                        . ' AND ordering < 10000'
                        . ' ORDER BY ordering';
                }

                $order = JHTML::_('list.genericordering', $query);
                $lists['ordering']  = JHTML::_('select.genericlist', $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval($row->ordering));
                
                $lists['published'] = '';
                
                $options = array(
                    1 => WFText::_('WF_OPTION_YES'),
                    0 => WFTEXT::_('WF_OPTION_NO')
                );
                
                foreach($options as $value => $text) {
                    $checked = '';
                    
                    if ($value == $row->published) {
                        $checked = ' checked="checked"';
                    }
                    
                    $lists['published'] .= '<label class="radio inline"><input type="radio" id="published-' . $value . '" name="published" value="' . $value . '"' . $checked . ' />' . $text . '</label>';
                }

                $exclude = array(
                    'com_admin',
                    'com_cache',
                    'com_checkin',
                    'com_config',
                    'com_cpanel',
                    'com_finder',
                    'com_installer',
                    'com_languages',
                    'com_jce',
                    'com_login',
                    'com_mailto',
                    'com_menus',
                    'com_media',
                    'com_messages',
                    'com_newsfeeds',
                    'com_plugins',
                    'com_redirect',
                    'com_templates',
                    'com_users',
                    'com_wrapper',
                    'com_search',
                    'com_user',
                    'com_updates'
                );

                $query = $db->getQuery(true);

                if (is_object($query)) {
                    $query->select('element AS value, name AS text')->from('#__extensions')->where(array('type = ' . $db->Quote('component'), 'enabled = 1'))->order('name');
                } else {
                    $query = "SELECT `option` AS value, name AS text"
                    . " FROM #__components"
                    . " WHERE parent = 0"
                    . " AND enabled = 1"
                    . " ORDER BY name";
                }

                $db->setQuery($query);
                $components = $db->loadObjectList();

                $options = array();
                
                // load component languages
                for ($i = 0; $i < count($components); $i++) {
                    if (!in_array($components[$i]->value, $exclude)) {
                        $options[] = $components[$i];
                        // load system language file
                        $language->load($components[$i]->value . '.sys', JPATH_ADMINISTRATOR);
                    }
                }
                // set disabled attribute
                $disabled = (!$row->components) ? ' disabled="disabled"' : '';

                // components list
                $lists['components'] = '<ul id="components" class="checkbox-list">';

                foreach ($options as $option) {
                    $checked = in_array($option->value, explode(',', $row->components)) ? ' checked="checked"' : '';
                    $lists['components'] .= '<li><input type="checkbox" name="components[]" value="' . $option->value . '"' . $checked . $disabled . ' /><label class="checkbox">' . JText::_($option->text) . '</label></li>';
                }

                $lists['components'] .= '</ul>';

                // components select
                $options = array(
                    'all'       => WFText::_('WF_PROFILES_COMPONENTS_ALL'),
                    'select'    => WFText::_('WF_PROFILES_COMPONENTS_SELECT')
                );

                $lists['components-select'] = '';
                
                foreach($options as $value => $text) {
                    $checked = '';
                    
                    if ($row->components) {
                        if ($value == 'select') {
                            $checked = ' checked="checked"';
                        }
                    } else {
                        if ($value == 'all') {
                            $checked = ' checked="checked"';
                        }
                    }
                    
                    $lists['components-select'] .= '<label class="radio inline"><input type="radio" id="components-select-' . $value . '" name="components-select" value="' . $value . '"' . $checked . ' />' . $text . '</label>';
                }

                // area
                $options = array(
                    1 => WFText::_('WF_PROFILES_AREA_FRONTEND'),
                    2 => WFText::_('WF_PROFILES_AREA_BACKEND')      
                );
                
                $lists['area'] = '';
                
                foreach($options as $value => $text) {
                    $checked = '';
                    
                    if (!isset($row->area) || empty($row->area) || in_array($value, explode(',', $row->area))) {
                        $checked = ' checked="checked"';
                    }
                    
                    $lists['area'] .= '<label class="checkbox inline"><input type="checkbox" name="area[]" value="' . $value . '"'. $checked .' />' . $text . '</label>'; 
                }

                // device
                $options = array(
                    'desktop'   => WFText::_('WF_PROFILES_DEVICE_DESKTOP'),
                    'tablet'    => WFText::_('WF_PROFILES_DEVICE_TABLET'),
                    'phone'    => WFText::_('WF_PROFILES_DEVICE_PHONE')
                );
                
                $lists['device'] = '';
                
                foreach($options as $value => $text) {
                    $checked = '';
                    
                    if (!isset($row->device) || empty($row->device) || in_array($value, explode(',', $row->device))) {
                        $checked = ' checked="checked"';
                    }
                    
                    $lists['device'] .= '<label class="checkbox inline"><input type="checkbox" name="device[]" value="' . $value . '"'. $checked .' />' . $text . '</label>'; 
                }

                // user types from profile
                $query = $db->getQuery(true);

                if (is_object($query)) {
                    $query->select('types')->from('#__wf_profiles')->where('id NOT IN (17,28,29,30)');
                    
                    $db->setQuery($query);
                    $types = $db->loadColumn();
                } else {
                    $query = 'SELECT types'
                    . ' FROM #__wf_profiles'
                    // Exclude ROOT, USERS, Super Administrator, Public Frontend, Public Backend
                    . ' WHERE id NOT IN (17,28,29,30)';
                    
                    $db->setQuery($query);
                    $types = $db->loadResultArray();
                }

                if (defined('JPATH_PLATFORM')) {
                    $options = array();
                    
                    $query = $db->getQuery(true);

                    $query->select('a.id AS value, a.title AS text')->from('#__usergroups AS a');

                    // Add the level in the tree.
                    $query->select('COUNT(DISTINCT b.id) AS level');
                    $query->join('LEFT OUTER', '#__usergroups AS b ON a.lft > b.lft AND a.rgt < b.rgt');
                    $query->group('a.id, a.lft, a.rgt, a.parent_id, a.title');
                    $query->order('a.lft ASC');

                    // Get the options.
                    $db->setQuery($query);
                    $options = $db->loadObjectList() or die($db->stdErr());

                    // Pad the option text with spaces using depth level as a multiplier.
                    for ($i = 0, $n = count($options); $i < $n; $i++) {
                        $options[$i]->text = str_repeat('<span class="gi">|&mdash;</span>', $options[$i]->level) . $options[$i]->text;
                    }
                } else {
                    // get list of Groups for dropdown filter
                    $query = 'SELECT id AS value, name AS text' 
                    . ' FROM #__core_acl_aro_groups'
                    // Exclude ROOT, USERS, Super Administrator, Public Frontend, Public Backend
                    . ' WHERE id NOT IN (17,28,29,30)';
                    $db->setQuery($query);
                    $types = $db->loadObjectList();

                    $i = '-';
                    $options = array(
                        JHTML::_('select.option', '0', WFText::_('Guest'))
                    );

                    foreach ($types as $type) {
                        $options[] = JHTML::_('select.option', $type->value, $i . WFText::_($type->text));
                        $i .= '|&mdash;';
                    }
                }

                $lists['usergroups'] = '<ul id="user-groups" class="checkbox-list">';

                foreach ($options as $option) {
                    $checked = in_array($option->value, explode(',', $row->types)) ? ' checked="checked"' : '';
                    $lists['usergroups'] .= '<li><input type="checkbox" name="usergroups[]" value="' . $option->value . '"' . $checked . ' /><label class="checkbox">' . $option->text . '</label></li>';
                }

                $lists['usergroups'] .= '</ul>';

                // users
                $options = array();

                if ($row->id && $row->users) {
                    $query = $db->getQuery(true);

                    if (is_object($query)) {
                        $query->select('id AS value, username AS text')->from('#__users')->where('id IN (' . $row->users . ')');
                    } else {
                        $query = 'SELECT id as value, username as text'
                        . ' FROM #__users'
                        . ' WHERE id IN (' . $row->users . ')';
                    }

                    $db->setQuery($query);
                    $gusers = $db->loadObjectList();

                    if ($gusers) {
                        foreach ($gusers as $guser) {
                            $options[] = JHTML::_('select.option', $guser->value, $guser->text);
                        }
                    }
                }
                $lists['users'] = '<ul id="users" class="users-list">';

                foreach ($options as $option) {
                    $lists['users'] .= '<li><input type="hidden" name="users[]" value="' . $option->value . '" /><label><span class="users-list-delete"></span>' . $option->text . '</label></li>';
                }

                $lists['users'] .= '</ul>';

                // Get layout rows
                $rows = $model->getRowArray($row->rows);

                // assign params to row
                $model->getEditorParams($row);
                $model->getLayoutParams($row);

                // create $params object for "editor"
                $params = new WFParameter($row->params, '', 'editor');

                // load other theme css
                foreach ($model->getThemes() as $theme) {
                    $files = JFolder::files($theme, 'ui([\w\.]*)\.css$');

                    foreach ($files as $file) {
                        $this->addStyleSheet(JURI::root(true) . '/components/com_jce/editor/tiny_mce/themes/advanced/skins/' . basename($theme) . '/' . $file);
                    }
                }

                // assign references
                $this->assignRef('lists', $lists);
                $this->assignRef('profile', $row);
                $this->assignRef('rows', $rows);
                $this->assignRef('params', $params);
                $this->assignRef('plugins', $plugins);
                
                // get options for various widgets
                $options = $this->getOptions($params);
                
                // set suhosin flag
                $options['suhosin'] = ini_get('suhosin.post.max_vars') && (int) ini_get('suhosin.post.max_vars') < 1000;

                $this->addScriptDeclaration('jQuery.jce.Profiles.options = ' . json_encode($options) . ';');

                // set toolbar
                if ($row->id) {
                    JToolBarHelper::title(WFText::_('WF_ADMINISTRATION') . ' :: ' . WFText::_('WF_PROFILES_EDIT') . ' - [' . $row->name . ']', 'logo.png');
                } else {
                    JToolBarHelper::title(WFText::_('WF_ADMINISTRATION') . ' :: ' . WFText::_('WF_PROFILES_NEW'), 'logo.png');
                }

                // set buttons
                WFToolbarHelper::apply();
                WFToolbarHelper::save();
                WFToolbarHelper::cancel('cancelEdit', 'Close');
                WFToolbarHelper::help('profiles.edit');

                JRequest::setVar('hidemainmenu', 1);

                $this->setLayout('form');
                break;
        }
        $this->assignRef('model', $model);

        parent::display($tpl);
    }

}
