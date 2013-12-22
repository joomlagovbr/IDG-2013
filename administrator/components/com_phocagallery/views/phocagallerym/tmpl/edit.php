<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

$task		= 'phocagallerym';

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$r 			=  new PhocaGalleryRenderAdminView();
$app		= JFactory::getApplication();
$option 	= $app->input->get('option');
$tasks		= $task . 's';

// phocagallerym-form renamed to adminForm because of used Joomla! javascript and its fixed value.
?><script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		
		if (task == 'phocagallerym.cancel') {
			submitform(task);
		}

		if (task == 'phocagallerym.save') {
			phocagallerymform = document.getElementById('adminForm');
			
			if (phocagallerymform.boxchecked.value==0) {
				alert( "<?php echo JText::_( 'COM_PHOCAGALLERY_WARNING_SELECT_FILENAME_OR_FOLDER', true ); ?>" );
			} else  {
				var f = phocagallerymform;
				var nSelectedImages = 0;
				var nSelectedFolders = 0;
				var i=0;
				cb = eval( 'f.cb' + i );
				while (cb) {
					if (cb.checked == false) {
						// Do nothing
					}
					else if (cb.name == "cid[]") {
						nSelectedImages++;
					}
					else {
						nSelectedFolders++;
					}
					// Get next
					i++;
					cb = eval( 'f.cb' + i );
				}
				
				if (phocagallerymform.jform_catid.value == "" && nSelectedImages > 0){
					alert( "<?php echo JText::_( 'COM_PHOCAGALLERY_WARNING_IMG_SELECTED_SELECT_CATEGORY', true ); ?>" );
				} else {
					submitform(task);
				}
			}
		}
		//submitform(task);
	}
</script><?php
echo '<div class="phoca-thumb-status">' . $this->tmpl['enablethumbcreationstatus'] .'</div>';

echo $r->startForm($option, $task, 'adminForm', 'adminForm');
echo '<div class="span4 form-horizontal" style="border-right: 1px solid #d3d3d3;padding-right: 5px; margin-right: 5px;">';
echo '<h4>'. JText::_('COM_PHOCAGALLERY_MULTIPLE_ADD'). '</h4>';

echo '<div>'."\n"; 
$formArray = array ('title', 'alias','published', 'approved', 'ordering', 'catid', 'language');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";

echo '</div>';


echo '<div class="span8 form-horizontal">';

echo '<div class="ph-admin-path">' . JText::_('COM_PHOCAGALLERY_PATH'). ': '.JPath::clean($this->path->image_abs. $this->folderstate->folder) .'</div>';

$countFaF =  count($this->images) + count($this->folders);
echo '<table class="table table-hover table-condensed ph-multiple-table">'
.'<thead>'
.'<tr>';
echo '<th class="hidden-phone ph-check">'. "\n"
.'<input type="checkbox" name="checkall-toggle" value="" title="'.JText::_('JGLOBAL_CHECK_ALL').'" onclick="Joomla.checkAll(this)" />'. "\n"
.'</th>'. "\n";
echo '<th width="20">&nbsp;</th>'
.'<th width="95%">'.JText::_( 'COM_PHOCAGALLERY_FILENAME' ).'</th>'
.'</tr>'
.'</thead>';

echo '<tbody>';
$link = 'index.php?option=com_phocagallery&amp;view=phocagallerym&amp;layout=edit&amp;hidemainmenu=1&amp;folder='.$this->folderstate->parent;
echo '<tr><td>&nbsp;</td>'
.'<td class="ph-img-table">'
.'<a href="'.$link.'" >'
. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-up.png', '').'</a>'
.'</td>'
.'<td><a href="'.$link.'" >..</a></td>'
.'</tr>';
			
