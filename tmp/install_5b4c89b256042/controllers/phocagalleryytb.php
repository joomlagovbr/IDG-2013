<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');
jimport('joomla.client.helper');
phocagalleryimport('phocagallery.youtube.youtube');

class PhocaGalleryCpControllerPhocaGalleryYtb extends JControllerForm
{
	//protected	$option 		= 'com_phocagallery';
	protected $context 	= 'com_phocagallery.phocagalleryytjjb';
	
	function __construct() {
		parent::__construct();
		$this->registerTask( 'import'  , 	'import' );	
	}

	function import() {

		JSession::checkToken() or die( 'Invalid Token' );
		$app = JFactory::getApplication();
		//$post	= JFactory::getApplication()->input->get('post');
		//$ytb_link	= JFactory::getApplication()->input->get( 'ytb_link', '', 'post', 'string', J REQUEST_NOTRIM);
		//$field		= JFactory::getApplication()->input->get( 'field', '', 'post', 'string', J REQUEST_NOTRIM);
		$ytb_link	= JFactory::getApplication()->input->get( 'ytb_link', '',  'string' );
		$field		= JFactory::getApplication()->input->get( 'field', '',  'string' );
		$catid		= JFactory::getApplication()->input->get( 'catid', 0,  'int' );
		
		
		$folder = '';
		if ((int)$catid > 0) {
			$db =JFactory::getDBO();
			$query = 'SELECT c.userfolder'
			.' FROM #__phocagallery_categories AS c'
			.' WHERE c.id = '.$db->Quote((int)$catid);

			$db->setQuery($query, 0, 1);
			$folderObj = $db->loadObject();
			
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			if (isset($folderObj->userfolder) && $folderObj->userfolder != '') {
				$folder = $folderObj->userfolder . '/';// Save to category folder
			} else {
				$folder = '';// No category folder - save to root
			}
		} else {
			$errorMsg .= JText::_('COM_PHOCAGALLERY_YTB_ERROR_NO_CATEGORY');
		}
		
		$ytb	= PhocaGalleryYoutube::importYtb($ytb_link, $folder, $errorYtbMsg);

/*		
		$ytb_code 	= str_replace("&feature=related","",PhocaGalleryYoutube::getCode(strip_tags($ytb_link)));

		$msg = $errorMsg = '';
		$ytb				= array();
		$ytb['title']		= '';
		$ytb['desc']		= '';
		$ytb['filename']	= '';
		$ytb['link']		= strip_tags($ytb_link);
			
		if(!function_exists("curl_init")){
			$errorMsg .= JText::_('COM_PHOCAGALLERY_YTB_NOT_LOADED_CURL');
		} else if ($ytb_code == '') {
			$errorMsg .= JText::_('COM_PHOCAGALLERY_YTB_URL_NOT_CORRECT');
		} else {
			
			$folder = '';
			if ((int)$catid > 0) {
				$db =JFactory::getDBO();
				$query = 'SELECT c.userfolder'
				.' FROM #__phocagallery_categories AS c'
				.' WHERE c.id = '.$db->Quote((int)$catid);

				$db->setQuery($query, 0, 1);
				$folderObj = $db->loadObject();
				
				if (!$db->query()) {
					$this->setError($db->getErrorMsg());
					return false;
				}
				
				if (isset($folderObj->userfolder) && $folderObj->userfolder != '') {
					$folder = $folderObj->userfolder . '/';// Save to category folder
				} else {
					$folder = '';// No category folder - save to root
				}
			} else {
				$errorMsg .= JText::_('COM_PHOCAGALLERY_YTB_ERROR_NO_CATEGORY');
			}
			
			// Data
			$cUrl		= curl_init("http://gdata.youtube.com/feeds/api/videos/".strip_tags($ytb_code));
            curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
            $xml		= curl_exec($cUrl);
            curl_close($cUrl);
			
			$xml 	= str_replace('<media:', '<phcmedia', $xml);
			$xml 	= str_replace('</media:', '</phcmedia', $xml);
			
			$data = simplexml_load_file($file);

			//Title			
			if (isset($data->title)) {
				$ytb['title'] = (string)$data->title;
			}
			
			if ($ytb['title'] == '' && isset($data->phcmediagroup->phcmediatitle)) {
				$ytb['title'] = (string)$data->phcmediagroup->phcmediatitle;
			}
			
			if (isset($data->phcmediagroup->phcmediadescription)) {
				$ytb['desc'] = (string)$data->phcmediagroup->phcmediadescription;
			}
			
			// Thumbnail
			if (isset($data->phcmediagroup->phcmediathumbnail[0]['url'])) {
				$cUrl		= curl_init(strip_tags((string)$data->phcmediagroup->phcmediathumbnail[0]['url']));
				curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
				$img		= curl_exec($cUrl);
				curl_close($cUrl);
			}
            	
			if ($img != '') {
				$cUrl		= curl_init("http://img.youtube.com/vi/".strip_tags($ytb_code)."/0.jpg");
				curl_setopt($cUrl,CURLOPT_RETURNTRANSFER,1);
				$img		= curl_exec($cUrl);
				curl_close($cUrl);
			}
	
			$ytb['filename']	= $folder.strip_tags($ytb_code).'.jpg';
			
            if (!JFile::write(JPATH_ROOT . '/' .'images' . '/' . 'phocagallery' . '/'. $ytb['filename'], $img)) {
				$errorMsg .= JText::_('COM_PHOCAGALLERY_YTB_ERROR_WRITE_IMAGE');
			}
		}*/
		
		JFactory::getApplication()->input->set('ytb_title', $ytb['title']);
		JFactory::getApplication()->input->set('ytb_desc', $ytb['desc']);
		JFactory::getApplication()->input->set('ytb_filename', $ytb['filename']);
		JFactory::getApplication()->input->set('ytb_link', $ytb['link']);
		
		if ($errorYtbMsg != '') {
			$msg 	= $errorYtbMsg;
			$import	= '';
			$redirect = 'index.php?option=com_phocagallery&view=phocagalleryytb&tmpl=component&field='.$field.'&catid='.(int)$catid.$import;
			$app->enqueueMessage($errorYtbMsg, 'error');
			$this->setRedirect( $redirect );
		} else {
			$msg 		= JText::_('COM_PHOCAGALLERY_YTB_SUCCESS_IMPORT');
			$import		= '&import=1';

			$app->getUserStateFromRequest( $this->context.'.ytb_title', 'ytb_title', $ytb['title'], 'string' );
			$app->getUserStateFromRequest( $this->context.'.ytb_desc', 'ytb_desc', $ytb['desc'], 'string' );
			$app->getUserStateFromRequest( $this->context.'.ytb_filename', 'ytb_filename', $ytb['filename'], 'string' );
			$app->getUserStateFromRequest( $this->context.'.ytb_link', 'ytb_link', $ytb['link'], 'string' );
			$redirect = 'index.php?option=com_phocagallery&view=phocagalleryytb&tmpl=component&field='.$field.'&catid='.(int)$catid.$import;
			$app->enqueueMessage($msg, 'message');
			$this->setRedirect( $redirect );
		}
		
	}	

	function cancel($key = NULL) {
		$this->setRedirect( 'index.php?option=com_phocagallery' );
	}
}
?>
