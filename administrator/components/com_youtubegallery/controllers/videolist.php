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
 
// import Joomla controllerform library
jimport('joomla.application.component.controlleradmin');
 


/**
 * YoutubeGallery - VideoList Controller
 */

class YoutubeGalleryControllerVideoList extends JControllerAdmin
{
		function display()
		{
				switch(JRequest::getVar( 'task'))
				{
						case 'cancel':
								$this->cancel();
								break;
						default:
								JRequest::setVar( 'view', 'videoylist');
								parent::display();
								break;
				}
				
		}

        
		function cancel()
		{
				$this->setRedirect( 'index.php?option=com_youtubegallery');
		}
}

?>