if (count($this->images) > 0 || count($this->folders) > 0) {
	//FOLDERS
	for ($i = 0, $n = count($this->folders); $i<$n; $i++) {
		$checked 	= JHtml::_( 'grid.id', $i, $this->folders[$i]->path_with_name_relative_no, false, 'foldercid' );
		//$checked 	= PhocaGalleryGrid::id( $i, $this->folders[$i]->path_with_name_relative_no, false, 'foldercid' );
		$link		= 'index.php?option=com_phocagallery&view=phocagallerym&layout=edit&hidemainmenu=1&folder='
					  .$this->folders[$i]->path_with_name_relative_no;
		echo '<tr>'
			.' <td>'. $checked .'</td>'
			.' <td class="ph-img-table"><a href="'. JRoute::_( $link ).'">'
			. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-folder-small.gif', '').'</a></td>'
			.' <td><a href="'. JRoute::_( $link ).'">'. $this->folders[$i]->name.'</a></td>'
			.'</tr>';
	}
				
	//IMAGES
	for ($i = 0,$n = count($this->images); $i<$n; $i++) {
		$row 		= &$this->images[$i];
		$checked 	= JHtml::_( 'grid.id', $i+count($this->folders), $this->images[$i]->nameno);
		//$checked	= '<input type="checkbox" name="cid[]" value="'.$i.'" />';
		echo '<tr>'
			.' <td>'. $checked .'</td>'
			.' <td class="ph-img-table">'
			. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-image-small.gif', '').'</td>'
			.' <td>'.$this->images[$i]->nameno.'</td>'
			.'</tr>';
	}
} else { 
	echo '<tr>'
	.'<td>&nbsp;</td>'
	.'<td>&nbsp;</td>'
	.'<td>'.JText::_( 'COM_PHOCAGALLERY_THERE_IS_NO_IMAGE' ).'</td>'
	.'</tr>';			

}
echo '</tbody>'
.'</table>';

echo '<input type="hidden" name="task" value="" />'. "\n";
echo '<input type="hidden" name="boxchecked" value="0" />'. "\n";
echo '<input type="hidden" name="layout" value="edit" />'. "\n";
echo JHtml::_('form.token');
echo $r->endForm();

echo '</div>';
echo '<div class="clearfix"></div>';

if ($this->tmpl['displaytabs'] > 0) {

	echo '<ul class="nav nav-tabs" id="configTabs">';
	
	$label = JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_UPLOAD');
	echo '<li><a href="#upload" data-toggle="tab">'.$label.'</a></li>';
	
	if((int)$this->tmpl['enablemultiple']  >= 0) {
		$label = JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-multiple.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_MULTIPLE_UPLOAD');
		echo '<li><a href="#multipleupload" data-toggle="tab">'.$label.'</a></li>';
	}
	
	if($this->tmpl['enablejava'] >= 0) {
	
		$label = JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-java.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_JAVA_UPLOAD');
		echo '<li><a href="#javaupload" data-toggle="tab">'.$label.'</a></li>';
	}
	$label = JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-folder.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_CREATE_FOLDER');
	echo '<li><a href="#createfolder" data-toggle="tab">'.$label.'</a></li>';
	
	echo '</ul>';
	
	echo '<div class="tab-content">'. "\n";
	
	echo '<div class="tab-pane" id="upload">'. "\n";
	echo $this->loadTemplate('upload');
	echo '</div>'. "\n";
	echo '<div class="tab-pane" id="multipleupload">'. "\n";
	echo $this->loadTemplate('multipleupload');
	echo '</div>'. "\n";
	echo '<div class="tab-pane" id="javaupload">'. "\n";
	echo $this->loadTemplate('javaupload');
	echo '</div>'. "\n";
	
	echo '<div class="tab-pane" id="createfolder">'. "\n";
	echo PhocaGalleryFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $this->currentFolder, 'phocagallerym', 'tab=createfolder' );
	echo '</div>'. "\n";
	
	echo '</div>'. "\n";
}


if ($this->tmpl['tab'] != '') {$jsCt = 'a[href=#'.$this->tmpl['tab'] .']';} else {$jsCt = 'a:first';}
echo '<script type="text/javascript">';
echo '   jQuery(\'#configTabs '.$jsCt.'\').tab(\'show\');'; // Select first tab
echo '</script>';
?>
