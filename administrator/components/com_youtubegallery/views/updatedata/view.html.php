<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
        
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the YoutubeGallery Component
 */

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
 
    class YoutubeGalleryViewUpdateData extends JViewLegacy
    {
        function display($tpl = null) 
        {
                parent::display($tpl);
        }
    }

}else{
    
    class YoutubeGalleryViewUpdateData extends JView
    {
        function display($tpl = null) 
        {
                parent::display($tpl);
        }
    }  
    
}

?>