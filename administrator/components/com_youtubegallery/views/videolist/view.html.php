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
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
 
// import Joomla view library
jimport('joomla.application.component.view');


jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
        //joomla 3.x
 
/**
 * YoutubeGallery VideoList View
 */
class YoutubeGalleryViewVideoList extends JViewLegacy
{
        /**
         * YoutubeGallery view display method
         * @return void
         */
        function display($tpl = null) 
        {
				//if(JFactory::getApplication()->input->getVar('task')=='cancel')
				//{
						
						//$app= JFactory::getApplication();
						//$app->redirect('index.php?option=com_youtubegallery');
						//global $mainframe;
						//$mainframe->redirect('index.php?option=com_youtubegallery');
						
				//}


                // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;


				//$script = $this->get('Script');

                // Set the toolbar
                $this->addToolBar();
                
                $context= '';//com_youtubegallery.videoylist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
                $search			= JString::strtolower( $search );
                
                $lists['search']=$search;
				
				$this->assignRef('lists', $lists);
                
                // Display the template
                parent::display($tpl);
        }
                /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_VIDEO_LIST'));

				JToolBarHelper::cancel('videolist.cancel', 'JTOOLBAR_CLOSE');
        }

}//class

}else{
	
	
/** for joomla 2.5
 * YoutubeGallery VideoList View
 */
class YoutubeGalleryViewVideoList extends JView
{
        /**
	 *
         * YoutubeGallery view display method
         * @return void
         */
        function display($tpl = null) 
        {
				//if(JFactory::getApplication()->input->getVar('task')=='cancel')
				//{
						
						//$app= JFactory::getApplication();
						//$app->redirect('index.php?option=com_youtubegallery');
						//global $mainframe;
						//$mainframe->redirect('index.php?option=com_youtubegallery');
						
				//}


                // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;


				//$script = $this->get('Script');

                // Set the toolbar
                $this->addToolBar();
                
                $context= '';//com_youtubegallery.videoylist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
                $search			= JString::strtolower( $search );
                
                $lists['search']=$search;
				
				$this->assignRef('lists', $lists);
                
                // Display the template
                parent::display($tpl);
        }
                /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_VIDEO_LIST'));

				JToolBarHelper::cancel('videolist.cancel', 'JTOOLBAR_CLOSE');
        }

}//class

	
}