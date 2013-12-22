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
 * YoutubeGallery - themeform Controller
 */

class YoutubeGalleryControllerThemeForm extends JControllerForm
{
       /**
         * Proxy for getModel.
         */
        function display()
	{
		$task=$_POST['task'];
	
		if($task=='themeform.add' or $task=='add' )
		{
			$this->setRedirect( 'index.php?option=com_youtubegallery&view=themeform&layout=edit');
			return true;
		}
		
		if($task=='themeform.edit' or $task=='edit')
		{
			$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

			if (!count($cid))
			{
				$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', JText::_('COM_YOUTUBEGALLERY_NO_THEME_SELECTED'),'error' );
				return false;
			}
			
			$this->setRedirect( 'index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$cid[0]);
			return true;
		}
		
		
		JRequest::setVar('hidemainmenu', true);
		JRequest::setVar('view', 'themeform');
		JRequest::setVar('layout', 'edit');
		
		switch($task)
		{
		case 'apply':
			$this->save();
			break;
		case 'themeform.apply':
			$this->save();
			break;
		case 'save':
			$this->save();
			break;
		case 'themeform.save':
			$this->save();
			break;
		case 'cancel':
			$this->cancel();
			break;
		case 'themeform.cancel':
			$this->cancel();
			break;
		}
		
		parent::display();
	}
    
	function save()
	{
		$task = JRequest::getVar( 'task');
		
		// get our model
		$model = $this->getModel('themeform');
		// attempt to store, update user accordingly
		
		if($task != 'save' and $task != 'apply' and $task != 'themeform.save' and $task != 'themeform.apply')
		{
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
		
		
		if ($model->store())
		{
		
			if($task == 'save' or $task == 'themeform.save' )
			{
				$link 	= 'index.php?option=com_youtubegallery&view=themelist';

			  }
			elseif($task == 'apply' or $task == 'themeform.apply')
			{
	
				
				$link 	= 'index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$model->id;
			}
			
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_SAVED_SUCCESSFULLY' );
			
			$this->setRedirect($link, $msg);
		}
		else
		{

			$link 	= 'index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$model->id;
			$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}
	
	/**
	* Cancels an edit operation
	*/
	function cancelItem()
	{
		

		$model = $this->getModel('item');
		$model->checkin();

		
		
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist');
	}

	/**
	* Form for copying item(s) to a specific option
	*/
}
