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

class WFPopupsExtension extends WFExtension {
    
    protected static $instance;
    
    private $_popups = array();
    private $_templates = array();

    /**
     * Constructor activating the default information of the class
     *
     * @access  protected
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        $this->setProperties($config);
    }

    /**
     * Returns a reference to a plugin object
     *
     * This method must be invoked as:
     *    <pre>  $advlink =AdvLink::getInstance();</pre>
     *
     * @access  public
     * @return  JCE  The editor object.
     * @since 1.5
     */
    public static function getInstance($config = array()) {
        if (!isset(self::$instance)) {
            self::$instance = new WFPopupsExtension($config);
        }

        return self::$instance;
    }

    public function display() {
        parent::display();

        $document = WFDocument::getInstance();

        // Load javascript        
        $document->addScript(array(
            'popups'
        ), 'libraries.extensions');

        // get all popups extensions
        $popups = parent::loadExtensions('popups');

        $config = $this->getProperties();

        if ($config) {
            // Create global config
            $document->addScriptDeclaration('WFExtensions.Popups.setConfig(' . json_encode($config) . ');');
        }

        // Create an instance of each popup and check if enabled
        foreach ($popups as $name) {
            $popup = $this->getPopupExtension($name);

            if ($popup->isEnabled()) {
                $this->addPopup($name);

                $params = $popup->getParams();

                if (!empty($params)) {
                    $document->addScriptDeclaration('WFExtensions.Popups.setParams("' . $name . '",' . json_encode($params) . ');');
                }
            }
        }

        $tabs = WFTabs::getInstance();

        // Add popup tab and assign popups reference to document
        if (count($this->getPopups())) {
            $tabs->addTab('popups', 1);
            $tabs->getPanel('popups')->assign('popups', $this);
        }
    }

    private function getPopups() {
        return $this->_popups;
    }

    public function addPopup($popup) {
        $this->_popups[] = $popup;
    }

    private function getTemplates() {
        return $this->_templates;
    }

    public function addTemplate($template) {
        $this->_templates[] = $template;
    }

    private function getPopupExtension($name) {
        static $popups;

        if (!isset($popups)) {
            $popups = array();
        }

        if (empty($popups[$name])) {
            $classname = 'WFPopupsExtension_' . ucfirst($name);

            $popups[$name] = new $classname();
        }

        return $popups[$name];
    }

    public function getPopupList() {
        $options = array();

        $options[] = JHTML::_('select.option', '', '-- ' . WFText::_('WF_POPUP_TYPE_SELECT') . ' --');

        foreach ($this->getPopups() as $popup) {
            $options[] = JHTML::_('select.option', $popup, WFText::_('WF_POPUPS_' . strtoupper($popup) . '_TITLE'));
        }

        return JHTML::_('select.genericlist', $options, 'popup_list', 'class="inputbox levels" size="1"', 'value', 'text', $this->get('default'));
    }

    public function getPopupTemplates() {
        $output = '';

        $path = WF_EDITOR_EXTENSIONS . '/popups';

        $file = 'default.php';

        foreach ($this->getTemplates() as $template) {
            $wf = WFEditorPlugin::getInstance();
            $view = $wf->getView();

            $output .= $view->loadTemplate($template);
        }

        foreach ($this->getPopups() as $popup) {
            $view = new WFView(array(
                        'name' => $popup,
                        'base_path' => WF_EDITOR_EXTENSIONS . '/popups/' . $popup,
                        'template_path' => WF_EDITOR_EXTENSIONS . '/popups/' . $popup . '/tmpl'
                    ));

            $instance = $this->getPopupExtension($popup);
            $view->assign('popup', $instance);

            if (file_exists($path . '/' . $popup . '/tmpl/' . $file)) {
                ob_start();

                $output .= '<div id="popup_extension_' . $popup . '" style="display:none;">';

                $view->display();

                $output .= ob_get_contents();
                $output .= '</div>';
                ob_end_clean();
            }
        }

        return $output;
    }

}