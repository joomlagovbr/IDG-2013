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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * VideoList Model
 */
class YoutubeGalleryModelVideoList extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return string  An SQL query
         */
        protected function getListQuery()
        {
				$where=array();
				
				$context= 'com_youtubegallery.videolist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
				$search			=strtolower(trim(preg_replace("/[^a-zA-Z0-9 ]/", "", $search)));
				
				$where[]='listid='.JFactory::getApplication()->input->getInt( 'listid');
                
				//$where[]='isvideo';
				
				if($search!='')
						$where[]='( instr(link,"'.$search.'") OR instr(title,"'.$search.'") OR instr(description,"'.$search.'") )';
				
				
                // Create a new query object.         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('*');
                // From the Youtube Gallery Videos table
                $query->from('#__youtubegallery_videos');
				
				if(count($where)>0)
						$query->where(implode(' AND ',$where));
				
				
                return $query;
        }
  
		public function getTable($type = 'VideoList', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
      
}
