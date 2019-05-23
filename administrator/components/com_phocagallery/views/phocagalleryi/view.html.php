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
defined( '_JEXEC' ) or die();
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
phocagalleryimport( 'phocagallery.file.fileuploadmultiple' );
phocagalleryimport( 'phocagallery.file.fileuploadsingle' );
phocagalleryimport( 'phocagallery.file.fileuploadjava' );

class PhocaGalleryCpViewPhocagalleryI extends JViewLegacy
{
	protected $field;
	protected $fce;
	protected $folderstate;
	protected $images;
	protected $folders;
	protected $tmpl;
	protected $session;
	protected $currentFolder;
	
	public function display($tpl = null) {

		$this->field	= JFactory::getApplication()->input->get('field');
		$this->fce 		= 'phocaSelectFileName_'.$this->field;
		
		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		$document		= JFactory::getDocument();
		//$document->addCustomTag(PhocaGalleryRenderAdmin::renderIeCssLink(1));
		
		$this->folderstate	= $this->get('FolderState');
		$this->images		= $this->get('Images');
		$this->folders		= $this->get('Folders');
		$this->session		= JFactory::getSession();
		
		$params 									= JComponentHelper::getParams('com_phocagallery');
		$this->tmpl['enablethumbcreation']			= $params->get('enable_thumb_creation', 1 );
		$this->tmpl['enablethumbcreationstatus'] 	= PhocaGalleryRenderAdmin::renderThumbnailCreationStatus((int)$this->tmpl['enablethumbcreation']);		
		$this->tmpl['multipleuploadchunk']			= $params->get( 'multiple_upload_chunk', 0 );
		$this->tmpl['large_image_width']	= $params->get( 'large_image_width', 640 );
		$this->tmpl['large_image_height']	= $params->get( 'large_image_height', 480 );
		$this->tmpl['javaboxwidth'] 		= $params->get( 'java_box_width', 480 );
		$this->tmpl['javaboxheight'] 		= $params->get( 'java_box_height', 480 );
		$this->tmpl['uploadmaxsize'] 		= $params->get( 'upload_maxsize', 3145728 );
		$this->tmpl['uploadmaxsizeread'] 	= PhocaGalleryFile::getFileSizeReadable($this->tmpl['uploadmaxsize']);
		$this->tmpl['uploadmaxreswidth'] 	= $params->get( 'upload_maxres_width', 3072 );
		$this->tmpl['uploadmaxresheight'] 	= $params->get( 'upload_maxres_height', 2304 );
		$this->tmpl['enablejava'] 			= $params->get( 'enable_java', -1 );
		$this->tmpl['enablemultiple'] 		= $params->get( 'enable_multiple', 0 );
		$this->tmpl['multipleuploadmethod'] = $params->get( 'multiple_upload_method', 4 );
		$this->tmpl['multipleresizewidth'] 	= $params->get( 'multiple_resize_width', -1 );
		$this->tmpl['multipleresizeheight'] = $params->get( 'multiple_resize_height', -1 );

		$this->currentFolder = '';
		if (isset($this->folderstate->folder) && $this->folderstate->folder != '') {
			$this->currentFolder = $this->folderstate->folder;
		}
		
		// - - - - - - - - - -
		//TABS
		// - - - - - - - - - - 
		$this->tmpl['tab'] 			= JFactory::getApplication()->input->get('tab', '', '', 'string');
		$this->tmpl['displaytabs']	= 0;
		
		// UPLOAD
		$this->tmpl['currenttab']['upload'] = $this->tmpl['displaytabs'];
		$this->tmpl['displaytabs']++;
		
		// MULTIPLE UPLOAD
		if((int)$this->tmpl['enablemultiple']  >= 0) {
			$this->tmpl['currenttab']['multipleupload'] = $this->tmpl['displaytabs'];
			$this->tmpl['displaytabs']++;	
		}
	
		// MULTIPLE UPLOAD
		if($this->tmpl['enablejava']  >= 0) {
			$this->tmpl['currenttab']['javaupload'] = $this->tmpl['displaytabs'];
			$this->tmpl['displaytabs']++;	
		}

		// - - - - - - - - - - -
		// Upload
		// - - - - - - - - - - -
		$sU							= new PhocaGalleryFileUploadSingle();
		$sU->returnUrl				= 'index.php?option=com_phocagallery&view=phocagalleryi&tab=upload&tmpl=component&field='.$this->field.'&folder='. $this->currentFolder;
		$sU->tab					= 'upload';
		$this->tmpl['su_output']	= $sU->getSingleUploadHTML();
		$this->tmpl['su_url']		= JURI::base().'index.php?option=com_phocagallery&task=phocagalleryu.upload&amp;'
								  .$this->session->getName().'='.$this->session->getId().'&amp;'
								  . JSession::getFormToken().'=1&amp;viewback=phocagalleryi&amp;field='.$this->field.'&amp;'
								  .'folder='. $this->currentFolder.'&amp;tab=upload';
		
		
		// - - - - - - - - - - -
		// Multiple Upload
		// - - - - - - - - - - -
		// Get infos from multiple upload
		$muFailed						= JFactory::getApplication()->input->get( 'mufailed', '0', '', 'int' );
		$muUploaded						= JFactory::getApplication()->input->get( 'muuploaded', '0', '', 'int' );
		$this->tmpl['mu_response_msg']	= $muUploadedMsg 	= '';
		
		if ($muUploaded > 0) {
			$muUploadedMsg = JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded;
		}
		if ($muFailed > 0) {
			$muFailedMsg = JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed;
		}
		if ($muFailed > 0 && $muUploaded > 0) {
			$this->tmpl['mu_response_msg'] = '<div class="alert alert-info">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded .'<br />'
			.JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed.'</div>';
		} else if ($muFailed > 0 && $muUploaded == 0) {
			$this->tmpl['mu_response_msg'] = '<div class="alert alert-error">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCAGALLERY_COUNT_NOT_UPLOADED_IMG'). ': ' . $muFailed.'</div>';
		} else if ($muFailed == 0 && $muUploaded > 0){
			$this->tmpl['mu_response_msg'] = '<div class="alert alert-success">'
			.'<button type="button" class="close" data-dismiss="alert">&times;</button>'
			.JText::_('COM_PHOCAGALLERY_COUNT_UPLOADED_IMG'). ': ' . $muUploaded.'</div>';
		} else {
			$this->tmpl['mu_response_msg'] = '';
		}
		
