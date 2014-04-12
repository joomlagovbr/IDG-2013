<?php

/**
 * @copyright      Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved
 * @@license@@
 */
defined('_JEXEC') or die;

/**
 * JCE File Browser Quick Icon plugin
 *
 * @package		JCE
 * @subpackage	Quickicon.JCE
 * @since		2.1
 */
class plgQuickiconJcefilebrowser extends JPlugin {

    /**
     * Constructor
     *
     * @param       object  $subject The object to observe
     * @param       array   $config  An array that holds the plugin configuration
     *
     * @since       2.5
     */
    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);

        $app = JFactory::getApplication();

        // only in Admin and only if the component is enabled
        if ($app->isSite() || JComponentHelper::getComponent('com_jce', true)->enabled === false) {
            return;
        }

        $this->loadLanguage();
    }

    /**
     * This method is called when the Quick Icons module is constructing its set
     * of icons. You can return an array which defines a single icon and it will
     * be rendered right after the stock Quick Icons.
     *
     * @param  $context  The calling context
     *
     * @return array A list of icon definition associative arrays, consisting of the
     * 				 keys link, image, text and access.
     *
     * @since       2.5
     */
    public function onGetIcons($context) {
        @include_once(JPATH_ADMINISTRATOR . '/components/com_jce/models/model.php');

        // check for class to prevent fatal errors
        if (!class_exists('WFModel')) {
            return;
        }

        if ($context != $this->params->get('context', 'mod_quickicon') || WFModel::authorize('browser') === false) {
            return;
        }

        $document = JFactory::getDocument();
        $language = JFactory::getLanguage();

        $language->load('com_jce', JPATH_ADMINISTRATOR);

        $width = $this->params->get('width', 800);
        $height = $this->params->get('height', 600);
        $filter = $this->params->get('filter', '');

        JHtml::_('behavior.modal');

        $document->addScriptDeclaration(
                "
		window.addEvent('domready', function() {
			SqueezeBox.assign($$('#plg_quickicon_jcefilebrowser a'), {
				handler: 'iframe', size: {x: " . $width . ", y: " . $height . "}
			});
		});"
        );

        require_once(JPATH_ADMINISTRATOR . '/components/com_jce/helpers/browser.php');
        
        $version = new JVersion;
        $icon = $version->isCompatible('3.0') ? 'pictures' : 'header/icon-48-media.png';

        return array(array(
                'link' => WFBrowserHelper::getBrowserLink('', $filter),
                'image' => $icon,
                'icon' => 'pictures',
                'access' => array('jce.browser', 'com_jce'),
                'text' => JText::_('WF_QUICKICON_BROWSER'),
                'id' => 'plg_quickicon_jcefilebrowser'
        ));
    }

}