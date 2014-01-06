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

class JoomlalinksContact extends JObject {

    var $_option = 'com_contact';

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
    function & getInstance() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new JoomlalinksContact();
        }
        return $instance;
    }

    public function getOption() {
        return $this->_option;
    }

    public function getList() {
        //Reference to JConentEditor (JCE) instance
        $wf = WFEditorPlugin::getInstance();

        if ($wf->checkAccess('links.joomlalinks.contacts', 1)) {
            return '<li id="index.php?option=com_contact"><div class="tree-row"><div class="tree-image"></div><span class="folder contact nolink"><a href="javascript:;">' . WFText::_('WF_LINKS_JOOMLALINKS_CONTACTS') . '</a></span></div></li>';
        }
    }

    function getLinks($args) {
        $items = array();
        $view = isset($args->view) ? $args->view : '';
        switch ($view) {
            default:
                if (defined('JPATH_PLATFORM')) {
                    $categories = WFLinkBrowser::getCategory('com_contact');
                } else {
                    $categories = WFLinkBrowser::getCategory('com_contact_details');
                }

                foreach ($categories as $category) {
                    $itemid = WFLinkBrowser::getItemId('com_contact', array('category' => $category->id));
                    
                    if (defined('JPATH_PLATFORM')) {
                        $url = 'index.php?option=com_contact&view=category&id=';
                    } else {
                        $url = 'index.php?option=com_contact&view=category&catid=';
                    }

                    $items[] = array(
                        'id'    => 'index.php?option=com_contact&view=category&id=' . $category->id,
                        'url'   => $url . $category->slug . $itemid,
                        'name'  => $category->title . ' / ' . $category->alias,
                        'class' => 'folder contact'
                    );
                }
                break;
            case 'category':
                if (defined('JPATH_PLATFORM')) {
                    $categories = WFLinkBrowser::getCategory('com_contact', $args->id);

                    foreach ($categories as $category) {
                        $children = WFLinkBrowser::getCategory('com_contact', $category->id);

                        if ($children) {
                            $id = 'index.php?option=com_contact&view=category&id=' . $category->id;
                        } else {
                            $itemid = WFLinkBrowser::getItemId('com_contact', array('category' => $category->id));

                            if (!$itemid && isset($args->Itemid)) {
                                // fall back to the parent item's Itemid
                                $itemid = '&Itemid=' . $args->Itemid;
                            }

                            $id = 'index.php?option=com_contact&view=category&id=' . $category->slug . $itemid;
                        }

                        $items[] = array(
                            'id'    => $id,
                            'name'  => $category->title . ' / ' . $category->alias,
                            'class' => 'folder content'
                        );
                    }
                }

                $contacts = self::_contacts($args->id);

                foreach ($contacts as $contact) {
                    $catid  = $args->id ? '&catid=' . $args->id : '';
                    $itemid = WFLinkBrowser::getItemId('com_contact', array('contact' => $contact->id));

                    if (!$itemid && isset($args->Itemid)) {
                        // fall back to the parent item's Itemid
                        $itemid = '&Itemid=' . $args->Itemid;
                    }

                    $items[] = array(
                        'id' => 'index.php?option=com_contact&view=contact' . $catid . '&id=' . $contact->id . '-' . $contact->alias . $itemid,
                        'name' => $contact->name . ' / ' . $contact->alias,
                        'class' => 'file'
                    );
                }
                break;
        }
        return $items;
    }

    private static function _contacts($id) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $where = '';

        $query = $db->getQuery(true);

        if (is_object($query)) {
            $query->select(array('id', 'name', 'alias'))->from('#__contact_details')->where(array('catid='. (int) $id, 'published = 1', 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')'));
        } else {
            $query = 'SELECT id, name, alias'
            . ' FROM #__contact_details'
            . ' WHERE catid = ' . (int) $id
            . ' AND published = 1'
            . ' AND access <= ' . (int) $user->get('aid')
            . ' ORDER BY name'
            ;
        }

        $db->setQuery($query);
        return $db->loadObjectList();
    }

}

?>