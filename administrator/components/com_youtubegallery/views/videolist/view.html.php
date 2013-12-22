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
 
// import Joomla view library
jimport('joomla.application.component.view');

 
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
				//if(JRequest::getVar('task')=='cancel')
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
                        JError::raiseError(500, implode('<br />', $errors));
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
                JRequest::setVar('hidemainmenu', true);
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_VIDEO_LIST'));

				JToolBarHelper::cancel('videolist.cancel', 'JTOOLBAR_CLOSE');
        }

}
