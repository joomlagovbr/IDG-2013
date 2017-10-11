<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * YoutubeGallery Component Controller
 */

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
    class YoutubeGalleryController extends JControllerLegacy
    {
    }
}
else
{
    class YoutubeGalleryController extends JController
    {
    }
}

?>