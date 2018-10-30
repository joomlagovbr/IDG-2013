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
        
        class YoutubeGalleryViewYoutubeGallery extends JViewLegacy
        {
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                
                 // Assign data to the view
                $this->youtubegallerycode = $this->get('YoutubeGalleryCode');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                
 
                // Display the view
                parent::display($tpl);
        }
        }
}
else
{
        class YoutubeGalleryViewYoutubeGallery extends JView
        {
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                
                 // Assign data to the view
                $this->youtubegallerycode = $this->get('YoutubeGalleryCode');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                
 
                // Display the view
                parent::display($tpl);
        }
        }  
}


?>