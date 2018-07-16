<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
    
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
                $jinput=JFactory::getApplication()->input;
                $view=$jinput->getCmd('view', 'linkslist');
                
                $jinput->set('view', $view);
                
                // call parent behavior
                parent::display($cachable);
        }
    }
