<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * YoutubeGallery - Theme Form Model
 */
class YoutubeGalleryModelthemeForm extends JModelAdmin
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
		
		
        public function getTable($type = 'Themes', $prefix = 'YoutubeGalleryTable', $config = array()) 
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
                $form = $this->loadForm('com_youtubegallery.themeform', 'themeform', array('control' => 'jform', 'load_data' => true)); //$loadData
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
                return 'administrator/components/com_youtubegallery/models/forms/themeform.js';
        }
		
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         
         */
        protected function loadFormData() 
        {
                // Check the session for previously entered form data.
				//$data = (array)JFactory::getApplication()->getUserState('com_youtubegallery.edit.themeform.data', array());
                $data = JFactory::getApplication()->getUserState('com_youtubegallery.edit.themeform.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }
		


        function store()
        {
                
                
        	$themeform_row = $this->getTable('themes');
            
		$jinput = JFactory::getApplication()->input;
                $data = $jinput->get( 'jform',array(),'ARRAY');
            
        	$post = array();
            
            $themename=trim(preg_replace("/[^a-zA-Z0-9_]/", "", $data['themename']));
            
            $data['jform']['themename']=$themename;
            
           

        	if (!$themeform_row->bind($data))
        	{
                echo 'Cannot bind.';
        		return false;
        	}
               
        	// Make sure the  record is valid
        	if (!$themeform_row->check())
        	{
                echo 'Cannot check.';
        		return false;
        	}
				
						
        	// Store
        	if (!$themeform_row->store())
        	{
				
                echo '<p>Cannot store.</p>
				<p>There is some fields missing.</p>
				';
        		return false;
        	}
				
        	$this->id=$themeform_row->id;
			
        	return true;
        }
        
    		
		function deleteTheme($cids)
        {

        	$themeform_row = $this->getTable('themes');

            $db = JFactory::getDBO();
            
        	if (count( $cids ))
        	{
        		foreach($cids as $cid)
        		{
						
				
				if (!$themeform_row->delete( $cid ))
				{
					return false;
				}
			}
        	}
		
		
		
        	return true;
        }
}
