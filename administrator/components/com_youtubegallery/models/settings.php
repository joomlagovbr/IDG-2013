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
	

	static protected function makeQueryLine($field,$value)
	{
		return 'INSERT INTO #__youtubegallery_settings (`option`, `value`)
		VALUES ("'.$field.'", "'.$value.'")
		ON DUPLICATE KEY UPDATE `option`="'.$field.'", `value`="'.$value.'"';
	}
        

        function store()
        {

			$jform=JFactory::getApplication()->input->getVar('jform');
			$allowsef=trim(preg_replace("/[^0-9]/", "", $jform['allowsef']));
			$getinfomethod='php';//trim(preg_replace("/[^a-zA-Z0-9_-]/", "", $jform['getinfomethod']));
			$vimeo_api_client_id=trim(preg_replace("/[^a-zA-Z0-9+\/_-]/", "", JFactory::getApplication()->input->getVar('vimeo_api_client_id')));
			$vimeo_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9+\/_-]/", "", JFactory::getApplication()->input->getVar('vimeo_api_client_secret')));
			$vimeo_api_access_token=trim(preg_replace("/[^a-zA-Z0-9+\/_-]/", "", JFactory::getApplication()->input->getVar('vimeo_api_access_token')));
			
			$soundcloud_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JFactory::getApplication()->input->getVar('soundcloud_api_client_id')));
			$soundcloud_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JFactory::getApplication()->input->getVar('soundcloud_api_client_secret')));
			
			//$youtube_api_client_id=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JFactory::getApplication()->input->getVar('youtube_api_client_id')));
			//$youtube_api_client_secret=trim(preg_replace("/[^a-zA-Z0-9_]/", "", JFactory::getApplication()->input->getVar('youtube_api_client_secret')));
			$youtube_api_key=trim(preg_replace("/[^a-zA-Z0-9_-]/", "", JFactory::getApplication()->input->getVar('youtube_api_key')));
			
			$errorreporting=trim(preg_replace("/[^0-9]/", "", $jform['errorreporting']));
		

		$db = JFactory::getDBO();
		$query=array();
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('allowsef',$allowsef);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('getinfomethod',$getinfomethod);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('vimeo_api_client_id',$vimeo_api_client_id);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('vimeo_api_client_secret',$vimeo_api_client_secret);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('vimeo_api_access_token',$vimeo_api_access_token);
		
		
		
		
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('soundcloud_api_client_id',$soundcloud_api_client_id);
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('soundcloud_api_client_secret',$soundcloud_api_client_secret);
		
		$query[] = YoutubeGalleryModelSettings::makeQueryLine('youtube_api_key',$youtube_api_key);

			foreach($query as $q)
		{
			$db->setQuery($q);
			if (!$db->query())    die ( $db->stderr());
		}
		return true;

        }

}
