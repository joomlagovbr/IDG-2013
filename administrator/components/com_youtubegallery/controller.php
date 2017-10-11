<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.0
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of Youtube Gallery component
 */
jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
    
    class YoutubeGalleryController extends JControllerLegacy
    {
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = null) 
        {
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd('view', 'linkslist'));
                
                // call parent behavior
                parent::display($cachable);
        }
    }
}
else
{
    class YoutubeGalleryController extends JController
    {
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = null) 
        {
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd('view', 'linkslist'));
                
                // call parent behavior
                parent::display($cachable);
        }
    }
}
