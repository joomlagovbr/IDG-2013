<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$task		= 'phocagalleryimg';

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$r 			=  new PhocaGalleryRenderAdminView();
$app		= JFactory::getApplication();
$option 	= $app->input->get('option');
$OPT		= strtoupper($option);

?>
<script type="text/javascript">
Joomla.submitbutton = function(task){
	if (task != 'phocagalleryimg.cancel' && document.id('jform_catid').value == '') {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')) . ' - '. $this->escape(JText::_('COM_PHOCAGALLERY_CATEGORY_NOT_SELECTED'));?>');
	} else if (task == 'phocagalleryimg.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		<?php echo $this->form->getField('description')->save(); ?>
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else {
		<?php /* Joomla.renderMessages({"error": ["<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>"]});
		 alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>'); */ ?>
		
		// special case for modal popups validation response
		jQuery('#adminForm .modal-value.invalid').each(function(){
			
			var field = jQuery(this),
				idReversed = field.attr('id').split('').reverse().join(''),
				separatorLocation = idReversed.indexOf('_'),
				nameId = '#' + idReversed.substr(separatorLocation).split('').reverse().join('') + 'name';
			alert(nameId);
			jQuery(nameId).addClass('invalid');
		});
		
	}
}
</script><?php
echo $r->startForm($option, $task, $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($OPT.'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($OPT.'_PUBLISHING_OPTIONS'),
'geo' 			=> JText::_($OPT.'_GEO_OPTIONS'),
'external'		=> JText::_($OPT.'_EXTERNAL_LINK_OPTIONS'),
'metadata'		=> JText::_($OPT.'_METADATA_OPTIONS'));
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n"; 
$formArray = array ('title', 'alias', 'catid', 'ordering', 'filename', 'videocode', 'pcproductid', 'vmproductid');
echo $r->group($this->form, $formArray);

echo $this->form->getInput('extid');

$formArray = array('description');
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="publishing">'."\n"; 
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo '</div>';

echo '<div class="tab-pane" id="geo">'. "\n";
$formArray = array ('latitude', 'longitude', 'zoom', 'geotitle');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="external">'. "\n";
echo '<div class="clearfix"></div>'. "\n";
echo '<h3>'.JText::_('COM_PHOCAGALLERY_EXTERNAL_LINKS1').'</h3>'."\n";
$formArray = array ('extlink1link', 'extlink1title', 'extlink1target', 'extlink1icon');
echo $r->group($this->form, $formArray);

echo '<div class="clearfix"></div>'. "\n";
echo '<h3>'.JText::_('COM_PHOCAGALLERY_EXTERNAL_LINKS2').'</h3>'."\n";
$formArray = array ('extlink2link', 'extlink2title', 'extlink2target', 'extlink2icon');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="metadata">'. "\n";
echo $this->loadTemplate('metadata');
echo '</div>'. "\n";

echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span2">';
				
// - - - - - - - - - -
// Image

$fileOriginal = PhocaGalleryFile::getFileOriginal($this->item->filename);
if (!JFile::exists($fileOriginal)) {
	$this->item->fileoriginalexist = 0;
} else {
	$fileThumb 		= PhocaGalleryFileThumbnail::getOrCreateThumbnail($this->item->filename, '', 0, 0, 0);
	$this->item->linkthumbnailpath 	= $fileThumb['thumb_name_m_no_rel'];
	$this->item->fileoriginalexist = 1;	
}

echo '<div style="float:right;margin:5px;">';
// PICASA
if (isset($this->item->extid) && $this->item->extid !='') {									
	
	$resW				= explode(',', $this->item->extw);
	$resH				= explode(',', $this->item->exth);
	$correctImageRes 	= PhocaGalleryImage::correctSizeWithRate($resW[2], $resH[2], 100, 100);
	$imgLink			= $this->item->extl;
	
	echo '<img class="img-polaroid" src="'.$this->item->exts.'" width="'.$correctImageRes['width'].'" height="'.$correctImageRes['height'].'" alt="" />';
	
} else if (isset ($this->item->fileoriginalexist) && $this->item->fileoriginalexist == 1) {
	
	$imageRes			= PhocaGalleryImage::getRealImageSize($this->item->filename, 'medium');
	//$correctImageRes 	= PhocaGalleryImage::correctSizeWithRate($imageRes['w'], $imageRes['h'], 100, 100);
	$imgLink			= PhocaGalleryFileThumbnail::getThumbnailName($this->item->filename, 'large');
	// TO DO check the image

	echo '<img class="img-polaroid" style="max-width:100px;" src="'.JURI::root().$this->item->linkthumbnailpath.'?imagesid='.md5(uniqid(time())).'" alt="" />'
	.'</a>';
} else {
	
}
echo '</div>';


echo '</div>';//end span2
echo $r->formInputs();
echo $r->endForm();
?>		
