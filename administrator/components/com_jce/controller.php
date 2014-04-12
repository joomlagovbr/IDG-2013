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

wfimport('admin.classes.controller');

class WFController extends WFControllerBase {

    /**
     * Custom Constructor
     */
    public function __construct($default = array()) {
        parent::__construct($default);

        // load helpers
        wfimport('admin.helpers.parameter');
        wfimport('admin.helpers.extension');
        wfimport('admin.helpers.xml');
    }

    private function loadMenu() {
        $view = JRequest::getWord('view', 'cpanel');

        wfimport('admin.models.model');

        JSubMenuHelper::addEntry(WFText::_('WF_CPANEL'), 'index.php?option=com_jce&view=cpanel', $view == 'cpanel');

        $subMenus = array(
            'WF_CONFIGURATION' => 'config',
            'WF_PROFILES' => 'profiles',
            'WF_INSTALL' => 'installer'
        );

        if (JPluginHelper::isEnabled('system', 'jcemediabox')) {
            $subMenus['WF_MEDIABOX'] = 'mediabox';
        }

        foreach ($subMenus as $menu => $item) {
            if (WFModel::authorize($item)) {
                JSubMenuHelper::addEntry(WFText::_($menu), 'index.php?option=com_jce&view=' . $item, $view == $item);
            }
        }
    }

    /**
     * Create the View. 
     * This is an overloaded function of JController::getView 
     * and includes addition of the JDocument Object with required scripts and styles
     * @return object
     */
    public function getView($name = '', $type = '', $prefix = '', $config = array()) {        
        $language = JFactory::getLanguage();
        $language->load('com_jce', JPATH_ADMINISTRATOR);

        $document = JFactory::getDocument();

        if (!$name) {
            $name = JRequest::getWord('view', 'cpanel');
        }

        if (!$type) {
            $type = $document->getType();
        }

        if (empty($config)) {
            $config = array(
                'base_path' => dirname(__FILE__)
            );
        }
        
        $model = $this->getModel($name);

        $view = parent::getView($name, $type, $prefix, $config);
        $document = JFactory::getDocument();
        
        // check for bootstrap
        $bootstrap = is_file(JPATH_LIBRARIES . '/cms/html/bootstrap.php'); 

        // not using JUI...
        if (!$bootstrap) {
            // set device-width meta
            $document->setMetaData('meta', 'width=device-width, initial-scale=1.0');

            // JQuery UI
            $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/jquery/js/jquery-' . WF_JQUERY . '.min.js');
            // jQuery noConflict
            $view->addScriptDeclaration('jQuery.noConflict();');
        } else {
            JHtml::_('bootstrap.framework');
        }

        // JQuery UI
        $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/jquery/js/jquery-ui-' . WF_JQUERYUI . '.custom.min.js');
        // JQuery Touch Punch
        $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/jquery/js/jquery.ui.touch-punch.min.js');

        $scripts = array();

        switch ($name) {
            case 'help':
                $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/help.js');                
                break;
            default:
                $view->addStyleSheet(JURI::root(true) . '/administrator/components/com_jce/media/css/global.css');
                
                // load Joomla! core javascript
                if (method_exists('JHtml', 'core')) {
                    JHtml::core();
                }

                require_once(JPATH_ADMINISTRATOR . '/includes/toolbar.php');

                JToolBarHelper::title(WFText::_('WF_ADMINISTRATION') . ' :: ' . WFText::_('WF_' . strtoupper($name)), 'logo.png');

                $params = WFParameterHelper::getComponentParams();
                $theme = $params->get('preferences.theme', 'jce');

                $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/tips.js');
                $view->addScript(JURI::root(true) . '/components/com_jce/editor/libraries/js/html5.js');
                $view->addScript(JURI::root(true) . '/administrator/components/com_jce/media/js/jce.js');

                $options = array(
                    'labels' => array(
                        'ok' => WFText::_('WF_LABEL_OK'),
                        'cancel' => WFText::_('WF_LABEL_CANCEL'),
                        'select' => WFText::_('WF_LABEL_SELECT'),
                        'save' => WFText::_('WF_LABEL_SAVE'),
                        'saveclose' => WFText::_('WF_LABEL_SAVECLOSE'),
                        'alert' => WFText::_('WF_LABEL_ALERT'),
                        'required' => WFText::_('WF_MESSAGE_REQUIRED')
                    ),
                    'bootstrap' => $bootstrap
                );

                $view->addScriptDeclaration('jQuery.jce.options = ' . json_encode($options) . ';');

                $view->addHelperPath(dirname(__FILE__) . '/helpers');
                $this->addModelPath(dirname(__FILE__) . '/models');

                $view->loadHelper('toolbar');
                $view->loadHelper('xml');
                $view->loadHelper($name);

                $this->loadMenu();

                break;
        }

        if ($model = $this->getModel($name)) {
            $view->setModel($model, true);
        }

        $view->assignRef('document', $document);

        return $view;
    }
    
