<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
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

	static protected function makeQueryLine($field,$value)
	{
		return 'INSERT INTO `#__youtubegallery_settings` (`option`, `value`)
		VALUES ("'.$field.'", "'.$value.'")
		ON DUPLICATE KEY UPDATE `option`="'.$field.'", `value`="'.$value.'"';
	}
        

        function store()
        {
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;

		if($JoomlaVersionRelease>=3.0)
		{
			$jform=JRequest::getVar('jform');
			$allowsef=trim(preg_replace("/[^0-9]/", "", $jform['allowsef']));
			$getinfomethod=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", $jform['getinfomethod']));
			$vimeo_api_client_id=trim(preg_replace("/[^a-zA-Z0-9+\/_-]/", "", JRequest::getVar('vimeo_api_client_id')));
			$vimeo_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9+\/_-]/", "", JRequest::getVar('vimeo_api_client_secret')));
			$soundcloud_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('soundcloud_api_client_id')));
			$soundcloud_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('soundcloud_api_client_secret')));
			
			//$youtube_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('youtube_api_client_id')));
			//$youtube_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('youtube_api_client_secret')));
			$youtube_api_key=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('youtube_api_key')));
			
			$errorreporting=trim(preg_replace("/[^0-9]/", "", $jform['errorreporting']));
		}
		else
		{
			$allowsef=JRequest::getInt('allowsef');
			$getinfomethod=JRequest::getCmd('getinfomethod');
			$vimeo_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('vimeo_api_client_id')));
			$vimeo_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('vimeo_api_client_secret')));
			$soundcloud_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('soundcloud_api_client_id')));
			$soundcloud_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('soundcloud_api_client_secret')));
			
			//$youtube_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('youtube_api_client_id')));
			//$youtube_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JRequest::getVar('youtube_api_client_secret')));
			$youtube_api_key=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JRequest::getVar('youtube_api_key')));
		}

		$db = JFactory::getDBO();
		$query=array();
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('allowsef',$allowsef);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('getinfomethod',$getinfomethod);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('vimeo_api_client_id',$vimeo_api_client_id);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('vimeo_api_client_secret',$vimeo_api_client_secret);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('soundcloud_api_client_id',$soundcloud_api_client_id);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('soundcloud_api_client_secret',$soundcloud_api_client_secret);
		
		//$query[] = YoutubeGalleryModelSettings::makeQueryLine('youtube_api_client_id',$youtube_api_client_id);
		//$query[] = YoutubeGalleryModelSettings::makeQueryLine('youtube_api_client_secret',$youtube_api_client_secret);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('youtube_api_key',$youtube_api_key);

		if($JoomlaVersionRelease>=3.0)
			$query[] = YoutubeGalleryModelSettings::makeQueryLine('errorreporting',$errorreporting);

		foreach($query as $q)
		{
			$db->setQuery($q);
			if (!$db->query())    die ( $db->stderr());
		}
		return true;

        }

}
