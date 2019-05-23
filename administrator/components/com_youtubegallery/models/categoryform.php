<?php
/**
 * YoutubeGallery Joomla!  Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  
/**
 * YoutubeGallery - Category Model
 */
class YoutubeGalleryModelCategoryForm extends JModelAdmin
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
		
        public function getTable($type = 'Categories', $prefix = 'YoutubeGalleryTable', $config = array()) 
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
				//echo '7676f';
                // Get the form.
                $form = $this->loadForm('com_youtubegallery.categoryform', 'categoryform', array('control' => 'jform', 'load_data' => $loadData));
				//echo 'abc';
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
                return 'administrator/components/com_youtubegallery/models/forms/categoryform.js';
        }
		
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         
         */
        protected function loadFormData() 
        {
                // Check the session for previously entered form data.
                $data = JFactory::getApplication()->getUserState('com_youtubegallery.edit.categoryform.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }
        

        function store()
        {
                
                
        	$category_row = $this->getTable('categories');
            
		$jinput = JFactory::getApplication()->input;
                $data = $jinput->get( 'jform',array(),'ARRAY');
		
        	$post = array();
            
            $categoryname=trim(preg_replace("/[^a-zA-Z0-9_]/", "", $data['jform']['categoryname']));
            
            $data['jform']['categoryname']=$categoryname;
            
           

        	if (!$category_row->bind($data))
        	{
                
        		return false;
        	}
               
        	// Make sure the  record is valid
        	if (!$category_row->check())
        	{
                
        		return false;
        	}

        	// Store
        	if (!$category_row->store())
        	{
                
        		return false;
        	}
				
        	$this->id=$category_row->id;
			
				
				
        	return true;
        }
        
        function deleteCategory($cids)
        {

        	$category_row = $this->getTable('categories');

            $db = JFactory::getDBO();
            
        	if (count( $cids ))
        	{
        		foreach($cids as $cid)
        		{
						
				
				if (!$category_row->delete( $cid ))
				{
					return false;
				}
			}
        	}
		
		
		
        	return true;
        }
}
