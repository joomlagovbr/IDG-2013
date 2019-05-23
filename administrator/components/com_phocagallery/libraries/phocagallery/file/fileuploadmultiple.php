<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryFileUploadMultiple
{
	public $method 		= 1;
	public $url			= '';
	public $reload		= '';
	public $maxFileSize	= '';
	public $chunkSize	= '';
	public $imageHeight	= '';
	public $imageWidth	= '';
	public $imageQuality= '';
	public $frontEnd	= 0;

	public function __construct() {}

	static public function renderMultipleUploadLibraries() {

		$paramsC 		= JComponentHelper::getParams('com_phocagallery');
		$chunkMethod 	= $paramsC->get( 'multiple_upload_chunk', 0 );
		$uploadMethod 	= $paramsC->get( 'multiple_upload_method', 4 );

		//First load mootools, then jquery and set noConflict
		JHtml::_('behavior.framework', true);// Load it here to be sure, it is loaded before jquery
		JHtml::_('jquery.framework', false);// Load it here because of own nonConflict method (nonconflict is set below)
		$document	= JFactory::getDocument();
		// No more used  - - - - -
		//$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/jquery/jquery-1.6.4.min.js');//USE SYSTEM
		//$nC = 'var pgJQ =  jQuery.noConflict();';//SET BELOW
		//$document->addScriptDeclaration($nC);//SET BELOW
		// - - - - - - - - - - - -

		if ($uploadMethod == 2) {
			//$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/gears_init.js');
		}
		if ($uploadMethod == 5) {
			//$document->addScript('http://bp.yahooapis.com/2.4.21/browserplus-min.js');
		}
		$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.js');
		if ($uploadMethod == 2) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.gears.js');
		}
		if ($uploadMethod == 3) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.silverlight.js');
		}
		if ($uploadMethod == 1) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.flash.js');
		}
		if ($uploadMethod == 5) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.browserplus.js');
		}
		if ($uploadMethod == 6) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.html4.js');
		}
		if ($uploadMethod == 4) {
			$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.html5.js');
		}

		$document->addScript(JURI::root(true).'/components/com_phocagallery/assets/plupload/jquery.plupload.queue/jquery.plupload.queue.js');
		JHTML::stylesheet( 'components/com_phocagallery/assets/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css' );
	}

	static public function getMultipleUploadSizeFormat($size) {
		$readableSize = PhocaGalleryFile::getFileSizeReadable($size, '%01.0f %s', 1);

		$readableSize 	= str_replace(' ', '', $readableSize);

		$readableSize 	= strtolower($readableSize);
		return $readableSize;
	}

	public function renderMultipleUploadJS($frontEnd = 0, $chunkMethod = 0) {

		$document			= JFactory::getDocument();

		switch ($this->method) {
			case 2:
				$name		= 'gears_uploader';
				$runtime	= 'gears';
			break;
			case 3:
				$name		= 'silverlight_uploader';
				$runtime	= 'silverlight';
			break;
			case 4:
				$name		= 'html5_uploader';
				$runtime	= 'html5';
			break;

			case 5:
				$name		= 'browserplus_uploader';
				$runtime	= 'browserplus';
			break;

			case 6:
				$name		= 'html4_uploader';
				$runtime	= 'html4';
			break;

			case 1:
			default:
				$name		= 'flash_uploader';
				$runtime	= 'flash';
			break;
		}

		$chunkEnabled = 0;
		// Chunk only if is enabled and only if flash is enabled
		if (($chunkMethod == 1 && $this->method == 1) || ($this->frontEnd == 0 && $chunkMethod == 0 && $this->method == 1)) {
			$chunkEnabled = 1;
		}

        $this->url      = PhocaGalleryText::filterValue($this->url, 'text');
        $this->reload 	= PhocaGalleryText::filterValue($this->reload, 'text');
		$this->url 		= str_replace('&amp;', '&', $this->url);
		$this->reload 	= str_replace('&amp;', '&', $this->reload);




		//$js = ' var pgJQ = jQuery.noConflict();';
		$js = 'var pgJQ =  jQuery.noConflict();';
		$js .=' pgJQ(function() {'."\n";

		$js.=''."\n";
		$js.='   plupload.addI18n({'."\n";
		$js.='	   \'Select files\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_SELECT_IMAGES')).'\','."\n";
		$js.='	   \'Add files to the upload queue and click the start button.\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_ADD_IMAGES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON')).'\','."\n";
		$js.='	   \'Filename\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_FILENAME')).'\','."\n";
		$js.='	   \'Status\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_STATUS')).'\','."\n";
		$js.='	   \'Size\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_SIZE')).'\','."\n";
		$js.='	   \'Add files\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_ADD_IMAGES')).'\','."\n";
		$js.='	   \'Start upload\':\''.addslashes(JText::_('COM_PHOCAGALLERY_START_UPLOAD')).'\','."\n";
		$js.='	   \'Stop current upload\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_STOP_CURRENT_UPLOAD')).'\','."\n";
		$js.='	   \'Start uploading queue\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_START_UPLOADING_QUEUE')).'\','."\n";
		$js.='	   \'Drag files here.\' : \''.addslashes(JText::_('COM_PHOCAGALLERY_DRAG_FILES_HERE')).'\''."\n";
		$js.='   });';
		$js.=''."\n";

		$js.='	pgJQ("#'.$name.'").pluploadQueue({'."\n";
		$js.='		runtimes : \''.$runtime.'\','."\n";
		$js.='		url : \''.$this->url.'\','."\n";
		//$js.='		max_file_size : \''.$this->maxFileSize.'\','."\n";

		if ($this->maxFileSize != '0b') {
			$js.='		max_file_size : \''.$this->maxFileSize.'\','."\n";
		}

		if ($chunkEnabled == 1) {
			$js.='		chunk_size : \'1mb\','."\n";
		}
		$js.='      preinit: attachCallbacks,'."\n";
		$js.='		unique_names : false,'."\n";
		$js.='		multipart: true,'."\n";
		$js.='		filters : ['."\n";
		$js.='			{title : "'.JText::_('COM_PHOCAGALLERY_IMAGE_FILES').'", extensions : "jpg,gif,png,jpeg,webp"}'."\n";
		//$js.='			{title : "Zip files", extensions : "zip"}'."\n";
		$js.='		],'."\n";
		$js.=''."\n";
		if ($this->method != 6) {
			if ((int)$this->imageWidth > 0 || (int)$this->imageWidth > 0) {
				$js.='		resize : {width : '.$this->imageWidth.', height : '.$this->imageHeight.', quality : '.$this->imageQuality.'},'."\n";
				$js.=''."\n";
			}
		}
		if ($this->method == 1) {
			$js.='		flash_swf_url : \''.JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.flash.swf\''."\n";
		} else if ($this->method == 3) {
			$js.='		silverlight_xap_url : \''.JURI::root(true).'/components/com_phocagallery/assets/plupload/plupload.silverlight.xap\''."\n";
		}
		$js.='	});'."\n";

		$js.=''."\n";

		$js.='function attachCallbacks(Uploader) {'."\n";
		$js.='	Uploader.bind(\'FileUploaded\', function(Up, File, Response) {'."\n";
		$js.='		var obj = eval(\'(\' + Response.response + \')\');'."\n";
		if ($this->method == 6) {
			$js.='		var queueFiles = Uploader.total.failed + Uploader.total.uploaded;'."\n";
			$js.='		var uploaded0 = Uploader.total.uploaded;'."\n";
		} else {
			$js.='		var queueFiles = Uploader.total.failed + Uploader.total.uploaded + 1;'."\n";
			$js.='		var uploaded0 = Uploader.total.uploaded + 1;'."\n";
		}
		$js.=''."\n";
		$js.='		if ((typeof(obj.result) != \'undefined\') && obj.result == \'error\') {'."\n";
		$js.='			'."\n";
		if ($this->method == 6) {
			//$js.='		var uploaded0 = Uploader.total.uploaded;'."\n";
		} else {
			//$js.='		var uploaded0 = Uploader.total.uploaded + 1;'."\n";
		}
		$js.='			Up.trigger("Error", {message : obj.message, code : obj.code, details : obj.details, file: File});'."\n";
		$js.='				if( queueFiles == Uploader.files.length) {'."\n";
		if ($this->method == 6) {
			$js.='		var uploaded0 = Uploader.total.uploaded;'."\n";
		} else {
			$js.='		var uploaded0 = Uploader.total.uploaded;'."\n";
		}
		$js.='					window.location = \''.$this->reload.'\' + \'&muuploaded=\' + uploaded0 + \'&mufailed=\' + Uploader.total.failed;'."\n";
		//$js.='					alert(\'Error\' + obj.message)'."\n";
		$js.='				}'."\n";
		$js.='				return false; '."\n";
		$js.=''."\n";
		$js.='		} else {'."\n";
		$js.='			if( queueFiles == Uploader.files.length) {'."\n";
		//$js.='				var uploaded = Uploader.total.uploaded + 1;'."\n";
		if ($this->method == 6) {
			$js.='		var uploaded = Uploader.total.uploaded;'."\n";
		} else {
			$js.='		var uploaded = Uploader.total.uploaded + 1;'."\n";
		}
		$js.='				window.location = \''.$this->reload.'\' + \'&muuploaded=\' + uploaded + \'&mufailed=\' + Uploader.total.failed;'."\n";
		//$js.='					alert(\'OK\' + obj.message)'."\n";
		$js.='			}'."\n";
		$js.='		}'."\n";
		$js.='	});'."\n";
		$js.='	'."\n";
		$js.='    Uploader.bind(\'Error\', function(Up, ErrorObj) {'."\n";
		$js.=''."\n";
	//	$js.='         if (ErrorObj.code == 100) { '."\n";
		$js.='			pgJQ(\'#\' + ErrorObj.file.id).append(\'<div class="alert alert-error">\'+ ErrorObj.message + ErrorObj.details +\'</div>\');'."\n";
	//	$js.='         }'."\n";
		$js.='    });	'."\n";
		$js.='}';

		$js.='});'."\n";// End $(function()

		$document->addScriptDeclaration($js);


	}

	public function getMultipleUploadHTML($width = '', $height = '330', $mootools = 1) {


		switch ($this->method) {
			case 2:
				$name		= 'gears_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_INSTALLED_GEARS');
			break;
			case 3:
				$name		= 'silverlight_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_INSTALLED_SILVERLIGHT');
			break;
			case 4:
				$name		= 'html5_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_SUPPORTED_HTML5');
			break;

			case 5:
				$name		= 'browserplus_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_INSTALLED_BROWSERPLUS');
			break;

			case 6:
				$name		= 'html4_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_SUPPORTED_HTML4');
			break;

			case 1:
			default:
				$name		= 'flash_uploader';
				$msg		= JText::_('COM_PHOCAGALLERY_NOT_INSTALLED_FLASH');
			break;
		}

		$style				= '';
		if ($width != '') {
			$style	.= 'width: '.(int)$width.'px;';
		}
		if ($height != '') {
			$style	.= 'height: '.(int)$height.'px;';
		}

		return '<div id="'.$name.'" style="'.$style.'">'.$msg.'</div>';

	}
}
?>
