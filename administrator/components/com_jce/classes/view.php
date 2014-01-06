<?php

jimport('joomla.application.component.view');

if (!class_exists('WFViewBase')) {
    if (interface_exists('JView')) {

        abstract class WFViewBase extends JViewLegacy {
            
        }

    } else {

        abstract class WFViewBase extends JView {
            
        }

    }
}

class WFView extends WFViewBase {

    /**
     * Array of linked scripts
     *
     * @var    array
     */
    protected $scripts = array();

    /**
     * Array of linked style sheets
     *
     * @var    array
     */
    protected $stylesheets = array();

    /**
     * Array of included style declarations
     *
     * @var    array
     */
    protected $styles = array();

    /**
     * Array of scripts placed in the header
     *
     * @var    array
     */
    protected $javascript = array();

    public function display($tpl = null) {
        $document   = JFactory::getDocument();
        $tab        = $document->_getTab();
        $end        = $document->_getLineEnd();
        
        $model      = new WFModel();
        
        foreach ($this->stylesheets as $style) {
            if (strpos($style, '?version=') === false || strpos($style, '?v=') === false) {
                $style .= '?v=' . $model->getVersion();
            }

            $document->addCustomTag($tab . '<link rel="stylesheet" href="' . $style . '" type="text/css" />' . $end);
        }
        
        foreach ($this->scripts as $script) {
            if (strpos($script, '?version=') === false || strpos($script, '?v=') === false) {
                $script .= '?v=' . $model->getVersion();
            }
            
            $document->addCustomTag($tab . '<script src="' . $script . '" type="text/javascript"></script>' . $end);
        }
        
        $head = array();

        foreach ($this->javascript as $script) {
            $head[] = $tab . '<script type="text/javascript">' . $script . '</script>' . $end;
        }

        foreach ($this->styles as $style) {
            $head[] = $tab . '<style type="text/css">' . $style . '></style>' . $end;
        }
        
        if (!empty($head)) { 
            $document->addCustomTag(implode('', $head));
        }

        parent::display($tpl);
    }

    public function addScript($url) {        
        $this->scripts[] = $url;
    }

    public function addStyleSheet($url) {
        $this->stylesheets[] = $url;
    }

    public function addScriptDeclaration($text) {
        $this->javascript[] = $text;
    }

    public function addStyleDeclaration($text) {
        $this->styles[] = $text;
    }

}

?>
