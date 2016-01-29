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
 * YoutubeGallery - LinksForm Controller
 */
class YoutubeGalleryControllerLinksForm extends JControllerForm
{
       /**
         * Proxy for getModel.
       */
       
       	function display($cachable = false, $urlparams = Array())
	{
		$task=$_POST['task'];
		
	
		if($task=='linksform.add' or $task=='add' )
		{
			$this->setRedirect( 'index.php?option=com_youtubegallery&view=linksform&layout=edit');
			return true;
		}
		
		if($task=='linksform.edit' or $task=='edit' )
		{
			$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

			if (!count($cid))
			{
				$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', JText::_('COM_YOUTUBEGALLERY_NO_VIDEOLISTS_SELECTED'),'error' );
				return false;
			}
			
			$this->setRedirect( 'index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$cid[0]);
			return true;
		}
	
		JRequest::setVar( 'view', 'linksform');
		JRequest::setVar( 'layout', 'edit');
		
		switch(JRequest::getVar( 'task'))
		{
		case 'apply':
			$this->save();
			break;
		case 'linksform.apply':
			$this->save();
			break;
		case 'save':
			$this->save();
			break;
		case 'linksform.save':
			$this->save();
			break;
		case 'cancel':
			$this->cancel();
			break;
		case 'linksform.cancel':
			$this->cancel();
			break;
		default:
			parent::display();
			break;
		}
		
	}

       
	function save($key = NULL, $urlVar = NULL)
	{
		$task = JRequest::getVar( 'task');
		
		// get our model
		$model = $this->getModel('linksform');
		// attempt to store, update user accordingly
		
		if($task != 'save' and $task != 'apply' and $task != 'linksform.save' and $task != 'linksform.apply' )
		{
			$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
		
		
		if ($model->store())
		{
		
			if($task == 'save' or $task == 'linksform.save')
				$link 	= 'index.php?option=com_youtubegallery&view=linkslist';
			elseif($task == 'apply' or $task == 'linksform.apply')
			{
	
				
				$link 	= 'index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$model->id;
			}
			
			$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_SAVED_SUCCESSFULLY' );
			
			$this->setRedirect($link, $msg);
		}
		else
		{
			  //die;
			$link 	= 'index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$model->id;
			$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}
	
	/**
	* Cancels an edit operation
	*/
	/*function cancelItem()
	{
		

		$model = $this->getModel('item');
		$model->checkin();

		
		
	}*/

	/**
	* Cancels an edit operation
	*/
	function cancel($key = NULL)
	{
		$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist');
	}

	/**
	* Form for copying item(s) to a specific option
	*/
}
