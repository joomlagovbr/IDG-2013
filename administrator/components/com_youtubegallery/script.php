<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
  
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

class com_YoutubeGalleryInstallerScript
{
    function postflight($route, $adapter)
    {
        require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'fixdatabase.php');
        YouTubeGalleryFixDB::FixDB();
    }
}
?>