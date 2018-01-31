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

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');


/**
 * YoutubeGallery - LinksForm Model
 */
class YoutubeGalleryModelLinksForm extends JModelAdmin
{
        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         
         */
		public $id;
		
		
        public function getTable($type = 'VideoLists', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
        /**
         * Method to get the record form.
         *
         * @param       array   $data           Data for the form.
         * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
         * @return      mixed   A JForm object on success, false on failure
         
         */
        public function getForm($data = array(), $loadData = true) 
        {
                // Get the form.
				
                $form = $this->loadForm('com_youtubegallery.linksform', 'linksform', array('control' => 'jform', 'load_data' => $loadData)); //$loadData
                if (empty($form)) 
                {
                        return false;
                }
				
                return $form;
        }
		
		/**
         * Method to get the script that have to be included on the form
         *
         * @return string       Script files
         */
        public function getScript() 
        {
                return 'administrator/components/com_youtubegallery/models/forms/linksform.js';
        }
		
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         
         */
        protected function loadFormData() 
        {
                // Check the session for previously entered form data.
                $data = JFactory::getApplication()->getUserState('com_youtubegallery.edit.linksform.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }
		

	function RefreshPlayist($cids)
	{
		$where=array();
				
		foreach($cids as $cid)
			$where[]= 'id='.$cid;
				
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
				
		// Create a new query object.         
                
		$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('*');
                // From the Youtube Gallery table
                $query->from('#__youtubegallery_videolists');
				
		if(count($where)>0)
			$query->where(implode(' OR ',$where));
								
		$db->setQuery($query);
		if (!$db->query())    die( $db->stderr());
                
		$linksform_rows=$db->loadObjectList();
		if(count($linksform_rows)<1)
			return false;
				
		$misc=new YouTubeGalleryMisc;
		
		
		foreach($linksform_rows as $linksform_row)
		{
			
			$misc->videolist_row = $linksform_row;
			$misc->update_cache_table($linksform_row); 
				
			$query='UPDATE #__youtubegallery_videolists SET `lastplaylistupdate`="'.date( 'Y-m-d H:i:s').'" WHERE `id`='.$linksform_row->id;
			$db->setQuery($query);
			if (!$db->query())    die( $db->stderr());
						
			//Clear Update Info for each video in this gallery
			$query='UPDATE #__youtubegallery_videos SET `lastupdate`="0000-00-00 00:00:00" WHERE `isvideo` AND `listid`='.$linksform_row->id;
			$db->setQuery($query);
			if (!$db->query())    die( $db->stderr());
	
		}
				
				return true;
	}
        

        function store()
        {
                
                
        	$linksform_row = $this->getTable('videolists');
            

            
        	// consume the post data with allow_html
        	$data_ = JRequest::get( 'post',JREQUEST_ALLOWRAW);
            $data=$data_['jform'];
            
        	$post = array();
            
            $listname=trim(preg_replace("/[^a-zA-Z0-9_]/", "", $data['listname']));
            
            $data['jform']['listname']=$listname;
            
           

        	if (!$linksform_row->bind($data))
        	{
                echo 'Cannot bind.';
        		return false;
        	}
               
        	// Make sure the  record is valid
        	if (!$linksform_row->check())
        	{
                echo 'Cannot check.';
        		return false;
        	}
				
				
				if($linksform_row->id!=0)
				{
						require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
						$misc=new YouTubeGalleryMisc;
						$misc->videolist_row = $linksform_row;
						$misc->update_cache_table($linksform_row); 
						$linksform_row->lastplaylistupdate =date( 'Y-m-d H:i:s');
				}
				
						
						
        	// Store
        	if (!$linksform_row->store())
        	{
				
                echo '<p>Cannot store.</p>
				<p>There is some fields missing.</p>
				';
        		return false;
        	}
				
        	$this->id=$linksform_row->id;
			
        	return true;
        }
        
    		
		function deleteVideoList($cids)
        {

        	$linksform_row = $this->getTable('videolists');

            $db = JFactory::getDBO();
            
        	if (count( $cids ))
        	{
        		foreach($cids as $cid)
        		{
						
				
				if (!$linksform_row->delete( $cid ))
				{
					return false;
				}
			}
        	}
		
		
		
        	return true;
        }
}
