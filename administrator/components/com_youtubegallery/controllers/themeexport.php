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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

 
 
/**
 * YoutubeGallery - themeexport Controller
 */

class YoutubeGalleryControllerThemeExport extends JControllerForm
{

	function display($cachable = false, $urlparams = array())
	{
		switch(JFactory::getApplication()->input->getVar( 'task'))
		{
			case 'cancel':
				$this->cancel();
				break;
			default:
				JFactory::getApplication()->input->setVar( 'view', 'themeexport');
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
