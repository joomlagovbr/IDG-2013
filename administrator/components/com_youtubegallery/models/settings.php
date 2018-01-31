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

 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

/**
 * YoutubeGallery - Settings Model
 */
class YoutubeGalleryModelSettings extends JModelAdmin
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
		
		
        public function getTable($type = 'Settings', $prefix = 'YoutubeGalleryTable', $config = array()) 
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
				
                $form = $this->loadForm('com_youtubegallery.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData)); //$loadData
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
		
	/*
        public function getScript() 
        {
                return 'administrator/components/com_youtubegallery/models/forms/linksform.js';
        }
        */
		
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         
         */
	/*
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
	*/

	
        

        function store()
        {
		
		$vimeo_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('vimeo_api_client_id')));
        $vimeo_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('vimeo_api_client_secret')));
		$youtube_public_api=trim(str_ireplace(array('select','=','update','insert'), '', JRequest::getVar('youtube_public_api')));
	    
	    
		$db = JFactory::getDBO();

		//Load Theme Row
		$query=array();
		
		$query[] = 'INSERT INTO `#__youtubegallery_settings` (`option`, `value`)
		VALUES ("vimeo_api_client_id", "'.$vimeo_api_client_id.'")
		ON DUPLICATE KEY UPDATE `option`="vimeo_api_client_id", `value`="'.$vimeo_api_client_id.'"';
		
		$query[] = 'INSERT INTO `#__youtubegallery_settings` (`option`, `value`)
		VALUES ("vimeo_api_client_secret", "'.$vimeo_api_client_secret.'")
		ON DUPLICATE KEY UPDATE `option`="vimeo_api_client_secret", `value`="'.$vimeo_api_client_secret.'"';

        $query[] = 'INSERT INTO `#__youtubegallery_settings` (`option`, `value`)
        VALUES ("youtube_public_api", "'.$youtube_public_api.'")
        ON DUPLICATE KEY UPDATE `option`="youtube_public_api", `value`="'.$youtube_public_api.'"';		

		foreach($query as $q)
		{
			//echo 'q='.$q.'<br/>';
			$db->setQuery($q);
			if (!$db->query())    die ( $db->stderr());
		}
		
		return true;
	
        }
        
    		
		
}
