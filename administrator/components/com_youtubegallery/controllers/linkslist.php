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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Youtube Gallery - LinksList Controller
 */

class YoutubeGalleryControllerLinksList extends JControllerAdmin
{
		/**
		* Proxy for getModel.
		*/
		
		function display($cachable = false, $urlparams = array())
		{
				$task=JRequest::getVar( 'task');
				//echo '$task='.$task.'<br/>';
				//die;
				switch($task)
				{
						case 'delete':
								$this->delete();
								break;
						case 'linkslist.delete':
								$this->delete();
								break;
						case 'remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'linkslist.remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'copyItem':
								$this->copyItem();
								break;
						case 'linkslist.copyItem':
								$this->copyItem();
								break;
						case 'refreshItem':
								$this->refreshItem();
								break;
						case 'linkslist.refreshItem':
								$this->refreshItem();
								break;
						default:
								JRequest::setVar( 'view', 'linkslist');
								parent::display();
								break;
				}
		
				
		}
		
		public function getModel($name = 'LinksList', $prefix = 'YoutubeGalleryModel', $config = array()) 
		{
		        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
		        return $model;
		}
 
		public function refreshItem()
		{
				
				$model = $this->getModel('linksform');
        	
				
				$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	    
				if (count($cid)<1) {
		
				       $this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', JText::_('COM_YOUTUBEGALLERY_NO_ITEMS_SELECTED'),'error' );
                
						return false;
				}
					    	    
				if($model->RefreshPlayist($cid))
				{
						$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_REFRESHED_SUCCESSFULLY' );
						$link 	= 'index.php?option=com_youtubegallery&view=linkslist';
						$this->setRedirect($link, $msg);
				}
				else
				{
						$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_WAS_UNABLE_TO_REFRESHED' );
						$link 	= 'index.php?option=com_youtubegallery&view=linkslist';
						$this->setRedirect($link, $msg,'error');
				}

		}
 
        
		public function delete()
		{
                
				// Check for request forgeries
				JRequest::checkToken() or jexit( 'Invalid Token' );
        	
				$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

				if (count($cid)<1)
				{

						$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', JText::_('COM_YOUTUBEGALLERY_NO_VIDEOLISTS_SELECTED'),'error' );
						return false;
				}
		
				$model = $this->getModel();
        	
				$model->ConfirmRemove();
		}
	
		public function remove_confirmed()
		{
		
				// Get some variables from the request
				$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

				if (count($cid)<1)
				{
					$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', JText::_('COM_YOUTUBEGALLERY_NO_VIDEOLISTS_SELECTED'),'error' );
					return false;
				}

				$model = $this->getModel('linksform');
				if ($n = $model->deleteVideoList($cid))
				{
					$msg = JText::sprintf( 'COM_YOUTUBEGALLERY_VIDEOLIST_S_DELETED', $n );
					$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', $msg );
				}else
				{
					$msg = $model->getError();
				$this->setRedirect( 'index.php?option=com_youtubegallery&view=linkslist', $msg,'error' );
				}
		}
		
		public function copyItem()
		{
				
				$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	    
				$model = $this->getModel('linkslist');
	    
	    
				if($model->copyItem($cid))
				{
						$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_COPIED_SUCCESSFULLY' );
						$link 	= 'index.php?option=com_youtubegallery&view=linkslist';
						$this->setRedirect($link, $msg);
				}
				else
				{
						$msg = JText::_( 'COM_YOUTUBEGALLERY_VIDEOLIST_WAS_UNABLE_TO_COPY' );
						$link 	= 'index.php?option=com_youtubegallery&view=linkslist';
						$this->setRedirect($link, $msg,'error');
				}
	    	}

}

?>