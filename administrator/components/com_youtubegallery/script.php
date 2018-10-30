<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
  
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

class com_YoutubeGalleryInstallerScript
{
    function postflight($route, $adapter)
    {
        require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'fixdatabase.php');
        YouTubeGalleryFixDB::FixDB();
    }
}
?>