		if((int)$this->tmpl['enablemultiple']  >= 0) {
		
			PhocaGalleryFileUploadMultiple::renderMultipleUploadLibraries();
			$mU						= new PhocaGalleryFileUploadMultiple();
			$mU->frontEnd			= 0;
			$mU->method				= $this->tmpl['multipleuploadmethod'];
			$mU->url				= JURI::base().'index.php?option=com_phocagallery&task=phocagalleryu.multipleupload&amp;'
									 .$this->session->getName().'='.$this->session->getId().'&'
									 . JSession::getFormToken().'=1&tab=multipleupload&field='.$this->field.'&folder='. $this->currentFolder;
			$mU->reload				= JURI::base().'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&'
									.$this->session->getName().'='.$this->session->getId().'&'
									. JSession::getFormToken().'=1&tab=multipleupload&'
									.'field='.$this->field.'&folder='. $this->currentFolder;
			$mU->maxFileSize		= PhocaGalleryFileUploadMultiple::getMultipleUploadSizeFormat($this->tmpl['uploadmaxsize']);
			$mU->chunkSize			= '1mb';
			$mU->imageHeight		= $this->tmpl['multipleresizeheight'];
			$mU->imageWidth			= $this->tmpl['multipleresizewidth'];
			$mU->imageQuality		= 100;
			$mU->renderMultipleUploadJS(0, $this->tmpl['multipleuploadchunk']);
			$this->tmpl['mu_output']= $mU->getMultipleUploadHTML();
		}
		
		// - - - - - - - - - - -
		// Java Upload
		// - - - - - - - - - - -
		if((int)$this->tmpl['enablejava']  >= 0) {
			$jU							= new PhocaGalleryFileUploadJava();
			$jU->width					= $this->tmpl['javaboxwidth'];
			$jU->height					= $this->tmpl['javaboxheight'];
			$jU->resizewidth			= $this->tmpl['multipleresizewidth'];
			$jU->resizeheight			= $this->tmpl['multipleresizeheight'];
			$jU->uploadmaxsize			= $this->tmpl['uploadmaxsize'];
			$jU->returnUrl				= JURI::base().'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&tab=javaupload&'
										.'field='.$this->field.'&folder='. $this->currentFolder;
			$jU->url					= JURI::base().'index.php?option=com_phocagallery&task=phocagalleryu.javaupload&amp;'
									 .$this->session->getName().'='.$this->session->getId().'&'
									 . JSession::getFormToken().'=1&amp;viewback=phocagalleryi&amp;tab=javaupload'
									 .'&field='.$this->field.'&folder='. $this->currentFolder;
			$jU->source 				= JURI::root(true).'/components/com_phocagallery/assets/jupload/wjhk.jupload.jar';
			$this->tmpl['ju_output']	= $jU->getJavaUploadHTML();
			
		}				  
		$this->tmpl['ftp'] 			= !JClientHelper::hasCredentials('ftp');

		parent::display($tpl);
		echo JHTML::_('behavior.keepalive');
	}

	function setFolder($index = 0) {
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0) {
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}
}
?>