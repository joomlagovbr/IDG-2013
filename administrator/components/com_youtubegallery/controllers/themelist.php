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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Youtube Gallery - ThemeList Controller
 */
class YoutubeGalleryControllerThemeList extends JControllerAdmin
{
		/**
		 * Proxy for getModel.
		 */
		
		function display($cachable = false, $urlparams = array())
		{
				switch(JFactory::getApplication()->input->getVar( 'task'))
				{
						case 'delete':
								$this->delete();
								break;
						case 'remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'copyItem':
								$this->copyItem();
								break;
						case 'uploadItem':
								$this->uploadItem();
								break;
						case 'themelist.delete':
								$this->delete();
								break;
						case 'themelist.remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'themelist.copyItem':
								$this->copyItem();
								break;
						case 'themelist.uploadItem':
								$this->uploadItem();
								break;
						default:
								JFactory::getApplication()->input->setVar( 'view', 'themelist');
								parent::display();
								break;
				}
		
				
		}
	
		public function getModel($name = 'ThemeList', $prefix = 'YoutubeGalleryModel', $config = array()) 
		{
		        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
		        return $model;
		}
 
		public function delete()
		{
                
				// Check for request forgeries
				JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        	
				$cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
				
				if (count($cid)<1)
				{
						$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', JText::_('COM_YOUTUBEGALLERY_NO_THEME_SELECTED'),'error' );
						return false;
				}
		
				$model = $this->getModel();
        	
				$model->ConfirmRemove();
		}
	
        
		public function remove_confirmed()
		{
		
				// Get some variables from the request
				$cid	= JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );

				if (count($cid)<1)
				{
						$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', JText::_('COM_YOUTUBEGALLERY_NO_THEME_SELECTED'),'error' );
						return false;
				}

				$model = $this->getModel('themeform');
				if ($n = $model->deleteTheme($cid))
				{
						$msg = JText::sprintf( 'COM_YOUTUBEGALLERY_THEME_S_DELETED', $n );
						$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', $msg );
				}else
				{
						$msg = $model->getError();
						$this->setRedirect( 'index.php?option=com_youtubegallery&view=themelist', $msg,'error' );
				}
		
		}
		
		
		public function copyItem()
		{
				
				$cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
	    
				$model = $this->getModel('themelist');
	    
	    
				if($model->copyItem($cid,$msg))
				{
						$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_COPIED_SUCCESSFULLY' );
						$link 	= 'index.php?option=com_youtubegallery&view=themelist';
						$this->setRedirect($link, $msg);
				}
				else
				{
						if($msg=='')
								$msg = JText::_( 'COM_YOUTUBEGALLERY_THEME_WAS_UNABLE_TO_COPY' );
						
						$link 	= 'index.php?option=com_youtubegallery&view=themelist';
						$this->setRedirect($link, $msg,'error');
				}
		}
		
		
		public function uploadItem()
		{
				$link 	= 'index.php?option=com_youtubegallery&view=themeimport';
					
				$this->setRedirect($link, '');
		}



}

?>