    protected function getStyles()
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        
        wfimport('admin.helpers.extension');
        
        $view = JRequest::getCmd('view', 'cpanel');

        $component 	= WFExtensionHelper::getComponent();        
        $params 	= new WFParameter($component->params);
        
        $theme  	= $params->get('preferences.theme', 'jce');
        $site_path      = JPATH_COMPONENT_SITE . '/editor/libraries/css';
	$admin_path     = JPATH_COMPONENT_ADMINISTRATOR . '/media/css';
        
        // Load styles
        $styles = array();
        
        if (!JFolder::exists($site_path  . '/jquery/' . $theme)) {
            $theme = 'jce';
        }
        
        if (JFolder::exists($site_path . '/jquery/' . $theme)) {
            $files = JFolder::files($site_path . '/jquery/' . $theme, '\.css');
            
            foreach ($files as $file) {
                //$styles[] = 'components/com_jce/editor/libraries/css/jquery/' . $theme . '/' . $file;
            }
        }

		// admin global css
        $styles = array_merge($styles, array(
            'administrator/components/com_jce/media/css/global.css'
        ));
        
        return $styles;
    }

    public function pack() {
        
    }

    /**
     * Display View
     * @return 
     */
    public function display($cachable = false, $params = false) {
        $view = $this->getView();
        $view->display();
    }

    /**
     * Generic cancel method
     * @return 
     */
    public function cancel() {
        // Check for request forgeries
        JRequest::checkToken() or die('Invalid Token');
        $this->setRedirect(JRoute::_('index.php?option=com_jce&view=cpanel', false));
    }

    public function check() {
        // we already no its broken..
        if (JRequest::getCmd('task') == 'repair') {
            return;
        }

        wfimport('admin.models.profiles');
        $profiles = new WFModelProfiles();

        $state = $profiles->checkTable();

        // Check Profiles DB
        if (!$state) {
            $link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=tables', WFText::_('WF_DB_CREATE_RESTORE'));
            self::_redirect(WFText::_('WF_DB_PROFILES_ERROR') . ' - ' . $link, 'error');
        }

        if ($state) {
            if (!$profiles->checkTableContents()) {
                $link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=tables', WFText::_('WF_DB_CREATE_RESTORE'));
                self::_redirect(WFText::_('WF_DB_PROFILES_ERROR') . ' - ' . $link, 'error');
            }
        }

        jimport('joomla.plugin.helper');

        // Check Editor is installed
        if (JPluginHelper::getPlugin('editors', 'jce') === false) {
            $link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=editor', WFText::_('WF_EDITOR_INSTALL'));
            self::_redirect(WFText::_('WF_EDITOR_INSTALLED_MANUAL_ERROR') . ' - ' . $link, 'error');
        }
    }

    public function repair() {
        $app = JFactory::getApplication();
        $type = JRequest::getWord('type', 'tables');

        switch ($type) {
            case 'tables' :                
                wfimport('admin.models.profiles');
                $profiles = new WFModelProfiles();

                $profiles->installProfiles();

                $this->setRedirect(JRoute::_('index.php?option=com_jce&view=cpanel', false));

                break;
            case 'editor' :
                $source = dirname(__FILE__) . '/packages/editors';

                if (is_dir($source)) {
                    jimport('joomla.installer.installer');

                    $installer = new JInstaller();
                    if ($installer->install($source)) {
                        $app->enqueueMessage(WFText::_('WF_EDITOR_INSTALL_SUCCESS'));
                    } else {
                        $app->enqueueMessage(WFText::_('WF_EDITOR_INSTALL_FAILED'));
                    }

                    $this->setRedirect(JRoute::_('index.php?option=com_jce&view=cpanel', false));
                }

                break;
        }
    }

    public function authorize($task) {
        wfimport('admin.models.model');
        
        // map updates/blank/cpanel task to manage
        if (empty($task) || $task == 'cpanel' || $task == 'updates') {
            $task = 'manage';
        }

        if (WFModel::authorize($task) === false) {
            $this->setRedirect('index.php', WFText::_('ALERTNOTAUTH'), 'error');
            return false;
        }

        return true;
    }

    private static function _redirect($msg = '', $state = '') {
        $app = JFactory::getApplication();

        if ($msg) {
            $app->enqueueMessage($msg, $state);
        }
        JRequest::setVar('view', 'cpanel');
        JRequest::setVar('task', '');

        return false;
    }
    
    public function cleanInput($input, $method = 'string') {
        $filter = JFilterInput::getInstance();
        $input  = (array) $input;

        foreach($input as $k => $v) {
            if (is_array($v)) {
                $input[$k] = $this->cleanInput($v, $method);
            } else {
                $input[$k] = $filter->clean($v, $method);
            }
        }
        
        return $input;
    }
}

?>
