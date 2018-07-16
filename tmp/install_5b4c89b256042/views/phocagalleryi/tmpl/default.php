<?php 
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>

<?php echo $this->loadTemplate('up'); ?>
<?php if (count($this->images) > 0 || count($this->folders) > 0) { ?>
<div>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0,$n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('image');
		endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'COM_PHOCAGALLERY_THERE_IS_NO_IMAGE' ); ?>
	</center>
</div>
<?php } ?>

<div style="clear:both">
<div style="border-bottom:1px solid #cccccc;margin-bottom: 10px">&nbsp;</div>

<?php
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
	echo PhocaGalleryFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $this->currentFolder, 'phocagalleryi', 'tab=createfolder&amp;field='.$this->field );
	echo '</div>'. "\n";
	
	echo '</div>'. "\n";
}
?>



<?php
/*
if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocagallery-pane">';
	//$pane =& J Pane::getInstance('Tabs', array('startOffset'=> $this->tmpl['tab']));
	echo JHtml::_('tabs.start', 'config-tabs-com_phocagallery-i', array('useCookie'=>1, 'startOffset'=> $this->tmpl['tab']));
	//echo $pane->startPane( 'pane' );

	//echo $pane->startPanel( JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_UPLOAD'), 'upload' );
	echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_UPLOAD'), 'upload' );
	echo $this->loadTemplate('upload');
	//echo $pane->endPanel();
	
	if((int)$this->tmpl['enablemultiple']  >= 0) {
		//echo $pane->startPanel( JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-multiple.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_MULTIPLE_UPLOAD'), 'multipleupload' );
		echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-multiple.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_MULTIPLE_UPLOAD'), 'multipleupload' );
		echo $this->loadTemplate('multipleupload');
		//echo $pane->endPanel();
	}

	if($this->tmpl['enablejava'] >= 0) {
		//echo $pane->startPanel( JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-java.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_JAVA_UPLOAD'), 'javaupload' );
		echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/administrator/icon-16-upload-java.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_JAVA_UPLOAD'), 'javaupload' );
		echo $this->loadTemplate('javaupload');
		//echo $pane->endPanel();
	}

	//echo $pane->endPane();
	echo JHtml::_('tabs.end');
	echo '</div>';// end phocagallery-pane
}
*/

//TEMP
//$this->tmpl['tab'] = 'multipleupload';
if ($this->tmpl['tab'] != '') {$jsCt = 'a[href=#'.$this->tmpl['tab'] .']';} else {$jsCt = 'a:first';}
echo '<script type="text/javascript">';
echo '   jQuery(\'#configTabs '.$jsCt.'\').tab(\'show\');'; // Select first tab
echo '</script>';
?>
