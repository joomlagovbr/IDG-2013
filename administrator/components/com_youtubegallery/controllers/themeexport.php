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
jimport('joomla.application.component.controllerform');

 
 
/**
 * YoutubeGallery - themeexport Controller
 */

class YoutubeGalleryControllerThemeExport extends JControllerForm
{

	function display()
	{
		switch(JRequest::getVar( 'task'))
		{
			case 'cancel':
				$this->cancel();
				break;
			default:
				JRequest::setVar( 'view', 'themeexport');
				parent::display();
				break;
			}
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist');
	}


}
