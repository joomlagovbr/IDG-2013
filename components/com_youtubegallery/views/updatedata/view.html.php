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
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Display the view
                parent::display($tpl);
        }
    }
}
else
{
    class YoutubeGalleryViewUpdateData extends JView
    {
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Display the view
                parent::display($tpl);
        }
    }
}





?>