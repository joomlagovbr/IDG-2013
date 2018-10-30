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
 * YoutubeGallery - LinksForm Controller
 */
class YoutubeGalleryControllerSettings extends JControllerForm
{
    function display($cachable = false, $urlparams = array())
	{
		$jinput = JFactory::getApplication()->input;
		$task=$jinput->post->get('task','');
		
	
		if($task=='linksform.add' or $task=='add' or $task=='linksform.edit' or $task=='edit' )
		{
			$this->setRedirect( 'index.php?option=com_youtubegallery&view=settings&layout=edit');
			return true;
		}
	
		JFactory::getApplication()->input->setVar( 'view', 'settings');
		JFactory::getApplication()->input->setVar( 'layout', 'edit');
		
		switch($task)
		{
		case 'apply':
			$this->save();
			break;
		case 'settings.apply':
			$this->save();
			break;
		case 'save':
			$this->save();
			break;
		case 'settings.save':
			$this->save();
			break;
		case 'cancel':
			$this->cancel();
			break;
		case 'settings.cancel':
			$this->cancel();
			break;
		default:
			parent::display();
			break;
		}
		
	}

       
	function save($key = NULL, $urlVar = NULL)
	{

		$task = JFactory::getApplication()->input->getVar( 'task');
		
		// get our model
		$model = $this->getModel('settings');
		// attempt to store, update user accordingly
		
		if($task != 'save' and $task != 'apply' and $task != 'settings.save' and $task != 'settings.apply' )
		{
			$msg = JText::_( 'COM_YOUTUBEGALLERY_SETTINGS_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
		
		
		if ($model->store())
		{
		
			if($task == 'save' or $task == 'settings.save')
				$link 	= 'index.php?option=com_youtubegallery&view=settings&layout=edit';
			elseif($task == 'apply' or $task == 'settings.apply')
			{
	
				
				$link 	= 'index.php?option=com_youtubegallery&view=settings&layout=edit';
			}
			
			$msg = JText::_( 'COM_YOUTUBEGALLERY_SETTINGS_SAVED_SUCCESSFULLY' );
			
			$this->setRedirect($link, $msg);
		}
		else
		{
			  //die;
			$link 	= 'index.php?option=com_youtubegallery&view=settings&layout=edit';
			$msg = JText::_( 'COM_YOUTUBEGALLERY_SETTINGS_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}
	
	function cancel($key = NULL)
	{
		$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist');
	}

}
