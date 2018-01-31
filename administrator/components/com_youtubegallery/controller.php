<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
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
