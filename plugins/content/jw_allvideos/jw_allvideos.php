<?php
/**
 * @version    4.8.0
 * @package    AllVideos (plugin)
 * @author     JoomlaWorks - http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2017 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentJw_allvideos extends JPlugin
{
    // JoomlaWorks reference parameters
    public $plg_name              = "jw_allvideos";
    public $plg_copyrights_start  = "\n\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.8.0) starts here -->\n";
    public $plg_copyrights_end    = "\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.8.0) ends here -->\n\n";

    public function __construct(&$subject, $params)
    {
        parent::__construct($subject, $params);
    }

    // Joomla 1.5
    public function onPrepareContent(&$row, &$params, $page = 0)
    {
        $this->renderAllVideos($row, $params, $page = 0);
    }

    // Joomla 2.5+
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        $this->renderAllVideos($row, $params, $page = 0);
    }

    // The main function
    public function renderAllVideos(&$row, &$params, $page = 0)
    {
        // API
        if (version_compare(JVERSION, '2.5.0', 'ge')) {
            jimport('joomla.html.parameter');
        }
        jimport('joomla.filesystem.file');
        $app = JFactory::getApplication();
        $document  = JFactory::getDocument();

        // Assign paths
        $sitePath = JPATH_SITE;
        $siteUrl  = JURI::root(true);
        if (version_compare(JVERSION, '2.5.0', 'ge')) {
            $pluginLivePath = $siteUrl.'/plugins/content/'.$this->plg_name.'/'.$this->plg_name;
        } else {
            $pluginLivePath = $siteUrl.'/plugins/content/'.$this->plg_name;
        }

        // Check if plugin is enabled
        if (JPluginHelper::isEnabled('content', $this->plg_name)==false) {
            return;
        }

        // Load the plugin language file the proper way
        JPlugin::loadLanguage('plg_content_'.$this->plg_name, JPATH_ADMINISTRATOR);

        // Includes
        $tagReplace = array();
        require_once dirname(__FILE__).'/'.$this->plg_name.'/includes/helper.php';
        require dirname(__FILE__).'/'.$this->plg_name.'/includes/sources.php';

        // Simple performance check to determine whether plugin should process further
        $grabTags = strtolower(implode(array_keys($tagReplace), "|"));
        if (preg_match("~{(".$grabTags.")}~is", $row->text)==false) {
            return;
        }



        // ----------------------------------- Get plugin parameters -----------------------------------

        // Get plugin info
        $plugin = JPluginHelper::getPlugin('content', $this->plg_name);

        // Control external parameters and set variable for controlling plugin layout within modules
        if (!$params) {
            $params = class_exists('JParameter') ? new JParameter(null) : new JRegistry(null);
        }
        if (is_string($params)) {
            $params = class_exists('JParameter') ? new JParameter($params) : new JRegistry($params);
        }
        $parsedInModule = $params->get('parsedInModule');

        $pluginParams = class_exists('JParameter') ? new JParameter($plugin->params) : new JRegistry($plugin->params);

        /* Video Parameters */
        $playerTemplate                 = ($params->get('playerTemplate')) ? $params->get('playerTemplate') : $pluginParams->get('playerTemplate', 'Responsive');
        $vfolder                        = ($params->get('vfolder')) ? $params->get('vfolder') : $pluginParams->get('vfolder', 'images/stories/videos');
        $vwidth                         = ($params->get('vwidth')) ? $params->get('vwidth') : $pluginParams->get('vwidth', 400);
        $vheight                        = ($params->get('vheight')) ? $params->get('vheight') : $pluginParams->get('vheight', 300);
        $transparency                   = $pluginParams->get('transparency', 'transparent');
        $background                     = $pluginParams->get('background', '#010101');
        $backgroundQT                   = $pluginParams->get('backgroundQT', 'black');
        $jwPlayerControls               = $pluginParams->get('controls', 1);
        /* Audio Parameters */
        $afolder                        = $pluginParams->get('afolder', 'images/stories/audio');
        $awidth                         = ($params->get('awidth')) ? $params->get('awidth') : $pluginParams->get('awidth', 480);
        $aheight                        = ($params->get('aheight')) ? $params->get('aheight') : $pluginParams->get('aheight', 24);
        $allowAudioDownloading          = $pluginParams->get('allowAudioDownloading', 0);
        /* Global Parameters */
        $autoplay                       = ($params->get('autoplay')) ? $params->get('autoplay') : $pluginParams->get('autoplay', 0);
        $loop                           = ($params->get('loop')) ? $params->get('loop') : $pluginParams->get('loop', 0);
        $ytnocookie                     = ($params->get('ytnocookie')) ? $params->get('ytnocookie') : $pluginParams->get('ytnocookie', 0);
        /* Performance Parameters */
        $gzipScripts                    = $pluginParams->get('gzipScripts', 0);
        /* Advanced */
        $jwPlayerLoading                = $pluginParams->get('jwPlayerLoading', 'cdn'); // local | cdn
        $jwPlayerAPIKey                 = $pluginParams->get('jwPlayerAPIKey', 'ABCdeFG123456SeVenABCdeFG123456SeVen==');
        $jwPlayerCDNUrl                 = $pluginParams->get('jwPlayerCDNUrl', 'https://content.jwplatform.com/libraries/VudZEfME.js');

        // Variable cleanups for K2
        if (JRequest::getCmd('format')=='raw') {
            $this->plg_copyrights_start = '';
            $this->plg_copyrights_end = '';
        }

        // Assign the AllVideos helper class
        $AllVideosHelper = new AllVideosHelper;



        // ----------------------------------- Render the output -----------------------------------

        // Append head includes only when the document is in HTML mode
        if (JRequest::getCmd('format')=='html' || JRequest::getCmd('format')=='') {
            // CSS
            $avCSS = $AllVideosHelper->getTemplatePath($this->plg_name, 'css/template.css', $playerTemplate);
            $avCSS = $avCSS->http;
            $document->addStyleSheet($avCSS);

            // JS
            if (version_compare(JVERSION, '2.5.0', 'ge')) {
                JHtml::_('behavior.framework');
            } else {
                JHTML::_('behavior.mootools');
            }

            if ($gzipScripts) {
                $document->addScript($pluginLivePath.'/includes/js/jwp.js.php?v=4.8.0');
            } else {
                $document->addScript($pluginLivePath.'/includes/js/behaviour.js?v=4.8.0');
                $document->addScript($pluginLivePath.'/includes/js/wmvplayer/silverlight.js?v=4.8.0');
                $document->addScript($pluginLivePath.'/includes/js/wmvplayer/wmvplayer.js?v=4.8.0');
                $document->addScript($pluginLivePath.'/includes/js/quicktimeplayer/ac_quicktime.js?v=4.8.0');
            }

            // Clappr
            $document->addScript('https://cdn.jsdelivr.net/gh/clappr/clappr@latest/dist/clappr.min.js');

            // JW Player v7
            if ($jwPlayerLoading=='local') {
                $document->addScript($pluginLivePath.'/includes/js/jwplayer/jwplayer.js?v=4.8.0');
                if (!defined('ALLVIDEOS_JW_PLAYER_KEY')) {
                    define('ALLVIDEOS_JW_PLAYER_KEY', true);
                    $document->addScriptDeclaration('jwplayer.key="'.$jwPlayerAPIKey.'"; /* JW Player API Key */');
                }
            } else {
                $document->addScript($jwPlayerCDNUrl);
            }
        }

        // Loop throught the found tags
        $tagReplace = array_change_key_case($tagReplace, CASE_LOWER);
        foreach ($tagReplace as $plg_tag => $value) {
            $cloned_plg_tag = $plg_tag;
            $plg_tag = strtolower($plg_tag);

            // expression to search for
            $regex = "~{".$plg_tag."}.*?{/".$plg_tag."}~is";

            // replacements for content to avoid issues with RegEx
            $row->text = str_replace('~', '&#126;', $row->text);

            // process tags
            if (preg_match_all($regex, $row->text, $matches, PREG_PATTERN_ORDER)) {

                // start the replace loop
                foreach ($matches[0] as $key => $match) {
                    $tagcontent = preg_replace("/{.+?}/", "", $match);
                    $tagcontent = str_replace(array('"','\'','`'), array('&quot;','&apos;','&#x60;'), $tagcontent); // Address potential XSS attacks
                    $tagparams = explode('|', $tagcontent);
                    $tagsource = trim(strip_tags($tagparams[0]));

                    // Prepare the HTML
                    $output = new JObject;

                    // Width/height/source folder split per media type
                    if (in_array($plg_tag, array(
                        'mp3',
                        'mp3remote',
                        'aac',
                        'aacremote',
                        'm4a',
                        'm4aremote',
                        'oga',
                        'ogaremote',
                        'ogg',
                        'oggremote',
                        'wma',
                        'wmaremote',
                        'soundcloud'
                    ))) {
                        // Poster frame
                        $posterFramePath = $sitePath.'/'.$afolder;
                        if (JFile::exists($posterFramePath.'/'.$tagsource.'.jpg')) {
                            $output->posterFrame = $siteUrl.'/'.$afolder.'/'.$tagsource.'.jpg';
                        } elseif (JFile::exists($posterFramePath.'/'.$tagsource.'.png')) {
                            $output->posterFrame = $siteUrl.'/'.$afolder.'/'.$tagsource.'.png';
                        } elseif (JFile::exists($posterFramePath.'/'.$tagsource.'.gif')) {
                            $output->posterFrame = $siteUrl.'/'.$afolder.'/'.$tagsource.'.gif';
                        } else {
                            $output->posterFrame = '';
                        }

                        if ($output->posterFrame) {
                            $aheight = ($awidth * 9 / 16);
                        }

                        $final_awidth = (@$tagparams[1]) ? $tagparams[1] : $awidth;
                        $final_aheight = (@$tagparams[2]) ? $tagparams[2] : $aheight;

                        $output->playerWidth = $final_awidth;
                        $output->playerHeight = $final_aheight;
                        $output->folder = $afolder;

                        if ($plg_tag=='soundcloud') {
                            if (strpos($tagsource, '/sets/') !== false) {
                                $output->mediaTypeClass = ' avSoundCloudSet';
                            } else {
                                $output->mediaTypeClass = ' avSoundCloudSong';
                            }
                            $output->mediaType = '';
                        } else {
                            $output->mediaTypeClass = ' avAudio';
                            $output->mediaType = 'audio';
                        }

                        if (in_array($plg_tag, array('mp3','aac','m4a','oga','ogg','wma'))) {
                            $output->source = "$siteUrl/$afolder/$tagsource.$plg_tag";
                        } elseif (in_array($plg_tag, array('mp3remote','aacremote','m4aremote','ogaremote','oggremote','wmaremote'))) {
                            $output->source = $tagsource;
                        } else {
                            $output->source = '';
                        }
                    } else {
                        // Poster frame
                        $posterFramePath = $sitePath.'/'.$vfolder;
                        if (JFile::exists($posterFramePath.'/'.$tagsource.'.jpg')) {
                            $output->posterFrame = $siteUrl.'/'.$vfolder.'/'.$tagsource.'.jpg';
                        } elseif (JFile::exists($posterFramePath.'/'.$tagsource.'.png')) {
                            $output->posterFrame = $siteUrl.'/'.$vfolder.'/'.$tagsource.'.png';
                        } elseif (JFile::exists($posterFramePath.'/'.$tagsource.'.gif')) {
                            $output->posterFrame = $siteUrl.'/'.$vfolder.'/'.$tagsource.'.gif';
                        } else {
                            $output->posterFrame = '';
                        }

                        $final_vwidth = (@$tagparams[1]) ? $tagparams[1] : $vwidth;
                        $final_vheight = (@$tagparams[2]) ? $tagparams[2] : $vheight;

                        $output->playerWidth = $final_vwidth;
                        $output->playerHeight = $final_vheight;
                        $output->folder = $vfolder;
                        $output->mediaType = 'video';
                        $output->mediaTypeClass = ' avVideo';
                    }

                    // Autoplay
                    $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                    $final_autoplay = ($final_autoplay) ? 'true' : 'false';

                    // Loop
                    $final_loop = (@$tagparams[4]) ? $tagparams[4] : $loop;
                    $final_loop = ($final_loop) ? 'true' : 'false';

                    // Special treatment for specific video providers
                    if ($plg_tag=="collegehumor") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = urlencode($tagsource);
                        }
                    }

                    if ($plg_tag=="dailymotion") {
                        $tagsource = preg_replace("~(http|https):(.+?)dailymotion.com\/video\/~s", "", $tagsource);
                        $tagsourceDailymotion = explode('_', $tagsource);
                        $tagsource = $tagsourceDailymotion[0];
                        if ($final_autoplay=='true') {
                            if (strpos($tagsource, '?')!==false) {
                                $tagsource = $tagsource.'&amp;autoplay=1';
                            } else {
                                $tagsource = $tagsource.'?autoplay=1';
                            }
                        }
                    }

                    if ($plg_tag=="dotsub") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/view/', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="facebook") {
                        $tagsource = urlencode($tagsource);
                    }

                    if ($plg_tag=="flickr") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = urlencode($tagsource);
                        }
                    }

                    if ($plg_tag=="funnyordie") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/videos/', $tagsource);
                            $tagsource = explode('/', $tagsource[1]);
                            $tagsource = $tagsource[0];
                        }
                    }

                    if ($plg_tag=="gloria") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/video/', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="godtube") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('?v=', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="ku6") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('?vid=', $tagsource);
                            $tagsource = 'https://rbv01.ku6.com/'.$tagsource[1].'.mp4';
                        }
                    }

                    if ($plg_tag=="liveleak") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('?i=', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="metacafe") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = str_replace('/watch/', '/embed/', $tagsource);
                        } elseif (is_int($tagsource)) {
                            $tagsource = 'http://www.metacafe.com/embed/'.$tagsource.'/';
                        } else {
                            $tagsource = explode('?i=', $tagsource);
                            $tagsource = 'http://www.metacafe.com/embed/'.$tagsource[1].'/';
                        }
                    }

                    if ($plg_tag=="myspace") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/', $tagsource);
                            $tagsource = array_reverse($tagsource);
                            $tagsource = $tagsource[0];
                        }
                    }

                    if ($plg_tag=="rutube") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = urlencode($tagsource);
                        }
                    }
                    
                    if ($plg_tag=="sapo") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('.pt/', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="twitch") {
                        if (strpos($tagsource, '/videos/')!==false) {
                            // Video
                            $tagsource = explode('/videos/', $tagsource);
                            $tagsource = 'video='.$tagsource[1];
                        } else {
                            // Channel
                            $tagsource = explode('twitch.tv/', $tagsource);
                            $tagsource = 'channel='.$tagsource[1];
                        }
                    }

                    if ($plg_tag=="ustream") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/recorded/', $tagsource);
                            $tagsource = (int) $tagsource[1];
                        }
                    }

                    if ($plg_tag=="vbox7") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('play:', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="videa") {
                        $tagsource = explode('-', $tagsource);
                        $tagsource = array_reverse($tagsource);
                        $tagsource = $tagsource[0];
                    }

                    if ($plg_tag=="vimeo") {
                        $tagsource = preg_replace("~(http|https):(.+?)vimeo.com\/~s", "", $tagsource);
                        if (strpos($tagsource, '?')!==false) {
                            $tagsource = $tagsource.'&amp;portrait=0';
                        } else {
                            $tagsource = $tagsource.'?portrait=0';
                        }
                        if ($final_autoplay=='true') {
                            $tagsource = $tagsource.'&amp;autoplay=1';
                        }
                        if ($final_loop=='true') {
                            $tagsource = $tagsource.'&amp;loop=1';
                        }
                    }

                    if ($plg_tag=="vine") {
                        if (strpos($tagsource, 'http')===false) {
                            $tagsource = 'https://vine.co/v/'.$tagsource;
                        }
                    }

                    if ($plg_tag=="youku") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('v_show/id_', $tagsource);
                            $tagsource = explode('.html', $tagsource[1]);
                            $tagsource = $tagsource[0];
                        }
                    }

                    if ($plg_tag=="youmaker") {
                        if (strpos($tagsource, 'http')!==false) {
                            $tagsource = explode('/video/', $tagsource);
                            $tagsource = $tagsource[1];
                        }
                    }

                    if ($plg_tag=="youtube") {

                        // Check the presence of fully pasted URLs
                        if (strpos($tagsource, 'youtube.com')!==false) {
                            $ytQuery = parse_url($tagsource, PHP_URL_QUERY);
                            $ytQuery = str_replace('&amp;', '&', $ytQuery);
                        } elseif (strpos($tagsource, 'youtu.be')!==false) {
                            $ytQuery = explode('youtu.be/', $tagsource);
                            $tagsource = $ytQuery[1];
                        } else {
                            $ytQuery = $tagsource;
                        }

                        // Process string
                        if (strpos($ytQuery, '&')!==false) {
                            $ytQuery = explode('&', $ytQuery);
                            $ytParams = array();
                            foreach ($ytQuery as $ytParam) {
                                $ytParam = explode('=', $ytParam);
                                $ytParams[$ytParam[0]] = $ytParam[1];
                            }
                            if (array_key_exists('v', $ytParams)) {
                                $tagsource = $ytParams['v'];
                            } elseif (array_key_exists('list', $ytParams)) {
                                $tagsource = 'videoseries?list='.$ytParams['list'];
                            }
                        } elseif (strpos($ytQuery, '=')!==false) {
                            $ytQuery = explode('=', $ytQuery);
                            $ytParams = array();
                            $ytParams[$ytQuery[0]] = $ytQuery[1];
                            if (array_key_exists('v', $ytParams)) {
                                $tagsource = $ytParams['v'];
                            } elseif (array_key_exists('list', $ytParams)) {
                                $tagsource = 'videoseries?list='.$ytParams['list'];
                            }
                        } else {
                            if (substr($tagsource, 0, 2)=="PL") {
                                $tagsource = 'videoseries?list='.$tagsource;
                            }
                        }

                        if (strpos($tagsource, '?')!==false) {
                            $tagsource = $tagsource.'&amp;rel=0&amp;fs=1&amp;wmode=transparent';
                        } else {
                            $tagsource = $tagsource.'?rel=0&amp;fs=1&amp;wmode=transparent';
                        }

                        // Additional playback parameters
                        if ($final_autoplay=='true') {
                            $tagsource = $tagsource.'&amp;autoplay=1';
                        }
                        if ($final_loop=='true') {
                            $tagsource = $tagsource.'&amp;loop=1';
                        }
                    }

                    // Poster frame (remote)
                    $output->posterFrameRemote = substr($tagsource, 0, -3).'jpg';

                    // Set a unique ID
                    $output->playerID = 'AVPlayerID_'.$key.'_'.md5($tagsource);

                    // Placeholder elements
                    $findAVparams = array(
                        "{SOURCE}",
                        "{SOURCEID}",
                        "{FOLDER}",
                        "{WIDTH}",
                        "{HEIGHT}",
                        "{PLAYER_AUTOPLAY}",
                        "{PLAYER_LOOP}",
                        "{PLAYER_TRANSPARENCY}",
                        "{PLAYER_BACKGROUND}",
                        "{PLAYER_BACKGROUNDQT}",
                        "{JWPLAYER_CONTROLS}",
                        "{SITEURL}",
                        "{SITEURL_ABS}",
                        "{FILE_EXT}",
                        "{FILE_TYPE}",
                        "{PLUGIN_PATH}",
                        "{PLAYER_POSTER_FRAME}",
                        "{PLAYER_POSTER_FRAME_REMOTE}"
                    );

                    // Replacement elements
                    $replaceAVparams = array(
                        $tagsource,
                        $output->playerID,
                        $output->folder,
                        $output->playerWidth,
                        $output->playerHeight,
                        $final_autoplay,
                        $final_loop,
                        $transparency,
                        $background,
                        $backgroundQT,
                        $jwPlayerControls,
                        $siteUrl,
                        substr(JURI::root(false), 0, -1),
                        $plg_tag,
                        str_replace("remote", "", $plg_tag),
                        $pluginLivePath,
                        $output->posterFrame,
                        $output->posterFrameRemote
                    );

                    // Do the element replace
                    $output->player = str_replace($findAVparams, $replaceAVparams, $tagReplace[$cloned_plg_tag]);

                    // Post processing
                    // For YouTube
                    if ($ytnocookie) {
                        $output->player = str_replace('www.youtube.com/embed', 'www.youtube-nocookie.com/embed', $output->player);
                    }

                    // Fetch the template
                    ob_start();
                    $getTemplatePath = $AllVideosHelper->getTemplatePath($this->plg_name, 'default.php', $playerTemplate);
                    $getTemplatePath = $getTemplatePath->file;
                    include($getTemplatePath);
                    $getTemplate = $this->plg_copyrights_start.ob_get_contents().$this->plg_copyrights_end;
                    ob_end_clean();

                    // Output
                    $row->text = preg_replace("~{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}~is", $getTemplate, $row->text);
                } // End second foreach
            } // End if
        } // End first foreach
    } // End function
} // End class
