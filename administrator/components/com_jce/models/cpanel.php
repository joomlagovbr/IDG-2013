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

// load base model
require_once(dirname(__FILE__) . '/model.php');

class WFModelCpanel extends WFModel {

    public function iconButton($link, $image, $text, $description = '', $disabled = false) {
        $lang = JFactory::getLanguage();

        if ($disabled) {
            $link = '#';
        }

        $description = $description ? $text . '::' . $description : $text;
        ?>
        <li class="cpanel-icon tooltip ui-corner-all" title="<?php echo $description; ?>">
            <a href="<?php echo $link; ?>"><?php echo JHTML::_('image.site', $image, '/components/com_jce/media/img/cpanel/', NULL, NULL, $text); ?><?php echo $text; ?></a>
        </li>
        <?php
    }

    public function getVersion() {
        $xml = WFXMLHelper::parseInstallManifest(JPATH_ADMINISTRATOR . '/components/com_jce/jce.xml');

        return $xml['version'];
    }

    public function getLicense() {
        return '<a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" title="GNU General Public License, version 2" target="_blank">GNU General Public License, version 2</a>';
    }

    public function getFeeds() {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_jce');
        $limit = $params->get('feed_limit', 2);

        $feeds = array();
        $options = array(
            'rssUrl' => 'http://www.joomlacontenteditor.net/news/feed/rss/latest-news?format=feed',
            'cache_time' => $params->get('feed_cachetime', 86400)
        );
        
        // prevent Strict Standards errors in simplepie
        error_reporting(32767 ^ 2048);

        // use this directly instead of JFactory::getXMLParser to avoid the feed data error
        jimport('simplepie.simplepie');

        if (!is_writable(JPATH_BASE . '/cache')) {
            $options['cache_time'] = 0;
        }
        $rss = new SimplePie($options['rssUrl'], JPATH_BASE . '/cache', isset($options['cache_time']) ? $options['cache_time'] : 0);
        $rss->force_feed(true);
        $rss->handle_content_type();

        if ($rss->init()) {
            $count = $rss->get_item_quantity();

            if ($count) {
                $count = ($count > $limit) ? $limit : $count;
                for ($i = 0; $i < $count; $i++) {
                    $feed = new StdClass();
                    $item = $rss->get_item($i);

                    $feed->link = $item->get_link();
                    $feed->title = $item->get_title();
                    $feed->description = $item->get_description();

                    $feeds[] = $feed;
                }
            }
        }

        return $feeds;
    }

}
?>