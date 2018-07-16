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
 * YoutubeGallery LinksList View
 */
class YoutubeGalleryViewLinksList extends JViewLegacy
{
        /**
         * YoutubeGallery view display method
         * @return void
         */
        function display($tpl = null) 
        {
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

                // Set the toolbar
                $this->addToolBar();
                
                $context= 'com_youtubegallery.linkslist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
                $search			= JString::strtolower( $search );
                
                $lists['search']=$search;
                
                
                $filter_category= $mainframe->getUserStateFromRequest($context."filter_category",'filter_category','',	'integer' );
                
                
                $available_categories=$this->getAllCategories();
                $javascript = 'onchange="document.adminForm.submit();"';
                $lists['categories']=JHTML::_('select.genericlist', $available_categories, 'filter_category', $javascript ,'id','categoryname', $filter_category);
                
                $this->assignRef('lists', $lists);
                
                
                // Display the template
                parent::display($tpl);
        }
        
        /**
         * Setting the toolbar
        */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_LINKSLIST'));
                
                
                JToolBarHelper::addNew('linksform.add');
                JToolBarHelper::editList('linksform.edit');
                JToolBarHelper::custom( 'linkslist.copyItem', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::custom( 'linkslist.updateItem', 'refresh.png', 'refresh_f2.png', 'Update', true);
		JToolBarHelper::custom( 'linkslist.refreshItem', 'purge.png', 'purge_f2.png', 'Refresh', true);
                JToolBarHelper::deleteList('', 'linkslist.delete');
                
        }
        
        
       	function getAllCategories()
        {
        	$db = JFactory::getDBO();
		
        	$query = "SELECT id, categoryname FROM #__youtubegallery_categories ORDER BY categoryname";
        	$db->setQuery( $query );
        	$available_categories = $db->loadObjectList();
        	$this->array_insert($available_categories ,array("id" => 0, "categoryname" => JText::_( 'COM_YOUTUBEGALLERY_SELECT_CATEGORY' )),0);
        	return $available_categories;
        }
        
        function array_insert(&$array, $insert, $position = -1)
        {
                $position = ($position == -1) ? (count($array)) : $position ;
                if($position != (count($array))) {
                $ta = $array;
                for($i = $position; $i < (count($array)); $i++)
                {
                        if(!isset($array[$i])) {
                                 die("\r\nInvalid array: All keys must be numerical and in sequence.");
                        }
                        $tmp[$i+1] = $array[$i];
                        unset($ta[$i]);
                }       
                $ta[$position] = $insert;
                $array = $ta + $tmp;

                } else {
                     $array[$position] = $insert;
                }
                ksort($array);
                return true;
        }   
        
}//class

}else
{
	//for joomla 2.5
	/**
 * YoutubeGallery LinksList View
 */
class YoutubeGalleryViewLinksList extends JView
{
        /**
         * YoutubeGallery view display method
         * @return void
         */
        function display($tpl = null) 
        {
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

                // Set the toolbar
                $this->addToolBar();
                
                $context= 'com_youtubegallery.linkslist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
                $search			= JString::strtolower( $search );
                
                $lists['search']=$search;
                
                
                $filter_category= $mainframe->getUserStateFromRequest($context."filter_category",'filter_category','',	'integer' );
                
                
                $available_categories=$this->getAllCategories();
                $javascript = 'onchange="document.adminForm.submit();"';
                $lists['categories']=JHTML::_('select.genericlist', $available_categories, 'filter_category', $javascript ,'id','categoryname', $filter_category);
                
                $this->assignRef('lists', $lists);
                
                
                // Display the template
                parent::display($tpl);
        }
        
        /**
         * Setting the toolbar
        */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_LINKSLIST'));
                
                
                JToolBarHelper::addNewX('linksform.add');
                JToolBarHelper::editListX('linksform.edit');
                JToolBarHelper::customX( 'linkslist.copyItem', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::custom( 'linkslist.updateItem', 'refresh.png', 'refresh_f2.png', 'Update', true);
		JToolBarHelper::custom( 'linkslist.refreshItem', 'purge.png', 'purge_f2.png', 'Refresh', true);
                JToolBarHelper::deleteListX('', 'linkslist.delete');
                
        }
        
        
       	function getAllCategories()
        {
        	$db = JFactory::getDBO();
		
        	$query = "SELECT id, categoryname FROM #__youtubegallery_categories ORDER BY categoryname";
        	$db->setQuery( $query );
        	$available_categories = $db->loadObjectList();
        	$this->array_insert($available_categories ,array("id" => 0, "categoryname" => JText::_( 'COM_YOUTUBEGALLERY_SELECT_CATEGORY' )),0);
        	return $available_categories;
        }
        
        function array_insert(&$array, $insert, $position = -1)
        {
                $position = ($position == -1) ? (count($array)) : $position ;
                if($position != (count($array))) {
						$ta = $array;
						for($i = $position; $i < (count($array)); $i++)
						{
						        if(!isset($array[$i])) {
						                 die("\r\nInvalid array: All keys must be numerical and in sequence.");
						        }
						        $tmp[$i+1] = $array[$i];
						        unset($ta[$i]);
						}       
						$ta[$position] = $insert;
						$array = $ta + $tmp;

                } else {
                     $array[$position] = $insert;
                }
                ksort($array);
                return true;
        }   
        
}//class

	
	
}//if